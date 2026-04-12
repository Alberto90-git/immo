<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signature état des lieux</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { background: #f5f5f9; font-family: 'Segoe UI', sans-serif; }
    .sign-container { max-width: 820px; margin: 40px auto; }
    .header-agence { background: linear-gradient(135deg, #696cff, #5a5fba); color: white; border-radius: 12px 12px 0 0; padding: 24px; }
    .badge-etat { font-size: 0.75rem; }
    .table th { font-size: 0.8rem; background: #f8f9fa; }
    .table td { font-size: 0.85rem; }
    .btn-signer { font-size: 1.1rem; font-weight: 600; padding: 14px 40px; border-radius: 30px; }
    .signe-block { background: #d4edda; border-radius: 12px; padding: 30px; text-align: center; }
  </style>
</head>
<body>
<div class="sign-container px-3">

  {{-- ── Déjà signé ──────────────────────────────────────────────────────── --}}
  @if(!empty($dejaSigné))
    <div class="card mt-5">
      <div class="signe-block">
        <div class="fs-1 mb-3">✅</div>
        <h4 class="text-success fw-bold">Document déjà signé</h4>
        <p class="text-muted">
          Cet état des lieux a été signé le
          {{ \Carbon\Carbon::parse($etat->signe_locataire_at)->format('d/m/Y à H:i') }}.
        </p>
        <p class="text-muted small">Merci pour votre signature.</p>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  </html>
  @php return; @endphp
  @endif

  {{-- ── En-tête agence ──────────────────────────────────────────────────── --}}
  <div class="header-agence mb-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
      <div>
        <h5 class="mb-1">{{ $agence['designation'] ?? config('app.name') }}</h5>
        @if(!empty($agence['adresse']))<div class="opacity-75 small">{{ $agence['adresse'] }}</div>@endif
        @if(!empty($agence['telephone']))<div class="opacity-75 small">{{ $agence['telephone'] }}</div>@endif
      </div>
      <div class="text-end">
        @if($etat->type === 'entree')
          <span class="badge bg-white text-primary badge-etat">ÉTAT D'ENTRÉE</span>
        @else
          <span class="badge bg-warning text-dark badge-etat">ÉTAT DE SORTIE</span>
        @endif
        <div class="small opacity-75 mt-1">{{ $etat->date_etat->format('d/m/Y') }}</div>
      </div>
    </div>
  </div>

  <div class="card rounded-top-0 shadow-sm mb-4">
    <div class="card-body">
      <h5 class="fw-bold mb-3 text-center">PROCÈS-VERBAL D'ÉTAT DES LIEUX</h5>

      {{-- Infos locataire & logement --}}
      <div class="row g-2 mb-4 text-sm">
        <div class="col-6">
          <div class="text-muted small">Locataire</div>
          <div class="fw-semibold">{{ $etat->locataire->nom ?? '–' }} {{ $etat->locataire->prenom ?? '' }}</div>
        </div>
        <div class="col-6">
          <div class="text-muted small">Logement</div>
          <div class="fw-semibold">{{ $etat->maison->nom_maison ?? '–' }} – N°{{ $etat->chambre->numero_chambre ?? '–' }}</div>
        </div>
      </div>

      {{-- Pièces --}}
      @foreach($etat->pieces as $piece)
        <h6 class="fw-bold text-primary mt-4 mb-2">{{ $piece->nom_piece }}</h6>
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead>
              <tr>
                <th>Élément</th>
                @if($etat->type === 'sortie' && $etat->etatEntree)
                  <th class="text-center" style="width:14%">Entrée</th>
                @endif
                <th class="text-center" style="width:14%">État</th>
                <th>Observations</th>
                @if($etat->type === 'sortie')
                  <th class="text-center" style="width:12%">Dégradation</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($piece->elements as $el)
                <tr class="{{ $el->degradation_detectee ? 'table-danger' : '' }}">
                  <td class="fw-semibold">{{ $el->element }}</td>
                  @if($etat->type === 'sortie' && $etat->etatEntree)
                    <td class="text-center">
                      @php $elE = $entreeMap[$piece->nom_piece][$el->element] ?? null; @endphp
                      @if($elE)
                        <span class="badge {{ $elE->etat_badge_class }} badge-etat">{{ $elE->etat_label }}</span>
                      @else –
                      @endif
                    </td>
                  @endif
                  <td class="text-center">
                    <span class="badge {{ $el->etat_badge_class }} badge-etat">{{ $el->etat_label }}</span>
                  </td>
                  <td class="small">{{ $el->description ?: '–' }}</td>
                  @if($etat->type === 'sortie')
                    <td class="text-center">
                      @if($el->degradation_detectee)
                        <span class="badge bg-danger badge-etat">Oui</span>
                      @else
                        <span class="badge bg-success badge-etat">Non</span>
                      @endif
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endforeach

      {{-- Retenue --}}
      @if($etat->type === 'sortie' && $etat->retenue_caution > 0)
        <div class="alert alert-danger mt-3 mb-0">
          <strong>Total retenu sur caution :</strong>
          <span class="float-end fw-bold">{{ number_format($etat->retenue_caution, 0, ',', ' ') }} FCFA</span>
        </div>
      @endif

      @if($etat->notes_generales)
        <div class="alert alert-light border mt-3 mb-0">
          <strong>Notes :</strong> {{ $etat->notes_generales }}
        </div>
      @endif
    </div>
  </div>

  {{-- ── Bloc signature ───────────────────────────────────────────────────── --}}
  <div class="card shadow-sm mb-5">
    <div class="card-body text-center py-4">
      <h5 class="fw-bold mb-2">Signature du locataire</h5>
      <p class="text-muted mb-4">
        En cliquant sur « Je signe », vous confirmez avoir pris connaissance<br>
        du présent état des lieux et l'acceptez.
      </p>
      <button type="button" class="btn btn-success btn-signer" id="btnSigner">
        ✅ Je signe et j'accepte cet état des lieux
      </button>
      <div class="text-muted small mt-3">
        {{ $etat->locataire->nom ?? '' }} {{ $etat->locataire->prenom ?? '' }} –
        Le {{ now()->format('d/m/Y') }}
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('btnSigner').addEventListener('click', function () {
    Swal.fire({
        title: 'Confirmer la signature',
        html: 'En confirmant, vous signez électroniquement cet état des lieux.<br><strong>Cette action est définitive.</strong>',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        confirmButtonText: 'Je confirme et signe',
        cancelButtonText: 'Annuler',
    }).then(result => {
        if (!result.isConfirmed) return;
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Signature en cours…';

        fetch('{{ route("etat_des_lieux.signer_confirmer", $token) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({}),
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                document.getElementById('btnSigner').closest('.card-body').innerHTML = `
                  <div class="signe-block">
                    <div class="fs-1 mb-3">✅</div>
                    <h4 class="text-success fw-bold">Signature enregistrée !</h4>
                    <p class="text-muted">Merci, votre signature a été prise en compte.</p>
                  </div>`;
            } else {
                Swal.fire('Erreur', data.message, 'error');
                this.disabled = false;
                this.innerHTML = '✅ Je signe et j\'accepte cet état des lieux';
            }
        })
        .catch(() => {
            Swal.fire('Erreur', 'Une erreur est survenue. Veuillez réessayer.', 'error');
            this.disabled = false;
            this.innerHTML = '✅ Je signe et j\'accepte cet état des lieux';
        });
    });
});
</script>
</body>
</html>
