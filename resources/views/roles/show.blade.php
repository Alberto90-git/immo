@extends('layouts.template')

@section('title')
    <title>{{ __('pages.role_show_title') }}</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y" style="background-color: #f6f8fb; border-radius: 10px; padding: 25px;">

    <!-- Titre principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">
            <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.role_title') }} / {{ __('pages.role_show_title') }}
        </h4>
        <a href="{{ route('roles.index') }}" class="btn btn-outline-dark rounded-pill">
            <i class="bx bx-arrow-back"></i> {{ __('common.btn_back') }}
        </a>
    </div>

    <!-- Informations du fonction -->
    <div class="card mb-4 shadow-sm" style="background-color: #f0f4f7;">
        <div class="card-body">
            <h5 class="card-title mb-3 text-primary">
                <i class="bx bx-user-circle me-2"></i> {{ __('pages.role_card_info') }}
            </h5>
            <p class="mb-0"><strong>{{ __('pages.role_card_label_name') }}</strong> 
                <span class="badge bg-secondary text-white px-3 py-2 rounded-pill">
                    {{ $role->name }}
                </span>
            </p>
        </div>
    </div>

    <!-- Liste des permissions -->
    <div class="card shadow-sm border-0" style="background-color:rgb(234, 237, 241);">
        <div class="card-header border-bottom bg-primary text-white">
            <h5 class="mb-0 text-white text-center">
                <i class="bx bx-lock-alt me-2"></i> {{ __('pages.role_card_permissions') }}
            </h5>
        </div> <br />
        <div class="card-body">
            @if (!empty($rolePermissions) && $rolePermissions->count())
                <div class="row">
                    @foreach($rolePermissions as $permission)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-2 bg-white d-flex align-items-center shadow-sm small">
                                <i class="fa fa-check-circle text-success me-2"></i>
                                <span>{{ $permission->label }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    {{ __('pages.role_no_permissions') }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
