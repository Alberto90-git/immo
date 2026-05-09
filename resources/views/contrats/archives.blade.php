@extends('layouts.template')

@section('title')
<title>{{ __('pages.archives_title') }} – Lokativ</title>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('ui.common.home') }} /</span>
      {{ __('pages.archives_breadcrumb') }}
    </h4>
  </div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 datatable">
          <thead class="table-light">
            <tr>
              <th>{{ __('pages.archives_col_document') }}</th>
              <th>{{ __('pages.archives_col_tenant') }}</th>
              <th>{{ __('pages.archives_col_signed_at') }}</th>
              <th>{{ __('pages.archives_col_sha256') }}</th>
              <th class="text-center">{{ __('pages.archives_col_actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($archives as $d)
            <tr>
              <td>
                <div class="fw-semibold">{{ $d->document_titre }}</div>
                @if($d->document_description)
                  <small class="text-muted">{{ Str::limit($d->document_description, 60) }}</small>
                @endif
              </td>
              <td>
                <div class="fw-semibold">{{ $d->signataire_nom }}</div>
                @if($d->signataire_email)
                  <small class="text-muted">{{ $d->signataire_email }}</small>
                @endif
              </td>
              <td>
                @if($d->signe_at)
                  {{ $d->signe_at->format('d/m/Y H:i') }}
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td>
                @if($d->sha256_signe)
                  <code title="{{ $d->sha256_signe }}" data-bs-toggle="tooltip" style="font-size:0.75rem;">
                    {{ Str::limit($d->sha256_signe, 16) }}…
                  </code>
                @else
                  <span class="text-muted">—</span>
                @endif
              </td>
              <td class="text-center">
                @can('download-signature')
                  @if($d->pdf_signe_path && \Illuminate\Support\Facades\Storage::exists($d->pdf_signe_path))
                    <a href="{{ route('signature.certificat', encrypt_id($d->id)) }}"
                       class="btn btn-sm btn-outline-success"
                       title="Télécharger le contrat signé">
                      <i class="bx bx-download"></i>
                    </a>
                  @else
                    <span class="text-muted small">PDF manquant</span>
                  @endif
                @endcan
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center py-4 text-muted">
                <i class="bx bx-folder-open fs-4 d-block mb-1"></i>
                {{ __('pages.archives_empty') }}
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
  // Init Bootstrap tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
    new bootstrap.Tooltip(el);
  });
</script>
@endsection
