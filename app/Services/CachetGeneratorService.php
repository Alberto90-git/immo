<?php

namespace App\Services;

class CachetGeneratorService
{
    private string $fontRegular;
    private string $fontBold;

    public function __construct()
    {
        $base = base_path('vendor/dompdf/dompdf/lib/fonts/');
        $this->fontRegular = $base . 'DejaVuSans.ttf';
        $this->fontBold    = $base . 'DejaVuSans-Bold.ttf';
    }

    /**
     * Génère un cachet PNG circulaire pour une agence et retourne le chemin relatif storage.
     */
    public function generate(string $designation, int $directionId): ?string
    {
        if (!extension_loaded('gd') || !function_exists('imagettftext')) {
            return null;
        }

        $size = 420;
        $cx   = $size / 2;
        $cy   = $size / 2;

        $img = imagecreatetruecolor($size, $size);
        imageantialias($img, true);

        $white  = imagecolorallocate($img, 255, 255, 255);
        $red    = imagecolorallocate($img, 139, 0, 0);

        imagefill($img, 0, 0, $white);

        // Cercle extérieur (triple trait pour épaisseur)
        $outerR = 195;
        for ($i = 0; $i < 4; $i++) {
            imageellipse($img, (int)$cx, (int)$cy, ($outerR - $i) * 2, ($outerR - $i) * 2, $red);
        }

        // Cercle intérieur (double trait)
        $innerR = 160;
        for ($i = 0; $i < 2; $i++) {
            imageellipse($img, (int)$cx, (int)$cy, ($innerR - $i) * 2, ($innerR - $i) * 2, $red);
        }

        // Texte du haut (désignation de l'agence) — arc supérieur
        $topText  = mb_strtoupper(mb_substr($designation, 0, 30));
        $textR    = 177;
        $this->drawArcText($img, $topText, $cx, $cy, $textR, -155, -25, $red, 11, $this->fontRegular, false);

        // Texte du bas (sigle/points) — arc inférieur
        $abbr       = $this->makeAbbr($designation);
        $bottomText = "\u{2022} " . $abbr . " \u{2022}";
        $this->drawArcText($img, $bottomText, $cx, $cy, $textR, 30, 150, $red, 10, $this->fontRegular, true);

        // Texte central (2 lignes)
        $this->centerText($img, 'CACHET',   $cx, $cy - 14, 18, $red, $this->fontBold);
        $this->centerText($img, 'OFFICIEL', $cx, $cy + 16, 12, $red, $this->fontRegular);

        // Sauvegarde
        $dir = storage_path('app/public/cachetons');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = 'cachet_' . $directionId . '.png';
        $filepath = $dir . '/' . $filename;
        imagepng($img, $filepath, 6);
        imagedestroy($img);

        return 'cachetons/' . $filename;
    }

    /**
     * Place chaque caractère du texte le long d'un arc, en le faisant pivoter.
     * $startAngle / $endAngle en degrés, convention trigonométrique (0 = droite, sens horaire en écran).
     * $flip = true pour l'arc inférieur (texte lisible depuis l'extérieur).
     */
    private function drawArcText($img, string $text, float $cx, float $cy, float $radius,
                                  float $startAngle, float $endAngle, $color,
                                  float $fontSize, string $fontPath, bool $flip = false): void
    {
        if (!file_exists($fontPath)) return;

        $chars = mb_str_split($text);
        $n     = count($chars);
        if ($n === 0) return;

        $totalAngle = $endAngle - $startAngle;
        $step       = $n > 1 ? $totalAngle / ($n - 1) : 0;

        foreach ($chars as $i => $char) {
            $angleDeg = $startAngle + $i * $step;
            $angleRad = deg2rad($angleDeg);

            // Centre du caractère sur l'arc
            $x = $cx + $radius * cos($angleRad);
            $y = $cy + $radius * sin($angleRad);

            // Angle de rotation pour imagettftext (sens anti-horaire)
            $rot = $flip ? $angleDeg - 90 : $angleDeg + 90;

            // Boîte englobante pour centrer le caractère sur sa position
            $bbox  = @imagettfbbox($fontSize, $rot, $fontPath, $char);
            if (!$bbox) continue;
            $bCx   = ($bbox[0] + $bbox[2] + $bbox[4] + $bbox[6]) / 4;
            $bCy   = ($bbox[1] + $bbox[3] + $bbox[5] + $bbox[7]) / 4;

            imagettftext($img, $fontSize, $rot, (int)round($x - $bCx), (int)round($y - $bCy), $color, $fontPath, $char);
        }
    }

    private function centerText($img, string $text, float $cx, float $cy,
                                 float $size, $color, string $fontPath): void
    {
        if (!file_exists($fontPath)) return;

        $bbox  = @imagettfbbox($size, 0, $fontPath, $text);
        if (!$bbox) return;
        $textW = abs($bbox[2] - $bbox[0]);
        $textH = abs($bbox[7] - $bbox[1]);

        imagettftext($img, $size, 0,
            (int)round($cx - $textW / 2),
            (int)round($cy + $textH / 2),
            $color, $fontPath, $text);
    }

    private function makeAbbr(string $text): string
    {
        $words = preg_split('/\s+/', trim($text));
        $abbr  = '';
        foreach ($words as $w) {
            if (mb_strlen($w) > 2) {
                $abbr .= mb_strtoupper(mb_substr($w, 0, 1));
            }
        }
        return $abbr ?: mb_strtoupper(mb_substr($text, 0, 4));
    }
}
