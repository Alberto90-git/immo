@extends('layouts.template')


@section('content')
  @section('title')
  <title>{{ __('pages.client_title') }}</title>
  @endsection

  @include('notification.display_message')


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span>{{ __('pages.client_title') }}</h4>

    @can('ajouter-client')
      <div class="col-md-6">
        <div class="demo-inline-spacing">
          <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#AjouerClient">
            <span class="bx bx-plus"></span>
          </button>
        </div>
      </div><br/>
    @endcan


    {{-- Modal Ajouter --}}
    <div class="modal fade" id="AjouerClient" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.client_add_modal') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" id="formulaire" onsubmit="save_client(event)">
               @csrf

                <div class="col-12 col-sm-6">
                  <label class="form-label">{{ __('pages.client_name') }}<span style="color: red;">*</span></label>
                  <input type="text" name="nom" class="form-control" id="nom" required="">
                  <span class="invalid-feedback nom_err" role="alert"></span>
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">{{ __('pages.client_firstname') }}<span style="color: red;">*</span></label>
                  <input type="text" name="prenom" class="form-control" id="prenom" required="">
                  <span class="invalid-feedback prenom_err" role="alert"></span>
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">{{ __('pages.client_phone') }}<span style="color: red;">*</span></label>
                  <input type="text" name="telephone" class="form-control" id="telephone" required=""
                    onkeypress="return /[0-9]/i.test(event.key)">
                  <span class="invalid-feedback telephone_err" role="alert"></span>
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">{{ __('pages.client_zone') }}<span style="color: red;">*</span></label>
                  <input type="text" name="zone" class="form-control" id="zone" required="">
                  <span class="invalid-feedback zone_err" role="alert"></span>
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">{{ __('pages.client_area') }}</label>
                  <input type="text" name="superficie" class="form-control" id="superficie"
                    onkeypress="return /[0-9]/i.test(event.key)">
                </div>

                <div class="col-12 col-sm-6">
                  <label class="form-label">{{ __('pages.client_budget') }}<span style="color: red;">*</span></label>
                  <input type="text" name="budget" class="form-control" id="budget" required="">
                  <span class="invalid-feedback budget_err" role="alert"></span>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                  <button type="submit" class="btn btn-primary" id="valider">
                    <span class="fa fa-save"></span> {{ __('common.btn_save') }}
                  </button>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Détails (shared) --}}
    <div class="modal fade" id="sharedDetailsClient" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title text-white">
              <i class="bx bx-user-circle me-2"></i>
              <span id="d-client-nom"></span>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-12 col-sm-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-phone fs-4 text-primary mb-1"></i>
                    <div class="small text-muted">{{ __('pages.client_phone') }}</div>
                    <div class="fw-semibold" id="d-client-telephone">—</div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-map fs-4 text-success mb-1"></i>
                    <div class="small text-muted">{{ __('pages.client_zone') }}</div>
                    <div class="fw-semibold" id="d-client-zone">—</div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-area fs-4 text-info mb-1"></i>
                    <div class="small text-muted">{{ __('pages.client_area') }}</div>
                    <div class="fw-semibold"><span id="d-client-superficie">—</span> m²</div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-money fs-4 text-warning mb-1"></i>
                    <div class="small text-muted">{{ __('pages.client_budget') }}</div>
                    <div class="fw-semibold"><span id="d-client-budget">—</span> {{ get_symbole_devise() }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Modifier (shared) --}}
    <div class="modal fade" id="sharedModifierClient" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.client_edit_modal') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" id="formModifClient" onsubmit="updateClient(event)">
              @csrf
              <input type="hidden" name="id" id="mc-id">

              <div class="col-12 col-sm-6">
                <label class="form-label">{{ __('pages.client_name') }}<span style="color: red;">*</span></label>
                <input type="text" name="nom" id="mc-nom" class="form-control" required>
              </div>
              <div class="col-12 col-sm-6">
                <label class="form-label">{{ __('pages.client_firstname') }}<span style="color: red;">*</span></label>
                <input type="text" name="prenom" id="mc-prenom" class="form-control" required>
              </div>
              <div class="col-12 col-sm-6">
                <label class="form-label">{{ __('pages.client_phone') }}<span style="color: red;">*</span></label>
                <input type="text" name="telephone" id="mc-telephone" class="form-control" required
                  onkeypress="return /[0-9]/i.test(event.key)">
              </div>
              <div class="col-12 col-sm-6">
                <label class="form-label">{{ __('pages.client_zone') }}<span style="color: red;">*</span></label>
                <input type="text" name="zone" id="mc-zone" class="form-control" required>
              </div>
              <div class="col-12 col-sm-6">
                <label class="form-label">{{ __('pages.client_area') }}</label>
                <input type="text" name="superficie" id="mc-superficie" class="form-control"
                  onkeypress="return /[0-9]/i.test(event.key)">
              </div>
              <div class="col-12 col-sm-6">
                <label class="form-label">{{ __('pages.client_budget') }}<span style="color: red;">*</span></label>
                <input type="text" name="budget" id="mc-budget" class="form-control" required
                  onkeypress="return /[0-9]/i.test(event.key)">
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                <button type="submit" class="btn btn-primary" id="mc-submit">{{ __('common.btn_save') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Supprimer (shared) --}}
    <div class="modal fade" id="sharedSupprimerClient" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.client_delete_modal') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form onsubmit="deleteClient(event)">
              @csrf
              <input type="hidden" name="id" id="sc-id">
              <p>{{ __('pages.client_delete_confirm') }}</p>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_no') }}</button>
                <button type="submit" class="btn btn-outline-danger" id="sc-submit">{{ __('common.btn_yes') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Cloturer (shared) --}}
    <div class="modal fade" id="sharedCloturerClient" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.client_close_modal') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form onsubmit="cloturerClient(event)">
              @csrf
              <input type="hidden" name="id" id="cc-id">
              <p>{{ __('pages.client_close_confirm') }}</p>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_no') }}</button>
                <button type="submit" class="btn btn-danger shadow" id="cc-submit">{{ __('common.btn_yes') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">{{ __('pages.client_list') }}</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%">
          <thead>
            <tr>
              <th scope="col">{{ __('pages.client_th_name') }}</th>
              <th scope="col">{{ __('pages.client_th_phone') }}</th>
              <th scope="col">{{ __('pages.client_th_status') }}</th>
              <th scope="col">{{ __('common.th_actions') }}</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0" id="clients-tbody">
            @can('consulter-client')
              @if(isset($all_customers))
                @foreach($all_customers as $item)
                  <tr id="row-{{ $item->id }}"
                    data-id="{{ $item->id }}"
                    data-nom="{{ $item->nom }}"
                    data-prenom="{{ $item->prenom }}"
                    data-telephone="{{ $item->telephone }}"
                    data-zone="{{ $item->zone_voulu }}"
                    data-superficie="{{ $item->superficie }}"
                    data-budget="{{ $item->budget }}"
                    data-status="{{ $item->status }}">
                    <th scope="row">{{ $item->nom }} {{ $item->prenom }}</th>
                    <td>{{ $item->telephone }}</td>
                    <td>
                      @if($item->status == '')
                        <span class="badge rounded-pill bg-success">{{ __('pages.client_badge_pending') }}</span>
                      @else
                        <span class="badge rounded-pill bg-danger">{{ __('pages.client_badge_closed') }}</span>
                      @endif
                    </td>
                    <td>
                      @can('modifier-client')
                        @if($item->status == '')
                          <button class="btn rounded-pill btn-primary btn-modifier-client" title="{{ __('common.title_edit') }}">
                            <i class="bx bx-edit-alt me-1"></i>
                          </button>
                        @endif
                      @endcan
                      @can('supprimer-client')
                        @if($item->status == '')
                          <button class="btn rounded-pill btn-danger btn-supprimer-client" title="{{ __('common.title_delete') }}">
                            <i class="bx bx-trash me-1"></i>
                          </button>
                        @endif
                      @endcan
                      @can('cloturer-client')
                        @if($item->status == '')
                          <button class="btn rounded-pill btn-success btn-cloturer-client" title="{{ __('pages.client_btn_close') }}">
                            <i class="bx bx-check-circle me-1"></i>
                          </button>
                        @endif
                      @endcan
                      <button class="btn rounded-pill btn-primary btn-details-client" title="{{ __('common.title_details') }}">
                        <i class="bx bx-zoom-in me-1"></i>
                      </button>
                    </td>
                  </tr>
                @endforeach
              @endif
            @endcan
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Hoverable Table rows -->

    <script>
    var CLIENT_I18N = {
        inProgress:    '{{ __('pages.owner_in_progress') }}',
        btnSave:       '{{ __('common.btn_save') }}',
        btnYes:        '{{ __('common.btn_yes') }}',
        btnPending:    '{{ __('pages.client_badge_pending') }}',
        btnClosed:     '{{ __('pages.client_badge_closed') }}',
        swalSuccess:   '{{ __('common.swal_success') }} !',
        swalError:     '{{ __('common.swal_error') }} !',
        swalDeleted:   'Supprimé !',
        swalClosed:    'Clôturé !',
        titleEdit:     '{{ __('common.title_edit') }}',
        titleDelete:   '{{ __('common.title_delete') }}',
        titleClose:    '{{ __('pages.client_btn_close') }}',
        titleDetails:  '{{ __('common.title_details') }}',
        genericError:  '{{ __('common.swal_generic_error') }}',
    };

    function closeModalClient(selector) {
        $(selector).modal('hide');
        setTimeout(function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css({ overflow: '', paddingRight: '' });
        }, 350);
    }

    // ── Séparateur milliers sur le champ budget (ajout)
    $(document).on('keyup', '#budget', function() { Sepatateur_Milliers('#budget'); });
    $(document).on('keyup', '#mc-budget', function() { Sepatateur_Milliers('#mc-budget'); });

    // ── Délégation d'événements sur les boutons de ligne
    $(document).on('click', '.btn-details-client', function() {
        var d = $(this).closest('tr').data();
        $('#d-client-nom').text(d.nom + ' ' + d.prenom);
        $('#d-client-telephone').text(d.telephone || '—');
        $('#d-client-zone').text(d.zone || '—');
        $('#d-client-superficie').text(d.superficie || '—');
        $('#d-client-budget').text(Number(d.budget).toLocaleString('fr-FR') || '—');
        $('#sharedDetailsClient').modal('show');
    });

    $(document).on('click', '.btn-modifier-client', function() {
        var d = $(this).closest('tr').data();
        $('#mc-id').val(d.id);
        $('#mc-nom').val(d.nom);
        $('#mc-prenom').val(d.prenom);
        $('#mc-telephone').val(d.telephone);
        $('#mc-zone').val(d.zone);
        $('#mc-superficie').val(d.superficie);
        $('#mc-budget').val(d.budget);
        $('#sharedModifierClient').modal('show');
    });

    $(document).on('click', '.btn-supprimer-client', function() {
        var id = $(this).closest('tr').data('id');
        $('#sc-id').val(id);
        $('#sharedSupprimerClient').modal('show');
    });

    $(document).on('click', '.btn-cloturer-client', function() {
        var id = $(this).closest('tr').data('id');
        $('#cc-id').val(id);
        $('#sharedCloturerClient').modal('show');
    });

    // ── Ajouter client
    function save_client(e) {
        e.preventDefault();
        var btn = $('#valider');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + CLIENT_I18N.inProgress);

        var data = new FormData($('#formulaire')[0]);

        $.ajax({
            url: "{{ route('store_client') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: data,
            success: function(data) {
                btn.prop('disabled', false).html('<span class="fa fa-save"></span> ' + CLIENT_I18N.btnSave);

                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                    return;
                }

                if (data.status) {
                    var c = data.client;
                    var newRow = '<tr id="row-' + c.id + '"' +
                        ' data-id="' + c.id + '"' +
                        ' data-nom="' + c.nom + '"' +
                        ' data-prenom="' + c.prenom + '"' +
                        ' data-telephone="' + c.telephone + '"' +
                        ' data-zone="' + (c.zone || '') + '"' +
                        ' data-superficie="' + (c.superficie || '') + '"' +
                        ' data-budget="' + (c.budget || '') + '"' +
                        ' data-status="">' +
                        '<th scope="row">' + c.nom + ' ' + c.prenom + '</th>' +
                        '<td>' + c.telephone + '</td>' +
                        '<td><span class="badge rounded-pill bg-success">' + CLIENT_I18N.btnPending + '</span></td>' +
                        '<td>' +
                        @can('modifier-client') '<button class="btn rounded-pill btn-primary btn-modifier-client" title="' + CLIENT_I18N.titleEdit + '"><i class="bx bx-edit-alt me-1"></i></button> ' + @endcan
                        @can('supprimer-client') '<button class="btn rounded-pill btn-danger btn-supprimer-client" title="' + CLIENT_I18N.titleDelete + '"><i class="bx bx-trash me-1"></i></button> ' + @endcan
                        @can('cloturer-client') '<button class="btn rounded-pill btn-success btn-cloturer-client" title="' + CLIENT_I18N.titleClose + '"><i class="bx bx-check-circle me-1"></i></button> ' + @endcan
                        '<button class="btn rounded-pill btn-primary btn-details-client" title="' + CLIENT_I18N.titleDetails + '"><i class="bx bx-zoom-in me-1"></i></button>' +
                        '</td></tr>';

                    $('#clients-tbody').prepend(newRow);
                    $('#formulaire')[0].reset();
                    display_message(CLIENT_I18N.swalSuccess, data.message, "success", "btn btn-primary");
                } else {
                    display_message(CLIENT_I18N.swalError, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<span class="fa fa-save"></span> ' + CLIENT_I18N.btnSave);
                display_message(CLIENT_I18N.swalError, CLIENT_I18N.genericError, "warning", "btn btn-danger");
            }
        });
    }

    // ── Modifier client
    function updateClient(e) {
        e.preventDefault();
        var btn = $('#mc-submit');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + CLIENT_I18N.inProgress);
        var form = $('#formModifClient');

        $.ajax({
            url: "{{ route('update_client') }}",
            method: "POST",
            data: form.serialize(),
            success: function(data) {
                btn.prop('disabled', false).html(CLIENT_I18N.btnSave);
                if (data.status) {
                    var id  = $('#mc-id').val();
                    var nom = $('#mc-nom').val().toUpperCase();
                    var prenom = $('#mc-prenom').val();
                    var tel = $('#mc-telephone').val();
                    var zone = $('#mc-zone').val();
                    var sup = $('#mc-superficie').val();
                    var budget = $('#mc-budget').val();
                    var row = $('#row-' + id);
                    row.find('th').text(nom + ' ' + prenom);
                    row.find('td:nth-child(2)').text(tel);
                    // update data-* attributes
                    row.data('nom', nom).attr('data-nom', nom);
                    row.data('prenom', prenom).attr('data-prenom', prenom);
                    row.data('telephone', tel).attr('data-telephone', tel);
                    row.data('zone', zone).attr('data-zone', zone);
                    row.data('superficie', sup).attr('data-superficie', sup);
                    row.data('budget', budget).attr('data-budget', budget);
                    closeModalClient('#sharedModifierClient');
                    display_message(CLIENT_I18N.swalSuccess, data.message, "success", "btn btn-primary");
                } else {
                    display_message(CLIENT_I18N.swalError, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html(CLIENT_I18N.btnSave);
                display_message(CLIENT_I18N.swalError, CLIENT_I18N.genericError, "warning", "btn btn-danger");
            }
        });
    }

    // ── Supprimer client
    function deleteClient(e) {
        e.preventDefault();
        var btn = $('#sc-submit');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + CLIENT_I18N.inProgress);
        var id = $('#sc-id').val();

        $.ajax({
            url: "{{ route('delete_client') }}",
            method: "POST",
            data: { _token: $('meta[name="csrf-token"]').attr('content'), id: id },
            success: function(data) {
                btn.prop('disabled', false).html(CLIENT_I18N.btnYes);
                if (data.status) {
                    $('#row-' + id).fadeOut(400, function() { $(this).remove(); });
                    closeModalClient('#sharedSupprimerClient');
                    display_message(CLIENT_I18N.swalDeleted, data.message, "success", "btn btn-primary");
                } else {
                    display_message(CLIENT_I18N.swalError, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html(CLIENT_I18N.btnYes);
                display_message(CLIENT_I18N.swalError, CLIENT_I18N.genericError, "warning", "btn btn-danger");
            }
        });
    }

    // ── Cloturer client
    function cloturerClient(e) {
        e.preventDefault();
        var btn = $('#cc-submit');
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + CLIENT_I18N.inProgress);
        var id = $('#cc-id').val();

        $.ajax({
            url: "{{ route('cloture_client') }}",
            method: "POST",
            data: { _token: $('meta[name="csrf-token"]').attr('content'), id: id },
            success: function(data) {
                btn.prop('disabled', false).html(CLIENT_I18N.btnYes);
                if (data.status) {
                    var row = $('#row-' + id);
                    row.find('td:nth-child(3) span').removeClass('bg-success').addClass('bg-danger').text(CLIENT_I18N.btnClosed);
                    row.find('.btn-modifier-client, .btn-supprimer-client, .btn-cloturer-client').remove();
                    closeModalClient('#sharedCloturerClient');
                    display_message(CLIENT_I18N.swalClosed, data.message, "success", "btn btn-primary");
                } else {
                    display_message(CLIENT_I18N.swalError, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html(CLIENT_I18N.btnYes);
                display_message(CLIENT_I18N.swalError, CLIENT_I18N.genericError, "warning", "btn btn-danger");
            }
        });
    }

    function printErrorMsg(msg) {
        $.each(msg, function(key, value) {
            $('.' + key + '_err').text(value).show();
        });
    }

    $(':input').on('input change', function() {
        $('.' + $(this).attr("id") + '_err').hide();
    });
    </script>
@endsection
