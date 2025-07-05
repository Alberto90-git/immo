
<?php
use App\Parametre;
use App\Locataire;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Annexe;
use App\Direction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Gate;




if (!function_exists("set_sous_menu")) {

    function set_sous_menu($route)
    {

        if ($route == 'home') {
            return 'active';
        } elseif ($route == 'roles' || $route == 'roles/create' || $route == 'gerer-user/utilisateur' || $route == 'gerer-user/add' || $route == 'password-off-line') {
            return 'active';
        } 
         elseif ($route == 'parametrage') {
            return 'active';
        } elseif ($route == 'gerer-proprietaire/create') {
            return 'active';
        } elseif ($route == 'gerer-maison/create') {
            return 'active';
        } elseif ($route == 'gerer-chambre/create') {
            return 'active';
        }elseif ($route == 'gerer-prix/create') {
            return 'active';
        }elseif ($route == 'gerer-locataire/create') {
            return 'active';
        }elseif ($route == 'gerer-facture/create') {
            return 'active';
        }elseif ($route == 'finance') {
            return 'active';
        }elseif ($route == 'gerer-statistique-list' || $route == 'statistique-recu' || $route == 'statistique-dossier') {
            return 'active';
        }elseif ($route == 'historique') {
            return 'active';
        }
        elseif ($route == 'parcelle') {
            return 'active';
        }
        elseif ($route == 'client') {
            return 'active';
        }elseif ($route == 'gerer-statistique-list') {
            return 'active';
        }elseif ($route == 'statistique-recu') {
            return 'active';
        }
        elseif ($route == 'finance') {
            return 'active';
        }
        elseif ($route == 'statistique-dossier') {
            return 'active';
        }
        else {
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
        } 
         elseif ($route == 'parametrage') {
            return 'active';
        } elseif ($route == 'gerer-proprietaire/create') {
            return 'active';
        } elseif ($route == 'gerer-maison/create') {
            return 'active';
        } elseif ($route == 'gerer-chambre/create') {
            return 'active';
        }elseif ($route == 'gerer-prix/create') {
            return 'active';
        }elseif ($route == 'gerer-locataire/create') {
            return 'active';
        }elseif ($route == 'gerer-facture/create') {
            return 'active';
        }elseif ($route == 'finance') {
            return 'active';
        }elseif ($route == 'gerer-statistique-list' || $route == 'statistique-recu' || $route == 'statistique-dossier') {
            return 'active';
        }elseif ($route == 'historique') {
            return 'active';
        }
        elseif ($route == 'parcelle' || $route == 'client') {
            return 'active';
        }
        elseif ($route == 'publicite/pub') {
            return 'active';
        }
         else {
            return '';
        }
    }
}


if (!function_exists("get_locataire_liste")) {
    
    function get_locataire_liste()
    {
        try {
            $reponse = Locataire::whereNull('locataires.delete_at')
                                ->where('locataires.status',true)
                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                ->where(function($querry){
                                    if (Gate::none(['Is_admin'])) {
                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                    }
                                })
                                ->get();
            return $reponse;

        } catch (QueryException $e) {
            return;
        }

    }
}

if (!function_exists("get_message")) {
    function get_message($text){
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
            $reponse = Parametre::where('iddirection_ref',$id_direction)  
                                ->first();
            return $reponse;

        } catch (QueryException $e) {
            return;
        }

    }
}

if (!function_exists("get_status_entreprise")) {
    
    function get_status_entreprise($id_direction,$id_annexe)
    {
        try {
            $reponse = Annexe::where('iddirection_ref',$id_direction)
                            ->where('idannexes',$id_annexe)
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
            return  Direction::where('iddirection',$id_direction)
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
                                ->where('idannexes',$id)
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
                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                ->where(function($querry){
                                    if (Gate::none(['Is_admin'])) {
                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                    } 
                                })
                                ->where('annexes.designation','!=','All Digital Agency')
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
        }
        elseif ($route == 'parcelle' || $route == 'client') {
            return 'open';
        }elseif ($route == 'gerer-statistique-list' || $route == 'statistique-recu' || $route == 'statistique-dossier') {
            return 'open';
        }
        else {
            return '';
        }
    }
}

if (!function_exists("set_active")) {

    function set_active($route)
    {
        if ($route == 'roles' || $route == 'roles/create' ) {
            return 'active';
        } elseif ($route =='gerer-user/utilisateur' || $route =='gerer-user/add') {  
            return 'active';
        } elseif ($route  == 'password-off-line') {  
            return 'active';
        } elseif ($route == 'gerer-statistique-create') {
            return 'active';
        } elseif ($route == 'statistique-recu') {
            return 'active';
        } elseif ($route == 'finance') {
            return 'active';
        }
        elseif ($route == 'parcelle' || $route == 'client') {
            return 'active';
        }
        elseif ($route == 'statistique-dossier') {
            return 'active';
        }
        else {
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
}