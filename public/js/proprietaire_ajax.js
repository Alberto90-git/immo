// ============================================
// INITIALISATION
// ============================================

let proprietaireTable;

$(document).ready(function() {
    initializeDataTable();
    initializePhoneInput();
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
            { extend: 'copy', exportOptions: { columns: ':not(:last-child)' }},
            { extend: 'csv', exportOptions: { columns: ':not(:last-child)' }},
            { extend: 'excel', exportOptions: { columns: ':not(:last-child)' }},
            { extend: 'pdf', exportOptions: { columns: ':not(:last-child)' }},
            { extend: 'print', exportOptions: { columns: ':not(:last-child)' }},
            'colvis'
        ]
    });
    
    proprietaireTable.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
}

// ============================================
// INITIALISATION TÉLÉPHONE
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
// VALIDATION TÉLÉPHONE
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
// GESTION ÉVÉNEMENTS
// ============================================

function setupEventListeners() {
    $(':input').on('input change', function() {
        $('.' + $(this).attr("id") + '_err').hide();
    });
    
    $('select').on('change', function() {
        $('.' + $(this).attr("id") + '_err').hide();
    });
    
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
        $(this).find('.invalid-feedback').hide();
    });
}

// ============================================
// AFFICHAGE MESSAGES
// ============================================

// Fonction pour afficher les messages SweetAlert2 au-dessus des modals
function showProprietaireMessage(title, message, type, buttonClass) {
    // Fermer d'abord tout modal ouvert pour éviter les problèmes de z-index
    $('.modal').modal('hide');

    // Petit délai pour laisser le modal se fermer
    setTimeout(function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                html: message,
                icon: type,
                confirmButtonText: 'OK',
                confirmButtonClass: buttonClass,
                customClass: {
                    container: 'swal-over-modal',
                    popup: 'animated bounceIn'
                },
                didOpen: () => {
                    // Forcer le z-index au-dessus des modals
                    const swalContainer = document.querySelector('.swal2-container');
                    if (swalContainer) {
                        swalContainer.style.zIndex = '10070';
                    }
                }
            }).then((result) => {
                // Recharger la page après confirmation pour les succès
                if (type === 'success') {
                    window.location.reload();
                }
            });
        } else if (typeof display_message === 'function') {
            display_message(title, message, type, buttonClass);
        } else {
            alert(title + ":\n" + message);
        }
    }, 300);
}

function printErrorMsg(msg) {
    $.each(msg, function(key, value) {
        $('.' + key + '_err').text(value).show();
    });
}

// ============================================
// AJOUT PROPRIÉTAIRE
// ============================================



// ============================================
// MODIFICATION PROPRIÉTAIRE
// ============================================

function update_proprietaire(form, proprietaireId) {
    // ⭐ Utiliser l'ID du propriétaire directement
    const phoneInputId = 'telephone' + proprietaireId;
    const formattedPhone = validateAndFormatPhone(phoneInputId);
    if (!formattedPhone) return false;

    var data = new FormData();
    var form_data = $(form).serializeArray();
    
    $.each(form_data, function(key, input) {
        if (input.name === "telephone") {
            data.append("telephone", formattedPhone);
        } else {
            data.append(input.name, input.value);
        }
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: $(form).data('action-url'),
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function() {
            $(form).find('button[type="submit"]').prop("disabled", true).html('<i class="fa fa-spinner fa-pulse"></i> En cours...');
        },
        success: function(response) {
            $(form).find('button[type="submit"]').prop("disabled", false).html('<span class="fa fa-save"></span> Enregistrer');

            if (response.status) {
                updateRowInTable(response.data);
                $('#modifier' + proprietaireId).modal('hide');
                showProprietaireMessage("Succès !", response.message, "success", "btn btn-primary");
            } else {
                if (response.error) {
                    printErrorMsg(response.error);
                } else {
                    showProprietaireMessage("Erreur !", response.message, "warning", "btn btn-danger");
                }
            }
        },
        error: function(xhr) {
            $(form).find('button[type="submit"]').prop("disabled", false).html('<span class="fa fa-save"></span> Enregistrer');

            if (xhr.status === 422) {
                printErrorMsg(xhr.responseJSON.error);
            } else {
                showProprietaireMessage("Erreur !", "Une erreur est survenue", "error", "btn btn-danger");
            }
        }
    });
    
    return false;
}

// ============================================
// SUPPRESSION PROPRIÉTAIRE
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
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: $('#delete-route').data('url'),
        method: "POST",
        data: { id: proprietaireId },
        success: function(response) {
            if (response.status) {
                removeRowFromTable(proprietaireId);
                showProprietaireMessage("Succès !", response.message, "success", "btn btn-primary");
            } else {
                showProprietaireMessage("Erreur !", response.message, "warning", "btn btn-danger");
            }
        },
        error: function(xhr) {
            showProprietaireMessage("Erreur !", "Une erreur est survenue", "error", "btn btn-danger");
        }
    });
}

// ============================================
// GESTION DATATABLE
// ============================================

function addRowToTable(proprietaire) {
    const actionsHtml = buildActionsHtml(proprietaire);
    
    const rowData = [
        proprietaire.annexe_name || '',
        `<strong>${proprietaire.nom} ${proprietaire.prenom}</strong>`,
        `<i class="bx bx-phone-call text-success me-1"></i> ${proprietaire.telephone}`,
        `<i class="bx bx-map text-secondary me-1"></i> ${proprietaire.adresse}`,
        actionsHtml
    ];
    
    proprietaireTable.row.add(rowData).draw(false);
    createModalsForProprietaire(proprietaire);
    initializePhoneInputForModal(proprietaire.id);
}

function updateRowInTable(proprietaire) {
    proprietaireTable.rows().every(function() {
        const rowData = this.data();
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
            updateModalsForProprietaire(proprietaire);
            
            return false;
        }
    });
}

function removeRowFromTable(proprietaireId) {
    proprietaireTable.rows().every(function() {
        const rowData = this.data();
        if (rowData[4] && rowData[4].includes(`data-id="${proprietaireId}"`)) {
            this.remove();
            return false;
        }
    });
    
    proprietaireTable.draw(false);
    $(`#modifier${proprietaireId}`).remove();
}

function buildActionsHtml(proprietaire) {
    let html = '<div class="text-center" data-id="' + proprietaire.id + '">';
    
    if (typeof canModifyProprietaire !== 'undefined' && canModifyProprietaire) {
        html += `<a class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Modifier" data-bs-toggle="modal" data-bs-target="#modifier${proprietaire.id}"><i class="bx bx-edit-alt"></i></a>`;
    }
    
    if (typeof canDeleteProprietaire !== 'undefined' && canDeleteProprietaire) {
        html += `<a class="btn btn-sm btn-outline-danger rounded-circle" title="Supprimer" onclick="delete_proprietaire(${proprietaire.id}, '${proprietaire.nom}', '${proprietaire.prenom}')" style="cursor: pointer;"><i class="bx bx-trash"></i></a>`;
    }
    
    html += '</div>';
    return html;
}

function createModalsForProprietaire(proprietaire) {
    const modifierModal = `
        <div class="modal fade" id="modifier${proprietaire.id}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Modifier un propriétaire</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3" data-action-url="${$('#update-route').data('url')}" onsubmit="return update_proprietaire(this, ${proprietaire.id});">
                            <input type="hidden" name="id" value="${proprietaire.id}">
                            <div class="col-md-6">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" value="${proprietaire.nom}" required>
                                <span class="invalid-feedback nom_err"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="prenom" value="${proprietaire.prenom}" required>
                                <span class="invalid-feedback prenom_err"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="telephone${proprietaire.id}" name="telephone" value="${proprietaire.telephone}" required>
                                <span class="invalid-feedback telephone_err"></span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="adresse" value="${proprietaire.adresse}" required>
                                <span class="invalid-feedback adresse_err"></span>
                            </div>
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(modifierModal);
    initializePhoneInputForModal(proprietaire.id);
}

function updateModalsForProprietaire(proprio) {
    const modal = $(`#modifier${proprio.id}`);
    if (modal.length) {
        modal.find('input[name="nom"]').val(proprio.nom);
        modal.find('input[name="prenom"]').val(proprio.prenom);
        modal.find('input[name="telephone"]').val(proprio.telephone);
        modal.find('input[name="adresse"]').val(proprio.adresse);
    }
}

function initializePhoneInputForModal(proprietaireId) {
    const input = document.querySelector(`#telephone${proprietaireId}`);
    if (input && !input.classList.contains('iti__tel-input')) {
        window.intlTelInput(input, {
            preferredCountries: ["bj", "fr", "ci"],
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    }
}