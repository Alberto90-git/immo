@extends('layouts.template')

@section('content')

<div class="pagetitle">
    <h1>Gestion rôle</h1>
    <nav>
        <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item">Gestion rôle</li>
            <li class="breadcrumb-item active">Liste</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <div class="pull-left">
                        <h4 class="center"></h4>
                    </div>
                    <div class="pull-right">
                        <a class="btn sbg1 shadow" href="{{ route('roles.index') }}">Retour</a>
                    </div>
                    <h4 class="header-title mt-3">Modification rôle</h4>


                    @if (count($errors) > 0)
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        <strong></strong> Veuillez verifier les informations <br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif


                    {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Nom rôle</strong> <span style="color: red;">*</span>
                                {!! Form::text('name', null, array('placeholder' => 'Nom rôle','class' => 'form-control','autocomplete' => 'off')) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <p class="text-center mb-3 card-title"> <b> <strong>PERMISSION SUR:</strong> </b> </p>

                            <div class="form-group">
                                <strong class="card-title"><u>Paramétrage</u></strong>
                                <br />
                                <br />
                                <div class="mb-4 row">
                                    @foreach($permissionParametrage as $permissionParametrage)
                                    <div class="col-md-3">
                                        <label> {{ Form::checkbox('permission[]', $permissionParametrage->id, in_array($permissionParametrage->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionParametrage->label }} </b> </label>
                                    </div>
                                    <br />
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">

                                <strong class="card-title"><u>Propriétaire</u></strong>
                                <br />
                                <br />

                                <div class=" mb-4 row ">

                                    @foreach($permissionProprio as $permissionProprio)
                                    <div class="col-md-3">
                                        <label> {{ Form::checkbox('permission[]', $permissionProprio->id, in_array($permissionProprio->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionProprio->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <strong class="card-title"><u>Maison</u></strong>
                                <br />
                                <br />
                                <div class=" mb-4 row ">
                                    @foreach($permissionMaison as $permissionMaison)
                                    <div class="col-md-3">

                                        <label> {{ Form::checkbox('permission[]', $permissionMaison->id, in_array($permissionMaison->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionMaison->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>



                            <div class="form-group">
                                <strong class="card-title"><u>Chambre</u></strong>
                                <br />
                                <br />
                                <div class=" mb-4 row ">
                                    @foreach($permissionChambre as $permissionChambre)
                                    <div class="col-md-3">

                                        <label> {{ Form::checkbox('permission[]', $permissionChambre->id, in_array($permissionChambre->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionChambre->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>




                            <div class="form-group">
                                <strong class="card-title"><u>Prix</u></strong>
                                <br />
                                <br />
                                <div class=" mb-4 row ">
                                    @foreach($permissionPrix as $permissionPrix)
                                    <div class="col-md-3">

                                        <label> {{ Form::checkbox('permission[]', $permissionPrix->id, in_array($permissionPrix->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionPrix->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="form-group">
                                <strong class="card-title"><u>Locataire</u></strong>
                                <br />
                                <br />
                                <div class=" mb-4 row ">
                                    @foreach($permissionLocataire as $permissionLocataire)
                                    <div class="col-md-3">

                                        <label> {{ Form::checkbox('permission[]', $permissionLocataire->id, in_array($permissionLocataire->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionLocataire->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="form-group">
                                <strong class="card-title"><u>Paiement</u></strong>
                                <br />
                                <br />
                                <div class=" mb-4 row ">
                                    @foreach($permissionPaiement as $permissionPaiement)
                                    <div class="col-md-3">

                                        <label> {{ Form::checkbox('permission[]', $permissionPaiement->id, in_array($permissionPaiement->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionPaiement->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <strong class="card-title"><u>Statistique</u></strong>
                                <br />
                                <br />
                                <div class=" mb-4 row ">
                                    @foreach($permissionStatistique as $permissionStatistique)
                                    <div class="col-md-3">

                                        <label> {{ Form::checkbox('permission[]', $permissionStatistique->id, in_array($permissionStatistique->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionStatistique->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>


                            


                            <div class="form-group">
                                <strong class="card-title"><u>Gestion des dossiers</u></strong>
                                <br />
                                <br />
                                <div class=" mb-4 row ">
                                    @foreach($permissionDossier as $permissionDossier)
                                    <div class="col-md-3">

                                        <label> {{ Form::checkbox('permission[]', $permissionDossier->id, in_array($permissionDossier->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $permissionDossier->label }} </b> </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>



                            <div class="form-group">
                                <strong class="card-title"><u>Permission rôle & utilisateur</u></strong>
                                <br />
                                <br />
                                <div class="row">
                                    @foreach($permission as $value)
                                    <div class="col-md-3">
                                        <label> {{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                            <b> {{ $value->label }} </b> </label>
                                    </div>
                                    <br />
                                    @endforeach
                                </div>
                            </div>
                             <br />

                           
                        </div>
                        <div class="form-group">
                                <button type="submit" class="btn sbg1 shadow">Enregistrer</button>
                            </div>

                        <!-- <button type="submit" class="btn btn-primary shadow">Update</button> -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection