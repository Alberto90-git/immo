<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\MaisonController;
use App\Http\Controllers\ChambreController;
use App\Http\Controllers\PrixController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\ParametreController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ParcelleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\PubliciteController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EnvoiDocumentController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\PlatformConfigController;
use App\Http\Controllers\SuperAdminPlanController;

use App\Publicite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// login and logout


Route::get('/', [ParametreController::class, 'welcome_page'])->name('accueil');

// Pages légales (publiques)
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('conditions-utilisation', fn() => view('legal.cgu'))->name('cgu');
    Route::get('confidentialite', fn() => view('legal.confidentialite'))->name('confidentialite');
    Route::get('mentions-legales', fn() => view('legal.mentions'))->name('mentions');
    Route::get('cookies', fn() => view('legal.cookies'))->name('cookies');
});




Route::get('connexion', function () {
    return view('auth/login');
})->name('connexion');  



Auth::routes();

Route::post('pre-validate-inscription', [UtilisateurController::class, 'preValidateInscription'])->name('pre_validate_inscription');
Route::post('creation-compte', [UtilisateurController::class, 'saveAdminCompte'])->name('creation_compte');

Route::post('login-check', [UtilisateurController::class, 'HandleLogin'])->name('login_check');

// Config KKiaPay publique (pour welcome page, sans auth)
Route::get('platform/kkiapay-public', [PlatformConfigController::class, 'publicConfig'])->name('platform.kkiapay.public');


Route::middleware('auth')->group(function () {

    // Routes Super Admin — Configuration plateforme KKiaPay
    Route::get('super-admin/config-paiement', [PlatformConfigController::class, 'configPage'])->name('super_admin.config_paiement');
    Route::get('platform/kkiapay-config', [PlatformConfigController::class, 'getAdminConfig'])->name('platform.kkiapay.config');
    Route::post('platform/kkiapay-config', [PlatformConfigController::class, 'update'])->name('platform.kkiapay.update');

    // Routes Super Admin — Configuration des plans d'abonnement
    Route::get('super-admin/config-plans', [SuperAdminPlanController::class, 'index'])->name('super_admin.config_plans');
    Route::post('super-admin/plans/update', [SuperAdminPlanController::class, 'update'])->name('super_admin.plans.update');
    Route::post('super-admin/plans/store',  [SuperAdminPlanController::class, 'store'])->name('super_admin.plans.store');
    Route::post('super-admin/plans/destroy',[SuperAdminPlanController::class, 'destroy'])->name('super_admin.plans.destroy');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
   //aax
   Route::get('listeLocataire', [HomeController::class, 'getLocataire']); 


    Route::get('/historique', [HomeController::class, 'historique'])->name('historique');
    Route::post('/historique', [HomeController::class, 'historique'])->name('chekhistorique');

});


Route::get('/verify/{token}', [\App\Http\Controllers\VerifyController::class, 'VerifyEmail'])->name('verify');

 //SEND THE VIEW FOR SENDING THE MAIL
      Route::get('forgot-password', [UtilisateurController::class, 'forgotPassword'])->name('getEmail');

      Route::get('forgot-password/{token}', [UtilisateurController::class, 'forgotPasswordValidate']);
// PAGE DE CODE
      Route::get('code-login', [UtilisateurController::class, 'code_login'])->name('code_login');



Route::group(['middleware' => ['auth']], function () {

      
      //CHANGE LE MOT DE PASSE FOR THE FIRST  TIME CONNECTED
      Route::get('change', [UtilisateurController::class, 'getPasswordChange'])->name('change');
     

});

//SOUMETTRE EMAIL POUR REINITIALISER SON PWD ONLINE OPTION
Route::post('forgot-password', [UtilisateurController::class, 'resetPassword'])->name('forgot-password');

//NEVER PASSWORD MUST DO IT FIRSTIME CONNECTED
Route::post('password-submit', [UtilisateurController::class, 'updatePassword'])->name('password_submit');

//SOUMETTRE LE CODE RECU
Route::post('code-submit', [UtilisateurController::class, 'code_submit'])->name('code_submit');
Route::post('send-login-otp', [UtilisateurController::class, 'sendLoginOtp'])->name('send_login_otp');

//SOUMETTRE LA MOD DU PWD
Route::post('changement', [UtilisateurController::class, 'reinitilisationPassword'])->name('changementPwd') ;



Route::group(['middleware' => ['auth']], function () {

        Route::resource('roles', RoleController::class);
});

Route::middleware('auth')->prefix('gerer-user')->group(function () {

   Route::get('utilisateur', [UtilisateurController::class, 'index'])->name('getUserView');
   Route::delete('destroy/{id}', [UtilisateurController::class, 'destroy'])->name('supprime');
   Route::get('add', [UtilisateurController::class, 'create'])->name('addUser');
   Route::post('save', [UtilisateurController::class, 'store'])->name('saveUser');
   Route::get('edit/{id}', [UtilisateurController::class, 'edit'])->name('editView');
   Route::post('modifie', [UtilisateurController::class, 'update'])->name('modifieUser');



});



Route::middleware('auth')->group(function () {

    #PARAMETRAGE
    Route::get('/parametrage', [ParametreController::class, 'index'])->name('parametrage');
    Route::post('add', [ParametreController::class, 'create'])->name('store_param');
    Route::post('add-anneexe', [ParametreController::class, 'storeAnnexe'])->name('store_annexe');
    Route::post('destroy', [ProprietaireController::class, 'destroy'])->name('destroy_proprio');
    Route::post('update-annexe', [ParametreController::class, 'updateAnnexe'])->name('update_annexe');
    Route::post('delete-annexe', [ParametreController::class, 'destroyAnnexe'])->name('destroy_annexe');

    # POURCENTAGE DE GESTION
    Route::post('pourcentage-general', [ParametreController::class, 'storePourcentageGeneral'])->name('store_pourcentage_general');
    Route::post('pourcentage-toggle', [ParametreController::class, 'togglePourcentage'])->name('toggle_pourcentage');
    Route::post('pourcentage-groupe', [ParametreController::class, 'storeGroupePourcentage'])->name('store_pourcentage_groupe');
    Route::post('pourcentage-groupe-update', [ParametreController::class, 'updateGroupePourcentage'])->name('update_pourcentage_groupe');
    Route::post('pourcentage-groupe-delete', [ParametreController::class, 'destroyGroupePourcentage'])->name('destroy_pourcentage_groupe');
    Route::get('/api/get-pourcentage-proprietaire', [ParametreController::class, 'getPourcentageForProprietaire'])->name('api.get_pourcentage_proprietaire');

    # MODELE DE CONTRAT
    Route::post('contrat-config', [ParametreController::class, 'storeContratConfig'])->name('store_contrat_config');
    Route::post('contrat-config-reset', [ParametreController::class, 'resetContratConfig'])->name('reset_contrat_config');

    # API pour la gestion centralisée de l'agence active
    Route::post('/api/set-active-annexe', [ParametreController::class, 'setActiveAnnexe'])->name('api.set_active_annexe');
    Route::get('/api/get-active-annexe', [ParametreController::class, 'getActiveAnnexe'])->name('api.get_active_annexe');
    
    #PROFILE
    Route::get('/profile',[HomeController::class, 'profile'])->name('profileView');
    Route::post('update-profile', [HomeController::class, 'updateProfile'])->name('modifier_profile');
    Route::post('change-password', [HomeController::class, 'updatePassword'])->name('modifierPassword');

    # GESTION DES PLANS ET ABONNEMENTS
    Route::prefix('plans')->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('plans.index');
        Route::get('/all', [PlanController::class, 'getPlans'])->name('plans.all');
        Route::get('/current', [PlanController::class, 'getCurrentPlan'])->name('plans.current');
        Route::get('/check-maison', [PlanController::class, 'checkMaisonLimit'])->name('plans.check-maison');
        Route::get('/check-annexe', [PlanController::class, 'checkAnnexeLimit'])->name('plans.check-annexe');
        Route::get('/usage-stats', [PlanController::class, 'getUsageStats'])->name('plans.usage-stats');
        Route::post('/change', [PlanController::class, 'changePlan'])->name('plans.change');
    });

});

Route::middleware(['auth'])->group(function () {
    
    // ============================================
    // ROUTES PROPRIÉTAIRES
    // ============================================
    
    // Afficher la liste des propriétaires
    Route::get('/proprietaires', [ProprietaireController::class, 'index'])
        ->name('proprietaires.index')
        ->middleware('can:Consulter-proprietaire');
    
    // Guide (si nécessaire)
    Route::get('/guide', [ProprietaireController::class, 'guide'])
        ->name('guide');
    
    // ⭐ Créer un nouveau propriétaire (AJAX)
    Route::post('/proprietaires/create', [ProprietaireController::class, 'create'])
        ->name('create_propre')
        ->middleware('can:ajoute-proprietaire');
    
    // ⭐ Mettre à jour un propriétaire (AJAX)
    Route::post('/proprietaires/update', [ProprietaireController::class, 'update'])
        ->name('update_proprio')
        ->middleware('can:modify-proprietaire');
    
    // ⭐ Supprimer un propriétaire (AJAX - soft delete)
    Route::post('/proprietaires/destroy', [ProprietaireController::class, 'destroy'])
        ->name('destroy_proprio')
        ->middleware('can:delete-proprietaire');
    
});

// Route::middleware('auth')->prefix('gerer-proprietaire')->group(function () {

//     // Route::get('create', [ProprietaireController::class, 'index'])->name('get_proprioView');
//     // Route::post('add', [ProprietaireController::class, 'create'])->name('store_propre');
//     // Route::post('destroy', [ProprietaireController::class, 'destroy'])->name('destroy_proprio');
//     // Route::post('update', [ProprietaireController::class, 'update'])->name('update_proprio');
    
//     Route::get('liste', [ProprietaireController::class, 'index'])->name('get_proprioNewView');
//     Route::post('/proprietaires/store', [ProprietaireController::class, 'store'])->name('store_propre');
// Route::post('/proprietaires/update', [ProprietaireController::class, 'update'])->name('update_proprio');
// Route::post('/proprietaires/destroy', [ProprietaireController::class, 'destroy'])->name('destroy_proprio');
// });

Route::middleware('auth')->prefix('gerer-maison')->group(function () {

    Route::get('create', [MaisonController::class, 'index'])->name('get_maisonView');
    Route::post('add', [MaisonController::class, 'store'])->name('store_house');
    Route::post('update', [MaisonController::class, 'update'])->name('update_house');
    Route::post('destroy-house', [MaisonController::class, 'destroy'])->name('destroy_house');
});



Route::middleware('auth')->prefix('gerer-chambre')->group(function () {

    Route::get('create', [ChambreController::class, 'index'])->name('get_chambreView');
    Route::post('add', [ChambreController::class, 'store'])->name('store_chambre');
    Route::post('update', [ChambreController::class, 'update'])->name('update_chambre');
   Route::post('destroy-chambre', [ChambreController::class, 'destroy'])->name('destroy_chambre');
});


Route::middleware('auth')->prefix('gerer-prix')->group(function () {

    Route::get('create', [PrixController::class, 'index'])->name('get_prixView');
    Route::post('add', [PrixController::class, 'store'])->name('store_prix');
    Route::post('update', [PrixController::class, 'update'])->name('update_prix');
    Route::post('destroy-prix', [PrixController::class, 'destroy'])->name('destroy_prix');

    # AJAX
    Route::get('numero_chambre', [PrixController::class, 'getNumeroChambre']);
    Route::get('type_chambre', [PrixController::class, 'getTypeChambre']);


});

Route::middleware('auth')->prefix('gerer-locataire')->group(function () {

    Route::get('create', [LocataireController::class, 'index'])->name('get_locataireView');
    Route::post('add', [LocataireController::class, 'store'])->name('store_locataire');
    Route::post('update', [LocataireController::class, 'update'])->name('update_locataire');
    Route::post('destroy-locataire', [LocataireController::class, 'destroy'])->name('destroy_locataire');

    # AJAX
    Route::get('numero_chambre', [LocataireController::class, 'getNumeroChambreForLocation']);
    Route::get('type_chambre', [LocataireController::class, 'getTypeChambre']);
    Route::get('get_prix', [LocataireController::class, 'getPrix']);

    Route::post('download-contrat', [HomeController::class, 'getcontratpdf'])->name('download_contrat');
    Route::get('api/locataires-contrat', [HomeController::class, 'getLocatairesForContrat'])->name('api.locataires_contrat');

});


Route::middleware('auth')->prefix('gerer-facture')->group(function () {

    Route::get('create', [FactureController::class, 'index'])->name('get_factureView');
    Route::post('add', [FactureController::class, 'store'])->name('store_facture');
    Route::post('update', [FactureController::class, 'update'])->name('update_facture');
    Route::post('destroy-paiement', [FactureController::class, 'destroy'])->name('destroy_facture');
    # FACTURE POUR AVANCE
    Route::get('/telecharge/{id}', [FactureController::class, 'factureAvance'])->name('telecharge');
    
    #FACTURE DE CHAQUE MOIS
    Route::get('/telecharge2/{id}',[FactureController::class, 'factureParMois'])->name('telecharge2');


    # AJAX
    Route::get('numero_chambre', [FactureController::class, 'getNumeroChambre']);
    Route::get('type_chambre', [FactureController::class, 'getTypeChambre']);


});


Route::middleware('auth')->group(function () {
    
    //PAGE GENERALE DES STATISTIQUE
    Route::get('gerer-statistique-list', [StatistiqueController::class, 'viewProprioMaison'])->name('get_statistiqueView');

   //GET PDF OF ALL PROPRIETOR
   Route::get('gerer-statistique-pdf', [StatistiqueController::class, 'getProprioHousePdf'])->name('getPdf');
   
   //GET HOUSE AND DETAIL BY PROPRITOR AJAX
   Route::get('houseStatistique', [StatistiqueController::class, 'getHouseChambreByProprio']); 
   //HIS PDF DOWNLOADING
    Route::get('/house-chambre/{id}',[StatistiqueController::class, 'getHouseChambrePdf'])->name('house-chambre');

   //GET LOCATAIRE BASED ON HOUSE NAME AJAX
   Route::get('locataireStatistique', [StatistiqueController::class, 'getHouseAndLocataire']);

   //HIS PDF
    Route::get('/house-locataire/{id}',[StatistiqueController::class, 'getHouseLocatairePdf'])->name('house-locataire');

});

   


Route::middleware('auth')->group(function () {
    //LA VUE DES STATISTIQUE DES RECUS
    Route::get('statistique-recu', [StatistiqueController::class, 'getRecuView'])->name('getRecu');
   //RECHERCHE PAR AJAX
   Route::get('statistique-facture-mois', [StatistiqueController::class, 'getFactureByDatePerMonths']);
   Route::get('statistique-facture-mois-nom', [StatistiqueController::class, 'getFactureByLocataireNamePerMonths']);
   Route::get('statistique-facture-avance', [StatistiqueController::class, 'getFactureByDateAvance']);
   Route::get('statistique-facture-avance-nom', [StatistiqueController::class, 'getFactureByLocataireNameAvance']);

});

//DOSSIER CLIENT ET PARCELLE

Route::middleware('auth')->group(function () {
    //LA VUE DES STATISTIQUES DOSSIER
  Route::get('statistique-dossier', [StatistiqueController::class, 'getviewDossier'])->name('getDossier');
  //AJAX TO GET STA CUSTOMERS
  Route::get('statistique-dossier-by-date', [StatistiqueController::class, 'getDossierClientByDate']);
  //DOWNLOAD PDF FOR CUSTOMERS
  Route::get('pdf-client-dossier/{date_debut}/{date_fin}', [StatistiqueController::class, 'getClientDossierPdf']);

  //AJAX TO ET STA PARCELLE
  Route::get('statistique-parcelle-by-date', [StatistiqueController::class, 'getDossierParcelleByDate']);
  

  //DOWNLOAD PDF FOR PARCELLE
  Route::get('pdf-parcelle-dossier/{date_debut2}/{date_fin2}', [StatistiqueController::class, 'getParcelleDossierPdf']);

});




Route::middleware('auth')->group(function () {
    //LA VUE DES STATISTIQUE DES FINANCES
    Route::get('finance', [StatistiqueController::class, 'getFinanceView'])->name('getFinance');
    //RETRIEVE PROPRIETOR PAYMENT PEER MONTH
    Route::get('propritor-payment', [StatistiqueController::class, 'getProprietaireSolde'])->name('propritor-payment');
    //PDF
    Route::get('pdf-solde-proprietor/{id}/{pourcentage}/{date_debut}/{date_fin}', [StatistiqueController::class, 'getPropriotorSoldePdf']);
    
    //RETRIEVE AGENCE PAYMENT PEER MONTH FOR AGENCY
    Route::get('agence-payment', [StatistiqueController::class, 'getAgenceSoldeByProprio'])->name('agence-payment');

    Route::get('pdf-solde-agence/{id2}/{pourcentage2}/{date_debut2}/{date_fin2}', [StatistiqueController::class, 'getAgencySoldePdf']);

    //RETRIEVE ALL PAYMENT FOR AGENCE
    Route::get('agence-payment-general', [StatistiqueController::class, 'getAllPaymentToAgenceSoldeByDate'])->name('agence-payment-general');

    Route::get('pdf-all-solde-agence/{pourcentage_general}/{date_debut_general}/{date_fin_general}', [StatistiqueController::class, 'getAllPaymentToAgencySoldePdf']);


});



    
Route::get('ssss/{ee}', [UtilisateurController::class, 'ssss'])->name('ssss');



//ROUTE DES PARCELLES
Route::middleware('auth')->group(function () {

  Route::get('parcelle', [ParcelleController::class, 'index'])->name('getViewParcelle');
  Route::post('add-parcelle', [ParcelleController::class, 'create'])->name('store_terrain');
  Route::post('update-parcelle', [ParcelleController::class, 'update'])->name('update_terrain');
  Route::post('delete-parcelle', [ParcelleController::class, 'delete'])->name('delete_terrain');
  Route::post('cloturer-parcelle', [ParcelleController::class, 'cloturer'])->name('cloturer_terrain');
});

//ROUTE DES CLIENTS
Route::middleware('auth')->group(function () {

  Route::get('client', [ClientController::class, 'index'])->name('getViewClient');
  Route::post('add-client', [ClientController::class, 'create'])->name('store_client');
  Route::post('update-client', [ClientController::class, 'update'])->name('update_client');
  Route::post('delete-client', [ClientController::class, 'delete'])->name('delete_client');
  Route::post('cloture-client', [ClientController::class, 'cloturer'])->name('cloture_client');
});

Route::middleware('auth')->group(function () {

    Route::get('liste-compte', [EntrepriseController::class, 'display_compte_entreprise'])->name('getViewCompte');
    Route::post('entreprise/change-plan', [EntrepriseController::class, 'changePlanAdmin'])->name('entreprise.change-plan');
    Route::post('entreprise/renouveler', [EntrepriseController::class, 'renouvelerAbonnement'])->name('entreprise.renouveler');

  });
  Route::get('blocage/{id}', [EntrepriseController::class, 'manage_compte'])->name('blocage');


  Route::middleware('auth')->prefix('publicite')->group(function () {

    Route::get('pub', [PubliciteController::class, 'display_pub'])->name('pub_displaying');
    Route::post('add', [PubliciteController::class, 'create'])->name('store_pub');
    Route::post('update', [PubliciteController::class, 'update_pub'])->name('update_pub');
    Route::post('destroy', [PubliciteController::class, 'destroy'])->name('destroy_pub');
    Route::post('add-image', [PubliciteController::class, 'addImage'])->name('pub_add_image');
    Route::post('remove-image', [PubliciteController::class, 'removeImage'])->name('pub_remove_image');

  });


  Route::middleware('auth')->prefix('notifications')->group(function () {
    Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread_count');
    Route::get('/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    Route::post('/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark_read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark_all_read');
  });

  Route::middleware('auth')->prefix('envoi-document')->group(function () {
    Route::get('/', [EnvoiDocumentController::class, 'index'])->name('envoi_document.index');
    Route::post('/envoyer', [EnvoiDocumentController::class, 'envoyer'])->name('envoi_document.envoyer');
    Route::get('/historique', [EnvoiDocumentController::class, 'historique'])->name('envoi_document.historique');
  });

  Route::middleware('auth')->post('comm-config', [ParametreController::class, 'storeCommConfig'])->name('store_comm_config');

  Route::middleware('auth')->prefix('whatsapp')->group(function () {
    Route::get('/status',     [WhatsAppController::class, 'status'])->name('whatsapp.status');
    Route::get('/qr',         [WhatsAppController::class, 'qr'])->name('whatsapp.qr');
    Route::get('/qr-image',   [WhatsAppController::class, 'qrImage'])->name('whatsapp.qr_image');
    Route::post('/connect',   [WhatsAppController::class, 'connect'])->name('whatsapp.connect');
    Route::post('/disconnect',[WhatsAppController::class, 'disconnect'])->name('whatsapp.disconnect');
  });


  


