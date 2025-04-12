@extends('layouts.template')

@section('content')

    @section('title')
    <title>Gestion reporting</title>
    @endsection


    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Gestion statistique /</span> Propriètaire / Maison / Chambre / Locataire</h4>
      
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

                        <i class="tf-icons bx bx-home"></i> Propriétaire & maison
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
                        <i class="tf-icons bx bx-user"></i> Maison & chambre
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
                            <i class="tf-icons bx bx-user"></i> Maison & locataire
                        </button>
                        </li>
                    
                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="navs-pills-justified-house_proprio" role="tabpanel">
                            <div class="col-12">
                            <a href="{{ route('getPdf') }}" class="btn btn-success rounded-pill ri-arrow-down-circle-fill shadow">Télécharger pdf
                            </a> <br/>

                            <h5 class="card-title text-center">Liste de tous les propriétaires et leurs maisons</h5>

                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
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
                    </div>


                    <div class="tab-pane fade" id="navs-pills-justified-house_chambre" role="tabpanel">
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
                            <br/>
                            
                             <p id="pdfRecu">
                               
                             </p> 
                                 
                            <h5 class="card-title text-center">Liste des maisons et chambres de <strong id="proprio_adrese"></strong>  </h5>
        
                            <div class="table-responsive text-nowrap">
                              <table id="example" class="table table-bordered border-primary" style="width:100%" >
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
                    </div>



                    <div class="tab-pane fade" id="navs-pills-justified-house_locataire" role="tabpanel">
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
                          <br/>
      
                         
                          </a> <p id="pdfRecuLocataire">
                             
                           </p>

                          <h5 class="card-title text-center" style="margin: auto;">Liste des locataires de <strong id="house_adrese_recu"></strong></h5>
                          <br/>
                           
                          <div class="table-responsive text-nowrap">
                            <table id="example" class="table table-bordered border-primary" style="width:100%" >
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