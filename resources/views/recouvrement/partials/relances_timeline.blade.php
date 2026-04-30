@if($dossier->relances->count())
<ul class="timeline ps-3 py-3 pe-3 mb-0">
  @foreach($dossier->relances->sortByDesc('envoye_le') as $relance)
  <li class="timeline-item pb-3 border-start border-2 border-{{ $relance->statut === 'echec' ? 'danger' : ($relance->statut === 'simule' ? 'info' : 'success') }} ms-3 ps-3 position-relative">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
      <div>
        {!! $relance->type_badge !!}
        <span class="badge bg-light text-dark ms-1"><i class="{{ $relance->canal_icon }} me-1"></i>{{ $relance->canal_label }}</span>
        {!! $relance->statut_badge !!}
      </div>
      <small class="text-muted">{{ $relance->envoye_le->format('d/m/Y H:i') }}</small>
    </div>
    @if($relance->message)
    <p class="small text-muted mt-1 mb-0">{{ Str::limit($relance->message, 120) }}</p>
    @endif
  </li>
  @endforeach
</ul>
@else
<div class="text-center text-muted py-4">Aucune relance envoyée</div>
@endif
