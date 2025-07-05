@extends('layouts.template')

@section('content')

  @section('title')
    <title>Accueil</title>
  @endsection

<div class="container-xxl flex-grow-1 container-p-y">
    @php
      $liste = get_annexe_liste();
    @endphp

    @can('Is_admin')
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-body">
              <label></label>
              <label for="inputPassword4" class="form-label text-center">Filtrer par Annee</label>
              <select class="form-select" name="annexe_id"  id="annexe_id" aria-label="multiple select example">
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
      </div>
    @endcan

    @if(Auth::user()->is_admin != 1)

      <div class="card">
        <h5 class="card-header text-center">Liste de tous les locataires</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
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
      <br/>


      <div class="card">
        <h5 class="card-header text-center">Liste de tous les propriétaires et leurs maisons</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
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
    @endif

    @can('Is_admin')

      <br/>
      <div class="row">
        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total propriétaire : {{ $data['nombreProprio'] }}</h5>
            </div>
          </div>
        </div>


        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total maison : {{ $data['nombreMaison'] }}</h5>
            </div>
          </div>
        </div>



        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total locataire : {{ $data['nombreLocataire'] }}</h5>
            </div>
          </div>
        </div>


        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total chambre : {{ $data['nombreChambre'] }}</h5>
            </div>
          </div>
        </div>

      
        
      </div>



      <br/>
      <div class="row">
        
        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de propriétaire</h5>
              <p class="card-text"  id="nombre_proprio"></p>
            </div>
          </div>
        </div>


        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de maison</h5>
              <p class="card-text"  id="nombre_maison"></p>
            </div>
          </div>
        </div>




        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de locataire</h5>
              <p class="card-text"  id="nombre_locataire"></p>
            </div>
          </div>
        </div>



        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de chambre</h5>
              <p class="card-text"  id="nombre_chambre"></p>
            </div>
          </div>
        </div>


      </div>


      <div class="card">
        <h5 class="card-header text-center">Liste de tous les locataires</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
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
      <br/>




      <div class="card">
        <h5 class="card-header text-center">Liste de tous les propriétaires et leurs maisons</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
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
            </tbody>
          </table>
        </div>
      </div>
      <br/>

    @endcan



</div>


    
   
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

  @endsection