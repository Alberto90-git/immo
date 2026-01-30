<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Direction extends Model
{
    protected $table = 'directions';
    protected $primaryKey = 'iddirection';

    protected $fillable = [
        'designation',
        'telephone',
        'email',
        'siege_social',
        'status',
        'blocage_entreprise',
        'idplan_ref',
        'abonnement_debut',
        'abonnement_fin',
        'statut_abonnement'
    ];

    protected $casts = [
        'abonnement_debut' => 'date',
        'abonnement_fin' => 'date',
    ];

    /**
     * Relation avec le plan d'abonnement
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'idplan_ref', 'idplan');
    }

    /**
     * Relation avec les annexes
     */
    public function annexes()
    {
        return $this->hasMany(Annexe::class, 'iddirection_ref', 'iddirection');
    }

    /**
     * Relation avec les maisons (via toutes les annexes)
     */
    public function maisons()
    {
        return $this->hasMany(Maison::class, 'iddirection_ref', 'iddirection');
    }

    /**
     * Relation avec les propriétaires
     */
    public function proprietaires()
    {
        return $this->hasMany(Proprietaire::class, 'iddirection_ref', 'iddirection');
    }

    /**
     * Vérifie si l'abonnement est actif
     */
    public function hasAbonnementActif()
    {
        if ($this->statut_abonnement === 'suspendu') {
            return false;
        }

        if ($this->statut_abonnement === 'essai') {
            if ($this->abonnement_fin && Carbon::parse($this->abonnement_fin)->isPast()) {
                return false; // Période d'essai expirée
            }
            return true;
        }

        if ($this->abonnement_fin && Carbon::parse($this->abonnement_fin)->isPast()) {
            return false;
        }

        return $this->statut_abonnement === 'actif';
    }

    /**
     * Vérifie si l'abonnement est expiré
     */
    public function isAbonnementExpire()
    {
        if (!$this->abonnement_fin) {
            return false;
        }

        return Carbon::parse($this->abonnement_fin)->isPast();
    }

    /**
     * Compte le nombre de maisons de la direction
     */
    public function getNombreMaisons()
    {
        return $this->maisons()->count();
    }

    /**
     * Compte le nombre d'annexes de la direction (exclut l'annexe principale)
     */
    public function getNombreAnnexes()
    {
        // On compte les annexes créées après la première (annexe principale)
        return max(0, $this->annexes()->count() - 1);
    }

    /**
     * Vérifie si la direction peut créer une nouvelle maison
     */
    public function canCreateMaison()
    {
        // Vérifier si l'abonnement est actif
        if (!$this->hasAbonnementActif()) {
            return [
                'allowed' => false,
                'message' => 'Votre abonnement n\'est pas actif. Veuillez renouveler votre abonnement.'
            ];
        }

        $plan = $this->plan;

        // Si pas de plan, on utilise les limites du plan Starter par défaut
        if (!$plan) {
            $plan = Plan::starter();
        }

        // Si pas de plan trouvé, bloquer
        if (!$plan) {
            return [
                'allowed' => false,
                'message' => 'Aucun plan d\'abonnement configuré. Veuillez contacter l\'administrateur.'
            ];
        }

        // Plan avec maisons illimitées
        if ($plan->hasMaisonsIllimitees()) {
            return [
                'allowed' => true,
                'message' => ''
            ];
        }

        // Vérifier la limite
        $nombreMaisons = $this->getNombreMaisons();
        if ($nombreMaisons >= $plan->max_maisons) {
            return [
                'allowed' => false,
                'message' => "Vous avez atteint la limite de {$plan->max_maisons} maison(s) pour le plan {$plan->nom}. Passez au plan supérieur pour gérer plus de maisons."
            ];
        }

        return [
            'allowed' => true,
            'message' => '',
            'remaining' => $plan->max_maisons - $nombreMaisons
        ];
    }

    /**
     * Vérifie si la direction peut créer une nouvelle annexe
     */
    public function canCreateAnnexe()
    {
        // Vérifier si l'abonnement est actif
        if (!$this->hasAbonnementActif()) {
            return [
                'allowed' => false,
                'message' => 'Votre abonnement n\'est pas actif. Veuillez renouveler votre abonnement.'
            ];
        }

        $plan = $this->plan;

        // Si pas de plan, on utilise les limites du plan Starter par défaut
        if (!$plan) {
            $plan = Plan::starter();
        }

        // Si pas de plan trouvé, bloquer
        if (!$plan) {
            return [
                'allowed' => false,
                'message' => 'Aucun plan d\'abonnement configuré. Veuillez contacter l\'administrateur.'
            ];
        }

        // Plan sans annexes autorisées
        if (!$plan->canCreateAnnexes()) {
            return [
                'allowed' => false,
                'message' => "Le plan {$plan->nom} ne permet pas de créer des annexes. Passez au plan Premium pour créer des annexes."
            ];
        }

        // Vérifier la limite d'annexes
        $nombreAnnexes = $this->getNombreAnnexes();
        if ($nombreAnnexes >= $plan->max_annexes) {
            return [
                'allowed' => false,
                'message' => "Vous avez atteint la limite de {$plan->max_annexes} annexe(s) pour le plan {$plan->nom}."
            ];
        }

        return [
            'allowed' => true,
            'message' => '',
            'remaining' => $plan->max_annexes - $nombreAnnexes
        ];
    }

    /**
     * Récupère les informations du plan actuel
     */
    public function getPlanInfo()
    {
        $plan = $this->plan ?? Plan::starter();

        if (!$plan) {
            return null;
        }

        return [
            'nom' => $plan->nom,
            'code' => $plan->code,
            'max_maisons' => $plan->max_maisons,
            'max_annexes' => $plan->max_annexes,
            'maisons_utilisees' => $this->getNombreMaisons(),
            'annexes_utilisees' => $this->getNombreAnnexes(),
            'maisons_restantes' => $plan->hasMaisonsIllimitees() ? 'Illimité' : max(0, $plan->max_maisons - $this->getNombreMaisons()),
            'annexes_restantes' => max(0, $plan->max_annexes - $this->getNombreAnnexes()),
            'abonnement_fin' => $this->abonnement_fin,
            'statut' => $this->statut_abonnement
        ];
    }

    /**
     * Active un plan pour cette direction
     */
    public function activerPlan($planId, $dureeEnMois = 12)
    {
        $plan = Plan::find($planId);

        if (!$plan) {
            return false;
        }

        $this->idplan_ref = $plan->idplan;
        $this->abonnement_debut = Carbon::now();
        $this->abonnement_fin = Carbon::now()->addMonths($dureeEnMois);
        $this->statut_abonnement = 'actif';
        $this->save();

        return true;
    }
}
