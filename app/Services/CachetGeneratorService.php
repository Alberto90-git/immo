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
     * Génère un cachet PNG circulaire et retourne le chemin relatif storage.
     */
    public function generate(string $designation, int $directionId): ?string
    {
        if (!extension_loaded('gd') || !function_exists('imagettftext')) {
            return null;
        }

        $size = 500;
        $cx   = $size / 2;   // 250
        $cy   = $size / 2;   // 250

        $img = imagecreatetruecolor($size, $size);
        imageantialias($img, true);

        $white = imagecolorallocate($img, 255, 255, 255);
        $red   = imagecolorallocate($img, 128, 8, 8);   // bordeaux foncé comme le référentiel

        imagefill($img, 0, 0, $white);

        // ── Anneau extérieur : 2 cercles fins ─────────────────────────────
        $this->thinCircle($img, (int)$cx, (int)$cy, 238, $red);
        $this->thinCircle($img, (int)$cx, (int)$cy, 231, $red);

        // ── Anneau intérieur : 2 cercles fins ─────────────────────────────
        $this->thinCircle($img, (int)$cx, (int)$cy, 183, $red);
        $this->thinCircle($img, (int)$cx, (int)$cy, 176, $red);

        // ── Rayon du texte dans l'anneau (mi-chemin entre les deux anneaux)
        $textR = 207;

        // ── Arc supérieur : nom de l'entreprise (centré en haut, -90°) ────
        $topText = mb_strtoupper(mb_substr($designation, 0, 32));
        $n       = mb_strlen($topText);

        // Espacement angulaire adaptatif : ~5.5° par caractère, max 230°
        $degPer   = $n > 22 ? 5.0 : ($n > 14 ? 5.8 : 7.0);
        $topSpan  = min($n * $degPer, 230.0);
        $this->drawArcText(
            $img, $topText,
            $cx, $cy, $textR,
            -90.0 - $topSpan / 2.0,
            -90.0 + $topSpan / 2.0,
            $red, 12, $this->fontRegular, false
        );

        // ── Arc inférieur : "• DIRECTEUR GÉNÉRAL •" (centré en bas, 90°) ──
        $bottomText = "\u{2022} DIRECTEUR G\u{00C9}N\u{00C9}RAL \u{2022}";
        $bn         = mb_strlen($bottomText);
        $botSpan    = min($bn * 5.0, 162.0);
        $this->drawArcText(
            $img, $bottomText,
            $cx, $cy, $textR,
            90.0 - $botSpan / 2.0,
            90.0 + $botSpan / 2.0,
            $red, 11, $this->fontBold, true
        );

        // ── Centre : "Le" + "Directeur" + "Général" ───────────────────────
        $this->centerText($img, 'Le',          $cx, $cy - 26, 15, $red, $this->fontRegular);
        $this->centerText($img, 'Directeur',   $cx, $cy +  6, 21, $red, $this->fontBold);
        $this->centerText($img, "G\u{00E9}n\u{00E9}ral", $cx, $cy + 36, 21, $red, $this->fontBold);

        // ── Sauvegarde ─────────────────────────────────────────────────────
        $dir = storage_path('app/public/cachetons');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = 'cachet_' . $directionId . '.png';
        imagepng($img, $dir . '/' . $filename, 6);
        imagedestroy($img);

        return 'cachetons/' . $filename;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Dessine un cercle fin (1 pixel)
    // ─────────────────────────────────────────────────────────────────────────
    private function thinCircle($img, int $cx, int $cy, int $radius, $color): void
    {
        imageellipse($img, $cx, $cy, $radius * 2, $radius * 2, $color);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Place chaque caractère debout le long d'un arc.
    //
    // Convention angles : trigonométrique GD (0° = droite, sens horaire).
    //   → top arc  : startAngle=-90-span/2  endAngle=-90+span/2  flip=false
    //   → bot arc  : startAngle=90-span/2   endAngle=90+span/2   flip=true
    //
    // Rotation du glyphe :
    //   flip=false (haut)  → rot = angleDeg + 90   le dessus du char pointe vers l'extérieur
    //   flip=true  (bas)   → rot = angleDeg - 90   le dessus du char pointe vers le centre
    //                         (texte lisible quand on retourne le cachet)
    // ─────────────────────────────────────────────────────────────────────────
    private function drawArcText(
        $img,
        string $text,
        float $cx, float $cy, float $radius,
        float $startAngle, float $endAngle,
        $color,
        float $fontSize,
        string $fontPath,
        bool $flip = false
    ): void {
        if (!file_exists($fontPath)) return;

        $chars = mb_str_split($text);
        $n     = count($chars);
        if ($n === 0) return;

        $totalAngle = $endAngle - $startAngle;
        $step       = $n > 1 ? $totalAngle / ($n - 1) : 0.0;

        foreach ($chars as $i => $char) {
            $angleDeg = $startAngle + $i * $step;
            $angleRad = deg2rad($angleDeg);

            // Position du centre du caractère sur l'arc
            $x = $cx + $radius * cos($angleRad);
            $y = $cy + $radius * sin($angleRad);

            // Rotation du glyphe pour qu'il soit "debout"
            // imagettftext : angle CCW en degrés
            $rot = $flip ? $angleDeg - 90.0 : $angleDeg + 90.0;

            // Boîte englobante pour centrer le char sur sa position
            $bbox = @imagettfbbox($fontSize, $rot, $fontPath, $char);
            if (!$bbox) continue;

            // Centre de la boîte (moyenne des 4 coins)
            $bCx = ($bbox[0] + $bbox[2] + $bbox[4] + $bbox[6]) / 4.0;
            $bCy = ($bbox[1] + $bbox[3] + $bbox[5] + $bbox[7]) / 4.0;

            imagettftext(
                $img,
                $fontSize,
                $rot,
                (int) round($x - $bCx),
                (int) round($y - $bCy),
                $color,
                $fontPath,
                $char
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Centre un texte horizontal à (cx, cy)
    // ─────────────────────────────────────────────────────────────────────────
    private function centerText(
        $img,
        string $text,
        float $cx, float $cy,
        float $size,
        $color,
        string $fontPath
    ): void {
        if (!file_exists($fontPath)) return;

        $bbox = @imagettfbbox($size, 0, $fontPath, $text);
        if (!$bbox) return;

        $textW = abs($bbox[2] - $bbox[0]);
        $textH = abs($bbox[7] - $bbox[1]);

        imagettftext(
            $img,
            $size,
            0,
            (int) round($cx - $textW / 2),
            (int) round($cy + $textH / 2),
            $color,
            $fontPath,
            $text
        );
    }
}
