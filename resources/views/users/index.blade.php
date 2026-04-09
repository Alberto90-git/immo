@extends('layouts.template')

@section('content')

    @section('title')
    <title>{{ __('pages.user_title') }}</title>
    @endsection

    @include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('pages.user_breadcrumb') }}</span></h4>

    @can('ajouter-utilisateur')
      <div class="ms-3 demo-inline-spacing mb-3">
        <a href="{{ route('addUser') }}" class="btn rounded-pill btn-primary">
            <span class="bx bx-plus"></span>&nbsp; {{ __('pages.user_btn_add') }}
        </a>
      </div>
    @endcan

    <div class="card">
      <h5 class="card-header text-center">{{ __('pages.user_list_title') }}</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%">
          <thead>
            <tr>
                <th>{{ __('pages.user_th_agency') }}</th>
                <th>{{ __('pages.user_th_name') }}</th>
                <th>{{ __('pages.user_th_status') }}</th>
                <th width="280px">{{ __('pages.user_th_actions') }}</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('liste-utilisateur')
              @if(isset($data))
                @foreach($data as $user)
                  <tr>
                    <td><strong>{{ get_annexee_name($user->idannexe_ref) }}</strong></td>
                    <td>{{ $user->nom }} {{ $user->prenom }}</td>
                    <td>
                        @if ($user->status == '')
                        <label class="badge rounded-pill bg-success badge-status">{{ __('pages.user_badge_active') }}</label>
                        @else
                        <label class="badge rounded-pill bg-danger badge-status">{{ __('pages.user_badge_disabled') }}</label>
                        @endif
                    </td>
                    <td>
                        @can('modifier-utilisateur')
                          <a class="btn rounded-pill btn-primary" href="{{ route('editView', encrypt_id($user->id)) }}" title="{{ __('common.title_edit') }}">
                            <i class="bx bx-edit-alt me-1"></i>
                          </a>
                        @endcan

                        @can('desactive-utilisateur')
                          <button type="button"
                            class="btn shadow btn-toggle-status {{ $user->status == '' ? 'btn-danger' : 'btn-success' }}"
                            data-id="{{ encrypt_id($user->id) }}"
                            data-status="{{ $user->status == '' ? 'active' : 'inactive' }}"
                            data-nom="{{ $user->nom }} {{ $user->prenom }}"
                            title="{{ $user->status == '' ? __('pages.user_btn_disable') : __('pages.user_btn_enable') }}">
                            {{ $user->status == '' ? __('pages.user_btn_disable') : __('pages.user_btn_enable') }}
                          </button>
                        @endcan

                        @can('liste-utilisateur')
                          <button type="button" class="btn rounded-pill btn-info text-white"
                            data-bs-toggle="modal" data-bs-target="#modalUser{{ $loop->iteration }}" title="{{ __('common.title_details') }}">
                            <i class="bx bx-user-circle me-1"></i>
                          </button>
                        @endcan
                    </td>
                  </tr>

                  <!-- Modal détails utilisateur -->
                  <div class="modal fade" id="modalUser{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">

                        <div class="modal-header" style="background: linear-gradient(135deg, #696cff, #012970);">
                          <h5 class="modal-title text-white">
                            <i class="bx bx-user-circle me-2"></i> {{ __('pages.user_details_modal') }}
                          </h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('common.btn_close') }}"></button>
                        </div>

                        <div class="modal-body p-4">

                          {{-- Avatar + nom --}}
                          <div class="d-flex align-items-center mb-4">
                            <div class="avatar avatar-lg me-3">
                              <span class="avatar-initial rounded-circle text-white fw-bold fs-4"
                                style="width:56px;height:56px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#696cff,#012970);">
                                {{ strtoupper(substr($user->nom, 0, 1)) }}{{ strtoupper(substr($user->prenom, 0, 1)) }}
                              </span>
                            </div>
                            <div>
                              <h5 class="mb-0 fw-bold">{{ $user->nom }} {{ $user->prenom }}</h5>
                              <small class="text-muted">{{ get_annexee_name($user->idannexe_ref) }}</small>
                            </div>
                            <div class="ms-auto">
                              @if ($user->status == '')
                              <span class="badge rounded-pill bg-success">{{ __('pages.user_badge_active') }}</span>
                              @else
                              <span class="badge rounded-pill bg-danger">{{ __('pages.user_badge_disabled') }}</span>
                              @endif
                            </div>
                          </div>

                          <hr class="my-3">

                          {{-- Infos --}}
                          <ul class="list-unstyled mb-0">
                            <li class="d-flex align-items-start py-2 border-bottom">
                              <span class="me-3 mt-1 text-primary"><i class="bx bx-envelope fs-5"></i></span>
                              <div>
                                <small class="text-muted d-block">{{ __('pages.user_detail_email') }}</small>
                                <span class="fw-semibold">{{ $user->email }}</span>
                              </div>
                            </li>
                            <li class="d-flex align-items-start py-2 border-bottom">
                              <span class="me-3 mt-1 text-primary"><i class="bx bx-briefcase fs-5"></i></span>
                              <div>
                                <small class="text-muted d-block">{{ __('pages.user_detail_grade') }}</small>
                                <span class="fw-semibold">{{ $user->grade ?: '—' }}</span>
                              </div>
                            </li>
                            <li class="d-flex align-items-start py-2">
                              <span class="me-3 mt-1 text-primary"><i class="bx bx-shield fs-5"></i></span>
                              <div>
                                <small class="text-muted d-block">{{ __('pages.user_detail_roles') }}</small>
                                @if($user->getRoleNames()->isNotEmpty())
                                  @foreach($user->getRoleNames() as $v)
                                    <span class="badge bg-label-primary me-1">{{ $v }}</span>
                                  @endforeach
                                @else
                                  <span class="text-muted fst-italic">{{ __('pages.user_no_role') }}</span>
                                @endif
                              </div>
                            </li>
                          </ul>

                        </div>

                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bx bx-x me-1"></i> {{ __('common.btn_close') }}
                          </button>
                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- Fin modal -->

                @endforeach
              @endif
            @endcan
          </tbody>
        </table>
      </div>
    </div>

</div>

<script>
var USER_DISABLE_LBL   = '{{ __('pages.user_btn_disable') }}';
var USER_ENABLE_LBL    = '{{ __('pages.user_btn_enable') }}';
var USER_ACTIVE_LBL    = '{{ __('pages.user_badge_active') }}';
var USER_DISABLED_LBL  = '{{ __('pages.user_badge_disabled') }}';
var USER_CONFIRM_TITLE = '{{ __('common.swal_confirm') }}';
var USER_BTN_YES       = '{{ __('common.btn_yes') }}';
var USER_BTN_NO        = '{{ __('common.btn_no') }}';
var USER_SUCCESS_LBL   = '{{ __('common.swal_success') }}';
var USER_ERROR_LBL     = '{{ __('common.swal_error') }}';
var USER_ERR_MSG       = '{{ __('common.swal_unexpected_error') }}';

$(document).on('click', '.btn-toggle-status', function () {
    var $btn   = $(this);
    var id     = $btn.data('id');
    var status = $btn.data('status');
    var nom    = $btn.data('nom');
    var action = status === 'active' ? USER_DISABLE_LBL.toLowerCase() : USER_ENABLE_LBL.toLowerCase();

    Swal.fire({
        title: USER_CONFIRM_TITLE,
        html: 'Voulez-vous <strong>' + action + '</strong> le compte de <strong>' + nom + '</strong> ?',
        icon: status === 'active' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: USER_BTN_YES,
        cancelButtonText: USER_BTN_NO,
        confirmButtonColor: status === 'active' ? '#d33' : '#28a745',
    }).then(function (result) {
        if (!result.isConfirmed) return;

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

        $.ajax({
            url: '{{ url('gerer-user/destroy') }}/' + id,
            method: 'POST',
            data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: function (data) {
                if (!data.status) {
                    display_message(USER_ERROR_LBL, data.message, 'error', 'btn btn-danger');
                    $btn.prop('disabled', false).text(status === 'active' ? USER_DISABLE_LBL : USER_ENABLE_LBL);
                    return;
                }

                if (data.logout) {
                    window.location.href = '{{ route('connexion') }}';
                    return;
                }

                var isNowActive  = data.new_status === 'active';
                var newLabel     = isNowActive ? USER_DISABLE_LBL : USER_ENABLE_LBL;
                var newBtnClass  = isNowActive ? 'btn-danger'  : 'btn-success';
                var prevBtnClass = isNowActive ? 'btn-success' : 'btn-danger';

                $btn.data('status', data.new_status)
                    .attr('title', newLabel)
                    .removeClass(prevBtnClass).addClass(newBtnClass)
                    .prop('disabled', false).text(newLabel);

                var $badge = $btn.closest('tr').find('.badge-status');
                $badge.removeClass('bg-success bg-danger')
                      .addClass(isNowActive ? 'bg-success' : 'bg-danger')
                      .text(isNowActive ? USER_ACTIVE_LBL : USER_DISABLED_LBL);

                display_message(USER_SUCCESS_LBL, data.message, 'success', 'btn btn-primary');
            },
            error: function () {
                display_message(USER_ERROR_LBL, USER_ERR_MSG, 'error', 'btn btn-danger');
                $btn.prop('disabled', false).text(status === 'active' ? USER_DISABLE_LBL : USER_ENABLE_LBL);
            }
        });
    });
});
</script>

@endsection
