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
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class FactureController extends Controller
{

  public function index()
  {
    try {
      // Utiliser l'agence active centralisee
      $idannexe_ref = get_active_annexe_id();

      $allMaison = Maison::whereNull('delete_at')
                          ->where('iddirection_ref', Auth::user()->iddirection_ref)
                          ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                            $query->where('idannexe_ref', $idannexe_ref);
                          })
                          ->select('id', 'nom_maison')
                          ->get();

      $allChambres = Chambre::join('maisons', 'chambres.maison_id', '=', 'maisons.id')
                            ->where('chambres.iddirection_ref', Auth::user()->iddirection_ref)
                            ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                              $query->where('chambres.idannexe_ref', $idannexe_ref);
                            })
                            ->whereNull('chambres.delete_at')
                            ->whereNull('maisons.delete_at')
                            ->select('chambres.maison_id', 'chambres.id', 'maisons.nom_maison', 'chambres.numero_chambre', 'chambres.type_chambre', 'chambres.etat')
                            ->get();


      $allFacture = Facture::whereNull('factures.delete_at')
                            ->where('factures.iddirection_ref', Auth::user()->iddirection_ref)
                            ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                              $query->where('factures.idannexe_ref', $idannexe_ref);
                            })
                            ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                            ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                            ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                            ->whereNull('maisons.delete_at')
                            ->whereNull('chambres.delete_at')
                            ->whereNull('locataires.delete_at')
                            ->where('locataires.status', true)
                            ->whereMonth('factures.created_at', Carbon::now()->format('m'))
                            ->select('factures.iddirection_ref', 'factures.idannexe_ref', 'factures.id', 'maisons.nom_maison', 'chambres.numero_chambre', 'locataires.nom', 'locataires.prenom', 'factures.montant', 'factures.mois', 'factures.type_paiement', 'factures.date_paiement', 'factures.mode_paiement','factures.maison_id', 'factures.chambre_id', 'factures.locataire_id', 'chambres.type_chambre')
                            ->get();

      return view('facture.facture', compact(['allFacture', 'allMaison']));
    } catch (QueryException $e) {

      return back()->with('error', 'Echec, veuillez verifier les donnees');
    }
  }


  public function getNumeroChambre(Request $request)
  {
    $vide = '';

    $vide .= "<option disabled selected>Choisir une chambre</option>";

    $val = Chambre::where('maison_id', $request->idMaison)
      ->where('iddirection_ref', Auth::user()->iddirection_ref)
      ->where(function ($querry) {
        if (Gate::none(['Is_admin'])) {
          $querry->where('idannexe_ref', Auth::user()->idannexe_ref);
        }
      })
      ->whereNull('delete_at')
      ->select('id', 'numero_chambre')
      ->get();

    foreach ($val as  $cont) {
      $vide .= "<option value=" . $cont->id . ">" . $cont->numero_chambre . "</option>";
    }

    return response()->json([
      'list_chambre' => $vide,
    ]);
  }

  public function getTypeChambre(Request $request)
  {

    $vide = Chambre::where('id', $request->numero_chambre_got)
      ->value('type_chambre');

    $sonPrix = Prix::where('chambre_id', $request->numero_chambre_got)
      ->whereNull('delete_at')
      ->where('status', true)
      ->value('prix');

    $locataire = Locataire::where('chambre_id', $request->numero_chambre_got)
      ->whereNull('delete_at')
      ->where('status', true)
      ->select('nom', 'prenom')
      ->first();

    $nom    = $locataire ? $locataire->nom    : '';
    $prenom = $locataire ? $locataire->prenom : '';



    return response()->json([
      'type_chambres_get' => $vide,
      'sonPrix' => $sonPrix,
      'sonLocataire' => $nom . ' ' . $prenom
    ]);
  }


  public function connaitreLocataire($id)
  {
    $obj = Locataire::where('id', $id)->whereNull('delete_at')->first();

    return $obj->nom . ' ' . $obj->prenom;
  }

  public function numeChambre($id)
  {
    $num = Chambre::where('id', $id)->whereNull('delete_at')->first();

    return $num->numero_chambre;
  }

  public function store(Request $request)
  {
    try {

          $validator = Validator::make(
            $request->all(),
            [
              'nom_maison' => ['bail', 'required'],
              'numero_chambre' => ['bail', 'required'],
              'montant' => ['bail', 'required'],
              'mois' => ['bail', 'required'],
              'date_paiement' => ['bail', 'required'],
              'mode_paiement' => ['bail', 'required'],
              'type_paiement' => ['bail', 'required'],
            ],
          );



          if ($validator->fails()) {
            return response()->json([
              'error' => $validator->errors()
            ]);
          }

          // Utiliser l'annexe active centralisée
          $idannexe_ref = get_active_annexe_id();
          if (!$idannexe_ref) {
              return response()->json([
                  'status' => false,
                  'message' => "Veuillez sélectionner une agence dans le header"
              ]);
          }

          $locataire_id = Locataire::where('chambre_id', $request->numero_chambre)
                                  ->whereNull('delete_at')
                                  ->where('status', true)
                                  ->get()
                                  ->pluck('id')[0];

          #VIRIFIER SI LE MONTANT CORRESPOND
          if (str_replace(" ", "", $request->montant) == $request->sonPrix) {
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
                'mode_paiement' => $request->mode_paiement,
                'type_paiement' => $request->type_paiement,
                'iddirection_ref' => Auth::user()->iddirection_ref,
                'idannexe_ref' => $idannexe_ref,
              ]);

              if ($facture) {

                activity()->performedOn(new Facture())
                  ->causedBy(Auth::user()->id)
                  ->log('Paiement effectué pour le locataire ' . FactureController::connaitreLocataire($locataire_id) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);


                return response()->json([
                  'status' => true,
                  'message' => "Paiement effectué avec succès, aller télécharger le réçu",
                ]);
              }
            } else {
              //RECUPERATION DU NOMBRE D'AVANCE QUI RESTE
              $nombre_avance_restant = Locataire::where('id', $locataire_id)
                                                ->whereNull('delete_at')
                                                ->where('status', true)
                                                ->get()
                                                ->pluck('nombre_avance')[0];

              $nombre_avance_consomme = Locataire::where('id', $locataire_id)
                                                ->whereNull('delete_at')
                                                ->where('status', true)
                                                ->get()
                                                ->pluck('nombre_avance_consomme')[0];

              if ($nombre_avance_restant == $nombre_avance_consomme) {

                return response()->json([
                  'status' => false,
                  'message' => "Paiement impossible, nombre d'avance épuisé pour ce locataire",
                ]);
              } else {
                // Mettre à jour le nombre d'avance consommé

                $diminue_nombre_avance = Locataire::where('id', $locataire_id)
                                                  ->whereNull('delete_at')
                                                  ->where('status', true)
                                                  ->update([
                                                    'nombre_avance_consomme' => $nombre_avance_consomme + 1,
                                                  ]);
                //Je crée le paiement
                if ($diminue_nombre_avance) {

                  $facture = Facture::create([
                    'maison_id' => $request->nom_maison,
                    'chambre_id' => $request->numero_chambre,
                    'locataire_id' => $locataire_id,
                    'date_paiement' => $request->date_paiement,
                    'mode_paiement' => $request->mode_paiement,
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
                      ->log('Paiement effectué pour le locataire ' . FactureController::connaitreLocataire($locataire_id) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

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

      // Utiliser l'annexe active centralisée
      $idannexe_ref = get_active_annexe_id();
      if (!$idannexe_ref) {
          return back()->with('error', "Veuillez sélectionner une agence dans le header");
      }

      if ($request->type_paiement == 'direct') {

        $exist  = Facture::where('id', $request->facture_id2)
          ->where('type_paiement', "avance")
          ->first();

        if ($exist) {

          $nombre_avance = FactureController::requeteData($request->locataire_id2, 'nombre_avance_consomme');

          $facture = Locataire::where('id', $request->locataire_id2)
            ->update([
              'nombre_avance_consomme' => $nombre_avance - 1,
            ]);
        }

        $facture = Facture::where('id', $request->facture_id2)
                          ->update([
                            'maison_id' => $request->maison_id2,
                            'chambre_id' => $request->chambre_id2,
                            'locataire_id' => $request->locataire_id2,
                            'date_paiement' => $request->date_paiement,
                            'mode_paiement' => $request->mode_paiement,
                            'montant' => $request->montant,
                            'mois' => $request->mois,
                            'type_paiement' => $request->type_paiement,
                            'idannexe_ref' => $idannexe_ref,
                          ]);

        if ($facture) {

          activity()->performedOn(new Facture())
            ->causedBy(Auth::user()->id)
            ->log('Modification du paiement pour le locataire ' . FactureController::connaitreLocataire($request->locataire_id2) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);


          return back()->with('success', 'Paiement modifié avec succès');
        }
      } else {

        //RECUPERATION DU NOMBRE D'AVANCE QUI RESTE
        $nombre_avance_restant = Locataire::where('id', $request->locataire_id2)
                                          ->whereNull('delete_at')
                                          ->where('status', true)
                                          ->get()
                                          ->pluck('nombre_avance')[0];

        $nombre_avance_consomme = Locataire::where('id', $request->locataire_id2)
                                            ->whereNull('delete_at')
                                            ->where('status', true)
                                            ->get()
                                            ->pluck('nombre_avance_consomme')[0];

        if ($nombre_avance_restant == $nombre_avance_consomme) {
          return back()->with('error', "Modification de paiement impossible, nombre d'avance épuisé pour ce locataire");
        } else {
          // Mettre à jour le nombre d'avance consommé

          $diminue_nombre_avance = Locataire::where('id', $request->locataire_id2)
            ->whereNull('delete_at')
            ->where('status', true)
            ->update([
              'nombre_avance_consomme' => $nombre_avance_consomme + 1,
            ]);
          //Je crée le paiement
          if ($diminue_nombre_avance) {

            $facture = Facture::where('id', $request->facture_id2)
              ->update([
                'maison_id' => $request->maison_id2,
                'chambre_id' => $request->chambre_id2,
                'locataire_id' => $request->locataire_id2,
                'date_paiement' => $request->date_paiement,
                'mode_paiement' => $request->mode_paiement,
                'montant' => $request->montant,
                'mois' => $request->mois,
                'type_paiement' => $request->type_paiement,
                'idannexe_ref' => $idannexe_ref,
              ]);

            if ($facture) {

              activity()->performedOn(new Facture())
                ->causedBy(Auth::user()->id)
                ->log('Modification de paiement effectué pour le locataire ' . FactureController::connaitreLocataire($request->locataire_id2) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

              return back()->with('success', "Modification de paiement effectué avec succès, aller télécharger le réçu");
            }
          } else {
            return back()->with('error', "Il y a un soucis");
          }
        }
      }
    } catch (QueryException $e) {
      return back()->with('error', "Echéc, veuillez verifier les données");
    }
  }

  public function destroy(Request $request)
  {
    try {
      $deletedValue = Locataire::where('id', $request->facture_id_destroy)->first();

      $deleted = Facture::where('id', $request->facture_id_destroy)->update(['delete_at' => Carbon::now()]);

      if ($deleted) {

        activity()->performedOn(new Facture())
          ->causedBy(Auth::user()->id)
          ->log('Suppression du paiement pour le locataire ' . $deletedValue->nom . ' ' . $deletedValue->prenom . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

        return back()->with('success', 'Suppression effectuée avec succès');
      }
    } catch (QueryException $e) {
      return back()->with('error', 'Echéc, veuillez verifier les données');
    }
  }

  #FACTURE DE CHAQUE MOIS

  public function requeteDataFactureMois($id, $champs)
  {
    return Facture::where('factures.id', $id)
      ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
      ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
      ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
      ->whereNull('maisons.delete_at')
      ->whereNull('chambres.delete_at')
      ->whereNull('factures.delete_at')
      ->select($champs)
      ->get()
      ->pluck($champs)[0];
  }



  public function factureParMois($id)
  {
      try {
           // Version professionnelle sur une page A5
            $fpdf = new FPDF('P', 'mm', 'A5');
            $fpdf->SetAutoPageBreak(false);
            $fpdf->AddPage();

            // Dimensions
            $pageWidth = 148;
            $pageHeight = 210;

            // Couleurs
            $color_header = array(52, 152, 219);
            $color_border = array(200, 200, 200);
            $color_success = array(39, 174, 96);

            // Récupérer l'idannexe_ref de la facture
            $facture = Facture::find($id);
            $idannexe_ref = $facture ? $facture->idannexe_ref : get_active_annexe_id();

            // Récupérer les informations de l'agence
            $annexeData = get_annexe_details_for_invoice($idannexe_ref);

            // Position Y initiale
            $y = 10;

            // Logo de l'agence
            $logoPath = null;
            $logoSize = 15;
            if ($annexeData && $annexeData['logo_path']) {
              $logoPath = $annexeData['logo_path'];
            }

            if ($logoPath && file_exists($logoPath)) {
              $fpdf->Image($logoPath, 10, $y, $logoSize);
            } else {
              $fpdf->SetFillColor(240, 240, 240);
              $fpdf->Rect(10, $y, $logoSize, $logoSize, 'F');
            }

            // Titre principal
            $fpdf->SetFont('Arial', 'B', 14);
            $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetXY(30, $y);
            $fpdf->Cell($pageWidth - 40, 8, 'QUITTANCE DE LOYER MENSUEL', 0, 1, 'C');

            // Informations agence
            if ($annexeData) {
              $y += 8;
              $fpdf->SetFont('Arial', 'B', 9);
              $fpdf->SetTextColor(50, 50, 50);
              $fpdf->SetX(10);
              $fpdf->Cell($pageWidth - 20, 4, utf8_decode(strtoupper($annexeData['designation'])), 0, 1, 'C');

              $fpdf->SetFont('Arial', '', 7);
              $fpdf->SetTextColor(100, 100, 100);
              $fpdf->SetX(10);
              $fpdf->Cell($pageWidth - 20, 3, utf8_decode($annexeData['siege_social']), 0, 1, 'C');
              $fpdf->SetX(10);
              $fpdf->Cell($pageWidth - 20, 3, 'Tel: ' . $annexeData['telephone'] . ' - Email: ' . $annexeData['email'], 0, 1, 'C');
            }

            $y += 10;

            // Ligne décorative
            $fpdf->SetDrawColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetLineWidth(0.3);
            $fpdf->Line(10, $y, $pageWidth - 10, $y);
            $y += 6;

            // Récupération des données
            $maison = $this->requeteDataFactureMois($id, 'nom_maison');
            $numero_chambre = Facture::where('factures.id', $id)
                                    ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                                    ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                                    ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                                    ->whereNull('maisons.delete_at')
                                    ->whereNull('chambres.delete_at')
                                    ->whereNull('factures.delete_at')
                                    ->select('factures.numero_chambre')
                                    ->get()
                                    ->pluck('numero_chambre')[0];

              $mode_paiement = Facture::where('factures.id', $id)
                                      ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                                      ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                                      ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                                      ->whereNull('maisons.delete_at')
                                      ->whereNull('chambres.delete_at')
                                      ->whereNull('factures.delete_at')
                                      ->select('factures.mode_paiement')
                                      ->get()
                                      ->pluck('mode_paiement')[0];

            $nom_locataire = $this->requeteDataFactureMois($id, 'nom');
            $prenom_locataire = $this->requeteDataFactureMois($id, 'prenom');
            $profession = $this->requeteDataFactureMois($id, 'profession');

            $type_chambre = Facture::where('factures.id', $id)
                                    ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                                    ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                                    ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                                    ->whereNull('maisons.delete_at')
                                    ->whereNull('chambres.delete_at')
                                    ->whereNull('factures.delete_at')
                                    ->select('factures.type_chambre')
                                    ->get()
                                    ->pluck('type_chambre')[0];

            $prix_mois = $this->requeteDataFactureMois($id, 'montant');
            $mois = $this->requeteDataFactureMois($id, 'mois');
            //$mode_paiement = $this->requeteDataFactureMois($id, 'mode_paiement');
            $type_paiement = $this->requeteDataFactureMois($id, 'type_paiement');
            $valeur = $type_paiement == 'direct' ? 'Direct' : 'Dans son avance';

            $date_paiement = Facture::where('factures.id', $id)
                                    ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                                    ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                                    ->whereNull('maisons.delete_at')
                                    ->whereNull('chambres.delete_at')
                                    ->select('factures.date_paiement')
                                    ->get()
                                    ->pluck('date_paiement')[0];

            // Section informations
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell(0, 6, 'INFORMATIONS', 0, 1);
            $y += 8;

            // Grille d'informations compacte
            $labelWidth = 45;
            $lineHeight = 5;

            // Ligne 1 - Locataire
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($labelWidth, $lineHeight, 'Locataire:', 0, 0);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->Cell(0, $lineHeight, utf8_decode($nom_locataire . ' ' . $prenom_locataire), 0, 1);
            $y += 5;

            // Ligne 2 - Maison
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($labelWidth, $lineHeight, 'Maison:', 0, 0);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->Cell(0, $lineHeight, utf8_decode($maison), 0, 1);
            $y += 5;

            // Ligne 3 - Chambre
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($labelWidth, $lineHeight, 'Chambre:', 0, 0);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->Cell(0, $lineHeight, utf8_decode('N° ' . $numero_chambre . ' (' . $type_chambre . ')'), 0, 1);
            $y += 5;

            // Ligne 4 - Profession
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($labelWidth, $lineHeight, 'Profession:', 0, 0);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->Cell(0, $lineHeight, utf8_decode($profession), 0, 1);
            $y += 10;

            // Section détails du paiement
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell(0, 6, 'DETAILS DU PAIEMENT', 0, 1);
            $y += 5;

            $fpdf->SetFont('Arial', 'B', 7);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell(0, 6, 'Mode paiement :'.utf8_decode($mode_paiement), 0, 1);
            $y += 8;
            // Tableau des détails
            $colDesc = 70;
            $colValue = 60;

            // En-tête tableau
            $fpdf->SetFillColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetTextColor(255, 255, 255);
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($colDesc, 6, 'DESCRIPTION', 1, 0, 'L', true);
            $fpdf->Cell($colValue, 6, 'VALEUR', 1, 1, 'C', true);
            $y += 6;

            // Ligne 1 - Période
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($colDesc, 6, utf8_decode('Période de loyer'), 1, 0, 'L');
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->Cell($colValue, 6, utf8_decode($mois), 1, 1, 'C');
            $y += 6;

            // Ligne 2 - Type de paiement
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($colDesc, 6, 'Type de paiement', 1, 0, 'L');
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->Cell($colValue, 6, utf8_decode($valeur), 1, 1, 'C');
            $y += 6;

            // Ligne 3 - Date de paiement
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($colDesc, 6, 'Date de paiement', 1, 0, 'L');
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->Cell($colValue, 6, date('d/m/Y H:i', strtotime($date_paiement)), 1, 1, 'C');
            $y += 8;

            // Ligne de séparation
            $fpdf->SetDrawColor(200, 200, 200);
            $fpdf->SetLineWidth(0.2);
            $fpdf->Line(10, $y, $pageWidth - 10, $y);
            $y += 4;

            // Montant total
            $fpdf->SetFont('Arial', 'B', 9);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($colDesc, 6,utf8_decode('MONTANT PERÇU:') , 0, 0, 'L');

            $fpdf->SetFont('Arial', 'B', 11);
            $fpdf->SetTextColor($color_success[0], $color_success[1], $color_success[2]);
            $fpdf->SetX(10 + $colDesc);
            $fpdf->Cell($colValue, 6, number_format($prix_mois, 0, ",", ".") . ' XOF', 0, 1, 'C');
            $y += 10;

            if ($y < 140) {

              $fpdf->SetX(10);
          
          
              // VALEUR
              $fpdf->SetFont('Arial', 'B', 10);
              $fpdf->SetTextColor(0, 0, 0);
              $fpdf->SetX(10);
          
              $montantLettres = ucfirst(nombreEnLettres($prix_mois)) . ' francs CFA';
              $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
              $fpdf->MultiCell(190, 6, utf8_decode('Quittance arrêtée à :'.$montantLettres), 0, 'L');
          
              $y = $fpdf->GetY() + 8;
          }
        
        

          // Informations de paiement mobile (Cash électronique)
          if ($annexeData && !empty($annexeData['cash_electronique']) && $y < 155) {
            $fpdf->SetFont('Arial', 'B', 7);
            $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell(0, 4, 'MODES DE PAIEMENT MOBILE:', 0, 1, 'L');
            $y += 4;

            $fpdf->SetFont('Arial', '', 7);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(10, $y);
            $fpdf->MultiCell($pageWidth - 20, 3, utf8_decode($annexeData['cash_electronique']), 0, 'L');
            $y = $fpdf->GetY() + 5;
          }

          // Mentions légales
          if ($y < 160) {
            $fpdf->SetFont('Arial', 'I', 7);
            $fpdf->SetTextColor(100, 100, 100);
            $fpdf->SetXY(10, $y);
            $fpdf->MultiCell(
              $pageWidth - 20,
              3,
              utf8_decode('Cette quittance fait foi de paiement du loyer pour la période indiquée.'),
              0,
              'C'
            );
            $y += 10;
          }

          $date_paiement = new DateTime($date_paiement);

          // Code de référence
          $fpdf->SetFont('Courier', 'B', 8);
          $fpdf->SetTextColor(50, 50, 50);
          $ref = 'FACT-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '-' . $date_paiement->format('dmY');
          $fpdf->SetXY(10, $y);
          $fpdf->Cell(0, 4,utf8_decode('Référence: ')  . $ref, 0, 1, 'C');
          $y += 8;

          // Pied de page avec signatures
          $signatureY = $pageHeight - 50;

          // Lignes de signature
          $fpdf->SetDrawColor(150, 150, 150);
          $fpdf->SetLineWidth(0.2);

          // Signature locataire
          $fpdf->SetFont('Arial', 'I', 7);
          $fpdf->SetTextColor(100, 100, 100);
          $fpdf->SetXY(10, $signatureY);
          $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du locataire'), 0, 0, 'C');

          // Signature responsable
          $fpdf->SetX(($pageWidth - 30) / 2 + 20);
          $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du responsable'), 0, 1, 'C');

          // Image de signature du responsable (si disponible)
          if ($annexeData && !empty($annexeData['signature_path']) && file_exists($annexeData['signature_path'])) {
              $sigImgX = ($pageWidth - 30) / 4 * 3 - 12;
              $fpdf->Image($annexeData['signature_path'], $sigImgX, $signatureY + 2, 25, 12);
          }

          $signatureY += 15;

          // Lignes de signature
          $lineLength = 40;
          $fpdf->Line(($pageWidth - 30) / 4, $signatureY, ($pageWidth - 30) / 4 + $lineLength, $signatureY);
          $fpdf->Line(($pageWidth - 30) / 4 * 3 - $lineLength / 2, $signatureY, ($pageWidth - 30) / 4 * 3 + $lineLength / 2, $signatureY);

          $signatureY += 3;

          // Noms sous les signatures
          $fpdf->SetFont('Arial', 'I', 6);
          $fpdf->SetXY(10, $signatureY);
          $fpdf->Cell(($pageWidth - 30) / 2, 3, utf8_decode($nom_locataire . ' ' . $prenom_locataire), 0, 0, 'C');
          $fpdf->SetX(($pageWidth - 30) / 2 + 20);
          $fpdf->Cell(($pageWidth - 30) / 2, 3, Auth::user()->nom . ' ' . Auth::user()->prenom, 0, 1, 'C');

          // Message de remerciement
          $fpdf->SetY($pageHeight - 12);
          $fpdf->SetFont('Arial', 'I', 7);
          $fpdf->SetTextColor($color_success[0], $color_success[1], $color_success[2]);
          $fpdf->Cell(0, 3, 'Merci pour votre confiance !', 0, 0, 'C');

          // Date de génération
          $fpdf->SetY($pageHeight - 8);
          $fpdf->SetFont('Arial', 'I', 6);
          $fpdf->SetTextColor(120, 120, 120);
          $fpdf->Cell(0, 3, utf8_decode('Facture générée le ')  .utf8_decode(date('d/m/Y à H:i')) , 0, 0, 'C');

          // Journalisation
          activity()->performedOn(new Facture())
            ->causedBy(Auth::user()->id)
            ->log('Téléchargement de la quittance mensuelle de ' . $nom_locataire . ' ' . $prenom_locataire . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

          // Output
          $fileName = 'Quittance_Mensuelle_' . str_replace(' ', '_', $nom_locataire) . '_' . date('Ymd') . '.pdf';
          $fpdf->Output('D', $fileName);

          return;
      } catch (\Throwable $th) {
        return back()->with('error', "Une erreur est survenue lors de la génération de la quittance.");
      }
  }


  #FACTURE POUR PAIEMENT DES AVANCES DE LOCATION

  public function requeteData($id, $champs)
  {
    return Locataire::where('locataires.id', $id)
      ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
      ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
      ->whereNull('maisons.delete_at')
      ->whereNull('chambres.delete_at')
      ->select($champs)
      ->get()
      ->pluck($champs)[0];
  }



  public function factureAvance($id)
  {
      // Version professionnelle sur une page A5
      $fpdf = new FPDF('P', 'mm', 'A5');
      $fpdf->SetAutoPageBreak(false);
      $fpdf->AddPage();

      // Dimensions
      $pageWidth = 148;
      $pageHeight = 210;

      // Couleurs
      $color_header = array(52, 152, 219);
      $color_border = array(200, 200, 200);
      $color_success = array(39, 174, 96);

      // Récupérer l'idannexe_ref du locataire
      $locataire = Locataire::find($id);
      $idannexe_ref = $locataire ? $locataire->idannexe_ref : get_active_annexe_id();

      // Récupérer les informations de l'agence
      $annexeData = get_annexe_details_for_invoice($idannexe_ref);

      // Position Y initiale
      $y = 10;

      // Logo de l'agence
      $logoPath = null;
      $logoSize = 15;
      if ($annexeData && $annexeData['logo_path']) {
          $logoPath = $annexeData['logo_path'];
      }

      if ($logoPath && file_exists($logoPath)) {
          $fpdf->Image($logoPath, 10, $y, $logoSize);
      } else {
          $fpdf->SetFillColor(240, 240, 240);
          $fpdf->Rect(10, $y, $logoSize, $logoSize, 'F');
      }

      // Titre principal
      $fpdf->SetFont('Arial', 'B', 14);
      $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
      $fpdf->SetXY(30, $y);
      $fpdf->Cell($pageWidth - 40, 8, 'QUITTANCE DE CAUTION', 0, 1, 'C');

      // Informations agence
      if ($annexeData) {
          $y += 8;
          $fpdf->SetFont('Arial', 'B', 9);
          $fpdf->SetTextColor(50, 50, 50);
          $fpdf->SetX(10);
          $fpdf->Cell($pageWidth - 20, 4, utf8_decode(strtoupper($annexeData['designation'])), 0, 1, 'C');

          $fpdf->SetFont('Arial', '', 7);
          $fpdf->SetTextColor(100, 100, 100);
          $fpdf->SetX(10);
          $fpdf->Cell($pageWidth - 20, 3, utf8_decode($annexeData['siege_social']), 0, 1, 'C');
          $fpdf->SetX(10);
          $fpdf->Cell($pageWidth - 20, 3, 'Tel: ' . $annexeData['telephone'] . ' - Email: ' . $annexeData['email'], 0, 1, 'C');
      }

      $y += 10;

      // Ligne décorative
      $fpdf->SetDrawColor($color_header[0], $color_header[1], $color_header[2]);
      $fpdf->SetLineWidth(0.3);
      $fpdf->Line(10, $y, $pageWidth - 10, $y);
      $y += 6;

      // Récupération des données
      $maison = $this->requeteData($id, 'nom_maison');
      $nom_locataire = $this->requeteData($id, 'nom');
      $prenom_locataire = $this->requeteData($id, 'prenom');
      $numero_chambre = $this->requeteData($id, 'numero_chambre');
      $profession = $this->requeteData($id, 'profession');
      $type_chambre = $this->requeteData($id, 'type_chambre');
      $prix_mois = $this->requeteData($id, 'prix_mois');
      $nombre_avance = $this->requeteData($id, 'nombre_avance');
      $nombre_caution = $this->requeteData($id, 'nombre_caution');
      $caution_courant = $this->requeteData($id, 'caution_courant');
      $caution_eau = $this->requeteData($id, 'caution_eau');
      $date_entre = $this->requeteData($id, 'date_entree');
      $mode_paiement = $this->requeteData($id, 'mode_paiement');

      // Section informations
      $fpdf->SetFont('Arial', 'B', 10);
      $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell(0, 6, 'INFORMATIONS', 0, 1);
      $y += 8;

      // Grille d'informations compacte
      $labelWidth = 45;
      $lineHeight = 5;

      // Ligne 1 - Locataire
      $fpdf->SetFont('Arial', 'B', 8);
      $fpdf->SetTextColor(80, 80, 80);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell($labelWidth, $lineHeight, 'Locataire:', 0, 0);
      $fpdf->SetFont('Arial', '', 8);
      $fpdf->SetTextColor(0, 0, 0);
      $fpdf->Cell(0, $lineHeight, utf8_decode($nom_locataire . ' ' . $prenom_locataire), 0, 1);
      $y += 5;

      // Ligne 2 - Maison
      $fpdf->SetFont('Arial', 'B', 8);
      $fpdf->SetTextColor(80, 80, 80);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell($labelWidth, $lineHeight, 'Maison:', 0, 0);
      $fpdf->SetFont('Arial', '', 8);
      $fpdf->SetTextColor(0, 0, 0);
      $fpdf->Cell(0, $lineHeight, utf8_decode($maison), 0, 1);
      $y += 5;

      // Ligne 3 - Chambre
      $fpdf->SetFont('Arial', 'B', 8);
      $fpdf->SetTextColor(80, 80, 80);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell($labelWidth, $lineHeight, 'Chambre:', 0, 0);
      $fpdf->SetFont('Arial', '', 8);
      $fpdf->SetTextColor(0, 0, 0);
      $fpdf->Cell(0, $lineHeight, utf8_decode('N° ' . $numero_chambre . ' (' . $type_chambre . ')'), 0, 1);
      $y += 5;

      // Ligne 4 - Profession
      $fpdf->SetFont('Arial', 'B', 8);
      $fpdf->SetTextColor(80, 80, 80);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell($labelWidth, $lineHeight, 'Profession:', 0, 0);
      $fpdf->SetFont('Arial', '', 8);
      $fpdf->SetTextColor(0, 0, 0);
      $fpdf->Cell(0, $lineHeight, utf8_decode($profession), 0, 1);
      $y += 5;

      // Ligne 5 - Date d'entrée
      $fpdf->SetFont('Arial', 'B', 8);
      $fpdf->SetTextColor(80, 80, 80);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell($labelWidth, $lineHeight, utf8_decode('Date entrée:'), 0, 0);
      $fpdf->SetFont('Arial', '', 8);
      $fpdf->SetTextColor(0, 0, 0);
      $fpdf->Cell(0, $lineHeight, date('d/m/Y', strtotime($mode_paiement)), 0, 1);
      $y += 10;

      // Section détails du paiement
      $fpdf->SetFont('Arial', 'B', 10);
      $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell(0, 6, 'DETAILS DES MONTANTS', 0, 1);
      $y += 4;

      $fpdf->SetFont('Arial', 'B', 7);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell(0, 6, 'Mode paiement :'.utf8_decode($mode_paiement), 0, 1);
      $y += 8;


      // Tableau des détails
      $colDesc = 70;
      $colQte = 25;
      $colMontant = 30;

      // En-tête tableau
      $fpdf->SetFillColor($color_header[0], $color_header[1], $color_header[2]);
      $fpdf->SetTextColor(255, 255, 255);
      $fpdf->SetFont('Arial', 'B', 8);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell($colDesc, 6, 'DESCRIPTION', 1, 0, 'L', true);
      $fpdf->Cell($colQte, 6, 'QTE', 1, 0, 'C', true);
      $fpdf->Cell($colMontant, 6, 'MONTANT', 1, 1, 'C', true);
      $y += 6;

      // Calcul des montants
      $montant_avance = $prix_mois * $nombre_avance;
      $montant_caution = $prix_mois * $nombre_caution;
      $total = $montant_avance + $caution_courant + $caution_eau + $montant_caution;

      // Items du tableau
      $items = [
          ['Avance', $nombre_avance . ' mois', $montant_avance],
          ['Caution', $nombre_caution . ' mois', $montant_caution],
          ['Caution électricité', '1', $caution_courant],
          ['Caution eau', '1', $caution_eau]
      ];

      foreach ($items as $item) {
          $fpdf->SetFont('Arial', '', 8);
          $fpdf->SetTextColor(0, 0, 0);
          $fpdf->SetXY(10, $y);
          $fpdf->Cell($colDesc, 6, utf8_decode($item[0]), 1, 0, 'L');
          $fpdf->Cell($colQte, 6, $item[1], 1, 0, 'C');
          $fpdf->SetFont('Arial', 'B', 8);
          $fpdf->Cell($colMontant, 6, number_format($item[2], 0, ",", "."), 1, 1, 'C');
          $y += 6;
      }

      $y += 2;

      // Ligne de séparation
      $fpdf->SetDrawColor(200, 200, 200);
      $fpdf->SetLineWidth(0.2);
      $fpdf->Line(10, $y, $pageWidth - 10, $y);
      $y += 4;

      // Total
      $fpdf->SetFont('Arial', 'B', 9);
      $fpdf->SetTextColor(0, 0, 0);
      $fpdf->SetXY(10, $y);
      $fpdf->Cell($colDesc + $colQte, 6, 'TOTAL A PAYER:', 0, 0, 'L');

      $fpdf->SetFont('Arial', 'B', 11);
      $fpdf->SetTextColor($color_success[0], $color_success[1], $color_success[2]);
      $fpdf->SetX(10 + $colDesc + $colQte);
      $fpdf->Cell($colMontant, 6, number_format($total, 0, ",", ".") . ' XOF', 0, 1, 'C');
      $y += 12;

      // Montant en lettres (si assez d'espace)
      if ($y < 140) {
          $fpdf->SetX(10);
          $fpdf->SetFont('Arial', 'B', 10);
          $fpdf->SetTextColor(0, 0, 0);
          $fpdf->SetX(10);
          
          $montantLettres = ucfirst(nombreEnLettres($total)) . ' XOF';
          $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
          $fpdf->MultiCell(190, 6, utf8_decode('Quittance arrêtée à ' . $montantLettres), 0, 'L');
          
          $y = $fpdf->GetY() + 8;
      }

      // Informations de paiement mobile (Cash électronique)
      if ($annexeData && !empty($annexeData['cash_electronique']) && $y < 155) {
          $fpdf->SetFont('Arial', 'B', 7);
          $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
          $fpdf->SetXY(10, $y);
          $fpdf->Cell(0, 4, 'MODES DE PAIEMENT MOBILE:', 0, 1, 'L');
          $y += 4;

          $fpdf->SetFont('Arial', '', 7);
          $fpdf->SetTextColor(80, 80, 80);
          $fpdf->SetXY(10, $y);
          $fpdf->MultiCell($pageWidth - 20, 3, utf8_decode($annexeData['cash_electronique']), 0, 'L');
          $y = $fpdf->GetY() + 5;
      }

      // Mentions légales
      if ($y < 160) {
          $fpdf->SetFont('Arial', 'I', 7);
          $fpdf->SetTextColor(100, 100, 100);
          $fpdf->SetXY(10, $y);
          $fpdf->MultiCell(
              $pageWidth - 20,
              3,
              utf8_decode('Cette quittance fait foi de paiement des cautions pour le logement indiqué.'),
              0,
              'C'
          );
          $y += 10;
      }


      

      $date_entre = new DateTime($date_entre);

      // Code de référence
      $fpdf->SetFont('Courier', 'B', 8);
      $fpdf->SetTextColor(50, 50, 50);
      $ref = 'FACT-' . str_pad($id, 6, '0', STR_PAD_LEFT) . '-' . $date_entre->format('dmY');
      $fpdf->SetXY(10, $y);
      $fpdf->Cell(0, 4, utf8_decode('Référence: ') . $ref, 0, 1, 'C');
      $y += 8;

      // Pied de page avec signatures
      $signatureY = $pageHeight - 50;

      // Lignes de signature
      $fpdf->SetDrawColor(150, 150, 150);
      $fpdf->SetLineWidth(0.2);

      // Signature locataire
      $fpdf->SetFont('Arial', 'I', 7);
      $fpdf->SetTextColor(100, 100, 100);
      $fpdf->SetXY(10, $signatureY);
      $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du locataire'), 0, 0, 'C');

      // Signature responsable
      $fpdf->SetX(($pageWidth - 30) / 2 + 20);
      $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du responsable'), 0, 1, 'C');

      // Image de signature du responsable (si disponible)
      if ($annexeData && !empty($annexeData['signature_path']) && file_exists($annexeData['signature_path'])) {
          $sigImgX = ($pageWidth - 30) / 4 * 3 - 12;
          $fpdf->Image($annexeData['signature_path'], $sigImgX, $signatureY + 2, 25, 12);
      }

      $signatureY += 15;

      // Lignes de signature
      $lineLength = 40;
      $fpdf->Line(($pageWidth - 30) / 4, $signatureY, ($pageWidth - 30) / 4 + $lineLength, $signatureY);
      $fpdf->Line(($pageWidth - 30) / 4 * 3 - $lineLength / 2, $signatureY, ($pageWidth - 30) / 4 * 3 + $lineLength / 2, $signatureY);

      $signatureY += 3;

      // Noms sous les signatures
      $fpdf->SetFont('Arial', 'I', 6);
      $fpdf->SetXY(10, $signatureY);
      $fpdf->Cell(($pageWidth - 30) / 2, 3, utf8_decode($nom_locataire . ' ' . $prenom_locataire), 0, 0, 'C');
      $fpdf->SetX(($pageWidth - 30) / 2 + 20);
      $fpdf->Cell(($pageWidth - 30) / 2, 3, Auth::user()->nom . ' ' . Auth::user()->prenom, 0, 1, 'C');

      // Message de remerciement
      $fpdf->SetY($pageHeight - 12);
      $fpdf->SetFont('Arial', 'I', 7);
      $fpdf->SetTextColor($color_success[0], $color_success[1], $color_success[2]);
      $fpdf->Cell(0, 3, 'Merci pour votre confiance !', 0, 0, 'C');

      // Date de génération
      $fpdf->SetY($pageHeight - 8);
      $fpdf->SetFont('Arial', 'I', 6);
      $fpdf->SetTextColor(120, 120, 120);
      $fpdf->Cell(0, 3, utf8_decode('Quittance générée le ') . utf8_decode(date('d/m/Y à H:i')), 0, 0, 'C');

      // Journalisation
      activity()->performedOn(new Facture())
          ->causedBy(Auth::user()->id)
          ->log('Téléchargement de la quittance de caution de ' . $nom_locataire . ' ' . $prenom_locataire . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

      // Output
      $fileName = 'Quittance_Caution_' . str_replace(' ', '_', $nom_locataire) . '_' . date('Ymd') . '.pdf';
      $fpdf->Output('D', $fileName);

      return;
  }

  // Méthode optionnelle pour générer un QR code
  // private function generateQRCode($data)
  // {
  //     // Si vous avez la librairie phpqrcode installée
  //     if (class_exists('QRcode')) {
  //         $tempFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
  //         QRcode::png($data, $tempFile, 'L', 4, 2);
  //         return $tempFile;
  //     }
  //     return null;
  // }







}
