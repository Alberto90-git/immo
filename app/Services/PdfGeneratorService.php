<?php

namespace App\Services;

use App\Facture;
use App\Locataire;
use App\Proprietaire;
use App\Maison;
use Carbon\Carbon;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Auth;
use PDF;

class PdfGeneratorService
{
    /**
     * Génère le contrat PDF d'un locataire.
     * Retourne ['content' => string, 'filename' => string, 'label' => string]
     */
    public function genererContrat(int $locataireId): array
    {
        $data = Locataire::where('locataires.id', $locataireId)
                    ->whereNull('locataires.delete_at')
                    ->where('locataires.status', true)
                    ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                    ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                    ->select(
                        'locataires.*',
                        'maisons.nom_maison',
                        'maisons.quartier as quartier_maison',
                        'chambres.type_chambre',
                        'chambres.numero_chambre',
                        'chambres.prix_chambre'
                    )
                    ->first();

        if (!$data) {
            throw new \Exception('Locataire introuvable');
        }

        $idannexe_ref = get_active_annexe_id() ?? $data->idannexe_ref;
        $agence = get_annexe_details_for_invoice($idannexe_ref) ?? [
            'designation'      => 'Agence Immobilière',
            'telephone'        => '',
            'email'            => '',
            'siege_social'     => '',
            'logo_path'        => null,
            'logo_base64'      => null,
            'signature_path'   => null,
            'signature_base64' => null,
        ];

        $contratConfig = \App\ContratConfig::where('iddirection_ref', Auth::user()->iddirection_ref)->first();

        $replacements = [
            '{nom_agence}'           => $agence['designation'] ?? '',
            '{adresse_agence}'       => $agence['siege_social'] ?? '',
            '{telephone_agence}'     => $agence['telephone'] ?? '',
            '{nom_locataire}'        => trim(($data->nom ?? '') . ' ' . ($data->prenom ?? '')),
            '{telephone_locataire}'  => $data->telephone ?? '',
            '{profession_locataire}' => $data->profession ?? '',
            '{adresse_locataire}'    => $data->quartier ?? '',
            '{nom_maison}'           => $data->nom_maison ?? '',
            '{quartier_maison}'      => $data->quartier_maison ?? '',
            '{type_chambre}'         => $data->type_chambre ?? '',
            '{numero_chambre}'       => $data->numero_chambre ?? '',
            '{montant_loyer}'        => number_format($data->prix_mois ?? 0, 0, ',', '.') . ' F CFA',
            '{nombre_caution}'       => $data->nombre_caution ?? 0,
            '{montant_caution}'      => number_format(($data->nombre_caution ?? 0) * ($data->prix_mois ?? 0), 0, ',', '.') . ' F CFA',
            '{caution_courant}'      => number_format($data->caution_courant ?? 0, 0, ',', '.') . ' F CFA',
            '{caution_eau}'          => number_format($data->caution_eau ?? 0, 0, ',', '.') . ' F CFA',
            '{nombre_avance}'        => $data->nombre_avance ?? 0,
            '{montant_avance}'       => number_format(($data->nombre_avance ?? 0) * ($data->prix_mois ?? 0), 0, ',', '.') . ' F CFA',
            '{mode_paiement}'        => $data->mode_paiement ?? 'tout moyen convenu entre les parties',
            '{date_entree}'          => isset($data->date_entree) ? Carbon::parse($data->date_entree)->translatedFormat('d F Y') : 'N/A',
            '{date_contrat}'         => Carbon::now()->translatedFormat('d F Y'),
        ];

        $articlesCustom = null;
        if ($contratConfig && !empty($contratConfig->articles)) {
            $articlesCustom = array_map(function ($article) use ($replacements) {
                return [
                    'titre'   => str_replace(array_keys($replacements), array_values($replacements), $article['titre']),
                    'contenu' => str_replace(array_keys($replacements), array_values($replacements), $article['contenu']),
                ];
            }, $contratConfig->articles);
        }

        $pdf = PDF::loadView('pdf.contrat', compact('data', 'agence', 'contratConfig', 'articlesCustom'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => true]);

        $content  = $pdf->output();
        $filename = 'Contrat_' . ($data->nom ?? '') . '_' . ($data->prenom ?? '') . '_' . Carbon::now()->format('d-m-Y') . '.pdf';
        $label    = 'Contrat de ' . trim(($data->nom ?? '') . ' ' . ($data->prenom ?? ''));

        return compact('content', 'filename', 'label');
    }

    /**
     * Génère la quittance mensuelle PDF d'une facture.
     */
    public function genererQuittanceMensuelle(int $factureId): array
    {
        $facture = Facture::find($factureId);
        if (!$facture) {
            throw new \Exception('Facture introuvable');
        }

        $idannexe_ref = $facture->idannexe_ref ?? get_active_annexe_id();
        $annexeData   = get_annexe_details_for_invoice($idannexe_ref);

        $pageWidth  = 148;
        $pageHeight = 210;
        $color_header  = [52, 152, 219];
        $color_success = [39, 174, 96];

        $fpdf = new FPDF('P', 'mm', 'A5');
        $fpdf->SetAutoPageBreak(false);
        $fpdf->AddPage();

        $y = 10;

        // Logo
        $logoPath = ($annexeData && $annexeData['logo_path']) ? $annexeData['logo_path'] : null;
        if ($logoPath && file_exists($logoPath)) {
            $fpdf->Image($logoPath, 10, $y, 15);
        } else {
            $fpdf->SetFillColor(240, 240, 240);
            $fpdf->Rect(10, $y, 15, 15, 'F');
        }

        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
        $fpdf->SetXY(30, $y);
        $fpdf->Cell($pageWidth - 40, 8, 'QUITTANCE DE LOYER MENSUEL', 0, 1, 'C');

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
        $fpdf->SetDrawColor($color_header[0], $color_header[1], $color_header[2]);
        $fpdf->SetLineWidth(0.3);
        $fpdf->Line(10, $y, $pageWidth - 10, $y);
        $y += 6;

        // Récupération données via jointures
        $row = Facture::where('factures.id', $factureId)
                ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                ->join('chambres', 'factures.chambre_id', '=', 'chambres.id')
                ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                ->whereNull('maisons.delete_at')
                ->whereNull('chambres.delete_at')
                ->whereNull('factures.delete_at')
                ->select(
                    'maisons.nom_maison',
                    'factures.numero_chambre',
                    'factures.mode_paiement',
                    'locataires.nom',
                    'locataires.prenom',
                    'locataires.profession',
                    'factures.type_chambre',
                    'factures.montant',
                    'factures.mois',
                    'factures.type_paiement',
                    'factures.date_paiement'
                )
                ->first();

        if (!$row) {
            throw new \Exception('Données facture introuvables');
        }

        $nom_locataire    = $row->nom;
        $prenom_locataire = $row->prenom;
        $maison           = $row->nom_maison;
        $numero_chambre   = $row->numero_chambre;
        $mode_paiement    = $row->mode_paiement;
        $profession       = $row->profession;
        $type_chambre     = $row->type_chambre;
        $prix_mois        = $row->montant;
        $mois             = $row->mois;
        $type_paiement    = $row->type_paiement;
        $valeur           = $type_paiement == 'direct' ? 'Direct' : 'Dans son avance';
        $date_paiement    = $row->date_paiement;

        $labelWidth = 45;
        $lineHeight = 5;

        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
        $fpdf->SetXY(10, $y);
        $fpdf->Cell(0, 6, 'INFORMATIONS', 0, 1);
        $y += 8;

        $infos = [
            'Locataire:'      => utf8_decode($nom_locataire . ' ' . $prenom_locataire),
            'Profession:'     => utf8_decode($profession),
            'Maison:'         => utf8_decode($maison),
            'Chambre:'        => $type_chambre . ' N°' . $numero_chambre,
            'Mode paiement:'  => utf8_decode($mode_paiement),
            'Type paiement:'  => $valeur,
            'Mois:'           => $mois,
        ];

        foreach ($infos as $label => $val) {
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($labelWidth, $lineHeight, $label, 0, 0);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->Cell(0, $lineHeight, $val, 0, 1);
            $y += $lineHeight;
        }

        $y += 4;
        $colDesc  = 90;
        $colValue = 35;

        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetXY(10, $y);
        $fpdf->Cell($colDesc, 6, utf8_decode('MONTANT PERÇU:'), 0, 0, 'L');
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->SetTextColor($color_success[0], $color_success[1], $color_success[2]);
        $fpdf->SetX(10 + $colDesc);
        $fpdf->Cell($colValue, 6, number_format($prix_mois, 0, ",", ".") . ' XOF', 0, 1, 'C');
        $y += 10;

        if ($y < 140) {
            $montantLettres = ucfirst(nombreEnLettres($prix_mois)) . ' francs CFA';
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetX(10);
            $fpdf->MultiCell(190, 6, utf8_decode('Quittance arrêtée à :' . $montantLettres), 0, 'L');
            $y = $fpdf->GetY() + 8;
        }

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

        if ($y < 160) {
            $fpdf->SetFont('Arial', 'I', 7);
            $fpdf->SetTextColor(100, 100, 100);
            $fpdf->SetXY(10, $y);
            $fpdf->MultiCell($pageWidth - 20, 3, utf8_decode('Cette quittance fait foi de paiement du loyer pour la période indiquée.'), 0, 'C');
            $y += 10;
        }

        $datePaiementObj = new \DateTime($date_paiement);
        $ref = 'FACT-' . str_pad($factureId, 6, '0', STR_PAD_LEFT) . '-' . $datePaiementObj->format('dmY');
        $fpdf->SetFont('Courier', 'B', 8);
        $fpdf->SetTextColor(50, 50, 50);
        $fpdf->SetXY(10, $y);
        $fpdf->Cell(0, 4, utf8_decode('Référence: ') . $ref, 0, 1, 'C');
        $y += 8;

        $signatureY = $pageHeight - 50;
        $fpdf->SetDrawColor(150, 150, 150);
        $fpdf->SetLineWidth(0.2);
        $fpdf->SetFont('Arial', 'I', 7);
        $fpdf->SetTextColor(100, 100, 100);
        $fpdf->SetXY(10, $signatureY);
        $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du locataire'), 0, 0, 'C');
        $fpdf->SetX(($pageWidth - 30) / 2 + 20);
        $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du responsable'), 0, 1, 'C');

        if ($annexeData && !empty($annexeData['signature_path']) && file_exists($annexeData['signature_path'])) {
            $sigImgX = ($pageWidth - 30) / 4 * 3 - 12;
            $fpdf->Image($annexeData['signature_path'], $sigImgX, $signatureY + 2, 25, 12);
        }

        $signatureY += 15;
        $lineLength = 40;
        $fpdf->Line(($pageWidth - 30) / 4, $signatureY, ($pageWidth - 30) / 4 + $lineLength, $signatureY);
        $fpdf->Line(($pageWidth - 30) / 4 * 3 - $lineLength / 2, $signatureY, ($pageWidth - 30) / 4 * 3 + $lineLength / 2, $signatureY);
        $signatureY += 3;
        $fpdf->SetFont('Arial', 'I', 6);
        $fpdf->SetXY(10, $signatureY);
        $fpdf->Cell(($pageWidth - 30) / 2, 3, utf8_decode($nom_locataire . ' ' . $prenom_locataire), 0, 0, 'C');
        $fpdf->SetX(($pageWidth - 30) / 2 + 20);
        $fpdf->Cell(($pageWidth - 30) / 2, 3, Auth::user()->nom . ' ' . Auth::user()->prenom, 0, 1, 'C');

        $fpdf->SetY($pageHeight - 12);
        $fpdf->SetFont('Arial', 'I', 7);
        $fpdf->SetTextColor($color_success[0], $color_success[1], $color_success[2]);
        $fpdf->Cell(0, 3, 'Merci pour votre confiance !', 0, 0, 'C');
        $fpdf->SetY($pageHeight - 8);
        $fpdf->SetFont('Arial', 'I', 6);
        $fpdf->SetTextColor(120, 120, 120);
        $fpdf->Cell(0, 3, utf8_decode('Facture générée le ') . utf8_decode(date('d/m/Y à H:i')), 0, 0, 'C');

        $content  = $fpdf->Output('S', '');
        $filename = 'Quittance_Mensuelle_' . str_replace(' ', '_', $nom_locataire) . '_' . date('Ymd') . '.pdf';
        $label    = 'Quittance mensuelle de ' . trim($nom_locataire . ' ' . $prenom_locataire) . ' - ' . $mois;

        return compact('content', 'filename', 'label');
    }

    /**
     * Génère la quittance de caution PDF d'un locataire.
     */
    public function genererQuittanceCaution(int $locataireId): array
    {
        $locataire = Locataire::find($locataireId);
        if (!$locataire) {
            throw new \Exception('Locataire introuvable');
        }

        $idannexe_ref = $locataire->idannexe_ref ?? get_active_annexe_id();
        $annexeData   = get_annexe_details_for_invoice($idannexe_ref);

        $pageWidth  = 148;
        $pageHeight = 210;
        $color_header  = [52, 152, 219];
        $color_success = [39, 174, 96];

        $fpdf = new FPDF('P', 'mm', 'A5');
        $fpdf->SetAutoPageBreak(false);
        $fpdf->AddPage();

        $y = 10;

        $logoPath = ($annexeData && $annexeData['logo_path']) ? $annexeData['logo_path'] : null;
        if ($logoPath && file_exists($logoPath)) {
            $fpdf->Image($logoPath, 10, $y, 15);
        } else {
            $fpdf->SetFillColor(240, 240, 240);
            $fpdf->Rect(10, $y, 15, 15, 'F');
        }

        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
        $fpdf->SetXY(30, $y);
        $fpdf->Cell($pageWidth - 40, 8, 'QUITTANCE DE CAUTION', 0, 1, 'C');

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
        $fpdf->SetDrawColor($color_header[0], $color_header[1], $color_header[2]);
        $fpdf->SetLineWidth(0.3);
        $fpdf->Line(10, $y, $pageWidth - 10, $y);
        $y += 6;

        // Récupération données
        $row = Locataire::where('locataires.id', $locataireId)
                ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                ->whereNull('maisons.delete_at')
                ->whereNull('chambres.delete_at')
                ->select(
                    'maisons.nom_maison',
                    'locataires.nom',
                    'locataires.prenom',
                    'chambres.numero_chambre',
                    'locataires.profession',
                    'chambres.type_chambre',
                    'locataires.prix_mois',
                    'locataires.nombre_avance',
                    'locataires.nombre_caution',
                    'locataires.caution_courant',
                    'locataires.caution_eau',
                    'locataires.date_entree',
                    'locataires.mode_paiement'
                )
                ->first();

        if (!$row) {
            throw new \Exception('Données locataire introuvables');
        }

        $nom_locataire    = $row->nom;
        $prenom_locataire = $row->prenom;
        $maison           = $row->nom_maison;
        $numero_chambre   = $row->numero_chambre;
        $profession       = $row->profession;
        $type_chambre     = $row->type_chambre;
        $prix_mois        = $row->prix_mois;
        $nombre_avance    = $row->nombre_avance;
        $nombre_caution   = $row->nombre_caution;
        $caution_courant  = $row->caution_courant;
        $caution_eau      = $row->caution_eau;
        $date_entre       = $row->date_entree;
        $mode_paiement    = $row->mode_paiement;

        $labelWidth = 45;
        $lineHeight = 5;

        $fpdf->SetFont('Arial', 'B', 10);
        $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
        $fpdf->SetXY(10, $y);
        $fpdf->Cell(0, 6, 'INFORMATIONS', 0, 1);
        $y += 8;

        $infos = [
            'Locataire:'     => utf8_decode($nom_locataire . ' ' . $prenom_locataire),
            'Profession:'    => utf8_decode($profession),
            'Maison:'        => utf8_decode($maison),
            'Chambre:'       => $type_chambre . ' N°' . $numero_chambre,
            'Date entrée:'   => $date_entre,
            'Mode paiement:' => utf8_decode($mode_paiement),
        ];

        foreach ($infos as $label => $val) {
            $fpdf->SetFont('Arial', 'B', 8);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(10, $y);
            $fpdf->Cell($labelWidth, $lineHeight, $label, 0, 0);
            $fpdf->SetFont('Arial', '', 8);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->Cell(0, $lineHeight, $val, 0, 1);
            $y += $lineHeight;
        }

        $y += 4;
        $colDesc  = 70;
        $colQte   = 25;
        $colMontant = 30;

        $fpdf->SetFillColor($color_header[0], $color_header[1], $color_header[2]);
        $fpdf->SetTextColor(255, 255, 255);
        $fpdf->SetFont('Arial', 'B', 8);
        $fpdf->SetXY(10, $y);
        $fpdf->Cell($colDesc, 6, 'DESCRIPTION', 1, 0, 'L', true);
        $fpdf->Cell($colQte, 6, 'QTE', 1, 0, 'C', true);
        $fpdf->Cell($colMontant, 6, 'MONTANT', 1, 1, 'C', true);
        $y += 6;

        $montant_avance = $prix_mois * $nombre_avance;
        $montant_caution = $prix_mois * $nombre_caution;
        $total = $montant_avance + $caution_courant + $caution_eau + $montant_caution;

        $items = [
            ['Avance', $nombre_avance . ' mois', $montant_avance],
            ['Caution', $nombre_caution . ' mois', $montant_caution],
            ['Caution électricité', '1', $caution_courant],
            ['Caution eau', '1', $caution_eau],
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

        $y += 4;
        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetXY(10, $y);
        $fpdf->Cell($colDesc + $colQte, 6, 'TOTAL A PAYER:', 0, 0, 'L');
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->SetTextColor($color_success[0], $color_success[1], $color_success[2]);
        $fpdf->SetX(10 + $colDesc + $colQte);
        $fpdf->Cell($colMontant, 6, number_format($total, 0, ",", ".") . ' XOF', 0, 1, 'C');
        $y += 12;

        if ($y < 140) {
            $montantLettres = ucfirst(nombreEnLettres($total)) . ' XOF';
            $fpdf->SetFont('Arial', 'B', 10);
            $fpdf->SetTextColor($color_header[0], $color_header[1], $color_header[2]);
            $fpdf->SetX(10);
            $fpdf->MultiCell(190, 6, utf8_decode('Quittance arrêtée à ' . $montantLettres), 0, 'L');
            $y = $fpdf->GetY() + 8;
        }

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

        $signatureY = $pageHeight - 45;
        $fpdf->SetDrawColor(150, 150, 150);
        $fpdf->SetLineWidth(0.2);
        $fpdf->SetFont('Arial', 'I', 7);
        $fpdf->SetTextColor(100, 100, 100);
        $fpdf->SetXY(10, $signatureY);
        $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du locataire'), 0, 0, 'C');
        $fpdf->SetX(($pageWidth - 30) / 2 + 20);
        $fpdf->Cell(($pageWidth - 30) / 2, 4, utf8_decode('Signature du responsable'), 0, 1, 'C');

        if ($annexeData && !empty($annexeData['signature_path']) && file_exists($annexeData['signature_path'])) {
            $sigImgX = ($pageWidth - 30) / 4 * 3 - 12;
            $fpdf->Image($annexeData['signature_path'], $sigImgX, $signatureY + 2, 25, 12);
        }

        $content  = $fpdf->Output('S', '');
        $filename = 'Quittance_Caution_' . str_replace(' ', '_', $nom_locataire) . '_' . date('Ymd') . '.pdf';
        $label    = 'Quittance de caution de ' . trim($nom_locataire . ' ' . $prenom_locataire);

        return compact('content', 'filename', 'label');
    }

    /**
     * Génère le relevé propriétaire PDF.
     */
    public function genererReleveProprietaire(int $proprietaireId, string $debut, string $fin, float $pct): array
    {
        $proprio = Proprietaire::find($proprietaireId);
        if (!$proprio) {
            throw new \Exception('Propriétaire introuvable');
        }

        $element2 = [
            'proprio_nom'    => $proprio->nom,
            'proprio_prenom' => $proprio->prenom,
            'date_debut'     => $debut,
            'date_fin'       => $fin,
            'pourcentage'    => $pct,
            'donnees'        => $this->getMaisonData($proprietaireId, $debut, $fin),
            'garde'          => 0,
        ];

        foreach ($element2['donnees'] as $value) {
            $element2['garde'] += $value->montant * (100 - $pct) / 100;
        }

        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.proprio_solde', compact('element2', 'annexeData'))
                  ->setOptions(['defaultFont' => 'sans-serif']);

        $content  = $pdf->output();
        $filename = 'Releve_Proprietaire_' . str_replace(' ', '_', $proprio->nom) . '_' . date('Ymd') . '.pdf';
        $label    = 'Relevé propriétaire ' . trim($proprio->nom . ' ' . $proprio->prenom) . ' du ' . $debut . ' au ' . $fin;

        return compact('content', 'filename', 'label');
    }

    /**
     * Génère le relevé agence PDF.
     */
    public function genererReleveAgence(int $proprietaireId, string $debut, string $fin, float $pct): array
    {
        $proprio = Proprietaire::find($proprietaireId);
        if (!$proprio) {
            throw new \Exception('Propriétaire introuvable');
        }

        $element2 = [
            'proprio_nom'    => $proprio->nom,
            'proprio_prenom' => $proprio->prenom,
            'date_debut'     => $debut,
            'date_fin'       => $fin,
            'pourcentage'    => $pct,
            'donnees'        => $this->getMaisonData($proprietaireId, $debut, $fin),
            'garde'          => 0,
        ];

        foreach ($element2['donnees'] as $value) {
            $element2['garde'] += $value->montant * $pct / 100;
        }

        $annexeData = get_annexe_details_for_invoice(get_active_annexe_id());
        $pdf = PDF::loadView('pdf.agence_solde', compact('element2', 'annexeData'))
                  ->setOptions(['defaultFont' => 'sans-serif']);

        $content  = $pdf->output();
        $filename = 'Releve_Agence_' . str_replace(' ', '_', $proprio->nom) . '_' . date('Ymd') . '.pdf';
        $label    = 'Relevé agence chez ' . trim($proprio->nom . ' ' . $proprio->prenom) . ' du ' . $debut . ' au ' . $fin;

        return compact('content', 'filename', 'label');
    }

    private function getMaisonData(int $proprietaireId, string $debut, string $fin)
    {
        return Maison::whereBetween('factures.created_at', [$debut . ' 01:00:00', $fin . ' 23:59:59'])
            ->join('factures', 'factures.maison_id', '=', 'maisons.id')
            ->join('annexes', 'annexes.idannexes', '=', 'maisons.idannexe_ref')
            ->where('maisons.proprio_id', $proprietaireId)
            ->where('maisons.iddirection_ref', Auth::user()->iddirection_ref)
            ->whereNull('maisons.delete_at')
            ->select(
                'annexes.designation',
                'maisons.nom_maison',
                'maisons.quartier',
                'factures.montant',
                'factures.numero_chambre',
                'factures.type_chambre'
            )
            ->get();
    }
}
