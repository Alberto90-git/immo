<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Proprietaire;
use App\Maison;
use App\Client;
use App\Parcelle;
use App\Locataire;
use App\Facture;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class StatistiqueController extends Controller
{
	//GET ALL PROPRIO
	private function getAllProprioWithHouse()
	{
		return Proprietaire::whereNull('proprietaires.delete_at')
                            ->where('proprietaires.iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('proprietaires.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                            ->join('maisons', 'maisons.proprio_id', '=', 'proprietaires.id')
                            ->join('annexes', 'annexes.idannexes', '=', 'proprietaires.idannexe_ref')
                            ->whereNull('maisons.delete_at')
                            ->select(
                                'proprietaires.id', 'proprietaires.nom', 'proprietaires.prenom',
                                'proprietaires.telephone', 'proprietaires.adresse', 'proprietaires.idannexe_ref',
                                'maisons.id as maison_id', 'maisons.nom_maison', 'maisons.quartier',
                                'maisons.nombre_chambre',
                                'annexes.designation as annexe_designation'
                            )
                            ->get();
	}

    //LA GRANDE PAGE DE VUE STATISTIQUE
    public function viewProprioMaison()
    {

    	try {
    		$element = array();

    		$element['proprioMaison'] = $this->getAllProprioWithHouse();

    		//LES PROPRIETAIRES
            $element['proprio'] = Proprietaire::whereNull('proprietaires.delete_at')
                                                ->where('proprietaires.iddirection_ref',Auth::user()->iddirection_ref)
                                                ->where(function($querry){
                                                    if (Gate::none(['Is_admin'])) {
                                                        $querry->where('proprietaires.idannexe_ref',Auth::user()->idannexe_ref);
                                                    }
                                                })
                                                ->join('annexes', 'annexes.idannexes', '=', 'proprietaires.idannexe_ref')
                                                ->get();

            $element['house'] = Maison::whereNull('maisons.delete_at')
                                        ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                                        ->where(function($querry){
                                            if (Gate::none(['Is_admin'])) {
                                                $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                                            }
                                        })
                                        ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
                                        ->get();

            return view('statistique.proprietaireChambre',['data' => $element]);

    	} catch (\Exception $e) {
    		return view('statistique.proprietaireChambre',['data' => ['proprioMaison' => collect(), 'proprio' => collect(), 'house' => collect()]]);
    	}
    }

    //TELECHARGER PDF DES PROPRIO AVEC LEURS MAISONS
    public function getProprioHousePdf()
    {

    	$element = $this->getAllProprioWithHouse();
    	$annexeData = get_annexe_details_for_invoice(get_active_annexe_id());

    	$pdf = PDF::loadView('pdf.proprio_house',compact('element','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

    	$pdf->output();

      activity()->performedOn(new Proprietaire())
                           ->causedBy(Auth::user()->id)
                           ->log('Téléchargement du rapport propriétaire et leurs maisons '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

    	return $pdf->download('Rapport du '.Carbon::now().'.pdf');
    }

    // filter house and chambre based on propriotor

    private function getProprietaire($id)
    {
        return Proprietaire::where('id', $id)
                            ->whereNull('delete_at')
                            ->select('nom', 'prenom')
                            ->first();
    }

    private function getProprietaireNom($id)
    {
        $p = $this->getProprietaire($id);
        return $p ? $p->nom : '';
    }

    private function getProprietairePrenom($id)
    {
        $p = $this->getProprietaire($id);
        return $p ? $p->prenom : '';
    }

    
    
    public function getHouseChambreByProprio(Request $request)
    {
       
        $vide='';
        $valeur = '';


        $proprio_obj    = $this->getProprietaire($request->idRecu);
        $proprio_nom    = $proprio_obj ? $proprio_obj->nom    : '';
        $proprio_prenom = $proprio_obj ? $proprio_obj->prenom : '';

        $donnees = Maison::where('maisons.proprio_id',$request->idRecu)
                         ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                         ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                            }
                        })
                         ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
                          ->join('chambres', 'chambres.maison_id', '=', 'maisons.id')
                          ->join('prixes', 'prixes.chambre_id', '=', 'chambres.id')
                          ->whereNull('chambres.delete_at')
                          ->whereNull('maisons.delete_at')
                          ->get();

        
            if(count($donnees) === 0)
            {
                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des maisons de '.$proprio_nom.' '.$proprio_prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';
                return response()->json([
                    'list_house'=> $vide,
                    'infor_proprio' => $proprio_nom.' '.$proprio_prenom,
                    'valeur' => $valeur
                ]);

            }
            else
            {
                foreach ($donnees as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.e($value->nom_maison).'</td><td>'.e($value->quartier).'</td><td>'.e($value->numero_chambre).'</td>';
                    $vide.='<td>'.e($value->type_chambre).'</td>';
                    $vide.='<td>'.number_format($value->prix,"0",",",".").' XOF'.'</td>';
                    $vide.='</tr>';
                }

                $valeur .= '<a '.'class'.'='.' "btn rounded-pill btn-primary" '.
                              'title'.'='.' "Télécharger pdf" '.'href'.'='.'house-chambre/'.encrypt_id($request->idRecu).'>'.
                                 'Télécharger pdf'.
                               '</a>';

                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des maisons de '.$proprio_nom.' '.$proprio_prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


                return response()->json([
                    'list_house'=> $vide,
                    'infor_proprio' => $proprio_nom.' '.$proprio_prenom,
                    'valeur' => $valeur
                ]);
            }

    }

    //TELECHARGER PDF DES MAISONS  AVEC LEURS CHAMBRES FILTER BY PROPRITOR
    public function getHouseChambrePdf(Request $request)
    {
        $decId = decrypt_id($request->route('id') ?? $request->id); abort_if(!$decId, 404);
        $request->merge(['id' => $decId]);
        $proprio = Proprietaire::where('id', $request->id)
                                ->whereNull('delete_at')
                                ->select('nom', 'prenom')
                                ->first();

        $element2['nom']    = $proprio ? $proprio->nom : '';
        $element2['prenom'] = $proprio ? $proprio->prenom : '';

         $element2['house'] = Maison::where('maisons.proprio_id',$request->id)
                                   ->join('chambres', 'chambres.maison_id', '=', 'maisons.id')
                                   ->join('prixes', 'prixes.chambre_id', '=', 'chambres.id')
                                   ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                                   ->where(function($querry){
                                    if (Gate::none(['Is_admin'])) {
                                        $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                                    }
                                    })
                                   ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
                                   ->whereNull('chambres.delete_at')
                                   ->whereNull('maisons.delete_at')
                                   ->get();

        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.house_chambre',compact('element2','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

        $pdf->output();

        activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Téléchargement du rapport des maisons et chambres'.$element2['nom'].' '.$element2['prenom'].' par '.Auth::user()->nom.' '.Auth::user()->prenom);


        return $pdf->download('Rapport du '.Carbon::now().'.pdf');
    }



    //TELECHARGER PDF DES MAISONS  AVEC LEURS locataire FILTER BY HOUSE
    public function getHouseLocatairePdf(Request $request)
    {
        $decId = decrypt_id($request->route('id') ?? $request->id); abort_if(!$decId, 404);
        $request->merge(['id' => $decId]);
          $element2['house_name']     = Maison::where('id',$request->id)
                                              ->whereNull('delete_at')
                                              ->select('nom_maison')
                                              ->get()
                                              ->pluck('nom_maison')[0];


          $element2['house'] = Maison::where('maisons.id',$request->id)
                                    ->join('chambres', 'chambres.maison_id', '=', 'maisons.id')
                                    ->join('locataires', 'locataires.chambre_id', '=', 'chambres.id')
                                    ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                                    ->where(function($querry){
                                        if (Gate::none(['Is_admin'])) {
                                            $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                                        }
                                    })
                                    ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
                                    ->whereNull('chambres.delete_at')
                                    ->whereNull('maisons.delete_at')
                                    //->where('locataires.status',true)
                                    ->get();

        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.house_locataire',compact('element2','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

        $pdf->output();

        activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Téléchargement du rapport des locataires de '.$element2['house_name'].' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return $pdf->download('Rapport de la '. $element2['house_name'].' du '.Carbon::now().'.pdf');
    }



    //STATISTIQUE SUR MAISONS ET LOCATAIRES

    public function getHouseAndLocataire(Request $request)
    {
        //setlocale(LC_TIME, 'French');

        $vide='';
        $valeur = '';

        $house_name     = Maison::where('id',$request->house_recu)
                               ->whereNull('delete_at')
                               ->select('nom_maison')
                                ->get()
                                ->pluck('nom_maison')[0];


        $donnees = Maison::where('maisons.id',$request->house_recu)
                          ->join('chambres', 'chambres.maison_id', '=', 'maisons.id')
                          ->join('locataires', 'locataires.chambre_id', '=', 'chambres.id')
                          ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                          ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                            }
                            })
                          ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
                          ->whereNull('chambres.delete_at')
                          ->whereNull('maisons.delete_at')
                          //->where('locataires.status',true)
                          ->get();

                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des locataires de '.$house_name.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        
            if(count($donnees) === 0)
            {
                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';
                return response()->json([
                    'list_locataire'=> $vide,
                    'infor_house' => $house_name,
                    'valeur2' => $valeur
                ]);
            }
            else
            {
                foreach ($donnees as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.e($value->numero_chambre).'</td><td>'.e($value->type_chambre).'</td><td>'.e($value->nom).' '.e($value->prenom).'</td>';
                    $vide.='<td>'.e($value->telephone).'</td><td>'.(int)$value->nombre_avance.' mois'.'</td>';
                    $vide.='<td>'.Carbon::parse($value->date_entree)->format('d/m/Y').'</td>';
                    $vide.='</tr>';
                }

                 $valeur .= '<a '.'class'.'='.' "btn rounded-pill btn-primary" '.
                              'title'.'='.' "Télécharger pdf" '.'href'.'='.'house-locataire/'.encrypt_id($request->house_recu).'>'.
                                 'Télécharger pdf'.
                               '</a>';


                return response()->json([
                    'list_locataire'=> $vide,
                    'infor_house' => $house_name,
                    'valeur2' => $valeur
                ]);
            }

    }


    #PARTIE DES RECUS

    public function getRecuView()
    {
        return view('statistique.recu');
    }


    #------------------------------------------------------------------#
    #         ANCIEN RECU POUR AVANCE                                  #
    #                                                                  #
    #------------------------------------------------------------------#


    public function getFactureByDateAvance(Request $request)
    {
        $vide = '';

        $facture =  Locataire::whereBetween('locataires.date_entree', [$request->date_debut, $request->date_fin])
                              ->whereNull('locataires.delete_at')
                              ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                              ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                              ->where('locataires.iddirection_ref',Auth::user()->iddirection_ref)
                              ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('locataires.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                             ->join('annexes', 'annexes.idannexes', '=', 'locataires.idannexe_ref')
                              ->select('annexes.designation','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','locataires.nom','locataires.prenom','locataires.profession','locataires.telephone','locataires.nombre_avance','locataires.date_entree','locataires.id','locataires.chambre_id')
                              ->get();

            activity()->performedOn(new Locataire())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des anciens réçu '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


            if(count($facture) === 0)
            {
                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';
                return response()->json([

                   'list_recu'=> $vide,
                    'titre2' => 'Ancien réçu pour avance du '.Carbon::parse($request->date_debut)->format('d/m/Y').' au '.Carbon::parse($request->date_fin)->format('d/m/Y')
                ]);
            }
            else
            {
                foreach ($facture as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.e($value->nom_maison).'</td><td>'.e($value->numero_chambre).'</td><td>'.e($value->nom).' '.e($value->prenom).'</td>';
                    $vide.='<td>'.e($value->profession).'</td><td>'.e($value->telephone).'</td>';
                    $vide.='<td>'.(int)$value->nombre_avance.' Mois'.'</td>';
                    $vide.='<td>'.Carbon::parse($value->date_entree)->format('d/m/Y').'</td><td>'
                              .'<a class="btn btn-sm btn-primary rounded-pill" href="gerer-facture/telecharge/'.encrypt_id($value->id).'">'
                              .'<i class="bx bx-download me-1"></i>Télécharger'
                              .'</a>'
                            .'</td>';
                    $vide.='</tr>';
                }


                return response()->json([
                    'list_recu'=> $vide,
                    'titre2' => 'Ancien réçu pour avance du '.Carbon::parse($request->date_debut)->format('d/m/Y').' au '.Carbon::parse($request->date_fin)->format('d/m/Y')


                ]);
            }
    }


    public function getFactureByLocataireNameAvance(Request $request)
    {
        $vide = '';

        $facture =  Locataire::where(function($q) use ($request) {
                                    $q->where('locataires.nom','like','%'.$request->user_name.'%')
                                      ->orWhere('locataires.prenom','like','%'.$request->user_name.'%');
                              })
                              ->where('locataires.iddirection_ref',Auth::user()->iddirection_ref)
                              ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('locataires.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                              })
                             ->join('annexes', 'annexes.idannexes', '=', 'locataires.idannexe_ref')
                              ->whereNull('locataires.delete_at')
                              ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                              ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                              ->select('annexes.designation','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','locataires.nom','locataires.prenom','locataires.profession','locataires.telephone','locataires.nombre_avance','locataires.date_entree','locataires.id','locataires.chambre_id')
                              ->get();

            activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des anciens réçu '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


            if(count($facture) === 0)
            {
                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';
                return response()->json([

                   'list_recu'=> $vide,
                    'titre2' => 'Ancien réçu pour le locataire '.$request->user_name
                ]);
            }
            else
            {
                foreach ($facture as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.e($value->nom_maison).'</td><td>'.e($value->numero_chambre).'</td><td>'.e($value->nom).' '.e($value->prenom).'</td>';
                    $vide.='<td>'.e($value->profession).'</td><td>'.e($value->telephone).'</td>';
                    $vide.='<td>'.(int)$value->nombre_avance.' Mois'.'</td>';
                    $vide.='<td>'.Carbon::parse($value->date_entree)->format('d/m/Y').'</td><td>'
                              .'<a class="btn btn-sm btn-primary rounded-pill" href="gerer-facture/telecharge/'.encrypt_id($value->id).'">'
                              .'<i class="bx bx-download me-1"></i>Télécharger'
                              .'</a>'
                            .'</td>';
                    $vide.='</tr>';
                }


                return response()->json([
                    'list_recu'=> $vide,
                    'titre2' => 'Ancien réçu pour le locataire '.$request->user_name

                ]);
            }
    }


    #------------------------------------------------------------------#
    #         ANCIEN RECU POUR LOCATION                                #
    #          DE CHAQUE MOIS                                          #
    #------------------------------------------------------------------#


    public function getFactureByDatePerMonths(Request $request)
    {
        $vide = '';
        $facture =  Facture::whereBetween('factures.date_paiement', [$request->date_debut2.' 00:00:00', $request->date_fin2.' 23:59:59'])
                            ->whereNull('factures.delete_at')
                            ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                            ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                            ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                            ->where('factures.iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('factures.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                             ->join('annexes', 'annexes.idannexes', '=', 'factures.idannexe_ref')
                            ->whereNull('maisons.delete_at')
                            ->whereNull('chambres.delete_at')
                            ->whereNull('locataires.delete_at')
                            ->where('locataires.status',true)
                            ->select('annexes.designation','factures.id','maisons.nom_maison','chambres.numero_chambre','locataires.nom','locataires.prenom','factures.montant','factures.mois','factures.type_paiement','factures.date_paiement','factures.maison_id','factures.chambre_id','factures.locataire_id','chambres.type_chambre')
                            ->get();

                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des anciens réçu '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

            if(count($facture) === 0)
            {
                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';
                return response()->json([
                    'list_locataireP'=> $vide,
                    'titre' => 'Ancien réçu de loyer du '.Carbon::parse($request->date_debut2)->format('d/m/Y').' au '.Carbon::parse($request->date_fin2)->format('d/m/Y')
                ]);
            }
            else
            {
                foreach ($facture as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.e($value->nom_maison).'</td><td>'.e($value->numero_chambre).'</td><td>'.e($value->nom).' '.e($value->prenom).'</td>';
                    $vide.='<td>'.format_price($value->montant).'</td><td>'.e($value->mois).'</td>';
                    $vide.='<td>'.Carbon::parse($value->date_paiement)->format('d/m/Y H:i').'</td><td>'
                              .'<a class="btn btn-sm btn-success rounded-pill" href="gerer-facture/telecharge2/'.encrypt_id($value->id).'">'
                              .'<i class="bx bx-download me-1"></i>Télécharger'
                              .'</a>'
                            .'</td>';
                    $vide.='</tr>';
                }

                return response()->json([
                    'list_locataireP'=> $vide,
                    'titre' => 'Ancien réçu de loyer du '.Carbon::parse($request->date_debut2)->format('d/m/Y').' au '.Carbon::parse($request->date_fin2)->format('d/m/Y')

                ]);
            }
    }


     public function getFactureByLocataireNamePerMonths(Request $request)
    {

        $vide = '';
        $facture =  Facture::where(function($q) use ($request) {
                                $q->where('locataires.nom','like','%'.$request->user_name2.'%')
                                  ->orWhere('locataires.prenom','like','%'.$request->user_name2.'%');
                            })
                            ->where('factures.iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('factures.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                             })
                             ->join('annexes', 'annexes.idannexes', '=', 'factures.idannexe_ref')
                            ->whereNull('factures.delete_at')
                            ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                            ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                            ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                            ->whereNull('maisons.delete_at')
                            ->whereNull('chambres.delete_at')
                            ->whereNull('locataires.delete_at')
                            ->where('locataires.status',true)
                            ->select('annexes.designation','factures.id','maisons.nom_maison','chambres.numero_chambre','locataires.nom','locataires.prenom','factures.montant','factures.mois','factures.type_paiement','factures.date_paiement','factures.maison_id','factures.chambre_id','factures.locataire_id','chambres.type_chambre')
                            ->get();

                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des anciens réçu '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

            if(count($facture) === 0)
            {
                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';
                return response()->json([
                    'list_locataireP'=> $vide,
                    'titre' => 'Ancien réçu de loyer de '.$request->user_name2
                ]);
            }
            else
            {
                foreach ($facture as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.e($value->nom_maison).'</td><td>'.e($value->numero_chambre).'</td><td>'.e($value->nom).' '.e($value->prenom).'</td>';
                    $vide.='<td>'.format_price($value->montant).'</td><td>'.e($value->mois).'</td>';
                    $vide.='<td>'.Carbon::parse($value->date_paiement)->format('d/m/Y H:i').'</td><td>'
                              .'<a class="btn btn-sm btn-success rounded-pill" href="gerer-facture/telecharge2/'.encrypt_id($value->id).'">'
                              .'<i class="bx bx-download me-1"></i>Télécharger'
                              .'</a>'
                            .'</td>';
                    $vide.='</tr>';
                }

                return response()->json([
                    'list_locataireP'=> $vide,
                    'titre' => 'Ancien réçu de loyer de '.$request->user_name2

                ]);
            }
    }


    #------------------------------------------------------------------#
    #      PARTIE DE LA FINANCE                                        #
    #------------------------------------------------------------------#

    public function getFinanceView()
    {
        $data = Proprietaire::whereNull('delete_at')
                          ->where('iddirection_ref',Auth::user()->iddirection_ref)
                          ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                            }
                            })
                          ->get();

        $pourcentages = get_all_pourcentages_for_direction();

        return view('statistique.financier',['data' => $data, 'pourcentages' => $pourcentages]);
    }

    //GET LE MONTANT A PAYER AU PROPRIETAIRE POUR CHAQUE

    private function get_maison_data($proprioId,$date1,$date2)
    {
        return Maison::where('maisons.proprio_id',$proprioId)
                        ->whereBetween('factures.created_at', [$date1.' 01:00:00',$date2.' 23:59:59'])
                        ->join('factures', 'factures.maison_id', '=', 'maisons.id')
                        ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                        ->where(function($querry){
                        if (Gate::none(['Is_admin'])) {
                            $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                        }
                        })
                        ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
                        ->whereNull('maisons.delete_at')
                        ->select('annexes.designation','maisons.nom_maison','maisons.quartier','factures.montant','factures.numero_chambre','factures.type_chambre')
                        ->get();
                    
    }



    public function getProprietaireSolde(Request $request)
    {
        $vide = '';
        $vide2 = '';
        $pdf = '';

        $proprio_nom = $this->getProprietaireNom($request->proprietaire);
        $proprio_prenom = $this->getProprietairePrenom($request->proprietaire);

        $donnees = $this->get_maison_data($request->proprietaire,$request->date_debut,$request->date_fin);

        $pourcentage = get_pourcentage_gestion_or_null($request->proprietaire) ?? (float)($request->pourcentage ?? 10);

        activity()->performedOn(new Proprietaire())
                   ->causedBy(Auth::user()->id)
                   ->log('Consultation de la statistique du montant à payer à '. $proprio_nom.' '. $proprio_prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

       if(count($donnees) === 0)
       {

           $vide.='<tr> <td colspan="13">'.__('pdf.no_result').'</td><tr>';
           return response()->json([
               'infos_solde'=> $vide,
               'somme_solde'=> $vide2,
               'pdf' => $pdf,
                'titre' => 'Fiche de paie de '.$proprio_nom.' '.$proprio_prenom.' du '.Carbon::parse($request->date_debut)->format('d/m/Y').' au '.Carbon::parse($request->date_fin)->format('d/m/Y')

           ]);
       }
       else
       {
           $garde = 0 ;

           foreach ($donnees as $value) {
               $vide.='<tr>';
               $vide.='<td>'.e($value->nom_maison).'</td><td>'.e($value->quartier).'</td>';
               $vide.='<td>'.e($value->type_chambre).' ( N° '.e($value->numero_chambre).')'.'</td><td>'.number_format($value->montant,"0",",",".").' XOF'. '</td>';
               $vide.='<td>'.number_format( ( $value->montant * (100 - $pourcentage) ) / 100,"0",",",".").' XOF'.'</td>';
               $vide.='</tr>';



               $garde += $value->montant * (100 - $pourcentage)  / 100;
           }

           $vide2.='<td>'.number_format($garde ,"0",",",".").' XOF'.'</td>';

           //CREATION DU LIEN POUR TELECHARGEMENT PDF
           $pdf = '<a '.'class'.'='.' "bx bx-download me-1" '.
                  'title'.'='.' "Télécharger réçu" '.'href'.'='.'pdf-solde-proprietor/'.encrypt_id($request->proprietaire).'/'.$pourcentage.'/'.$request->date_debut.'/'.$request->date_fin.'>'.

                  '</a>';

           return response()->json([
               'infos_solde'=> $vide,
               'somme_solde'=> $vide2,
               'pdf' => $pdf,
                'titre' => 'Fiche de paie de '.$proprio_nom.' '.$proprio_prenom.' du '.Carbon::parse($request->date_debut)->format('d/m/Y').' au '.Carbon::parse($request->date_fin)->format('d/m/Y')

           ]);
       }
    }


    //TELECHARGER PDF DU SOLDE A PAYER AU PROPRIETAIRE
    public function getPropriotorSoldePdf(Request $request)
    {
        $decId = decrypt_id($request->route('id') ?? $request->id); abort_if(!$decId, 404);
        $request->merge(['id' => $decId]);
        $element2['proprio_nom'] = $this->getProprietaireNom($request->id);
        $element2['proprio_prenom'] = $this->getProprietairePrenom($request->id);

        $element2['date_debut'] = $request->date_debut;
        $element2['date_fin'] = $request->date_fin;

        $pctResolved = get_pourcentage_gestion_or_null($request->id) ?? (float)($request->pourcentage ?? 10);
        $element2['pourcentage'] = $pctResolved;

        $element2['donnees'] = $this->get_maison_data($request->id,$request->date_debut,$request->date_fin);

        //Total
        $element2['garde'] = 0;
        foreach ($element2['donnees'] as $value) {
          $element2['garde'] += $value->montant * (100 - $pctResolved) / 100;
        }


        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.proprio_solde',compact('element2','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

        $pdf->output();

        activity()->performedOn(new Proprietaire())
                     ->causedBy(Auth::user()->id)
                     ->log('Téléchargement du fiche de paie de '.$element2['proprio_nom'].' '.$element2['proprio_prenom'].' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return $pdf->download('Fiche de paie de '.$element2['proprio_nom'].$element2['proprio_prenom'].' du '.Carbon::now().'.pdf');
    }



    //GET LE MONTANT A PAYER A L'AGENCE POUR CHAQUE MOIS PAR PROPRIETAIRE
    public function getAgenceSoldeByProprio(Request $request)
    {
        $vide = '';
        $vide2 = '';
        $pdf = '';

        $proprio_nom = $this->getProprietaireNom($request->proprietaire2);
        $proprio_prenom = $this->getProprietairePrenom($request->proprietaire2);

        $donnees = $this->get_maison_data($request->proprietaire2,$request->date_debut2,$request->date_fin2);

        $pourcentage2 = get_pourcentage_gestion_or_null($request->proprietaire2) ?? (float)($request->pourcentage2 ?? 10);

          activity()->performedOn(new Proprietaire())
                     ->causedBy(Auth::user()->id)
                     ->log('Consultation de la statistique du montant réçu de l\'agence chez '. $proprio_nom.' '. $proprio_prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

       if(count($donnees) === 0)
       {
           $vide.='<tr> <td colspan="13">'.__('pdf.no_result').'</td><tr>';
           return response()->json([
               'infos_solde2'=> $vide,
               'somme_solde2'=> $vide2,
               'pdf2' => $pdf,
                'titre2' => 'Bénéfice réalisé chez '.$proprio_nom.' '.$proprio_prenom.' entre '.Carbon::parse($request->date_debut2)->format('d/m/Y').' au '.Carbon::parse($request->date_fin2)->format('d/m/Y')

           ]);
       }
       else
       {
           $garde = 0 ;

           foreach ($donnees as $value) {
               $vide.='<tr>';
               $vide.='<td>'.e($value->nom_maison).'</td><td>'.e($value->quartier).'</td>';
               $vide.='<td>'.e($value->type_chambre).' (N° '.e($value->numero_chambre).')'. '</td><td>'.number_format($value->montant,"0",",",".").' XOF'. '</td>';
               $vide.='<td>'.number_format( ( $value->montant * $pourcentage2 ) / 100,"0",",",".").' XOF'.'</td>';
               $vide.='</tr>';



               $garde += ( $value->montant * $pourcentage2 )  / 100;
           }

           $vide2.='<td>'.number_format($garde ,"0",",",".").' XOF'.'</td>';

           //CREATION DU LIEN POUR TELECHARGEMENT PDF
           $pdf = '<a '.'class'.'='.' "btn btn-primary rounded-pill ri-arrow-down-circle-fill shadow" '.
                  'title'.'='.' "Télécharger réçu" '.'href'.'='.'pdf-solde-agence/'.encrypt_id($request->proprietaire2).'/'.$pourcentage2.'/'.$request->date_debut2.'/'.$request->date_fin2.'>'.
                     'Télécharger'.
                  '</a>';

           return response()->json([
               'infos_solde2'=> $vide,
               'somme_solde2'=> $vide2,
               'pdf2' => $pdf,
                'titre2' => 'Bénéfice réalisé chez '.$proprio_nom.' '.$proprio_prenom.' entre '.Carbon::parse($request->date_debut2)->format('d/m/Y').' au '.Carbon::parse($request->date_fin2)->format('d/m/Y')

           ]);
       }
    }


    //TELECHARGER PDF DU SOLDE A PAYER AU PROPRIETAIRE
    public function getAgencySoldePdf(Request $request)
    {
        $decId2 = decrypt_id($request->route('id2') ?? $request->id2); abort_if(!$decId2, 404);
        $request->merge(['id2' => $decId2]);
        $element2['proprio_nom'] = $this->getProprietaireNom($request->id2);
        $element2['proprio_prenom'] = $this->getProprietairePrenom($request->id2);

        $element2['date_debut'] = $request->date_debut2;
        $element2['date_fin'] = $request->date_fin2;

        $pctResolved2 = get_pourcentage_gestion_or_null($request->id2) ?? (float)($request->pourcentage2 ?? 10);
        $element2['pourcentage'] = $pctResolved2;

        $element2['donnees'] = $this->get_maison_data($request->id2,$request->date_debut2,$request->date_fin2);

        //Total
        $element2['garde'] = 0;
        foreach ($element2['donnees'] as $value) {
          $element2['garde'] += $value->montant * $pctResolved2 / 100;
        }


        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.agence_solde',compact('element2','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

        $pdf->output();


         activity()->performedOn(new Proprietaire())
                     ->causedBy(Auth::user()->id)
                     ->log('Téléchargement du point des bénéfices réalisé chez le propriétaire '.$element2['proprio_nom'].' '.$element2['proprio_prenom'].' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return $pdf->download('Bénéfice réalisé chez '.$element2['proprio_nom'].$element2['proprio_prenom'].' du '.Carbon::now().'.pdf');
    }


    //GET LE TOTAL SELON LA DATE DE TOUS LES PAYEMENTS DE TOUS PROPRIO A L'AGENCE

    private function get_house_data_by_date($date1,$date2)
    {
        return Maison::whereBetween('factures.created_at', [$date1.' 01:00:00',$date2.' 23:59:59'])
                        ->join('factures', 'factures.maison_id', '=', 'maisons.id')
                        ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                        ->where(function($querry){
                        if (Gate::none(['Is_admin'])) {
                            $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                        }
                        })
                        ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
                        ->whereNull('maisons.delete_at')
                        ->select('annexes.designation','maisons.nom_maison','maisons.quartier','maisons.proprio_id','factures.montant','factures.numero_chambre','factures.type_chambre')
                        ->get();

    }



    public function getAllPaymentToAgenceSoldeByDate(Request $request)
    {
        $vide = '';
        $vide2 = '';
        $pdf = '';

        $donnees = $this->get_house_data_by_date($request->date_debut_general,$request->date_fin_general);

        activity()->performedOn(new Proprietaire())
                   ->causedBy(Auth::user()->id)
                   ->log('Consultation de la statistique du bénéfice de l\'agence chez tous les propriétaires '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

       if(count($donnees) === 0)
       {
           $vide.='<tr> <td colspan="7">Aucune donné trouvée</td></tr>';
           return response()->json([
               'infos_solde_general'=> $vide,
               'somme_solde_general'=> $vide2,
               'pdf_general' => $pdf,
               'titre_general' => 'Bénéfice réalisé chez tous les propriétaires entre '.$request->date_debut_general.' au '.$request->date_fin_general
           ]);
       }
       else
       {
           $garde = 0;

           foreach ($donnees as $value) {
               $pct = get_pourcentage_gestion($value->proprio_id);
               $commission = ($value->montant * $pct) / 100;
               $garde += $commission;

               $vide.='<tr>';
               $vide.='<td>'.$value->nom_maison.'</td><td>'.$value->quartier.'</td>';
               $vide.='<td>'.$value->type_chambre.' (N° '.$value->numero_chambre.')</td>';
               $vide.='<td>'.format_price($value->montant).'</td>';
               $vide.='<td>'.$pct.' %</td>';
               $vide.='<td>'.format_price($commission).'</td>';
               $vide.='</tr>';
           }

           $vide2.='<td>'.format_price($garde).'</td>';

           $pdf = '<a '.'class'.'='.' "btn btn-primary rounded-pill ri-arrow-down-circle-fill shadow" '.
                  'title'.'='.' "Télécharger réçu" '.'href'.'='."pdf-all-solde-agence/$request->date_debut_general/$request->date_fin_general".'>'.
                     'Télécharger'.
                  '</a>';

           return response()->json([
               'infos_solde_general'=> $vide,
               'somme_solde_general'=> $vide2,
               'pdf_general' => $pdf,
               'titre_general' => 'Bénéfice réalisé chez tous les propriétaires entre '.Carbon::parse($request->date_debut_general)->format('d/m/Y').' au '.Carbon::parse($request->date_fin_general)->format('d/m/Y')
           ]);
       }
    }



    //TELECHARGER PDF DU SOLDE A PAYER FOR ALL PAYMENT
    public function getAllPaymentToAgencySoldePdf(Request $request)
    {
        $element2['date_debut'] = $request->date_debut_general;
        $element2['date_fin'] = $request->date_fin_general;

        $donnees = $this->get_house_data_by_date($request->date_debut_general,$request->date_fin_general);

        // Enrichir chaque ligne avec le pourcentage propre au propriétaire
        $element2['donnees'] = $donnees->map(function ($item) {
            $item->pourcentage = get_pourcentage_gestion($item->proprio_id);
            $item->commission  = ($item->montant * $item->pourcentage) / 100;
            return $item;
        });

        $element2['garde'] = $element2['donnees']->sum('commission');

        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.solde_general',compact('element2','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

        $pdf->output();

        activity()->performedOn(new Proprietaire())
                     ->causedBy(Auth::user()->id)
                     ->log('Téléchargement du point des bénéfices de l\'agence chez tous les propriétaires '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return $pdf->download('Bénéfice réalisé chez tous les propriétaires'.' du '.Carbon::now().'.pdf');
    }



    #############################################################
    #                                                           #
    #                CLIENT ET PARCELLE                         #
    #                                                           #
    #############################################################         
    public function getviewDossier()
    {
      return view('statistique.dossier');
    }
    
    #PARTIE CLIENT

    private function get_client_liste($date1,$date2)
    {
        return Client::whereBetween('clients.created_at',[$date1.' 01:00:00', $date2.' 23:59:59'])
                    ->where('clients.iddirection_ref',Auth::user()->iddirection_ref)
                    ->where(function($querry){
                        if (Gate::none(['Is_admin'])) {
                            $querry->where('clients.idannexe_ref',Auth::user()->idannexe_ref);
                        }
                    })
                    ->join('annexes', 'annexes.idannexes', '=', 'clients.idannexe_ref')
                    ->whereNotNull('clients.status')
                    ->get();

    }

    public function getDossierClientByDate(Request $request)
    {
        $vide = '';

        $dossierClient =  $this->get_client_liste($request->date_debut,$request->date_fin);


            activity()->performedOn(new Client())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des dossiers client '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


            if(count($dossierClient) === 0)
            {
                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';

                return response()->json([
                   'list_client'=> $vide,
                    'titre2' => 'Liste des dossiers des clients déjà traité du '.Carbon::parse($request->date_debut)->format('d/m/Y').' au '.Carbon::parse($request->date_fin)->format('d/m/Y')
                ]);
            }
            else
            {
                foreach ($dossierClient as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.$value->nom.' '.$value->prenom.'</td>';
                    $vide .= '<td>'.$value->telephone.'</td><td>'.$value->zone_voulu.'</td>';
                    $vide.='<td>'.$value->superficie.' m²'.'</td><td>'.number_format( $value->budget,"0",",",".").' XOF'.'</td>';
                    $vide.='<td>'.strftime("%d/%m/%Y", strtotime($value->status)) .'</td>';
                    $vide.='</tr>';
                }

                 $pdf = '<a '.'class'.'='.' "btn btn-primary rounded-pill ri-arrow-down-circle-fill shadow" '.
                  'title'.'='.' "Télécharger pdf" '.'href'.'='."pdf-client-dossier/$request->date_debut/$request->date_fin".'>'.
                     'Télécharger pdf'.
                  '</a>';

                return response()->json([
                   'list_client'=> $vide,
                   'pdf' => $pdf,
                    'titre2' => 'Liste des dossiers des clients déjà traité du '.Carbon::parse($request->date_debut)->format('d/m/Y').' au '.Carbon::parse($request->date_fin)->format('d/m/Y')
                ]);

            }
    }

    
    public function getClientDossierPdf(Request $request)
    {

        $element2['date_debut'] = $request->date_debut;
        $element2['date_fin'] = $request->date_fin;

        $element2['donnees'] = $this->get_client_liste($request->date_debut,$request->date_fin); 


        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.client_dossier',compact('element2','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

        $pdf->output();

        activity()->performedOn(new Client())
                     ->causedBy(Auth::user()->id)
                     ->log('Téléchargement du point des dossiers des clients'.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return $pdf->download('Point des dossiers clients du '.Carbon::now().'.pdf');
    }

    #PARTIE PARCELLE

    private function asking_for_data($date1,$date2)
    {
        return Parcelle::whereBetween('parcelles.created_at',[$date1.' 01:00:00',$date2.' 23:59:59'])
                        ->where('parcelles.iddirection_ref',Auth::user()->iddirection_ref)
                        ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('parcelles.idannexe_ref',Auth::user()->idannexe_ref);
                            }
                        })
                        ->join('annexes', 'annexes.idannexes', '=', 'parcelles.idannexe_ref')
                        ->join('clients', 'clients.id', '=', 'parcelles.client_acheteur')
                        ->select('annexes.designation','parcelles.nom','parcelles.prenom','parcelles.telephone','parcelles.quartier','parcelles.superficie','parcelles.prix','parcelles.status','clients.nom as nomCli','clients.prenom as prenomCli')
                        ->whereNotNull('parcelles.status')
                        ->get();

    }

    public function getDossierParcelleByDate(Request $request)
    {
        $vide = '';

        $dossierParcelle =  $this->asking_for_data($request->date_debut2,$request->date_fin2);

            activity()->performedOn(new Parcelle())
                           ->causedBy(Auth::user()->id)
                           ->log('Consultation de la statistique des dossiers des parcelles'.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


            if(count($dossierParcelle) === 0)
            {
                $vide.='<tr> <td colspan="13">Aucune donné trouvée</td><tr>';

                return response()->json([
                   'list_parcelle'=> $vide,
                    'titre2' => 'Liste des dossiers des parcelles déjà traité du '.Carbon::parse($request->date_debut2)->format('d/m/Y').' au '.Carbon::parse($request->date_fin2)->format('d/m/Y')
                ]);
            }
            else
            {
                foreach ($dossierParcelle as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.$value->nom.' '.$value->prenom.'</td>';
                    $vide .= '<td>'.$value->telephone.'</td><td>'.$value->quartier.'</td>';
                    $vide.='<td>'.$value->superficie.' m²'.'</td><td>'.number_format( $value->prix,"0",",",".").' XOF'.'</td>';
                    $vide.='<td>'.$value->nomCli.' '.$value->prenomCli.'</td>';
                    $vide.='<td>'.strftime("%d/%m/%Y", strtotime($value->status)) .'</td>';
                    $vide.='</tr>';
                }

                $pdf2 = '<a '.'class'.'='.' "btn btn-primary rounded-pill ri-arrow-down-circle-fill shadow" '.
                  'title'.'='.' "Télécharger pdf" '.'href'.'='."pdf-parcelle-dossier/$request->date_debut2/$request->date_fin2".'>'.
                     'Télécharger pdf'.
                  '</a>';

                return response()->json([
                   'list_parcelle'=> $vide,
                   'pdf2' => $pdf2,
                    'titre2' => 'Liste des dossiers des parcelles déjà traité du '.Carbon::parse($request->date_debut2)->format('d/m/Y').' au '.Carbon::parse($request->date_fin2)->format('d/m/Y')
                ]);

            }   
    }



    public function getParcelleDossierPdf(Request $request)
    {

        $element2['date_debut'] = $request->date_debut2;
        $element2['date_fin'] = $request->date_fin2;

        $element2['donnees'] =  $this->asking_for_data($request->date_debut2,$request->date_fin2);



        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.parcelle_dossier',compact('element2','annexeData'))->setOptions(['defaultFont' => 'sans-serif']);

        $pdf->output();

        activity()->performedOn(new Client())
                     ->causedBy(Auth::user()->id)
                     ->log('Téléchargement du point des dossiers des parcelles'.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return $pdf->download('Point des dossiers parcelles du '.Carbon::now().'.pdf');
    }






}
