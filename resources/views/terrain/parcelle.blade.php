@extends('layouts.template')


@section('content')

  @section('title')
    <title>{{ __('pages.par_title') }}</title>
  @endsection

  @include('notification.display_message')


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }} /</span> {{ __('pages.par_breadcrumb') }}</h4>

    @can('ajouter-parcelle')
      <div class="col-md-6">
        <div class="demo-inline-spacing">
          <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#AjouerParcelle">
            <span class="bx bx-plus"></span>
          </button>
        </div>
      </div><br/>
    @endcan


    {{-- Modal Ajouter --}}
    <div class="modal fade" id="AjouerParcelle" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.par_modal_add') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" id="formulaire" onsubmit="save_parcelle(event)">
               @csrf

                <div class="col-6">
                  <label class="form-label">{{ __('pages.par_label_owner_name') }}<span style="color: red;">*</span></label>
                  <input type="text" name="nom" class="form-control" id="nom" required="">
                  <span class="invalid-feedback nom_err" role="alert"></span>
                </div>

                <div class="col-6">
                  <label class="form-label">{{ __('pages.par_label_owner_first') }}<span style="color: red;">*</span></label>
                  <input type="text" name="prenom" class="form-control" id="prenom" required="">
                  <span class="invalid-feedback prenom_err" role="alert"></span>
                </div>

                <div class="col-6">
                  <label class="form-label">{{ __('pages.par_label_phone') }}<span style="color: red;">*</span></label>
                  <input type="text" name="telephone" class="form-control" id="telephone" required=""
                    onkeypress="return /[0-9]/i.test(event.key)">
                  <span class="invalid-feedback telephone_err" role="alert"></span>
                </div>

                <div class="col-6">
                  <label class="form-label">{{ __('pages.par_label_district') }}<span style="color: red;">*</span></label>
                  <input type="text" name="quartier" class="form-control" id="quartier" required="">
                  <span class="invalid-feedback quartier_err" role="alert"></span>
                </div>

                <div class="col-6">
                  <label class="form-label">{{ __('pages.par_label_area') }}</label>
                  <input type="text" name="superficie" class="form-control" id="superficie"
                    onkeypress="return /[0-9]/i.test(event.key)">
                </div>

                <div class="col-6">
                  <label class="form-label">{{ __('pages.par_label_price') }}<span style="color: red;">*</span></label>
                  <input type="text" name="prix" class="form-control" id="prix" required="">
                  <span class="invalid-feedback prix_err" role="alert"></span>
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
    <div class="modal fade" id="sharedDetailsParcelle" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
              <i class="bx bx-map-pin me-2"></i>
              <span id="d-parcelle-nom"></span>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-phone fs-4 text-primary mb-1"></i>
                    <div class="small text-muted">{{ __('pages.par_detail_phone') }}</div>
                    <div class="fw-semibold" id="d-parcelle-telephone">—</div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-map fs-4 text-success mb-1"></i>
                    <div class="small text-muted">{{ __('pages.par_detail_district') }}</div>
                    <div class="fw-semibold" id="d-parcelle-quartier">—</div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-area fs-4 text-info mb-1"></i>
                    <div class="small text-muted">{{ __('pages.par_detail_area') }}</div>
                    <div class="fw-semibold"><span id="d-parcelle-superficie">—</span> m²</div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="card border-0 bg-light h-100">
                  <div class="card-body text-center py-3">
                    <i class="bx bx-money fs-4 text-warning mb-1"></i>
                    <div class="small text-muted">{{ __('pages.par_detail_price') }}</div>
                    <div class="fw-semibold"><span id="d-parcelle-prix">—</span> {{ get_symbole_devise() }}</div>
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
    <div class="modal fade" id="sharedModifierParcelle" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.par_modal_edit') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" id="formModifParcelle" onsubmit="updateParcelle(event)">
              @csrf
              <input type="hidden" name="id" id="mp-id">

              <div class="col-6">
                <label class="form-label">{{ __('pages.par_label_name') }}<span style="color: red;">*</span></label>
                <input type="text" name="nom" id="mp-nom" class="form-control" required>
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.par_label_firstname') }}<span style="color: red;">*</span></label>
                <input type="text" name="prenom" id="mp-prenom" class="form-control" required>
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.par_label_phone') }}<span style="color: red;">*</span></label>
                <input type="text" name="telephone" id="mp-telephone" class="form-control" required
                  onkeypress="return /[0-9]/i.test(event.key)">
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.par_label_district') }}<span style="color: red;">*</span></label>
                <input type="text" name="quartier" id="mp-quartier" class="form-control" required>
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.par_label_area') }}</label>
                <input type="text" name="superficie" id="mp-superficie" class="form-control"
                  onkeypress="return /[0-9]/i.test(event.key)">
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.par_label_price') }}<span style="color: red;">*</span></label>
                <input type="text" name="prix" id="mp-prix" class="form-control" required
                  onkeypress="return /[0-9]/i.test(event.key)">
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                <button type="submit" class="btn btn-primary" id="mp-submit">{{ __('common.btn_save') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Supprimer (shared) --}}
    <div class="modal fade" id="sharedSupprimerParcelle" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.par_modal_delete') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form onsubmit="deleteParcelle(event)">
              @csrf
              <input type="hidden" name="id" id="sp-id">
              <p>{{ __('pages.par_delete_confirm') }}</p>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_no') }}</button>
                <button type="submit" class="btn btn-outline-danger" id="sp-submit">{{ __('common.btn_yes') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal Cloturer (shared) --}}
    <div class="modal fade" id="sharedCloturerParcelle" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.par_modal_close') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form onsubmit="cloturerParcelle(event)">
              @csrf
              <input type="hidden" name="id" id="cp-id">
              <p>{{ __('pages.par_close_confirm') }}</p>
              <div class="col-12 mb-3">
                <label class="form-label">{{ __('pages.par_label_buyer') }}<span style="color: red;">*</span></label>
                <select class="form-select" name="client_acheteur" id="cp-client-acheteur" required>
                  <option selected disabled value="">{{ __('pages.par_label_buyer') }}</option>
                  @if(isset($all_customers))
                    @foreach($all_customers as $terme)
                      <option value="{{ $terme->id }}">{{ $terme->nom }} {{ $terme->prenom }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_no') }}</button>
                <button type="submit" class="btn btn-danger shadow" id="cp-submit">{{ __('common.btn_yes') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">{{ __('pages.par_card_title') }}</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%">
          <thead>
            <tr>
              <th scope="col">{{ __('pages.par_th_name') }}</th>
              <th scope="col">{{ __('pages.par_th_phone') }}</th>
              <th scope="col">{{ __('pages.par_th_status') }}</th>
              <th scope="col">{{ __('common.th_actions') }}</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0" id="parcelles-tbody">
            @can('consulter-parcelle')
              @if(isset($all_terrain))
                @foreach($all_terrain as $item)
                  <tr id="row-{{ $item->id }}"
                    data-id="{{ $item->id }}"
                    data-nom="{{ $item->nom }}"
                    data-prenom="{{ $item->prenom }}"
                    data-telephone="{{ $item->telephone }}"
                    data-quartier="{{ $item->quartier }}"
                    data-superficie="{{ $item->superficie }}"
                    data-prix="{{ $item->prix }}"
                    data-status="{{ $item->status }}">
                    <th scope="row">{{ $item->nom }} {{ $item->prenom }}</th>
                    <td>{{ $item->telephone }}</td>
                    <td>
                      @if($item->status == '')
                        <span class="badge rounded-pill bg-success">{{ __('pages.par_badge_pending') }}</span>
                      @else
                        <span class="badge rounded-pill bg-danger">{{ __('pages.par_badge_sold') }}</span>
                      @endif
                    </td>
                    <td>
                      @can('modifier-parcelle')
                        @if($item->status == '')
                          <button class="btn rounded-pill btn-primary btn-modifier-parcelle" title="{{ __('common.title_edit') }}">
                            <i class="bx bx-edit-alt me-1"></i>
                          </button>
                        @endif
                      @endcan
                      @can('supprimer-parcelle')
                        @if($item->status == '')
                          <button class="btn rounded-pill btn-danger btn-supprimer-parcelle" title="{{ __('common.title_delete') }}">
                            <i class="bx bx-trash me-1"></i>
                          </button>
                        @endif
                      @endcan
                      @can('cloturer-parcelle')
                        @if($item->status == '')
                          <button class="btn rounded-pill btn-success btn-cloturer-parcelle" title="{{ __('pages.par_title_close') }}">
                            <i class="bx bx-check-circle me-1"></i>
                          </button>
                        @endif
                      @endcan
                      <button class="btn rounded-pill btn-primary btn-details-parcelle" title="{{ __('common.title_details') }}">
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
    var PAR_I18N = {
        processing:   '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('pages.par_processing') }}',
        btnSave:      '<span class="fa fa-save"></span> {{ __('common.btn_save') }}',
        btnYes:       '{{ __('common.btn_yes') }}',
        success:      '{{ __('pages.par_swal_success') }}',
        error:        '{{ __('pages.par_swal_error') }}',
        errorShort:   '{{ __('pages.par_swal_error_short') }}',
        successShort: '{{ __('pages.par_swal_success_short') }}',
        deleted:      '{{ __('pages.par_swal_deleted') }}',
        closed:       '{{ __('pages.par_swal_closed') }}',
        genericError: '{{ __('pages.par_swal_generic_error') }}',
        badgePending: '{{ __('pages.par_badge_pending') }}',
        badgeSold:    '{{ __('pages.par_badge_sold') }}',
        titleEdit:    '{{ __('common.title_edit') }}',
        titleDelete:  '{{ __('common.title_delete') }}',
        titleClose:   '{{ __('pages.par_title_close') }}',
        titleDetails: '{{ __('common.title_details') }}',
    };

    function closeModalParcelle(selector) {
        $(selector).modal('hide');
        setTimeout(function() {
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open').css({ overflow: '', paddingRight: '' });
        }, 350);
    }

    // ── Séparateur milliers
    $(document).on('keyup', '#prix', function() { Sepatateur_Milliers('#prix'); });
    $(document).on('keyup', '#mp-prix', function() { Sepatateur_Milliers('#mp-prix'); });

    // ── Délégation d'événements
    $(document).on('click', '.btn-details-parcelle', function() {
        var d = $(this).closest('tr').data();
        $('#d-parcelle-nom').text(d.nom + ' ' + d.prenom);
        $('#d-parcelle-telephone').text(d.telephone || '—');
        $('#d-parcelle-quartier').text(d.quartier || '—');
        $('#d-parcelle-superficie').text(d.superficie || '—');
        $('#d-parcelle-prix').text(Number(d.prix).toLocaleString('fr-FR') || '—');
        $('#sharedDetailsParcelle').modal('show');
    });

    $(document).on('click', '.btn-modifier-parcelle', function() {
        var d = $(this).closest('tr').data();
        $('#mp-id').val(d.id);
        $('#mp-nom').val(d.nom);
        $('#mp-prenom').val(d.prenom);
        $('#mp-telephone').val(d.telephone);
        $('#mp-quartier').val(d.quartier);
        $('#mp-superficie').val(d.superficie);
        $('#mp-prix').val(d.prix);
        $('#sharedModifierParcelle').modal('show');
    });

    $(document).on('click', '.btn-supprimer-parcelle', function() {
        var id = $(this).closest('tr').data('id');
        $('#sp-id').val(id);
        $('#sharedSupprimerParcelle').modal('show');
    });

    $(document).on('click', '.btn-cloturer-parcelle', function() {
        var id = $(this).closest('tr').data('id');
        $('#cp-id').val(id);
        $('#cp-client-acheteur').val('');
        $('#sharedCloturerParcelle').modal('show');
    });

    // ── Ajouter parcelle
    function save_parcelle(e) {
        e.preventDefault();
        var btn = $('#valider');
        btn.prop('disabled', true).html(PAR_I18N.processing);

        var data = new FormData($('#formulaire')[0]);

        $.ajax({
            url: "{{ route('store_terrain') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: data,
            success: function(data) {
                btn.prop('disabled', false).html(PAR_I18N.btnSave);

                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                    return;
                }

                if (data.status) {
                    var p = data.parcelle;
                    var newRow = '<tr id="row-' + p.id + '"' +
                        ' data-id="' + p.id + '"' +
                        ' data-nom="' + p.nom + '"' +
                        ' data-prenom="' + p.prenom + '"' +
                        ' data-telephone="' + p.telephone + '"' +
                        ' data-quartier="' + (p.quartier || '') + '"' +
                        ' data-superficie="' + (p.superficie || '') + '"' +
                        ' data-prix="' + (p.prix || '') + '"' +
                        ' data-status="">' +
                        '<th scope="row">' + p.nom + ' ' + p.prenom + '</th>' +
                        '<td>' + p.telephone + '</td>' +
                        '<td><span class="badge rounded-pill bg-success">' + PAR_I18N.badgePending + '</span></td>' +
                        '<td>' +
                        @can('modifier-parcelle') '<button class="btn rounded-pill btn-primary btn-modifier-parcelle" title="' + PAR_I18N.titleEdit + '"><i class="bx bx-edit-alt me-1"></i></button> ' + @endcan
                        @can('supprimer-parcelle') '<button class="btn rounded-pill btn-danger btn-supprimer-parcelle" title="' + PAR_I18N.titleDelete + '"><i class="bx bx-trash me-1"></i></button> ' + @endcan
                        @can('cloturer-parcelle') '<button class="btn rounded-pill btn-success btn-cloturer-parcelle" title="' + PAR_I18N.titleClose + '"><i class="bx bx-check-circle me-1"></i></button> ' + @endcan
                        '<button class="btn rounded-pill btn-primary btn-details-parcelle" title="' + PAR_I18N.titleDetails + '"><i class="bx bx-zoom-in me-1"></i></button>' +
                        '</td></tr>';

                    $('#parcelles-tbody').prepend(newRow);
                    $('#formulaire')[0].reset();
                    display_message(PAR_I18N.success, data.message, "success", "btn btn-primary");
                } else {
                    display_message(PAR_I18N.error, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html(PAR_I18N.btnSave);
                display_message(PAR_I18N.error, PAR_I18N.genericError, "warning", "btn btn-danger");
            }
        });
    }

    // ── Modifier parcelle
    function updateParcelle(e) {
        e.preventDefault();
        var btn = $('#mp-submit');
        btn.prop('disabled', true).html(PAR_I18N.processing);

        $.ajax({
            url: "{{ route('update_terrain') }}",
            method: "POST",
            data: $('#formModifParcelle').serialize(),
            success: function(data) {
                btn.prop('disabled', false).html('{{ __('common.btn_save') }}');
                if (data.status) {
                    var id       = $('#mp-id').val();
                    var nom      = $('#mp-nom').val().toUpperCase();
                    var prenom   = $('#mp-prenom').val();
                    var tel      = $('#mp-telephone').val();
                    var quartier = $('#mp-quartier').val();
                    var sup      = $('#mp-superficie').val();
                    var prix     = $('#mp-prix').val();
                    var row = $('#row-' + id);
                    row.find('th').text(nom + ' ' + prenom);
                    row.find('td:nth-child(2)').text(tel);
                    row.attr({
                        'data-nom': nom, 'data-prenom': prenom, 'data-telephone': tel,
                        'data-quartier': quartier, 'data-superficie': sup, 'data-prix': prix
                    });
                    closeModalParcelle('#sharedModifierParcelle');
                    display_message(PAR_I18N.successShort, data.message, "success", "btn btn-primary");
                } else {
                    display_message(PAR_I18N.errorShort, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html('{{ __('common.btn_save') }}');
                display_message(PAR_I18N.errorShort, PAR_I18N.genericError, "warning", "btn btn-danger");
            }
        });
    }

    // ── Supprimer parcelle
    function deleteParcelle(e) {
        e.preventDefault();
        var btn = $('#sp-submit');
        btn.prop('disabled', true).html(PAR_I18N.processing);
        var id = $('#sp-id').val();

        $.ajax({
            url: "{{ route('delete_terrain') }}",
            method: "POST",
            data: { _token: $('meta[name="csrf-token"]').attr('content'), id: id },
            success: function(data) {
                btn.prop('disabled', false).html(PAR_I18N.btnYes);
                if (data.status) {
                    $('#row-' + id).fadeOut(400, function() { $(this).remove(); });
                    closeModalParcelle('#sharedSupprimerParcelle');
                    display_message(PAR_I18N.deleted, data.message, "success", "btn btn-primary");
                } else {
                    display_message(PAR_I18N.errorShort, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html(PAR_I18N.btnYes);
                display_message(PAR_I18N.errorShort, PAR_I18N.genericError, "warning", "btn btn-danger");
            }
        });
    }

    // ── Cloturer parcelle
    function cloturerParcelle(e) {
        e.preventDefault();
        var btn = $('#cp-submit');
        btn.prop('disabled', true).html(PAR_I18N.processing);
        var id = $('#cp-id').val();
        var client_acheteur = $('#cp-client-acheteur').val();

        $.ajax({
            url: "{{ route('cloturer_terrain') }}",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: id,
                client_acheteur: client_acheteur
            },
            success: function(data) {
                btn.prop('disabled', false).html(PAR_I18N.btnYes);
                if (data.status) {
                    var row = $('#row-' + id);
                    row.find('td:nth-child(3) span').removeClass('bg-success').addClass('bg-danger').text(PAR_I18N.badgeSold);
                    row.find('.btn-modifier-parcelle, .btn-supprimer-parcelle, .btn-cloturer-parcelle').remove();
                    closeModalParcelle('#sharedCloturerParcelle');
                    display_message(PAR_I18N.closed, data.message, "success", "btn btn-primary");
                } else {
                    display_message(PAR_I18N.errorShort, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                btn.prop('disabled', false).html(PAR_I18N.btnYes);
                display_message(PAR_I18N.errorShort, PAR_I18N.genericError, "warning", "btn btn-danger");
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
