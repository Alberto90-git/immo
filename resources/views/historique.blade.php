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
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-history me-2"></i>Journal des activites</h5>
            @if(isset($choix) && $choix == 'by_date')
                <span class="badge bg-white text-primary fs-6">
                    <i class="bx bx-calendar me-1"></i>
                    {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                    @if($dateDebut !== $dateFin)
                        → {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                    @endif
                </span>
            @endif
        </div>

        <div class="card-body">
            <!-- Formulaire de recherche -->
            @php $currentChoix = $choix ?? 'by_date'; @endphp
            <form action="{{ route('chekhistorique') }}" method="post" class="row g-3 align-items-end mb-4">
                @csrf
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Type de recherche</label>
                    <select class="form-select" id="choix" name="choix" onchange="displayChoix();" required>
                        <option value="by_date" {{ $currentChoix == 'by_date' ? 'selected' : '' }}>Par periode</option>
                        <option value="by_user" {{ $currentChoix == 'by_user' ? 'selected' : '' }}>Par utilisateur</option>
                    </select>
                </div>

                <div class="col-md-3" id="date_debutDiv">
                    <label class="form-label fw-semibold">Date debut</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control"
                           value="{{ $dateDebut ?? now()->format('Y-m-d') }}">
                </div>

                <div class="col-md-3" id="date_finDiv">
                    <label class="form-label fw-semibold">Date fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control"
                           value="{{ $dateFin ?? now()->format('Y-m-d') }}">
                </div>

                <div class="col-md-4" id="user_nameDiv" style="display: none;">
                    <label class="form-label fw-semibold">Utilisateur</label>
                    <select class="form-select" id="user_name" name="user_name">
                        <option value="">Selectionner un utilisateur</option>
                        @if(isset($users))
                            @foreach($users as $val)
                                <option value="{{ $val->id }}" {{ isset($userName) && $userName == $val->id ? 'selected' : '' }}>
                                    {{ $val->nom }} {{ $val->prenom }}
                                </option>
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
                            <th class="text-center" style="width: 4%;">
                                <i class="bx bx-hash me-1"></i>N
                            </th>
                            <th style="width: 14%;">
                                <i class="bx bx-user me-1"></i>Utilisateur
                            </th>
                            <th style="width: 26%;">
                                <i class="bx bx-message-detail me-1"></i>Description de l'action
                            </th>
                            <th style="width: 22%;">
                                <i class="bx bx-history me-1"></i>Ancienne valeur
                            </th>
                            <th style="width: 22%;">
                                <i class="bx bxs-edit me-1"></i>Nouvelle valeur
                            </th>
                            <th class="text-center" style="width: 12%;">
                                <i class="bx bx-calendar me-1"></i>Date et heure
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($all) && count($all) > 0)
                            @foreach($all as $items)
                                @php
                                    $author = isset($usersMap[$items->causer_id]) ? $usersMap[$items->causer_id] : null;
                                    $props  = $items->properties;
                                    $old    = ($props instanceof \Illuminate\Support\Collection) ? (array) ($props->get('old') ?? []) : [];
                                    $new    = ($props instanceof \Illuminate\Support\Collection) ? (array) ($props->get('new') ?? []) : [];
                                    $labels = [
                                        'nom' => 'Nom', 'prenom' => 'Prénom', 'telephone' => 'Tél',
                                        'nom_maison' => 'Maison', 'quartier' => 'Quartier',
                                        'nombre_chambre' => 'Nb chambres', 'numero_chambre' => 'N° chambre',
                                        'type_chambre' => 'Type', 'prix_chambre' => 'Prix chambre',
                                        'profession' => 'Profession', 'mode_paiement' => 'Mode paiement',
                                        'prix_mois' => 'Prix/mois', 'nombre_caution' => 'Nb caution',
                                        'nombre_avance' => 'Nb avance', 'date_entree' => 'Date entrée',
                                        'adresse' => 'Adresse', 'email' => 'Email',
                                        'montant' => 'Montant', 'mois' => 'Mois',
                                        'type_paiement' => 'Type paiement', 'date_paiement' => 'Date paiement',
                                        'locataire' => 'Locataire', 'maison' => 'Maison',
                                        'name' => 'Fonction', 'permissions' => 'Permissions',
                                        'superficie' => 'Superficie', 'prix' => 'Prix',
                                        'zone_voulu' => 'Zone', 'budget' => 'Budget',
                                        'etat' => 'État', 'client_acheteur' => 'Acheteur',
                                        'proprietaire_id' => 'Propriétaire',
                                    ];
                                @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($author)
                                            <span class="badge bg-label-secondary">{{ $author->nom }} {{ $author->prenom }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $items->description }}</span>
                                    </td>
                                    <td>
                                        @if(count($old) > 0)
                                            <ul class="list-unstyled mb-0" style="font-size:0.75rem;">
                                                @foreach($old as $k => $v)
                                                    @if($v !== null && $v !== '')
                                                        <li>
                                                            <span class="text-muted">{{ $labels[$k] ?? $k }} :</span>
                                                            @if(is_array($v))
                                                                <em>{{ implode(', ', $v) }}</em>
                                                            @else
                                                                <strong>{{ $v }}</strong>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted fst-italic" style="font-size:0.75rem;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(count($new) > 0)
                                            <ul class="list-unstyled mb-0" style="font-size:0.75rem;">
                                                @foreach($new as $k => $v)
                                                    @if($v !== null && $v !== '')
                                                        <li>
                                                            <span class="text-muted">{{ $labels[$k] ?? $k }} :</span>
                                                            @if(is_array($v))
                                                                <em class="text-success">{{ implode(', ', $v) }}</em>
                                                            @else
                                                                <strong class="text-success">{{ $v }}</strong>
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-muted fst-italic" style="font-size:0.75rem;">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-label-primary" style="font-size:0.7rem; line-height:1.5;">
                                            <i class="bx bx-time-five me-1"></i>
                                            {{ Carbon\Carbon::parse($items->created_at)->format('d/m/Y') }}<br>
                                            {{ Carbon\Carbon::parse($items->created_at)->format('H:i') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Aucune activité trouvée</td>
                            </tr>
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

        const userDiv       = document.getElementById('user_nameDiv');
        const dateDebutDiv  = document.getElementById('date_debutDiv');
        const dateFinDiv    = document.getElementById('date_finDiv');
        const userInput     = document.getElementById('user_name');
        const dateDebutInput= document.getElementById('date_debut');
        const dateFinInput  = document.getElementById('date_fin');

        userDiv.style.display      = 'none';
        dateDebutDiv.style.display = 'none';
        dateFinDiv.style.display   = 'none';
        userInput.required         = false;
        dateDebutInput.required    = false;
        dateFinInput.required      = false;

        if (val === 'by_user') {
            userDiv.style.display = 'block';
            userInput.required    = true;
        } else if (val === 'by_date') {
            dateDebutDiv.style.display = 'block';
            dateFinDiv.style.display   = 'block';
            dateDebutInput.required    = true;
            dateFinInput.required      = true;
        }
    }

    // Initialiser l'affichage au chargement selon le choix courant
    document.addEventListener('DOMContentLoaded', function () {
        displayChoix();
    });
</script>
@endsection
