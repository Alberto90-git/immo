
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
        try {
            $reponse = Parametre::where('iddirection_ref', $id_direction)
                ->first();
            return $reponse;
        } catch (QueryException $e) {
            return;
        }
    }
}

if (!function_exists("get_status_entreprise")) {

    function get_status_entreprise($id_direction, $id_annexe)
    {
        try {
            $reponse = Annexe::where('iddirection_ref', $id_direction)
                ->where('idannexes', $id_annexe)
                ->get()
                ->pluck('designation')[0];
            return $reponse;
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
            $reponse = Parametre::select('status_line')
                ->get()
                ->pluck('status_line')[0];

            return $reponse;
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

if (!function_exists("get_current_plan")) {
    /**
     * Récupère le plan actuel de l'utilisateur connecté
     *
     * @return Plan|null
     */
    function get_current_plan()
    {
        try {
            if (!Auth::check()) {
                return null;
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

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
            if (!Auth::check()) {
                return null;
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

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
            if (!Auth::check()) {
                return ['allowed' => false, 'message' => 'Vous devez être connecté.'];
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

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
            if (!Auth::check()) {
                return ['allowed' => false, 'message' => 'Vous devez être connecté.'];
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

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

if (!function_exists("format_price")) {
    /**
     * Formate un prix en XOF
     *
     * @param float $price
     * @return string
     */
    function format_price($price)
    {
        return number_format($price, 0, ',', ' ') . ' XOF';
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
            if (!Auth::check()) {
                return false;
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

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

            $direction = Direction::find(Auth::user()->iddirection_ref);

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
        try {
            $annexe = Annexe::where('idannexes', $idannexe_ref)->first();

            if (!$annexe) {
                return null;
            }

            // Trouver le chemin du logo
            $logoPath = null;
            if ($annexe->logo) {
                $possiblePaths = [
                    storage_path('app/public/' . $annexe->logo),
                    public_path('storage/' . $annexe->logo),
                    public_path($annexe->logo),
                    $annexe->logo
                ];

                foreach ($possiblePaths as $path) {
                    if (file_exists($path)) {
                        $logoPath = $path;
                        break;
                    }
                }
            }

            return [
                'designation' => $annexe->designation,
                'telephone' => $annexe->telephone,
                'email' => $annexe->email,
                'siege_social' => $annexe->siege_social,
                'logo_path' => $logoPath,
                'cash_electronique' => $annexe->cash_electronique
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
}
