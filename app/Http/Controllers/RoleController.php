<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
                      //->where('name','!=','Administrateur')
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
        $permissionPub = Permission::where("group", "pubs")->get();


        return view('roles.create', compact('permission', 'permissionParametrage',
         'permissionProprio','permissionMaison','permissionChambre','permissionPrix','permissionLocataire','permissionPaiement','permissionStatistique'
         ,'permissionDossier','permissionPub'));
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

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);   

        $role = Role::create(['name' => $request->input('name'),'iddirectionRef_role' => Auth::user()->iddirection_ref]);
        $role->syncPermissions($request->input('permission'));

        activity()->performedOn(new Role())
                           ->causedBy(Auth::user()->id)
                           ->log('Ajout du rôle '.$request->input('name').' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return redirect()->route('roles.index')
            ->with('success', 'Rôle ajouté avec succès');
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

        $role = Role::find($id);
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

        $role = Role::find($id);

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
        $permissionPub = Permission::where("group", "pubs")->get();


        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();


         return view('roles.edit', compact('role','rolePermissions','permission', 'permissionParametrage',
         'permissionProprio','permissionMaison','permissionChambre','permissionPrix','permissionLocataire',
         'permissionPaiement','permissionStatistique','permissionDossier','permissionPub'));
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

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        activity()->performedOn(new Role())
                           ->causedBy(Auth::user()->id)
                           ->log('Modification du rôle '.$request->input('name').' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return redirect()->route('roles.index')
            ->with('success', 'Rôle modifié avec succès');
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

        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Rôle supprimé avec succès');
    }
}
