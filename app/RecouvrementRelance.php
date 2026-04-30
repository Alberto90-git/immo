<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecouvrementRelance extends Model
{
    protected $table = 'recouvrement_relances';

    protected $fillable = [
        'dossier_id', 'iddirection_ref', 'type', 'canal',
        'statut', 'message', 'envoye_le',
    ];

    protected $casts = [
        'envoye_le' => 'datetime',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function dossier()
    {
        return $this->belongsTo(RecouvrementDossier::class, 'dossier_id');
    }

    // ── Accesseurs ─────────────────────────────────────────────────────────────

    /**
     * Génère le label lisible pour n'importe quel type (statique, utilisable sans instance).
     */
    public static function labelForType(string $type): string
    {
        if ($type === 'mise_en_demeure') return 'Mise en demeure';

        if (preg_match('/^rappel(\d+)$/', $type, $m)) {
            $n = (int) $m[1];
            $ordinal = match($n) {
                1       => '1er',
                default => $n . 'ème',
            };
            return $ordinal . ' rappel';
        }

        return $type;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::labelForType($this->type);
    }

    public function getTypeBadgeAttribute(): string
    {
        $class = $this->type === 'mise_en_demeure' ? 'bg-danger' : 'bg-dark';
        return '<span class="badge ' . $class . '">' . $this->type_label . '</span>';
    }

    public function getCanalLabelAttribute(): string
    {
        return [
            'email'     => 'Email',
            'sms'       => 'SMS',
            'whatsapp'  => 'WhatsApp',
            'courrier'  => 'Courrier',
        ][$this->canal] ?? $this->canal;
    }

    public function getCanalIconAttribute(): string
    {
        return [
            'email'    => 'bx bx-envelope',
            'sms'      => 'bx bx-message',
            'whatsapp' => 'bx bxl-whatsapp',
            'courrier' => 'bx bx-mail-send',
        ][$this->canal] ?? 'bx bx-send';
    }

    public function getStatutBadgeAttribute(): string
    {
        $map = [
            'envoye'  => ['bg-success', 'Envoyé'],
            'echec'   => ['bg-danger',  'Échec'],
            'simule'  => ['bg-info',    'Simulé'],
        ];
        [$class, $label] = $map[$this->statut] ?? ['bg-secondary', $this->statut];
        return '<span class="badge ' . $class . '">' . $label . '</span>';
    }
}
