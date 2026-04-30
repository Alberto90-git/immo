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
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_pct') }}<span style="color: red;">*</span></label>
                                        <select class="form-select" name="pourcentage" id="pourcentage">
                                            <option selected disabled>{{ __('pages.fin_ph_pct') }}</option>
                                            @if(isset($pourcentages))
                                                @foreach($pourcentages as $pct)
                                                    <option value="{{ $pct }}">{{ $pct }} %</option>
                                                @endforeach
                                            @else
                                                <option value="10">10 %</option>
                                            @endif
                                        </select>
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
                                    <div class="col-md-3">
                                        <label>{{ __('pages.fin_label_pct') }}<span style="color: red;">*</span></label>
                                        <select class="form-select" name="pourcentage2" id="pourcentage2">
                                            <option selected disabled>{{ __('pages.fin_ph_pct') }}</option>
                                            @if(isset($pourcentages))
                                                @foreach($pourcentages as $pct)
                                                    <option value="{{ $pct }}">{{ $pct }} %</option>
                                                @endforeach
                                            @else
                                                <option value="10">10 %</option>
                                            @endif
                                        </select>
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
                                    <!-- Pourcentage de gestion -->
                                    <div class="col-md-4">
                                        <label>{{ __('pages.fin_label_pct') }}<span style="color: red;">*</span></label>
                                        <select class="form-select" name="pourcentage_general" id="pourcentage_general">
                                            <option selected disabled>{{ __('pages.fin_ph_pct') }}</option>
                                            @if(isset($pourcentages))
                                                @foreach($pourcentages as $pct)
                                                    <option value="{{ $pct }}">{{ $pct }} %</option>
                                                @endforeach
                                            @else
                                                <option value="10">10 %</option>
                                            @endif
                                        </select>
                                    </div>
                            
                                    <!-- Date début -->
                                    <div class="col-md-4">
                                        <label>{{ __('pages.fin_label_date_start') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_debut_general" id="date_debut_general" class="form-control" required>
                                    </div>

                                    <!-- Date fin -->
                                    <div class="col-md-4">
                                        <label>{{ __('pages.fin_label_date_end') }}<span style="color: red;">*</span></label>
                                        <input type="date" name="date_fin_general" id="date_fin_general" class="form-control" required>
                                    </div>
                                </div>
                              </form>
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
                                <th scope="col">{{ __('pages.fin_th_agency_amount') }}</th>
                              </tr>
                            </thead>
                            <tbody id="solde_general">

                            </tbody>

                            <th colspan="4">{{ __('pages.fin_total') }}</th>
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
    
    
        // Auto-sélection du pourcentage quand un propriétaire est choisi
        $('#proprietaire').on('change', function() {
            var proprietaireId = $(this).val();
            if (proprietaireId) {
                $.ajax({
                    url: '{{ route("api.get_pourcentage_proprietaire") }}',
                    data: { proprietaire_id: proprietaireId },
                    type: 'GET',
                    success: function(data) {
                        if (data.status && data.pourcentage) {
                            var pctValue = parseFloat(data.pourcentage);
                            var selectEl = $('#pourcentage');
                            // Vérifier si l'option existe, sinon l'ajouter
                            if (selectEl.find('option[value="' + pctValue + '"]').length === 0) {
                                selectEl.append('<option value="' + pctValue + '">' + pctValue + ' %</option>');
                            }
                            selectEl.val(pctValue);
                        }
                    }
                });
            }
        });

        $('#proprietaire2').on('change', function() {
            var proprietaireId = $(this).val();
            if (proprietaireId) {
                $.ajax({
                    url: '{{ route("api.get_pourcentage_proprietaire") }}',
                    data: { proprietaire_id: proprietaireId },
                    type: 'GET',
                    success: function(data) {
                        if (data.status && data.pourcentage) {
                            var pctValue = parseFloat(data.pourcentage);
                            var selectEl = $('#pourcentage2');
                            if (selectEl.find('option[value="' + pctValue + '"]').length === 0) {
                                selectEl.append('<option value="' + pctValue + '">' + pctValue + ' %</option>');
                            }
                            selectEl.val(pctValue);
                        }
                    }
                });
            }
        });

        $('#date_fin').on('change',function(e)
        {
          //alert($(this).val());

          var proprietaire = $('#proprietaire').val();
          var pourcentage = $('#pourcentage').val();
          var date_debut = $('#date_debut').val();
          var date_fin = $(this).val();
    
    
    
            if((proprietaire === null) || (pourcentage === null) || (date_debut === null) 
            || (date_fin === null) )
            {
                alert(FIN_I18N.alertAllFields);
                return false;
            }
            else
            {
              // alert(code_banque);
                return $.ajax
                ({
                    url: '{{ url('propritor-payment') }}',
                    data: {proprietaire:proprietaire,pourcentage:pourcentage,date_debut:date_debut,date_fin:date_fin},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        //$('#echeance').val(data.echeance);
                        $('#solde').html(data.infos_solde);
                        $('#total').html(data.somme_solde) ;
                        $('#titre').html(data.titre) ;
                        $('#pdfToDownload').html(data.pdf) ;
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
    
          var proprietaire2 = $('#proprietaire2').val();
          var pourcentage2 = $('#pourcentage2').val();
          var date_debut2 = $('#date_debut2').val();
          var date_fin2 = $(this).val();
    
    
    
            if((proprietaire2 === null) || (pourcentage2 === null) || (date_debut2 === null) 
            || (date_fin2 === null) )
            {
                alert(FIN_I18N.alertAllFields);
                return false;
            }
            else
            {
              // alert(code_banque);
                return $.ajax
                ({
                    url: '{{ url('agence-payment') }}',
                    data: {proprietaire2:proprietaire2,pourcentage2:pourcentage2,date_debut2:date_debut2,date_fin2:date_fin2},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        //$('#echeance').val(data.echeance);
                        $('#solde2').html(data.infos_solde2);
                        $('#total2').html(data.somme_solde2) ;
                        $('#titre2').html(data.titre2) ;
                        $('#pdfToDownload2').html(data.pdf2) ;
                        //$('#tableData').append(data.table_data);
                    },
                    error:function(data) 
                    {
                      //alert(data.infor_proprio);
    
                    },
               });
            }
        });
    
    
        $('#date_fin_general').on('change',function(e)
        {
          //alert($(this).val());
    
         
          var pourcentage_general = $('#pourcentage_general').val();
          var date_debut_general = $('#date_debut_general').val();
          var date_fin_general = $(this).val();
    
    
    
            if( (pourcentage_general === null) || (date_debut_general === null) 
            || (date_fin_general === null) )
            {
                alert(FIN_I18N.alertAllFields);
                return false;
            }
            else
            {
              
                return $.ajax
                ({
                    url: '{{ url('agence-payment-general') }}',
                    data: {pourcentage_general:pourcentage_general,date_debut_general:date_debut_general,date_fin_general:date_fin_general},
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        //$('#echeance').val(data.echeance);
                        $('#solde_general').html(data.infos_solde_general);
                        $('#total_general').html(data.somme_solde_general) ;
                        $('#titre_general').html(data.titre_general) ;
                        $('#pdfToDownload_general').html(data.pdf_general) ;
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