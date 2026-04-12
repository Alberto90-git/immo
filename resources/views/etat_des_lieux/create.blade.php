@extends('layouts.template')

@section('title')
<title>Nouvel état des lieux – Lokativ</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex align-items-center mb-4 gap-2">
    <a href="{{ route('etat_des_lieux.index') }}" class="btn btn-icon btn-outline-secondary">
      <i class="bx bx-arrow-back"></i>
    </a>
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">États des lieux /</span> Nouveau
    </h4>
  </div>

  @include('etat_des_lieux._create_form')

  <div class="d-flex gap-2 justify-content-end mb-5">
    <a href="{{ route('etat_des_lieux.index') }}" class="btn btn-outline-secondary">Annuler</a>
    <button type="button" class="btn btn-primary px-4" id="btnSaveEtat">
      <span id="btnSaveText"><i class="bx bx-save me-1"></i> Enregistrer</span>
      <span id="btnSaveSpinner" class="d-none">
        <span class="spinner-border spinner-border-sm me-1"></span> En cours...
      </span>
    </button>
  </div>

</div>
@endsection
