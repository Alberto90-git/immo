<?php

namespace App\Http\Controllers;

use App\Facture;
use App\Maison;
use App\Chambre;
use App\Prix;
use App\Locataire;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class FactureController extends Controller
{
    
    public function index()
    {
       try {
            $allMaison = Maison::whereNull('delete_at')
                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                ->where(function($querry){
                                  if (Gate::none(['Is_admin'])) {
                                      $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                  }
                                })
                                ->get();

            $allChambres = Chambre::join('maisons', 'chambres.maison_id', '=', 'maisons.id')
                                  ->where('chambres.iddirection_ref',Auth::user()->iddirection_ref)
                                  ->where(function($querry){
                                    if (Gate::none(['Is_admin'])) {
                                        $querry->where('chambres.idannexe_ref',Auth::user()->idannexe_ref);
                                    }
                                  })
                                  ->whereNull('chambres.delete_at')
                                  ->whereNull('maisons.delete_at')
                                  ->select('chambres.maison_id','chambres.id','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','chambres.etat')
                                  ->get();

            
            $allFacture = Facture::whereNull('factures.delete_at')
                            ->where('factures.iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                              if (Gate::none(['Is_admin'])) {
                                  $querry->where('factures.idannexe_ref',Auth::user()->idannexe_ref);
                              }
                             })
                            ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                            ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                            ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                            ->whereNull('maisons.delete_at')
                            ->whereNull('chambres.delete_at')
                            ->whereNull('locataires.delete_at')
                            ->where('locataires.status',true)
                            ->whereMonth('factures.created_at', Carbon::now()->format('m'))
                            ->select('factures.iddirection_ref','factures.idannexe_ref','factures.id','maisons.nom_maison','chambres.numero_chambre','locataires.nom','locataires.prenom','factures.montant','factures.mois','factures.type_paiement','factures.date_paiement','factures.maison_id','factures.chambre_id','factures.locataire_id','chambres.type_chambre')
                            ->get();

                            


            return view('facture.facture', compact(['allFacture','allMaison']));

       } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
       }
    }


    public function getNumeroChambre(Request $request)
    {
            $vide = '';
    
            $vide.="<option disabled selected>Choisir une chambre</option>";
    
            $val = Chambre::where('maison_id',$request->idMaison)
                          ->where('iddirection_ref',Auth::user()->iddirection_ref)
                          ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                            }
                          })
                          ->whereNull('delete_at')
                          ->get();
    
             foreach ($val as  $cont) {
                 $vide.="<option value=".$cont->id.">".$cont->numero_chambre."</option>";
             }
    
            return response()->json([
                                    'list_chambre' => $vide,
                                    ]);
    }

    public function getTypeChambre(Request $request)
    {

            $vide = Chambre::where('id',$request->numero_chambre_got)
                         ->get()
                         ->pluck('type_chambre')[0];

            $sonPrix = Prix::where('chambre_id',$request->numero_chambre_got)
                             ->whereNull('delete_at')
                             ->where('status',true)
                             ->get()
                             ->pluck('prix')[0];

            $nom = Locataire::where('chambre_id',$request->numero_chambre_got)
                             ->whereNull('delete_at')
                             ->where('status',true)
                             ->get()
                             ->pluck('nom')[0];

            $prenom = Locataire::where('chambre_id',$request->numero_chambre_got)
                             ->whereNull('delete_at')
                             ->where('status',true)
                             ->get()
                             ->pluck('prenom')[0];

                             
    
            return response()->json([
                                    'type_chambres_get' => $vide,
                                    'sonPrix' => $sonPrix,
                                    'sonLocataire' => $nom.' '.$prenom
                                    ]);
    }


    public function connaitreLocataire($id)
    {
      $obj = Locataire::where('id',$id)->whereNull('delete_at')->first();

      return $obj->nom.' '.$obj->prenom;
    }

    public function numeChambre($id)
    {
      $num = Chambre::where('id',$id)->whereNull('delete_at')->first();

      return $num->numero_chambre;
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'nom_maison' => ['bail','required'],
                    'numero_chambre' => ['bail','required'],
                    'montant' => ['bail','required'],
                    'mois' => ['bail','required'],
                    'date_paiement' => ['bail','required'],
                    'type_paiement' => ['bail','required'],
                ],
            );



            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

              $response = $this->check_is_admin_and_entreprise();

                if ($response) {
                    $idannexe_ref = $request->annexe;
                }else {
                    $idannexe_ref = Auth::user()->idannexe_ref;
                }


            $locataire_id = Locataire::where('chambre_id',$request->numero_chambre)
                                        ->whereNull('delete_at')
                                        ->where('status',true)
                                        ->get()
                                        ->pluck('id')[0];

            #VIRIFIER SI LE MONTANT CORRESPOND
            if ( str_replace(" ", "", $request->montant) == $request->sonPrix) {
              #DEBUT

              if ($request->type_paiement == 'direct') {

                

              $facture = Facture::create([
                                    'maison_id' => $request->nom_maison,
                                    'chambre_id' => $request->numero_chambre,
                                    'locataire_id' => $locataire_id,
                                    'date_paiement' => $request->date_paiement,
                                    'montant' => str_replace(" ", "", $request->montant),
                                    'type_chambre' => $request->type_chambre,
                                    'numero_chambre' => FactureController::numeChambre($request->numero_chambre),
                                    'mois' => $request->mois,
                                    'type_paiement' => $request->type_paiement,
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                    ]);

                if ($facture) {

                   activity()->performedOn(new Facture())
                           ->causedBy(Auth::user()->id)
                           ->log('Paiement effectué pour le locataire '.FactureController::connaitreLocataire($locataire_id).' par '.Auth::user()->nom.' '.Auth::user()->prenom);


                    return response()->json([
                        'status' => true,
                        'message' => "Paiement effectué avec succès, aller télécharger le réçu",
                    ]);
                }

            } else {
              //RECUPERATION DU NOMBRE D'AVANCE QUI RESTE
               $nombre_avance_restant = Locataire::where('id',$locataire_id)
                                                  ->whereNull('delete_at')
                                                  ->where('status',true)
                                                  ->get()
                                                  ->pluck('nombre_avance')[0];

                $nombre_avance_consomme = Locataire::where('id',$locataire_id)
                                                  ->whereNull('delete_at')
                                                  ->where('status',true)
                                                  ->get()
                                                  ->pluck('nombre_avance_consomme')[0];

                if ($nombre_avance_restant == $nombre_avance_consomme) {

                  return response()->json([
                        'status' => false,
                        'message' => "Paiement impossible, nombre d'avance épuisé pour ce locataire",
                    ]);

                } else {
                  // Mettre à jour le nombre d'avance consommé

                  $diminue_nombre_avance = Locataire::where('id',$locataire_id)
                                                     ->whereNull('delete_at')
                                                     ->where('status',true)
                                                     ->update([
                                                        'nombre_avance_consomme' => $nombre_avance_consomme +1,
                                                     ]);
                  //Je crée le paiement
                  if ($diminue_nombre_avance) {

                    $facture = Facture::create([
                                    'maison_id' => $request->nom_maison,
                                    'chambre_id' => $request->numero_chambre,
                                    'locataire_id' => $locataire_id,
                                    'date_paiement' => $request->date_paiement,
                                    'montant' => str_replace(" ", "", $request->montant),
                                    'type_chambre' => $request->type_chambre,
                                    'numero_chambre' => FactureController::numeChambre($request->numero_chambre),
                                    'mois' => $request->mois,
                                    'type_paiement' => $request->type_paiement,
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                    ]); 

                    if ($facture) {

                      activity()->performedOn(new Facture())
                           ->causedBy(Auth::user()->id)
                           ->log('Paiement effectué pour le locataire '.FactureController::connaitreLocataire($locataire_id).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                      return response()->json([
                        'status' => true,
                        'message' => "Paiement effectué avec succès, aller télécharger le réçu",
                      ]); 

                    }

                  } else {

                    return response()->json([
                        'status' => false,
                        'message' => "Il y a un soucis",
                    ]); 

                  }
                  
                }
                
            }
            
              #FIN
            } else {

              return response()->json([
                'status' => false,
                'message' => "Le montant saissi ne correspond pas au loye attendu",
              ]);

            }
            
        } catch (QueryException $e) {

            return response()->json([
                'status' => false,
                'message' => "Echec,essayez encore",
            ]);

        }
    }



    public function update(Request $request)
    {
        try {

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $request->annexe;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

            if ($request->type_paiement == 'direct') {

                $exist  = Facture::where('id',$request->facture_id2)
                                ->where('type_paiement',"avance")
                                ->first();

                if($exist){

                  $nombre_avance = FactureController::requeteData($request->locataire_id2,'nombre_avance_consomme');

                  $facture = Locataire::where('id',$request->locataire_id2)
                                        ->update([
                                          'nombre_avance_consomme' => $nombre_avance - 1,
                                        ]);
                }

                $facture = Facture::where('id',$request->facture_id2)
                                 ->update([
                                    'maison_id' => $request->maison_id2,
                                    'chambre_id' => $request->chambre_id2,
                                    'locataire_id' => $request->locataire_id2,
                                    'date_paiement' => $request->date_paiement,
                                    'montant' => $request->montant,
                                    'mois' => $request->mois,
                                    'type_paiement' => $request->type_paiement,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);
            
                if ($facture) {

                  activity()->performedOn(new Facture())
                           ->causedBy(Auth::user()->id)
                           ->log('Modification du paiement pour le locataire '.FactureController::connaitreLocataire($request->locataire_id2).' par '.Auth::user()->nom.' '.Auth::user()->prenom);


                  return back()->with('message','Paiement modifié avec succès');
                }
            }else{

                  //RECUPERATION DU NOMBRE D'AVANCE QUI RESTE
                $nombre_avance_restant = Locataire::where('id',$request->locataire_id2)
                                                    ->whereNull('delete_at')
                                                    ->where('status',true)
                                                    ->get()
                                                    ->pluck('nombre_avance')[0];

                $nombre_avance_consomme = Locataire::where('id',$request->locataire_id2)
                                                    ->whereNull('delete_at')
                                                    ->where('status',true)
                                                    ->get()
                                                    ->pluck('nombre_avance_consomme')[0];

                if ($nombre_avance_restant == $nombre_avance_consomme) {
                  return back()->with('error',"Modification de paiement impossible, nombre d'avance épuisé pour ce locataire");
                } 
                else {
                    // Mettre à jour le nombre d'avance consommé

                    $diminue_nombre_avance = Locataire::where('id',$request->locataire_id2)
                                                      ->whereNull('delete_at')
                                                      ->where('status',true)
                                                      ->update([
                                                        'nombre_avance_consomme' => $nombre_avance_consomme +1,
                                                      ]);
                                    //Je crée le paiement
                    if ($diminue_nombre_avance) {

                      $facture = Facture::where('id',$request->facture_id2)
                                            ->update([
                                              'maison_id' => $request->maison_id2,
                                              'chambre_id' => $request->chambre_id2,
                                              'locataire_id' => $request->locataire_id2,
                                              'date_paiement' => $request->date_paiement,
                                              'montant' => $request->montant,
                                              'mois' => $request->mois,
                                              'type_paiement' => $request->type_paiement,
                                              'idannexe_ref' => $idannexe_ref,
                                          ]);

                        if ($facture) {

                          activity()->performedOn(new Facture())
                                    ->causedBy(Auth::user()->id)
                                    ->log('Modification de paiement effectué pour le locataire '.FactureController::connaitreLocataire($request->locataire_id2).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                          return back()->with('message',"Modification de paiement effectué avec succès, aller télécharger le réçu");

                        }
                    } else {
                      return back()->with('error',"Il y a un soucis");
                    }

                }

            }

        } catch (QueryException $e) {
            return back()->with('error',"Echéc, veuillez verifier les données");
        }
    }

    public function destroy(Request $request)
    {
        try {
            $deletedValue = Locataire::where('id',$request->facture_id_destroy)->first();
            
            $deleted = Facture::where('id',$request->facture_id_destroy)->update(['delete_at' => Carbon::now()]);

            if ($deleted) {

              activity()->performedOn(new Facture())
                           ->causedBy(Auth::user()->id)
                           ->log('Suppression du paiement pour le locataire '.$deletedValue->nom.' '.$deletedValue->prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','Suppression effectuée avec succès');
            }
            
        } catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

    #FACTURE DE CHAQUE MOIS

    public function requeteDataFactureMois($id,$champs)
    {
       return   Facture::where('factures.id',$id)
                               ->join('maisons','factures.maison_id','=','maisons.id')
                               ->join('chambres','factures.chambre_id','=','chambres.id')
                               ->join('locataires','factures.locataire_id','=','locataires.id')
                               ->whereNull('maisons.delete_at')
                               ->whereNull('chambres.delete_at')
                               ->whereNull('factures.delete_at')
                               ->select($champs)
                               ->get()
                              ->pluck($champs)[0];
    }


    public function factureParMois($id)
    {
        $facture_id = $id;

        $fpdf = new FPDF('P','mm',array(120,120));
        $fpdf->SetFont("Arial", "B",15);
        $fpdf->AddPage();
         $fpdf->Ln(-10);
        //$fpdf->Image("assets/img/news-1.jpg",5,280,200,0);
        $fpdf->Image('PAYER.jpg',70,90,50,30);

        $numero = 'FACTURE: ';

        //GENERER DE NUMERO SUR PLUSIEURS POSITIONS
        //str_pad("0",5,STR_PAD_LEFT)

        $fpdf->SetFont("Courier","B",16);
        //$fpdf->MultiCell(50,25, utf8_decode('QUITTANCE DE LOYER'),'C');
        //$fpdf->SetTextColor(255,255,255);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(50, 25, 'QUITTANCE DE LOYER');



        $maison = FactureController::requeteDataFactureMois($facture_id,'nom_maison');


        $fpdf->Ln(10);
        $fpdf->SetFont("Courier","B",15);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(25,27,'Maison:',0,0);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->Cell(37,27,utf8_decode(ucfirst($maison)),0,0);

         $fpdf->Image('PAYER.jpg',70,90,50,30);



        // = FactureController::requeteDataFactureMois($facture_id,'numero_chambre');

        $numero_chambre =   Facture::where('factures.id',$facture_id)
                               ->join('maisons','factures.maison_id','=','maisons.id')
                               ->join('chambres','factures.chambre_id','=','chambres.id')
                               ->join('locataires','factures.locataire_id','=','locataires.id')
                               ->whereNull('maisons.delete_at')
                               ->whereNull('chambres.delete_at')
                               ->whereNull('factures.delete_at')
                               ->select('factures.numero_chambre')
                               ->get()
                              ->pluck('numero_chambre')[0];

        
        $nom_locataire = FactureController::requeteDataFactureMois($facture_id,'nom');
        
        $prenom_locataire = FactureController::requeteDataFactureMois($facture_id,'prenom');
       
        $profession = FactureController::requeteDataFactureMois($facture_id,'profession');
       

        $fpdf->Ln(18);
        $fpdf->SetFont("Courier","B",12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(30,3,utf8_decode('Chambre N°:  '),0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(38,3,ucfirst($numero_chambre),0,0);

        
        $fpdf->Ln(10);
        $fpdf->SetFont("Courier","B",12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(27,3,'Locataire:  ',0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(37,3,utf8_decode(ucfirst($nom_locataire.' '.$prenom_locataire)),0,0);


        $fpdf->Ln(5);
        $fpdf->SetFont("Courier","B",12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(30,3,'Profession:  ',0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(37,3,utf8_decode(ucfirst($profession)),0,0);


        //$type_chambre = FactureController::requeteDataFactureMois($facture_id,'type_chambre');

        $type_chambre =   Facture::where('factures.id',$facture_id)
                               ->join('maisons','factures.maison_id','=','maisons.id')
                               ->join('chambres','factures.chambre_id','=','chambres.id')
                               ->join('locataires','factures.locataire_id','=','locataires.id')
                               ->whereNull('maisons.delete_at')
                               ->whereNull('chambres.delete_at')
                               ->whereNull('factures.delete_at')
                               ->select('factures.type_chambre')
                               ->get()
                              ->pluck('type_chambre')[0];

        $fpdf->Ln(5);
        $fpdf->SetFont("Courier","B",12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(35,3,'Type chambre:  ',0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(37,3,utf8_decode(ucfirst($type_chambre)),0,0);



        $prix_mois = FactureController::requeteDataFactureMois($facture_id,'montant');
        

        $fpdf->Ln(5);
        $fpdf->SetFont("Courier","B",12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(45,3,utf8_decode('Reçu la somme de: '),0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(37,3,number_format($prix_mois,"0",",",".").' XOF',0,0);
        
        // $fpdf->Cell(38,3,),'LR',0,'R');
        
         
        //$fpdf->Cell(28,3,'Avance pour : ',0,0);
        // $fpdf->SetFont('Arial','B',10);
        //$fpdf->Cell(41,3,$nombre_avance.' Mois','R',0,'R');

        $mois = FactureController::requeteDataFactureMois($facture_id,'mois');


        $fpdf->Ln(5);
        $fpdf->SetFont("Courier","B",12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(45,3,'Loyer de: ',0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(37,3,utf8_decode($mois),0,0);

        $type_paiement = FactureController::requeteDataFactureMois($facture_id,'type_paiement');
        
        if ($type_paiement == 'direct') {
          $valeur = 'Direct';
        }
        else{
          $valeur = 'Dans son avance';
        }

        $fpdf->Ln(5);
        $fpdf->SetFont("Courier","B",12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(50,3,'Type de paiement: ',0,0);
        $fpdf->SetFont("Courier","B",11);
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(37,3,utf8_decode($valeur),0,0);
        
        $date_paiement = Facture::where('factures.id',$facture_id)
                               ->join('maisons','factures.maison_id','=','maisons.id')
                               ->join('chambres','factures.chambre_id','=','chambres.id')
                               ->whereNull('maisons.delete_at')
                               ->whereNull('chambres.delete_at')
                               ->select('factures.date_paiement')
                               ->get()
                              ->pluck('date_paiement')[0];


        $fpdf->Ln(15);
        $fpdf->SetFont('Times');
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(35,3,utf8_decode('Date de paiement: '),0,0);
         setlocale(LC_TIME, 'French');
        $fpdf->SetTextColor(0,0,0);
        $fpdf->Cell(60,3,strftime("%d %B %Y, %H h:%M min", strtotime($date_paiement)),0,0, 'L');
         
       

       // $fpdf->Cell(41,3,'Zone Texte 1',1,5,'R');
         $fpdf->Ln(15);
         $fpdf->SetFont('Times');
         $fpdf->Cell(28,3,'Merci pour votre collaboration',0,0,'LR');

        //$fpdf->Output();
        //exit;

         activity()->performedOn(new Facture())
                           ->causedBy(Auth::user()->id)
                           ->log('Téléchargement du réçu du locataire '.$nom_locataire.' '.$prenom_locataire.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


        $fpdf->Output('D','Recu_'.$nom_locataire.'_'.$prenom_locataire.'.pdf');
        return;

    }


    #FACTURE POUR PAIEMENT DES AVANCES DE LOCATION

    public function requeteData($id,$champs)
    {
       return      Locataire::where('locataires.id',$id)
                               ->join('maisons','locataires.maison_id','=','maisons.id')
                               ->join('chambres','locataires.chambre_id','=','chambres.id')
                               ->whereNull('maisons.delete_at')
                               ->whereNull('chambres.delete_at')
                               ->select($champs)
                               ->get()
                              ->pluck($champs)[0];
    }

    public function factureAvance($id)
    {

        $fpdf = new FPDF('P','mm',array(120,200));
        $fpdf->SetFont("Arial", "B",15);
        $fpdf->AddPage();
        $fpdf->Ln(-10);

        $fpdf->Image('PAYER.jpg',70,170,50,30);

        // Add company logo
        //$fpdf->Image("http://127.0.0.1/ImmobilierApk/storage/app/public/".get_logo(Auth::user()->iddirection_ref)->logo_url),5,30,30);

        $fpdf->Image("assets2/img/portfolio/portfolio-6.jpg",5,9,15); 

        $data = get_entreprise_details_invoice(Auth::user()->iddirection_ref);

        $fpdf->SetFont("Courier","B",16);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(0, 10, 'QUITTANCE DE LOYER POUR AVANCE', 0, 1, 'C');
        $fpdf->Ln(5);

        if($data){
          $fpdf->SetFont("Arial", "", 12);
          $fpdf->SetTextColor(0,0,0);
          $fpdf->Cell(0, 6, utf8_decode($data[0]['designation']), 0, 1, 'C');
          $fpdf->Cell(0, 6, utf8_decode('Tél: '.$data[0]['telephone']), 0, 1, 'C');
          $fpdf->Cell(0, 6, utf8_decode('Email: '.$data[0]['email']), 0, 1, 'C');
          $fpdf->Cell(0, 6, utf8_decode($data[0]['siege_social']), 0, 1, 'C');
          $fpdf->Ln(10); 
        }



        $maison = FactureController::requeteData($id,'nom_maison');

        $fpdf->SetFont("Courier", "B", 12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(30, 8, 'Maison:', 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(50, 8, utf8_decode(ucfirst($maison)), 0, 1);
        

        $nom_locataire = FactureController::requeteData($id,'nom');
            
        $prenom_locataire = FactureController::requeteData($id,'prenom');
      
        $numero_chambre = FactureController::requeteData($id,'numero_chambre');
        
        $profession = FactureController::requeteData($id,'profession');


        $fpdf->SetFont("Courier", "B", 12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(30, 8, 'Locataire:', 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(50, 8, utf8_decode(ucfirst($nom_locataire.' '.$prenom_locataire)), 0, 1);
        

        $fpdf->SetFont("Courier", "B", 12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(30, 8,utf8_decode('Chambre N°: ') , 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(50, 8, ucfirst($numero_chambre), 0, 1);


        $fpdf->SetFont("Courier", "B", 12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(30, 8, 'Profession:', 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(50, 8, utf8_decode(ucfirst($profession)), 0, 1);

        $type_chambre = FactureController::requeteData($id,'type_chambre');


        $fpdf->SetFont("Courier", "B", 12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(30, 8, 'Type chambre:', 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(50, 8, utf8_decode(ucfirst($type_chambre)), 0, 1);


        
        $prix_mois = FactureController::requeteData($id,'prix_mois');
            
        $nombre_avance = FactureController::requeteData($id,'nombre_avance');


        $fpdf->Ln(5);
        $fpdf->SetFont("Courier", "B", 12);
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(60, 8, utf8_decode('Caution sur avance: '), 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(40, 8, number_format($prix_mois * $nombre_avance, 0, ",", ".") . ' XOF', 0, 1);


        $caution_courant = FactureController::requeteData($id,'caution_courant');

        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(60, 8, utf8_decode('Caution électricité: '), 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(40, 8, number_format($caution_courant, 0, ",", ".") . ' XOF', 0, 1);

        $caution_eau = FactureController::requeteData($id,'caution_eau');

        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(60, 8, utf8_decode('Caution eau: '), 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(40, 8, number_format($caution_eau, 0, ",", ".") . ' XOF', 0, 1);

        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(60, 8, utf8_decode('Avance pour: '), 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(40, 8, $nombre_avance . ' Mois', 0, 1);


        $date_paiement = Locataire::where('locataires.id',$id)
                                  ->join('maisons','locataires.maison_id','=','maisons.id')
                                  ->join('chambres','locataires.chambre_id','=','chambres.id')
                                  ->whereNull('maisons.delete_at')
                                  ->whereNull('chambres.delete_at')
                                  ->select('locataires.created_at')
                                  ->get()
                                  ->pluck('created_at')[0];

      
        $fpdf->Ln(10);
        $fpdf->SetFont('Times');
        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(40, 8, utf8_decode('Date de paiement: '), 0, 0);
        setlocale(LC_TIME, 'French');
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(40, 8, strftime("%d %B %Y, %H h:%M min", strtotime($date_paiement)), 0, 1,'L');

        $date_entre = FactureController::requeteData($id,'date_entree');


        $fpdf->SetTextColor(10,5,200);
        $fpdf->Cell(40, 8, utf8_decode('Date d\'entrée: '), 0, 0);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->Cell(40, 8, strftime("%d %B %Y", strtotime($date_entre)), 0, 1);



        $fpdf->Ln(15);
        $fpdf->SetFont('Arial', 'I', 10);
        $fpdf->Cell(0, 8, utf8_decode('Merci pour votre collaboration'), 0, 1, 'C');

        // $fpdf->Output();
        // exit;

        
        activity()->performedOn(new Facture())
                 ->causedBy(Auth::user()->id)
                ->log('Téléchargement du réçu du locataire '.$nom_locataire.' '.$prenom_locataire.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        $fpdf->Output('D','Recu_'.$nom_locataire.'_'.$prenom_locataire.'.pdf');

        return;

    }


   




}
