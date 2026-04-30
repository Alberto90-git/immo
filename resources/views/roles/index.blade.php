@extends('layouts.template')

@section('title')
    <title>{{ __('pages.role_title') }}</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    @include('notification.display_message')


    <!-- Titre de la page -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">
            <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.role_title') }} / {{ __('pages.role_list_title') }}
        </h4>
        @can('ajouter-role')
            <a href="{{ route('roles.create') }}" class="btn btn-primary rounded-pill">
                <i class="bx bx-plus me-1"></i> {{ __('pages.role_btn_new') }}
            </a>
        @endcan
    </div>

    <!-- Tableau des fonctions -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ __('pages.role_list_title') }}</h5>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-refresh"></i> {{ __('common.btn_refresh') }}
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('pages.role_th_number') }}</th>
                        <th>{{ __('pages.role_th_name') }}</th>
                        <th class="text-center">{{ __('pages.role_th_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @can('liste-role')
                        @forelse ($roles as $index => $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                </td>
                                <td class="text-center">
                                    @can('liste-role')
                                    <a href="{{ route('roles.show', encrypt_id($role->id)) }}" class="btn btn-icon btn-outline-info me-1" title="{{ __('common.title_view') }}">
                                        <i class="bx bx-show"></i>
                                    </a>
                                    @endcan

                                    @can('modifier-role')
                                    <a href="{{ route('roles.edit', encrypt_id($role->id)) }}" class="btn btn-icon btn-outline-warning me-1" title="{{ __('common.title_edit') }}">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                    @endcan

                                    @can('supprimer-role')
                                    <button type="button" class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $role->id }}" title="{{ __('common.title_delete') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>

                            {{-- Modal de suppression --}}
                            <div class="modal fade" id="deleteModal{{ $role->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $role->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $role->id }}">{{ __('pages.role_delete_title') }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.btn_close') }}"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ __('pages.role_delete_msg') }} <strong>{{ $role->name }}</strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <form method="POST" action="{{ route('roles.destroy', encrypt_id($role->id)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
                                                <button type="submit" class="btn btn-danger">{{ __('common.btn_delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">{{ __('pages.role_empty') }}</td>
                            </tr>
                        @endforelse
                    @endcan
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
