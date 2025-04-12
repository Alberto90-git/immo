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
            <a href="{{ route('roles.create') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons bx bx-arrow-back"></span>&nbsp;
            </a>
        </div>
        <div class="table-responsive">
            
          <table class="table table-striped table-borderless border-bottom">
            <thead>
              <tr>
                <th class="text-nowrap">Nom rôle</th>
                <th class="text-nowrap">Actions</th>
              </tr> 
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>
                        @can('liste-role')
                        <a class="btn rounded-pill btn-primary" href="{{ route('roles.show',$role->id) }}"><i class="bx bx-zoom-out"></i></a>
                        @endcan
                        @can('modifier-role')
                        <a class="btn rounded-pill btn-primary" href="{{ route('roles.edit',$role->id) }}"><i class="tf-icons bx bx-edit"></i></a>
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
        
        <!-- /Notifications -->
      </div>


</div>

@endsection