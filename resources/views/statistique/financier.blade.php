@extends('layouts.template')

@section('content')

    @section('title')
    <title>{{ __('pages.fin_title') }}</title>
    @endsection


    <div class="container-xxl flex-grow-1 container-p-y">
       <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('pages.fin_breadcrumb_parent') }} /</span> {{ __('pages.fin_breadcrumb') }}</h4>
      
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3 nav-fill" role="tablist">

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link active"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-point_proprio"
                        aria-controls="navs-pills-justified-point_proprio"
                        aria-selected="true">

                        <i class="tf-icons bx bx-home"></i> {{ __('pages.fin_tab_point_proprio') }}
                        {{-- <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">3</span> --}}
                    </button>
                    </li>

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-benefice_proprio"
                        aria-controls="navs-pills-justified-benefice_proprio"
                        aria-selected="false"
                    >
                        <i class="tf-icons bx bx-user"></i> {{ __('pages.fin_tab_benefice_proprio') }}
                    </button>
                    </li>


                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-benefice_general"
                            aria-controls="navs-pills-justified-benefice_general"
                            aria-selected="false"
                        >
                            <i class="tf-icons bx bx-user"></i> {{ __('pages.fin_tab_benefice_general') }}
                        </button>
                        </li>
                    
                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="navs-pills-justified-point_proprio" role="tabpanel">
                        <div class="col-12">

                            <div class="col-12 mt-5">
                              <form>
                                <div class="row align-items-center">
                                    <!-- Propriétaire -->
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_owner') }}<span style="color: red;">*</span></label>
                                        <select class="form-select" name="proprietaire" id="proprietaire">
                                            <option selected disabled>{{ __('pages.fin_ph_owner') }}</option>
                                            @if(isset($data))
                                            @foreach($data as $item)
                                            <option value="{{ $item->id }}">{{ $item->nom }} {{ $item->prenom }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                            
                                    <!-- Pourcentage -->
                                    <div class="col-md-3" id="pct-fiche-wrapper">
                                        <label>{{ __('pages.fin_label_pct') }}</label>
                                        <div id="pct-fiche-container" class="pt-1">
                                            <span class="text-muted small">{{ __('pages.fin_ph_owner') }}</span>
                                        </div>
                                    </div>

                                    <!-- Date début -->
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_date_start') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_debut" id="date_debut" class="form-control" required>
                                    </div>

                                    <!-- Date fin -->
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_date_end') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                                    </div>
                                </div>
                              </form>
                            </div>
                            <br/>
                          
                          <p id="pdfToDownload">
                            
                          </p> 
                          <br/>

                          <h5 class="card-title text-center" id="titre"></h5>

                          <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
                                <thead>
                                  <tr>
                                    <th scope="col">{{ __('pages.fin_th_house') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_district') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_room_type') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_price') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_owner_amount') }}</th>
                                  </tr>
                                </thead>
                                <tbody id="solde">

                                </tbody>

                                <th colspan="4">{{ __('pages.fin_total_owner') }}</th>
                                  <td id="total"></td>
                        
                              </table>
                          </div>
                        </div>
      
                    </div>


                    <div class="tab-pane fade" id="navs-pills-justified-benefice_proprio" role="tabpanel">
                        <div class="col-12">

                            <div class="col-12 mt-5">
                              <form>
                                <div class="row align-items-center">
                                    <!-- Propriétaire -->
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_owner') }}<span style="color: red;">*</span></label>
                                        <select class="form-select" name="proprietaire2" id="proprietaire2">
                                            <option selected disabled>{{ __('pages.fin_ph_owner') }}</option>
                                            @if(isset($data))
                                            @foreach($data as $item)
                                            <option value="{{ $item->id }}">{{ $item->nom }} {{ $item->prenom }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                            
                                    <!-- Pourcentage -->
                                    <div class="col-md-3" id="pct-fiche2-wrapper">
                                        <label>{{ __('pages.fin_label_pct') }}</label>
                                        <div id="pct-fiche2-container" class="pt-1">
                                            <span class="text-muted small">{{ __('pages.fin_ph_owner') }}</span>
                                        </div>
                                    </div>

                                    <!-- Date début -->
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_date_start') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_debut2" id="date_debut2" class="form-control" required>
                                    </div>

                                    <!-- Date fin -->
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_date_end') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_fin2" id="date_fin2" class="form-control" required>
                                    </div>
                                </div>
                              </form>
                            </div>
                            <br/>
                            
                            <p id="pdfToDownload2"></p> 
                            <br/>

                            <h5 class="card-title text-center" id="titre2"></h5>

                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
                                <thead>
                                  <tr>
                                    <th scope="col">{{ __('pages.fin_th_house') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_district') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_room_type') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_price') }}</th>
                                    <th scope="col">{{ __('pages.fin_th_agency_amount') }}</th>
                                  </tr>
                                </thead>
                                <tbody id="solde2">

                                </tbody>

                                <th colspan="4">{{ __('pages.fin_total_agency') }}</th>
                                  <td id="total2"></td>
                              </table>
                            </div>
                        </div>
                    </div>



                    <div class="tab-pane fade" id="navs-pills-justified-benefice_general" role="tabpanel">
                        <div class="col-12">

                            <div class="col-12 mt-5">
                              <form>
                                <div class="row align-items-center">
                                    <!-- Date début -->
                                    <div class="col-md-6">
                                        <label>{{ __('pages.fin_label_date_start') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_debut_general" id="date_debut_general" class="form-control" required>
                                    </div>

                                    <!-- Date fin -->
                                    <div class="col-md-6">
                                        <label>{{ __('pages.fin_label_date_end') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_fin_general" id="date_fin_general" class="form-control" required>
                                    </div>
                                </div>
                              </form>
                              <p class="text-muted small mt-2"><i class="bx bx-info-circle me-1"></i>Le taux de commission est calculé automatiquement selon le groupe de chaque propriétaire.</p>
                            </div>
                            <br/>

                            <p id="pdfToDownload_general"></p>
                            <h5 class="card-title text-center" id="titre_general"></h5>
                            <br/>

                          <div class="table-responsive text-nowrap">
                            <table id="example" class="table table-bordered border-primary" style="width:100%" >
                              <thead>
                              <tr>
                                <th scope="col">{{ __('pages.fin_th_house') }}</th>
                                <th scope="col">{{ __('pages.fin_th_district') }}</th>
                                <th scope="col">{{ __('pages.fin_th_room_type') }}</th>
                                <th scope="col">{{ __('pages.fin_th_price') }}</th>
                                <th scope="col">{{ __('pages.fin_label_pct') }}</th>
                                <th scope="col">{{ __('pages.fin_th_agency_amount') }}</th>
                              </tr>
                            </thead>
                            <tbody id="solde_general">

                            </tbody>

                            <th colspan="5">{{ __('pages.fin_total') }}</th>
                              <td id="total_general"></td>

                          </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    

    <script>

        var FIN_I18N = {
            alertAllFields: '{{ __('pages.fin_alert_all_fields') }}',
        };

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    
    
        var ROUTE_PCT_FICHE = '{{ route("envoi_document.pourcentages") }}';

        function renderPctContainer(container, hiddenId, pctData) {
            var value      = (pctData && pctData.pourcentage !== null && pctData.pourcentage !== undefined)
                             ? pctData.pourcentage : 10;
            var configured = pctData && pctData.configured;
            var borderClass = configured ? 'border-success' : '';
            var statusHtml  = configured
                ? '<small class="text-success d-block mt-1"><i class="bx bx-check-circle me-1"></i>Depuis la configuration</small>'
                : '<small class="text-muted d-block mt-1"><i class="bx bx-info-circle me-1"></i>Taux par défaut — modifiable</small>';

            container.html(
                '<div class="input-group">' +
                '<input type="number" id="' + hiddenId + '" class="form-control ' + borderClass + '" ' +
                'value="' + value + '" min="0" max="100" step="0.1">' +
                '<span class="input-group-text">%</span>' +
                '</div>' +
                statusHtml
            );
        }

        $('#proprietaire').on('change', function() {
            var proprietaireId = $(this).val();
            var container = $('#pct-fiche-container');
            if (!proprietaireId) return;
            container.html('<span class="text-muted small"><i class="bx bx-loader-alt bx-spin"></i></span>');
            $.ajax({
                url: ROUTE_PCT_FICHE,
                data: { ids: [proprietaireId], _token: $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                success: function(res) {
                    var pctData = (res.status && res.data) ? (res.data[proprietaireId] || null) : null;
                    renderPctContainer(container, 'pourcentage', pctData);
                    // Si les dates sont déjà renseignées, recharger automatiquement
                    if ($('#date_debut').val() && $('#date_fin').val()) {
                        $('#date_fin').trigger('change');
                    }
                },
                error: function() {
                    renderPctContainer(container, 'pourcentage', null);
                }
            });
        });

        $('#proprietaire2').on('change', function() {
            var proprietaireId = $(this).val();
            var container = $('#pct-fiche2-container');
            if (!proprietaireId) return;
            container.html('<span class="text-muted small"><i class="bx bx-loader-alt bx-spin"></i></span>');
            $.ajax({
                url: ROUTE_PCT_FICHE,
                data: { ids: [proprietaireId], _token: $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                success: function(res) {
                    var pctData = (res.status && res.data) ? (res.data[proprietaireId] || null) : null;
                    renderPctContainer(container, 'pourcentage2', pctData);
                    // Si les dates sont déjà renseignées, recharger automatiquement
                    if ($('#date_debut2').val() && $('#date_fin2').val()) {
                        $('#date_fin2').trigger('change');
                    }
                },
                error: function() {
                    renderPctContainer(container, 'pourcentage2', null);
                }
            });
        });

        $('#date_fin').on('change',function(e)
        {
          var proprietaire = $('#proprietaire').val();
          var pourcentage  = $('#pourcentage').val() || 10;
          var date_debut   = $('#date_debut').val();
          var date_fin     = $(this).val();

            if(!proprietaire || !date_debut || !date_fin)
            {
                return false;
            }
            else
            {
                return $.ajax
                ({
                    url: '{{ url('propritor-payment') }}',
                    data: {proprietaire:proprietaire,pourcentage:pourcentage,date_debut:date_debut,date_fin:date_fin},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#solde').html(data.infos_solde);
                        $('#total').html(data.somme_solde) ;
                        $('#titre').html(data.titre) ;
                        $('#pdfToDownload').html(data.pdf) ;
                    },
                    error:function(data) {},
               });
            }
        });
    
    
    
    
        $('#date_fin2').on('change',function(e)
        {
          var proprietaire2 = $('#proprietaire2').val();
          var pourcentage2  = $('#pourcentage2').val() || 10;
          var date_debut2   = $('#date_debut2').val();
          var date_fin2     = $(this).val();

            if(!proprietaire2 || !date_debut2 || !date_fin2)
            {
                return false;
            }
            else
            {
                return $.ajax
                ({
                    url: '{{ url('agence-payment') }}',
                    data: {proprietaire2:proprietaire2,pourcentage2:pourcentage2,date_debut2:date_debut2,date_fin2:date_fin2},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        $('#solde2').html(data.infos_solde2);
                        $('#total2').html(data.somme_solde2) ;
                        $('#titre2').html(data.titre2) ;
                        $('#pdfToDownload2').html(data.pdf2) ;
                    },
                    error:function(data) {},
               });
            }
        });
    
    
        function chargerBeneficeGeneral() {
          var date_debut_general = $('#date_debut_general').val();
          var date_fin_general   = $('#date_fin_general').val();

          if (!date_debut_general || !date_fin_general) {
              return false;
          }

          return $.ajax({
              url: '{{ url('agence-payment-general') }}',
              data: {date_debut_general: date_debut_general, date_fin_general: date_fin_general},
              type: 'GET',
              cache: false,
              dataType: 'json',
              success: function (data) {
                  $('#solde_general').html(data.infos_solde_general);
                  $('#total_general').html(data.somme_solde_general);
                  $('#titre_general').html(data.titre_general);
                  $('#pdfToDownload_general').html(data.pdf_general);
              },
              error: function() {}
          });
        }

        $('#date_debut_general, #date_fin_general').on('change', function() {
            chargerBeneficeGeneral();
        });
    
    
        </script>
    @endsection