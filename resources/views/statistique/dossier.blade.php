@extends('layouts.template')

@section('content')

  @section('title')
    <title>Gestion reporting</title>
  @endsection

    <div class="container-xxl flex-grow-1 container-p-y">
       <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion publicité</h4>
      
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3 nav-fill" role="tablist">

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link active"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-dossier_client"
                        aria-controls="navs-pills-justified-dossier_client"
                        aria-selected="true">

                        <i class="tf-icons bx bx-home"></i> Dossier client
                        {{-- <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">3</span> --}}
                    </button>
                    </li>

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-dossier_parcelle"
                        aria-controls="navs-pills-justified-dossier_parcelle"
                        aria-selected="false"
                    >
                        <i class="tf-icons bx bx-user"></i> Dossier parcelle
                    </button>
                    </li>
                    
                </ul>

                <div class="tab-content">

                      <div class="tab-pane fade show active" id="navs-pills-justified-dossier_client" role="tabpanel">
                        <div class="col-12">

                            <div class="col-8 mt-5">
                              <form>
                                <div class="row align-items-center">
                                    <!-- Date début -->
                                    <div class="col-md-6">
                                        <label for="date_debut">Date début</label>
                                        <input type="date" name="date_debut" id="date_debut" class="form-control" required>
                                    </div>
                            
                                    <!-- Date fin -->
                                    <div class="col-md-6">
                                        <label for="date_fin">Date fin</label>
                                        <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                                    </div>
                                </div>
                              </form>
                            </div>
                            <br/>
    
                            <p id="pdfToDownload"></p>

                            <h5 class="card-title text-center" id="titre"></h5>
                            <br/>


                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
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
                      </div>


                    <div class="tab-pane fade" id="navs-pills-justified-dossier_parcelle" role="tabpanel">
                        <div class="col-12">

                              <div class="col-8 mt-5">
                                <form>
                                  <div class="row align-items-center">
                                      <!-- Date début -->
                                      <div class="col-md-6">
                                          <label for="date_debut2">Date début</label>
                                          <input type="date" name="date_debut2" id="date_debut2" class="form-control" required>
                                      </div>
                              
                                      <!-- Date fin -->
                                      <div class="col-md-6">
                                          <label for="date_fin2">Date fin</label>
                                          <input type="date" name="date_fin2" id="date_fin2" class="form-control" required>
                                      </div>
                                  </div>
                                </form>
                              </div>
                                <br/>
        
                                <p id="pdfToDownload2"></p>
                                <h5 class="card-title text-center" id="titre2"></h5>
                                <br/>
        
        
                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
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
                    </div>

                </div>
            </div>
        </div>
    </div>
    
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