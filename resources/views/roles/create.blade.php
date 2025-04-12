@extends('layouts.template')


@section('content')

@section('title')
<title>Gestion rôle</title>
@endsection


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span>Gestion rôle / Liste des permissions</h4>

    <div class="card">
        <!-- Notifications -->
        <div class="ms-3 demo-inline-spacing">
            <a href="{{ route('roles.index') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons bx bx-arrow-back"></span>&nbsp;
            </a>
        </div>


            

        @if (count($errors) > 0)
            <div class="col-md-6 p-4">
                <div class="toast-container">
                <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Quelques erreurs</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        @endif


        <div class="table-responsive">
            
            <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-nowrap">Menu</th>
                <th class="text-nowrap text-center">Liste des permissions</th>
             </tr> 
            </thead>
            <tbody>
                {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}

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
                            @foreach($permissionParametrage as $valueParams)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $valueParams->id, false, ['class' => 'form-check-input permission-parametrage']) }}
                                        {{ $valueParams->label }}
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
                            @foreach($permissionProprio as $valueProprio)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $valueProprio->id, false, ['class' => 'form-check-input permission-proprietaire']) }}
                                        {{ $valueProprio->label }}
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
                                        {{ Form::checkbox('permission[]', $permissionMaison->id, false, ['class' => 'form-check-input permission-maison']) }}
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
                                        {{ Form::checkbox('permission[]', $permissionChambre->id, false, ['class' => 'form-check-input permission-chambre']) }}
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
                                        {{ Form::checkbox('permission[]', $permissionPrix->id, false, ['class' => 'form-check-input permission-prix']) }}
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
                                        {{ Form::checkbox('permission[]', $permissionLocataire->id, false, ['class' => 'form-check-input permission-locataire']) }}
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
                                        {{ Form::checkbox('permission[]', $permissionPaiement->id, false, ['class' => 'form-check-input permission-paiement']) }}
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
                                        {{ Form::checkbox('permission[]', $permissionStatistique->id, false, ['class' => 'form-check-input permission-sta']) }}
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
                                        {{ Form::checkbox('permission[]', $permissionDossier->id, false, ['class' => 'form-check-input permission-dossier']) }}
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
                                        {{ Form::checkbox('permission[]', $permissionPub->id, false, ['class' => 'form-check-input permission-pub']) }}
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
                                        {{ Form::checkbox('permission[]', $permission->id, false, ['class' => 'form-check-input permission-user']) }}
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
            </div>
        </div>
        {!! Form::close() !!}
        <!-- /Notifications -->
      </div>


</div>



<script>

    function setupSelectAll(selectAllId, checkboxClass) {
        const selectAllCheckbox = document.getElementById(selectAllId);
        const checkboxes = document.querySelectorAll(`.${checkboxClass}`);

        selectAllCheckbox.addEventListener('change', function() {
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    }

    setupSelectAll('selectAllParametrage', 'permission-parametrage');
    setupSelectAll('selectAllProprietaire', 'permission-proprietaire');
    setupSelectAll('selectAllMaison', 'permission-maison');
    setupSelectAll('selectAllChambre', 'permission-chambre');
    setupSelectAll('selectAllPrix', 'permission-prix');
    setupSelectAll('selectAllLocataire', 'permission-locataire');
    setupSelectAll('selectAllPaiement', 'permission-paiement');
    setupSelectAll('selectAllSta', 'permission-sta');
    setupSelectAll('selectAllDossier', 'permission-dossier');
    setupSelectAll('selectAllPub', 'permission-pub');
    setupSelectAll('selectAllUser', 'permission-user');
</script>

@endsection