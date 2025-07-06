<div class="card shadow-sm border-0">
    <div class="card-header bg-primary  text-center">
        <h5 class="mb-0 text-white"><i class="bx bx-user me-1"></i> Liste des propriétaires</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Agence</th>
                        <th>Nom & prénom</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @can('Consulter-proprietaire')
                        @if (isset($allProprios))
                            @foreach ($allProprios as $item)
                                <tr>
                                    <td>
                                        <span>
                                            {{ get_annexee_name($item->idannexe_ref) }}
                                        </span>
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
                                            <a class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Modifier"
                                                data-bs-toggle="modal" data-bs-target="#modifier{{ $loop->iteration }}">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                        @endcan

                                        @can('delete-proprietaire')
                                            <a class="btn btn-sm btn-outline-danger rounded-circle" title="Supprimer"
                                                data-bs-toggle="modal" data-bs-target="#supprimer{{ $loop->iteration }}">
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


<!-- CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">

<!-- JS -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            language: {
                search: "Rechercher :",
                lengthMenu: "Afficher _MENU_ lignes",
                zeroRecords: "Aucun résultat trouvé",
                info: "Page _PAGE_ sur _PAGES_",
                infoEmpty: "Aucune donnée disponible",
                paginate: {
                    first: "Début",
                    last: "Fin",
                    next: "Suivant",
                    previous: "Précédent"
                }
            }
        });
    });
</script>
