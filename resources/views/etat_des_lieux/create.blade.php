@extends('layouts.template')

@section('title')
<title>Nouvel état des lieux – Lokativ</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">États des lieux /</span> Nouveau
    </h4>
    <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
      <i class="bx bx-arrow-back me-1"></i> Retour
    </button>
  </div>

  @include('etat_des_lieux._create_form')

  <div class="d-flex gap-2 justify-content-end mb-5">
    <button type="button" class="btn btn-primary px-4" id="btnSaveEtat">
      <span id="btnSaveText"><i class="bx bx-save me-1"></i> Enregistrer</span>
      <span id="btnSaveSpinner" class="d-none">
        <span class="spinner-border spinner-border-sm me-1"></span> En cours...
      </span>
    </button>
  </div>

</div>
@endsection
