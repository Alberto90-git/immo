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

        @php
          $liste = get_annexe_liste();
        @endphp

      @can('Is_admin')

          
              <div class="modal-body sbg1">
                <div class="row mb-3">
                  <div class="col-sm-10">
                    <select class="form-selectss" name="annexe_id"  id="annexe_id" aria-label="multiple select example">
                      <option value="" disabled selected>--Filtrer par annexe--</option>
                      @if(isset($liste))
                        @foreach($liste as $list)
                          <option value="{{ $list->idannexes }}">{{ $list->designation }}</option>
                        @endforeach 
                      @endif
                    </select>
                  </div>
                </div>
              </div>
         
      @endcan

<section class="section dashboard">

  
      <div class="row">

        @if(Auth::user()->is_admin != 1)
        <!-- simple user -->
        <div class="col-lg-12">
          <div class="row">

            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                  <h5 class="card-title text-center">Liste de tous les locataires</h5>

                  <table class="table border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Maison</th>
                        <th scope="col">N° chambre</th>
                        <th scope="col">Locataire</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Profession</th>
                        <th scope="col">Date d'entrée</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($data['locataire']))
                       @foreach($data['locataire'] as $items)
                        <tr>
                          <td>{{ $items->nom_maison }}</td>
                          <td>{{ $items->numero_chambre }}</td>
                          <td>{{ $items->nom }} {{ $items->prenom }}</td>
                          <td>{{ $items->telephone }}</td>
                          <td>{{ $items->profession }}</td>
                          <td>{{ $items->date_entree }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>

                 
                </div>

              </div>
            </div>
            <!-- End Recent Sales -->


            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                  <h5 class="card-title text-center">Liste de tous les propriétaires et leurs maisons</h5>

                  <table class="table border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Nom & prénom</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Adresse</th>
                        <th scope="col">Maison</th>
                        <th scope="col">Quartier</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($data['proprioMaison']))
                       @foreach($data['proprioMaison'] as $items)
                        <tr>
                          <td>{{ $items->nom }}  {{ $items->prenom }}</td>
                          <td>{{ $items->telephone }}</td>
                          <td>{{ $items->adresse }}</td>
                          <td>{{ $items->nom_maison }}</td>
                          <td>{{ $items->quartier }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>

                </div>

              </div>
            </div>

          </div>
        </div>
        <!-- simple user -->
        @endif


        @can('Is_admin')

          <!-- Left side columns -->
          <div class="col-lg-12">
            <div class="row">


              <!-- TOTAL -->
              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Total propriétaire</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white">{{ $data['nombreProprio'] }}</h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Total maison</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white">{{ $data['nombreMaison'] }}</h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>


              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Total locataire</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white">{{ $data['nombreLocataire'] }}</h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Total chambre</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white">{{ $data['nombreChambre'] }}</h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            

              <!-- TOTAL -->

            

              <!-- Sales Card -->
              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Nbr de propriétaire</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white"  id="nombre_proprio"></h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Nbr de maison</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white" id="nombre_maison"></h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>


              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Nbr de locataire</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white" id="nombre_locataire"></h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <div class="col-xxl-3 col-md-3">
                <div class="card info-card sales-card sbg1">

                  <div class="card-body">
                    <h5 class="card-title text-white">Nbr de chambre</h5>

                    <div class="d-flex align-items-center">
                      <div class="ps-3">
                        <h6 class=" text-white" id="nombre_chambre"></h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            

              <!-- End Sales Card -->

              <div class="col-12">
                <div class="card recent-sales overflow-auto">
                  <div class="card-body">
                    <h5 class="card-title text-center">Liste de tous les locataires</h5>

                    <table class="table border-primary">
                      <thead>
                        <tr>
                          <th scope="col">Maison</th>
                          <th scope="col">N° chambre</th>
                          <th scope="col">Locataire</th>
                          <th scope="col">Téléphone</th>
                          <th scope="col">Profession</th>
                          <th scope="col">Date d'entrée</th>
                        </tr>
                      </thead>
                      <tbody id="list_locataire">
                        
                      </tbody>
                    </table>

                  
                  </div>

                </div>
              </div>
              <!-- End Recent Sales -->


              <div class="col-12">
                <div class="card recent-sales overflow-auto">
                  <div class="card-body">
                    <h5 class="card-title text-center">Liste de tous les propriétaires et leurs maisons</h5>

                    <table class="table border-primary">
                      <thead>
                        <tr>
                          <th scope="col">Nom & prénom</th>
                          <th scope="col">Téléphone</th>
                          <th scope="col">Adresse</th>
                          <th scope="col">Maison</th>
                          <th scope="col">Quartier</th>
                        </tr>
                      </thead>
                      <tbody id="list_proprio">
                        {{-- @if(isset($data['proprioMaison']))
                        @foreach($data['proprioMaison'] as $items)
                          <tr>
                            <td>{{ $items->nom }}  {{ $items->prenom }}</td>
                            <td>{{ $items->telephone }}</td>
                            <td>{{ $items->adresse }}</td>
                            <td>{{ $items->nom_maison }}</td>
                            <td>{{ $items->quartier }}</td>
                          </tr>
                          @endforeach
                          @endif --}}
                      </tbody>
                    </table>

                  </div>

                </div>
              </div>

            </div>
          </div>
          <!-- End Left side columns -->
        @endcan

     
          <!-- Website Traffic -->
        <div class="col-xxl-4 col-md-6">
          <div class="card">
            <!--<div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>  
            </div> -->

            <div class="card-body pb-0">
              <h5 class="card-title">Statistique sur les locataires par quartiers</h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>


            </div>
            </div>
          </div>
          <!-- End Website Traffic -->


        <div class="col-xxl-8 col-md-6">
           <!-- Recent Activity -->
           <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>
            <div class="card-body">
              <h5 class="card-title">Taux d'évolution des paiements par quartier</h5>

              <div class="activity">

                <div class="activity-item d-flex">
                  <div class="activite-label">25</div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    Cotonou<span> <strong>=></strong> 100%</span>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">56</div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    Godomey<span> <strong>=></strong> 100%</span>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">2</div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    Cocotomey<span> <strong>=></strong> 100%</span>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">12</div>
                  <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                  <div class="activity-content">
                    Cococodj <span> <strong>=></strong> 100%</span>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">22</div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content">
                    Abomey-calavi <span> <strong>=></strong> 100%</span>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">4</div>
                  <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                  <div class="activity-content">
                    Hêvie <span> <strong>=></strong> 100%</span>
                  </div>
                </div><!-- End activity item-->

              </div>

            </div>
          </div>
          <!-- End Recent Activity -->
        </div>
       

      </div>
      </div>
</section>

<script>

    $('#annexe_id').on('change', function(e) {
      var annexe_id = $(this).val();

      if (annexe_id === null) {
        alert('Merci de sélectionner un nom');
        return false;
      } else {
        $.ajax({
          url: '{{ url('listeLocataire') }}',
          data: { annexe_id: annexe_id },
          type: 'GET',
          cache: false,
          dataType: 'json',
          success: function(data) {

            var listData = JSON.parse(data.list);

            if (listData && Object.keys(listData).length > 0) {
              var pieChartData = [];

              $.each(listData, function(city, value) {
                pieChartData.push({
                  value: value,
                  name: city
                });
              });

              echarts.init(document.querySelector("#trafficChart")).setOption({
                tooltip: {
                  trigger: 'item'
                },
                // legend: {
                //   top: '-17%',
                //   left: 'center'
                // },
                series: [{
                  name: 'locataire / ville',
                  type: 'pie',
                  radius: ['40%', '70%'],
                  avoidLabelOverlap: false,
                  label: {
                    show: false,
                    position: 'center'
                  },
                  emphasis: {
                    label: {
                      show: true,
                      fontSize: '18',
                      fontWeight: 'bold'
                    }
                  },
                  labelLine: {
                    show: false
                  },
                  data: pieChartData 
                }]
              });
            } else {
              console.error('Pas de donnée');
            }
          },
          error: function(error) {
            console.error('An error occurred', error);
          },
        });
      }
    });



    $(document).ready(function() {
        swal({
            title: 'Bienvenue sur votre espace de travail !!',
            text: '{{ Auth::user()->nom }} {{ Auth::user()->prenom }}',
            icon: 'success',
            button: {
                text: "C'est parti !",
                className: "btn btn-primary"
            },
            timer: 5000,
            buttonsStyling: true,
            customClass: {
                popup: 'animated bounceInDown',
            },
            background: '#f0f0f0',
        });
    });






$('#annexe_id').on('change',function(e)
{
      //alert($(this).val());

      var annexe_id = $(this).val();

        if(annexe_id === null )
        {
            alert('Merci de sélectionner un nom');
            return false;
        }
        else
        {
          // alert(code_banque);
            return $.ajax
            ({
                url: '{{ url('listeLocataire') }}',
                data: {annexe_id:annexe_id},
                type: 'GET',
                cache: false,
                dataType: 'json',
                success: function (data) {
                  //console.log(data.list);
                  
                    //$('#echeance').val(data.echeance);
                    $('#list_locataire').html(data.getlist);
                    $('#list_proprio').html(data.getlist2);
                    $('#nombre_proprio').html(data.nombre_proprio);
                    $('#nombre_maison').html(data.nombre_maison);
                    $('#nombre_locataire').html(data.nombre_locataire);
                    $('#nombre_chambre').html(data.nombre_chambre);

                    
                   // $('#pdfRecu').html(data.valeur);  
                    //$('#tableData').append(data.table_data);
                },
                error:function(data)
                {
                  //alert(data.infor_proprio);

                },
           });
        }
});
</script>

<script src="assets/vendor/echarts/echarts.min.js"></script>


@endsection
