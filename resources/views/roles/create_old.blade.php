@extends('layouts.template')

@section('content')

<div class="pagetitle">
    <h1>Gestion rôle</h1>
    <nav>
        <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item">Gestion rôle</li>
            <li class="breadcrumb-item active">Liste des permissions</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <div class="col-lg-12 col-ml-12">
                        <div class="row">
                            <div class="col-lg-12 margin-tb">
                                <div class="pull-left">
                                    <h4 class="center"></h4>
                                </div>
                                <div class="pull-right">
                                    <a class="btn sbg1 shadow" href="{{ route('roles.index') }}">Retour</a>
                                </div>
                            </div>
                        </div> <br>


                        @if (count($errors) > 0)
                        <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show">
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>    
                        <strong>Vérifier bien vos données</strong> <br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif


                        {!! Form::open(array('route' => 'roles.store','method'=>'POST')) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nom rôle</strong><span style="color: red;">*</span>
                                    {!! Form::text('name', null, array('placeholder' => 'Nom rôle','class' => 'form-control','autocomplete' => 'off')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <p class="text-center mb-3 card-title"> <b> <strong>PERMISSION SUR :</strong> </b> </p>

                                    <strong class="card-title"><u>Paramétrage</u></strong>
                                    <br />
                                    <br />

                                    <div class=" mb-4 row ">

                                        @foreach($permissionParametrage as $valueParams)
                                        <div class="col-md-3">

                                            <label class="text-dark">{{ Form::checkbox('permission[]', $valueParams->id, false, array('class' => 'name')) }}
                                                {{ $valueParams->label }}</label> </b> </label> <br>

                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">
                                    <strong class="card-title"><u>Propriétaire</u></strong>
                                    <br />
                                    <br />

                                    <div class=" mb-4 row ">

                                        @foreach($permissionProprio as $valueProprio)
                                        <div class="col-md-3">
                                            <label class="text-dark">{{ Form::checkbox('permission[]', $valueProprio->id, false, array('class' => 'name')) }}
                                                {{ $valueProprio->label }}</label> </b> </label> <br>

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

                                            <label class="text-dark">{{ Form::checkbox('permission[]', $permissionMaison->id, false, array('class' => 'name')) }}
                                                {{ $permissionMaison->label }}</label> </b> </label> <br>

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

                                            <label class="text-dark">{{ Form::checkbox('permission[]', $permissionChambre->id, false, array('class' => 'name')) }}
                                                {{ $permissionChambre->label }}</label> </b> </label> <br>

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

                                            <label class="text-dark">{{ Form::checkbox('permission[]', $permissionPrix->id, false, array('class' => 'name')) }}
                                                {{ $permissionPrix->label }}</label> </b> </label> <br>

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
                                            <label class="text-dark">{{ Form::checkbox('permission[]', $permissionLocataire->id, false, array('class' => 'name')) }}
                                                {{ $permissionLocataire->label }}</label> </b> </label> <br>

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
                                            <label class="text-dark">{{ Form::checkbox('permission[]', $permissionPaiement->id, false, array('class' => 'name')) }}
                                                {{ $permissionPaiement->label }}</label> </b> </label> <br>

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
                                            <label class="text-dark">{{ Form::checkbox('permission[]', $permissionStatistique->id, false, array('class' => 'name')) }}
                                                {{ $permissionStatistique->label }}</label> </b> </label> <br>

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
                                            <label class="text-dark">{{ Form::checkbox('permission[]', $permissionDossier->id, false, array('class' => 'name')) }}
                                                {{ $permissionDossier->label }}</label> </b> </label> <br>

                                        </div>
                                        @endforeach
                                    </div>
                                </div>


                                <div class="form-group">
                                    <strong class="card-title"><u>Gestion publicité</u></strong>
                                    <br />
                                    <br />

                                    <div class=" mb-4 row ">

                                        @foreach($permissionPub as $valuePubs)
                                        <div class="col-md-3">
                                            <label class="text-dark">{{ Form::checkbox('permission[]', $valuePubs->id, false, array('class' => 'name')) }}
                                                {{ $valuePubs->label }}</label> </b> </label> <br>

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
                                            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                                                {{ $value->label }}</label> </b> </label> <br>

                                        </div>
                                        @endforeach
                                    </div>
                                </div>


                                

                               

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn sbg1 shadow">Enregister</button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


@endsection