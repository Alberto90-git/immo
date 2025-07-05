@extends('layouts.template')

@section('title')
    <title>Gestion des rôles</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Titre de la page -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">
            <span class="text-muted fw-light">Accueil /</span> Gestion des rôles / Liste des rôles
        </h4>
        <a href="{{ route('roles.create') }}" class="btn btn-success rounded-pill">
            <i class="bx bx-plus me-1"></i> Nouveau rôle
        </a>
    </div>

    <!-- Tableau des rôles -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des rôles</h5>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-refresh"></i> Actualiser
            </a>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nom du rôle</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $index => $role)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $role->name }}</span>
                            </td>
                            <td class="text-center">
                                @can('liste-role')
                                <a href="{{ route('roles.show', $role->id) }}" class="btn btn-icon btn-outline-info me-1" title="Voir">
                                    <i class="bx bx-show"></i>
                                </a>
                                @endcan

                                @can('modifier-role')
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-icon btn-outline-warning me-1" title="Modifier">
                                    <i class="bx bx-edit-alt"></i>
                                </a>
                                @endcan

                                @can('supprimer-role')
                                <button type="button" class="btn btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $role->id }}" title="Supprimer">
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
                                        <h5 class="modal-title" id="deleteModalLabel{{ $role->id }}">Confirmation de suppression</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        Êtes-vous sûr de vouloir supprimer le rôle <strong>{{ $role->name }}</strong> ?
                                    </div>
                                    <div class="modal-footer">
                                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Aucun rôle trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
