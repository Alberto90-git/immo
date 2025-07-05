@extends('layouts.template')


@section('content')

    @section('title')
    <title>Gestion utilisateur</title>
    @endsection


    @if (Session::has("success"))
      <div class="col-md-6 p-4">
        <div class="toast-container">
        <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">SUCCES</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              {{ Session::get('success') }}
            </div>
        </div>
        </div>
      </div>
    @elseif (Session::has("error"))
        <div class="col-md-6 p-4">
            <div class="toast-container">
            <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">ERREUR</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                  {{ Session::get('error') }}
                </div>
            </div>
            </div>
        </div>
    @endif
    
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil/Gestion utilisateur/</span>Ajouter utilisateur </h4>

    <div class="ms-3 demo-inline-spacing">
        <a href="{{ route('addUser') }}" class="btn rounded-pill btn-primary">
            <span class="bx bx-plus"></span>&nbsp; Ajouter utilisateur
        </a>
    </div> <br>  


   

      <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">Liste des utilisateurs</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
                <th>Agence</th>
                <th>Nom & prénom</th>
                 <th>status</th>
                 <th width="280px">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('Consulter-proprietaire')
              @if(isset($data))
                @foreach($data as $user)  
                  <tr>
                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ get_annexee_name($user->idannexe_ref) }}</strong></td>
                    <th scope="row">{{ $user->nom }}  {{ $user->prenom }}</th>
                    <td>
                        @if ($user->status == '')
                        <label class="badge rounded-pill bg-success">Actif</label>
                        @else
                        <label class="badge rounded-pill bg-danger">Désactivé</label>
                        @endif
                    </td>  

                    <td>
                        @can('modifier-utilisateur')
                        <a class="btn rounded-pill btn-primary"  href="{{ route('editView',$user->id) }}"><i class="bx bx-edit-alt me-1"></i></a>
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

                        <button type="button" class="btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                            <i class="bx bx-zoom-in me-1"></i>
                        </button>
                    </td>


                    {{-- <td>
                      @can('modify-proprietaire')
                      <a class="btn rounded-pill btn-primary" 
                      title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                          <i class="bx bx-edit-alt me-1"></i>
                        </a>
                      @endcan
                  
                      @can('delete-proprietaire')
                      <a class="btn rounded-pill btn-danger"
                      title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                            <i class="bx bx-trash me-1"></i>
                        </a>
                      @endcan
                  </td> --}}
                  
                  </tr>


                  <div class="modal fade" id="disablebackdrop{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel1">Détails utilisateur</h5>
                          <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                          ></button>
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
                            </ul>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Ferme
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>





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
                                    </ul>
                                    <!-- End Clean list group -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
              @endif
            @endcan
          </tbody>
        </table>
      </div>
    </div>

@endsection