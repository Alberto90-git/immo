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

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">

            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <div class="row mt-2 mb-2">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h4 class="center"></h4>
                            </div>
                            <div class="pull-right">
                                <a class="btn sbg1 shadow" href="{{ route('roles.index') }}">Retour</a>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group alert alert-info">
                                <strong>Nom rôle: </strong>
                                <b> {{ $role->name }} </b>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                 <p class="text-center mb-3 card-title"> <b> <strong>PERMISSION SUR :</strong> </b> </p>
                                 
                                @if(!empty($rolePermissions))
                                <div class="row">
                                    @foreach($rolePermissions as $v)
                                    <div class="col-md-3">
                                        <i class="fa fa-check"></i> <label class="label label-success"> <b> {{ $v->label }} </b> </label>

                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection