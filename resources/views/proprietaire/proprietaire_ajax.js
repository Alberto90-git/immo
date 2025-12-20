// ============================================
// INITIALISATION ET CONFIGURATION
// ============================================
function display_sweet_alert_over_modal(title, text, icon, buttonClass) {
    // Appeler votre fonction existante
    display_sweet_alerte2(title, text, icon, buttonClass);
    
    // Forcer le z-index après un court délai
    setTimeout(function() {
        const swalContainer = document.querySelector('.swal2-container');
        const swalPopup = document.querySelector('.swal2-popup');
        
        if (swalContainer) {
            swalContainer.style.zIndex = '10070';
        }
        if (swalPopup) {
            swalPopup.style.zIndex = '10071';
        }
    }, 10);
}
// Variable globale pour stocker l'instance DataTable
let proprietaireTable;

$(document).ready(function() {
    // Initialisation du DataTable
    initializeDataTable();
    
    // Initialisation du plugin téléphone international
    initializePhoneInput();
    
    // Gestion des événements
    setupEventListeners();
});

// ============================================
// INITIALISATION DATATABLE
// ============================================

function initializeDataTable() {
    proprietaireTable = $('#example').DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
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
        },
        buttons: [
            {
                extend: 'copy',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            },
            'colvis'
        ]
    });
    
    proprietaireTable.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
}

// ============================================
// INITIALISATION PLUGIN TÉLÉPHONE
// ============================================

function initializePhoneInput() {
    const phoneInput = document.querySelector("#telephone");
    if (phoneInput) {
        window.intlTelInput(phoneInput, {
            preferredCountries: ["bj", "fr", "ci"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    }
}

// ============================================
// VALIDATION ET FORMATAGE DU TÉLÉPHONE
// ============================================

function validateAndFormatPhone(inputId) {
    const input = document.getElementById(inputId);
    const iti = window.intlTelInputGlobals.getInstance(input);
    
    if (!iti.isValidNumber()) {
        $('.' + inputId + '_err').text('Numéro de téléphone invalide').show();
        return false;
    }
    
    return iti.getNumber();
}

// ============================================
// GESTION DES ÉVÉNEMENTS
// ============================================

function setupEventListeners() {
    // Cacher les messages d'erreur lors de la saisie
    $(':input').on('input change', function() {
        $('.' + $(this).attr("id") + '_err').hide();
    });
    
    $('select').on('change', function() {
        $('.' + $(this).attr("id") + '_err').hide();
    });
    
    // Gestion de la fermeture des modals
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.invalid-feedback').hide();
    });
}

// ============================================
// AFFICHAGE DES MESSAGES
// ============================================


function printErrorMsg(msg) {
    $.each(msg, function(key, value) {
        $('.' + key + '_err').text(value).show();
    });
}

// ============================================
// AJOUT D'UN PROPRIÉTAIRE
// ============================================

function save_proprietaire() {
    // Validation du numéro de téléphone
    const phoneInput = document.querySelector("#telephone");
    const iti = window.intlTelInputGlobals.getInstance(phoneInput);
    
    if (!iti.isValidNumber()) {
        $('.telephone_err').text('Numéro de téléphone invalide').show();
        return false;
    }
    
    const formattedPhone = iti.getNumber();
    
    // Préparation des données
    var data = new FormData();
    var form_data = $('#formulaireProprietaire').serializeArray();
    
    $.each(form_data, function(key, input) {
        if (input.name === "telephone") {
            data.append("telephone", formattedPhone);
        } else {
            data.append(input.name, input.value);
        }
    });

    // Configuration AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // URL correcte
    const url = $("#formulaireProprietaire").data('action-url') || "{{ route('create_propre') }}";

    // Requête AJAX
    $.ajax({
        url: url,
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function() {
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", true);
            $("#AjouerProprietaire button#valider").prop("disabled", true);
            $("#AjouerProprietaire button#valider").html(
                '<i class="fa fa-spinner fa-pulse"></i> En cours...'
            );
        },
        success: function(response) {
            // Réactiver les boutons
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", false);
            $("#AjouerProprietaire button#valider").prop("disabled", false);
            $("#AjouerProprietaire button#valider").html(
                '<span class="fa fa-save"></span> Enregistrer'
            );

            console.log('Réponse du serveur:', response); // Pour déboguer

            if (response.status) {
                // Ajouter la nouvelle ligne au DataTable
                if (typeof addRowToTable === 'function') {
                    addRowToTable(response.data);
                }
                
                // Afficher le message de succès
                Swal.fire({
                    title: 'Succès !',
                    html: response.message,
                    icon: 'success',
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false
                });
                
                // Fermer le modal
                $('#AjouerProprietaire').modal('hide');
                
                // Réinitialiser le formulaire
                $("#formulaireProprietaire")[0].reset();
                
            } else {
                if (response.error) {
                    printErrorMsg(response.error);
                } else {
                    Swal.fire({
                        title: 'Erreur !',
                        html: response.message || 'Une erreur est survenue',
                        icon: 'warning',
                        confirmButtonClass: 'btn btn-danger',
                        buttonsStyling: false
                    });
                }
            }
        },
        error: function(xhr) {
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", false);
            $("#AjouerProprietaire button#valider").prop("disabled", false);
            $("#AjouerProprietaire button#valider").html(
                '<span class="fa fa-save"></span> Enregistrer'
            );
            
            if (xhr.status === 422) {
                printErrorMsg(xhr.responseJSON.errors || xhr.responseJSON.error);
            } else {
                Swal.fire({
                    title: 'Erreur !',
                    html: 'Une erreur est survenue lors de l\'enregistrement',
                    icon: 'error',
                    confirmButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                });
            }
        }
    });
    
    return false;
}

// ============================================
// MODIFICATION D'UN PROPRIÉTAIRE
// ============================================

function update_proprietaire(form, proprietaireId) {
    // Validation du numéro de téléphone
    const phoneInputId = 'telephone' + proprietaireId;
    const formattedPhone = validateAndFormatPhone(phoneInputId);
    if (!formattedPhone) return false;

    // Préparation des données
    var data = new FormData();
    var form_data = $(form).serializeArray();
    
    $.each(form_data, function(key, input) {
        if (input.name === "telephone") {
            data.append("telephone", formattedPhone);
        } else {
            data.append(input.name, input.value);
        }
    });

    // Configuration AJAX
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Requête AJAX
    $.ajax({
        url: $(form).attr('data-update-url') || "{{ route('update_proprio') }}",
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function() {
            $(form).find('button[type="submit"]').prop("disabled", true);
            $(form).find('button[type="submit"]').html(
                '<i class="fa fa-spinner fa-pulse"></i> En cours...'
            );
        },
        success: function(response) {
            // Réactiver le bouton
            $(form).find('button[type="submit"]').prop("disabled", false);
            $(form).find('button[type="submit"]').html(
                '<span class="fa fa-save"></span> Enregistrer'
            );

            if (response.status) {
                // Mettre à jour la ligne dans le DataTable
                updateRowInTable(response.data);
                
                // Afficher le message de succès
                display_sweet_alert_over_modal("Succès !", response.message, "success", "btn btn-primary");
                
                // Fermer le modal
                $('#modifier' + proprietaireId).modal('hide');
            } else {
                if (response.error) {
                    printErrorMsg(response.error);
                } else {
                    display_sweet_alert_over_modal("Erreur !", response.message, "warning", "btn btn-danger");
                }
            }
        },
        error: function(xhr) {
            $(form).find('button[type="submit"]').prop("disabled", false);
            $(form).find('button[type="submit"]').html(
                '<span class="fa fa-save"></span> Enregistrer'
            );
            
            if (xhr.status === 422) {
                printErrorMsg(xhr.responseJSON.errors);
            } else {
                display_sweet_alert_over_modal("Erreur !", "Une erreur est survenue lors de la modification", "error", "btn btn-danger");
            }
        }
    });
    
    return false; // Empêcher la soumission normale du formulaire
}

// ============================================
// SUPPRESSION D'UN PROPRIÉTAIRE
// ============================================

function delete_proprietaire(proprietaireId, nom, prenom) {
    Swal.fire({
        title: 'Confirmation de suppression',
        html: `Voulez-vous vraiment supprimer ce propriétaire ?<br><strong class="text-danger">${nom} ${prenom}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Non, annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            performDelete(proprietaireId);
        }
    });
}

function performDelete(proprietaireId) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('destroy_proprio') }}",
        method: "POST",
        data: {
            id: proprietaireId
        },
        success: function(response) {
            if (response.status) {
                // Supprimer la ligne du DataTable
                removeRowFromTable(proprietaireId);
                
                // Afficher le message de succès
                display_sweet_alert_over_modal("Succès !", response.message, "success", "btn btn-primary");
            } else {
                display_sweet_alert_over_modal("Erreur !", response.message, "warning", "btn btn-danger");
            }
        },
        error: function(xhr) {
            display_sweet_alert_over_modal("Erreur !", "Une erreur est survenue lors de la suppression", "error", "btn btn-danger");
        }
    });
}

// ============================================
// GESTION DU DATATABLE
// ============================================

function addRowToTable(proprietaire) {
    // Construction de la ligne
    const actionsHtml = buildActionsHtml(proprietaire);
    
    const rowData = [
        proprietaire.annexe_name || '',
        `<strong>${proprietaire.nom} ${proprietaire.prenom}</strong>`,
        `<i class="bx bx-phone-call text-success me-1"></i> ${proprietaire.telephone}`,
        `<i class="bx bx-map text-secondary me-1"></i> ${proprietaire.adresse}`,
        actionsHtml
    ];
    
    // Ajouter la ligne au DataTable
    const row = proprietaireTable.row.add(rowData).draw(false);
    
    // Créer et ajouter les modals au body
    createModalsForProprietaire(proprietaire);
    
    // Initialiser le plugin téléphone pour le nouveau modal
    initializePhoneInputForModal(proprietaire.iteration);
}

function updateRowInTable(proprietaire) {
    // Trouver et mettre à jour la ligne
    proprietaireTable.rows().every(function() {
        const rowData = this.data();
        // Chercher la ligne correspondante (contenant l'ID dans les actions)
        if (rowData[4] && rowData[4].includes(`data-id="${proprietaire.id}"`)) {
            const actionsHtml = buildActionsHtml(proprietaire);
            
            const newRowData = [
                proprietaire.annexe_name || '',
                `<strong>${proprietaire.nom} ${proprietaire.prenom}</strong>`,
                `<i class="bx bx-phone-call text-success me-1"></i> ${proprietaire.telephone}`,
                `<i class="bx bx-map text-secondary me-1"></i> ${proprietaire.adresse}`,
                actionsHtml
            ];
            
            this.data(newRowData).draw(false);
            
            // Mettre à jour les modals existants
            updateModalsForProprietaire(proprietaire);
            
            return false; // Sortir de la boucle
        }
    });
}

function removeRowFromTable(proprietaireId) {
    // Trouver et supprimer la ligne
    proprietaireTable.rows().every(function() {
        const rowData = this.data();
        if (rowData[4] && rowData[4].includes(`data-id="${proprietaireId}"`)) {
            this.remove();
            return false;
        }
    });
    
    proprietaireTable.draw(false);
    
    // Supprimer les modals correspondants
    $(`#modifier${proprietaireId}, #supprimer${proprietaireId}`).remove();
}

// ============================================
// CONSTRUCTION HTML
// ============================================

function buildActionsHtml(proprietaire) {
    let html = '<div class="text-center" data-id="' + proprietaire.id + '">';
    
    // Vérifier les permissions (à adapter selon votre système de permissions)
    if (typeof canModifyProprietaire !== 'undefined' && canModifyProprietaire) {
        html += `
            <a class="btn btn-sm btn-outline-primary rounded-circle me-1" 
               title="Modifier"
               data-bs-toggle="modal" 
               data-bs-target="#modifier${proprietaire.iteration}">
                <i class="bx bx-edit-alt"></i>
            </a>
        `;
    }
    
    if (typeof canDeleteProprietaire !== 'undefined' && canDeleteProprietaire) {
        html += `
            <a class="btn btn-sm btn-outline-danger rounded-circle" 
               title="Supprimer"
               onclick="delete_proprietaire(${proprietaire.id}, '${proprietaire.nom}', '${proprietaire.prenom}')"
               style="cursor: pointer;">
                <i class="bx bx-trash"></i>
            </a>
        `;
    }
    
    html += '</div>';
    return html;
}

function createModalsForProprietaire(proprietaire) {
    // Créer le modal de modification
    const modifierModal = createModifierModal(proprietaire);
    $('body').append(modifierModal);
    
    // Initialiser le plugin téléphone pour ce modal
    initializePhoneInputForModal(proprietaire.iteration);
}

function createModifierModal(proprio) {
    // Cette fonction crée le HTML du modal de modification
    // À adapter selon vos besoins et permissions
    return `
        <div class="modal fade" id="modifier${proprio.iteration}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Modifier un propriétaire</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3" onsubmit="return update_proprietaire(this, ${proprio.iteration});">
                            <input type="hidden" name="id" value="${proprio.id}">
                            <!-- Ajouter tous les champs nécessaires -->
                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" value="${proprio.nom}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="prenom" value="${proprio.prenom}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="telephone${proprio.iteration}" name="telephone" value="${proprio.telephone}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="adresse" value="${proprio.adresse}" required>
                            </div>
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">
                                    <span class="fa fa-save"></span> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function updateModalsForProprietaire(proprio) {
    // Mettre à jour les valeurs dans le modal de modification existant
    const modal = $(`#modifier${proprio.iteration}`);
    if (modal.length) {
        modal.find('input[name="nom"]').val(proprio.nom);
        modal.find('input[name="prenom"]').val(proprio.prenom);
        modal.find('input[name="telephone"]').val(proprio.telephone);
        modal.find('input[name="adresse"]').val(proprio.adresse);
    }
}

function initializePhoneInputForModal(iteration) {
    const input = document.querySelector(`#telephone${iteration}`);
    if (input && !input.classList.contains('iti__tel-input')) {
        window.intlTelInput(input, {
            preferredCountries: ["bj", "fr", "ci"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    }
}