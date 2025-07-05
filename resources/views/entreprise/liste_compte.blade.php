@extends('layouts.template')

@section('content')
  @section('title')
  <title>Gestion entreprise</title>
  @endsection


<div class="container-xxl flex-grow-1 container-p-y">
    

    


      <div class="card">
        <h5 class="card-header text-center">Liste des directions</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
            <thead>
              <tr>
                <th scope="col">Entreprise</th>
                <th scope="col">Nom & prénom</th>
                <th scope="col">Grade</th>
                <th scope="col">status</th>
                <th scope="col">Bloqué/Débloqué</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @if(isset($data))
              @foreach($data as $items)
               <tr>
                 <td>{{ $items->designation }}</td>
                 <td>{{ $items->nom }} {{ $items->prenom }}</td>
                 <td>{{ $items->grade }}</td>
                 <td>
                   @if (empty($items->blocage_entreprise))
                       <label class="badge rounded-pill bg-success">Actif</label>
                   @else
                       <label class="badge rounded-pill bg-danger">Désactivé</label>
                   @endif
                 </td>
                 <td>
                   <a class="btn btn-primary"   href="{{ route('blocage',['id' =>  $items->id ]) }}">
                           Bloc/Debloc
                   </a>
                 </td>
               </tr>
               @endforeach
               @endif
            </tbody>
          </table>
        </div>
      </div>
      <br/>


      <div class="card">
        <h5 class="card-header text-center">Liste des annexes</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
            <thead>
              <tr>
                <th scope="col">Entreprise</th>
                <th scope="col">Agence</th>
                <th scope="col">status</th>
            </tr>
            </thead>
            <tbody>
              @if(isset($dataannexe))
                        @foreach($dataannexe as $items)
                            <tr>
                                <td>{{ $items->designation }}</td>
                                <td>{{ get_status_entreprise($items->iddirection_ref,$items->idannexes) }}</td>
                                <td>
                                @if (empty($items->blocage_annexe))
                                    <label class="badge rounded-pill bg-success">Actif</label>
                                @else
                                    <label class="badge rounded-pill bg-danger">Désactivé</label>
                                @endif
                                </td>
                            </tr>
                       @endforeach
                    @endif
            </tbody>
          </table>
        </div>
      </div>

</div>

    
@endsection