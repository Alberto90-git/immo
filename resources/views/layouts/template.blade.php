 <!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed"
  dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    {{-- ── Anti-flash : appliquer le thème AVANT le rendu ──────────────── --}}
    <script>
      (function(){
        try {
          if (localStorage.getItem('lokativ-theme') === 'dark') {
            document.documentElement.classList.remove('light-style');
            document.documentElement.classList.add('dark-style');
          }
        } catch(e) {}
      })();
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    
  <!-- Dans la section head -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @yield('title')


    <meta name="description" content="" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
   <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>

   <style>
    /* Forcer SweetAlert2 à apparaître au-dessus de tous les modals */
    .swal2-container {
        z-index: 10070 !important;
    }
    .swal2-popup {
        z-index: 10071 !important;
    }

    /* Navbar fixe au scroll */
    #layout-navbar {
        position: sticky;
        top: 0;
        z-index: 1020;
    }

    /* ══════════════════════════════════════════════
       Bouton toggle dark/light
       ══════════════════════════════════════════════ */
    .btn-theme-toggle {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      background: #012970;
      border-radius: 50%;
      color: white;
      border: none;
      cursor: pointer;
      transition: background 0.2s, transform 0.2s;
      font-size: 1.15rem;
    }
    .btn-theme-toggle:hover {
      background: #011d50;
      transform: scale(1.08);
    }
    html.dark-style .btn-theme-toggle {
      background: #373852;
    }
    html.dark-style .btn-theme-toggle:hover {
      background: #444564;
    }

    /* ══════════════════════════════════════════════
       Dark mode global — Lokativ
       ══════════════════════════════════════════════ */
    html.dark-style {
      color-scheme: dark;
      /* Variables CSS custom pour les composants inline */
      --kpi-card-bg:   #2f3049;
      --doc-thead-bg:  #373852;
    }

    /* Body & fond principal */
    html.dark-style body,
    html.dark-style .content-wrapper,
    html.dark-style .layout-page {
      background-color: #2b2c40 !important;
      color: #a3a4cc;
    }

    /* Navbar */
    html.dark-style .layout-navbar,
    html.dark-style .bg-navbar-theme {
      background-color: #2b2c40 !important;
      border-bottom: 1px solid #444564 !important;
      box-shadow: 0 2px 6px rgba(0,0,0,0.4) !important;
    }

    /* Sidebar / Menu */
    html.dark-style .layout-menu,
    html.dark-style .bg-menu-theme {
      background-color: #232333 !important;
      border-right: 1px solid #3a3b55 !important;
    }
    html.dark-style .menu-item .menu-link {
      color: #a3a4cc !important;
    }
    html.dark-style .menu-item.active > .menu-link,
    html.dark-style .menu-item .menu-link:hover {
      color: #d0d4e0 !important;
      background-color: rgba(255,255,255,0.07) !important;
    }
    html.dark-style .menu-header-text {
      color: #7f7f9d !important;
    }
    html.dark-style .app-brand-text {
      color: #d0d4e0 !important;
    }
    html.dark-style .menu-item.active > .menu-link::before {
      background-color: #696cff !important;
    }
    html.dark-style .menu-sub > .menu-item > .menu-link::before {
      background-color: #696cff !important;
    }

    /* Cards */
    html.dark-style .card {
      background-color: #2f3049 !important;
      border-color: #444564 !important;
      box-shadow: 0 2px 6px rgba(0,0,0,0.25) !important;
    }
    html.dark-style .card-header,
    html.dark-style .card-footer {
      background-color: rgba(0,0,0,0.15) !important;
      border-color: #444564 !important;
      color: #d0d4e0 !important;
    }
    html.dark-style .bg-light,
    html.dark-style .card.bg-light,
    html.dark-style .card-body.bg-light {
      background-color: #373852 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .bg-white {
      background-color: #2f3049 !important;
    }
    html.dark-style .table-responsive {
      background-color: transparent !important;
    }

    /* Tables */
    html.dark-style .table {
      color: #a3a4cc;
      --bs-table-bg: transparent;
      --bs-table-border-color: #444564;
      --bs-table-striped-bg: rgba(255,255,255,0.03);
      --bs-table-striped-color: #a3a4cc;
    }
    html.dark-style .table > :not(caption) > * > * {
      border-bottom-color: #444564 !important;
      color: #a3a4cc;
    }
    html.dark-style .table-light,
    html.dark-style thead.table-light,
    html.dark-style .table-light th,
    html.dark-style .table-light td {
      background-color: #373852 !important;
      color: #d0d4e0 !important;
      border-color: #444564 !important;
    }
    html.dark-style .table-hover > tbody > tr:hover > * {
      background-color: rgba(255,255,255,0.04) !important;
      color: #c0c4dc !important;
    }

    /* Forms */
    html.dark-style .form-control,
    html.dark-style .form-select,
    html.dark-style .form-control-sm,
    html.dark-style .form-select-sm {
      background-color: #373852 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .form-control:focus,
    html.dark-style .form-select:focus {
      background-color: #3e4060 !important;
      border-color: #696cff !important;
      color: #d0d4e0 !important;
      box-shadow: 0 0 0 0.2rem rgba(105,108,255,0.25) !important;
    }
    html.dark-style .form-control::placeholder {
      color: #6e7191 !important;
      opacity: 1;
    }
    html.dark-style .input-group-text {
      background-color: #373852 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .form-check-input:not(:checked) {
      background-color: #373852 !important;
      border-color: #555670 !important;
    }
    html.dark-style .form-text {
      color: #7f7f9d !important;
    }
    html.dark-style label {
      color: #c0c4dc;
    }
    html.dark-style .form-label {
      color: #c0c4dc;
    }

    /* Dropdowns */
    html.dark-style .dropdown-menu {
      background-color: #2f3049 !important;
      border-color: #444564 !important;
      box-shadow: 0 4px 24px rgba(0,0,0,0.5) !important;
    }
    html.dark-style .dropdown-item {
      color: #a3a4cc !important;
    }
    html.dark-style .dropdown-item:hover,
    html.dark-style .dropdown-item:focus {
      background-color: rgba(255,255,255,0.06) !important;
      color: #d0d4e0 !important;
    }
    html.dark-style .dropdown-item.active {
      background-color: rgba(105,108,255,0.15) !important;
      color: #8c8eff !important;
    }
    html.dark-style .dropdown-divider {
      border-color: #444564 !important;
    }
    html.dark-style .dropdown-header {
      color: #7f7f9d !important;
    }

    /* Modals */
    html.dark-style .modal-content {
      background-color: #2f3049 !important;
      border-color: #444564 !important;
    }
    html.dark-style .modal-header,
    html.dark-style .modal-footer {
      border-color: #444564 !important;
    }
    html.dark-style .modal-body {
      color: #a3a4cc;
    }

    /* Texte & titres */
    html.dark-style h1, html.dark-style h2, html.dark-style h3,
    html.dark-style h4, html.dark-style h5, html.dark-style h6 {
      color: #d0d4e0;
    }
    html.dark-style .fw-bold,
    html.dark-style .fw-semibold {
      color: inherit;
    }
    html.dark-style .text-muted {
      color: #7f7f9d !important;
    }
    html.dark-style .text-dark {
      color: #d0d4e0 !important;
    }
    html.dark-style small {
      color: #7f7f9d;
    }
    html.dark-style a:not(.btn):not(.nav-link):not(.dropdown-item):not(.menu-link):not(.nav-item) {
      color: #8c8eff;
    }
    html.dark-style a:not(.btn):not(.nav-link):not(.dropdown-item):not(.menu-link):not(.nav-item):hover {
      color: #b3b4ff;
    }

    /* Bordures */
    html.dark-style .border,
    html.dark-style .border-top,
    html.dark-style .border-bottom,
    html.dark-style .border-start,
    html.dark-style .border-end {
      border-color: #444564 !important;
    }
    html.dark-style hr {
      border-color: #444564;
    }

    /* Alerts */
    html.dark-style .alert-info {
      background-color: rgba(3, 195, 236, 0.1) !important;
      border-color: rgba(3, 195, 236, 0.3) !important;
      color: #03c3ec !important;
    }
    html.dark-style .alert-warning {
      background-color: rgba(255, 171, 0, 0.1) !important;
      border-color: rgba(255, 171, 0, 0.3) !important;
      color: #ffab00 !important;
    }
    html.dark-style .alert-danger {
      background-color: rgba(255, 62, 29, 0.1) !important;
      border-color: rgba(255, 62, 29, 0.3) !important;
      color: #ff8a75 !important;
    }
    html.dark-style .alert-success {
      background-color: rgba(113, 221, 55, 0.1) !important;
      border-color: rgba(113, 221, 55, 0.3) !important;
      color: #71dd37 !important;
    }

    /* Onglets nav-tabs */
    html.dark-style .nav-tabs {
      border-color: #444564 !important;
    }
    html.dark-style .nav-tabs .nav-link {
      color: #a3a4cc !important;
    }
    html.dark-style .nav-tabs .nav-link:hover {
      border-color: #444564 !important;
      color: #d0d4e0 !important;
    }
    html.dark-style .nav-tabs .nav-link.active {
      background-color: #2f3049 !important;
      border-color: #444564 #444564 #2f3049 !important;
      color: #d0d4e0 !important;
    }

    /* Badges */
    html.dark-style .badge.bg-label-info    { background-color: rgba(3,195,236,0.15) !important; color: #03c3ec !important; }
    html.dark-style .badge.bg-label-success { background-color: rgba(113,221,55,0.15) !important; color: #71dd37 !important; }
    html.dark-style .badge.bg-label-warning { background-color: rgba(255,171,0,0.15) !important;  color: #ffab00 !important; }
    html.dark-style .badge.bg-label-danger  { background-color: rgba(255,62,29,0.15) !important;  color: #ff8a75 !important; }
    html.dark-style .badge.bg-label-primary { background-color: rgba(105,108,255,0.15) !important; color: #8c8eff !important; }
    html.dark-style .badge.bg-label-secondary { background-color: rgba(130,141,163,0.15) !important; color: #a3a4cc !important; }
    html.dark-style .badge.bg-label-dark    { background-color: rgba(35,52,70,0.4) !important;    color: #d0d4e0 !important; }

    /* Boutons outlines */
    html.dark-style .btn-outline-secondary {
      color: #a3a4cc !important;
      border-color: #555670 !important;
    }
    html.dark-style .btn-outline-secondary:hover {
      background-color: #555670 !important;
      color: #d0d4e0 !important;
    }

    /* Boutons ronds "+" : fond solide en dark mode */
    html.dark-style .btn-icon.btn-outline-primary {
      background-color: #696cff !important;
      border-color: #696cff !important;
      color: #fff !important;
    }
    html.dark-style .btn-icon.btn-outline-primary:hover {
      background-color: #5f61e6 !important;
      border-color: #5f61e6 !important;
    }

    /* SweetAlert2 dark mode */
    html.dark-style .swal2-popup {
      background: #2f3049 !important;
      color: #a3a4cc !important;
      border: 1px solid #444564 !important;
    }
    html.dark-style .swal2-title {
      color: #d0d4e0 !important;
    }
    html.dark-style .swal2-html-container,
    html.dark-style .swal2-content {
      color: #a3a4cc !important;
    }
    html.dark-style .swal2-close {
      color: #a3a4cc !important;
    }
    html.dark-style .swal2-close:hover {
      color: #d0d4e0 !important;
    }
    html.dark-style .swal2-timer-progress-bar {
      background: #696cff !important;
    }
    /* Old SweetAlert dark mode */
    html.dark-style .sweet-alert {
      background-color: #2f3049 !important;
      border: 1px solid #444564 !important;
    }
    html.dark-style .sweet-alert h2 {
      color: #d0d4e0 !important;
    }
    html.dark-style .sweet-alert p {
      color: #a3a4cc !important;
    }
    html.dark-style .sweet-overlay {
      background: rgba(0, 0, 0, 0.6) !important;
    }

    /* Accordéons */
    html.dark-style .accordion-item {
      background-color: #2f3049 !important;
      border-color: #444564 !important;
    }
    html.dark-style .accordion-button {
      background-color: #373852 !important;
      color: #d0d4e0 !important;
    }
    html.dark-style .accordion-button:not(.collapsed) {
      background-color: rgba(105,108,255,0.1) !important;
      color: #8c8eff !important;
    }
    html.dark-style .accordion-button::after {
      filter: invert(0.7) sepia(0.3) saturate(0.8);
    }

    /* Timeline (recouvrement) */
    html.dark-style .timeline-item-inner {
      background-color: #373852 !important;
      border-color: #444564 !important;
    }

    /* Scrollbars */
    html.dark-style ::-webkit-scrollbar { width: 6px; height: 6px; }
    html.dark-style ::-webkit-scrollbar-track { background: #2b2c40; }
    html.dark-style ::-webkit-scrollbar-thumb { background: #555670; border-radius: 3px; }
    html.dark-style ::-webkit-scrollbar-thumb:hover { background: #696cff; }

    /* bg-body-secondary / bg-body-tertiary (Bootstrap 5.3 dark compat) */
    html.dark-style .bg-body-secondary,
    html.dark-style .bg-body-tertiary {
      background-color: #373852 !important;
      color: #a3a4cc;
    }

    /* ── parametre.blade.php : multiselect custom (.ms-tags / .ms-dropdown) ── */
    html.dark-style .ms-tags {
      background: #373852 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .ms-dropdown {
      background: #2f3049 !important;
      border-color: #444564 !important;
      box-shadow: 0 4px 16px rgba(0,0,0,0.4) !important;
    }
    html.dark-style .ms-dropdown .ms-search {
      background: #373852 !important;
      border-color: #444564 !important;
    }
    html.dark-style .ms-dropdown .ms-search input {
      background: #2b2c40 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .ms-dropdown .ms-option {
      color: #a3a4cc !important;
    }
    html.dark-style .ms-dropdown .ms-option:hover {
      background: rgba(105,108,255,0.1) !important;
    }
    html.dark-style .ms-dropdown .ms-option.selected {
      background: rgba(105,108,255,0.15) !important;
    }
    html.dark-style .ms-placeholder {
      color: #6e7191 !important;
    }

    /* ── depenses/index.blade.php : .kpi-card & .dep-tab-btn ── */
    html.dark-style .kpi-card {
      background: #2f3049 !important;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3) !important;
      color: #d0d4e0 !important;
    }
    html.dark-style .dep-tab-btn {
      color: #a3a4cc !important;
    }
    html.dark-style .dep-tab-btn.active,
    html.dark-style .dep-tab-btn:hover {
      color: #d0d4e0 !important;
    }

    /* ── maintenance/show.blade.php : timeline ── */
    html.dark-style .timeline-indicator {
      background: #373852 !important;
    }

    /* ── documents/envoyer.blade.php ── */
    html.dark-style .doc-preview-box,
    html.dark-style .border.rounded.p-2 {
      background: #373852 !important;
    }

    /* ── Inputs disabled / readonly ── */
    html.dark-style .form-control:disabled,
    html.dark-style .form-control[readonly] {
      background-color: #373852 !important;
      color: #7f7f9d !important;
      opacity: 1;
    }

    /* ── Pagination ── */
    html.dark-style .page-link {
      background-color: #2f3049 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .page-link:hover {
      background-color: #373852 !important;
      color: #d0d4e0 !important;
    }
    html.dark-style .page-item.disabled .page-link {
      background-color: #252639 !important;
      color: #555670 !important;
    }

    /* ── Toasts ── */
    html.dark-style .toast {
      background-color: #2f3049 !important;
      border-color: #444564 !important;
    }
    html.dark-style .toast-body {
      color: #a3a4cc !important;
    }

    /* ── jQuery DataTables (dark mode) — classes dataTables_* ── */
    html.dark-style .dataTables_wrapper {
      background-color: transparent !important;
      color: #a3a4cc !important;
    }
    html.dark-style .dataTables_filter label,
    html.dark-style .dataTables_length label {
      color: #a3a4cc !important;
    }
    html.dark-style .dataTables_filter input,
    html.dark-style .dataTables_length select {
      background-color: #373852 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .dataTables_info {
      color: #a3a4cc !important;
    }
    html.dark-style .dataTables_paginate .paginate_button {
      color: #a3a4cc !important;
      background-color: #373852 !important;
      border-color: #444564 !important;
    }
    html.dark-style .dataTables_paginate .paginate_button:hover,
    html.dark-style .dataTables_paginate .paginate_button.current,
    html.dark-style .dataTables_paginate .paginate_button.current:hover {
      background: #444564 !important;
      border-color: #555670 !important;
      color: #fff !important;
    }
    html.dark-style .dataTables_paginate .paginate_button.disabled,
    html.dark-style .dataTables_paginate .paginate_button.disabled:hover {
      color: #6e7191 !important;
      background-color: transparent !important;
    }

    /* ── Simple-DataTables (dark mode) — classes réelles : datatable-* ── */
    html.dark-style .datatable-wrapper {
      background-color: transparent !important;
      color: #a3a4cc !important;
    }
    html.dark-style .datatable-container {
      background-color: transparent !important;
    }
    html.dark-style .datatable-top,
    html.dark-style .datatable-bottom {
      background-color: transparent !important;
      color: #a3a4cc !important;
    }
    html.dark-style .datatable-input {
      background-color: #373852 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .datatable-selector {
      background-color: #373852 !important;
      border-color: #444564 !important;
      color: #a3a4cc !important;
    }
    html.dark-style .datatable-info {
      color: #a3a4cc !important;
    }
    html.dark-style .datatable-pagination a {
      color: #a3a4cc !important;
      background-color: #373852 !important;
      border-color: #444564 !important;
    }
    html.dark-style .datatable-pagination a:hover,
    html.dark-style .datatable-pagination .datatable-active a {
      background-color: #444564 !important;
      color: #fff !important;
    }
    html.dark-style .datatable-sorter::before {
      border-bottom-color: #a3a4cc !important;
    }
    html.dark-style .datatable-sorter::after {
      border-top-color: #a3a4cc !important;
    }

    /* ── nav-align-* tab-content (core.css force background:#fff) ── */
    html.dark-style .nav-align-top > .tab-content,
    html.dark-style .nav-align-right > .tab-content,
    html.dark-style .nav-align-bottom > .tab-content,
    html.dark-style .nav-align-left > .tab-content {
      background: #2f3049 !important;
      border-color: #444564 !important;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
      color: #a3a4cc !important;
    }
    html.dark-style .card-title {
      color: #d0d4e0 !important;
    }

    /* ── Texte primary en dark mode ── */
    html.dark-style .text-primary {
      color: #8c8eff !important;
    }
    html.dark-style .modal-title {
      color: #d0d4e0 !important;
    }

    /* ── Tableaux colorés (thead.table-*) ── */
    html.dark-style .table-primary,
    html.dark-style thead.table-primary th,
    html.dark-style .table-primary td {
      background-color: rgba(105, 108, 255, 0.18) !important;
      color: #b3b4ff !important;
      border-color: #444564 !important;
    }
    html.dark-style .table-success,
    html.dark-style thead.table-success th,
    html.dark-style .table-success td {
      background-color: rgba(113, 221, 55, 0.15) !important;
      color: #9de85a !important;
      border-color: #444564 !important;
    }
    html.dark-style .table-info,
    html.dark-style thead.table-info th,
    html.dark-style .table-info td {
      background-color: rgba(3, 195, 236, 0.15) !important;
      color: #03c3ec !important;
      border-color: #444564 !important;
    }
    html.dark-style .table-warning,
    html.dark-style thead.table-warning th,
    html.dark-style .table-warning td {
      background-color: rgba(255, 171, 0, 0.15) !important;
      color: #ffab00 !important;
      border-color: #444564 !important;
    }
    html.dark-style .table-danger,
    html.dark-style thead.table-danger th,
    html.dark-style .table-danger td {
      background-color: rgba(255, 62, 29, 0.15) !important;
      color: #ff8a75 !important;
      border-color: #444564 !important;
    }

    /* ── Chips / tags ── */
    html.dark-style .badge.bg-secondary {
      background-color: #444564 !important;
      color: #c0c4dc !important;
    }

    /* ── IFrame (aperçu PDF) ── */
    html.dark-style iframe {
      border-radius: 0;
    }

    /* ── Code / pre ── */
    html.dark-style pre,
    html.dark-style code {
      background-color: #1e1e2d !important;
      color: #a3a4cc !important;
    }

    /* ── Spinner / loader ── */
    html.dark-style .spinner-border {
      color: #8c8eff;
    }

    /* Transition douce sur le changement de thème */
    body, .card, .layout-navbar, .layout-menu, .dropdown-menu,
    .modal-content, .form-control, .form-select, .table {
      transition: background-color 0.25s ease, border-color 0.25s ease, color 0.15s ease;
    }
  </style>

    @include('css_file')


    
    <script src="{{ asset('assetsV2/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assetsV2/js/config.js') }}"></script>
    @stack('styles')
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        @include('partials.sidebar')
       
        <div class="layout-page">

          @include('partials.navbar')

          <div class="content-wrapper" id="main-content-wrapper">
            <!-- Alerte admin sans agence sélectionnée -->
            @if(is_admin_without_annexe_selected())
              <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                <strong>Attention !</strong> Veuillez sélectionner une agence dans le header pour pouvoir effectuer des opérations.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <!-- Content -->

            @yield('content')
          

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    @include('js_file')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

<script>
/* ── Initialisation globale des tableaux (search + pagination à partir de 10) ── */
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. simple-datatables : table.datatable ────────────────────────────────
    if (typeof simpleDatatables !== 'undefined') {
        document.querySelectorAll('table.datatable').forEach(function (table) {
            // Ignorer si déjà initialisé (parent = datatable-container)
            if (table.parentElement && table.parentElement.classList.contains('datatable-container')) return;
            new simpleDatatables.DataTable(table, {
                perPage: 10,
                perPageSelect: [10, 25, 50],
                searchable: true,
                fixedHeight: false,
                labels: {
                    placeholder: 'Rechercher...',
                    perPage:     '{select} lignes par page',
                    noRows:      'Aucune donnée',
                    info:        '{start}–{end} sur {rows} entrées',
                }
            });
        });
    }

    // ── 2. jQuery DataTables : tous les <table id="..."> non-datatable ────────
    if (typeof $ !== 'undefined' && $.fn && $.fn.DataTable) {
        $('table[id]:not(.datatable)').each(function () {
            if ($.fn.dataTable && $.fn.dataTable.isDataTable(this)) return; // déjà init

            // Ignorer les tableaux AJAX : tbody vide ou une seule ligne colspan (placeholder)
            var $firstBodyRow = $(this).find('tbody tr:first-child');
            if ($firstBodyRow.length) {
                var $cells = $firstBodyRow.find('td, th');
                if ($cells.length === 1 && $cells.first().attr('colspan')) return;
            }

            $(this).DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Tout']],
                language: {
                    search:          'Rechercher :',
                    lengthMenu:      '_MENU_ lignes par page',
                    info:            '_START_–_END_ sur _TOTAL_ entrées',
                    infoEmpty:       'Aucune entrée',
                    infoFiltered:    '(filtré sur _MAX_ entrées au total)',
                    zeroRecords:     'Aucun résultat',
                    emptyTable:      'Aucune donnée disponible',
                    paginate: { previous: '«', next: '»' }
                }
            });
        });
    }
});
</script>

<!-- Avant la fermeture du body -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
  <script>

    function display_sweet_alerte2(title, message, type, buttonClass) {
        // Vérifier si SweetAlert2 est disponible
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                html: message,
                icon: type,
                confirmButtonText: 'Fermer',
                confirmButtonClass: buttonClass,
                customClass: {
                    popup: 'animated bounceIn'
                }
            });
        }
        // Vérifier si Toastr est disponible
        else if (typeof toastr !== 'undefined') {
            switch(type) {
                case 'success':
                    toastr.success(message, title);
                    break;
                case 'warning':
                    toastr.warning(message, title);
                    break;
                case 'error':
                case 'danger':
                    toastr.error(message, title);
                    break;
                default:
                    toastr.info(message, title);
            }
        }
        // Fallback avec alert simple
        else {
            alert(title + ":\n" + message);
        }
    }

    function Sepatateur_Milliers(param)
    {

        var valSaisie=$(param).val().trim().replace(/\s/g,'');
        //alert(valSaisie);
        if($.isNumeric(valSaisie))
          {
            //.trim();
            if (valSaisie==0)
             {
               $(param).val(valSaisie);
             }
             else
             {
               var str= valSaisie.toString().replace(/\s/g,'');
               var n= str.length;
               if (n < 4)
                {
                  //alert(n);//return valSaisie;
                }
               else
                {
                  //$('#montant_prime').val().replace(/\s/g,'');
                 $(param).val(((n % 3) ? str.substr(0, n % 3) + ' ' : '') + str.substr(n % 3).match(new RegExp('[0-9]{3}', 'g')).join(' '));
                       //);
                }
              }
          }
          else
          {
              //alert("Ce champ nécessite une valeur entière");
              //$(param).removeClass('form-control').addClass('form-control has-warning');
              $(param).val().toString().replace(/\s/g,'');
              return false;
          }
    }

    
    $('#close').on('click', function(){
      setInterval(function(){
          window.location.reload()
      }, 1000)
    });


    // Dans votre layout ou dans un fichier JS global
    function display_message(title, message, type, buttonClass) {
        // Vérifier si SweetAlert2 est disponible
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                html: message,
                icon: type,
                confirmButtonText: 'OK',
                confirmButtonClass: buttonClass,
                customClass: {
                    popup: 'animated bounceIn'
                }
            });
        }
        // Vérifier si Toastr est disponible
        else if (typeof toastr !== 'undefined') {
            switch(type) {
                case 'success':
                    toastr.success(message, title);
                    break;
                case 'warning':
                    toastr.warning(message, title);
                    break;
                case 'error':
                case 'danger':
                    toastr.error(message, title);
                    break;
                default:
                    toastr.info(message, title);
            }
        }
        // Fallback avec alert simple
        else {
            alert(title + ":\n" + message);
        }
    }


     


        
    function validateAndFormatPhone(inputId) {
        const telephoneInput = document.querySelector(`#${inputId}`);

        if (!telephoneInput) {
            console.error(`L'élément avec l'ID "${inputId}" est introuvable.`);
            return null;
        }

        const iti = window.intlTelInputGlobals.getInstance(telephoneInput);

        if (!iti) {
            console.error(`L'instance intlTelInput n'est pas initialisée pour l'élément "${inputId}".`);
            return null;
        }

        const formattedPhone = iti.getNumber();
        const selectedCountry = iti.getSelectedCountryData();
        const cleanedPhone = formattedPhone.replace(/\s+/g, "");

        if (selectedCountry.iso2 === "bj" && cleanedPhone.length !== 14) {
            display_message("Erreur !!", "Le numéro béninois doit contenir exactement 10 chiffres après +229.",
                "warning", "btn btn-danger");
            return null;
        }

        if (selectedCountry.iso2 !== "bj") {
            display_message("Erreur !!", "Numéro de téléphone invalide ou trop court.",
                "warning", "btn btn-danger");
            return null;
        }

        return formattedPhone;
    }

    </script>

    @stack('scripts')

  {{-- ── Barre de progression NProgress (navigation entre pages) ──────── --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.css">
  <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.js"></script>
  <style>
    /* Couleur de la barre NProgress */
    #nprogress .bar { background: #696cff; height: 3px; }
    #nprogress .peg  { box-shadow: 0 0 10px #696cff, 0 0 5px #696cff; }
    #nprogress .spinner-icon { border-top-color: #696cff; border-left-color: #696cff; }
  </style>

  <script>
  // ── Dark / Light mode toggle ─────────────────────────────────────────
  (function () {
    var btn  = document.getElementById('btnThemeToggle');
    var html = document.documentElement;
    if (!btn) return;

    function isDark() { return html.classList.contains('dark-style'); }

    function applyTheme(dark) {
      if (dark) {
        html.classList.add('dark-style');
        html.classList.remove('light-style');
        btn.innerHTML = '<i class="bx bx-sun"></i>';
        btn.title = '{{ __("ui.common.light_mode") }}';
      } else {
        html.classList.add('light-style');
        html.classList.remove('dark-style');
        btn.innerHTML = '<i class="bx bx-moon"></i>';
        btn.title = '{{ __("ui.common.dark_mode") }}';
      }
      try { localStorage.setItem('lokativ-theme', dark ? 'dark' : 'light'); } catch(e) {}
    }

    // Initialiser l'icône selon le thème courant
    applyTheme(isDark());

    btn.addEventListener('click', function () { applyTheme(!isDark()); });
  }());

  (function () {
    // ── NProgress : configuration ─────────────────────────────────────
    if (typeof NProgress !== 'undefined') {
      NProgress.configure({ showSpinner: false, speed: 250, minimum: 0.15 });
    }

    // ── Démarrer la barre dès qu'un lien navigue vers une autre page ──
    document.addEventListener('click', function (e) {
      var el = e.target.closest('a[href]');
      if (!el) return;
      var href = el.getAttribute('href');
      if (!href || href === '#' || href.startsWith('javascript:') ||
          href.startsWith('mailto:') || href.startsWith('tel:') ||
          el.getAttribute('target') === '_blank' ||
          el.getAttribute('download') != null) return;
      try {
        var url = new URL(href, window.location.origin);
        if (url.origin !== window.location.origin) return;
        // Même page (ancre uniquement) → pas de barre
        if (url.pathname === window.location.pathname && url.search === window.location.search) return;
      } catch (err) { return; }
      if (typeof NProgress !== 'undefined') NProgress.start();
    }, true);

    // ── Arrêter la barre quand la page est chargée ────────────────────
    window.addEventListener('load', function () {
      if (typeof NProgress !== 'undefined') NProgress.done();
    });

    // ── Arrêter la barre si le formulaire soumet (navigation form) ───
    document.addEventListener('submit', function () {
      if (typeof NProgress !== 'undefined') NProgress.start();
    }, true);


  }());
  </script>

  </body>
</html>
