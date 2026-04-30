@extends('layouts.template')

@section('content')

    @section('title')
    <title>{{ __('pages.pc_title') }}</title>
    @endsection


    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('pages.fin_breadcrumb_parent') }} /</span> {{ __('pages.pc_breadcrumb') }}</h4>
      
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3 nav-fill" role="tablist">

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link active"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-house_proprio"
                        aria-controls="navs-pills-justified-house_proprio"
                        aria-selected="true">

                        <i class="tf-icons bx bx-home"></i> {{ __('pages.pc_tab_owner_house') }}
                        {{-- <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">3</span> --}}
                    </button>
                    </li>

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-house_chambre"
                        aria-controls="navs-pills-justified-house_chambre"
                        aria-selected="false"
                    >
                        <i class="tf-icons bx bx-user"></i> {{ __('pages.pc_tab_house_room') }}
                    </button>
                    </li>


                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-house_locataire"
                            aria-controls="navs-pills-justified-house_locataire"
                            aria-selected="false"
                        >
                            <i class="tf-icons bx bx-user"></i> {{ __('pages.pc_tab_house_tenant') }}
                        </button>
                        </li>
                    
                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="navs-pills-justified-house_proprio" role="tabpanel">
                            <div class="col-12">
                            <a href="{{ route('getPdf') }}" class="btn btn-primary rounded-pill ri-arrow-down-circle-fill shadow">{{ __('pages.pc_btn_download') }}
                            </a> <br/>

                            <h5 class="card-title text-center">{{ __('pages.pc_list_owners_houses') }}</h5>

                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
                                <thead>
                                  <tr>
                                    <th scope="col">{{ __('pages.pc_th_name') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_phone') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_address') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_house') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_district') }}</th>
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


                    <div class="tab-pane fade" id="navs-pills-justified-house_chambre" role="tabpanel">
                        <div class="col-12">

                            <div class="col-4 center">
                              <select class="form-select" id="proprioId" name="proprioId" aria-label="Floating label select example">
                                <option disabled selected>{{ __('pages.pc_ph_owner') }}</option>
                                @if(isset($data['proprio']))
                                 @foreach($data['proprio'] as $item)
                                  <option value="{{ $item->id }}">{{ $item->nom }}  {{ $item->prenom }} </option>
                                 @endforeach
                                @endif
                              </select>
                            </div>
                            <br/>
                            
                             <p id="pdfRecu">
                               
                             </p> 
                                 
                            <h5 class="card-title text-center">{{ __('pages.pc_list_rooms') }} <strong id="proprio_adrese"></strong></h5>
        
                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
                                <thead>
                                  <tr>
                                    <th scope="col">{{ __('pages.pc_th_house') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_district') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_room_no') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_room_type') }}</th>
                                    <th scope="col">{{ __('pages.pc_th_price') }}</th>
                                  </tr>
                                </thead>
                                <tbody id="list_recu">
                                  
                                </tbody>
                              </table>
                            </div>
                          </div>
                    </div>



                    <div class="tab-pane fade" id="navs-pills-justified-house_locataire" role="tabpanel">
                        <div class="col-4 center">
                            <select class="form-select" id="maison_choisie" name="maison_choisie" aria-label="Floating label select example">
                              <option disabled selected>{{ __('pages.pc_ph_house') }}</option>
                              @if(isset($data['house']))
                               @foreach($data['house'] as $item)
                                <option value="{{ $item->id }}">{{ $item->nom_maison }}</option>
                               @endforeach
                              @endif
                            </select>
                          </div>
                          <br/>
      
                         
                          </a> <p id="pdfRecuLocataire">
                             
                           </p>

                          <h5 class="card-title text-center" style="margin: auto;">{{ __('pages.pc_list_tenants') }} <strong id="house_adrese_recu"></strong></h5>
                          <br/>
                           
                          <div class="table-responsive text-nowrap">
                            <table id="example" class="table table-bordered border-primary" style="width:100%" >
                              <thead>
                                <tr>
                                  <th scope="col">{{ __('pages.pc_th_room_no') }}</th>
                                  <th scope="col">{{ __('pages.pc_th_room_type') }}</th>
                                  <th scope="col">{{ __('pages.pc_th_tenant') }}</th>
                                  <th scope="col">{{ __('pages.pc_th_phone') }}</th>
                                  <th scope="col">{{ __('pages.pc_th_advance') }}</th>
                                  <th scope="col">{{ __('pages.pc_th_entry_date') }}</th>
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
    
    
    <script>

      var PC_I18N = {
          alertSelect: '{{ __('pages.pc_alert_select') }}',
      };

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
              alert(PC_I18N.alertSelect);
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
              alert(PC_I18N.alertSelect);
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