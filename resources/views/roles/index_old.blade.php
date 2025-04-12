@extends('layouts.template')

@section('content')

<div class="pagetitle">
    <h1>Gestion rôle</h1>
    <nav>
        <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item">Gestion rôle</li>
            <li class="breadcrumb-item active">Liste des rôles</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">

            <div class="card recent-sales overflow-auto">
                <div class="card-body">
                    <div class="pull-left">
                        <h4 class="center"></h4>
                    </div>
                    @can('ajouter-role')
                    <a class="btn sbg1 shadow" href="{{ route('roles.create') }}">
                        <i class="ri-add-line"></i>Ajouter un rôle</a>
                    @endcan
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th scope="col">Nom rôle</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key => $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @can('liste-role')
                                    <a class="btn btn-info shadow" href="{{ route('roles.show',$role->id) }}"><i class="ri-eye-line"></i></a>
                                    @endcan
                                    @can('modifier-role')
                                    <a class="btn sbg1 shadow" href="{{ route('roles.edit',$role->id) }}"><i class="ri-edit-2-fill"></i></a>
                                    @endcan
                                    @can('supprimer-role')
                                    {{-- {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                                            {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                                                        {!! Form::close() !!} --}}
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal{{$role->id}}">
                                        Supprimer
                                    </button>
                                    @endcan
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</section>
@endsection