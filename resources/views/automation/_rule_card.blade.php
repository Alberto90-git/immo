@php
  $joursStr = '';
  if ($rule->jours_declenchement) {
      $joursStr = collect($rule->jours_declenchement)->map(fn($j) => $j >= 0 ? "J+{$j}" : "J{$j}")->implode(', ');
  }
@endphp

<div class="col-md-6 col-lg-4" id="ruleCard-{{ $rule->id }}">
  <div class="card shadow-sm h-100">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h6 class="fw-bold mb-0">{{ $rule->type_label }}</h6>
        @if($rule->actif)
          <span class="badge bg-success">{{ __('ui.automation.rule_active') }}</span>
        @else
          <span class="badge bg-secondary">{{ __('ui.automation.rule_inactive') }}</span>
        @endif
      </div>
      <div class="mb-2">
        <span class="badge bg-label-info me-1">{{ $rule->canal_label }}</span>
        <span class="badge bg-label-secondary"><i class="bx bx-time-five me-1"></i>{{ $rule->heure_envoi }}</span>
      </div>

      @if($rule->type === 'rappel_loyer' && $joursStr)
        <div class="text-muted small mt-1"><i class="bx bx-calendar me-1"></i>{{ $joursStr }}</div>
      @elseif($rule->type === 'escalade_impaye')
        <div class="text-muted small mt-1">
          <i class="bx bx-time me-1"></i>SMS à J+{{ $rule->delai_escalade_sms ?? 3 }} — WA à J+{{ $rule->delai_escalade_whatsapp ?? 5 }}
        </div>
      @elseif(in_array($rule->type, ['preavis_bail', 'renouvellement']))
        <div class="text-muted small mt-1">
          <i class="bx bx-calendar-check me-1"></i>{{ __('ui.automation.months_before', ['n' => $rule->mois_avant_echeance ?? 2]) }}
        </div>
      @elseif($rule->type === 'rapport_mensuel')
        <div class="text-muted small mt-1">
          <i class="bx bx-calendar me-1"></i>{{ __('ui.automation.day_of_month', ['jour' => $rule->jour_du_mois ?? 1]) }}
        </div>
      @endif
    </div>
    <div class="card-footer d-flex gap-2 justify-content-end py-2">
      @can('toggle-automation')
      <button class="btn btn-sm btn-outline-{{ $rule->actif ? 'warning' : 'success' }} btn-toggle-actif"
              data-id="{{ $rule->id }}" title="{{ $rule->actif ? __('ui.common.deactivate') : __('ui.common.activate') }}">
        <i class="bx bx-{{ $rule->actif ? 'pause' : 'play' }}"></i>
      </button>
      @endcan
      @can('delete-automation')
      <button class="btn btn-sm btn-outline-danger btn-supprimer-regle"
              data-id="{{ $rule->id }}" title="{{ __('ui.common.delete') }}">
        <i class="bx bx-trash"></i>
      </button>
      @endcan
    </div>
  </div>
</div>
