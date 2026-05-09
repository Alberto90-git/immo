@extends('layouts.template')

@section('title')
  <title>{{ __('pages.msgr_title') }}</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Super Admin /</span> {{ __('pages.msgr_title') }}
    </h4>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-0">{{ __('pages.msgr_intro') }}</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddRate">
            <i class="bx bx-plus me-1"></i> {{ __('pages.msgr_btn_add') }}
        </button>
    </div>

    <div class="card">
        <div class="card-header" style="background:linear-gradient(135deg,#065f46,#059669);color:#fff;border-radius:8px 8px 0 0;">
            <h5 class="mb-0"><i class="bx bx-money me-2"></i>{{ __('pages.msgr_grid_title') }}</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tableRates">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('pages.msgr_th_country') }}</th>
                            <th>{{ __('pages.msgr_th_code') }}</th>
                            <th><i class="bx bx-message-detail me-1 text-warning"></i>{{ __('pages.msgr_th_sms') }}</th>
                            <th><i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.msgr_th_wa') }}</th>
                            <th>{{ __('pages.msgr_th_currency') }}</th>
                            <th>{{ __('pages.msgr_th_default') }}</th>
                            <th>{{ __('common.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyRates">
                        @forelse($rates as $rate)
                        <tr id="rate-row-{{ encrypt_id($rate->id) }}">
                            <td class="fw-semibold">{{ $rate->country_name }}</td>
                            <td><span class="badge bg-label-secondary">{{ $rate->country_code }}</span></td>
                            <td>
                                <span class="badge bg-label-warning" id="sms-cost-{{ $rate->id }}">
                                    {{ number_format($rate->sms_unit_cost, 0, ',', ' ') }} {{ $rate->currency }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-label-success" id="wa-cost-{{ $rate->id }}">
                                    {{ number_format($rate->whatsapp_unit_cost, 0, ',', ' ') }} {{ $rate->currency }}
                                </span>
                            </td>
                            <td>{{ $rate->currency }}</td>
                            <td>
                                @if($rate->is_default)
                                    <span class="badge bg-primary">
                                        <i class="bx bx-star me-1"></i>{{ __('pages.msgr_badge_default') }}
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1"
                                        onclick="editRate('{{ encrypt_id($rate->id) }}', '{{ $rate->country_name }}', {{ $rate->sms_unit_cost }}, {{ $rate->whatsapp_unit_cost }}, '{{ $rate->currency }}', {{ $rate->is_default ? 'true' : 'false' }})"
                                        title="{{ __('common.title_edit') }}">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteRate('{{ encrypt_id($rate->id) }}', '{{ $rate->country_name }}')"
                                        title="{{ __('common.title_delete') }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bx bx-info-circle me-1"></i>{{ __('pages.msgr_empty') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Modal : Ajouter un pays --}}
<div class="modal fade" id="modalAddRate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bx bx-plus me-2"></i>{{ __('pages.msgr_modal_add') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddRate">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">{{ __('pages.msgr_label_code') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="add_country_code"
                                   placeholder="{{ __('pages.msgr_ph_code') }}" maxlength="5" required>
                            <div class="form-text">{{ __('pages.msgr_code_hint') }}</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">{{ __('pages.msgr_label_country') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_country_name"
                                   placeholder="{{ __('pages.msgr_ph_country') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-message-detail me-1 text-warning"></i>{{ __('pages.msgr_label_sms_cost') }}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="add_sms_cost"
                                       min="0" step="0.01" value="5" required>
                                <span class="input-group-text" id="add_currency_display">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.msgr_label_wa_cost') }}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="add_wa_cost"
                                       min="0" step="0.01" value="10" required>
                                <span class="input-group-text">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('pages.msgr_label_currency') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_currency"
                                   value="XOF" maxlength="10" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
                <button type="button" class="btn btn-success" id="btnSaveAddRate">
                    <i class="bx bx-save me-1"></i> {{ __('pages.msgr_btn_create') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal : Modifier un tarif --}}
<div class="modal fade" id="modalEditRate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bx bx-edit me-2"></i>{{ __('pages.msgr_modal_edit') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditRate">
                    <input type="hidden" id="edit_rate_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">{{ __('pages.msgr_label_edit_country') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_country_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-message-detail me-1 text-warning"></i>{{ __('pages.msgr_label_edit_sms') }}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="edit_sms_cost" min="0" step="0.01" required>
                                <span class="input-group-text" id="edit_currency_display">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.msgr_label_edit_wa') }}
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="edit_wa_cost" min="0" step="0.01" required>
                                <span class="input-group-text">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('pages.msgr_label_currency') }}</label>
                            <input type="text" class="form-control" id="edit_currency" maxlength="10" required>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="edit_is_default">
                                <label class="form-check-label fw-semibold" for="edit_is_default">
                                    <i class="bx bx-star me-1 text-primary"></i>{{ __('pages.msgr_label_edit_default') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="bx bx-info-circle me-1"></i>
                                {{ __('pages.msgr_default_hint') }}
                            </small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
                <button type="button" class="btn btn-primary" id="btnSaveEditRate">
                    <i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

var MSGR_I18N = {
    required:    '{{ __('pages.msgr_swal_required') }}',
    fillAll:     '{{ __('pages.msgr_swal_fill_all') }}',
    fill:        '{{ __('pages.msgr_swal_fill') }}',
    creating:    '<i class="bx bx-loader bx-spin me-1"></i> {{ __('pages.msgr_creating') }}',
    saving:      '<i class="bx bx-loader bx-spin me-1"></i> {{ __('pages.msgr_saving') }}',
    created:     '{{ __('pages.msgr_swal_created') }}',
    updated:     '{{ __('pages.msgr_swal_updated') }}',
    deleted:     '{{ __('pages.msgr_swal_deleted') }}',
    error:       '{{ __('pages.msgr_swal_error') }}',
    serverError: '{{ __('pages.msgr_swal_server_error') }}',
    deleteText:  '{{ __('pages.msgr_delete_text') }}',
    deleteYes:   '{{ __('pages.msgr_delete_yes') }}',
    cancel:      '{{ __('common.btn_cancel') }}',
    btnCreate:   '<i class="bx bx-save me-1"></i> {{ __('pages.msgr_btn_create') }}',
    btnSave:     '<i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}',
    empty:       '{{ __('pages.msgr_empty') }}',
};

/* ── Ajouter un tarif ── */
document.getElementById('btnSaveAddRate').addEventListener('click', function() {
    const code     = document.getElementById('add_country_code').value.trim().toUpperCase();
    const name     = document.getElementById('add_country_name').value.trim();
    const sms      = parseFloat(document.getElementById('add_sms_cost').value) || 0;
    const wa       = parseFloat(document.getElementById('add_wa_cost').value)  || 0;
    const currency = document.getElementById('add_currency').value.trim();

    if (!code || !name || !currency) {
        Swal.fire(MSGR_I18N.required, MSGR_I18N.fillAll, 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = MSGR_I18N.creating;

    $.ajax({
        url:         '{{ route("super_admin.messaging_rates.store") }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify({
            country_code:       code,
            country_name:       name,
            sms_unit_cost:      sms,
            whatsapp_unit_cost: wa,
            currency:           currency,
        }),
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                Swal.fire({ icon: 'success', title: MSGR_I18N.created, text: data.message, timer: 1800, showConfirmButton: false })
                    .then(() => location.reload());
                bootstrap.Modal.getInstance(document.getElementById('modalAddRate')).hide();
            } else {
                Swal.fire(MSGR_I18N.error, data.message, 'error');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || MSGR_I18N.serverError;
            Swal.fire(MSGR_I18N.error, msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = MSGR_I18N.btnCreate;
        }
    });
});

/* ── Ouvrir modal edit ── */
function editRate(id, name, sms, wa, currency, isDefault) {
    document.getElementById('edit_rate_id').value       = id;
    document.getElementById('edit_country_name').value  = name;
    document.getElementById('edit_sms_cost').value      = sms;
    document.getElementById('edit_wa_cost').value       = wa;
    document.getElementById('edit_currency').value      = currency;
    document.getElementById('edit_currency_display').textContent = currency;
    document.getElementById('edit_is_default').checked  = isDefault;

    new bootstrap.Modal(document.getElementById('modalEditRate')).show();
}

/* ── Enregistrer modif ── */
document.getElementById('btnSaveEditRate').addEventListener('click', function() {
    const id       = document.getElementById('edit_rate_id').value;
    const name     = document.getElementById('edit_country_name').value.trim();
    const sms      = parseFloat(document.getElementById('edit_sms_cost').value) || 0;
    const wa       = parseFloat(document.getElementById('edit_wa_cost').value)  || 0;
    const currency = document.getElementById('edit_currency').value.trim();
    const isDef    = document.getElementById('edit_is_default').checked;

    if (!name || !currency) {
        Swal.fire(MSGR_I18N.required, MSGR_I18N.fill, 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = MSGR_I18N.saving;

    $.ajax({
        url:         '/super-admin/messaging-rates/' + id,
        type:        'PUT',
        contentType: 'application/json',
        data:        JSON.stringify({
            country_name:       name,
            sms_unit_cost:      sms,
            whatsapp_unit_cost: wa,
            currency:           currency,
            is_default:         isDef ? 1 : 0,
        }),
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                Swal.fire({ icon: 'success', title: MSGR_I18N.updated, text: data.message, timer: 1800, showConfirmButton: false })
                    .then(() => location.reload());
                bootstrap.Modal.getInstance(document.getElementById('modalEditRate')).hide();
            } else {
                Swal.fire(MSGR_I18N.error, data.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire(MSGR_I18N.error, xhr.responseJSON?.message || MSGR_I18N.serverError, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = MSGR_I18N.btnSave;
        }
    });
});

/* ── Supprimer un tarif ── */
function deleteRate(id, name) {
    Swal.fire({
        title:             '{{ __('pages.msgr_delete_title') }} « ' + name + ' » ?',
        text:              MSGR_I18N.deleteText,
        icon:              'warning',
        showCancelButton:  true,
        confirmButtonColor: '#d33',
        cancelButtonText:  MSGR_I18N.cancel,
        confirmButtonText: MSGR_I18N.deleteYes,
    }).then(function(result) {
        if (!result.isConfirmed) return;

        $.ajax({
            url:     '/super-admin/messaging-rates/' + id,
            type:    'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            success: function(data) {
                if (data.status) {
                    document.getElementById('rate-row-' + id).remove();
                    // Afficher message "vide" si plus de lignes
                    if (document.querySelectorAll('#tbodyRates tr').length === 0) {
                        document.getElementById('tbodyRates').innerHTML =
                            '<tr><td colspan="7" class="text-center text-muted py-4">' +
                            '<i class="bx bx-info-circle me-1"></i>' + MSGR_I18N.empty + '</td></tr>';
                    }
                    Swal.fire({ icon: 'success', title: MSGR_I18N.deleted, text: data.message, timer: 1800, showConfirmButton: false });
                } else {
                    Swal.fire(MSGR_I18N.error, data.message, 'error');
                }
            },
            error: function() {
                Swal.fire(MSGR_I18N.error, MSGR_I18N.serverError, 'error');
            }
        });
    });
}

/* Sync devise affichée dans modal ajout */
document.getElementById('add_currency').addEventListener('input', function() {
    document.getElementById('add_currency_display').textContent = this.value || 'XOF';
});
</script>
@endpush

@endsection
