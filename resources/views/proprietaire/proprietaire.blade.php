@extends('layouts.template')

@section('title')
    <title>{{ __('pages.owner_title') }}</title>
@endsection



@section('content')

@include('notification.display_message')


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.owner_breadcrumb') }}</h4>
    
    @can('ajoute-proprietaire')
        <div class="col-md-6">
            <div class="demo-inline-spacing">
                <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" 
                        data-bs-toggle="modal" data-bs-target="#AjouerProprietaire">
                    <span class="bx bx-plus"></span>
                </button>
            </div>
        </div>
        <br />
    @endcan

    {{-- Routes cachées pour JavaScript --}}
    <div id="update-route" data-url="{{ route('update_proprio') }}" style="display: none;"></div>
    <div id="delete-route" data-url="{{ route('destroy_proprio') }}" style="display: none;"></div>

    {{-- Modal d'ajout --}}
    @include('proprietaire.ajouter')
    
    {{-- ⭐ SOLUTION 2: Tout dans liste.blade.php (tableau + modals) --}}
    @include('proprietaire.liste', ['allProprios' => $allProprios])

</div>

@endsection

{{-- Scripts JavaScript --}}
@push('scripts')
<script>
    // Variables JavaScript pour les permissions
    var canModifyProprietaire = @json(auth()->user()->can('modify-proprietaire'));
    var canDeleteProprietaire = @json(auth()->user()->can('delete-proprietaire'));
</script>

{{-- Charger le script AJAX --}}
<script src="{{ asset('js/proprietaire_ajax.js') }}"></script>
@endpush