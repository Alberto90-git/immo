<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\AutomationRule;
use App\AutomationLog;
use App\Locataire;
use App\Facture;
use App\Chambre;
use App\Maison;
use App\Proprietaire;
use App\Parametre;
use App\Services\AfricasTalkingService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessAutomationRules extends Command
{
    protected $signature   = 'automation:process {--force : Ignorer la vérification d\'heure (test)}';
    protected $description = 'Traite toutes les règles d\'automatisation actives (rappels loyer, impayés, préavis, rapports, renouvellements)';

    protected AfricasTalkingService $at;
    protected WhatsAppService $wa;

    public function __construct()
    {
        parent::__construct();
        $this->at = new AfricasTalkingService();
        $this->wa = new WhatsAppService();
    }

    public function handle(): int
    {
        $force = $this->option('force');
        $rules = AutomationRule::where('actif', true)->get();

        foreach ($rules as $rule) {
            // Récupérer les paramètres de la direction
            $parametre = Parametre::where('iddirection_ref', $rule->iddirection_ref)->first();
            $timezone  = $parametre?->timezone ?? config('app.timezone', 'Africa/Porto-Novo');
            $now       = Carbon::now($timezone);

            // Appliquer la locale de la direction pour tous les __() appelés dans ce cycle
            App::setLocale(get_direction_locale($rule->iddirection_ref));
            $heure     = $now->format('H:i');

            // Vérifier l'heure dans le timezone local de la direction (sauf --force)
            if (!$force && $rule->heure_envoi !== $heure) {
                continue;
            }

            try {
                match ($rule->type) {
                    'rappel_loyer'    => $this->traiterRappelLoyer($rule, $now),
                    'escalade_impaye' => $this->traiterEscaladeImpaye($rule, $now),
                    'preavis_bail'    => $this->traiterPreavisBail($rule, $now),
                    'rapport_mensuel' => $this->traiterRapportMensuel($rule, $now),
                    'renouvellement'  => $this->traiterRenouvellement($rule, $now),
                    default           => null,
                };
            } catch (\Exception $e) {
                Log::error("AutomationRule #{$rule->id} ({$rule->type}) [{$timezone}] error: " . $e->getMessage());
                $this->error("ERREUR règle #{$rule->id} ({$rule->type}) : " . $e->getMessage());
            }
        }

        $this->info('Automation rules processed at ' . now()->format('H:i') . ' UTC');
        return 0;
    }

    // ── Rappel de loyer ──────────────────────────────────────────────────────────

    private function traiterRappelLoyer(AutomationRule $rule, Carbon $now): void
    {
        $jours = $rule->jours_declenchement ?? [-5, 0, 3];
        $jourDuMois = $now->day;
        $force = $this->option('force');

        $this->info("[rappel_loyer] Aujourd'hui = jour {$jourDuMois} | Jours configurés : " . implode(', ', $jours));

        $locataires = Locataire::where('iddirection_ref', $rule->iddirection_ref)
            ->where('status', true)->whereNull('delete_at')
            ->where('automation_opt_out', false)
            ->get();

        $this->info("[rappel_loyer] {$locataires->count()} locataire(s) actif(s) trouvé(s)");

        foreach ($locataires as $locataire) {
            // Déterminer le jour d'échéance : date_entree donne le jour du mois du loyer
            $jourEcheance = Carbon::parse($locataire->date_entree)->day;

            $this->info("  → {$locataire->prenom} {$locataire->nom} | date_entree={$locataire->date_entree} | jour échéance={$jourEcheance} | email=" . ($locataire->email ?? 'VIDE'));

            foreach ($jours as $offset) {
                $jourCible = $jourEcheance + $offset;
                if ($jourCible < 1)  $jourCible += $now->daysInMonth;
                if ($jourCible > $now->daysInMonth) $jourCible -= $now->daysInMonth;

                $this->info("     offset={$offset} → jourCible={$jourCible} | match=" . ($jourCible === $jourDuMois || $force ? 'OUI' : 'NON'));

                if ($jourCible === $jourDuMois || $force) {
                    // Vérifier si déjà envoyé aujourd'hui pour ce locataire
                    $dejaEnvoye = AutomationLog::where('locataire_id', $locataire->id)
                        ->where('type', 'rappel_loyer')
                        ->whereDate('envoye_le', $now->toDateString())
                        ->where('statut', 'succes')
                        ->exists();

                    if ($dejaEnvoye) { $this->warn("     → Déjà envoyé aujourd'hui, skip."); continue; }

                    // Vérifier si le loyer du mois courant est déjà payé
                    $moisLabel   = $now->locale('fr')->monthName . ' ' . $now->year;
                    $dejaPaye = Facture::where('locataire_id', $locataire->id)
                        ->where('type_paiement', 'direct')
                        ->whereYear('date_paiement', $now->year)
                        ->whereMonth('date_paiement', $now->month)
                        ->exists();

                    if ($dejaPaye && $offset >= 0) { $this->warn("     → Loyer déjà payé ce mois, skip."); continue; }

                    $this->info("     → ENVOI en cours...");
                    $msg = $this->messageRappelLoyer($locataire, $moisLabel, $offset);
                    $this->envoyerCanaux($rule, $locataire, null, 'rappel_loyer', $msg, "Loyer {$moisLabel}");
                    break; // Un seul envoi par locataire par run
                }
            }
        }
    }

    // ── Escalade impayé ──────────────────────────────────────────────────────────

    private function traiterEscaladeImpaye(AutomationRule $rule, Carbon $now): void
    {
        $delaiSms = $rule->delai_escalade_sms ?? 3;
        $delaiWa  = $rule->delai_escalade_whatsapp ?? 5;

        $locataires = Locataire::where('iddirection_ref', $rule->iddirection_ref)
            ->where('status', true)->whereNull('delete_at')
            ->where('automation_opt_out', false)
            ->get();

        foreach ($locataires as $locataire) {
            $jourEcheance = Carbon::parse($locataire->date_entree)->day;
            $dateEcheance = $now->copy()->startOfMonth()->addDays($jourEcheance - 1);
            if ($now->lt($dateEcheance)) continue; // Pas encore échu

            $joursRetard = $now->diffInDays($dateEcheance);

            // Vérifier si loyer payé ce mois
            $moisLabel = $now->locale('fr')->monthName . ' ' . $now->year;
            $dejaPaye = Facture::where('locataire_id', $locataire->id)
                ->where('type_paiement', 'direct')
                ->whereYear('date_paiement', $now->year)
                ->whereMonth('date_paiement', $now->month)
                ->exists();

            if ($dejaPaye) continue;

            // Déterminer le canal selon le retard
            $canaux = [];
            if ($joursRetard == 1) {
                $canaux = ['email'];
            } elseif ($joursRetard == $delaiSms) {
                $canaux = ['sms'];
            } elseif ($joursRetard == $delaiWa) {
                $canaux = ['whatsapp'];
            }

            if (empty($canaux)) continue;

            foreach ($canaux as $canal) {
                $dejaEnvoye = AutomationLog::where('locataire_id', $locataire->id)
                    ->where('type', 'escalade_impaye')
                    ->where('canal', $canal)
                    ->whereDate('envoye_le', $now->toDateString())
                    ->where('statut', 'succes')
                    ->exists();

                if ($dejaEnvoye) continue;

                $msg = "⚠️ Impayé - Loyer {$moisLabel}\n\nBonjour {$locataire->prenom} {$locataire->nom},\n\nVotre loyer de " . format_price($locataire->prix_mois, $locataire->iddirection_ref ?? null) . " est en retard de {$joursRetard} jour(s).\nMerci de régulariser au plus vite.";
                $this->envoyerSingleCanal($rule, $locataire, null, 'escalade_impaye', $canal, $msg, "Impayé {$moisLabel}");
            }
        }
    }

    // ── Préavis de fin de bail ───────────────────────────────────────────────────

    private function traiterPreavisBail(AutomationRule $rule, Carbon $now): void
    {
        $moisAvant = $rule->mois_avant_echeance ?? 2;

        $locataires = Locataire::where('iddirection_ref', $rule->iddirection_ref)
            ->where('status', true)->whereNull('delete_at')
            ->where('automation_opt_out', false)
            ->get();

        foreach ($locataires as $locataire) {
            // Calculer date de fin estimée (date_entree + 12 mois par défaut)
            $dateEntree  = Carbon::parse($locataire->date_entree);
            $anneesBail  = (int) floor($now->diffInYears($dateEntree));
            $dateFin     = $dateEntree->copy()->addYears($anneesBail + 1);

            $joursAvantFin = $now->diffInDays($dateFin, false);
            $moisAvantFin  = round($joursAvantFin / 30);

            if ($moisAvantFin != $moisAvant) continue;

            $dejaEnvoye = AutomationLog::where('locataire_id', $locataire->id)
                ->where('type', 'preavis_bail')
                ->whereMonth('envoye_le', $now->month)
                ->whereYear('envoye_le', $now->year)
                ->where('statut', 'succes')
                ->exists();

            if ($dejaEnvoye) continue;

            $dateFmt = $dateFin->format('d/m/Y');
            $msg = "📋 Préavis de fin de bail\n\nBonjour {$locataire->prenom} {$locataire->nom},\n\nVotre bail arrive à échéance le {$dateFmt} (dans {$moisAvant} mois).\nMerci de nous contacter pour discuter du renouvellement ou des modalités de départ.";
            $this->envoyerCanaux($rule, $locataire, null, 'preavis_bail', $msg, "Préavis bail - {$dateFmt}");
        }
    }

    // ── Rapport mensuel propriétaire ─────────────────────────────────────────────

    private function traiterRapportMensuel(AutomationRule $rule, Carbon $now): void
    {
        if ($now->day !== ($rule->jour_du_mois ?? 1)) return;

        $moisPrecedent  = $now->copy()->subMonth();
        $moisLabel      = $moisPrecedent->locale('fr')->monthName . ' ' . $moisPrecedent->year;
        $moisCourant    = $moisPrecedent->format('Y-m');

        $proprietaires = Proprietaire::where('iddirection_ref', $rule->iddirection_ref)
            ->whereNotNull('email')
            ->get();

        foreach ($proprietaires as $proprio) {
            $dejaEnvoye = AutomationLog::where('proprietaire_id', $proprio->id)
                ->where('type', 'rapport_mensuel')
                ->whereMonth('envoye_le', $now->month)
                ->whereYear('envoye_le', $now->year)
                ->where('statut', 'succes')
                ->exists();

            if ($dejaEnvoye) continue;

            // Récupérer les maisons du propriétaire
            $maisons = Maison::where('proprio_id', $proprio->id)
                ->where('iddirection_ref', $rule->iddirection_ref)
                ->get();

            $totalLoyers = 0;
            $totalChambres = 0;
            $chambresOccupees = 0;

            foreach ($maisons as $maison) {
                $chambres = Chambre::where('maison_id', $maison->id)->get();
                $totalChambres += $chambres->count();
                foreach ($chambres as $chambre) {
                    if ($chambre->status === 'occuper') {
                        $chambresOccupees++;
                        $totalLoyers += Facture::where('maison_id', $maison->id)
                            ->where('chambre_id', $chambre->id)
                            ->where('type_paiement', 'direct')
                            ->whereYear('date_paiement', $moisPrecedent->year)
                            ->whereMonth('date_paiement', $moisPrecedent->month)
                            ->sum('montant');
                    }
                }
            }

            $nomProprio = "{$proprio->prenom} {$proprio->nom}";
            $msg = "📊 Rapport mensuel – {$moisLabel}\n\nBonjour {$nomProprio},\n\nVoici le résumé de votre portefeuille pour {$moisLabel} :\n• Biens : {$maisons->count()} maison(s)\n• Chambres : {$chambresOccupees}/{$totalChambres} occupées\n• Loyers encaissés : " . format_price($totalLoyers, $rule->iddirection_ref) . "\n\nPour le détail, connectez-vous à votre espace Lokativ.";

            $this->envoyerEmail($proprio->email, $nomProprio, 'rapport_mensuel', $msg, "Rapport {$moisLabel}", $rule, null, $proprio->id);
        }
    }

    // ── Renouvellement de contrat ────────────────────────────────────────────────

    private function traiterRenouvellement(AutomationRule $rule, Carbon $now): void
    {
        $moisAvant = $rule->mois_avant_echeance ?? 1;

        $locataires = Locataire::where('iddirection_ref', $rule->iddirection_ref)
            ->where('status', true)->whereNull('delete_at')
            ->where('automation_opt_out', false)
            ->get();

        foreach ($locataires as $locataire) {
            $dateEntree = Carbon::parse($locataire->date_entree);
            $anneesBail = (int) floor($now->diffInYears($dateEntree));
            $dateFin    = $dateEntree->copy()->addYears($anneesBail + 1);

            $moisAvantFin = round($now->diffInDays($dateFin, false) / 30);
            if ($moisAvantFin != $moisAvant) continue;

            $dejaEnvoye = AutomationLog::where('locataire_id', $locataire->id)
                ->where('type', 'renouvellement')
                ->whereMonth('envoye_le', $now->month)
                ->whereYear('envoye_le', $now->year)
                ->where('statut', 'succes')
                ->exists();

            if ($dejaEnvoye) continue;

            $dateFmt = $dateFin->format('d/m/Y');
            $msg = "🔄 Renouvellement de contrat\n\nBonjour {$locataire->prenom} {$locataire->nom},\n\nVotre contrat de location arrive à terme le {$dateFmt}.\nVotre agence vous contactera prochainement pour discuter du renouvellement de votre bail.";
            $this->envoyerCanaux($rule, $locataire, null, 'renouvellement', $msg, "Renouvellement bail - {$dateFmt}");

            // Notifier aussi le propriétaire (email uniquement)
            $chambre = Chambre::find($locataire->chambre_id);
            if ($chambre) {
                $maison = Maison::find($locataire->maison_id);
                $proprio = $maison ? Proprietaire::find($maison->proprio_id) : null;
                if ($proprio && $proprio->email) {
                    $msgProprio = "🔄 Renouvellement contrat locataire\n\nBonjour {$proprio->prenom} {$proprio->nom},\n\nLe contrat de {$locataire->prenom} {$locataire->nom} (Chambre {$chambre->numero_chambre}) arrive à terme le {$dateFmt}.\nVotre agence se chargera du renouvellement.";
                    $this->envoyerEmail($proprio->email, "{$proprio->prenom} {$proprio->nom}", 'renouvellement', $msgProprio, "Renouvellement locataire {$dateFmt}", $rule, null, $proprio->id);
                }
            }
        }
    }

    // ── Helpers d'envoi ──────────────────────────────────────────────────────────

    private function envoyerCanaux(AutomationRule $rule, ?Locataire $locataire, $props, string $type, string $message, string $reference): void
    {
        foreach ($rule->getCanaux() as $canal) {
            $this->envoyerSingleCanal($rule, $locataire, $props, $type, $canal, $message, $reference);
        }
    }

    private function envoyerSingleCanal(AutomationRule $rule, ?Locataire $locataire, $props, string $type, string $canal, string $message, string $reference): void
    {
        $statut      = 'succes';
        $erreur      = null;
        $destinataire = null;

        try {
            if ($canal === 'email') {
                $email = $locataire?->email ?? null;
                if (!$email) { $statut = 'ignore'; $erreur = 'Pas d\'email'; }
                else {
                    $nom  = "{$locataire->prenom} {$locataire->nom}";
                    $this->envoyerEmailSimple($email, $nom, $message, $type);
                    $destinataire = $email;
                }
            } elseif ($canal === 'sms') {
                $tel = $locataire?->telephone ?? null;
                if (!$tel) { $statut = 'ignore'; $erreur = 'Pas de téléphone'; }
                else {
                    $result = $this->at->envoyerSMS($tel, strip_tags($message));
                    if (!$result['success']) { $statut = 'echec'; $erreur = $result['message']; }
                    $destinataire = $tel;
                }
            } elseif ($canal === 'whatsapp') {
                $tel = $locataire?->telephone ?? null;
                if (!$tel) { $statut = 'ignore'; $erreur = 'Pas de téléphone'; }
                else {
                    $result = $this->wa->envoyerTexte($tel, strip_tags($message));
                    if (!$result['success']) { $statut = 'echec'; $erreur = $result['message']; }
                    $destinataire = $tel;
                }
            }
        } catch (\Exception $e) {
            $statut = 'echec';
            $erreur = $e->getMessage();
        }

        AutomationLog::create([
            'automation_rule_id' => $rule->id,
            'iddirection_ref'    => $rule->iddirection_ref,
            'locataire_id'       => $locataire?->id,
            'proprietaire_id'    => null,
            'type'               => $type,
            'canal'              => $canal,
            'statut'             => $statut,
            'message'            => $erreur,
            'destinataire'       => $destinataire,
            'reference'          => $reference,
            'envoye_le'          => now(),
        ]);

        if ($statut === 'succes') {
            $this->info("     ✓ [{$canal}] Envoyé à {$destinataire}");
        } elseif ($statut === 'ignore') {
            $this->warn("     ⚠ [{$canal}] Ignoré : {$erreur}");
        } else {
            $this->error("     ✗ [{$canal}] Échec : {$erreur}");
        }
    }

    private function envoyerEmail(string $email, string $nom, string $type, string $message, string $reference, AutomationRule $rule, ?int $locataireId, ?int $proprietaireId = null): void
    {
        $statut = 'succes';
        $erreur = null;

        try {
            $this->envoyerEmailSimple($email, $nom, $message, $type);
        } catch (\Exception $e) {
            $statut = 'echec';
            $erreur = $e->getMessage();
        }

        AutomationLog::create([
            'automation_rule_id' => $rule->id,
            'iddirection_ref'    => $rule->iddirection_ref,
            'locataire_id'       => $locataireId,
            'proprietaire_id'    => $proprietaireId,
            'type'               => $type,
            'canal'              => 'email',
            'statut'             => $statut,
            'message'            => $erreur,
            'destinataire'       => $email,
            'reference'          => $reference,
            'envoye_le'          => now(),
        ]);
    }

    private function envoyerEmailSimple(string $email, string $nom, string $message, string $type = 'rappel_loyer'): void
    {
        $viewData = $this->buildEmailViewData($type, $nom, $message);
        $html     = view('mail.automation_notification', $viewData)->render();
        $sujet    = $viewData['sujet'];

        Mail::send([], [], function ($mail) use ($email, $nom, $sujet, $html) {
            $mail->to($email, $nom)
                 ->subject($sujet)
                 ->setBody($html, 'text/html');
        });
    }

    private function buildEmailViewData(string $type, string $nom, string $message): array
    {
        // Extraire les paragraphes du message texte (sauter la 1ère ligne = titre)
        $lignes = array_filter(array_map('trim', explode("\n", strip_tags($message))));
        $lignes = array_values($lignes);

        // La 1ère ligne contient souvent "📅 Rappel loyer – Avril 2026", on la retire du body
        $titre       = !empty($lignes[0]) ? $lignes[0] : '';
        $paragraphes = array_slice($lignes, 1);
        // Supprimer la ligne "Bonjour X," car elle est dans le template
        $paragraphes = array_filter($paragraphes, fn($l) => !str_starts_with($l, 'Bonjour '));
        $paragraphes = array_values($paragraphes);

        // Détecter la valeur à mettre en avant (montant, date, etc.)
        $highlightValue = null;
        $highlightLabel = '';
        $highlightSub   = '';
        $highlightIcon  = '';
        foreach ($paragraphes as $para) {
            // Détecte un montant suivi d'un code devise (XOF, FCFA, NGN, €, $, etc.)
            if (preg_match('/(\d[\d\s]+)\s*(?:F\s*CFA|XOF|XAF|GHS|NGN|EUR|USD|FCFA|€|\$|£|₦|₵)/u', $para, $m)) {
                $highlightValue = trim($m[0]);
                $highlightLabel = __('mail.automation.rappel_loyer_hl_label');
                $highlightIcon  = '💰';
                if (preg_match('/pour\s+([A-Za-zéèêàùûîôâ]+\s+\d{4})/u', $para, $mm)) {
                    $highlightSub = $mm[1];
                }
                break;
            }
            if (preg_match('/(\d{2}\/\d{2}\/\d{4})/', $para, $m)) {
                $highlightValue = $m[1];
                $highlightLabel = __('mail.automation.rappel_loyer_hl_date');
                $highlightIcon  = '📅';
                break;
            }
        }

        // Thème visuel selon le type — textes traduits via __()
        $moisAnnee = now()->locale(app()->getLocale())->monthName . ' ' . now()->year;
        $themes = [
            'rappel_loyer' => [
                'sujet'          => __('mail.automation.rappel_loyer_sujet', ['mois' => now()->locale(app()->getLocale())->monthName, 'annee' => now()->year]),
                'badgeIcon'      => '⏰',
                'badgeLabel'     => __('mail.automation.rappel_loyer_badge'),
                'badgeColor'     => '#fde68a',
                'headerFrom'     => '#1a56db',
                'headerTo'       => '#0e3a9c',
                'cardBg'         => '#fffbeb',
                'cardBorder'     => '#f59e0b',
                'cardLabelColor' => '#92400e',
                'cardValueColor' => '#d97706',
                'cardSubColor'   => '#78350f',
                'alertBg'        => '#fffbeb',
                'alertBorder'    => '#f59e0b',
                'alertIcon'      => '⚠️',
                'alertText'      => __('mail.automation.rappel_loyer_alert'),
                'alertTextColor' => '#92400e',
            ],
            'escalade_impaye' => [
                'sujet'          => __('mail.automation.escalade_sujet'),
                'badgeIcon'      => '🚨',
                'badgeLabel'     => __('mail.automation.escalade_badge'),
                'badgeColor'     => '#fecaca',
                'headerFrom'     => '#dc2626',
                'headerTo'       => '#991b1b',
                'cardBg'         => '#fef2f2',
                'cardBorder'     => '#ef4444',
                'cardLabelColor' => '#991b1b',
                'cardValueColor' => '#dc2626',
                'cardSubColor'   => '#7f1d1d',
                'alertBg'        => '#fef2f2',
                'alertBorder'    => '#ef4444',
                'alertIcon'      => '🚨',
                'alertText'      => __('mail.automation.escalade_alert'),
                'alertTextColor' => '#991b1b',
            ],
            'preavis_bail' => [
                'sujet'          => __('mail.automation.preavis_sujet'),
                'badgeIcon'      => '📋',
                'badgeLabel'     => __('mail.automation.preavis_badge'),
                'badgeColor'     => '#fecaca',
                'headerFrom'     => '#7c3aed',
                'headerTo'       => '#4c1d95',
                'cardBg'         => '#f5f3ff',
                'cardBorder'     => '#7c3aed',
                'cardLabelColor' => '#4c1d95',
                'cardValueColor' => '#7c3aed',
                'cardSubColor'   => '#5b21b6',
                'alertBg'        => '#fef2f2',
                'alertBorder'    => '#ef4444',
                'alertIcon'      => '⚠️',
                'alertText'      => __('mail.automation.preavis_alert'),
                'alertTextColor' => '#991b1b',
            ],
            'rapport_mensuel' => [
                'sujet'          => __('mail.automation.rapport_sujet', ['mois' => now()->locale(app()->getLocale())->subMonth()->monthName, 'annee' => now()->subMonth()->year]),
                'badgeIcon'      => '📊',
                'badgeLabel'     => __('mail.automation.rapport_badge'),
                'badgeColor'     => '#a5f3fc',
                'headerFrom'     => '#0891b2',
                'headerTo'       => '#0e7490',
                'cardBg'         => '#ecfeff',
                'cardBorder'     => '#06b6d4',
                'cardLabelColor' => '#164e63',
                'cardValueColor' => '#0891b2',
                'cardSubColor'   => '#155e75',
                'alertBg'        => '#f0f9ff',
                'alertBorder'    => '#38bdf8',
                'alertIcon'      => '📈',
                'alertText'      => __('mail.automation.rapport_alert'),
                'alertTextColor' => '#0c4a6e',
            ],
            'renouvellement' => [
                'sujet'          => __('mail.automation.renouvellement_sujet'),
                'badgeIcon'      => '🔄',
                'badgeLabel'     => __('mail.automation.renouvellement_badge'),
                'badgeColor'     => '#bbf7d0',
                'headerFrom'     => '#16a34a',
                'headerTo'       => '#14532d',
                'cardBg'         => '#f0fdf4',
                'cardBorder'     => '#22c55e',
                'cardLabelColor' => '#14532d',
                'cardValueColor' => '#16a34a',
                'cardSubColor'   => '#166534',
                'alertBg'        => '#f0fdf4',
                'alertBorder'    => '#22c55e',
                'alertIcon'      => '📝',
                'alertText'      => __('mail.automation.renouvellement_alert'),
                'alertTextColor' => '#14532d',
            ],
        ];

        $theme = $themes[$type] ?? $themes['rappel_loyer'];

        return array_merge($theme, [
            'nom'            => $nom,
            'sujet'          => $theme['sujet'],
            'paragraphes'    => $paragraphes,
            'highlightValue' => $highlightValue,
            'highlightLabel' => $highlightLabel,
            'highlightSub'   => $highlightSub,
            'highlightIcon'  => $highlightIcon,
        ]);
    }

    private function messageRappelLoyer(Locataire $locataire, string $moisLabel, int $offset): string
    {
        $montant = format_price($locataire->prix_mois, $locataire->iddirection_ref ?? null);
        if ($offset < 0) {
            $nbJours = abs($offset);
            return "📅 Rappel loyer – {$moisLabel}\n\nBonjour {$locataire->prenom} {$locataire->nom},\n\nVotre loyer de {$montant} pour {$moisLabel} est dû dans {$nbJours} jour(s).\nMerci de prévoir le règlement dans les délais.";
        } elseif ($offset === 0) {
            return "📅 Rappel loyer – {$moisLabel}\n\nBonjour {$locataire->prenom} {$locataire->nom},\n\nVotre loyer de {$montant} pour {$moisLabel} est dû aujourd'hui.\nMerci de procéder au règlement.";
        } else {
            return "⚠️ Rappel loyer – {$moisLabel}\n\nBonjour {$locataire->prenom} {$locataire->nom},\n\nVotre loyer de {$montant} pour {$moisLabel} est en retard de {$offset} jour(s).\nMerci de régulariser au plus vite.";
        }
    }
}
