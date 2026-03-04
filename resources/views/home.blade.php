@extends('layouts.template')

@section('content')

  @section('title')
    <title>Accueil</title>
  @endsection

<div class="container-xxl flex-grow-1 container-p-y">
    @php
      $liste = get_annexe_liste();
    @endphp

    {{-- ===== PANNEAU SUPER ADMIN : Prestataire de paiement ===== --}}
    @can('config-paiement')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between py-3"
                     style="background:linear-gradient(135deg,#1e40af,#3b82f6);color:#fff;border-radius:8px 8px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-credit-card fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Prestataire de paiement</h6>
                            <small class="opacity-75">Choisissez et configurez la passerelle de paiement pour les abonnements</small>
                        </div>
                    </div>
                    @php
                        $apInit  = $activePaymentProvider ?? 'none';
                        $sbInit  = $activePaymentSandbox ?? true;
                        $badgeClass = $apInit === 'kkiapay' ? 'badge bg-success fs-6 px-3 py-2'
                                    : ($apInit === 'fedapay' ? 'badge bg-warning text-dark fs-6 px-3 py-2'
                                    : 'badge bg-secondary fs-6 px-3 py-2');
                        $badgeText  = $apInit === 'kkiapay' ? 'KKiaPay — ' . ($sbInit ? 'Sandbox' : 'Production')
                                    : ($apInit === 'fedapay' ? 'FedaPay — ' . ($sbInit ? 'Sandbox' : 'Production')
                                    : 'Paiement désactivé');
                    @endphp
                    <span id="payment-status-badge" class="{{ $badgeClass }}">
                        {{ $badgeText }}
                    </span>
                </div>
                <div class="card-body pt-4">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bx bx-info-circle me-2 fs-5"></i>
                        <div>
                            Activez un prestataire pour que les plans payants requièrent un paiement lors de
                            <strong>l'inscription</strong> et du <strong>changement de plan</strong>.
                            Par défaut, aucun prestataire n'est actif.
                        </div>
                    </div>

                    <form id="payment-config-form">
                        @csrf

                        {{-- Choix du prestataire actif --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-6">
                                <i class="bx bx-list-check me-1 text-primary"></i>Prestataire actif
                            </label>
                            @php $ap = $activePaymentProvider ?? 'none'; @endphp
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check border rounded p-3 pe-4 {{ $ap === 'none' ? 'border-primary' : '' }}" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="active_payment_provider"
                                           id="provider_none" value="none" {{ $ap === 'none' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="provider_none" style="cursor:pointer;">
                                        <i class="bx bx-block me-1 text-secondary"></i> Aucun paiement
                                    </label>
                                </div>
                                <div class="form-check border rounded p-3 pe-4 {{ $ap === 'kkiapay' ? 'border-primary' : '' }}" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="active_payment_provider"
                                           id="provider_kkiapay" value="kkiapay" {{ $ap === 'kkiapay' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="provider_kkiapay" style="cursor:pointer;">
                                        <i class="bx bx-credit-card me-1 text-primary"></i> KKiaPay
                                    </label>
                                </div>
                                <div class="form-check border rounded p-3 pe-4 {{ $ap === 'fedapay' ? 'border-warning' : '' }}" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="active_payment_provider"
                                           id="provider_fedapay" value="fedapay" {{ $ap === 'fedapay' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="provider_fedapay" style="cursor:pointer;">
                                        <i class="bx bx-credit-card-alt me-1 text-warning"></i> FedaPay
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Section KKiaPay --}}
                        <div id="section-kkiapay" class="{{ ($activePaymentProvider ?? 'none') === 'kkiapay' ? '' : 'd-none' }}">
                            <hr class="my-3">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-credit-card me-1"></i>Configuration KKiaPay</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-key me-1 text-warning"></i>Clé publique (Public Key) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="kkiapay_public_key"
                                           name="kkiapay_public_key" placeholder="Votre clé publique KKiaPay"
                                           value="{{ $paymentCfgData['kkiapay_public_key'] ?? '' }}">
                                    <div class="form-text">Utilisée côté client pour ouvrir le widget de paiement.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-lock me-1 text-danger"></i>Clé privée (Private Key) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="kkiapay_private_key"
                                               name="kkiapay_private_key"
                                               placeholder="{{ ($paymentCfgData['has_kkiapay_private'] ?? false) ? '••••••••••••••••' : 'Entrer la clé privée' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('kkiapay_private_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>Confidentielle.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-shield me-1 text-danger"></i>Clé secrète (Secret Key) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="kkiapay_secret_key"
                                               name="kkiapay_secret_key"
                                               placeholder="{{ ($paymentCfgData['has_kkiapay_secret'] ?? false) ? '••••••••••••••••' : 'Entrer la clé secrète' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('kkiapay_secret_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>Vérification côté serveur.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-test-tube me-1 text-info"></i>Environnement KKiaPay
                                    </label>
                                    <select class="form-select" id="kkiapay_sandbox" name="kkiapay_sandbox">
                                        <option value="1" {{ ($paymentCfgData['kkiapay_sandbox'] ?? true) ? 'selected' : '' }}>Sandbox (Mode test)</option>
                                        <option value="0" {{ !($paymentCfgData['kkiapay_sandbox'] ?? true) ? 'selected' : '' }}>Production (Paiements réels)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section FedaPay --}}
                        <div id="section-fedapay" class="{{ ($activePaymentProvider ?? 'none') === 'fedapay' ? '' : 'd-none' }}">
                            <hr class="my-3">
                            <h6 class="fw-bold mb-3 text-warning"><i class="bx bx-credit-card-alt me-1"></i>Configuration FedaPay</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-key me-1 text-warning"></i>Clé publique (Public Key) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="fedapay_public_key"
                                           name="fedapay_public_key" placeholder="pk_sandbox_... ou pk_live_..."
                                           value="{{ $paymentCfgData['fedapay_public_key'] ?? '' }}">
                                    <div class="form-text">Utilisée côté client pour initialiser le widget FedaPay.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-shield me-1 text-danger"></i>Clé secrète (Secret Key) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="fedapay_secret_key"
                                               name="fedapay_secret_key"
                                               placeholder="{{ ($paymentCfgData['has_fedapay_secret'] ?? false) ? '••••••••••••••••' : 'Entrer la clé secrète' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('fedapay_secret_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>Vérification côté serveur.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-test-tube me-1 text-info"></i>Environnement FedaPay
                                    </label>
                                    <select class="form-select" id="fedapay_sandbox" name="fedapay_sandbox">
                                        <option value="1" {{ ($paymentCfgData['fedapay_sandbox'] ?? true) ? 'selected' : '' }}>Sandbox (Mode test)</option>
                                        <option value="0" {{ !($paymentCfgData['fedapay_sandbox'] ?? true) ? 'selected' : '' }}>Production (Paiements réels)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4" id="btn-save-payment">
                                <i class="bx bx-save me-1"></i> Enregistrer la configuration
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="loadPaymentConfig()">
                                <i class="bx bx-refresh me-1"></i> Recharger
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan
    {{-- ===== FIN PANNEAU SUPER ADMIN Prestataire ===== --}}

    @can('Is_admin')
      <div class="row">
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-body">
              <label></label>
              <label for="inputPassword4" class="form-label text-center">Filtrer par Annee</label>
              <select class="form-select" name="annexe_id"  id="annexe_id" aria-label="multiple select example">
                <option value="" disabled selected>--Filtrer par annexe--</option>
                @if(isset($liste))
                  @foreach($liste as $list)
                    <option value="{{ $list->idannexes }}">{{ $list->designation }}</option>
                  @endforeach 
                @endif
              </select>
            </div>
          </div>
        </div>
      </div>
    @endcan

    @if(Auth::user()->is_admin != 1)

      <div class="card">
        <h5 class="card-header text-center">Liste de tous les locataires</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
            <thead>
              <tr>
                <th scope="col">Maison</th>
                <th scope="col">N° chambre</th>
                <th scope="col">Locataire</th>
                <th scope="col">Téléphone</th>
                <th scope="col">Profession</th>
                <th scope="col">Date d'entrée</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($data['locataire']))
              @foreach($data['locataire'] as $items)
                <tr>
                  <td>{{ $items->nom_maison }}</td>
                  <td>{{ $items->numero_chambre }}</td>
                  <td>{{ $items->nom }} {{ $items->prenom }}</td>
                  <td>{{ $items->telephone }}</td>
                  <td>{{ $items->profession }}</td>
                  <td>{{ $items->date_entree }}</td>
                </tr>
                @endforeach
                @endif
            </tbody>
          </table>
        </div>
      </div>
      <br/>


      <div class="card">
        <h5 class="card-header text-center">Liste de tous les propriétaires et leurs maisons</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
            <thead>
              <tr>
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
    @endif

    @can('Is_admin')

      <br/>
      <div class="row">
        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total propriétaire : {{ $data['nombreProprio'] }}</h5>
            </div>
          </div>
        </div>


        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total maison : {{ $data['nombreMaison'] }}</h5>
            </div>
          </div>
        </div>



        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total locataire : {{ $data['nombreLocataire'] }}</h5>
            </div>
          </div>
        </div>


        <div class="col-md-6 col-xl-3">
          <div class="card bg-primary text-white mb-3">
            <div class="card-body">
              <h5 class="card-title text-white">Total chambre : {{ $data['nombreChambre'] }}</h5>
            </div>
          </div>
        </div>

      
        
      </div>



      <br/>
      <div class="row">
        
        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de propriétaire</h5>
              <p class="card-text"  id="nombre_proprio"></p>
            </div>
          </div>
        </div>


        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de maison</h5>
              <p class="card-text"  id="nombre_maison"></p>
            </div>
          </div>
        </div>




        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de locataire</h5>
              <p class="card-text"  id="nombre_locataire"></p>
            </div>
          </div>
        </div>



        <div class="col-md-6 col-xl-3">
          <div class="card shadow-none bg-transparent border border-primary mb-3">
            <div class="card-body">
              <h5 class="card-title">Nbr de chambre</h5>
              <p class="card-text"  id="nombre_chambre"></p>
            </div>
          </div>
        </div>


      </div>


      <div class="card">
        <h5 class="card-header text-center">Liste de tous les locataires</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
            <thead>
              <tr>
                <th scope="col">Maison</th>
                <th scope="col">N° chambre</th>
                <th scope="col">Locataire</th>
                <th scope="col">Téléphone</th>
                <th scope="col">Profession</th>
                <th scope="col">Date d'entrée</th>
              </tr>
            </thead>
            <tbody id="list_locataire">
            </tbody>
          </table>
        </div>
      </div>
      <br/>




      <div class="card">
        <h5 class="card-header text-center">Liste de tous les propriétaires et leurs maisons</h5>
        <div class="table-responsive text-nowrap">
          <table id="example" class="table table-hover border-primary" style="width:100%" >
            <thead>
              <tr>
                <th scope="col">Nom & prénom</th>
                <th scope="col">Téléphone</th>
                <th scope="col">Adresse</th>
                <th scope="col">Maison</th>
                <th scope="col">Quartier</th>
              </tr>
            </thead>
            <tbody id="list_proprio">
            </tbody>
          </table>
        </div>
      </div>
      <br/>

    @endcan


</div>


    
   
<script>

  $('#annexe_id').on('change', function(e) {
    var annexe_id = $(this).val();

    if (annexe_id === null) {
      alert('Merci de sélectionner un nom');
      return false;
    } else {
      $.ajax({
        url: '{{ url('listeLocataire') }}',
        data: { annexe_id: annexe_id },
        type: 'GET',
        cache: false,
        dataType: 'json',
        success: function(data) {

          var listData = JSON.parse(data.list);

          if (listData && Object.keys(listData).length > 0) {
            var pieChartData = [];

            $.each(listData, function(city, value) {
              pieChartData.push({
                value: value,
                name: city
              });
            });

            echarts.init(document.querySelector("#trafficChart")).setOption({
              tooltip: {
                trigger: 'item'
              },
              // legend: {
              //   top: '-17%',
              //   left: 'center'
              // },
              series: [{
                name: 'locataire / ville',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                label: {
                  show: false,
                  position: 'center'
                },
                emphasis: {
                  label: {
                    show: true,
                    fontSize: '18',
                    fontWeight: 'bold'
                  }
                },
                labelLine: {
                  show: false
                },
                data: pieChartData 
              }]
            });
          } else {
            console.error('Pas de donnée');
          }
        },
        error: function(error) {
          console.error('An error occurred', error);
        },
      });
    }
  });



  $(document).ready(function() {
      if (!sessionStorage.getItem('welcome_shown')) {
          swal({
              title: 'Bienvenue sur votre espace de travail !!',
              text: '{{ Auth::user()->nom }} {{ Auth::user()->prenom }}',
              icon: 'success',
              button: {
                  text: "C'est parti !",
                  className: "btn btn-primary"
              },
              timer: 5000,
              buttonsStyling: true,
              customClass: {
                  popup: 'animated bounceInDown',
              },
              background: '#f0f0f0',
          });
          sessionStorage.setItem('welcome_shown', '1');
      }
  });






$('#annexe_id').on('change',function(e)
{
    //alert($(this).val());

    var annexe_id = $(this).val();

      if(annexe_id === null )
      {
          alert('Merci de sélectionner un nom');
          return false;
      }
      else
      {
        // alert(code_banque);
          return $.ajax
          ({
              url: '{{ url('listeLocataire') }}',
              data: {annexe_id:annexe_id},
              type: 'GET',
              cache: false,
              dataType: 'json',
              success: function (data) {
                //console.log(data.list);
                
                  //$('#echeance').val(data.echeance);
                  $('#list_locataire').html(data.getlist);
                  $('#list_proprio').html(data.getlist2);
                  $('#nombre_proprio').html(data.nombre_proprio);
                  $('#nombre_maison').html(data.nombre_maison);
                  $('#nombre_locataire').html(data.nombre_locataire);
                  $('#nombre_chambre').html(data.nombre_chambre);

                  
                 // $('#pdfRecu').html(data.valeur);  
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

@can('config-paiement')
<script>
// ===== Panneau Prestataire de paiement — Super Admin =====

function toggleFieldVisibility(fieldId) {
    var input = document.getElementById(fieldId);
    input.type = (input.type === 'password') ? 'text' : 'password';
}

function updatePaymentBadge(provider, sandbox) {
    var badge = document.getElementById('payment-status-badge');
    if (provider === 'kkiapay') {
        badge.className = 'badge bg-success fs-6 px-3 py-2';
        badge.textContent = 'KKiaPay — ' + (sandbox ? 'Sandbox' : 'Production');
    } else if (provider === 'fedapay') {
        badge.className = 'badge bg-warning text-dark fs-6 px-3 py-2';
        badge.textContent = 'FedaPay — ' + (sandbox ? 'Sandbox' : 'Production');
    } else {
        badge.className = 'badge bg-secondary fs-6 px-3 py-2';
        badge.textContent = 'Paiement désactivé';
    }
}

function showProviderSection(provider) {
    document.getElementById('section-kkiapay').classList.toggle('d-none', provider !== 'kkiapay');
    document.getElementById('section-fedapay').classList.toggle('d-none', provider !== 'fedapay');
}

function loadPaymentConfig() {
    $.ajax({
        url: '{{ route('platform.kkiapay.config') }}',
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            if (!data.status) return;

            var provider = data.active_payment_provider || 'none';
            // Sélectionner le bon radio
            $('input[name="active_payment_provider"][value="' + provider + '"]').prop('checked', true);
            showProviderSection(provider);

            // KKiaPay
            $('#kkiapay_public_key').val(data.kkiapay_public_key || '');
            $('#kkiapay_sandbox').val(data.kkiapay_sandbox ? '1' : '0');
            if (data.has_kkiapay_private_key) {
                $('#kkiapay_private_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
            if (data.has_kkiapay_secret_key) {
                $('#kkiapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }

            // FedaPay
            $('#fedapay_public_key').val(data.fedapay_public_key || '');
            $('#fedapay_sandbox').val(data.fedapay_sandbox ? '1' : '0');
            if (data.has_fedapay_secret_key) {
                $('#fedapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }

            var isSandbox = provider === 'kkiapay' ? data.kkiapay_sandbox : data.fedapay_sandbox;
            updatePaymentBadge(provider, isSandbox);
        },
        error: function() {
            document.getElementById('payment-status-badge').textContent = 'Erreur de chargement';
        }
    });
}

// Afficher/masquer les sections quand le radio change
$('input[name="active_payment_provider"]').on('change', function() {
    var provider = this.value;
    showProviderSection(provider);
    var sandbox = provider === 'kkiapay'
        ? ($('#kkiapay_sandbox').val() === '1')
        : ($('#fedapay_sandbox').val() === '1');
    updatePaymentBadge(provider, sandbox);
});

// Soumission du formulaire
$('#payment-config-form').on('submit', function(e) {
    e.preventDefault();

    var btn = document.getElementById('btn-save-payment');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Enregistrement...';

    var payload = {
        active_payment_provider: $('input[name="active_payment_provider"]:checked').val(),
        // KKiaPay
        kkiapay_public_key:  $('#kkiapay_public_key').val(),
        kkiapay_private_key: $('#kkiapay_private_key').val(),
        kkiapay_secret_key:  $('#kkiapay_secret_key').val(),
        kkiapay_sandbox:     $('#kkiapay_sandbox').val() === '1' ? 1 : 0,
        // FedaPay
        fedapay_public_key: $('#fedapay_public_key').val(),
        fedapay_secret_key: $('#fedapay_secret_key').val(),
        fedapay_sandbox:    $('#fedapay_sandbox').val() === '1' ? 1 : 0,
    };

    $.ajax({
        url:         '{{ route('platform.kkiapay.update') }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                // Appliquer immédiatement l'état depuis ce qui vient d'être envoyé
                // (évite que le radio reparte sur "Aucun" pendant le rechargement serveur)
                var chosenProvider = payload.active_payment_provider;
                $('input[name="active_payment_provider"]').prop('checked', false);
                $('input[name="active_payment_provider"][value="' + chosenProvider + '"]').prop('checked', true);
                showProviderSection(chosenProvider);
                var isSandbox = chosenProvider === 'kkiapay'
                    ? (payload.kkiapay_sandbox === 1)
                    : (payload.fedapay_sandbox === 1);
                updatePaymentBadge(chosenProvider, isSandbox);

                // Vider les champs secrets après sauvegarde
                $('#kkiapay_private_key, #kkiapay_secret_key, #fedapay_secret_key').val('');

                Swal.fire({
                    title: 'Succès',
                    text:  data.message,
                    icon:  'success',
                    timer: 2500,
                    showConfirmButton: false,
                });

                // Rafraîchir uniquement les clés (placeholders) — le radio est déjà positionné ci-dessus
                refreshKeyFields();
            } else {
                Swal.fire('Erreur', data.message || 'Une erreur est survenue.', 'error');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Une erreur est survenue.';
            Swal.fire('Erreur', msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Enregistrer la configuration';
        }
    });
});

$(document).ready(function() {
    // L'état initial (radio, sections, badge, inputs) est entièrement rendu par PHP/Blade.
    // Rien à charger via AJAX au démarrage.
});

function refreshKeyFields() {
    $.ajax({
        url: '{{ route('platform.kkiapay.config') }}',
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            if (!data.status) return;
            $('#kkiapay_public_key').val(data.kkiapay_public_key || '');
            $('#kkiapay_sandbox').val(data.kkiapay_sandbox ? '1' : '0');
            $('#fedapay_public_key').val(data.fedapay_public_key || '');
            $('#fedapay_sandbox').val(data.fedapay_sandbox ? '1' : '0');
            if (data.has_kkiapay_private_key) {
                $('#kkiapay_private_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
            if (data.has_kkiapay_secret_key) {
                $('#kkiapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
            if (data.has_fedapay_secret_key) {
                $('#fedapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
        }
    });
}
</script>
@endcan

  @endsection