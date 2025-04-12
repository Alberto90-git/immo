@extends('layouts.template')

@section('content')

@section('title')
  <title>Gestion profile</title>
  @endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

    <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages-account-settings-notifications.html"
              ><i class="bx bx-bell me-1"></i> Notifications</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages-account-settings-connections.html"
              ><i class="bx bx-link-alt me-1"></i> Connections</a
            >
          </li>
        </ul>
        <div class="card mb-4">
          <h5 class="card-header">Profile Details</h5>
          <!-- Account -->
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img
                src="../assets/img/avatars/1.png"
                alt="user-avatar"
                class="d-block rounded"
                height="100"
                width="100"
                id="uploadedAvatar"
              />
              <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                  <span class="d-none d-sm-block">Upload new photo</span>
                  <i class="bx bx-upload d-block d-sm-none"></i>
                  <input
                    type="file"
                    id="upload"
                    class="account-file-input"
                    hidden
                    accept="image/png, image/jpeg"
                  />
                </label>
                <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                  <i class="bx bx-reset d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Reset</span>
                </button>

                <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
              </div>
            </div>
          </div>
          <hr class="my-0" />
          <div class="card-body">
            <form id="formAccountSettings" method="POST" onsubmit="return false">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="firstName" class="form-label">First Name</label>
                  <input
                    class="form-control"
                    type="text"
                    id="firstName"
                    name="firstName"
                    value="John"
                    autofocus
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="lastName" class="form-label">Last Name</label>
                  <input class="form-control" type="text" name="lastName" id="lastName" value="Doe" />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    class="form-control"
                    type="text"
                    id="email"
                    name="email"
                    value="john.doe@example.com"
                    placeholder="john.doe@example.com"
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="organization" class="form-label">Organization</label>
                  <input
                    type="text"
                    class="form-control"
                    id="organization"
                    name="organization"
                    value="ThemeSelection"
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="phoneNumber">Phone Number</label>
                  <div class="input-group input-group-merge">
                    <span class="input-group-text">US (+1)</span>
                    <input
                      type="text"
                      id="phoneNumber"
                      name="phoneNumber"
                      class="form-control"
                      placeholder="202 555 0111"
                    />
                  </div>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="address" class="form-label">Address</label>
                  <input type="text" class="form-control" id="address" name="address" placeholder="Address" />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="state" class="form-label">State</label>
                  <input class="form-control" type="text" id="state" name="state" placeholder="California" />
                </div>
                <div class="mb-3 col-md-6">
                  <label for="zipCode" class="form-label">Zip Code</label>
                  <input
                    type="text"
                    class="form-control"
                    id="zipCode"
                    name="zipCode"
                    placeholder="231465"
                    maxlength="6"
                  />
                </div>
                <div class="mb-3 col-md-6">
                  <label class="form-label" for="country">Country</label>
                  <select id="country" class="select2 form-select">
                    <option value="">Select</option>
                    <option value="Australia">Australia</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Canada">Canada</option>
                    <option value="China">China</option>
                    <option value="France">France</option>
                    <option value="Germany">Germany</option>
                    <option value="India">India</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Israel">Israel</option>
                    <option value="Italy">Italy</option>
                    <option value="Japan">Japan</option>
                    <option value="Korea">Korea, Republic of</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Russia">Russian Federation</option>
                    <option value="South Africa">South Africa</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="United Arab Emirates">United Arab Emirates</option>
                    <option value="United Kingdom">United Kingdom</option>
                    <option value="United States">United States</option>
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="language" class="form-label">Language</label>
                  <select id="language" class="select2 form-select">
                    <option value="">Select Language</option>
                    <option value="en">English</option>
                    <option value="fr">French</option>
                    <option value="de">German</option>
                    <option value="pt">Portuguese</option>
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="timeZones" class="form-label">Timezone</label>
                  <select id="timeZones" class="select2 form-select">
                    <option value="">Select Timezone</option>
                    <option value="-12">(GMT-12:00) International Date Line West</option>
                    <option value="-11">(GMT-11:00) Midway Island, Samoa</option>
                    <option value="-10">(GMT-10:00) Hawaii</option>
                    <option value="-9">(GMT-09:00) Alaska</option>
                    <option value="-8">(GMT-08:00) Pacific Time (US & Canada)</option>
                    <option value="-8">(GMT-08:00) Tijuana, Baja California</option>
                    <option value="-7">(GMT-07:00) Arizona</option>
                    <option value="-7">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                    <option value="-7">(GMT-07:00) Mountain Time (US & Canada)</option>
                    <option value="-6">(GMT-06:00) Central America</option>
                    <option value="-6">(GMT-06:00) Central Time (US & Canada)</option>
                    <option value="-6">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                    <option value="-6">(GMT-06:00) Saskatchewan</option>
                    <option value="-5">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                    <option value="-5">(GMT-05:00) Eastern Time (US & Canada)</option>
                    <option value="-5">(GMT-05:00) Indiana (East)</option>
                    <option value="-4">(GMT-04:00) Atlantic Time (Canada)</option>
                    <option value="-4">(GMT-04:00) Caracas, La Paz</option>
                  </select>
                </div>
                <div class="mb-3 col-md-6">
                  <label for="currency" class="form-label">Currency</label>
                  <select id="currency" class="select2 form-select">
                    <option value="">Select Currency</option>
                    <option value="usd">USD</option>
                    <option value="euro">Euro</option>
                    <option value="pound">Pound</option>
                    <option value="bitcoin">Bitcoin</option>
                  </select>
                </div>
              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
              </div>
            </form>
          </div>
          <!-- /Account -->
        </div>
        <div class="card">
          <h5 class="card-header">Delete Account</h5>
          <div class="card-body">
            <div class="mb-3 col-12 mb-0">
              <div class="alert alert-warning">
                <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
              </div>
            </div>
            <form id="formAccountDeactivation" onsubmit="return false">
              <div class="form-check mb-3">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="accountActivation"
                  id="accountActivation"
                />
                <label class="form-check-label" for="accountActivation"
                  >I confirm my account deactivation</label
                >
              </div>
              <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
            </form>
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