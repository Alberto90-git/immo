@extends('layouts.template')
@section('content')
<div class="pagetitle">
    <h1>Gestion utilisateur</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item">Gestion utilisateur</li>
            <li class="breadcrumb-item active">Ajouter utilisateur</li>
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
                    @can('ajouter-utilisateur')
                        <a class="btn sbg1 shadow"  href="{{ route('addUser') }}">
                        <i class="ri-add-line"></i>Ajouter utilisateur</a>
                    @endcan
                    <table class="table datatable">
                        <thead class="bg-light text-capitalize">
                            <tr>
                               <th>Nom & prénom</th>
                               <th>Agence</th>
                                <th>status</th>
                                <th width="280px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($data))
                            @foreach ($data as $user)
                            <tr>
                                <td>{{ $user->nom }}   {{ $user->prenom }}</td>
                                <td>{{ get_annexee_name($user->idannexe_ref) }}</td>
                                <td>
                                    @if ($user->status == '')
                                    <label class="badge rounded-pill bg-success">Actif</label>
                                    @else
                                    <label class="badge rounded-pill bg-danger">Désactivé</label>
                                    @endif
                                </td>  
                                <td>
                                    @can('modifier-utilisateur')
                                    <a class="btn sbg1 shadow"  href="{{ route('editView',$user->id) }}"><i class="ri-edit-2-fill"></i></a>
                                    @endcan

                                    {!! Form::open(['method' => 'DELETE','route' => ['supprime', $user->id],'style'=>'display:inline']) !!}
                                    @can('desactive-utilisateur')

                                    @if ($user->status == '')
                                    {!! Form::submit('Désactivé', ['class' => 'btn btn-danger shadow']) !!}
                                    @else
                                    {!! Form::submit('Activé', ['class' => 'btn btn-success shadow']) !!}
                                    @endif
                                    @endcan
                                    {!! Form::close() !!}

                                    <button type="button" class="btn sbg1 shadow" data-bs-toggle="modal" data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                                        <i class="ri-zoom-in-line"></i>
                                    </button>
                                </td>

                            </tr>


                            <div class="modal fade" id="disablebackdrop{{ $loop->iteration }}" tabindex="-1" data-bs-backdrop="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header text-white" style="background-color: #012970;">
                                            <h5 class="modal-title center">Détails utilisateur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group list-group-flush">
                                                <h4 class="list-group-item"><label class="badge rounded-pill bg-primary">Email</label> {{ $user->email }}</h4>
                                                <h4 class="list-group-item"><label class="badge rounded-pill bg-primary">Grade</label> {{ $user->grade }}</h4>
                                                <h4 class="list-group-item"> <label class="badge rounded-pill bg-primary">Rôles: </label>
                                                    @if(!empty($user->getRoleNames()))
                                                        @foreach($user->getRoleNames() as $v)
                                                            <label >{{ $v }}</label>
                                                        @endforeach
                                                    @endif
                                                </h4>
                                            </ul><!-- End Clean list group -->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection