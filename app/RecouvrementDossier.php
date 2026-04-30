<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Facture;

class RecouvrementDossier extends Model
{
    protected $table = 'recouvrement_dossiers';

    protected $fillable = [
        'iddirection_ref', 'locataire_id', 'statut',
        'montant_du', 'montant_recouvre', 'nb_mois_impayes',
        'date_dernier_paiement', 'notes_juridiques', 'date_assignation', 'delai_paiement',
    ];

    protected $casts = [
        'date_dernier_paiement' => 'date',
        'date_assignation'      => 'date',
        'montant_du'            => 'decimal:2',
        'montant_recouvre'      => 'decimal:2',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function locataire()
    {
        return $this->belongsTo(Locataire::class, 'locataire_id');
    }

    public function relances()
    {
        return $this->hasMany(RecouvrementRelance::class, 'dossier_id')->orderBy('envoye_le');
    }

    public function echeances()
    {
        return $this->hasMany(RecouvrementEcheance::class, 'dossier_id')->orderBy('date_echeance');
    }

    // ── Accesseurs ─────────────────────────────────────────────────────────────

    public function getStatutLabelAttribute(): string
    {
        return [
            'actif'       => 'En cours',
            'apurement'   => 'Plan d\'apurement',
            'contentieux' => 'Contentieux',
            'resolu'      => 'Résolu',
            'classe'      => 'Classé',
        ][$this->statut] ?? $this->statut;
    }

    public function getStatutBadgeAttribute(): string
    {
        $classes = [
            'actif'       => 'bg-danger',
            'apurement'   => 'bg-warning text-dark',
            'contentieux' => 'bg-dark',
            'resolu'      => 'bg-success',
            'classe'      => 'bg-secondary',
        ];
        $class = $classes[$this->statut] ?? 'bg-secondary';
        return '<span class="badge ' . $class . '">' . $this->statut_label . '</span>';
    }

    public function getMontantResteAttribute(): float
    {
        return max(0, (float) $this->montant_du - (float) $this->montant_recouvre);
    }

    public function getDerniereRelanceAttribute(): ?RecouvrementRelance
    {
        return $this->relances->last();
    }

    public function getProchainTypeRelanceAttribute(): string
    {
        $nbRappels = $this->relances
            ->filter(fn($r) => preg_match('/^rappel\d+$/', $r->type))
            ->count();
        return 'rappel' . ($nbRappels + 1);
    }

    /**
     * Options disponibles pour le select "Type de relance".
     * Génère dynamiquement les rappels passés + le prochain + mise_en_demeure.
     */
    public function getOptionsRelanceAttribute(): array
    {
        $options = [];

        // Tous les rappels déjà envoyés
        $types = $this->relances->pluck('type')->unique();
        foreach ($types->filter(fn($t) => preg_match('/^rappel\d+$/', $t)) as $type) {
            $options[$type] = RecouvrementRelance::labelForType($type);
        }

        // Le prochain rappel
        $prochain = $this->prochain_type_relance;
        $options[$prochain] = RecouvrementRelance::labelForType($prochain);

        // Mise en demeure toujours disponible
        $options['mise_en_demeure'] = 'Mise en demeure';

        return $options;
    }

    // ── Score de risque (0 = fiable, 100 = très risqué) ─────────────────────

    public function getScoreRisqueAttribute(): int
    {
        $locataire = $this->locataire;
        if (!$locataire || !$locataire->date_entree) return 50;

        $nbMoisActif = max(1, Carbon::parse($locataire->date_entree)->diffInMonths(now()));
        $nbMoisPaye  = Facture::where('locataire_id', $locataire->id)
            ->whereNull('delete_at')
            ->selectRaw('COUNT(DISTINCT EXTRACT(YEAR FROM date_paiement) * 100 + EXTRACT(MONTH FROM date_paiement)) as nb')
            ->value('nb') ?? 0;

        $tauxPaiement = min(1, $nbMoisPaye / $nbMoisActif);
        $score = (1 - $tauxPaiement) * 70; // 0-70 pts

        if ($this->statut === 'contentieux') $score += 20;
        elseif (in_array($this->statut, ['actif', 'apurement'])) $score += 10;

        return (int) min(100, max(0, round($score)));
    }

    public function getScoreLabelAttribute(): string
    {
        $s = $this->score_risque;
        if ($s <= 25)  return 'Faible';
        if ($s <= 50)  return 'Modéré';
        if ($s <= 75)  return 'Élevé';
        return 'Très élevé';
    }

    public function getScoreColorAttribute(): string
    {
        $s = $this->score_risque;
        if ($s <= 25)  return 'success';
        if ($s <= 50)  return 'warning';
        if ($s <= 75)  return 'danger';
        return 'dark';
    }
}
