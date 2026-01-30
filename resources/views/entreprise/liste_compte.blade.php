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
                <th scope="col">Plan</th>
                <th scope="col">Prix</th>
                <th scope="col">Inscription</th>
                <th scope="col">Fin abonnement</th>
                <th scope="col">Statut</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @if(isset($data))
              @foreach($data as $items)
               <tr>
                 <td>{{ $items->designation }}</td>
                 <td>{{ $items->nom }} {{ $items->prenom }}</td>
                 <td>
                   <span class="badge bg-info">{{ $items->plan_nom ?? 'Aucun' }}</span>
                 </td>
                 <td>
                   @if($items->plan_prix)
                     {{ number_format($items->plan_prix, 0, ',', '.') }} XOF
                   @else
                     Gratuit
                   @endif
                 </td>
                 <td>
                   @if($items->abonnement_debut)
                     {{ \Carbon\Carbon::parse($items->abonnement_debut)->format('d/m/Y') }}
                   @else
                     -
                   @endif
                 </td>
                 <td>
                   @if($items->abonnement_fin)
                     {{ \Carbon\Carbon::parse($items->abonnement_fin)->format('d/m/Y') }}
                   @else
                     -
                   @endif
                 </td>
                 <td>
                   @if (empty($items->blocage_entreprise))
                       <label class="badge rounded-pill bg-success">Actif</label>
                   @else
                       <label class="badge rounded-pill bg-danger">En attente</label>
                   @endif
                 </td>
                 <td>
                   @if (empty($items->blocage_entreprise))
                     <a class="btn btn-sm btn-outline-danger" href="{{ route('blocage',['id' => $items->iddirection]) }}">
                       <i class="bx bx-lock me-1"></i>Bloquer
                     </a>
                   @else
                     <a class="btn btn-sm btn-success" href="{{ route('blocage',['id' => $items->iddirection]) }}">
                       <i class="bx bx-check me-1"></i>Valider
                     </a>
                   @endif
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
