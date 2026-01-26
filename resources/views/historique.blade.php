@extends('layouts.template')

@section('content')

@section('title')
    <title>Historique des connexions</title>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Accueil /</span> Historique des activites
    </h4>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bx bx-history me-2"></i>Journal des activites</h5>
        </div>

        <div class="card-body">
            <!-- Formulaire de recherche -->
            <form action="{{ route('chekhistorique') }}" method="post" class="row g-3 align-items-end mb-4">
                @csrf
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Type de recherche</label>
                    <select class="form-select" id="choix" name="choix" onchange="displayChoix();" required>
                        <option disabled selected value="">Choisir...</option>
                        <option value="by_user">Par utilisateur</option>
                        <option value="by_date">Par periode</option>
                    </select>
                </div>

                <div class="col-md-3" id="date_debutDiv" style="display: none;">
                    <label class="form-label fw-semibold">Date debut</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control">
                </div>

                <div class="col-md-3" id="date_finDiv" style="display: none;">
                    <label class="form-label fw-semibold">Date fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control">
                </div>

                <div class="col-md-4" id="user_nameDiv" style="display: none;">
                    <label class="form-label fw-semibold">Utilisateur</label>
                    <select class="form-select" id="user_name" name="user_name">
                        <option disabled selected value="">Selectionner un utilisateur</option>
                        @if(isset($users))
                            @foreach($users as $val)
                                <option value="{{ $val->id }}">{{ $val->nom }} {{ $val->prenom }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bx bx-search me-1"></i>Rechercher
                    </button>
                </div>
            </form>

            <!-- Tableau des resultats -->
            <div class="table-responsive">
                <table id="example" class="table table-striped table-hover align-middle" style="width:100%">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width: 8%;">
                                <i class="bx bx-hash me-1"></i>N
                            </th>
                            <th style="width: 65%;">
                                <i class="bx bx-message-detail me-1"></i>Description de l'action
                            </th>
                            <th class="text-center" style="width: 27%;">
                                <i class="bx bx-calendar me-1"></i>Date et heure
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($all) && count($all) > 0)
                            @foreach($all as $items)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="text-dark">{{ $items->description }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-primary">
                                            <i class="bx bx-time-five me-1"></i>
                                            {{ Carbon\Carbon::parse($items->created_at)->format('d/m/Y') }}
                                            <span class="text-muted">a</span>
                                            {{ Carbon\Carbon::parse($items->created_at)->format('H:i') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function displayChoix() {
        const val = document.getElementById('choix').value;

        const userDiv = document.getElementById('user_nameDiv');
        const dateDebutDiv = document.getElementById('date_debutDiv');
        const dateFinDiv = document.getElementById('date_finDiv');
        const userInput = document.getElementById('user_name');
        const dateDebutInput = document.getElementById('date_debut');
        const dateFinInput = document.getElementById('date_fin');

        // Reinitialisation
        userDiv.style.display = 'none';
        dateDebutDiv.style.display = 'none';
        dateFinDiv.style.display = 'none';
        userInput.required = false;
        dateDebutInput.required = false;
        dateFinInput.required = false;

        // Affichage selon le choix
        if (val === 'by_user') {
            userDiv.style.display = 'block';
            userInput.required = true;
        } else if (val === 'by_date') {
            dateDebutDiv.style.display = 'block';
            dateFinDiv.style.display = 'block';
            dateDebutInput.required = true;
            dateFinInput.required = true;
        }
    }
</script>
@endsection
