<?php

namespace App\Services;

class LogoGeneratorService
{
    private string $fontBold;

    public function __construct()
    {
        $base = base_path('vendor/dompdf/dompdf/lib/fonts/');
        $this->fontBold = $base . 'DejaVuSans-Bold.ttf';
    }

    /**
     * Génère un logo PNG badge circulaire.
     * Retourne le chemin relatif storage (ex: "logos/logo_1.png").
     */
    public function generate(string $designation, int $directionId): ?string
    {
        if (!extension_loaded('gd') || !function_exists('imagettftext')) {
            return null;
        }

        $size = 400;
        $cx   = $size / 2;   // 200
        $cy   = $size / 2;   // 200

        $img = imagecreatetruecolor($size, $size);
        imageantialias($img, true);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 15,  15,  15);

        imagefill($img, 0, 0, $white);

        // ── Bordure circulaire double ─────────────────────────────────────────
        imageellipse($img, (int)$cx, (int)$cy, 192 * 2, 192 * 2, $black);
        imageellipse($img, (int)$cx, (int)$cy, 185 * 2, 185 * 2, $black);

        // ── Initiales centrées ────────────────────────────────────────────────
        if (file_exists($this->fontBold)) {
            $initials = $this->getInitials($designation, 2);
            $fontSize = mb_strlen($initials) === 1 ? 140 : 95;

            $bbox = @imagettfbbox($fontSize, 0, $this->fontBold, $initials);
            if ($bbox) {
                $tw = abs($bbox[2] - $bbox[0]);
                $th = abs($bbox[7] - $bbox[1]);
                imagettftext(
                    $img, $fontSize, 0,
                    (int) round($cx - $tw / 2),
                    (int) round($cy + $th / 2),
                    $black, $this->fontBold, $initials
                );
            }

            // ── Nom en arc haut — espacement proportionnel ────────────────────
            $arcText  = mb_strtoupper(mb_substr($designation, 0, 28));
            $arcSz    = mb_strlen($arcText) > 20 ? 14.0 : (mb_strlen($arcText) > 14 ? 16.0 : 18.0);
            $textR    = 160.0;   // rayon entre bordure et initiales
            $maxSpan  = 145.0;   // max 145° pour garder les lettres quasi droites

            $this->drawArcTextProportional(
                $img, $arcText,
                $cx, $cy, $textR,
                -90.0,           // centré au sommet
                $maxSpan,
                $black, $arcSz, $this->fontBold,
                false            // flip=false → arc haut, lettres vers l'extérieur
            );
        }

        // ── Sauvegarde ────────────────────────────────────────────────────────
        $dir = storage_path('app/public/logos');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = 'logo_' . $directionId . '.png';
        imagepng($img, $dir . '/' . $filename, 6);
        imagedestroy($img);

        return 'logos/' . $filename;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Arc text avec espacement PROPORTIONNEL à la largeur réelle de chaque lettre.
    // Cela évite que les lettres larges (M, W) et étroites (I, l) soient
    // distribuées uniformément en degrés — ce qui causait un rendu inégal.
    //
    // $centerAngle : angle GD du centre de l'arc (-90° = sommet, 90° = bas)
    // $maxSpan     : span angulaire max en degrés
    // flip=false   : rot = angleDeg + 90  (haut, sommet vers l'extérieur)
    // flip=true    : rot = angleDeg - 90  (bas,  sommet vers le centre)
    // ─────────────────────────────────────────────────────────────────────────
    private function drawArcTextProportional(
        $img,
        string $text,
        float $cx, float $cy, float $radius,
        float $centerAngle,
        float $maxSpan,
        $color,
        float $fontSize,
        string $fontPath,
        bool $flip = false
    ): void {
        if (!file_exists($fontPath)) return;

        $chars = mb_str_split($text);
        $n     = count($chars);
        if ($n === 0) return;

        // ── 1. Mesurer la largeur réelle de chaque caractère ─────────────────
        $kerningPx  = $fontSize * 0.10;   // espace inter-lettre = 10% de la taille
        $charWidths = [];

        foreach ($chars as $char) {
            if ($char === ' ') {
                $charWidths[] = $fontSize * 0.28;
                continue;
            }
            $b = @imagettfbbox($fontSize, 0, $fontPath, $char);
            $charWidths[] = $b ? abs($b[2] - $b[0]) : $fontSize * 0.55;
        }

        // ── 2. Largeur totale en pixels → angle total en degrés ──────────────
        $totalPx  = array_sum($charWidths) + $kerningPx * ($n - 1);
        $totalDeg = rad2deg($totalPx / $radius);  // arc length = r * θ (rad)

        // ── 3. Mise à l'échelle si trop large ────────────────────────────────
        $scale = 1.0;
        if ($totalDeg > $maxSpan) {
            $scale    = $maxSpan / $totalDeg;
            $totalDeg = $maxSpan;
            $charWidths  = array_map(fn($w) => $w * $scale, $charWidths);
            $kerningPx  *= $scale;
        }

        // ── 4. Angle de départ (pour centrer l'arc) ───────────────────────────
        $currentAngle = $centerAngle - $totalDeg / 2.0;

        // ── 5. Placer chaque caractère ────────────────────────────────────────
        foreach ($chars as $i => $char) {
            $charDeg    = rad2deg($charWidths[$i] / $radius);
            $kernDeg    = rad2deg($kerningPx / $radius);
            $charCenter = $currentAngle + $charDeg / 2.0;

            $angleRad = deg2rad($charCenter);
            $x = $cx + $radius * cos($angleRad);
            $y = $cy + $radius * sin($angleRad);

            $rot  = $flip ? $charCenter - 90.0 : $charCenter + 90.0;
            $bbox = @imagettfbbox($fontSize, $rot, $fontPath, $char);
            if (!$bbox) {
                $currentAngle += $charDeg + $kernDeg;
                continue;
            }

            // Centre géométrique de la boîte englobante
            $bCx = ($bbox[0] + $bbox[2] + $bbox[4] + $bbox[6]) / 4.0;
            $bCy = ($bbox[1] + $bbox[3] + $bbox[5] + $bbox[7]) / 4.0;

            imagettftext(
                $img, $fontSize, $rot,
                (int) round($x - $bCx),
                (int) round($y - $bCy),
                $color, $fontPath, $char
            );

            $currentAngle += $charDeg + $kernDeg;
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    private function getInitials(string $text, int $max = 2): string
    {
        $words    = preg_split('/\s+/', trim($text));
        $initials = '';
        foreach ($words as $word) {
            if (mb_strlen($word) > 0) {
                $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            }
            if (mb_strlen($initials) >= $max) break;
        }
        return $initials ?: mb_strtoupper(mb_substr($text, 0, 1));
    }
}
