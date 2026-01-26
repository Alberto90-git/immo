<?php

namespace App\Http\Controllers;

use App\Plan;
use App\Direction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Carbon\Carbon;

class PlanController extends Controller
{
    /**
     * Affiche la page de gestion des abonnements
     */
    public function index()
    {
        $plans = Plan::actifs();
        $direction = Direction::find(Auth::user()->iddirection_ref);
        $currentPlan = $direction ? $direction->plan : null;
        $planInfo = $direction ? $direction->getPlanInfo() : null;

        return view('plans.index', compact('plans', 'currentPlan', 'planInfo', 'direction'));
    }

    /**
     * Récupère tous les plans disponibles (API)
     */
    public function getPlans()
    {
        try {
            $plans = Plan::actifs();

            return response()->json([
                'status' => true,
                'plans' => $plans
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération des plans.'
            ]);
        }
    }

    /**
     * Récupère les informations du plan actuel de l'utilisateur (API)
     */
    public function getCurrentPlan()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous devez être connecté.'
                ], 401);
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

            if (!$direction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Direction non trouvée.'
                ], 404);
            }

            $planInfo = $direction->getPlanInfo();

            return response()->json([
                'status' => true,
                'plan_info' => $planInfo,
                'can_create_maison' => $direction->canCreateMaison(),
                'can_create_annexe' => $direction->canCreateAnnexe()
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération des informations.'
            ], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur peut créer une maison (API)
     */
    public function checkMaisonLimit()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous devez être connecté.'
                ], 401);
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

            if (!$direction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Direction non trouvée.'
                ], 404);
            }

            $canCreate = $direction->canCreateMaison();

            return response()->json([
                'status' => true,
                'can_create' => $canCreate['allowed'],
                'message' => $canCreate['message'],
                'remaining' => $canCreate['remaining'] ?? null
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la vérification.'
            ], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur peut créer une annexe (API)
     */
    public function checkAnnexeLimit()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous devez être connecté.'
                ], 401);
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

            if (!$direction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Direction non trouvée.'
                ], 404);
            }

            $canCreate = $direction->canCreateAnnexe();

            return response()->json([
                'status' => true,
                'can_create' => $canCreate['allowed'],
                'message' => $canCreate['message'],
                'remaining' => $canCreate['remaining'] ?? null
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la vérification.'
            ], 500);
        }
    }

    /**
     * Change le plan de l'utilisateur (nécessite traitement de paiement)
     */
    public function changePlan(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous devez être connecté.'
                ], 401);
            }

            $request->validate([
                'plan_id' => 'required|exists:plans,idplan'
            ]);

            $direction = Direction::find(Auth::user()->iddirection_ref);

            if (!$direction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Direction non trouvée.'
                ], 404);
            }

            $newPlan = Plan::find($request->plan_id);

            // Vérifier si le nouveau plan est plus restrictif
            $currentMaisons = $direction->getNombreMaisons();
            $currentAnnexes = $direction->getNombreAnnexes();

            if ($newPlan->max_maisons > 0 && $currentMaisons > $newPlan->max_maisons) {
                return response()->json([
                    'status' => false,
                    'message' => "Vous avez actuellement {$currentMaisons} maison(s). Le plan {$newPlan->nom} n'autorise que {$newPlan->max_maisons} maison(s). Veuillez d'abord réduire le nombre de maisons."
                ], 400);
            }

            if ($currentAnnexes > $newPlan->max_annexes) {
                return response()->json([
                    'status' => false,
                    'message' => "Vous avez actuellement {$currentAnnexes} annexe(s). Le plan {$newPlan->nom} n'autorise que {$newPlan->max_annexes} annexe(s). Veuillez d'abord réduire le nombre d'annexes."
                ], 400);
            }

            // Activer le nouveau plan (ici on suppose que le paiement a déjà été traité)
            $direction->activerPlan($request->plan_id);

            activity()->performedOn($direction)
                ->causedBy(Auth::user()->id)
                ->log('Changement de plan vers ' . $newPlan->nom . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => "Votre abonnement a été mis à jour vers le plan {$newPlan->nom}.",
                'plan_info' => $direction->getPlanInfo()
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors du changement de plan.'
            ], 500);
        }
    }

    /**
     * Récupère les statistiques d'utilisation du plan
     */
    public function getUsageStats()
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous devez être connecté.'
                ], 401);
            }

            $direction = Direction::find(Auth::user()->iddirection_ref);

            if (!$direction) {
                return response()->json([
                    'status' => false,
                    'message' => 'Direction non trouvée.'
                ], 404);
            }

            $plan = $direction->plan ?? Plan::starter();
            $planInfo = $direction->getPlanInfo();

            // Calcul des pourcentages d'utilisation
            $maisonsPercent = 0;
            $annexesPercent = 0;

            if ($plan && $plan->max_maisons > 0) {
                $maisonsPercent = round(($planInfo['maisons_utilisees'] / $plan->max_maisons) * 100);
            }

            if ($plan && $plan->max_annexes > 0) {
                $annexesPercent = round(($planInfo['annexes_utilisees'] / $plan->max_annexes) * 100);
            }

            return response()->json([
                'status' => true,
                'stats' => [
                    'plan_nom' => $planInfo['nom'],
                    'maisons' => [
                        'utilisees' => $planInfo['maisons_utilisees'],
                        'max' => $plan->max_maisons == 0 ? 'Illimité' : $plan->max_maisons,
                        'restantes' => $planInfo['maisons_restantes'],
                        'pourcentage' => $plan->max_maisons == 0 ? 0 : $maisonsPercent
                    ],
                    'annexes' => [
                        'utilisees' => $planInfo['annexes_utilisees'],
                        'max' => $plan->max_annexes,
                        'restantes' => $planInfo['annexes_restantes'],
                        'pourcentage' => $plan->max_annexes == 0 ? 0 : $annexesPercent
                    ],
                    'abonnement' => [
                        'statut' => $planInfo['statut'],
                        'date_fin' => $planInfo['abonnement_fin'] ? Carbon::parse($planInfo['abonnement_fin'])->format('d/m/Y') : null,
                        'jours_restants' => $planInfo['abonnement_fin'] ? max(0, Carbon::now()->diffInDays(Carbon::parse($planInfo['abonnement_fin']), false)) : null
                    ]
                ]
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération des statistiques.'
            ], 500);
        }
    }
}
