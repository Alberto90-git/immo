@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item">Statistique</li>
      <li class="breadcrumb-item active">Financier</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
    
    <div class="card recent-sales overflow-auto">
            <div class="card-body">

              <ul class="nav nav-tabs nav-tabs-bordered d-flex" id="borderedTabJustified" role="tablist">
                
                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-home" type="button" role="tab" aria-controls="home" aria-selected="true">Point par propriétaire</button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Bénéfice de l'agence / propriétaire</button>
                </li>

                <li class="nav-item flex-fill" role="presentation">
                  <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#bordered-justified-contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Bénéfice général de l'agence</button>
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
                                    <label>Propriétaire<span style="color: red;">*</span></label>
                                    <select class="form-select" name="proprietaire"  id="proprietaire">
                                        <option selected disabled>Choisir un propriétaire</option>
                                        @if(isset($data))
                                        @foreach($data as $item)
                                         <option value="{{ $item->id }}">{{ $item->nom }}  {{ $item->prenom }} </option>
                                        @endforeach
                                       @endif
                                    </select>
                               </div>

                               <div class="form-group col-md-3">
                                  <label>Pourcentage de gestion<span style="color: red;">*</span></label>
                                  <select class="form-select" name="pourcentage"  id="pourcentage">
                                      <option selected disabled>Choisir un pourcentage</option>
                                      <option value="5">5 %</option>
                                      <option value="10">10 %</option>
                                  </select>
                             </div>

                                <div class="form-group col-md-3">
                                  <label>Date début<span style="color: red;">*</span></label>
                                  <input type="date" name="date_debut"  id="date_debut" 
                                  class="form-control" required>
                                </div>

                                <div class="form-group col-md-3">
                                  <label>Date fin<span style="color: red;">*</span></label>
                                  <input type="date" name="date_fin" id="date_fin" 
                                  class="form-control" required>
                                </div>


                            </div>
                          </form>
                      </div>

                        
                    <h5 class="card-title center" id="titre"></h5>
                    
                    <p id="pdfToDownload">
                      
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
                          <th scope="col">Montant propriétaire</th>
                        </tr>
                      </thead>
                      <tbody id="solde">
                         
                      </tbody>
                      
                      <th colspan="6">Total à payer au propriétaire</th>
                        <td id="total"></td>
               
                    </table>
                  </div>

                </div>
                <!-- FIN -->


               <!-- DEBUT -->
                 <div class="tab-pane fade show" id="bordered-justified-profile" role="tabpanel" aria-labelledby="profile-tab">

                  <div class="col-12">

                      <div class="col-12 mt-5">
                          <form>
                            <div class="form-row">

                                <div class="form-group col-md-3">
                                    <label>Propriétaire<span style="color: red;">*</span></label>
                                    <select class="form-select" name="proprietaire2"  id="proprietaire2">
                                        <option selected disabled>Choisir un propriétaire</option>
                                        @if(isset($data))
                                        @foreach($data as $item)
                                         <option value="{{ $item->id }}">{{ $item->nom }}  {{ $item->prenom }} </option>
                                        @endforeach
                                       @endif
                                    </select>
                               </div>

                               <div class="form-group col-md-3">
                                  <label>Pourcentage de gestion<span style="color: red;">*</span></label>
                                  <select class="form-select" name="pourcentage2"  id="pourcentage2">
                                      <option selected disabled>Choisir un pourcentage</option>
                                      <option value="5">5 %</option>
                                      <option value="10">10 %</option>
                                  </select>
                             </div>

                                <div class="form-group col-md-3">
                                  <label>Date début<span style="color: red;">*</span></label>
                                  <input type="date" name="date_debut2"  id="date_debut2" 
                                  class="form-control" required>
                                </div>

                                <div class="form-group col-md-3">
                                  <label>Date fin<span style="color: red;">*</span></label>
                                  <input type="date" name="date_fin2" id="date_fin2" 
                                  class="form-control" required>
                                </div>


                            </div>
                          </form>
                      </div>

                        
                    <h5 class="card-title center" id="titre2"></h5>
                    
                    <p id="pdfToDownload2">
                      
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
                          <th scope="col">Montant de l'agence</th>
                        </tr>
                      </thead>
                      <tbody id="solde2">
                         
                      </tbody>
                      
                      <th colspan="6">Total à payer à l'agence</th>
                        <td id="total2"></td>
               
                    </table>
                  </div>

                </div>
              <!-- FIN -->



              <!-- DEBUT -->
                 <div class="tab-pane fade show" id="bordered-justified-contact" role="tabpanel" aria-labelledby="contact-tab">

                  <div class="col-12">

                      <div class="col-12 mt-5">
                          <form>
                            <div class="form-row">

                               <div class="form-group col-md-3">
                                  <label>Pourcentage de gestion<span style="color: red;">*</span></label>
                                  <select class="form-select" name="pourcentage_general"  id="pourcentage_general">
                                      <option selected disabled>Choisir un pourcentage</option>
                                      <option value="5">5 %</option>
                                      <option value="10">10 %</option>
                                  </select>
                             </div>

                                <div class="form-group col-md-3">
                                  <label>Date début<span style="color: red;">*</span></label>
                                  <input type="date" name="date_debut_general"  id="date_debut_general" 
                                  class="form-control" required>
                                </div>

                                <div class="form-group col-md-3">
                                  <label>Date fin<span style="color: red;">*</span></label>
                                  <input type="date" name="date_fin_general" id="date_fin_general" 
                                  class="form-control" required>
                                </div>


                            </div>
                          </form>
                      </div>

                        
                    <h5 class="card-title center" id="titre_general"></h5>
                    
                    <p id="pdfToDownload_general">
                      
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
                          <th scope="col">Montant de l'agence</th>
                        </tr>
                      </thead>
                      <tbody id="solde_general">
                         
                      </tbody>
                      
                      <th colspan="6">Total</th>
                        <td id="total_general"></td>
               
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

      var proprietaire = $('#proprietaire').val();
      var pourcentage = $('#pourcentage').val();
      var date_debut = $('#date_debut').val();
      var date_fin = $(this).val();



        if((proprietaire === null) || (pourcentage === null) || (date_debut === null) 
        || (date_fin === null) )
        {
            alert('Merci de sélectionner toutes les options');
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
            alert('Merci de sélectionner toutes les options');
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
            alert('Merci de sélectionner tous les champs');
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
