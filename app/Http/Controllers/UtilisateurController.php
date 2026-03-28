<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use App\User;
use App\Direction;
use App\Annexe;
use App\Plan;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use App\Mail\SendCodemail;
use App\Mail\VerificationEmail;
use App\Rules\MatchOldPassword;
use App\Jobs\SendSubscriptionInvoiceJob;
use App\Jobs\SendLoginCodeJob;
use App\PlatformConfig;
use App\Services\KkiapayService;
use App\Services\FedapayService;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;





class UtilisateurController extends SessionController
{
    protected $maxAttempts = 3; // Default is 5
    protected $decayMinutes = 2; // Default is 1


    public function loginNouveau()
    {
        if (Auth::user()) {
            Session::flush();
            Auth::logout();
        }
        return view('auth/login');
    }


    public function signOut()
    {
        Session::flush();
        Auth::logout();

        return Redirect()->route('login');
    }


    public function forgotPassword()
    {
        Auth::logout();
        return view('auth/reset_password');
    }

    // REINITILISER PASSWORD DEBUT
    public function resetPassword(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (!$user) {
                return back()->with('error', 'Erreur! votre email est incorrecte ou le système ne le reconnais pas.');
            }
    
            $token = Str::random(60);
            $update = User::where('email', $request->email)
                       ->update(['token' => $token, 'is_verified' => 0]);

            try {
                if ($update) {
                    Mail::to($request->email)->send(new ResetPassword($user->email, $token));
                 }
     
                 if (Mail::failures() != 0) {
                     return back()->with('message', 'Un lien a été envoyé à votre email, aller vérifier.');
                 }
            } catch (\Throwable $th) {
                //throw $th;
            }

            return back()->with('error', 'Echec! there is some issue with email provider');

        } catch (QueryException $th) {
           return redirect()->back()->with('error','Il y a un soucis');
        }
        

        
    }


    public function forgotPasswordValidate($token)
    {
        $user = User::where('token', $token)->where('is_verified', 0)->first();
        if ($user) {
            $email = $user->email;
            return view('auth.reinitilisePassword', compact('email'));
        }
       // return redirect()->route('reset_password')->with('error', 'Password reset link is expired');
       return redirect()->route('forgot-password')->with('error', 'Veuillez reprendre le procèssus de changement de mot de passe en entrant le email à nouveau');
    }



    public function reinitilisationPassword(Request $request)
    {

        $this->validate($request, [
            'email' => 'required',
            'Nouveau_mot_de_passe' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'confirm_mot_de_passe' => ['same:Nouveau_mot_de_passe'],
        ]);

        $user = User::where('email', $request->email )->first();
        if ($user) {

            $update = User::where('email', $request->email)
                          ->update([
                                   'token' => NULL, 
                                   'is_verified' => 0, 
                                   'password' => Hash::make($request->Nouveau_mot_de_passe)
                                ]);

            if ($update) {
                Auth::logout();
                return redirect()->route('login')->with('message', 'Mot de passe change avec succès');
            }

        }
        return redirect()->route('forgot-password')->with('error', 'error! something went wrong');
    }

    // REINITIALISER PASSWORD FIN

    function update_code_login($email,$code)
    {
        $update = DB::table('users')
                      ->where('email',$email)
                      ->update(['last_login' => Carbon::now(), 'code_login' => $code]);
        if ($update) {
            return true;
        }
        return false;
    }



    public function updatePassword(Request $request)
    {
        try {
        $this->validate($request, [

            'email' => ['required'],
            'Ancien_mot_de_passe' => ['required', new MatchOldPassword],
            'Nouveau_mot_de_passe' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            //'confirm_mot_de_passe' => ['same:Nouveau_mot_de_passe'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {

            $update = User::where('email', $request->email)
                           ->update([
                                     'token' => NULL,
                                     'is_verified' => 0,
                                     'password' => Hash::make($request->Nouveau_mot_de_passe),
                                     'password_changed_at' =>  Carbon::now()
                                 ]);
            if ($update) {

                $reponse1 = SessionController::save_session_annexe();  
                $reponse2 = SessionController::save_session_locataire();  


                Session::put(['anne_data'=> $reponse1,'locataire' => $reponse2]);

                activity()->performedOn(new User())
                           ->causedBy(Auth::user()->id)
                           ->log('Changement du mot de passe '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);
                //  Auth::logout();

                //$agent = Auth::user();
                $rand = rand(1000, 999999);

               
                $response = $this->update_code_login($request->email,$rand);
                
                if ($response) {
                    Session::put('otp_pending_email', $request->email);
                    return redirect()->route('code_login');
                } else {
                    return redirect()->back()->with('error', 'Il y a un soucis');
                }

            }

        }else {
           return redirect()->route('connexion')->with('error', 'Votre email ne correspond à aucun de nos enregistrement');
            
        }


        } catch (QueryException $e) {

           return redirect()->back()->with('error','Il y a un soucis');
        }
    }

    //changement password firstime connected
    public function getPasswordChange()
    {
        return view('auth.changePassword');
    }


    public function HandleLogin(Request $request)
    {

        try {

            $credentials = $request->only(['email', 'password']);
            if (Auth::attempt($credentials)) {
                $agent = Auth::user();

                if (empty($agent->password_changed_at))  {
                    return redirect()->route('change');
                }

                if (!empty($agent->status))  {
                    return redirect()->route('connexion')->with('error', 'ouuf désolé, votre compte a été désactivé par votre supérieur.');
                }
                if (!empty($agent->blocage_entreprise))  {
                    return redirect()->route('connexion')->with('error', 'Votre entreprise a été bloquée, veuillez nous contacter via whatsapp au (+22961082260).');
                }
                
                if (!empty($agent->password_changed_at) &&  empty($agent->status)) {

                    $rand = rand(1000, 999999);
                    $agent->update([
                        'last_login' => Carbon::now(),
                        'code_login' => $rand,
                    ]);

                     // Send code via email
                     $userinfo = [
                        'code_login' => $rand,
                        'email' => $agent->email
                    ];
                    //Session::put('enLigne', 'yes');

                    $reponse1 = SessionController::save_session_annexe();
                    $reponse2 = SessionController::save_session_locataire();

                    Session::put(['anne_data'=> $reponse1,'locataire' => $reponse2]);

                    // Initialiser l'annexe active en session pour les admins d'entreprise
                    if ($agent->is_admin == 1 && $agent->type_compte != 'Particulier') {
                        // Prendre la première annexe disponible par défaut
                        $firstAnnexe = Annexe::whereNull('status')
                                            ->whereNull('blocage_annexe')
                                            ->where('iddirection_ref', $agent->iddirection_ref)
                                            ->first();
                        if ($firstAnnexe) {
                            Session::put('active_annexe_id', $firstAnnexe->idannexes);
                        }
                    }

                    // Stocker l'email en session pour envoi OTP côté JS
                    Session::put('otp_pending_email', $agent->email);

                    activity()->performedOn($agent)
                                ->causedBy($agent)
                                ->log('Connexion au système par ' . $agent->nom . ' ' . $agent->prenom);

                    return redirect()->route('code_login');
                    
                }
            } else {
                return redirect()->back()->with('error', 'L\'email ou le mot de passe est incorrect.');
            }
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Veuillez démarrer le serveur local.');
        }
    }



    public function code_login()
    {
        Auth::logout();
        return view('auth/codelogin');
    }

    /**
     * Envoie (ou renvoie) le code OTP par email.
     * Appelé en AJAX depuis la page code_login.
     */
    public function sendLoginOtp(Request $request)
    {
        $email = Session::get('otp_pending_email');

        if (!$email) {
            return response()->json([
                'status'  => false,
                'message' => 'Session expirée. Veuillez vous reconnecter.',
            ], 403);
        }

        // Anti-spam : 1 envoi max toutes les 60 secondes
        $lastSent = Session::get('otp_last_sent');
        if ($lastSent && now()->diffInSeconds($lastSent) < 60) {
            $remaining = 60 - now()->diffInSeconds($lastSent);
            return response()->json([
                'status'    => false,
                'message'   => "Veuillez patienter {$remaining}s avant de renvoyer.",
                'remaining' => $remaining,
            ], 429);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Utilisateur introuvable.',
            ], 404);
        }

        // Régénérer le code à chaque envoi (renvoi inclus)
        $code = rand(100000, 999999);
        $user->update(['code_login' => $code, 'last_login' => Carbon::now()]);

        Session::put('otp_last_sent', now());

        try {
            Mail::to($email)->send(new SendCodemail([
                'code_login' => $code,
                'email'      => $email,
            ]));

            return response()->json([
                'status'  => true,
                'message' => 'Code envoyé à ' . $email,
            ]);
        } catch (\Exception $e) {
            Log::error('sendLoginOtp mail error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Échec de l\'envoi du code. Veuillez réessayer.',
            ], 500);
        }
    }


    public function code_submit(Request $request)
    {
        $validator =  Validator::make(
            $request->all(),
            [
                'code' => 'required'
            ],
            [
                '*code.required' => 'Le code est obligatoire',
            ]
        );
       
        $code = $request->code;
        $agent = User::where('code_login', $code)->orderByDesc('updated_at')->first();


       
        if ($agent != NULL) {
           Auth::login($agent);

            // Initialiser l'annexe active en session pour les admins d'entreprise
            if ($agent->is_admin == 1 && $agent->type_compte != 'Particulier') {
                // Vérifier si une annexe n'est pas déjà sélectionnée
                if (!Session::has('active_annexe_id')) {
                    $firstAnnexe = Annexe::whereNull('status')
                                        ->whereNull('blocage_annexe')
                                        ->where('iddirection_ref', $agent->iddirection_ref)
                                        ->first();
                    if ($firstAnnexe) {
                        Session::put('active_annexe_id', $firstAnnexe->idannexes);
                    }
                }
            }

            activity()->performedOn(new User())
                           ->causedBy(Auth::user()->id)
                           ->log('Connexion au système'.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

           return redirect()->route('home');

        } else {
            return redirect()->route('code_login')->with('error', 'Code incorrect, veuillez entre le code envoyé à votre adresse email');


        }
    }


    private function getDirectionId()
    {
        return Direction::pluck('iddirection', 'iddirection')->all();
    }



    public function index()
    {
        //$id = $this->getDirectionId();

        $data = User::where('email', 'NOT LIKE', 'alberttchegnon4@gmail.com')
                     ->where('is_admin',false)
                     ->where('iddirection_ref',Auth::user()->iddirection_ref)
                     //->where(Auth::user()->iddirection_ref,$id)
                     ->orderBy('id', 'DESC') 
                     ->get();


        //$data = Role::all();
        return view('users.index', compact('data'));
    }


    public function create()
    {
        //$this->authorize('ajouter-utilisateur');

        $roles = Role::where('name', 'NOT LIKE', 'Super Admin')
                       ->where('iddirectionRef_role',Auth::user()->iddirection_ref)
                       ->pluck('name', 'name')->all();

        return view('users.create')->with(['roles' => $roles]);
    }

    /**
     * Valide les données d'inscription sans créer de compte ni déclencher de paiement.
     * Appelé côté JS avant d'ouvrir le widget de paiement.
     */
    public function preValidateInscription(Request $request)
    {
        if ($request->type_compte === 'Particulier') {
            $validator = Validator::make(
                $request->all(),
                [
                    'nom'         => ['required', 'string', 'min:2'],
                    'prenom'      => ['required', 'string', 'min:2'],
                    'email'       => ['required','string','email','max:255', Rule::unique(User::class), Rule::unique('directions', 'email')],
                    'telephone'   => ['required'],
                    'type_compte' => ['required', 'string'],
                ],
                [
                    '*.required'   => 'Ce champ est obligatoire.',
                    'email.unique' => 'Cette adresse email est déjà associée à un compte existant.',
                    '*.min'        => 'Le :attribute doit avoir au moins :min caractères.',
                ]
            );
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'nom'              => ['required', 'string', 'min:2'],
                    'prenom'           => ['required', 'string', 'min:2'],
                    'email'            => ['required','string','email','max:255', Rule::unique(User::class)],
                    'telephone'        => ['required'],
                    'type_compte'      => ['required', 'string'],
                    'designation'      => ['required', 'string'],
                    'adresse'          => ['required', 'string'],
                    'email_entreprise' => ['required','string','email','max:255', Rule::unique('directions', 'email')],
                ],
                [
                    '*.required'              => 'Ce champ est obligatoire.',
                    'email.unique'            => 'Cette adresse email personnelle est déjà utilisée.',
                    'email_entreprise.unique' => 'Cette adresse email d\'entreprise est déjà associée à un compte existant.',
                    '*.min'                   => 'Le :attribute doit avoir au moins :min caractères.',
                ]
            );
        }

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'status'  => false,
                'message' => $errors->first(),
                'errors'  => $errors,
            ], 422);
        }

        // Émettre un token d'autorisation de paiement (valable 30 min, usage unique)
        $authToken = Str::random(48);
        session([
            'payment_auth_token'   => $authToken,
            'payment_auth_expires' => now()->addMinutes(30)->timestamp,
        ]);

        return response()->json(['status' => true, 'auth_token' => $authToken]);
    }

    public function saveAdminCompte(Request $request)
    {
        // Récupérer le transaction_id au plus tôt pour l'inclure dans les erreurs si paiement déjà fait
        $incomingTransactionId = $request->input('transaction_id');

        // ── Vérification du token d'autorisation pour les plans payants ──────────
        $planCodeAuth  = $request->input('plan_code', 'essai');
        $planAuth      = Plan::where('code', $planCodeAuth)->first();
        $isPaidPlanReq = $planAuth && floatval($planAuth->prix_annuel) > 0;

        if ($isPaidPlanReq) {
            $providedToken = $request->input('payment_auth_token');
            $sessionToken  = session('payment_auth_token');
            $sessionExpiry = session('payment_auth_expires', 0);

            if (empty($providedToken) || $providedToken !== $sessionToken || now()->timestamp > $sessionExpiry) {
                Log::warning('saveAdminCompte: token d\'autorisation invalide ou expiré', [
                    'plan'            => $planCodeAuth,
                    'transaction_id'  => $incomingTransactionId,
                ]);
                return response()->json([
                    'status'  => false,
                    'message' => 'Autorisation invalide. Veuillez recommencer le formulaire depuis le début.',
                ], 422);
            }

            // Usage unique : invalider le token immédiatement
            session()->forget(['payment_auth_token', 'payment_auth_expires']);
        }
        // ─────────────────────────────────────────────────────────────────────────

        try {
            DB::beginTransaction();

            // Validation différente selon le type de compte
            if ($request->type_compte === 'Particulier') {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'nom' => ['required', 'string', 'min:2'],
                        'prenom' => ['required', 'string', 'min:2'],
                        'email' => ['required','string','email','max:255', Rule::unique(User::class), Rule::unique('directions', 'email')],
                        'telephone' => ['required'],
                        'type_compte' => ['required', 'string'],
                    ],
                    [
                        '*.required' => 'Ce champ est obligatoire.',
                        'email.unique' => 'Cette adresse email est déjà associée à un compte existant.',
                        'nom.min' => 'Le :attribute doit avoir au moins 2 caractères.',
                        'prenom.min' => 'Le :attribute doit avoir au moins 2 caractères.',
                    ]
                );
            } else {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'nom' => ['required', 'string', 'min:2'],
                        'prenom' => ['required', 'string', 'min:2'],
                        'email' => ['required','string','email','max:255',Rule::unique(User::class)],
                        'telephone' => ['required'],
                        'type_compte' => ['required', 'string'],
                        'designation' => ['required', 'string'],
                        'adresse' => ['required', 'string'],
                        'email_entreprise' => ['required','string','email','max:255', Rule::unique('directions', 'email')],
                    ],
                    [
                        '*.required' => 'Ce champ est obligatoire.',
                        'email.unique' => 'Cette adresse email personnelle est déjà utilisée.',
                        'email_entreprise.unique' => 'Cette adresse email d\'entreprise est déjà associée à un compte existant.',
                        '*.min' => 'Le :attribute doit avoir au moins :min caractères.',
                    ]
                );
            }

            if ($validator->fails()) {
                DB::rollBack();

                // Si un paiement a déjà été effectué, on le signale explicitement
                if (!empty($incomingTransactionId)) {
                    // Vérification d'idempotence : si la direction avec cet email a déjà
                    // ce transaction_id, le compte a été créé lors d'une requête précédente.
                    $emailCheck = $request->input('email_entreprise') ?: $request->input('email');
                    $existingDir = Direction::where('email', $emailCheck)
                        ->where(function ($q) use ($incomingTransactionId) {
                            $q->where('kkiapay_transaction_id', $incomingTransactionId)
                              ->orWhere('fedapay_transaction_id', $incomingTransactionId);
                        })->first();

                    if ($existingDir) {
                        Log::info('saveAdminCompte: compte déjà créé (idempotence)', [
                            'transaction_id' => $incomingTransactionId,
                            'direction_id'   => $existingDir->iddirection,
                        ]);
                        return response()->json([
                            'status'  => true,
                            'message' => 'Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.',
                        ]);
                    }

                    Log::error('saveAdminCompte: validation échouée après paiement', [
                        'transaction_id' => $incomingTransactionId,
                        'email'          => $emailCheck,
                        'errors'         => $validator->errors()->toArray(),
                    ]);
                    return response()->json([
                        'status'          => false,
                        'payment_pending' => true,
                        'transaction_id'  => $incomingTransactionId,
                        'message'         => 'Votre paiement a été reçu (réf : ' . $incomingTransactionId . ') mais la création du compte a échoué : ' . $validator->errors()->first() . ' Contactez le support avec cette référence.',
                        'error'           => $validator->errors(),
                    ], 422);
                }

                return response()->json([
                    'status'  => false,
                    'message' => 'Veuillez vérifier les informations saisies',
                    'error'   => $validator->errors()
                ], 422);
            }
            
            // Préparation des données
            $nom = Str::upper($request->nom);
            $prenom = Str::ucfirst($request->prenom);
            $email = $request->email;
            $grade = 'Administrateur';
            $type_compte = $request->type_compte;
            $mot_de_passe = Str::random(12);

            // Déterminer le plan pour vérifier si paiement requis
            $planCodeTemp = $request->plan_code ?? 'essai';
            $planTemp     = Plan::where('code', $planCodeTemp)->first() ?? Plan::essai();
            $isPlanGratuitTemp = $planTemp && floatval($planTemp->prix_annuel) == 0;

            // Vérification du paiement si plan payant et prestataire activé
            $paymentConfig        = PlatformConfig::getConfig();
            $kkiapayTransactionId = null;
            $fedapayTransactionId = null;

            if (!$isPlanGratuitTemp && $paymentConfig->isOperational()) {
                $transactionId = $request->input('transaction_id');

                if (empty($transactionId)) {
                    DB::rollBack();
                    return response()->json([
                        'status'  => false,
                        'message' => 'Un paiement est requis pour ce plan. Veuillez effectuer le paiement.',
                    ], 422);
                }

                if ($paymentConfig->isKkiapayActive()) {
                    $svc = new KkiapayService(
                        $paymentConfig->kkiapay_public_key,
                        $paymentConfig->kkiapay_private_key,
                        $paymentConfig->kkiapay_secret_key,
                        $paymentConfig->getActiveSandbox()
                    );
                    $verification = $svc->verifyTransaction($transactionId);
                    if (!$verification['success']) {
                        DB::rollBack();
                        return response()->json([
                            'status'  => false,
                            'message' => 'Paiement KKiaPay invalide ou non confirmé. Veuillez réessayer.',
                        ], 422);
                    }
                    $kkiapayTransactionId = $transactionId;
                } else {
                    $svc = new FedapayService(
                        $paymentConfig->fedapay_secret_key,
                        $paymentConfig->getActiveSandbox()
                    );
                    $verification = $svc->verifyTransaction($transactionId);
                    if (!$verification['success']) {
                        DB::rollBack();
                        return response()->json([
                            'status'  => false,
                            'message' => 'Paiement FedaPay invalide ou non confirmé. Veuillez réessayer.',
                        ], 422);
                    }
                    $fedapayTransactionId = $transactionId;
                }
            }
            
            // Gestion des données de l'entreprise
            if ($type_compte === 'Entreprise') {
                $designation = $request->designation;
                $adresse = $request->adresse;
                $telepone_entreprise = $request->telephone;
                $email_entreprise = $request->email_entreprise;
            } else {
                // Pour les particuliers
                $designation = $nom . ' ' . $prenom;
                $adresse = 'Non spécifié';
                $telepone_entreprise = $request->telephone;
                $email_entreprise = $email;
            }
            
            // Récupérer le plan sélectionné par l'utilisateur (défaut = essai)
            $planCode = $request->plan_code ?? 'essai';
            $planSelectionne = Plan::where('code', $planCode)->first();
            if (!$planSelectionne) {
                $planSelectionne = Plan::essai();
            }
            $planId = $planSelectionne ? $planSelectionne->idplan : null;

            // Durée : 14 jours pour essai (gratuit), 12 mois pour plans payants
            $isPlanGratuit = $planSelectionne && floatval($planSelectionne->prix_annuel) == 0;
            $abonnementFin = $isPlanGratuit
                ? Carbon::now()->addDays(14)
                : Carbon::now()->addYear();

            // Création de la direction avec le plan sélectionné
            $direction_id = Direction::insertGetId([
                'designation'           => $designation,
                'siege_social'          => $adresse,
                'telephone'             => $telepone_entreprise,
                'email'                 => $email_entreprise,
                'idplan_ref'            => $planId,
                'abonnement_debut'      => Carbon::now(),
                'abonnement_fin'        => $abonnementFin,
                'statut_abonnement'      => 'essai', // En attente de validation admin
                'kkiapay_transaction_id' => $kkiapayTransactionId,
                'fedapay_transaction_id' => $fedapayTransactionId,
                'created_at'            => Carbon::now(),
                'updated_at'            => Carbon::now()
            ], 'iddirection');
            
            // Création de l'annexe (bloquée par défaut, en attente de validation admin)
            $annexe_id = Annexe::insertGetId([
                'iddirection_ref' => $direction_id,
                'designation' => $designation,
                'siege_social' => $adresse,
                'telephone' => $telepone_entreprise,
                'email' => $email_entreprise,
                'userdata' => $designation,
                'blocage_annexe' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ], 'idannexes');

            // Création de l'utilisateur (bloqué par défaut, en attente de validation admin)
            $newuser = User::create([
                'iddirection_ref' => $direction_id,
                'idannexe_ref' => $annexe_id,
                'nom' => $nom,
                'prenom' => $prenom,
                'grade' => $grade,
                'type_compte' => $type_compte,
                'email' => $email,
                'email_verification_token' => Str::random(32),
                'email_verified' => 0,
                'is_admin' => true,
                'blocage_entreprise' => Carbon::now(),
                'password' => Hash::make($mot_de_passe),
                'password_changed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // Création ou récupération du rôle Administrateur propre à cette direction
            $role = Role::where('name', 'Administrateur')
                        ->where('iddirectionRef_role', $direction_id)
                        ->first();
            if (!$role) {
                $role = Role::create([
                    'name'                => 'Administrateur',
                    'guard_name'          => 'web',
                    'iddirectionRef_role' => $direction_id,
                ]);
                // Toutes les permissions sauf config-paiement (réservé au propriétaire de l'appli)
                $permissions = Permission::where('name', '!=', 'config-paiement')->pluck('id')->all();
                $role->syncPermissions($permissions);
            }

            $newuser->assignRole($role->id);
            
            // Préparation des données pour l'email
            $userinfos = [
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'email_verification_token' => $newuser->email_verification_token,
                'password' => $mot_de_passe // Pour l'email de bienvenue
            ];
            
            // Mise à jour du token email
            User::where('id', $newuser->id)->update(['mail_token_at' => Carbon::now()]);
            
            DB::commit();

            // Générer le cachet et le logo par défaut pour cette direction
            try {
                $cachetService = new \App\Services\CachetGeneratorService();
                $cachetPath    = $cachetService->generate($designation, $direction_id);

                $logoService = new \App\Services\LogoGeneratorService();
                $logoPath    = $logoService->generate($designation, $direction_id);

                \DB::table('parametres')->insert([
                    'iddirection_ref'       => $direction_id,
                    'cash_electronique_url' => $cachetPath,
                    'logo_url'              => $logoPath ?? '',
                    'format_choisi'         => 'default',
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            } catch (\Exception $e) {
                // Non bloquant : la génération ne doit pas empêcher l'inscription
                \Illuminate\Support\Facades\Log::warning('Cachet/logo generation failed: ' . $e->getMessage());
            }

            // Préparer les données pour la facture d'abonnement
            $planData = $planSelectionne ? [
                'nom' => $planSelectionne->nom,
                'code' => $planSelectionne->code,
                'prix_annuel' => $planSelectionne->prix_annuel,
                'max_maisons' => $planSelectionne->max_maisons,
                'max_annexes' => $planSelectionne->max_annexes,
            ] : [
                'nom' => 'Essai',
                'code' => 'essai',
                'prix_annuel' => 0,
                'max_maisons' => 2,
                'max_annexes' => 0,
            ];

            $invoiceData = [
                'user' => [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'telephone' => $telepone_entreprise,
                ],
                'plan' => $planData,
                'direction' => [
                    'designation' => $designation,
                    'abonnement_debut' => Carbon::now()->toDateString(),
                    'abonnement_fin' => $abonnementFin->toDateString(),
                ],
            ];

            // Infos de paiement (si prestataire actif et transaction effectuée)
            if ($kkiapayTransactionId) {
                $invoiceData['payment'] = [
                    'provider'       => 'KKiaPay',
                    'transaction_id' => $kkiapayTransactionId,
                    'sandbox'        => $paymentConfig->getActiveSandbox(),
                ];
            } elseif ($fedapayTransactionId) {
                $invoiceData['payment'] = [
                    'provider'       => 'FedaPay',
                    'transaction_id' => $fedapayTransactionId,
                    'sandbox'        => $paymentConfig->getActiveSandbox(),
                ];
            }

            // Envoi de la facture d'abonnement par email (synchrone)
            try {
                $invoiceService = new \App\Services\SubscriptionInvoiceService();
                $pdfContent = $invoiceService->generate($invoiceData);
                $invoiceData['pdf_content'] = $pdfContent;
                Mail::to($email)->send(new \App\Mail\SubscriptionInvoiceMail($invoiceData));
            } catch (\Exception $invoiceException) {
                Log::error('Erreur envoi facture inscription: ' . $invoiceException->getMessage(), [
                    'email' => $email,
                    'trace' => $invoiceException->getTraceAsString(),
                ]);
            }

            // Envoi de l'email de vérification
            try {
                Mail::to($newuser->email)->send(new VerificationEmail($userinfos));

                return response()->json([
                    'status' => true,
                    'message' => 'Votre compte a été créé avec succès! Un email de confirmation vous a été envoyé.'
                ]);

            } catch (\Exception $mailException) {
                Log::error('Erreur envoi email vérification: ' . $mailException->getMessage(), [
                    'email' => $email,
                ]);
                // Le compte est créé même si l'email échoue
                return response()->json([
                    'status' => true,
                    'message' => 'Votre compte a été créé avec succès! Cependant, l\'email de confirmation n\'a pas pu être envoyé. Veuillez contacter le support.'
                ]);
            }
            
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('QueryException saveAdminCompte: ' . $e->getMessage(), [
                'transaction_id' => $incomingTransactionId,
            ]);

            // Violation de contrainte unique (PostgreSQL: 23505 / MySQL: 1062)
            $sqlState = $e->errorInfo[0] ?? '';
            if ($sqlState === '23505' || $e->getCode() == 1062) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'directions_email_unique') || str_contains($msg, '"directions"')) {
                    $detail = 'Cette adresse email d\'entreprise est déjà associée à un compte existant.';

                    // Vérification d'idempotence : même transaction = compte déjà créé
                    if (!empty($incomingTransactionId)) {
                        $emailCheck  = $request->input('email_entreprise') ?: $request->input('email');
                        $existingDir = Direction::where('email', $emailCheck)
                            ->where(function ($q) use ($incomingTransactionId) {
                                $q->where('kkiapay_transaction_id', $incomingTransactionId)
                                  ->orWhere('fedapay_transaction_id', $incomingTransactionId);
                            })->first();

                        if ($existingDir) {
                            Log::info('saveAdminCompte: compte déjà créé – idempotence (DB exception)', [
                                'transaction_id' => $incomingTransactionId,
                                'direction_id'   => $existingDir->iddirection,
                            ]);
                            return response()->json([
                                'status'  => true,
                                'message' => 'Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.',
                            ]);
                        }
                    }
                } elseif (str_contains($msg, 'users_email_unique') || str_contains($msg, '"users"')) {
                    $detail = 'Cette adresse email personnelle est déjà utilisée.';
                } else {
                    $detail = 'Une valeur saisie est déjà utilisée par un autre compte.';
                }

                if (!empty($incomingTransactionId)) {
                    return response()->json([
                        'status'          => false,
                        'payment_pending' => true,
                        'transaction_id'  => $incomingTransactionId,
                        'message'         => 'Votre paiement a été reçu (réf : ' . $incomingTransactionId . ') mais la création du compte a échoué : ' . $detail . ' Contactez le support avec cette référence.',
                    ], 422);
                }

                return response()->json([
                    'status'  => false,
                    'message' => $detail,
                ], 422);
            }

            if (!empty($incomingTransactionId)) {
                return response()->json([
                    'status'          => false,
                    'payment_pending' => true,
                    'transaction_id'  => $incomingTransactionId,
                    'message'         => 'Votre paiement a été reçu (réf : ' . $incomingTransactionId . ') mais une erreur technique a empêché la création du compte. Contactez le support avec cette référence.',
                ], 500);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.',
            ], 500);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Exception saveAdminCompte: ' . $e->getMessage(), [
                'transaction_id' => $incomingTransactionId,
            ]);

            if (!empty($incomingTransactionId)) {
                return response()->json([
                    'status'          => false,
                    'payment_pending' => true,
                    'transaction_id'  => $incomingTransactionId,
                    'message'         => 'Votre paiement a été reçu (réf : ' . $incomingTransactionId . ') mais une erreur inattendue a empêché la création du compte. Contactez le support avec cette référence.',
                ], 500);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Une erreur inattendue est survenue. Veuillez réessayer.',
            ], 500);
        }
    }

    public function saveAdminCompteOld(Request $request)
    {
        try {
            if ($request->type_compte == 'Particulier') {
                $validator = Validator::make(
                    $request->all(),
                    [
                    'nom' => ['required', 'string', 'min:2'],
                    'prenom' => ['required', 'string', 'min:2'],
                    'email' => ['required','string','email','max:255',Rule::unique(User::class),],
                    'telephone' => ['required'],
                    'type_compte' => ['required', 'string'],
                    'mot_de_passe' => ['required', 'string', 'min:8'],
                    'Confirmer_mot_de_passe' => ['required','same:mot_de_passe','string', 'min:8'],

                    ],
                    [
                        '*.required' => 'Ce champ est obligatoire.',
                        'email.unique' => 'L\'adresse mail est déjà utilisé',
                        'nom.min' => 'Le :attribute doit avoir au moins 2 caractères.',
                        'prenom.min' => 'Le :attribute doit avoir au moins 2 caractères.',
                    ]);
            }else {
                $validator = Validator::make(
                    $request->all(),
                    [
                    'nom' => ['required', 'string', 'min:2'],
                    'prenom' => ['required', 'string', 'min:2'],
                    'email' => ['required','string','email','max:255',Rule::unique(User::class),],
                    'telephone' => ['required'],
                    'type_compte' => ['required', 'string'],
                    'mot_de_passe' => ['required', 'string', 'min:8'],
                    'Confirmer_mot_de_passe' => ['required','same:mot_de_passe','string', 'min:8'],
                    'designation' => ['required', 'string'],
                    'adresse' => ['required', 'string'],
                    'email_entreprise' => ['required','string','email','max:255'],
                    ],
                    [
                        '*.required' => 'Ce champ est obligatoire.',
                        'email.unique' => 'L\'adresse mail est déjà utilisé',
                        '*.min' => 'Le :attribute doit avoir au moins :min caractères.',
                        'Confirmer_mot_de_passe.same' => 'Le :attribute ne correspond pas au précédent',
                    ]);
            }
            
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => "Veuillez vérifier les informations saisies",
                ]);
            }
    
            if ($request->type_compte == 'Particulier') {
                $nom = Str::upper($request->nom);
                $prenom =  Str::ucfirst($request->prenom);
                $email = $request->email;
                $grade  = 'Administrateur';
                //$telephone = $request->telephone;
                $type_compte = $request->type_compte;
                $mot_de_passe = $request->mot_de_passe;
                $designation = $request->nom.' '.$request->prenom;
                $adresse =$request->nom;
                $telepone_entreprise = $request->telephone;
                $email_entreprise = $request->email;
            }else {
                $nom = Str::upper($request->nom);
                $prenom =  Str::ucfirst($request->prenom);
                $email = $request->email;
                $grade  = 'Administrateur';
                $type_compte = $request->type_compte;
                $mot_de_passe = $request->mot_de_passe;
                $designation = $request->designation;
                $adresse =$request->adresse;
                $telepone_entreprise = $request->telephone;
                $email_entreprise = $request->email_entreprise;
            }
    
            $direction_id = Direction::insertGetId([
                'designation'                   => $designation,
                'siege_social'                => $adresse,
                'telephone'                 => $telepone_entreprise,
                'email'                 => $email_entreprise,
            ], 'iddirection');

    
    
            $annexe_id = Annexe::insertGetId([
                'iddirection_ref' => $direction_id,
                'designation'                   => $designation,
                'siege_social'                => $adresse,
                'telephone'                 => $telepone_entreprise,
                'email'                 => $email_entreprise,
                'userdata'              =>  $designation
            ], 'idannexes');
    
    
            $newuser = User::create([
                'iddirection_ref' => $direction_id,
                'idannexe_ref' => $annexe_id,
                'nom'                   => $nom,
                'prenom'                => $prenom,
                'grade'                 => $grade,
                'type_compte'           => $type_compte,
                'email'                 => $email,
                'email_verification_token' => Str::random(32),
                'email_verified'        => 0,
                'is_admin'              => true,
                'password'              =>  Hash::make($mot_de_passe),
            ]);


            $role = Role::where('name', 'Administrateur')
                        ->where('iddirectionRef_role', $direction_id)
                        ->first();
            if (!$role) {
                $role = Role::create([
                    'name'                => 'Administrateur',
                    'iddirectionRef_role' => $direction_id,
                ]);
            }
            // Toutes les permissions sauf config-paiement
            $permissions = Permission::where('name', '!=', 'config-paiement')->pluck('id', 'id')->all();
            $role->syncPermissions($permissions);
            $newuser->assignRole([$role->id]);
    
    
            $userinfos = [
                'nom'    => $nom,
                'prenom'    => $prenom,
                'email'         => $request->email,
                'email_verification_token' => $newuser->email_verification_token
            ];
    
    
            if ($newuser) {
    
                Mail::to($newuser->email)->send(new VerificationEmail($userinfos));
                User::where('id', $newuser->id)->update(['mail_token_at' => Carbon::now()]);
    
                return response()->json([
                    'status' => true,
                    'message' => 'Votre compte est crée avec succès,un mail vous a été envoyé pour se connecter.'
                ]);
    
            } else {
    
                return response()->json([
                    'status' => false,
                    'message' => 'Enregistrement échoué.'
                ]);
            }
        } catch (QueryException $th) {
            return response()->json([
                'status' => false,
                'message' => 'Enregistrement échoué.'.$th->getMessage()
            ]);
        }

    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nom' => ['required', 'string', 'min:2'],
                    'prenom' => ['required', 'string', 'min:2'],
                    'grade' => ['required', 'string', 'min:2'],
                    'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
                    'roles' => 'required',
                ],
                [
                    '*.required' => 'Ce champ est obligatoire.',
                    'email.unique' => 'L\'adresse mail est déjà utilisée',
                    'nom.min' => 'Le :attribute doit avoir au moins 2 caractères.',
                    'prenom.min' => 'Le :attribute doit avoir au moins 2 caractères.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => "Veuillez vérifier les informations saisies",
                ]);
            }

            // Utiliser l'annexe active centralisée
            $idannexe_ref = get_active_annexe_id();
            if (!$idannexe_ref) {
                return response()->json([
                    'status' => false,
                    'message' => "Veuillez sélectionner une agence dans le header"
                ]);
            }

            $password = uniqid();
            $emailVerificationToken = Str::random(32);
            $currentUser = Auth::user();

            DB::beginTransaction();

            try {
                $newuser = User::create([
                    'nom' => Str::upper($request->nom),
                    'prenom' => Str::ucfirst($request->prenom),
                    'grade' => Str::ucfirst($request->grade),
                    'idannexe_ref' => $idannexe_ref,
                    'iddirection_ref' => $currentUser->iddirection_ref,
                    'email' => $request->email,
                    'email_verification_token' => $emailVerificationToken,
                    'is_admin' => false,
                    'email_verified' => 0,
                    'password' => Hash::make($password),
                    'type_compte' => $currentUser->type_compte,
                    'mail_token_at' => Carbon::now(),
                ]);

                $newuser->assignRole($request->input('roles'));

                $userinfos = [
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                    'email' => $request->email,
                    'password' => $password,
                    'email_verification_token' => $emailVerificationToken
                ];

                activity()->performedOn($newuser)
                    ->causedBy($currentUser->id)
                    ->log("Création du compte de {$request->nom} {$request->prenom} par {$currentUser->nom} {$currentUser->prenom}");

                DB::commit();

                try {
                    Mail::to($newuser->email)->send(new VerificationEmail($userinfos));
                } catch (\Exception $mailException) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Enregistrement reussi mais envoie de mail échoué.'
                    ]);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Utilisateur enregistré avec succès, un mail lui est envoyé contenant ses accès'
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                //throw $e;
                return response()->json([
                    'status' => false,
                    'message' => 'Enregistrement échoué. Veuillez réessayer.'
                ]);
            }

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Enregistrement échoué. Veuillez réessayer.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Enregistrement échoué. Veuillez réessayer.'
            ]);
        }
    }


    public function show($id)
    {
       // $this->authorize('ajouter-utilisateur');
        $user = User::find($id);
        return view('users.show', compact('user'));
    }


    public function edit($id)
    {
        //$this->authorize('modifier-utilisateur');

        $id   = decrypt_id($id);
        abort_if(!$id, 404);
        $user = User::findOrFail($id);
        // $roles = Role::pluck('name','name')->all();
        $roles = Role::where('name', 'NOT LIKE', 'Super Admin')
                     ->where('iddirectionRef_role',Auth::user()->iddirection_ref)
                     ->pluck('name', 'name')->all();

        $userRole = $user->roles->pluck('name', 'name')->all();
        
        $annexes = Annexe::whereNull('status')
                         ->where('email','!=','alldigitalagency90@gmail.com')
                         ->where('annexes.iddirection_ref',Auth::user()->iddirection_ref)
                         ->get();


        return view('users.edit', compact('user', 'roles', 'userRole','annexes'));
    }



    public function update(Request $request)
    {
       // $this->authorize('modifier-utilisateur');
        $id = decrypt_id($request->user);
        if (!$id) {
            return response()->json(['status' => false, 'message' => 'Identifiant invalide.']);
        }
        $checkoldnum = User::where('id', $id)->first();
        $checkuser = Validator::make($request->all(), [
            'user' => 'required',
        ]);

        if ($checkuser->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => "Erreur dans la modification",
            ]);
        }

        $validator =  Validator::make(
            $request->all(),
            [
                'nom'           => 'required',
                'prenom'        => 'required',
                'email' => ['required','string','email','max:255'],
                'grade'        => 'required',
                'roles'         => 'required'

            ],
            [
                '*.required'            => 'Le :attribute est obligatoire.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error'     => $validator->errors(),
                'message'   => "Veuillez Vérifier Les Informations Saisies",
            ]);
        }

        // Utiliser l'annexe active centralisée
        $idannexe_ref = get_active_annexe_id();
        if (!$idannexe_ref) {
            return response()->json([
                'status' => false,
                'message' => "Veuillez sélectionner une agence dans le header"
            ]);
        }

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        // Ajouter l'annexe active aux données
        $input['idannexe_ref'] = $idannexe_ref;

        $nom           = Str::upper($request->nom);
        $prenom        = Str::ucfirst($request->prenom);
        $iddirection_ref       = Auth::user()->iddirection_ref;
        $email               = $request->email;
        $type_compte       = Auth::user()->type_compte;
        $grade     = Str::ucfirst($request->grade);
        $roles         = $request->roles;
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        // return redirect()->route('users.index')->with('success','User updated successfully');

        activity()->performedOn(new User())
                           ->causedBy(Auth::user()->id)
                           ->log('Modification du compte de '.$nom.' '.$prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return response()->json([
            'status'    => true,
            'message'   => "Modification effectué avec succès",
        ]);
    }

   
    public function destroy($id)
    {
        $id   = decrypt_id($id);
        abort_if(!$id, 404);
        $user = User::findOrFail($id);
    
        // Inversion du statut : NULL = actif, datetime = désactivé
        $isCurrentlyActive = empty($user->status);

        $user->status = $isCurrentlyActive ? Carbon::now()->toDateTimeString() : null;
        $user->save();
    
        // Action log
        $action = $isCurrentlyActive ? 'Désactivation' : 'Activation';
        activity()
            ->performedOn($user)
            ->causedBy(Auth::id())
            ->log("{$action} du compte de {$user->nom} {$user->prenom} par " . Auth::user()->nom . ' ' . Auth::user()->prenom);
    
        // Déconnexion si on désactive le compte courant
        if ($isCurrentlyActive && Auth::id() == $user->id) {
            Auth::logout();
        }
    
        $message = $isCurrentlyActive
            ? "Le compte de {$user->nom} {$user->prenom} a été désactivé."
            : "Le compte de {$user->nom} {$user->prenom} a été réactivé.";

        return response()->json([
            'status'     => true,
            'message'    => $message,
            'new_status' => $isCurrentlyActive ? 'inactive' : 'active',
            'logout'     => $isCurrentlyActive && Auth::id() == $user->id,
        ]);
    }
    

    

    
}
