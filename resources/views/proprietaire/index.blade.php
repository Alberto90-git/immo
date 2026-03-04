@extends('layouts.template')

@section('title')
    <title>Gestion propriétaire</title>
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    @include('notification.display_message')


    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion propriétaire</h4>

    @can('ajoute-proprietaire')
        <div class="col-md-6 mb-3">
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#AjouterProprietaire">
                <span class="bx bx-plus"></span>
            </button>
        </div>
    @endcan

    {{-- Modal Ajouter --}}
    <div class="modal fade" id="AjouterProprietaire" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Ajouter un propriétaire</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" method="POST" action="{{ route('create_propre') }}">
                        @csrf

                        <div class="col-md-6">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" placeholder="Nom du propriétaire" required>
                        </div>

                        <div class="col-md-6">
                            <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prenom" placeholder="Prénom du propriétaire" required>
                        </div>

                        <div class="col-md-6">
                            <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" name="telephone" placeholder="XX XX XX XX XX" required>
                        </div>

                        <div class="col-md-6">
                            <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adresse" placeholder="Adresse complète" required>
                        </div>

                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="bx bx-save me-1"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des propriétaires --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-center">
            <h5 class="mb-0 text-white"><i class="bx bx-user me-1"></i> Liste des propriétaires</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Agence</th>
                            <th>Nom & prénoms</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @can('Consulter-proprietaire')
                            @if (isset($allProprios) && count($allProprios) > 0)
                                @foreach ($allProprios as $item)
                                    <tr>
                                        <td>
                                            <span>{{ $item->annexe->designation ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $item->nom }} {{ $item->prenom }}</strong>
                                        </td>
                                        <td>
                                            <i class="bx bx-phone-call text-success me-1"></i> {{ $item->telephone }}
                                        </td>
                                        <td>
                                            <i class="bx bx-map text-secondary me-1"></i> {{ $item->adresse }}
                                        </td>
                                        <td class="text-center">
                                            @can('modify-proprietaire')
                                                <a class="btn btn-sm btn-outline-primary rounded-circle me-1"
                                                   title="Modifier"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#modifier{{ $item->id }}">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                            @endcan

                                            @can('delete-proprietaire')
                                                <a class="btn btn-sm btn-outline-danger rounded-circle"
                                                   title="Supprimer"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#supprimer{{ $item->id }}">
                                                    <i class="bx bx-trash"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endcan
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modals de modification et suppression --}}
    @if (isset($allProprios) && count($allProprios) > 0)
        @foreach ($allProprios as $item)
            {{-- Modal Modifier --}}
            <div class="modal fade" id="modifier{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Modifier un propriétaire</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3" method="POST" action="{{ route('update_proprio') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">

                                <div class="col-md-6">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nom" value="{{ $item->nom }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="prenom" value="{{ $item->prenom }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="telephone" value="{{ $item->telephone }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="adresse" value="{{ $item->adresse }}" required>
                                </div>

                                <div class="modal-footer mt-3">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="bx bx-save me-1"></span> Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Supprimer --}}
            <div class="modal fade" id="supprimer{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white">Confirmation</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body text-center">
                            <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                            <p class="mt-3">Voulez-vous vraiment supprimer ce propriétaire ?</p>
                            <p><strong class="text-danger">{{ $item->nom }} {{ $item->prenom }}</strong></p>
                            <small class="text-muted">Cette action supprimera également toutes les maisons, chambres et locataires associés.</small>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form method="POST" action="{{ route('destroy_proprio') }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
                                <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

</div>

@endsection
