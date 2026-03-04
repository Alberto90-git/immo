<?php

namespace App\Services;

use Codedge\Fpdf\Fpdf\Fpdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SubscriptionInvoiceService
{
    /**
     * Génère une facture d'abonnement PDF et la retourne en string.
     *
     * @param array $data Keys: user (nom, prenom, email, telephone),
     *                     plan (nom, code, prix_annuel, max_maisons, max_annexes),
     *                     direction (designation, abonnement_debut, abonnement_fin)
     * @return string PDF content
     */
    public function generate(array $data): string
    {
      try {
        $fpdf = new Fpdf('P', 'mm', 'A4');
        $fpdf->SetAutoPageBreak(true, 15);
        $fpdf->AddPage();

        $pageWidth = 210;

        // Couleurs
        $primaryR = 30; $primaryG = 64; $primaryB = 175;
        $successR = 39; $successG = 174; $successB = 96;

        $y = 15;

        // --- EN-TETE ---
        $logoPath = public_path('logo/LOGO.jpg');
        if (file_exists($logoPath)) {
            $fpdf->Image($logoPath, 15, $y, 25);
        }

        $fpdf->SetFont('Arial', 'B', 20);
        $fpdf->SetTextColor($primaryR, $primaryG, $primaryB);
        $fpdf->SetXY(45, $y);
        $fpdf->Cell(0, 10, 'Lokativ', 0, 1);

        $fpdf->SetFont('Arial', '', 9);
        $fpdf->SetTextColor(100, 100, 100);
        $fpdf->SetXY(45, $y + 10);
        $fpdf->Cell(0, 5, utf8_decode('Plateforme de gestion immobilière'), 0, 1);

        $y += 25;

        // --- LIGNE SEPARATRICE ---
        $fpdf->SetDrawColor($primaryR, $primaryG, $primaryB);
        $fpdf->SetLineWidth(0.5);
        $fpdf->Line(15, $y, $pageWidth - 15, $y);
        $y += 8;

        // --- TITRE FACTURE ---
        $fpdf->SetFont('Arial', 'B', 16);
        $fpdf->SetTextColor($primaryR, $primaryG, $primaryB);
        $fpdf->SetXY(15, $y);
        $fpdf->Cell(0, 10, utf8_decode("FACTURE D'ABONNEMENT"), 0, 1, 'C');
        $y += 15;

        // --- METADATA (numero, date) ---
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        $fpdf->SetFont('Arial', '', 9);
        $fpdf->SetTextColor(80, 80, 80);
        $fpdf->SetXY(15, $y);
        $fpdf->Cell(90, 5, utf8_decode('N° Facture: ') . $invoiceNumber, 0, 0);
        $fpdf->Cell(0, 5, 'Date: ' . Carbon::now()->format('d/m/Y'), 0, 1, 'R');
        $y += 12;

        // --- INFORMATIONS CLIENT ---
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->SetTextColor($primaryR, $primaryG, $primaryB);
        $fpdf->SetXY(15, $y);
        $fpdf->Cell(0, 7, 'INFORMATIONS CLIENT', 0, 1);
        $y += 8;

        $labelWidth = 40;
        $lineHeight = 6;

        $clientFields = [
            'Nom' => $data['user']['nom'] . ' ' . $data['user']['prenom'],
            'Email' => $data['user']['email'],
            utf8_decode('Téléphone') => $data['user']['telephone'],
            utf8_decode('Société') => utf8_decode($data['direction']['designation']),
        ];

        foreach ($clientFields as $label => $value) {
            $fpdf->SetFont('Arial', 'B', 9);
            $fpdf->SetTextColor(80, 80, 80);
            $fpdf->SetXY(15, $y);
            $fpdf->Cell($labelWidth, $lineHeight, $label . ':', 0, 0);
            $fpdf->SetFont('Arial', '', 9);
            $fpdf->SetTextColor(0, 0, 0);
            $fpdf->Cell(0, $lineHeight, $value, 0, 1);
            $y += $lineHeight;
        }

        $y += 10;

        // --- DETAILS ABONNEMENT (tableau) ---
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->SetTextColor($primaryR, $primaryG, $primaryB);
        $fpdf->SetXY(15, $y);
        $fpdf->Cell(0, 7, utf8_decode("DÉTAILS DE L'ABONNEMENT"), 0, 1);
        $y += 10;

        $colDesc = 100;
        $colValue = 80;

        // En-tete du tableau
        $fpdf->SetFillColor($primaryR, $primaryG, $primaryB);
        $fpdf->SetTextColor(255, 255, 255);
        $fpdf->SetFont('Arial', 'B', 9);
        $fpdf->SetXY(15, $y);
        $fpdf->Cell($colDesc, 7, 'DESCRIPTION', 1, 0, 'L', true);
        $fpdf->Cell($colValue, 7, 'VALEUR', 1, 1, 'C', true);
        $y += 7;

        // Lignes du tableau
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetFont('Arial', '', 9);

        $maxMaisons = $data['plan']['max_maisons'] == 0 ? utf8_decode('Illimité') : $data['plan']['max_maisons'];

        $tableRows = [
            ['Plan', utf8_decode($data['plan']['nom'])],
            ['Code', strtoupper($data['plan']['code'])],
            ['Maisons max.', $maxMaisons],
            ['Annexes max.', $data['plan']['max_annexes']],
            [utf8_decode('Période'), Carbon::parse($data['direction']['abonnement_debut'])->format('d/m/Y') . ' - ' . Carbon::parse($data['direction']['abonnement_fin'])->format('d/m/Y')],
            [utf8_decode('Durée'), '12 mois'],
        ];

        foreach ($tableRows as $row) {
            $fpdf->SetXY(15, $y);
            $fpdf->SetFont('Arial', '', 9);
            $fpdf->Cell($colDesc, 7, $row[0], 1, 0, 'L');
            $fpdf->SetFont('Arial', 'B', 9);
            $fpdf->Cell($colValue, 7, $row[1], 1, 1, 'C');
            $y += 7;
        }

        // Separateur
        $y += 3;
        $fpdf->SetDrawColor(200, 200, 200);
        $fpdf->SetLineWidth(0.2);
        $fpdf->Line(15, $y, $pageWidth - 15, $y);
        $y += 5;

        // Total
        $fpdf->SetFont('Arial', 'B', 11);
        $fpdf->SetTextColor(0, 0, 0);
        $fpdf->SetXY(15, $y);
        $fpdf->Cell($colDesc, 8, 'TOTAL:', 0, 0, 'R');

        $prix = floatval($data['plan']['prix_annuel']);
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->SetTextColor($successR, $successG, $successB);
        $fpdf->Cell($colValue, 8, number_format($prix, 0, ',', '.') . ' XOF', 0, 1, 'C');

        $y += 20;

        // --- PIED DE PAGE ---
        $fpdf->SetFont('Arial', 'I', 8);
        $fpdf->SetTextColor(100, 100, 100);
        $fpdf->SetXY(15, $y);
        $fpdf->MultiCell($pageWidth - 30, 4, utf8_decode(
            "Cette facture a été générée automatiquement lors de la création de votre compte Lokativ.\n" .
            "Pour toute question, contactez notre support."
        ), 0, 'C');

        $fpdf->SetY(-15);
        $fpdf->SetFont('Arial', 'I', 7);
        $fpdf->SetTextColor($primaryR, $primaryG, $primaryB);
        $fpdf->Cell(0, 4, utf8_decode('Lokativ - Facture générée le ') . date('d/m/Y H:i'), 0, 0, 'C');

        $pdfContent = $fpdf->Output('S');

        if (empty($pdfContent)) {
            Log::error('SubscriptionInvoiceService: Le PDF généré est vide', [
                'user_email' => $data['user']['email'] ?? 'inconnu',
            ]);
            throw new \RuntimeException('Le contenu PDF généré est vide');
        }

        Log::info('SubscriptionInvoiceService: PDF généré avec succès', [
            'taille' => strlen($pdfContent),
            'user_email' => $data['user']['email'] ?? 'inconnu',
        ]);

        return $pdfContent;
      } catch (\Exception $e) {
        Log::error('SubscriptionInvoiceService: Erreur lors de la génération du PDF', [
            'message' => $e->getMessage(),
            'user_email' => $data['user']['email'] ?? 'inconnu',
        ]);
        throw $e;
      }
    }
}
