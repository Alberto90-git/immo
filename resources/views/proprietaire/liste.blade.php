<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-center">
        <h5 class="mb-0 text-white"><i class="bx bx-user me-1"></i> {{ __('pages.owner_list') }}</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('common.th_agency') }}</th>
                        <th>{{ __('pages.owner_name_col') }}</th>
                        <th>{{ __('common.th_phone') }}</th>
                        <th>{{ __('common.th_address') }}</th>
                        <th class="text-center">{{ __('common.th_actions') }}</th>
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
                                    <td class="text-center" data-id="{{ $item->id }}">
                                        @can('modify-proprietaire')
                                            <a class="btn btn-sm btn-outline-primary rounded-circle me-1"
                                               title="{{ __('common.title_edit') }}"
                                               data-bs-toggle="modal"
                                               data-bs-target="#modifier{{ $item->id }}">
                                                <i class="bx bx-edit-alt"></i>
                                            </a>
                                        @endcan

                                        @can('delete-proprietaire')
                                            <a class="btn btn-sm btn-outline-danger rounded-circle"
                                               title="{{ __('common.title_delete') }}"
                                               onclick="delete_proprietaire({{ $item->id }}, '{{ $item->nom }}', '{{ $item->prenom }}')"
                                               style="cursor: pointer;">
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

{{-- Modals de modification --}}
@if (isset($allProprios) && count($allProprios) > 0)
    @foreach ($allProprios as $item)
        <div class="modal fade" id="modifier{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">{{ __('pages.owner_edit_modal') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('common.btn_close') }}"></button>
                    </div>

                    <div class="modal-body">
                        <form class="row g-3"
                              id="formulaireModifierProprietaire{{ $item->id }}"
                              data-action-url="{{ route('update_proprio') }}"
                              onsubmit="event.preventDefault(); update_proprietaire(this, {{ $item->id }});">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">

                            <div id="afficher" class="alert alert-warning d-none"></div>

                            <div class="col-md-6">
                                <label for="nom{{ $item->id }}" class="form-label">{{ __('pages.owner_lastname') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom{{ $item->id }}" name="nom" value="{{ $item->nom }}" required>
                                <span class="invalid-feedback nom_err"></span>
                            </div>

                            <div class="col-md-6">
                                <label for="prenom{{ $item->id }}" class="form-label">{{ __('pages.owner_firstname') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom{{ $item->id }}" name="prenom" value="{{ $item->prenom }}" required>
                                <span class="invalid-feedback prenom_err"></span>
                            </div>

                            <div class="col-md-6">
                                <label for="telephone{{ $item->id }}" class="form-label">
                                    {{ __('pages.owner_phone') }} <span class="text-danger">*</span>
                                </label>
                                <input type="tel"
                                       name="telephone"
                                       class="form-control"
                                       id="telephone{{ $item->id }}"
                                       required
                                       value="{{ $item->telephone }}">
                                <span class="invalid-feedback telephone_err"></span>
                            </div>

                            <div class="col-md-6">
                                <label for="adresse{{ $item->id }}" class="form-label">{{ __('pages.owner_address') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="adresse{{ $item->id }}" name="adresse" value="{{ $item->adresse }}" required>
                                <span class="invalid-feedback adresse_err"></span>
                            </div>

                            <div class="col-md-6">
                                <label for="email{{ $item->id }}" class="form-label">{{ __('pages.owner_email_opt') }}</label>
                                <input type="email" class="form-control" id="email{{ $item->id }}" name="email" value="{{ $item->email }}" placeholder="{{ __('pages.owner_email_ph') }}">
                            </div>

                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                                <button type="submit" class="btn btn-primary" id="valider{{ $item->id }}">
                                    <span class="fa fa-save"></span>
                                    <span>{{ __('common.btn_save') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const input = document.querySelector("#telephone{{ $item->id }}");
                if (input && !input.classList.contains('iti__tel-input')) {
                    window.intlTelInput(input, {
                        preferredCountries: ["bj", "fr", "ci"],
                        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                    });
                }
            });
        </script>
    @endforeach
@endif

<style>
    .iti {
        width: 100%;
    }

    .iti input[type="tel"] {
        width: 100%;
    }
</style>

<!-- CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- JS DataTables -->
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<!-- Intl Tel Input -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/proprietaire_ajax.js') }}"></script>
