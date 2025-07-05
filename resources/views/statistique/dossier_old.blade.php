@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item">Statistique</li>
      <li class="breadcrumb-item active">Dossier client & parcelle</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
    
    <div class="card recent-sales overflow-auto">
            <div class="card-body">

              <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Dossier client</button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Dossier parcelle</button>
                </li>

              </ul>

              <div class="tab-content pt-2" id="borderedTabJustifiedContent">

                <!-- DEBUT -->
                <div class="tab-pane fade show active" id="bordered-justified-home" role="tabpanel" aria-labelledby="home-tab">

                  <div class="col-12">

                        <div class="col-12 mt-5">
                            <form>
                              <div class="form-row">
                                  <div class="form-group col-md-3">
                                    <label>Date début</label>
                                    <input type="date" name="date_debut"  id="date_debut" class="form-control" required>
                                  </div>

                                  <div class="form-group col-md-3">
                                    <label>Date fin</label>
                                    <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                                  </div>

                              </div>
                            </form>
                        </div>

                        <h5 class="card-title center" id="titre"></h5>
                        <br/>

                        <p id="pdfToDownload">


                    <table class="table border-primary">
                      <thead>
                        <tr>
                          <th scope="col">Agence</th>
                          <th scope="col">Nom & prénom client</th>
                          <th scope="col">Téléphone</th>
                          <th scope="col">Zone voulue</th>
                          <th scope="col">Superficie</th>
                          <th scope="col">Budget</th>
                          <th scope="col">Date cloture du dossier</th>
                        </tr>
                      </thead>
                      <tbody id="list_clients">
                         
                      </tbody>
                    </table>
                  </div>

                </div>
                <!-- FIN -->

                <!-- DEBUT -->
                <div class="tab-pane fade" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">
                  <div class="col-12">

                    <div class="col-12 mt-5">
                            <form>
                              <div class="form-row">
                                  <div class="form-group col-md-3">
                                    <label>Date début</label>
                                    <input type="date" name="date_debut2"  id="date_debut2" class="form-control" required>
                                  </div>

                                  <div class="form-group col-md-3">
                                    <label>Date fin</label>
                                    <input type="date" name="date_fin2" id="date_fin2" class="form-control" required>
                                  </div>

                              </div>
                            </form>
                        </div>

                        <h5 class="card-title center" id="titre2"></h5>
                        <br/>

                         <p id="pdfToDownload2">


                    <table class="table table-bordered border-primary">
                    <thead>
                      <tr>
                        <th scope="col">Agence</th>
                        <th scope="col">Nom & prénom propriétaire</th>
                        <th scope="col">Téléphone</th>
                        <th scope="col">Quartier parcelle</th>
                        <th scope="col">Superficie</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Acheteur</th>
                        <th scope="col">Date cloture du dossier</th>
                      </tr>
                    </thead>
                    <tbody id="list_parcelles">
                       
                    </tbody>
                    </table>
                  </div>

                </div>

                <!-- FIN -->

               
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


    $('#date_fin').on('change',function(e)
    {
      //alert($(this).val());

      var date_debut = $('#date_debut').val();
      var date_fin = $('#date_fin').val();

        if(date_fin === null )
        {
            alert('Merci de sélectionner une date');
            return false;
        }
        else
        {
          // alert(code_banque);
            return $.ajax
            ({
                url: '{{ url('statistique-dossier-by-date') }}',
                data: {date_debut:date_debut, date_fin:date_fin},
                type: 'GET',
                cache: false,
                dataType: 'json',
                success: function (data) {
                    $('#list_clients').html(data.list_client);
                    $('#titre').html(data.titre2) ;
                    $('#pdfToDownload').html(data.pdf) ;
                    //$('#tableData').append(data.table_data);
                },
                error:function(data)
                {
                  //alert(data.list_recu);

                },
           });
        }
    });




    $('#date_fin2').on('change',function(e)
    {
      //alert($(this).val());

      var date_debut2 = $('#date_debut2').val();
      var date_fin2 = $('#date_fin2').val();

        if(date_fin2 === null )
        {
            alert('Merci de sélectionner une date');
            return false;
        }
        else
        {
          // alert(code_banque);
            return $.ajax
            ({
                url: '{{ url('statistique-parcelle-by-date') }}',
                data: {date_debut2:date_debut2, date_fin2:date_fin2},
                type: 'GET',
                cache: false,
                dataType: 'json',
                success: function (data) {
                    $('#list_parcelles').html(data.list_parcelle);
                    $('#titre2').html(data.titre2) ;
                    $('#pdfToDownload2').html(data.pdf2) ;
                    //$('#tableData').append(data.table_data);
                },
                error:function(data)
                {
                  //alert(data.list_recu);

                },
           });
        }
    });
    </script>
@endsection
