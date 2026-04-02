"""
Lokativ – Service WhatsApp natif
================================
Microservice Flask qui pilote WhatsApp Web via Selenium /
undetected-chromedriver pour envoyer des documents PDF sans API tierce.

Endpoints :
  GET  /status     → {"status": "disconnected|waiting_qr|connected"}
  GET  /qr         → {"status": "...", "qr": "<base64 PNG>|null"}
  POST /connect    → lance le navigateur
  POST /send       → {"telephone":"...", "pdf_base64":"...", "filename":"...", "caption":"..."}
  POST /disconnect → ferme le navigateur

Démarrage :
  python service.py
  (ou : scripts/whatsapp/start.bat sous Windows)
"""

import base64
import io
import logging
import os
import re
import subprocess
import tempfile
import threading
import time
import urllib.parse

from flask import Flask, jsonify, request, Response, abort

try:
    from selenium import webdriver
    from selenium.webdriver.chrome.service import Service as ChromeService
    from selenium.webdriver.chrome.options import Options as ChromeOptions
    from selenium.webdriver.common.by import By
    from selenium.webdriver.support import expected_conditions as EC
    from selenium.webdriver.support.ui import WebDriverWait
    from webdriver_manager.chrome import ChromeDriverManager
except ImportError as e:
    raise RuntimeError(
        f"Dépendances manquantes : {e}\n"
        "Installez-les avec : pip install -r requirements.txt"
    )

# ---------------------------------------------------------------------------
# Config
# ---------------------------------------------------------------------------
HOST = "127.0.0.1"
PORT = 5050
PROFILE_DIR = os.path.join(os.path.dirname(os.path.abspath(__file__)), "chrome_profile")
QR_POLL_INTERVAL = 2   # secondes entre chaque vérification du QR
SEND_TIMEOUT = 45      # secondes d'attente max lors d'un envoi

# ---------------------------------------------------------------------------
# Logging
# ---------------------------------------------------------------------------
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(message)s",
)
logger = logging.getLogger("whatsapp-service")

# ---------------------------------------------------------------------------
# État global
# ---------------------------------------------------------------------------
driver = None
driver_lock = threading.Lock()
_status = "disconnected"   # disconnected | waiting_qr | connected
_qr_base64 = None
_monitor_thread = None
_last_error = None          # Dernière erreur pour diagnostic


def set_status(s):
    global _status
    _status = s
    logger.info(f"Status → {s}")


# ---------------------------------------------------------------------------
# Détection de la version Chrome installée (Windows)
# ---------------------------------------------------------------------------
def _get_chrome_major_version():
    """Retourne le numéro de version majeure de Chrome (ex: 145), ou None."""
    # 1) Registre Windows
    reg_keys = [
        r"HKLM\SOFTWARE\Wow6432Node\Microsoft\Windows\CurrentVersion\Uninstall\Google Chrome",
        r"HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\Google Chrome",
        r"HKCU\SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\Google Chrome",
    ]
    for key in reg_keys:
        try:
            out = subprocess.check_output(
                ["reg", "query", key, "/v", "Version"],
                stderr=subprocess.DEVNULL, timeout=5
            ).decode(errors="ignore")
            m = re.search(r"Version\s+REG_SZ\s+(\d+)", out)
            if m:
                v = int(m.group(1))
                logger.info(f"Chrome version (registre) : {v}")
                return v
        except Exception:
            pass

    # 2) Exécutable chrome.exe --version
    chrome_paths = [
        r"C:\Program Files\Google\Chrome\Application\chrome.exe",
        r"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe",
        os.path.expandvars(r"%LOCALAPPDATA%\Google\Chrome\Application\chrome.exe"),
    ]
    for path in chrome_paths:
        if os.path.exists(path):
            try:
                out = subprocess.check_output(
                    [path, "--version"], stderr=subprocess.DEVNULL, timeout=5
                ).decode(errors="ignore")
                m = re.search(r"(\d+)\.", out)
                if m:
                    v = int(m.group(1))
                    logger.info(f"Chrome version (exe) : {v}")
                    return v
            except Exception:
                pass

    logger.warning("Impossible de détecter la version Chrome.")
    return None


# ---------------------------------------------------------------------------
# Initialisation du navigateur
# ---------------------------------------------------------------------------
def _init_driver():
    global driver, _qr_base64, _monitor_thread, _last_error

    # --- Étape 1 : démarrer Chrome via webdriver-manager ---
    logger.info("Étape 1 : démarrage de Chrome avec webdriver-manager…")
    try:
        os.makedirs(PROFILE_DIR, exist_ok=True)

        options = ChromeOptions()
        options.add_argument(f"--user-data-dir={PROFILE_DIR}")
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        options.add_argument("--disable-gpu")
        options.add_argument("--window-size=1280,900")
        options.add_argument("--disable-blink-features=AutomationControlled")
        options.add_argument("--disable-extensions")
        options.add_experimental_option("excludeSwitches", ["enable-automation"])
        options.add_experimental_option("useAutomationExtension", False)
        options.add_argument(
            "user-agent=Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
            "AppleWebKit/537.36 (KHTML, like Gecko) "
            "Chrome/146.0.0.0 Safari/537.36"
        )

        # webdriver-manager télécharge automatiquement le bon ChromeDriver
        service = ChromeService(ChromeDriverManager().install())

        with driver_lock:
            driver = webdriver.Chrome(service=service, options=options)
            # Masquer navigator.webdriver pour éviter la détection
            driver.execute_cdp_cmd("Page.addScriptToEvaluateOnNewDocument", {
                "source": "Object.defineProperty(navigator, 'webdriver', {get: () => undefined})"
            })

        logger.info("Chrome démarré avec succès.")

    except Exception as exc:
        _last_error = f"Échec démarrage Chrome : {exc}"
        logger.error(_last_error)
        driver = None
        set_status("disconnected")
        return

    # --- Étape 2 : charger WhatsApp Web ---
    logger.info("Étape 2 : chargement de https://web.whatsapp.com …")
    try:
        driver.get("https://web.whatsapp.com")
        logger.info("Page WhatsApp Web chargée.")
    except Exception as exc:
        _last_error = f"Navigation échouée (Chrome reste actif) : {exc}"
        logger.warning(_last_error)

    set_status("waiting_qr")

    # --- Étape 3 : lancer le thread de surveillance ---
    _monitor_thread = threading.Thread(target=_monitor_loop, daemon=True, name="wa-monitor")
    _monitor_thread.start()
    logger.info("Thread de surveillance démarré. En attente du scan QR.")


# JavaScript injecté dans le navigateur pour extraire le QR depuis le canvas
_JS_GET_QR = """
(function() {
    var selectors = [
        'canvas[aria-label="Scan this QR code to link a device"]',
        'canvas[aria-label="Scan me!"]',
        'div[data-ref] canvas',
        '[data-testid="qrcode"] canvas',
        'div[role="img"] canvas',
        'canvas'
    ];
    for (var i = 0; i < selectors.length; i++) {
        var el = document.querySelector(selectors[i]);
        if (el && el.width > 50 && el.height > 50) {
            try {
                var data = el.toDataURL('image/png');
                if (data && data.length > 100) {
                    return data.split(',')[1];
                }
            } catch(e) {}
        }
    }
    return null;
})();
"""

_JS_IS_CONNECTED = """
(function() {
    var selectors = [
        '[data-testid="chat-list-search"]',
        '[aria-label="Search input textbox"]',
        'div[aria-label="Chat list"]',
        '#side'
    ];
    for (var i = 0; i < selectors.length; i++) {
        if (document.querySelector(selectors[i])) return true;
    }
    return false;
})();
"""


# ---------------------------------------------------------------------------
# Thread de surveillance
# ---------------------------------------------------------------------------
def _monitor_loop():
    global driver, _qr_base64

    while True:
        time.sleep(QR_POLL_INTERVAL)

        if driver is None:
            set_status("disconnected")
            break

        try:
            with driver_lock:
                if driver is None:
                    break

                url = driver.current_url or ""

                # --- Connecté ? (chercher la barre latérale) ---
                try:
                    is_connected = driver.execute_script(_JS_IS_CONNECTED)
                    if is_connected:
                        _qr_base64 = None
                        set_status("connected")
                        continue
                except Exception:
                    pass

                # --- Screenshot et redimensionnement pour réduire la taille ---
                if "web.whatsapp.com" in url:
                    try:
                        png_bytes = driver.get_screenshot_as_png()
                        # Redimensionner à 700px de large max (réduit ~70% la taille)
                        try:
                            from PIL import Image
                            img_pil = Image.open(io.BytesIO(png_bytes))
                            max_w = 700
                            if img_pil.width > max_w:
                                ratio = max_w / img_pil.width
                                new_h = int(img_pil.height * ratio)
                                img_pil = img_pil.resize((max_w, new_h), Image.LANCZOS)
                            buf = io.BytesIO()
                            img_pil.save(buf, format="PNG", optimize=True)
                            png_bytes = buf.getvalue()
                        except Exception:
                            pass  # Pillow absent → garder l'original

                        shot = base64.b64encode(png_bytes).decode()
                        _qr_base64 = shot
                        set_status("waiting_qr")
                        logger.info(f"Screenshot capturé ({len(png_bytes)//1024} KB)")
                    except Exception as e:
                        logger.debug(f"Screenshot échoué : {e}")

        except Exception as exc:
            msg = str(exc).lower()
            if any(k in msg for k in ("invalid session", "no such window", "disconnected")):
                logger.warning("Navigateur fermé de manière inattendue.")
                driver = None
                _qr_base64 = None
                set_status("disconnected")
                break


# ---------------------------------------------------------------------------
# Envoi du document via WhatsApp Web
# ---------------------------------------------------------------------------
def _do_send(telephone: str, caption: str, file_path: str) -> dict:
    try:
        wait = WebDriverWait(driver, SEND_TIMEOUT)

        # Normaliser le numéro
        numero = telephone.lstrip("+").replace(" ", "").replace("-", "")
        url = f"https://web.whatsapp.com/send?phone={numero}"
        if caption:
            url += f"&text={urllib.parse.quote(caption)}"

        driver.get(url)
        time.sleep(4)  # laisser WhatsApp Web charger la conversation

        # Vérifier que la fenêtre de conversation est ouverte
        try:
            wait.until(EC.presence_of_element_located((
                By.CSS_SELECTOR,
                '[data-testid="conversation-compose-box-input"],'
                'footer [contenteditable="true"]',
            )))
        except Exception:
            return {"status": False, "message": "Conversation introuvable. Vérifiez le numéro de téléphone."}

        # Cliquer sur le bouton "Joindre un fichier"
        try:
            attach = wait.until(EC.element_to_be_clickable((
                By.CSS_SELECTOR,
                '[data-testid="clip"],'
                'span[data-icon="attach-menu-plus"],'
                'button[title="Attach"]',
            )))
            attach.click()
        except Exception:
            return {"status": False, "message": "Impossible de trouver le bouton d'attachement."}

        time.sleep(1)

        # Chercher l'input file pour les documents
        try:
            file_input = wait.until(EC.presence_of_element_located((
                By.CSS_SELECTOR,
                'input[type="file"][accept*="*"],'
                'input[type="file"][accept*="application"]',
            )))
            file_input.send_keys(file_path)
        except Exception:
            return {"status": False, "message": "Impossible d'ouvrir le sélecteur de fichier."}

        time.sleep(3)  # attendre la prévisualisation du fichier

        # Envoyer
        try:
            send_btn = wait.until(EC.element_to_be_clickable((
                By.CSS_SELECTOR,
                '[data-testid="send"],'
                'span[data-icon="send"]',
            )))
            send_btn.click()
        except Exception:
            return {"status": False, "message": "Impossible de cliquer sur Envoyer."}

        time.sleep(2)
        logger.info(f"Document envoyé à {telephone}")
        return {"status": True, "message": "Document envoyé avec succès via WhatsApp"}

    except Exception as exc:
        return {"status": False, "message": f"Erreur lors de l'envoi : {exc}"}


def _do_send_text(telephone: str, message: str) -> dict:
    """Envoie un message texte (sans fichier joint) via WhatsApp Web."""
    try:
        wait = WebDriverWait(driver, SEND_TIMEOUT)

        numero = telephone.lstrip("+").replace(" ", "").replace("-", "")
        url = f"https://web.whatsapp.com/send?phone={numero}&text={urllib.parse.quote(message)}"

        driver.get(url)
        time.sleep(4)

        # Vérifier que la fenêtre de conversation est ouverte
        try:
            wait.until(EC.presence_of_element_located((
                By.CSS_SELECTOR,
                '[data-testid="conversation-compose-box-input"],'
                'footer [contenteditable="true"]',
            )))
        except Exception:
            return {"status": False, "message": "Conversation introuvable. Vérifiez le numéro de téléphone."}

        # Cliquer sur Envoyer
        try:
            send_btn = wait.until(EC.element_to_be_clickable((
                By.CSS_SELECTOR,
                '[data-testid="send"],'
                'span[data-icon="send"]',
            )))
            send_btn.click()
        except Exception:
            return {"status": False, "message": "Impossible de cliquer sur Envoyer."}

        time.sleep(2)
        logger.info(f"Message texte envoyé à {telephone}")
        return {"status": True, "message": "Message envoyé avec succès via WhatsApp"}

    except Exception as exc:
        return {"status": False, "message": f"Erreur lors de l'envoi texte : {exc}"}


# ---------------------------------------------------------------------------
# Flask app
# ---------------------------------------------------------------------------
app = Flask(__name__)


@app.route("/status")
def route_status():
    return jsonify({"status": _status})


@app.route("/qr")
def route_qr():
    return jsonify({"status": _status, "qr": _qr_base64})


@app.route("/connect", methods=["POST"])
def route_connect():
    global driver, _qr_base64, _monitor_thread
    if _status == "connected":
        return jsonify({"status": True, "message": "Déjà connecté"})

    # Détecter état bloqué : driver actif mais monitor mort ou statut pas waiting_qr
    if driver is not None:
        monitor_dead = (_monitor_thread is None or not _monitor_thread.is_alive())
        if _status != "waiting_qr" or monitor_dead:
            logger.info("État bloqué détecté — réinitialisation du driver…")
            try:
                with driver_lock:
                    driver.quit()
            except Exception:
                pass
            driver      = None
            _qr_base64  = None
            set_status("disconnected")
        else:
            # Vraiment en attente de scan QR (monitor vivant)
            return jsonify({"status": True, "message": "Connexion en cours, scannez le QR code."})

    threading.Thread(target=_init_driver, daemon=True).start()
    return jsonify({"status": True, "message": "Démarrage du navigateur WhatsApp Web…"})


@app.route("/send", methods=["POST"])
def route_send():
    global driver

    if _status != "connected" or driver is None:
        return jsonify({
            "status": False,
            "message": "WhatsApp non connecté. Scannez le QR code dans Paramétrage > Communication.",
        })

    data = request.get_json(silent=True) or {}
    telephone = data.get("telephone", "").strip()
    caption   = data.get("caption", "").strip()
    pdf_b64   = data.get("pdf_base64", "")
    filename  = data.get("filename", "document.pdf").strip()

    if not telephone or not pdf_b64:
        return jsonify({"status": False, "message": "Paramètres manquants : telephone, pdf_base64."})

    # Décoder le PDF dans un fichier temporaire
    try:
        pdf_bytes = base64.b64decode(pdf_b64)
        tmp_dir   = tempfile.gettempdir()
        tmp_path  = os.path.join(tmp_dir, filename)
        with open(tmp_path, "wb") as fh:
            fh.write(pdf_bytes)
    except Exception as exc:
        return jsonify({"status": False, "message": f"Erreur décodage PDF : {exc}"})

    try:
        with driver_lock:
            result = _do_send(telephone, caption, tmp_path)
        return jsonify(result)
    finally:
        try:
            os.remove(tmp_path)
        except OSError:
            pass


@app.route("/send-text", methods=["POST"])
def route_send_text():
    """Envoie un message texte (sans PDF) via WhatsApp Web."""
    global driver

    if _status != "connected" or driver is None:
        return jsonify({
            "status": False,
            "message": "WhatsApp non connecté. Scannez le QR code dans Paramétrage > Communication.",
        })

    data = request.get_json(silent=True) or {}
    telephone = data.get("telephone", "").strip()
    message   = data.get("message", "").strip()

    if not telephone or not message:
        return jsonify({"status": False, "message": "Paramètres manquants : telephone, message."})

    with driver_lock:
        result = _do_send_text(telephone, message)
    return jsonify(result)


@app.route("/debug")
def route_debug():
    """Endpoint de diagnostic : état complet du service."""
    info = {
        "status":          _status,
        "driver_active":   driver is not None,
        "last_error":      _last_error,
        "monitor_alive":   (_monitor_thread is not None and _monitor_thread.is_alive()),
    }
    if driver is not None:
        try:
            info["current_url"] = driver.current_url
        except Exception:
            info["current_url"] = "inaccessible"
    logger.info(f"DEBUG: {info}")
    return jsonify(info)


@app.route("/qr-image")
def route_qr_image():
    """Retourne le PNG mis en cache par _monitor_loop — sans aucun lock.
    Utiliser avec un timestamp : /qr-image?t=<timestamp> pour éviter le cache.
    """
    if _qr_base64 is None:
        # Pas encore de screenshot disponible (Chrome démarre ou déjà connecté)
        logger.debug("qr-image: pas encore de screenshot disponible")
        abort(503)

    try:
        png_bytes = base64.b64decode(_qr_base64)
        logger.debug(f"qr-image: servi depuis cache ({len(png_bytes)} octets)")
        return Response(
            png_bytes,
            mimetype="image/png",
            headers={
                "Cache-Control": "no-cache, no-store, must-revalidate",
                "Pragma":        "no-cache",
                "Expires":       "0",
            },
        )
    except Exception as exc:
        logger.error(f"qr-image decode échoué : {exc}")
        abort(500)


@app.route("/screenshot")
def route_screenshot():
    """Alias de /qr-image pour diagnostic (retourne JSON avec base64)."""
    if driver is None:
        return jsonify({"status": False, "message": "Navigateur non démarré."})
    try:
        with driver_lock:
            shot = base64.b64encode(driver.get_screenshot_as_png()).decode()
            url  = driver.current_url
        return jsonify({"status": True, "url": url, "screenshot": shot})
    except Exception as exc:
        return jsonify({"status": False, "message": str(exc)})


@app.route("/disconnect", methods=["POST"])
def route_disconnect():
    global driver, _qr_base64

    try:
        if driver:
            with driver_lock:
                driver.quit()
    except Exception:
        pass
    finally:
        driver      = None
        _qr_base64  = None
        set_status("disconnected")

    return jsonify({"status": True, "message": "WhatsApp déconnecté."})


# ---------------------------------------------------------------------------
# Démarrage
# ---------------------------------------------------------------------------
if __name__ == "__main__":
    logger.info(f"Lokativ WhatsApp Service – http://{HOST}:{PORT}")
    app.run(host=HOST, port=PORT, debug=False, threaded=True)
