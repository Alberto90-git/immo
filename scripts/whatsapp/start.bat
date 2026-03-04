@echo off
echo Lokativ - Demarrage du service WhatsApp natif
echo ================================================
cd /d "%~dp0"

REM Verifier si Python est installe
python --version >nul 2>&1
if errorlevel 1 (
    echo [ERREUR] Python n'est pas installe ou pas dans le PATH.
    echo Telechargez Python depuis https://www.python.org/downloads/
    pause
    exit /b 1
)

REM Installer les dependances si necessaire
if not exist "venv\" (
    echo Installation de l'environnement virtuel...
    python -m venv venv
    call venv\Scripts\activate.bat
    echo Installation des dependances...
    pip install -r requirements.txt
) else (
    call venv\Scripts\activate.bat
)

echo Lancement du service sur http://127.0.0.1:5050 ...
python service.py

pause
