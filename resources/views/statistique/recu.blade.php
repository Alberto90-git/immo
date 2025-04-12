@extends('layouts.template')

@section('content')

    @section('title')
    <title>Gestion reporting</title>
    @endsection


    <div class="container-xxl flex-grow-1 container-p-y">
       <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion ancienne facture</h4>
      
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3 nav-fill" role="tablist">

                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-recu_avance"
                            aria-controls="navs-pills-justified-recu_avance"
                            aria-selected="true">

                            <i class="tf-icons bx bx-home"></i> Ancien réçu d'avance
                            {{-- <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">3</span> --}}
                        </button>
                    </li>

                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-reculocation"
                            aria-controls="navs-pills-justified-reculocation"
                            aria-selected="false"
                        >
                            <i class="tf-icons bx bx-user"></i> Ancien réçu location
                        </button>
                    </li>
                    
                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="navs-pills-justified-recu_avance" role="tabpanel">
                        <div class="col-12">

                            <div class="col-12 mt-5">
                              <form>
                                 @csrf
                                <div class="form-row">
    
                                    <div class="form-group col-md-3">
                                       <select class="form-select" id="choix" name="choix" onchange="displayChoix();" required>
                                        <option disabled selected value="">Choix de recherche</option>
                                        <option value="by_user">Par nom ou prénom</option>
                                        <option value="by_date">Par date</option>
                                      </select>
                                    </div>
    
                                    <div class="form-group col-md-3" id="date_debutDiv" style="display: none;">
                                      <input type="date" name="date_debut"  id="date_debut" class="form-control">
                                    </div>
    
                                    <div class="form-group col-md-3"  id="date_finDiv" style="display: none;">
                                      <input type="date" name="date_fin" id="date_fin" class="form-control">
                                    </div>
    
    
                                    <div class="form-group col-md-6"  style="display: none;" id="user_nameDiv" >
                                      <input type="text" placeholder="Saissir nom ou prénom du locataire" class="form-control" name="user_name" id="user_name">
                                    </div>
    
                                </div>
                              </form>
                            </div>
    
                            
                            <h5 class="card-title text-center" id="titre2"></h5>
                            <br/>
    
                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
                                <thead>
                                <tr>
                                  <th scope="col">Agence</th>
                                  <th scope="col">Maison</th>
                                  <th scope="col">N° chambre</th>
                                  <th scope="col">Locataire</th>
                                  <th scope="col">Profession</th>
                                  <th scope="col">Téléphone</th>
                                  <th scope="col">Avance</th>
                                  <th scope="col">Date entrée</th>
                                  <th scope="col"></th>
                                </tr>
                              </thead>
                              <tbody id="list_ancien_recu_avance">
                                 
                              </tbody>
                            </table>
                          </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="navs-pills-justified-reculocation" role="tabpanel">
                        <div class="col-12">


                            <div class="col-12 mt-5">
                              <form>
                                 @csrf
                                <div class="form-row">
    
                                    <div class="form-group col-md-3">
                                       <select class="form-select" id="choix2" name="choix2" onchange="displayChoix2();" required>
                                        <option disabled selected value="">Choix de recherche</option>
                                        <option value="by_user">Par nom ou prénom</option>
                                        <option value="by_date">Par date</option>
                                      </select>
                                    </div>
    
                                    <div class="form-group col-md-3" id="date_debutDiv2" style="display: none;">
                                      <input type="date" name="date_debut2"  id="date_debut2" class="form-control">
                                    </div>
    
                                    <div class="form-group col-md-3"  id="date_finDiv2" style="display: none;">
                                      <input type="date" name="date_fin2" id="date_fin2" class="form-control">
                                    </div>
    
    
                                    <div class="form-group col-md-6"  style="display: none;" id="user_nameDiv2" >
                                      <input type="text" placeholder="Saissir nom ou prénom du locataire" class="form-control" name="user_name2" id="user_name2">
                                    </div>
    
                                </div>
                              </form>
                            </div>
    
                             <h5 class="card-title text-center" id="titre"></h5>
    
                              <div class="table-responsive text-nowrap">
                                  <table id="example2" class="table table-bordered border-primary" style="width:100%" >
                                    <thead>
                                      <tr>
                                        <th scope="col">Agence</th>
                                        <th scope="col">Maison</th>
                                        <th scope="col">N° chambre</th>
                                        <th scope="col">Locataire</th>
                                        <th scope="col">Montant</th>
                                        <th scope="col">Mois</th>
                                        <th scope="col">Date paiement</th>
                                        <th scope="col"></th>
                                      </tr>
                                    </thead>
                                    <tbody id="list_locataire_recuPP">
                                    
                                    </tbody>
                                  </table>
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
    
    
        $('#user_name').on('keyup',function(e)
        {
          //alert($(this).val());
    
          var user_name = $('#user_name').val();
    
            if(user_name === null )
            {
                alert('Merci de saissir un nom');
                return false;
            }
            else
            {
              // alert(code_banque);
                return $.ajax
                ({
                    url: '{{ url('statistique-facture-avance-nom') }}',
                    data: {user_name:user_name},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_ancien_recu_avance').html(data.list_recu);
                        $('#titre2').html(data.titre2) ;
                        //$('#tableData').append(data.table_data);
                    },
                    error:function(data)
                    {
                      //alert(data.list_recu);
    
                    },
               });
            }
        }); 
    
    
        $('#user_name2').on('keyup',function(e)
        {
          //alert($(this).val());
          var user_name2 = $('#user_name2').val();
    
            if(user_name2 === null )
            {
                alert('Merci de sélectionner un nom ou prénom');
                return false;
            }
            else
            {
              // alert(code_banque);
                return $.ajax
                ({
                    url: '{{ url('statistique-facture-mois-nom') }}',
                    data: {user_name2:user_name2},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_locataire_recuPP').html(data.list_locataireP);
                        $('#titre').html(data.titre) ;
                        //$('#tableData').append(data.table_data);
                    },
                    error:function(data)
                    {
                      //alert(data.infor_proprio);
    
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
                alert('Merci de sélectionner un nom');
                return false;
            }
            else
            {
              // alert(code_banque);
                return $.ajax
                ({
                    url: '{{ url('statistique-facture-mois') }}',
                    data: {date_debut2:date_debut2, date_fin2:date_fin2},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_locataire_recuPP').html(data.list_locataireP);
                        $('#titre').html(data.titre) ;
                        //$('#tableData').append(data.table_data);
                    },
                    error:function(data)
                    {
                      //alert(data.infor_proprio);
    
                    },
               });
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
                    url: '{{ url('statistique-facture-avance') }}',
                    data: {date_debut:date_debut, date_fin:date_fin},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_ancien_recu_avance').html(data.list_recu);
                        $('#titre2').html(data.titre2) ;
                        //$('#tableData').append(data.table_data);
                    },
                    error:function(data)
                    {
                      //alert(data.list_recu);
    
                    },
               });
            }
        });
    
    
        function displayChoix2() {
        
            let val = document.getElementById('choix2').value;
            if (val == 'by_user') {
    
              document.getElementById('user_nameDiv2').style.display = 'block';
              document.getElementById('date_debutDiv2').style.display = 'none';
              document.getElementById('date_finDiv2').style.display = 'none';
    
              $("#user_name2").attr('required', true);
              $("#date_debut2").attr('required', false);
              $("#date_fin2").attr('required', false);
    
            } else {
    
              document.getElementById('user_nameDiv2').style.display = 'none';
              document.getElementById('date_debutDiv2').style.display = 'block';
              document.getElementById('date_finDiv2').style.display = 'block';
    
              $("#user_name2").attr('required', false);
              $("#date_debut2").attr('required', true);
              $("#date_fin2").attr('required', true);
    
            }
        } 
    
    
    
        function displayChoix() {
        
          let val = document.getElementById('choix').value;
          if (val == 'by_user') {
    
            document.getElementById('user_nameDiv').style.display = 'block';
            document.getElementById('date_debutDiv').style.display = 'none';
            document.getElementById('date_finDiv').style.display = 'none';
    
            $("#user_name").attr('required', true);
            $("#date_debut").attr('required', false);
            $("#date_fin").attr('required', false);
    
          } else {
    
            document.getElementById('user_nameDiv').style.display = 'none';
            document.getElementById('date_debutDiv').style.display = 'block';
            document.getElementById('date_finDiv').style.display = 'block';
    
            $("#user_name").attr('required', false);
            $("#date_debut").attr('required', true);
            $("#date_fin").attr('required', true);
    
          }
        } 
    
        </script>
    @endsection