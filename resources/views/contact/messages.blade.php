@extends('layouts.template')

@section('title')
    <title>{{ __('pages.contact_title') }}</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Super Admin /</span> {{ __('pages.contact_title') }}
    </h4>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="bx bx-envelope me-2"></i>{{ __('pages.contact_card') }}
            </h5>
            @if($nbNonLus > 0)
                <span class="badge bg-danger fs-6">{{ $nbNonLus }} non lu{{ $nbNonLus > 1 ? 's' : '' }}</span>
            @else
                <span class="badge bg-success">{{ __('pages.contact_show_all') }}</span>
            @endif
        </div>

        <div class="card-body">
            @if($messages->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bx bx-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-3">{{ __('pages.contact_empty') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table id="tbl-messages" class="table table-hover align-middle">
                        <thead class="table-dark text-white">
                            <tr>
                                <th style="width:4%">#</th>
                                <th style="width:14%"><i class="bx bx-user me-1"></i>{{ __('pages.contact_th_sender') }}</th>
                                <th style="width:16%"><i class="bx bx-envelope me-1"></i>{{ __('pages.contact_th_email') }}</th>
                                <th style="width:14%"><i class="bx bx-tag me-1"></i>{{ __('pages.contact_th_subject') }}</th>
                                <th style="width:34%"><i class="bx bx-message-detail me-1"></i>{{ __('pages.contact_th_message') }}</th>
                                <th style="width:12%" class="text-center"><i class="bx bx-calendar me-1"></i>{{ __('pages.contact_th_date') }}</th>
                                <th style="width:6%" class="text-center">{{ __('common.th_actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $msg)
                            <tr id="row-{{ $msg->id }}" class="{{ !$msg->lu ? 'table-warning fw-semibold' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $msg->nom }} {{ $msg->prenom }}
                                    @if(!$msg->lu)
                                        <span class="badge bg-danger ms-1" style="font-size:0.65rem;">{{ __('pages.contact_badge_new') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="mailto:{{ $msg->email }}" class="text-primary">{{ $msg->email }}</a>
                                </td>
                                <td>
                                    @php
                                        $sujets = [
                                            'information'  => 'Demande d\'information',
                                            'demo'         => 'Démonstration',
                                            'support'      => 'Support technique',
                                            'partenariat'  => 'Partenariat',
                                            'autre'        => 'Autre',
                                        ];
                                    @endphp
                                    <span class="badge bg-label-primary">{{ $sujets[$msg->sujet] ?? $msg->sujet }}</span>
                                </td>
                                <td>
                                    <div class="msg-preview" id="preview-{{ $msg->id }}" style="max-height:60px; overflow:hidden; cursor:pointer;" onclick="toggleMsg({{ $msg->id }})">
                                        {!! nl2br(e($msg->message)) !!}
                                    </div>
                                    <small class="text-muted msg-hint" id="hint-{{ $msg->id }}">
                                        <i class="bx bx-chevron-down"></i> Afficher tout
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-label-secondary" style="font-size:0.7rem; line-height:1.5;">
                                        {{ \Carbon\Carbon::parse($msg->created_at)->format('d/m/Y') }}<br>
                                        {{ \Carbon\Carbon::parse($msg->created_at)->format('H:i') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <button class="btn btn-sm btn-icon btn-outline-danger"
                                                title="{{ __('common.title_delete') }}"
                                                onclick="deleteMsg({{ $msg->id }})">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    var MSG_I18N = {
        reduce:       '<i class="bx bx-chevron-up"></i> {{ __('pages.contact_reduce') }}',
        showAll:      '<i class="bx bx-chevron-down"></i> {{ __('pages.contact_show_all') }}',
        deleteTitle:  '{{ __('pages.contact_delete_title') }}',
        deleteText:   '{{ __('pages.contact_delete_msg') }}',
        deleteBtn:    '{{ __('common.title_delete') }}',
        cancelBtn:    '{{ __('common.btn_cancel') }}',
        deleted:      '{{ __('pages.contact_deleted_ok') }}',
        deleteFail:   '{{ __('pages.contact_deleted_fail') }}',
        swalError:    '{{ __('common.swal_error') }}',
    };

    // Déplier / replier un message
    function toggleMsg(id) {
        const preview = document.getElementById('preview-' + id);
        const hint    = document.getElementById('hint-' + id);
        if (preview.style.maxHeight === '60px') {
            preview.style.maxHeight = 'none';
            hint.innerHTML = MSG_I18N.reduce;
        } else {
            preview.style.maxHeight = '60px';
            hint.innerHTML = MSG_I18N.showAll;
        }
    }

    // Supprimer un message
    function deleteMsg(id) {
        Swal.fire({
            title: MSG_I18N.deleteTitle,
            text: MSG_I18N.deleteText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: MSG_I18N.deleteBtn,
            cancelButtonText: MSG_I18N.cancelBtn,
            confirmButtonColor: '#d33',
        }).then(result => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '{{ url("super-admin/messages-contact") }}/' + id + '/destroy',
                method: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.status) {
                        document.getElementById('row-' + id).remove();
                        Swal.fire({ icon: 'success', title: MSG_I18N.deleted, timer: 1200, showConfirmButton: false });
                    }
                },
                error: function () {
                    Swal.fire({ icon: 'error', title: MSG_I18N.swalError, text: MSG_I18N.deleteFail });
                }
            });
        });
    }
</script>
@endpush
@endsection
