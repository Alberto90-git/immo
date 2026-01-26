@extends('layouts.template')

@section('content')

    @section('title')
    <title>Gestion reporting - Recus</title>
    @endsection


    <div class="container-xxl flex-grow-1 container-p-y">
       <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Accueil / Reporting /</span> Anciens recus
       </h4>

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
                            <i class="tf-icons bx bx-wallet me-1"></i> Recus d'avance (Caution)
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
                            <i class="tf-icons bx bx-receipt me-1"></i> Recus de loyer mensuel
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    <!-- TAB 1: Recus d'avance -->
                    <div class="tab-pane fade show active" id="navs-pills-justified-recu_avance" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bx bx-search-alt me-2"></i>Recherche de recus d'avance</h5>
                            </div>
                            <div class="card-body">
                                <form class="row g-3 align-items-end">
                                    @csrf
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Type de recherche</label>
                                        <select class="form-select" id="choix" name="choix" onchange="displayChoix();" required>
                                            <option disabled selected value="">Choisir...</option>
                                            <option value="by_user">Par nom/prenom</option>
                                            <option value="by_date">Par periode</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3" id="date_debutDiv" style="display: none;">
                                        <label class="form-label fw-semibold">Date debut</label>
                                        <input type="date" name="date_debut" id="date_debut" class="form-control">
                                    </div>

                                    <div class="col-md-3" id="date_finDiv" style="display: none;">
                                        <label class="form-label fw-semibold">Date fin</label>
                                        <input type="date" name="date_fin" id="date_fin" class="form-control">
                                    </div>

                                    <div class="col-md-6" style="display: none;" id="user_nameDiv">
                                        <label class="form-label fw-semibold">Nom ou prenom du locataire</label>
                                        <input type="text" placeholder="Saisir nom ou prenom..." class="form-control" name="user_name" id="user_name">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-center" id="titre2">
                                    <i class="bx bx-list-ul me-2"></i>Resultats de recherche
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-hover align-middle" style="width:100%">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="text-center" style="width: 10%;">
                                                    <i class="bx bx-building-house me-1"></i>Agence
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-home me-1"></i>Maison
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-door-open me-1"></i>Chambre
                                                </th>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="bx bx-user me-1"></i>Locataire
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-briefcase me-1"></i>Profession
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-phone me-1"></i>Telephone
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-calendar me-1"></i>Avance
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-calendar-check me-1"></i>Date entree
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-download me-1"></i>Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="list_ancien_recu_avance">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Recus de loyer mensuel -->
                    <div class="tab-pane fade" id="navs-pills-justified-reculocation" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bx bx-search-alt me-2"></i>Recherche de recus de loyer</h5>
                            </div>
                            <div class="card-body">
                                <form class="row g-3 align-items-end">
                                    @csrf
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Type de recherche</label>
                                        <select class="form-select" id="choix2" name="choix2" onchange="displayChoix2();" required>
                                            <option disabled selected value="">Choisir...</option>
                                            <option value="by_user">Par nom/prenom</option>
                                            <option value="by_date">Par periode</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3" id="date_debutDiv2" style="display: none;">
                                        <label class="form-label fw-semibold">Date debut</label>
                                        <input type="date" name="date_debut2" id="date_debut2" class="form-control">
                                    </div>

                                    <div class="col-md-3" id="date_finDiv2" style="display: none;">
                                        <label class="form-label fw-semibold">Date fin</label>
                                        <input type="date" name="date_fin2" id="date_fin2" class="form-control">
                                    </div>

                                    <div class="col-md-6" style="display: none;" id="user_nameDiv2">
                                        <label class="form-label fw-semibold">Nom ou prenom du locataire</label>
                                        <input type="text" placeholder="Saisir nom ou prenom..." class="form-control" name="user_name2" id="user_name2">
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-center" id="titre">
                                    <i class="bx bx-list-ul me-2"></i>Resultats de recherche
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-striped table-hover align-middle" style="width:100%">
                                        <thead class="table-success">
                                            <tr>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-building-house me-1"></i>Agence
                                                </th>
                                                <th class="text-center" style="width: 14%;">
                                                    <i class="bx bx-home me-1"></i>Maison
                                                </th>
                                                <th class="text-center" style="width: 10%;">
                                                    <i class="bx bx-door-open me-1"></i>Chambre
                                                </th>
                                                <th class="text-center" style="width: 16%;">
                                                    <i class="bx bx-user me-1"></i>Locataire
                                                </th>
                                                <th class="text-center" style="width: 14%;">
                                                    <i class="bx bx-money me-1"></i>Montant
                                                </th>
                                                <th class="text-center" style="width: 10%;">
                                                    <i class="bx bx-calendar me-1"></i>Mois
                                                </th>
                                                <th class="text-center" style="width: 14%;">
                                                    <i class="bx bx-calendar-check me-1"></i>Date paiement
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-download me-1"></i>Action
                                                </th>
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
          var user_name = $('#user_name').val();

            if(user_name === null || user_name.length < 2)
            {
                return false;
            }
            else
            {
                return $.ajax
                ({
                    url: '{{ url('statistique-facture-avance-nom') }}',
                    data: {user_name:user_name},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_ancien_recu_avance').html(data.list_recu);
                        $('#titre2').html('<i class="bx bx-list-ul me-2"></i>' + data.titre2);
                    },
                    error:function(data)
                    {
                      console.log('Erreur de recherche');
                    },
               });
            }
        });


        $('#user_name2').on('keyup',function(e)
        {
          var user_name2 = $('#user_name2').val();

            if(user_name2 === null || user_name2.length < 2)
            {
                return false;
            }
            else
            {
                return $.ajax
                ({
                    url: '{{ url('statistique-facture-mois-nom') }}',
                    data: {user_name2:user_name2},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_locataire_recuPP').html(data.list_locataireP);
                        $('#titre').html('<i class="bx bx-list-ul me-2"></i>' + data.titre);
                    },
                    error:function(data)
                    {
                      console.log('Erreur de recherche');
                    },
               });
            }
        });


        $('#date_fin2').on('change',function(e)
        {
          var date_debut2 = $('#date_debut2').val();
          var date_fin2 = $('#date_fin2').val();

            if(date_fin2 === null || date_debut2 === null)
            {
                alert('Merci de selectionner les deux dates');
                return false;
            }
            else
            {
                return $.ajax
                ({
                    url: '{{ url('statistique-facture-mois') }}',
                    data: {date_debut2:date_debut2, date_fin2:date_fin2},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_locataire_recuPP').html(data.list_locataireP);
                        $('#titre').html('<i class="bx bx-list-ul me-2"></i>' + data.titre);
                    },
                    error:function(data)
                    {
                      console.log('Erreur de recherche');
                    },
               });
            }
        });




        $('#date_fin').on('change',function(e)
        {
          var date_debut = $('#date_debut').val();
          var date_fin = $('#date_fin').val();

            if(date_fin === null || date_debut === null)
            {
                alert('Merci de selectionner les deux dates');
                return false;
            }
            else
            {
                return $.ajax
                ({
                    url: '{{ url('statistique-facture-avance') }}',
                    data: {date_debut:date_debut, date_fin:date_fin},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#list_ancien_recu_avance').html(data.list_recu);
                        $('#titre2').html('<i class="bx bx-list-ul me-2"></i>' + data.titre2);
                    },
                    error:function(data)
                    {
                      console.log('Erreur de recherche');
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
