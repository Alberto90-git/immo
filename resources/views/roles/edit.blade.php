@extends('layouts.template')


@section('content')

@section('title')
<title>Gestion rôle</title>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span>Gestion rôle / Modification</h4>

    <div class="card">
        <!-- Notifications -->
        <div class="ms-3 demo-inline-spacing">
            <a href="{{ route('roles.index') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons bx bx-arrow-back"></span>&nbsp;
            </a>
        </div>
        
        <div class="table-responsive">
            
          <table class="table table-striped table-borderless border-bottom">
            <thead>
              <tr>
                <th class="text-nowrap">MENU</th>
                <th class="text-nowrap text-center">Liste des permissions</th>
        </tr> 
            </thead>
            <tbody>
                {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}


                <div class="col-md-6">
                    <div class="form-group ms-3">
                        <label for="roleName" class="form-label">Nom rôle <span style="color: red;">*</span></label>
                        {!! Form::text('name', null, [
                            'placeholder' => 'Nom rôle',
                            'class' => 'form-control',
                            'id' => 'roleName',
                            'aria-describedby' => 'roleNameHelp',
                            'autocomplete' => 'off'
                        ]) !!}
                    </div>
                </div>
                


                <tr>
                    <td class="text-nowrap">Paramétrage</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllParametrage" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionParametrage as $permissionParametrage)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionParametrage->id, in_array($permissionParametrage->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-parametrage']) }}
                                        {{ $permissionParametrage->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td class="text-nowrap">Propriétaire</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllProprietaire" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionProprio as $permissionProprio)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionProprio->id, in_array($permissionProprio->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-proprietaire']) }}
                                        {{ $permissionProprio->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td class="text-nowrap">Maison</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllMaison" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionMaison as $permissionMaison)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionMaison->id, in_array($permissionMaison->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-maison']) }}
                                        {{ $permissionMaison->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Chambre</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllChambre" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionChambre as $permissionChambre)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionChambre->id, in_array($permissionChambre->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-chambre']) }}
                                        {{ $permissionChambre->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Prix</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllPrix" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionPrix as $permissionPrix)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionPrix->id, in_array($permissionPrix->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-prix']) }}
                                        {{ $permissionPrix->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Locataire</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllLocataire" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionLocataire as $permissionLocataire)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionLocataire->id, in_array($permissionLocataire->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-locataire']) }}
                                        {{ $permissionLocataire->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="text-nowrap">Paiement</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllPaiement" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionPaiement as $permissionPaiement)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionPaiement->id, in_array($permissionPaiement->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-paiement']) }}
                                        {{ $permissionPaiement->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Statistique</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllSta" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionStatistique as $permissionStatistique)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionStatistique->id, in_array($permissionStatistique->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-sta']) }}
                                        {{ $permissionStatistique->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Gestion des dossiers</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllDossier" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionDossier as $permissionDossier)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionDossier->id, in_array($permissionDossier->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-dossier']) }}
                                        {{ $permissionDossier->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Gestion publicité</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllPub" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionPub as $permissionPub)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionPub->id, in_array($permissionPub->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-pub']) }}
                                        {{ $permissionPub->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="text-nowrap">Permission rôle & utilisateur</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllUser" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permission as $permission)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permission->id, in_array($permission->id, $rolePermissions) ? true : false, ['class' => 'form-check-input permission-user']) }}
                                        {{ $permission->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                
            </tbody>
          </table>
        </div>
        <div class="card-body">
            <div class="mt-4">
            <button type="submit" class="btn btn-primary me-2">Enregister</button>
            {{-- <button type="reset" class="btn btn-outline-secondary">Effacer</button> --}}
            </div>
        </div>
        {!! Form::close() !!}
        <!-- /Notifications -->
      </div>


</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to handle "Tout sélectionner" logic for each group
        function setupSelectAll(section) {
            const selectAllCheckbox = document.getElementById(`selectAll${section}`);
            const permissionCheckboxes = document.querySelectorAll(`.permission-${section.toLowerCase()}`);

            // Update "Tout sélectionner" checkbox state based on individual checkboxes
            function updateSelectAllCheckbox() {
                const allChecked = Array.from(permissionCheckboxes).every(checkbox => checkbox.checked);
                selectAllCheckbox.checked = allChecked;
            }

            // "Tout sélectionner" event listener
            selectAllCheckbox.addEventListener('change', function () {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });

            // Individual checkboxes event listener
            permissionCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectAllCheckbox);
            });

            // Initial check in case all permissions are already selected
            updateSelectAllCheckbox();
        }

        // Initialize for each section
        setupSelectAll('Parametrage');
        setupSelectAll('Proprietaire');
        setupSelectAll('Maison');
        setupSelectAll('Chambre');
        setupSelectAll('Prix');
        setupSelectAll('Locataire');
        setupSelectAll('Paiement');
        setupSelectAll('Sta');
        setupSelectAll('Dossier');
        setupSelectAll('Pub');
        setupSelectAll('User');
    });
</script>

<script>




    // Fonction de sélection pour chaque groupe
    document.getElementById('selectAllParametrage').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-parametrage');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllProprietaire').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-proprietaire');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllMaison').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-maison');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllChambre').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-chambre');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllPrix').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-prix');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllLocataire').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-locataire');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllPaiement').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-paiement');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllSta').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-sta');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllDossier').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-dossier');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllPub').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-pub');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });

    document.getElementById('selectAllUser').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.permission-user');
        checkboxes.forEach(checkbox => checkbox.checked = this.checked);
    });
</script>
@endsection