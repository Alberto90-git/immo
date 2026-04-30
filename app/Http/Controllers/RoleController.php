<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //      $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
    //      $this->middleware('permission:role-create', ['only' => ['create','store']]);
    //      $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
    //      $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('gestion-role');

        // $roles = Role::orderBy('id','DESC')->paginate(5);

        // return view('roles.index',compact('roles'))
        //     ->with('i', ($request->input('page', 1) - 1) * 5);
        $roles = Role::where('name','!=','Super Admin')
                      ->where('name','!=','Administrateur')
                      ->where('iddirectionRef_role',Auth::user()->iddirection_ref)
                      ->get();

        $i = 0;

        return view('roles.index')->with(['i' => $i, 'roles' => $roles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('gestion-role');

        $permission = Permission::where("group", "admin")->get();
        $permissionParametrage = Permission::where("group", "params")->get();
        $permissionProprio = Permission::where("group", "proprietaire")->get();
        $permissionMaison = Permission::where("group", "maison")->get();
        $permissionChambre = Permission::where("group", "chambre")->get();
        $permissionPrix = Permission::where("group", "prix")->get();
        $permissionLocataire = Permission::where("group", "locataire")->get();
        $permissionPaiement = Permission::where("group", "paiement")->get();
        $permissionStatistique = Permission::where("group", "statistique")->get();
        //$permissionMotPasse = Permission::where("group", "changePwd")->get();
        $permissionDossier = Permission::where("group", "dossiers")->get();
        $permissionPub   = Permission::where("group", "pubs")->get();
        $abonnement               = Permission::where("group", "abonnement")->get();
        $permissionEnvoi          = Permission::where("group", "envoi")->get();
        $permissionEtatDesLieux   = Permission::where("group", "etat_des_lieux")->get();
        $permissionMaintenance    = Permission::where("group", "maintenance")->get();
        $permissionAutomation     = Permission::where("group", "automation")->get();
        $permissionRecouvrement   = Permission::where("group", "recouvrement")->get();
        $permissionSignature      = Permission::where("group", "signature")->get();

        return view('roles.create', compact(
            'permission', 'permissionParametrage',
            'permissionProprio', 'permissionMaison', 'permissionChambre',
            'permissionPrix', 'permissionLocataire', 'permissionPaiement',
            'permissionStatistique', 'permissionDossier', 'permissionPub',
            'abonnement', 'permissionEnvoi', 'permissionEtatDesLieux',
            'permissionMaintenance', 'permissionAutomation', 'permissionRecouvrement',
            'permissionSignature'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('gestion-role');

        $validator = Validator::make($request->all(), [
            'name'       => 'required|unique:roles,name',
            'permission' => 'required',
        ], [
            'name.required'       => 'Le nom de la fonction est obligatoire.',
            'name.unique'         => 'Ce nom de fonction existe déjà.',
            'permission.required' => 'Veuillez sélectionner au moins une permission.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error'  => $validator->errors(),
            ]);
        }

        $role = Role::create([
            'name'                => $request->input('name'),
            'iddirectionRef_role' => Auth::user()->iddirection_ref,
        ]);
        $role->syncPermissions($request->input('permission'));

        activity()->performedOn(new Role())
            ->causedBy(Auth::user()->id)
            ->withProperties(['old' => [], 'new' => [
                'name'        => $request->input('name'),
                'permissions' => $request->input('permission', []),
            ]])
            ->log('Ajout du fonction ' . $request->input('name') . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

        return response()->json([
            'status'  => true,
            'message' => 'La fonction « ' . $request->input('name') . ' » a été ajoutée avec succès.',
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('gestion-role');

        $id = decrypt_id($id);
        abort_if(!$id, 404);
        $role = Role::findOrFail($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('gestion-role');

        $id = decrypt_id($id);
        abort_if(!$id, 404);
        $role = Role::findOrFail($id);

        $permission = Permission::where("group", "admin")->get();
        $permissionParametrage = Permission::where("group", "params")->get();
        $permissionProprio = Permission::where("group", "proprietaire")->get();
        $permissionMaison = Permission::where("group", "maison")->get();
        $permissionChambre = Permission::where("group", "chambre")->get();
        $permissionPrix = Permission::where("group", "prix")->get();
        $permissionLocataire = Permission::where("group", "locataire")->get();
        $permissionPaiement = Permission::where("group", "paiement")->get();
        $permissionStatistique = Permission::where("group", "statistique")->get();
        //$permissionMotPasse = Permission::where("group", "changePwd")->get();
        $permissionDossier = Permission::where("group", "dossiers")->get();
        $permissionPub   = Permission::where("group", "pubs")->get();
        $abonnement               = Permission::where("group", "abonnement")->get();
        $permissionEnvoi          = Permission::where("group", "envoi")->get();
        $permissionEtatDesLieux   = Permission::where("group", "etat_des_lieux")->get();
        $permissionMaintenance    = Permission::where("group", "maintenance")->get();
        $permissionAutomation     = Permission::where("group", "automation")->get();
        $permissionRecouvrement   = Permission::where("group", "recouvrement")->get();
        $permissionSignature      = Permission::where("group", "signature")->get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('roles.edit', compact(
            'role', 'rolePermissions', 'permission', 'permissionParametrage',
            'permissionProprio', 'permissionMaison', 'permissionChambre',
            'permissionPrix', 'permissionLocataire', 'permissionPaiement',
            'permissionStatistique', 'permissionDossier', 'permissionPub',
            'abonnement', 'permissionEnvoi', 'permissionEtatDesLieux',
            'permissionMaintenance', 'permissionAutomation', 'permissionRecouvrement',
            'permissionSignature'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('gestion-role');

        // Déchiffrer l'ID (priorité au champ caché du formulaire, sinon paramètre de route)
        $realId = decrypt_id($request->input('role_id') ?? $id);
        abort_if(!$realId, 404);

        $validator = Validator::make($request->all(), [
            'name'       => 'required|unique:roles,name,' . $realId,
            'permission' => 'required',
        ], [
            'name.required'       => 'Le nom de la fonction est obligatoire.',
            'name.unique'         => 'Ce nom de fonction existe déjà.',
            'permission.required' => 'Veuillez sélectionner au moins une permission.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error'  => $validator->errors(),
            ]);
        }

        $role = Role::findOrFail($realId);
        $oldRoleName = $role->name;
        $oldPermissions = $role->permissions->pluck('name')->toArray();
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));

        activity()->performedOn(new Role())
            ->causedBy(Auth::user()->id)
            ->withProperties(['old' => [
                'name'        => $oldRoleName,
                'permissions' => $oldPermissions,
            ], 'new' => [
                'name'        => $request->input('name'),
                'permissions' => $request->input('permission', []),
            ]])
            ->log('Modification du fonction ' . $request->input('name') . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

        return response()->json([
            'status'  => true,
            'message' => 'La fonction « ' . $request->input('name') . ' » a été modifiée avec succès.',
        ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('gestion-role');

        $id = decrypt_id($id);
        abort_if(!$id, 404);
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Fonction est supprimée avec succès');
    }
}
