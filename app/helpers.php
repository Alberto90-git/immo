
<?php

use App\Parametre;
use App\Locataire;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Annexe;
use App\Direction;
use App\Plan;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;



/* ═══════════════════════════════════════════════
 *  Chiffrement / déchiffrement d'IDs dans les URLs
 * ═══════════════════════════════════════════════ */

if (!function_exists('encrypt_id')) {
    /**
     * Chiffre un ID numérique — AES-256-GCM avec nonce aléatoire.
     * Produit une chaîne URL-safe (~44-48 chars) avec intégrité authentifiée.
     * Chaque appel produit un résultat différent (nonce aléatoire).
     */
    function encrypt_id($id): string
    {
        $rawKey = config('app.key');
        if (str_starts_with($rawKey, 'base64:')) {
            $rawKey = base64_decode(substr($rawKey, 7));
        }
        $key    = hash_hmac('sha256', 'lokativ_id_cipher_v2', $rawKey, true);
        $nonce  = random_bytes(12);
        $tag    = '';
        $cipher = openssl_encrypt((string) $id, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $nonce, $tag, '', 16);
        return rtrim(strtr(base64_encode($nonce . $tag . $cipher), '+/', '-_'), '=');
    }
}

if (!function_exists('decrypt_id')) {
    /**
     * Déchiffre un ID récupéré depuis une URL (AES-256-GCM).
     * Retourne l'ID original (string) ou null si invalide / falsifié / tampered.
     */
    function decrypt_id(string $hash): ?string
    {
        try {
            $padded = str_pad($hash, strlen($hash) + (4 - strlen($hash) % 4) % 4, '=');
            $raw    = base64_decode(strtr($padded, '-_', '+/'));
            if ($raw === false || strlen($raw) < 29) {
                return null;
            }
            $nonce  = substr($raw, 0, 12);
            $tag    = substr($raw, 12, 16);
            $cipher = substr($raw, 28);
            $rawKey = config('app.key');
            if (str_starts_with($rawKey, 'base64:')) {
                $rawKey = base64_decode(substr($rawKey, 7));
            }
            $key   = hash_hmac('sha256', 'lokativ_id_cipher_v2', $rawKey, true);
            $plain = openssl_decrypt($cipher, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $nonce, $tag);
            return ($plain !== false && ctype_digit($plain) && (int) $plain > 0) ? $plain : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}

if (!function_exists('get_annexee_name')) {
    /**
     * Récupérer le nom d'une annexe par son ID
     * 
     * @param int|null $id - ID de l'annexe
     * @return string - Nom de l'annexe ou 'N/A'
     */
    function get_annexee_name($id)
    {
        if (!$id) {
            return 'N/A';
        }

        try {
            // CORRECTION: Utiliser idannexes au lieu de id
            $annexe = Annexe::where('idannexes', $id)->first();
            return $annexe ? $annexe->designation : 'N/A';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}



if (!function_exists("set_sous_menu")) {

    function set_sous_menu($route)
    {

        if ($route == 'home') {
            return 'active';
        } elseif ($route == 'roles' || $route == 'roles/create' || $route == 'gerer-user/utilisateur' || $route == 'gerer-user/add' || $route == 'password-off-line') {
            return 'active';
        } elseif ($route == 'parametrage') {
            return 'active';
        } elseif ($route == 'gerer-proprietaire/create') {
            return 'active';
        } elseif ($route == 'gerer-maison/create') {
            return 'active';
        } elseif ($route == 'gerer-chambre/create') {
            return 'active';
        } elseif ($route == 'gerer-prix/create') {
            return 'active';
        } elseif ($route == 'gerer-locataire/create') {
            return 'active';
        } elseif ($route == 'gerer-facture/create') {
            return 'active';
        } elseif ($route == 'finance') {
            return 'active';
        } elseif ($route == 'gerer-statistique-list' || $route == 'statistique-recu' || $route == 'statistique-dossier') {
            return 'active';
        } elseif ($route == 'historique') {
            return 'active';
        } elseif ($route == 'parcelle') {
            return 'active';
        } elseif ($route == 'client') {
            return 'active';
        } elseif ($route == 'gerer-statistique-list') {
            return 'active';
        } elseif ($route == 'statistique-recu') {
            return 'active';
        } elseif ($route == 'finance') {
            return 'active';
        } elseif ($route == 'statistique-dossier') {
            return 'active';
        } else {
            return '';
        }
    }
}

if (!function_exists("set_collapsed")) {

    function set_collapsed($route)
    {

        if ($route == 'home') {
            return 'active';
        } elseif ($route == 'roles' || $route == 'roles/create' || $route == 'gerer-user/utilisateur' || $route == 'gerer-user/add' || $route == 'password-off-line') {
            return 'active';
        } elseif ($route == 'parametrage') {
            return 'active';
        } elseif ($route == 'gerer-proprietaire/create') {
            return 'active';
        } elseif ($route == 'gerer-maison/create') {
            return 'active';
        } elseif ($route == 'gerer-chambre/create') {
            return 'active';
        } elseif ($route == 'gerer-prix/create') {
            return 'active';
        } elseif ($route == 'gerer-locataire/create') {
            return 'active';
        } elseif ($route == 'gerer-facture/create') {
            return 'active';
        } elseif ($route == 'finance') {
            return 'active';
        } elseif ($route == 'gerer-statistique-list' || $route == 'statistique-recu' || $route == 'statistique-dossier') {
            return 'active';
        } elseif ($route == 'historique') {
            return 'active';
        } elseif ($route == 'parcelle' || $route == 'client') {
            return 'active';
        } elseif ($route == 'publicite/pub') {
            return 'active';
        } else {
            return '';
        }
    }
}


if (!function_exists("get_locataire_liste")) {

    function get_locataire_liste()
    {
        try {
            return    Locataire::whereNull('locataires.delete_at')
                ->where('locataires.status', true)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where(function ($querry) {
                    if (Gate::none(['Is_admin'])) {
                        $querry->where('idannexe_ref', Auth::user()->idannexe_ref);
                    }
                })
                ->get();
        } catch (QueryException $e) {
            return;
        }
    }
}



if (!function_exists("get_agences_liste")) {

    function get_agences_liste()
    {
        try {
            return Annexe::whereNull('blocage_annexe')
                ->whereNull('status')
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where(function ($querry) {
                    if (Gate::none(['Is_admin'])) {
                        $querry->where('idannexes', Auth::user()->idannexe_ref);
                    }
                })
                ->get();
        } catch (QueryException $e) {
            return;
        }
    }
}

if (!function_exists("get_message")) {
    function get_message($text)
    {
        return '<div class="col-md-6 p-4">
        <div class="toast-container">
        <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">SUCCES</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              {{ $text }}
            </div>
        </div>
        </div>
      </div>';
    }
}


if (!function_exists("get_logo")) {

    function get_logo($id_direction)
    {
        if (!$id_direction) return null;
        try {
            return Cache::remember("logo_direction_{$id_direction}", 300, function () use ($id_direction) {
                return Parametre::where('iddirection_ref', $id_direction)
                    ->select('id', 'iddirection_ref', 'logo_url', 'cash_electronique', 'status_line')
                    ->first();
            });
        } catch (QueryException $e) {
            return;
        }
    }
}

if (!function_exists("get_status_entreprise")) {

    function get_status_entreprise($id_direction, $id_annexe)
    {
        try {
            return Annexe::where('iddirection_ref', $id_direction)
                ->where('idannexes', $id_annexe)
                ->value('designation');
        } catch (QueryException $e) {
            return;
        }
    }
}



if (!function_exists("get_entreprise_details_invoice")) {

    function get_entreprise_details_invoice($id_direction)
    {
        try {
            return  Direction::where('iddirection', $id_direction)
                ->get();
        } catch (QueryException $e) {
            return;
        }
    }
}


if (!function_exists("get_annexee_name")) {

    function get_annexee_name($id)
    {
        try {
            return  Annexe::whereNull('annexes.status')
                ->where('idannexes', $id)
                //->where('annexes.iddirection_ref',Auth::user()->iddirection_ref)
                ->get()
                ->pluck('designation')[0];
        } catch (QueryException $e) {
            return;
        }
    }
}



if (!function_exists("get_annexe_liste")) {

    function get_annexe_liste()
    {
        try {
            $reponse = Annexe::whereNull('annexes.status')
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where(function ($querry) {
                    if (Gate::none(['Is_admin'])) {
                        $querry->where('idannexe_ref', Auth::user()->idannexe_ref);
                    }
                })
                ->where('annexes.designation', '!=', 'All Digital Agency')
                ->get();
            return $reponse;
        } catch (QueryException $e) {
            return;
        }
    }
}




if (!function_exists("set_show")) {

    function set_show($route)
    {

        if ($route == 'roles' || $route == 'roles/create' || $route == 'gerer-user/utilisateur' || $route == 'gerer-user/add' || $route == 'password-off-line') {
            return 'open';
        } elseif ($route == 'gerer-statistique-create' || $route == 'statistique-recu' || $route == 'statistique-dossier') {
            return 'open';
        } elseif ($route == 'finance') {
            return 'open';
        } elseif ($route == 'parcelle' || $route == 'client') {
            return 'open';
        } elseif ($route == 'gerer-statistique-list' || $route == 'statistique-recu' || $route == 'statistique-dossier') {
            return 'open';
        } else {
            return '';
        }
    }
}

if (!function_exists("set_active")) {

    function set_active($route)
    {
        if ($route == 'roles' || $route == 'roles/create') {
            return 'active';
        } elseif ($route == 'gerer-user/utilisateur' || $route == 'gerer-user/add') {
            return 'active';
        } elseif ($route  == 'password-off-line') {
            return 'active';
        } elseif ($route == 'gerer-statistique-create') {
            return 'active';
        } elseif ($route == 'statistique-recu') {
            return 'active';
        } elseif ($route == 'finance') {
            return 'active';
        } elseif ($route == 'parcelle' || $route == 'client') {
            return 'active';
        } elseif ($route == 'statistique-dossier') {
            return 'active';
        } else {
            return '';
        }
    }
}



if (!function_exists("get_status_line")) {

    function get_status_line()
    {
        try {
            return Parametre::value('status_line');
        } catch (QueryException $e) {
            return;
        }
    }
}

if (!function_exists("nom_mois")) {

    function nom_mois($numero)
    {
        try {
            $nom = '';
            if ($numero == '01') {
                $nom = 'Janvier';
            }
            if ($numero == '02') {
                $nom = 'Février';
            }
            if ($numero == '03') {
                $nom = 'Mars';
            }
            if ($numero == '04') {
                $nom = 'Avriel';
            }
            if ($numero == '05') {
                $nom = 'Mai';
            }
            if ($numero == '06') {
                $nom = 'Juin';
            }
            if ($numero == '07') {
                $nom = 'Juillet';
            }
            if ($numero == '08') {
                $nom = 'Août';
            }
            if ($numero == '09') {
                $nom = 'Septembre';
            }
            if ($numero == '10') {
                $nom = 'Octobre';
            }
            if ($numero == '11') {
                $nom = 'Novembre';
            }
            if ($numero == '12') {
                $nom = 'Décembre';
            }
            return $nom;
        } catch (QueryException $e) {
            return;
        }
    }



    function nombreEnLettres($nombre)
    {
        $unites = [
            '',
            'un',
            'deux',
            'trois',
            'quatre',
            'cinq',
            'six',
            'sept',
            'huit',
            'neuf',
            'dix',
            'onze',
            'douze',
            'treize',
            'quatorze',
            'quinze',
            'seize'
        ];

        $dizaines = [
            '',
            '',
            'vingt',
            'trente',
            'quarante',
            'cinquante',
            'soixante',
            'soixante-dix',
            'quatre-vingt',
            'quatre-vingt-dix'
        ];

        if ($nombre < 17) {
            return $unites[$nombre];
        }

        if ($nombre < 20) {
            return 'dix-' . $unites[$nombre - 10];
        }

        if ($nombre < 100) {
            $dizaine = intdiv($nombre, 10);
            $unite = $nombre % 10;

            $texte = $dizaines[$dizaine];

            if ($unite === 1 && $dizaine !== 8) {
                $texte .= ' et un';
            } elseif ($unite > 0) {
                $texte .= '-' . $unites[$unite];
            }

            return $texte;
        }

        if ($nombre < 1000) {
            $centaine = intdiv($nombre, 100);
            $reste = $nombre % 100;

            $texte = ($centaine > 1 ? $unites[$centaine] . ' ' : '') . 'cent';

            if ($reste > 0) {
                $texte .= ' ' . nombreEnLettres($reste);
            }

            return $texte;
        }

        if ($nombre < 1000000) {
            $mille = intdiv($nombre, 1000);
            $reste = $nombre % 1000;

            $texte = ($mille > 1 ? nombreEnLettres($mille) . ' ' : '') . 'mille';

            if ($reste > 0) {
                $texte .= ' ' . nombreEnLettres($reste);
            }

            return $texte;
        }

        return 'montant trop élevé';
    }
}

/**
 * Fonctions helpers pour la gestion des plans d'abonnement
 */

if (!function_exists("get_current_direction")) {
    /**
     * Retourne la Direction de l'utilisateur connecté (memoïsée pour la requête en cours)
     */
    function get_current_direction()
    {
        static $cache = [];

        if (!Auth::check()) {
            return null;
        }

        $id = Auth::user()->iddirection_ref;

        if (!array_key_exists($id, $cache)) {
            try {
                $cache[$id] = Direction::with('plan')->find($id);
            } catch (QueryException $e) {
                $cache[$id] = null;
            }
        }

        return $cache[$id];
    }
}

if (!function_exists("get_current_plan")) {
    /**
     * Récupère le plan actuel de l'utilisateur connecté
     *
     * @return Plan|null
     */
    function get_current_plan()
    {
        try {
            $direction = get_current_direction();
            if (!$direction) {
                return null;
            }
            return $direction->plan ?? Plan::starter();
        } catch (QueryException $e) {
            return null;
        }
    }
}

if (!function_exists("get_plan_info")) {
    /**
     * Récupère les informations du plan de l'utilisateur connecté
     *
     * @return array|null
     */
    function get_plan_info()
    {
        try {
            $direction = get_current_direction();
            if (!$direction) {
                return null;
            }
            return $direction->getPlanInfo();
        } catch (QueryException $e) {
            return null;
        }
    }
}

if (!function_exists("can_create_maison")) {
    /**
     * Vérifie si l'utilisateur peut créer une nouvelle maison
     *
     * @return array ['allowed' => bool, 'message' => string]
     */
    function can_create_maison()
    {
        try {
            $direction = get_current_direction();
            if (!$direction) {
                return ['allowed' => false, 'message' => 'Direction non trouvée.'];
            }
            return $direction->canCreateMaison();
        } catch (QueryException $e) {
            return ['allowed' => false, 'message' => 'Erreur lors de la vérification.'];
        }
    }
}

if (!function_exists("can_create_annexe")) {
    /**
     * Vérifie si l'utilisateur peut créer une nouvelle annexe
     *
     * @return array ['allowed' => bool, 'message' => string]
     */
    function can_create_annexe()
    {
        try {
            $direction = get_current_direction();
            if (!$direction) {
                return ['allowed' => false, 'message' => 'Direction non trouvée.'];
            }
            return $direction->canCreateAnnexe();
        } catch (QueryException $e) {
            return ['allowed' => false, 'message' => 'Erreur lors de la vérification.'];
        }
    }
}

if (!function_exists("get_all_plans")) {
    /**
     * Récupère tous les plans actifs
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function get_all_plans()
    {
        try {
            return Plan::actifs();
        } catch (QueryException $e) {
            return collect([]);
        }
    }
}

// ═══════════════════════════════════════════════════════════════════════════
// MULTI-DEVISE — helpers de formatage & configuration
// ═══════════════════════════════════════════════════════════════════════════

if (!function_exists('get_direction_parametre')) {
    /**
     * Retourne le Parametre de la direction indiquée (ou de l'utilisateur connecté).
     * Memoïsé pour éviter les requêtes répétées dans la même requête HTTP.
     *
     * @param int|null $directionId
     * @return \App\Parametre|null
     */
    function get_direction_parametre(?int $directionId = null): ?\App\Parametre
    {
        static $cache = [];

        if ($directionId === null) {
            if (!\Illuminate\Support\Facades\Auth::check()) {
                return null;
            }
            $directionId = \Illuminate\Support\Facades\Auth::user()->iddirection_ref;
        }

        if (!array_key_exists($directionId, $cache)) {
            try {
                $cache[$directionId] = \App\Parametre::where('iddirection_ref', $directionId)->first();
            } catch (\Exception $e) {
                $cache[$directionId] = null;
            }
        }

        return $cache[$directionId];
    }
}

if (!function_exists('get_devise_config')) {
    /**
     * Retourne la config d'une devise (symbole, séparateurs, décimales).
     * Si $code est null, utilise la devise de l'utilisateur connecté.
     *
     * @param string|null $code  Code ISO 4217 (XOF, XAF, GHS, NGN, EUR, USD)
     * @return array
     */
    function get_devise_config(?string $code = null): array
    {
        if ($code === null) {
            $param = get_direction_parametre();
            $code  = $param->devise ?? 'XOF';
        }
        return \App\Parametre::deviseConfig($code);
    }
}

if (!function_exists('get_devise_courante')) {
    /**
     * Retourne le code devise de l'utilisateur connecté (ex: 'XOF').
     *
     * @param int|null $directionId
     * @return string
     */
    function get_devise_courante(?int $directionId = null): string
    {
        $param = get_direction_parametre($directionId);
        return $param->devise ?? 'XOF';
    }
}

if (!function_exists('get_symbole_devise')) {
    /**
     * Retourne le symbole affiché (ex: 'FCFA', '₦', '$').
     *
     * @param string|null $code
     * @return string
     */
    function get_symbole_devise(?string $code = null): string
    {
        return get_devise_config($code)['symbole'];
    }
}

if (!function_exists("format_price")) {
    /**
     * Formate un montant avec la devise de l'utilisateur connecté (ou d'une direction précise).
     *
     * Exemples :
     *   format_price(150000)               → "150 000 FCFA"  (XOF)
     *   format_price(1200.50, null, 'GHS') → "GH₵ 1,200.50" (GHS)
     *   format_price(99.99, null, 'USD')   → "$ 99.99"       (USD)
     *
     * @param float      $price
     * @param int|null   $directionId  Direction à utiliser (null = utilisateur connecté)
     * @param string|null $forceDevise Code devise explicite (prioritaire sur directionId)
     * @return string
     */
    function format_price($price, ?int $directionId = null, ?string $forceDevise = null): string
    {
        $cfg = $forceDevise
            ? \App\Parametre::deviseConfig($forceDevise)
            : get_devise_config(get_devise_courante($directionId));

        $formatted = number_format((float) $price, $cfg['decimales'], $cfg['sep_decimal'], $cfg['sep_milliers']);

        return $cfg['symbole_avant']
            ? $cfg['symbole'] . ' ' . $formatted
            : $formatted . ' ' . $cfg['symbole'];
    }
}

if (!function_exists('format_price_for')) {
    /**
     * Raccourci : format_price avec une direction explicite.
     * Pratique dans les boucles (notifications, PDF, etc.)
     *
     * @param float  $price
     * @param int    $directionId
     * @return string
     */
    function format_price_for(float $price, int $directionId): string
    {
        return format_price($price, $directionId);
    }
}

if (!function_exists('convertir_devise')) {
    /**
     * Convertit un montant d'une devise vers une autre en utilisant
     * les taux configurés sur la direction.
     *
     * @param float    $montant
     * @param string   $deviseSource
     * @param string   $deviseCible
     * @param int|null $directionId
     * @return float
     */
    function convertir_devise(float $montant, string $deviseSource, string $deviseCible, ?int $directionId = null): float
    {
        if ($deviseSource === $deviseCible) {
            return $montant;
        }

        $param = get_direction_parametre($directionId);
        $taux  = $param->taux_change ?? [];

        // taux_change stocke : 1 unité de devise cible = X unités devise locale
        // devise locale = devise configurée dans le parametre
        $deviseLocale = $param->devise ?? 'XOF';

        if ($deviseSource === $deviseLocale && isset($taux[$deviseCible])) {
            return $montant / (float) $taux[$deviseCible];
        }

        if ($deviseCible === $deviseLocale && isset($taux[$deviseSource])) {
            return $montant * (float) $taux[$deviseSource];
        }

        // Conversion indirecte via devise locale
        if (isset($taux[$deviseSource]) && isset($taux[$deviseCible])) {
            $montantLocal = $montant * (float) $taux[$deviseSource];
            return $montantLocal / (float) $taux[$deviseCible];
        }

        return $montant; // fallback sans conversion
    }
}

if (!function_exists('get_direction_locale')) {
    /**
     * Retourne la locale ('fr'|'en') configurée pour une direction.
     * Utile hors-session HTTP (cron, queue) pour envoyer les emails dans la bonne langue.
     *
     * @param int|null $directionId
     * @return string
     */
    function get_direction_locale(?int $directionId = null): string
    {
        $param = get_direction_parametre($directionId);
        $locale = $param->locale ?? 'fr';
        return in_array($locale, ['fr', 'en']) ? $locale : 'fr';
    }
}

if (!function_exists('get_taux_change')) {
    /**
     * Retourne le taux de change de 1 unité de $deviseSource en $deviseCible
     * selon les taux configurés pour la direction.
     *
     * @param string   $deviseSource
     * @param string   $deviseCible
     * @param int|null $directionId
     * @return float|null  null si taux inconnu
     */
    function get_taux_change(string $deviseSource, string $deviseCible, ?int $directionId = null): ?float
    {
        if ($deviseSource === $deviseCible) {
            return 1.0;
        }

        $param = get_direction_parametre($directionId);
        $taux  = $param->taux_change ?? [];
        $base  = $param->devise ?? 'XOF';

        if ($deviseSource === $base && isset($taux[$deviseCible])) {
            return 1.0 / (float) $taux[$deviseCible];
        }

        if ($deviseCible === $base && isset($taux[$deviseSource])) {
            return (float) $taux[$deviseSource];
        }

        return null;
    }
}

if (!function_exists("is_abonnement_actif")) {
    /**
     * Vérifie si l'abonnement de l'utilisateur est actif
     *
     * @return bool
     */
    function is_abonnement_actif()
    {
        try {
            $direction = get_current_direction();
            if (!$direction) {
                return false;
            }
            return $direction->hasAbonnementActif();
        } catch (QueryException $e) {
            return false;
        }
    }
}

if (!function_exists("get_abonnement_status")) {
    /**
     * Récupère le statut de l'abonnement avec plus de détails
     *
     * @return array
     */
    function get_abonnement_status()
    {
        try {
            if (!Auth::check()) {
                return [
                    'actif' => false,
                    'statut' => 'non_connecte',
                    'message' => 'Vous devez être connecté.'
                ];
            }

            $direction = get_current_direction();

            if (!$direction) {
                return [
                    'actif' => false,
                    'statut' => 'erreur',
                    'message' => 'Direction non trouvée.'
                ];
            }

            $plan = $direction->plan ?? Plan::starter();
            $isActif = $direction->hasAbonnementActif();
            $isExpire = $direction->isAbonnementExpire();

            return [
                'actif' => $isActif,
                'statut' => $direction->statut_abonnement,
                'plan_nom' => $plan ? $plan->nom : 'Aucun',
                'plan_code' => $plan ? $plan->code : null,
                'date_fin' => $direction->abonnement_fin,
                'expire' => $isExpire,
                'message' => $isActif ? 'Abonnement actif' : ($isExpire ? 'Abonnement expiré' : 'Abonnement inactif')
            ];
        } catch (QueryException $e) {
            return [
                'actif' => false,
                'statut' => 'erreur',
                'message' => 'Erreur lors de la vérification.'
            ];
        }
    }
}

/**
 * Fonctions helpers pour la gestion centralisée de l'agence active
 */

if (!function_exists("get_active_annexe_id")) {
    /**
     * Retourne l'ID de l'annexe active
     * - Pour les admins : utilise la session
     * - Pour les autres : utilise leur idannexe_ref
     *
     * @return int|null
     */
    function get_active_annexe_id()
    {
        try {
            if (!Auth::check()) {
                return null;
            }

            $user = Auth::user();

            // Si l'utilisateur est admin et type entreprise, on utilise la session
            if ($user->is_admin == 1 && $user->type_compte != 'Particulier') {
                return session('active_annexe_id');
            }

            // Pour les autres utilisateurs, on utilise leur annexe de référence
            return $user->idannexe_ref;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists("has_active_annexe")) {
    /**
     * Vérifie si une annexe active est définie
     *
     * @return bool
     */
    function has_active_annexe()
    {
        return get_active_annexe_id() !== null;
    }
}

if (!function_exists("is_admin_without_annexe_selected")) {
    /**
     * Vérifie si l'utilisateur est un admin sans annexe sélectionnée
     * Utile pour afficher une alerte
     *
     * @return bool
     */
    function is_admin_without_annexe_selected()
    {
        try {
            if (!Auth::check()) {
                return false;
            }

            $user = Auth::user();

            // Vérifier si c'est un admin d'entreprise
            if ($user->is_admin == 1 && $user->type_compte != 'Particulier') {
                // Vérifier si une annexe est sélectionnée en session
                return !session()->has('active_annexe_id') || session('active_annexe_id') === null;
            }

            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists("get_active_annexe_name")) {
    /**
     * Retourne le nom de l'annexe active
     *
     * @return string
     */
    function get_active_annexe_name()
    {
        $annexeId = get_active_annexe_id();

        if (!$annexeId) {
            return 'Aucune agence';
        }

        return get_annexee_name($annexeId);
    }
}

if (!function_exists("get_active_annexe")) {
    /**
     * Retourne l'objet Annexe active complet
     *
     * @return Annexe|null
     */
    function get_active_annexe()
    {
        $annexeId = get_active_annexe_id();

        if (!$annexeId) {
            return null;
        }

        try {
            return Annexe::where('idannexes', $annexeId)->first();
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists("get_annexe_details_for_invoice")) {
    /**
     * Retourne les détails de l'agence pour les factures
     *
     * @param int $idannexe_ref
     * @return array|null
     */
    function get_annexe_details_for_invoice($idannexe_ref)
    {
        if (!$idannexe_ref) return null;

        try {
            $annexe = Cache::remember("annexe_details_{$idannexe_ref}", 120, function () use ($idannexe_ref) {
                return Annexe::where('idannexes', $idannexe_ref)
                    ->select('idannexes', 'designation', 'telephone', 'email', 'siege_social', 'logo', 'signature', 'cash_electronique', 'iddirection_ref')
                    ->first();
            });

            if (!$annexe) {
                return null;
            }

            // Résoudre les chemins via les accesseurs du modèle
            $logoPath = $annexe->logo_path;
            $signaturePath = $annexe->signature_path;

            // Charger les paramètres de la direction (logo fallback + cash_electronique image)
            $parametre = null;
            if ($annexe->iddirection_ref) {
                $parametre = \App\Parametre::where('iddirection_ref', $annexe->iddirection_ref)->first();
            }

            // Fallback logo: chercher dans le Parametre de la direction
            if (!$logoPath && $parametre) {
                $rawLogoUrl = $parametre->getRawOriginal('logo_url');
                if ($rawLogoUrl) {
                    $possiblePaths = [
                        storage_path('app/public/' . $rawLogoUrl),
                        public_path('storage/' . $rawLogoUrl),
                        public_path($rawLogoUrl),
                        $rawLogoUrl
                    ];

                    foreach ($possiblePaths as $path) {
                        if (file_exists($path)) {
                            $logoPath = $path;
                            break;
                        }
                    }
                }
            }

            // Convertir en base64 pour les vues DomPDF (Blade)
            $logoBase64 = null;
            if ($logoPath && file_exists($logoPath)) {
                $mime = mime_content_type($logoPath) ?: 'image/jpeg';
                $logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
            }

            $signatureBase64 = null;
            if ($signaturePath && file_exists($signaturePath)) {
                $mime = mime_content_type($signaturePath) ?: 'image/jpeg';
                $signatureBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($signaturePath));
            }

            // Cash électronique : image depuis parametres.cash_electronique_url
            $cashImagePath = null;
            $cashImageBase64 = null;
            if ($parametre) {
                $rawCashUrl = $parametre->getRawOriginal('cash_electronique_url');
                if ($rawCashUrl) {
                    $possibleCashPaths = [
                        storage_path('app/public/' . $rawCashUrl),
                        public_path('storage/' . $rawCashUrl),
                        public_path($rawCashUrl),
                        $rawCashUrl
                    ];
                    foreach ($possibleCashPaths as $path) {
                        if (file_exists($path)) {
                            $cashImagePath = $path;
                            break;
                        }
                    }
                }
            }
            if ($cashImagePath) {
                $mime = mime_content_type($cashImagePath) ?: 'image/jpeg';
                $cashImageBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($cashImagePath));
            }

            return [
                'designation' => $annexe->designation,
                'telephone' => $annexe->telephone,
                'email' => $annexe->email,
                'siege_social' => $annexe->siege_social,
                'logo_path' => $logoPath,
                'logo_base64' => $logoBase64,
                'cash_electronique' => $annexe->cash_electronique,
                'cash_electronique_image_path' => $cashImagePath,
                'cash_electronique_image_base64' => $cashImageBase64,
                'signature_path' => $signaturePath,
                'signature_base64' => $signatureBase64,
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists("get_pourcentage_gestion")) {
    /**
     * Retourne le pourcentage de gestion actif pour un propriétaire donné.
     * Priorité : groupe actif > pourcentage général actif > fallback 10
     */
    function get_pourcentage_gestion($proprietaire_id)
    {
        try {
            if (!\Illuminate\Support\Facades\Auth::check()) {
                return 10;
            }

            $directionId = \Illuminate\Support\Facades\Auth::user()->iddirection_ref;

            // 1. Vérifier si le propriétaire appartient à un groupe actif
            $groupePourcentage = \App\PourcentageGestion::where('iddirection_ref', $directionId)
                ->where('type', 'groupe')
                ->where('is_active', true)
                ->whereNull('delete_at')
                ->whereHas('proprietaires', function ($query) use ($proprietaire_id) {
                    $query->where('proprietaires.id', $proprietaire_id);
                })
                ->first();

            if ($groupePourcentage) {
                return (float) $groupePourcentage->pourcentage;
            }

            // 2. Fallback : pourcentage général actif
            $generalPourcentage = \App\PourcentageGestion::where('iddirection_ref', $directionId)
                ->where('type', 'general')
                ->where('is_active', true)
                ->whereNull('delete_at')
                ->first();

            if ($generalPourcentage) {
                return (float) $generalPourcentage->pourcentage;
            }

            // 3. Fallback par défaut
            return 10;

        } catch (\Exception $e) {
            return 10;
        }
    }
}

if (!function_exists('get_pourcentage_gestion_or_null')) {
    /**
     * Retourne le pourcentage de gestion configuré pour un propriétaire,
     * ou null si aucune configuration explicite n'existe.
     */
    function get_pourcentage_gestion_or_null($proprietaire_id): ?float
    {
        try {
            if (!\Illuminate\Support\Facades\Auth::check()) return null;
            $directionId = \Illuminate\Support\Facades\Auth::user()->iddirection_ref;

            $groupe = \App\PourcentageGestion::where('iddirection_ref', $directionId)
                ->where('type', 'groupe')->where('is_active', true)->whereNull('delete_at')
                ->whereHas('proprietaires', function ($q) use ($proprietaire_id) {
                    $q->where('proprietaires.id', $proprietaire_id);
                })->first();
            if ($groupe) return (float) $groupe->pourcentage;

            $general = \App\PourcentageGestion::where('iddirection_ref', $directionId)
                ->where('type', 'general')->where('is_active', true)->whereNull('delete_at')
                ->first();
            if ($general) return (float) $general->pourcentage;

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists("get_all_pourcentages_for_direction")) {
    /**
     * Retourne tous les pourcentages actifs pour la direction courante.
     * Utilisé pour remplir les dropdowns dans financier.blade.php.
     */
    function get_all_pourcentages_for_direction()
    {
        try {
            if (!\Illuminate\Support\Facades\Auth::check()) {
                return collect([10]);
            }

            $directionId = \Illuminate\Support\Facades\Auth::user()->iddirection_ref;

            $pourcentages = \App\PourcentageGestion::where('iddirection_ref', $directionId)
                ->whereNull('delete_at')
                ->where('is_active', true)
                ->pluck('pourcentage')
                ->unique()
                ->sort()
                ->values();

            if ($pourcentages->isEmpty()) {
                return collect([10]);
            }

            return $pourcentages;

        } catch (\Exception $e) {
            return collect([10]);
        }
    }
}
