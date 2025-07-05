@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item">Statistique</li>
      <li class="breadcrumb-item active">Propriètaire,Maison,Chambre,Locataire</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
    
    <div class="card recent-sales overflow-auto">
            <div class="card-body">

              <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Propriétaire & maison</button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Maison & chambre</button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Maison & locataire</button>
                </li>

              </ul>

              <div class="tab-content pt-2" id="borderedTabJustifiedContent">

                <!-- DEBUT -->
                <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">

                  <div class="col-12">
                    <h5 class="card-title center">Liste de tous les propriétaires et leurs maisons</h5>
                    <a href="{{ route('getPdf') }}" class="btn btn-success rounded-pill ri-arrow-down-circle-fill shadow">Télécharger pdf
                    </a> <br/>
                    <table class="table datatable border-primary">
                      <thead>
                        <tr>
                          <th scope="col">Agence</th>
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
                            <td>{{ $items->designation }}</td>
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
                <!-- FIN -->

                <!-- DEBUT -->
                <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
                  <div class="col-12">

                    <div class="col-4 center">
                      <select class="form-select" id="proprioId" name="proprioId" aria-label="Floating label select example">
                        <option disabled selected>Selectionner un propriétaire</option>
                        @if(isset($data['proprio']))
                         @foreach($data['proprio'] as $item)
                          <option value="{{ $item->id }}">{{ $item->nom }}  {{ $item->prenom }} </option>
                         @endforeach
                        @endif
                      </select>
                    </div>

                    <h5 class="card-title center">Liste des maisons et chambres de <strong id="proprio_adrese"></strong>  </h5>
                  
                    
                     <p id="pdfRecu">
                       
                     </p> 
                    <br/>
                         

                    <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Agence</th>
                        <th scope="col">Maison</th>
                        <th scope="col">Quartier</th>
                        <th scope="col">N° chambre</th>
                        <th scope="col">Type chambre</th>
                        <th scope="col">Prix</th>
                      </tr>
                    </thead>
                    <tbody id="list_recu">
                       
                    </tbody>
                    </table>
                  </div>

                </div>

                <!-- FIN -->

                <div class="tab-pane fade" id="bordered-justified-contact" role="tabpanel" aria-labelledby="contact-tab">

                    <div class="col-4 center">
                      <select class="form-select" id="maison_choisie" name="maison_choisie" aria-label="Floating label select example">
                        <option disabled selected>Selectionner une maison</option>
                        @if(isset($data['house']))
                         @foreach($data['house'] as $item)
                          <option value="{{ $item->id }}">{{ $item->nom_maison }}</option>
                         @endforeach
                        @endif
                      </select>
                    </div>

                     <h5 class="card-title" style="margin: auto;">Liste des locataires de <strong id="house_adrese_recu"></strong></h5>
                   
                    </a> <p id="pdfRecuLocataire">
                       
                     </p>
                    <br/>

                    <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Agence</th>
                        <th scope="col">N° chambre</th>
                        <th scope="col">Type chambre</th>
                        <th scope="col">Locataire</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Avance</th>
                        <th scope="col">Date d'entrée</th>
                      </tr>
                    </thead>
                    <tbody id="list_locataire_recu">
                       
                    </tbody>
                    </table>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
</section>


<script>

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });   


     $('#maison_choisie').on('change',function(e)
    {
      //alert($(this).val());

      var maison_choisie = $(this).val();

        if(maison_choisie === null )
        {
            alert('Merci de sélectionner un nom');
            return false;
        }
        else
        {
          // alert(code_banque);
            return $.ajax
            ({
                url: '{{ url('locataireStatistique') }}',
                data: {house_recu:maison_choisie},
                type: 'GET',
                cache: false,
                dataType: 'json',
                success: function (data) {
                    //$('#echeance').val(data.echeance);
                    $('#list_locataire_recu').html(data.list_locataire);
                    $('#house_adrese_recu').html(data.infor_house) ;
                    $('#pdfRecuLocataire').html(data.valeur2) ;
                    //$('#tableData').append(data.table_data);
                },
                error:function(data) 
                {
                  //alert(data.infor_proprio);

                },
           });
        }
    });



    $('#proprioId').on('change',function(e)
    {
      //alert($(this).val());

      var proprioId = $(this).val();

        if(proprioId === null )
        {
            alert('Merci de sélectionner un nom');
            return false;
        }
        else
        {
          // alert(code_banque);
            return $.ajax
            ({
                url: '{{ url('houseStatistique') }}',
                data: {idRecu:proprioId},
                type: 'GET',
                cache: false,
                dataType: 'json',
                success: function (data) {
                    //$('#echeance').val(data.echeance);
                    $('#list_recu').html(data.list_house);
                    $('#proprio_adrese').html(data.infor_proprio);
                    $('#pdfRecu').html(data.valeur);
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
