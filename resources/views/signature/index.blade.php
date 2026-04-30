@extends('layouts.template')

@section('title')
<title>{{ __('sig.title') }} – Lokativ</title>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('ui.common.home') }} /</span> {{ __('sig.title') }}
    </h4>
    @can('ajoute-signature')
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNouvelle">
        <i class="bx bx-plus me-1"></i> {{ __('sig.btn_new') }}
      </button>
    @endcan
  </div>

  <!-- Tableau des demandes -->
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 datatable">
          <thead class="table-light">
            <tr>
              <th>{{ __('sig.col_document') }}</th>
              <th>{{ __('sig.col_type') }}</th>
              <th>{{ __('sig.col_signataire') }}</th>
              <th>{{ __('sig.col_statut') }}</th>
              <th>{{ __('sig.col_created') }}</th>
              <th>{{ __('sig.col_signed') }}</th>
              <th class="text-center">{{ __('sig.col_actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($demandes as $d)
            <tr>
              <td>
                <div class="fw-semibold">{{ $d->document_titre }}</div>
                @if($d->document_description)
                  <small class="text-muted">{{ Str::limit($d->document_description, 60) }}</small>
                @endif
              </td>
              <td>
                <span class="badge bg-label-primary">{{ $d->getDocumentTypeLabel() }}</span>
              </td>
              <td>
                <div class="fw-semibold">{{ $d->signataire_nom }}</div>
                @if($d->signataire_email)
                  <small class="text-muted">{{ $d->signataire_email }}</small>
                @elseif($d->signataire_telephone)
                  <small class="text-muted">{{ $d->signataire_telephone }}</small>
                @endif
              </td>
              <td>
                <span class="badge {{ $d->getStatutBadgeClass() }}">{{ $d->getStatutLabel() }}</span>
              </td>
              <td>{{ $d->created_at->format('d/m/Y H:i') }}</td>
              <td>{{ $d->signe_at ? $d->signe_at->format('d/m/Y H:i') : '—' }}</td>
              <td class="text-center">
                <div class="d-flex gap-1 justify-content-center">
                  {{-- Copier le lien --}}
                  @if($d->statut === 'en_attente')
                    <button class="btn btn-sm btn-outline-secondary"
                      title="{{ __('sig.btn_copy_link') }}"
                      onclick="copierLien('{{ route('signature.signer', $d->token) }}')">
                      <i class="bx bx-copy"></i>
                    </button>
                  @endif
                  {{-- Renvoyer --}}
                  @can('ajoute-signature')
                    @if($d->statut === 'en_attente')
                      <button class="btn btn-sm btn-outline-primary btn-renvoyer"
                        title="{{ __('sig.btn_resend') }}" data-id="{{ $d->id }}">
                        <i class="bx bx-send"></i>
                      </button>
                    @endif
                  @endcan
                  {{-- Certificat PDF --}}
                  @can('download-signature')
                    @if($d->statut === 'signe')
                      <a href="{{ route('signature.certificat', $d->id) }}" target="_blank"
                         class="btn btn-sm btn-outline-success" title="{{ __('sig.btn_cert') }}">
                        <i class="bx bx-file-pdf"></i>
                      </a>
                    @endif
                  @endcan
                  {{-- Annuler --}}
                  @can('delete-signature')
                    @if($d->statut === 'en_attente')
                      <button class="btn btn-sm btn-outline-danger btn-annuler"
                        title="{{ __('sig.btn_cancel') }}" data-id="{{ $d->id }}">
                        <i class="bx bx-x-circle"></i>
                      </button>
                    @endif
                  @endcan
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">{{ __('sig.empty') }}</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL — Nouvelle demande de signature
════════════════════════════════════════════════════════ --}}
@can('ajoute-signature')
<div class="modal fade" id="modalNouvelle" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(135deg,#696cff,#5a5fba);color:white;">
        <h5 class="modal-title">{{ __('sig.form_title') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formNouvelle" enctype="multipart/form-data">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">{{ __('sig.form_doc_type') }} <span class="text-danger">*</span></label>
              <select name="document_type" class="form-select" required>
                <option value="contrat">{{ __('sig.type_contrat') }}</option>
                <option value="etat_des_lieux">{{ __('sig.type_edl') }}</option>
                <option value="quittance">{{ __('sig.type_quittance') }}</option>
                <option value="autre" selected>{{ __('sig.type_autre') }}</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">{{ __('sig.form_expires') }}</label>
              <input type="number" name="expires_days" class="form-control" value="7" min="1" max="60">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">{{ __('sig.form_doc_titre') }} <span class="text-danger">*</span></label>
              <input type="text" name="document_titre" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">{{ __('sig.form_doc_desc') }}</label>
              <textarea name="document_description" class="form-control" rows="2"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">{{ __('sig.form_signataire') }} <span class="text-danger">*</span></label>
              <input type="text" name="signataire_nom" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">{{ __('sig.form_email') }}</label>
              <input type="email" name="signataire_email" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">{{ __('sig.form_telephone') }}</label>
              <input type="text" name="signataire_telephone" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">{{ __('sig.form_pdf') }}</label>
              <input type="file" name="pdf_file" class="form-control" accept=".pdf">
            </div>
            <div class="col-12">
              <small class="text-muted">{{ __('sig.form_hint_contact') }}</small>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
        <button type="button" class="btn btn-primary" id="btnCreer" onclick="creerDemande()">
          <i class="bx bx-send me-1"></i> {{ __('sig.btn_create') }}
        </button>
      </div>
    </div>
  </div>
</div>
@endcan

{{-- Modal copie lien --}}
<div class="modal fade" id="modalLien" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('sig.btn_copy_link') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="input-group">
          <input type="text" id="inputLienCopie" class="form-control font-monospace" readonly>
          <button class="btn btn-outline-secondary" type="button" onclick="doCopy()">
            <i class="bx bx-copy"></i>
          </button>
        </div>
        <div id="copyFeedback" class="text-success mt-2 d-none">
          <i class="bx bx-check me-1"></i>{{ __('sig.link_copied') }}
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function copierLien(lien) {
  document.getElementById('inputLienCopie').value = lien;
  document.getElementById('copyFeedback').classList.add('d-none');
  new bootstrap.Modal(document.getElementById('modalLien')).show();
}

function doCopy() {
  const input = document.getElementById('inputLienCopie');
  navigator.clipboard.writeText(input.value).then(() => {
    document.getElementById('copyFeedback').classList.remove('d-none');
  }).catch(() => {
    input.select();
    document.execCommand('copy');
    document.getElementById('copyFeedback').classList.remove('d-none');
  });
}

async function creerDemande() {
  const btn = document.getElementById('btnCreer');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __("common.processing") }}';

  const formData = new FormData(document.getElementById('formNouvelle'));

  try {
    const r = await fetch('{{ route("signature.store") }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: formData,
    });

    if (!r.ok) throw new Error('{{ __("common.swal_server_error") }} ' + r.status);
    const data = await r.json();

    if (data.status) {
      bootstrap.Modal.getInstance(document.getElementById('modalNouvelle')).hide();
      Swal.fire({
        icon: 'success',
        title: '{{ __("common.swal_success") }}',
        text: data.message,
        confirmButtonColor: '#696cff'
      }).then(() => {
        if (data.lien) {
          copierLien(data.lien);
        }
        setTimeout(() => window.location.reload(), 1500);
      });
    } else {
      Swal.fire('{{ __("common.swal_error") }}', data.message, 'error');
    }
  } catch (e) {
    Swal.fire('{{ __("common.swal_network_error") }}', e.message || '{{ __("common.swal_generic_error") }}', 'error');
  } finally {
    btn.disabled = false;
    btn.innerHTML = '<i class="bx bx-send me-1"></i>{{ __("sig.btn_create") }}';
  }
}

// Renvoyer
document.querySelectorAll('.btn-renvoyer').forEach(btn => {
  btn.addEventListener('click', async function () {
    const id = this.dataset.id;
    this.disabled = true;

    try {
      const r = await fetch('{{ route("signature.renvoyer") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ id }),
      });
      const data = await r.json();
      if (data.status) {
        Swal.fire({ icon: 'success', title: '{{ __("common.swal_success") }}', text: data.message, confirmButtonColor: '#696cff' });
        if (data.lien) copierLien(data.lien);
      } else {
        Swal.fire('{{ __("common.swal_error") }}', data.message, 'error');
      }
    } catch (e) {
      Swal.fire('{{ __("common.swal_network_error") }}', '{{ __("common.swal_network_msg") }}', 'error');
    } finally {
      this.disabled = false;
    }
  });
});

// Annuler
document.querySelectorAll('.btn-annuler').forEach(btn => {
  btn.addEventListener('click', async function () {
    const id = this.dataset.id;
    const self = this;

    const result = await Swal.fire({
      title: '{{ __("common.swal_confirm") }}',
      text: '{{ __("sig.btn_cancel") }} ?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc2626',
      cancelButtonColor: '#6b7280',
      confirmButtonText: '{{ __("common.btn_yes_delete") }}',
      cancelButtonText: '{{ __("common.btn_cancel") }}',
    });

    if (!result.isConfirmed) return;
    self.disabled = true;

    try {
      const r = await fetch('{{ route("signature.annuler") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ id }),
      });
      const data = await r.json();
      if (data.status) {
        Swal.fire({ icon: 'success', title: '{{ __("common.swal_success") }}', text: data.message, confirmButtonColor: '#696cff' })
          .then(() => window.location.reload());
      } else {
        Swal.fire('{{ __("common.swal_error") }}', data.message, 'error');
      }
    } catch (e) {
      Swal.fire('{{ __("common.swal_network_error") }}', '{{ __("common.swal_network_msg") }}', 'error');
    } finally {
      self.disabled = false;
    }
  });
});
</script>
@endsection
