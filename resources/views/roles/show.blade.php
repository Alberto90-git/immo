@extends('layouts.template')

@section('content')

@section('title')
<title>Gestion rôle</title>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span>Gestion rôle / Liste des permissions</h4>

        <div class="ms-3 demo-inline-spacing">
            <a href="{{ route('roles.index') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons bx bx-arrow-back"></span>&nbsp;
            </a>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group alert alert-info">
                <strong>Nom rôle: </strong>
                <b> {{ $role->name }} </b>
            </div>
        </div>
      
      <div class="card">
        <h5 class="card-header">Les permissions</h5>
        <div class="card-body">
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
@endsection