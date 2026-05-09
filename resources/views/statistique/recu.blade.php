@extends('layouts.template')

@section('content')

    @section('title')
    <title>{{ __('pages.recu_title') }}</title>
    @endsection


    <div class="container-xxl flex-grow-1 container-p-y">
       <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }} / {{ __('pages.fin_title') }} /</span> {{ __('pages.recu_breadcrumb') }}
       </h4>

        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3 nav-fill" role="tablist" id="recuTabList">

                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-recu_avance"
                            aria-controls="navs-pills-justified-recu_avance"
                            aria-selected="false">
                            <i class="tf-icons bx bx-wallet me-1"></i> {{ __('pages.recu_tab_advance') }}
                        </button>
                    </li>

                    <li class="nav-item">
                        <button
                            type="button"
                            class="nav-link"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-pills-justified-reculocation"
                            aria-controls="navs-pills-justified-reculocation"
                            aria-selected="false">
                            <i class="tf-icons bx bx-receipt me-1"></i> {{ __('pages.recu_tab_monthly') }}
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    <!-- TAB 1: Recus d'avance -->
                    <div class="tab-pane fade" id="navs-pills-justified-recu_avance" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bx bx-search-alt me-2"></i>{{ __('pages.recu_search_advance') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="row g-3 align-items-end">
                                    @csrf
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_search_type') }}</label>
                                        <select class="form-select" id="choix" name="choix" onchange="displayChoix();" required>
                                            <option disabled selected value="">{{ __('pages.recu_ph_search_type') }}</option>
                                            <option value="by_user">{{ __('pages.recu_opt_by_name') }}</option>
                                            <option value="by_date">{{ __('pages.recu_opt_by_date') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3" id="date_debutDiv" style="display: none;">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_date_start') }}</label>
                                        <input type="date" name="date_debut" id="date_debut" class="form-control">
                                    </div>

                                    <div class="col-md-3" id="date_finDiv" style="display: none;">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_date_end') }}</label>
                                        <input type="date" name="date_fin" id="date_fin" class="form-control">
                                    </div>

                                    <div class="col-md-5" style="display: none;" id="user_nameDiv">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_tenant_name') }}</label>
                                        <input type="text" placeholder="{{ __('pages.recu_ph_tenant_name') }}" class="form-control" name="user_name" id="user_name">
                                    </div>
                                    <div class="col-md-2" id="btnSearchDiv" style="display: none;">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <button type="button" class="btn btn-primary w-100" id="btnSearchByDate">
                                            <i class="bx bx-search me-1"></i>{{ __('pages.recu_btn_search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-center" id="titre2">
                                    <i class="bx bx-list-ul me-2"></i>{{ __('pages.recu_results') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="table table-striped table-hover align-middle" style="width:100%">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-home me-1"></i>{{ __('pages.recu_th_house') }}
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-door-open me-1"></i>{{ __('pages.recu_th_room') }}
                                                </th>
                                                <th class="text-center" style="width: 15%;">
                                                    <i class="bx bx-user me-1"></i>{{ __('pages.recu_th_tenant') }}
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-briefcase me-1"></i>{{ __('pages.recu_th_profession') }}
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-phone me-1"></i>{{ __('pages.recu_th_phone') }}
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-calendar me-1"></i>{{ __('pages.recu_th_advance') }}
                                                </th>
                                                <th class="text-center" style="width: 12%;">
                                                    <i class="bx bx-calendar-check me-1"></i>{{ __('pages.recu_th_entry_date') }}
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-download me-1"></i>{{ __('pages.recu_th_action') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="list_ancien_recu_avance">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Recus de loyer mensuel -->
                    <div class="tab-pane fade" id="navs-pills-justified-reculocation" role="tabpanel">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bx bx-search-alt me-2"></i>{{ __('pages.recu_search_monthly') }}</h5>
                            </div>
                            <div class="card-body">
                                <form class="row g-3 align-items-end">
                                    @csrf
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_search_type') }}</label>
                                        <select class="form-select" id="choix2" name="choix2" onchange="displayChoix2();" required>
                                            <option disabled selected value="">{{ __('pages.recu_ph_search_type') }}</option>
                                            <option value="by_user">{{ __('pages.recu_opt_by_name') }}</option>
                                            <option value="by_date">{{ __('pages.recu_opt_by_date') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3" id="date_debutDiv2" style="display: none;">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_date_start') }}</label>
                                        <input type="date" name="date_debut2" id="date_debut2" class="form-control">
                                    </div>

                                    <div class="col-md-3" id="date_finDiv2" style="display: none;">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_date_end') }}</label>
                                        <input type="date" name="date_fin2" id="date_fin2" class="form-control">
                                    </div>

                                    <div class="col-md-5" style="display: none;" id="user_nameDiv2">
                                        <label class="form-label fw-semibold">{{ __('pages.recu_label_tenant_name') }}</label>
                                        <input type="text" placeholder="{{ __('pages.recu_ph_tenant_name') }}" class="form-control" name="user_name2" id="user_name2">
                                    </div>
                                    <div class="col-md-2" id="btnSearchDiv2" style="display: none;">
                                        <label class="form-label d-block">&nbsp;</label>
                                        <button type="button" class="btn btn-success w-100" id="btnSearchByDate2">
                                            <i class="bx bx-search me-1"></i>{{ __('pages.recu_btn_search') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card shadow-sm mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0 text-center" id="titre">
                                    <i class="bx bx-list-ul me-2"></i>{{ __('pages.recu_results') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example2" class="table table-striped table-hover align-middle" style="width:100%">
                                        <thead class="table-success">
                                            <tr>
                                                <th class="text-center" style="width: 14%;">
                                                    <i class="bx bx-home me-1"></i>{{ __('pages.recu_th_house') }}
                                                </th>
                                                <th class="text-center" style="width: 10%;">
                                                    <i class="bx bx-door-open me-1"></i>{{ __('pages.recu_th_room') }}
                                                </th>
                                                <th class="text-center" style="width: 16%;">
                                                    <i class="bx bx-user me-1"></i>{{ __('pages.recu_th_tenant') }}
                                                </th>
                                                <th class="text-center" style="width: 14%;">
                                                    <i class="bx bx-money me-1"></i>{{ __('pages.recu_th_amount') }}
                                                </th>
                                                <th class="text-center" style="width: 10%;">
                                                    <i class="bx bx-calendar me-1"></i>{{ __('pages.recu_th_month') }}
                                                </th>
                                                <th class="text-center" style="width: 14%;">
                                                    <i class="bx bx-calendar-check me-1"></i>{{ __('pages.recu_th_pay_date') }}
                                                </th>
                                                <th class="text-center" style="width: 8%;">
                                                    <i class="bx bx-download me-1"></i>{{ __('pages.recu_th_action') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="list_locataire_recuPP">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>

        var RECU_I18N = {
            alertDates: '{{ __('pages.recu_alert_dates') }}',
        };

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // ── Persistance du tab actif via localStorage ─────────────────────────────
        (function () {
            var DEFAULT_TAB = '#navs-pills-justified-recu_avance';
            var saved = localStorage.getItem('recu_active_tab') || DEFAULT_TAB;
            var tabBtn = document.querySelector('[data-bs-target="' + saved + '"]');
            if (tabBtn) {
                new bootstrap.Tab(tabBtn).show();
            } else {
                // fallback : activer le premier onglet
                var first = document.querySelector('#recuTabList [data-bs-toggle="tab"]');
                if (first) new bootstrap.Tab(first).show();
            }
            document.querySelectorAll('#recuTabList [data-bs-toggle="tab"]').forEach(function (btn) {
                btn.addEventListener('shown.bs.tab', function (e) {
                    localStorage.setItem('recu_active_tab', e.target.getAttribute('data-bs-target'));
                });
            });
        })();

        // ── Helpers AJAX ──────────────────────────────────────────────────────────
        function rechercherAvanceParDate() {
            var date_debut = $('#date_debut').val();
            var date_fin   = $('#date_fin').val();
            if (!date_debut || !date_fin) {
                alert(RECU_I18N.alertDates);
                return;
            }
            $.ajax({
                url: '{{ url('statistique-facture-avance') }}',
                data: { date_debut: date_debut, date_fin: date_fin },
                type: 'GET', cache: false, dataType: 'json',
                success: function (data) {
                    $('#list_ancien_recu_avance').html(data.list_recu);
                    $('#titre2').html('<i class="bx bx-list-ul me-2"></i>' + data.titre2);
                }
            });
        }

        function rechercherMoisParDate() {
            var date_debut2 = $('#date_debut2').val();
            var date_fin2   = $('#date_fin2').val();
            if (!date_debut2 || !date_fin2) {
                alert(RECU_I18N.alertDates);
                return;
            }
            $.ajax({
                url: '{{ url('statistique-facture-mois') }}',
                data: { date_debut2: date_debut2, date_fin2: date_fin2 },
                type: 'GET', cache: false, dataType: 'json',
                success: function (data) {
                    $('#list_locataire_recuPP').html(data.list_locataireP);
                    $('#titre').html('<i class="bx bx-list-ul me-2"></i>' + data.titre);
                }
            });
        }

        // ── Recherche par nom (Tab 1 – avance) ───────────────────────────────────
        $('#user_name').on('keyup', function () {
            var val = $(this).val();
            if (val.length < 2) return;
            $.ajax({
                url: '{{ url('statistique-facture-avance-nom') }}',
                data: { user_name: val },
                type: 'GET', cache: false, dataType: 'json',
                success: function (data) {
                    $('#list_ancien_recu_avance').html(data.list_recu);
                    $('#titre2').html('<i class="bx bx-list-ul me-2"></i>' + data.titre2);
                }
            });
        });

        // ── Recherche par nom (Tab 2 – loyer mensuel) ────────────────────────────
        $('#user_name2').on('keyup', function () {
            var val = $(this).val();
            if (val.length < 2) return;
            $.ajax({
                url: '{{ url('statistique-facture-mois-nom') }}',
                data: { user_name2: val },
                type: 'GET', cache: false, dataType: 'json',
                success: function (data) {
                    $('#list_locataire_recuPP').html(data.list_locataireP);
                    $('#titre').html('<i class="bx bx-list-ul me-2"></i>' + data.titre);
                }
            });
        });

        // ── Bouton Rechercher (Tab 1 – dates) ────────────────────────────────────
        $('#btnSearchByDate').on('click', rechercherAvanceParDate);

        // ── Bouton Rechercher (Tab 2 – dates) ────────────────────────────────────
        $('#btnSearchByDate2').on('click', rechercherMoisParDate);

        // ── Fonctions displayChoix ────────────────────────────────────────────────
        function displayChoix() {
            var val = document.getElementById('choix').value;
            var isByUser = val === 'by_user';
            document.getElementById('user_nameDiv').style.display   = isByUser ? 'block' : 'none';
            document.getElementById('date_debutDiv').style.display  = isByUser ? 'none'  : 'block';
            document.getElementById('date_finDiv').style.display    = isByUser ? 'none'  : 'block';
            document.getElementById('btnSearchDiv').style.display   = isByUser ? 'none'  : 'block';
            $('#user_name').attr('required', isByUser);
            $('#date_debut').attr('required', !isByUser);
            $('#date_fin').attr('required', !isByUser);
            // Vider les résultats lors du changement de mode
            $('#list_ancien_recu_avance').html('');
        }

        function displayChoix2() {
            var val = document.getElementById('choix2').value;
            var isByUser = val === 'by_user';
            document.getElementById('user_nameDiv2').style.display  = isByUser ? 'block' : 'none';
            document.getElementById('date_debutDiv2').style.display = isByUser ? 'none'  : 'block';
            document.getElementById('date_finDiv2').style.display   = isByUser ? 'none'  : 'block';
            document.getElementById('btnSearchDiv2').style.display  = isByUser ? 'none'  : 'block';
            $('#user_name2').attr('required', isByUser);
            $('#date_debut2').attr('required', !isByUser);
            $('#date_fin2').attr('required', !isByUser);
            // Vider les résultats lors du changement de mode
            $('#list_locataire_recuPP').html('');
        }

    </script>
@endsection
