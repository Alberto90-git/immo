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
                
                if ($response)
                {
                    
                   try {
                            $userinfo = [
                                'code_login' => $rand,
                                'email' => $request->email
                            ];

                            Mail::to($request->email)->send(new SendCodemail($userinfo));
                            return redirect()->route('code_login');

                   } catch (\Throwable $th) {
                        //throw $th;
                   }
                  //return redirect()->route('home')->with('success', 'Mot de passe change avec succès');
                }else {
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

                    Mail::to($agent->email)->send(new SendCodemail($userinfo));

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

        $data = User::where('email', 'NOT LIKE', 'admin@immo.net')
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

    public function saveAdminCompte(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Validation différente selon le type de compte
            if ($request->type_compte === 'Particulier') {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'nom' => ['required', 'string', 'min:2'],
                        'prenom' => ['required', 'string', 'min:2'],
                        'email' => ['required','string','email','max:255',Rule::unique(User::class)],
                        'code_pays' => ['required'],
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
                        'Confirmer_mot_de_passe.same' => 'Le mot de passe de confirmation ne correspond pas.',
                    ]
                );
            } else {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'nom' => ['required', 'string', 'min:2'],
                        'prenom' => ['required', 'string', 'min:2'],
                        'email' => ['required','string','email','max:255',Rule::unique(User::class)],
                        'code_pays' => ['required'],
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
                        'Confirmer_mot_de_passe.same' => 'Le mot de passe de confirmation ne correspond pas.',
                    ]
                );
            }
            
            if ($validator->fails()) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Veuillez vérifier les informations saisies',
                    'error' => $validator->errors()
                ], 422);
            }
            
            // Préparation des données
            $nom = Str::upper($request->nom);
            $prenom = Str::ucfirst($request->prenom);
            $email = $request->email;
            $grade = 'Administrateur';
            $type_compte = $request->type_compte;
            $mot_de_passe = $request->mot_de_passe;
            
            // Gestion des données de l'entreprise
            if ($type_compte === 'Entreprise') {
                $designation = $request->designation;
                $adresse = $request->adresse;
                $telepone_entreprise = $request->code_pays . $request->telephone;
                $email_entreprise = $request->email_entreprise;
            } else {
                // Pour les particuliers
                $designation = $nom . ' ' . $prenom;
                $adresse = 'Non spécifié';
                $telepone_entreprise = $request->code_pays . $request->telephone;
                $email_entreprise = $email;
            }
            
            // Récupérer le plan Starter par défaut
            $planStarter = Plan::starter();
            $planId = $planStarter ? $planStarter->idplan : null;

            // Création de la direction avec le plan Starter par défaut
            $direction_id = Direction::insertGetId([
                'designation' => $designation,
                'siege_social' => $adresse,
                'telephone' => $telepone_entreprise,
                'email' => $email_entreprise,
                'idplan_ref' => $planId,
                'abonnement_debut' => Carbon::now(),
                'abonnement_fin' => Carbon::now()->addYear(), // 1 an d'abonnement
                'statut_abonnement' => 'essai', // Période d'essai par défaut
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // Création de l'annexe
            $annexe_id = Annexe::insertGetId([
                'iddirection_ref' => $direction_id,
                'designation' => $designation,
                'siege_social' => $adresse,
                'telephone' => $telepone_entreprise,
                'email' => $email_entreprise,
                'userdata' => $designation,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // Création de l'utilisateur
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
                'password' => Hash::make($mot_de_passe),
                'password_changed_at' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            
            // Création ou récupération du rôle Administrateur
            $role = Role::where('name', 'Administrateur')->first();
            if (!$role) {
                $role = Role::create([
                    'name' => 'Administrateur',
                    'guard_name' => 'web',
                    'iddirectionRef_role' => $direction_id
                ]);
                
                // Attribution de toutes les permissions au rôle Administrateur
                $permissions = Permission::pluck('id')->all();
                $role->syncPermissions($permissions);
            }
            
            // Attribution du rôle à l'utilisateur
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
            
            // Envoi de l'email de vérification
            try {
                Mail::to($newuser->email)->send(new VerificationEmail($userinfos));
                
                return response()->json([
                    'status' => true,
                    'message' => 'Votre compte a été créé avec succès! Un email de confirmation vous a été envoyé.'
                ]);
                
            } catch (\Exception $mailException) {
                // Le compte est créé même si l'email échoue
                return response()->json([
                    'status' => true,
                    'message' => 'Votre compte a été créé avec succès! Cependant, l\'email de confirmation n\'a pas pu être envoyé. Veuillez contacter le support.'
                ]);
            }
            
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.'
            ], 500);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Une erreur inattendue est survenue. Veuillez réessayer.'
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
                    //'telephone' => ['required'],
                    'code_pays' => ['required'],
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
                    //'telephone' => ['required'],
                    'code_pays' => ['required'],
                    'type_compte' => ['required', 'string'],
                    'mot_de_passe' => ['required', 'string', 'min:8'],
                    'Confirmer_mot_de_passe' => ['required','same:mot_de_passe','string', 'min:8'],
                    'designation' => ['required', 'string'],
                    'adresse' => ['required', 'string'],
                    'telepone_entreprise' => ['required'],
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
                $telepone_entreprise = $request->code_pays.''.$request->telephone;
                $email_entreprise = $request->email;
            }else {
                $nom = Str::upper($request->nom);
                $prenom =  Str::ucfirst($request->prenom);
                $email = $request->email;
                $grade  = 'Administrateur';
               // $telephone = $request->telephone;
                $type_compte = $request->type_compte;
                $mot_de_passe = $request->mot_de_passe;
                $designation = $request->designation;
                $adresse =$request->adresse;
                $telepone_entreprise = $request->code_pays.''.$request->telephone;
                $email_entreprise = $request->email_entreprise;
            }
    
            $direction_id = Direction::insertGetId([
                'designation'                   => $designation,
                'siege_social'                => $adresse,
                'telephone'                 => $telepone_entreprise,
                'email'                 => $email_entreprise,
            ]);

    
    
            $annexe_id = Annexe::insertGetId([
                'iddirection_ref' => $direction_id,
                'designation'                   => $designation,
                'siege_social'                => $adresse,
                'telephone'                 => $telepone_entreprise,
                'email'                 => $email_entreprise,
                'userdata'              =>  $designation
            ]);
    
    
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


            $role = Role::where('name','Administrateur')->first();
            if (empty($role)) {
               $role = Role::create(['name' => 'Administrateur']);
            }


            $permissions = Permission::pluck('id','id')->all();
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

        $user = User::find($id);
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
        $id           = $request->user;
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
        $user = User::findOrFail($id);
    
        // Inversion du statut : si NULL → désactivation, sinon → activation
        $isCurrentlyActive = is_null($user->status);
    
        $user->status = $isCurrentlyActive ? Carbon::now() : null;
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
    
        // Message utilisateur
        $message = $isCurrentlyActive
            ? "L’utilisateur {$user->nom} {$user->prenom} a été désactivé avec succès."
            : "L’utilisateur {$user->nom} {$user->prenom} a été réactivé avec succès.";
    
        return redirect()->route('getUserView')->with('success', $message);
    }
    

    

    
}
