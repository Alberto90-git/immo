@extends('layouts.template')

@section('content')

<div class="pagetitle">
      <h1>Accueil</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Accueil</li>
        </ol>
      </nav>
    </div>

<section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- End Sales Card -->

            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                  <h5 class="card-title text-center">Liste des directions</h5>

                  <table class="table datatable border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Entreprise</th>
                        <th scope="col">Nom & prénom</th>
                        <th scope="col">Grade</th>
                        <th scope="col">status</th>
                        <th scope="col">Bloqué/Débloqué</th>
                      </tr>
                    </thead>
                    <tbody>
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
                            <a class="btn sbg1 shadow"   href="{{ route('blocage',['id' =>  $items->id ]) }}">
                                <button class="btn">
                                    Bloc/Debloc
                                </button>
                            </a>
                          </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>

                </div>

              </div>
            </div>
            <!-- End Recent Sales -->



          </div>
        </div>
        <!-- End Left side columns -->




        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- End Sales Card -->

            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                  <h5 class="card-title text-center">Liste des annexes</h5>

                  <table class="table datatable border-primary">
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
            <!-- End Recent Sales -->



          </div>
        </div>
        <!-- End Left side columns -->

     


     
       

      </div>
      </div>
</section>

<script src="assets/vendor/echarts/echarts.min.js"></script>

@endsection
