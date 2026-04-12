<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    protected $table = 'prestataires';

    protected $fillable = [
        'iddirection_ref', 'idannexe_ref', 'nom', 'specialite',
        'telephone', 'email', 'adresse', 'actif', 'delete_at',
    ];

    const SPECIALITES = [
        'plomberie'    => ['label' => 'Plomberie',     'icon' => 'bx bx-water',        'color' => 'bg-label-info'],
        'electricite'  => ['label' => 'Électricité',   'icon' => 'bx bx-bolt-circle',  'color' => 'bg-label-warning'],
        'serrurerie'   => ['label' => 'Serrurerie',    'icon' => 'bx bx-key',          'color' => 'bg-label-secondary'],
        'menuiserie'   => ['label' => 'Menuiserie',    'icon' => 'bx bx-cube',         'color' => 'bg-label-primary'],
        'climatisation'=> ['label' => 'Climatisation', 'icon' => 'bx bx-wind',         'color' => 'bg-label-info'],
        'maconnerie'   => ['label' => 'Maçonnerie',    'icon' => 'bx bx-buildings',    'color' => 'bg-label-dark'],
        'peinture'     => ['label' => 'Peinture',      'icon' => 'bx bx-paint',        'color' => 'bg-label-success'],
        'autre'        => ['label' => 'Autre',         'icon' => 'bx bx-wrench',       'color' => 'bg-label-secondary'],
    ];

    public function tickets()
    {
        return $this->hasMany(TicketMaintenance::class, 'prestataire_id');
    }

    public function getSpecialiteLabelAttribute()
    {
        return self::SPECIALITES[$this->specialite]['label'] ?? ucfirst($this->specialite);
    }

    public function getSpecialiteBadgeAttribute()
    {
        $info = self::SPECIALITES[$this->specialite] ?? ['label' => $this->specialite, 'color' => 'bg-label-secondary'];
        return '<span class="badge ' . $info['color'] . '">' . $info['label'] . '</span>';
    }

    public function getSpecialiteIconAttribute()
    {
        return self::SPECIALITES[$this->specialite]['icon'] ?? 'bx bx-wrench';
    }
}
