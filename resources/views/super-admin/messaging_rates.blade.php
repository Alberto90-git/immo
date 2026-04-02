@extends('layouts.template')

@section('title')
  <title>Tarifs SMS &amp; WhatsApp</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Super Admin /</span> Tarifs SMS &amp; WhatsApp
    </h4>

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-0">
                Configurez les coûts unitaires (en devises) pour les envois SMS et WhatsApp par pays.
                Un tarif par défaut est utilisé si le pays du destinataire n'est pas configuré.
            </p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddRate">
            <i class="bx bx-plus me-1"></i> Ajouter un pays
        </button>
    </div>

    <div class="card">
        <div class="card-header" style="background:linear-gradient(135deg,#065f46,#059669);color:#fff;border-radius:8px 8px 0 0;">
            <h5 class="mb-0"><i class="bx bx-money me-2"></i>Grille tarifaire par pays</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tableRates">
                    <thead class="table-light">
                        <tr>
                            <th>Pays</th>
                            <th>Code</th>
                            <th><i class="bx bx-message-detail me-1 text-warning"></i>SMS (coût/msg)</th>
                            <th><i class="bx bxl-whatsapp me-1 text-success"></i>WhatsApp (coût/msg)</th>
                            <th>Devise</th>
                            <th>Par défaut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyRates">
                        @forelse($rates as $rate)
                        <tr id="rate-row-{{ $rate->id }}">
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
                                        <i class="bx bx-star me-1"></i>Défaut
                                    </span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1"
                                        onclick="editRate({{ $rate->id }}, '{{ $rate->country_name }}', {{ $rate->sms_unit_cost }}, {{ $rate->whatsapp_unit_cost }}, '{{ $rate->currency }}', {{ $rate->is_default ? 'true' : 'false' }})"
                                        title="Modifier">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="deleteRate({{ $rate->id }}, '{{ $rate->country_name }}')"
                                        title="Supprimer">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr id="emptyRow">
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bx bx-info-circle me-1"></i>Aucun tarif configuré.
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
                <h5 class="modal-title"><i class="bx bx-plus me-2"></i>Ajouter un tarif pays</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formAddRate">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Code pays <span class="text-danger">*</span></label>
                            <input type="text" class="form-control text-uppercase" id="add_country_code"
                                   placeholder="ex: TG" maxlength="5" required>
                            <div class="form-text">2-5 caractères (ex: BJ, TG, CI)</div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nom du pays <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_country_name"
                                   placeholder="ex: Togo" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-message-detail me-1 text-warning"></i>Coût SMS (par message)
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="add_sms_cost"
                                       min="0" step="0.01" value="5" required>
                                <span class="input-group-text" id="add_currency_display">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>Coût WhatsApp (par message)
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="add_wa_cost"
                                       min="0" step="0.01" value="10" required>
                                <span class="input-group-text">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Devise <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="add_currency"
                                   value="XOF" maxlength="10" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="btnSaveAddRate">
                    <i class="bx bx-save me-1"></i> Créer
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
                <h5 class="modal-title"><i class="bx bx-edit me-2"></i>Modifier le tarif</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditRate">
                    <input type="hidden" id="edit_rate_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Nom du pays <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_country_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bx-message-detail me-1 text-warning"></i>Coût SMS
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="edit_sms_cost" min="0" step="0.01" required>
                                <span class="input-group-text" id="edit_currency_display">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>Coût WhatsApp
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="edit_wa_cost" min="0" step="0.01" required>
                                <span class="input-group-text">XOF</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Devise</label>
                            <input type="text" class="form-control" id="edit_currency" maxlength="10" required>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="edit_is_default">
                                <label class="form-check-label fw-semibold" for="edit_is_default">
                                    <i class="bx bx-star me-1 text-primary"></i>Tarif par défaut
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                <i class="bx bx-info-circle me-1"></i>
                                Le tarif par défaut est utilisé quand le pays du destinataire n'est pas dans la liste.
                            </small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btnSaveEditRate">
                    <i class="bx bx-save me-1"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/* ── Ajouter un tarif ── */
document.getElementById('btnSaveAddRate').addEventListener('click', function() {
    const code     = document.getElementById('add_country_code').value.trim().toUpperCase();
    const name     = document.getElementById('add_country_name').value.trim();
    const sms      = parseFloat(document.getElementById('add_sms_cost').value) || 0;
    const wa       = parseFloat(document.getElementById('add_wa_cost').value)  || 0;
    const currency = document.getElementById('add_currency').value.trim();

    if (!code || !name || !currency) {
        Swal.fire('Champs requis', 'Veuillez remplir tous les champs obligatoires.', 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Création...';

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
                Swal.fire({ icon: 'success', title: 'Créé', text: data.message, timer: 1800, showConfirmButton: false })
                    .then(() => location.reload());
                bootstrap.Modal.getInstance(document.getElementById('modalAddRate')).hide();
            } else {
                Swal.fire('Erreur', data.message, 'error');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON?.message || 'Erreur serveur.';
            Swal.fire('Erreur', msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Créer';
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
        Swal.fire('Champs requis', 'Veuillez remplir tous les champs.', 'warning');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Enregistrement...';

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
                Swal.fire({ icon: 'success', title: 'Mis à jour', text: data.message, timer: 1800, showConfirmButton: false })
                    .then(() => location.reload());
                bootstrap.Modal.getInstance(document.getElementById('modalEditRate')).hide();
            } else {
                Swal.fire('Erreur', data.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Erreur', xhr.responseJSON?.message || 'Erreur serveur.', 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Enregistrer';
        }
    });
});

/* ── Supprimer un tarif ── */
function deleteRate(id, name) {
    Swal.fire({
        title:             'Supprimer « ' + name + ' » ?',
        text:              'Cette action est irréversible.',
        icon:              'warning',
        showCancelButton:  true,
        confirmButtonColor: '#d33',
        cancelButtonText:  'Annuler',
        confirmButtonText: 'Oui, supprimer',
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
                            '<i class="bx bx-info-circle me-1"></i>Aucun tarif configuré.</td></tr>';
                    }
                    Swal.fire({ icon: 'success', title: 'Supprimé', text: data.message, timer: 1800, showConfirmButton: false });
                } else {
                    Swal.fire('Erreur', data.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Erreur', 'Erreur serveur.', 'error');
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
