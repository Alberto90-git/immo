@extends('layouts.template')

@section('content')

@section('title')
    <title>Gestion logs</title>
    @endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion logs</h4>

    <!-- Hoverable Table rows -->
    <div class="card">
        <h5 class="card-header text-center">Liste des historiques</h5>
        <div class="row align-items-center">
            <form action="{{ route('chekhistorique') }}" method="post" class="d-flex align-items-center">
                @csrf
                <div class="me-3">
                    <select class="form-select" id="choix" name="choix" onchange="displayChoix();" required>
                        <option disabled selected value="">Choix de recherche</option>
                        <option value="by_user">Par utilisateur</option>
                        <option value="by_date">Par date</option>
                    </select>
                </div>

                <!-- Champs pour les dates -->
                <div class="me-3 d-flex" id="date_debutDiv" style="display: none;">
                    <input type="date" name="date_debut" id="date_debut" class="form-control">
                </div>

                <div class="me-3 d-flex" id="date_finDiv" style="display: none;">
                    <input type="date" name="date_fin" id="date_fin" class="form-control">
                </div>

                <!-- Champ pour l'utilisateur -->
                <div class="me-3" id="user_nameDiv" style="display: none;">
                    <select class="form-select" id="user_name" name="user_name" aria-label="Floating label select example">
                        <option disabled selected value="">Séléctionner un utilisateur</option>
                        @if(isset($users))
                            @foreach($users as $val)
                                <option value="{{ $val->id }}">{{ $val->nom }} {{ $val->prenom }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-2 center">
                    <button class="btn btn-primary" type="submit">Recherche</button>
                </div>
            </form>
        </div>

        <div class="table-responsive text-nowrap">
            <table id="example" class="table table-hover border-primary" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Description de l'action</th>
                        <th scope="col">Date opération</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @if(isset($all))
                        @foreach($all as $items)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $items->description }}</td>
                                <td>{{ Carbon\Carbon::parse($items->created_at)->format('d/m/Y à H:i') }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    function displayChoix() {
        const val = document.getElementById('choix').value;

        // Sélection des éléments
        const userDiv = document.getElementById('user_nameDiv');
        const dateDebutDiv = document.getElementById('date_debutDiv');
        const dateFinDiv = document.getElementById('date_finDiv');
        const userInput = document.getElementById('user_name');
        const dateDebutInput = document.getElementById('date_debut');
        const dateFinInput = document.getElementById('date_fin');

        // Réinitialisation : cacher tout
        userDiv.style.display = 'none';
        dateDebutDiv.style.display = 'none';
        dateFinDiv.style.display = 'none';
        userInput.required = false;
        dateDebutInput.required = false;
        dateFinInput.required = false;

        // Affichage en fonction du choix
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
