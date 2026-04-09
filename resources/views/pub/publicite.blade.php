@extends('layouts.template')


@section('content')
    @section('title')
    <title>{{ __('pages.pub_title') }}</title>
    @endsection


    @include('notification.display_message')

<style>
    .img-slot {
        width: 130px;
        height: 130px;
        border: 2px dashed #ccc;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        background: #fafafa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        transition: border-color 0.3s;
    }
    .img-slot:hover {
        border-color: #696cff;
    }
    .img-slot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        top: 0; left: 0;
        z-index: 1;
    }
    .slot-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #aaa;
        text-align: center;
        font-size: 0.8rem;
        pointer-events: none;
    }
    .slot-placeholder i {
        font-size: 1.8rem;
        display: block;
        margin-bottom: 4px;
    }
    .img-slot .slot-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        padding: 0 5px;
        font-size: 0.7rem;
        line-height: 1.4;
        z-index: 5;
    }
    .img-slot .file-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 3;
    }
    /* Edit modal */
    .edit-img-box {
        width: 100%;
        height: 120px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        background: #fafafa;
    }
    .edit-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .edit-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #aaa;
        pointer-events: none;
    }
    .edit-col {
        position: relative;
    }
    .edit-col .edit-file-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%;
        height: 120px;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    .edit-col .edit-action-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        z-index: 3;
        padding: 0 5px;
        font-size: 0.7rem;
        line-height: 1.4;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }} /</span> {{ __('pages.pub_title') }}</h4>

    @can('ajouter-publicite')
        <div class="col-md-6">
        <div class="demo-inline-spacing">
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#AjouerPub">
            <span class="bx bx-plus"></span>
            </button>
        </div>
        </div><br/>
    @endcan


    <!-- Modal Ajout -->
    <div class="modal fade" id="AjouerPub" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.pub_add_modal') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:void(0);" id="formulaire" enctype="multipart/form-data">
                @csrf

                 <div class="col-6">
                   <label class="form-label">{{ __('pages.pub_label_address') }}<span style="color: red;">*</span></label>
                   <input type="text" name="adresse" class="form-control" id="adresse" required="">
                   <span class="invalid-feedback adresse_err" role="alert"></span>
                 </div>
                 <div class="col-6">
                   <label class="form-label">{{ __('pages.pub_label_area') }}<span style="color: red;">*</span></label>
                   <input type="text" name="superficie" class="form-control" id="superficie" onkeypress="return /[0-9]/i.test(event.key)" required="">
                   <span class="invalid-feedback superficie_err" role="alert"></span>
                 </div>
                 <div class="col-6">
                   <label class="form-label">{{ __('pages.pub_label_price') }}<span style="color: red;">*</span></label>
                   <input type="text" name="prix_vente" class="form-control" id="prix_vente" onkeypress="return /[0-9]/i.test(event.key)" required="">
                   <span class="invalid-feedback prix_vente_err" role="alert"></span>
                 </div>

                 <div class="col-6">
                   <label class="form-label">{{ __('pages.pub_label_phone') }}<span style="color: red;">*</span></label>
                   <input type="tel" name="telephone" class="form-control" id="telephone" required="">
                   <span class="invalid-feedback telephone_err" role="alert"></span>
                 </div>
                 <div class="col-12">
                   <label class="form-label">{{ __('pages.pub_label_description') }}<span style="color: red;">*</span></label>
                   <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
                   <span class="invalid-feedback description_err" role="alert"></span>
                 </div>

                 <!-- Zone d'upload multi-images (4 slots fixes) -->
                 <div class="col-12">
                     <label class="form-label">{{ __('pages.pub_images_label') }} <small class="text-muted">{{ __('pages.pub_images_required') }}</small></label>
                     <div id="image-upload-zone" class="d-flex flex-wrap gap-2 align-items-start">
                         <div class="img-slot" id="create-slot-1">
                             <input type="file" name="image" id="create-file-1" class="file-overlay" accept="image/jpeg,image/png,image/jpg" onchange="previewCreateImage(this, 1)">
                             <div class="slot-placeholder" id="create-ph-1">
                                 <i class="bx bx-image-add"></i>
                                 Image 1 *
                             </div>
                         </div>
                         <div class="img-slot" id="create-slot-2">
                             <input type="file" name="image2" id="create-file-2" class="file-overlay" accept="image/jpeg,image/png,image/jpg" onchange="previewCreateImage(this, 2)">
                             <div class="slot-placeholder" id="create-ph-2">
                                 <i class="bx bx-image-add"></i>
                                 Image 2
                             </div>
                         </div>
                         <div class="img-slot" id="create-slot-3">
                             <input type="file" name="image3" id="create-file-3" class="file-overlay" accept="image/jpeg,image/png,image/jpg" onchange="previewCreateImage(this, 3)">
                             <div class="slot-placeholder" id="create-ph-3">
                                 <i class="bx bx-image-add"></i>
                                 Image 3
                             </div>
                         </div>
                         <div class="img-slot" id="create-slot-4">
                             <input type="file" name="image4" id="create-file-4" class="file-overlay" accept="image/jpeg,image/png,image/jpg" onchange="previewCreateImage(this, 4)">
                             <div class="slot-placeholder" id="create-ph-4">
                                 <i class="bx bx-image-add"></i>
                                 Image 4
                             </div>
                         </div>
                     </div>
                     <small class="text-muted d-block mt-1">{{ __('pages.pub_images_hint') }}</small>
                 </div>

               <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" id="close" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                 <button class="btn btn-primary" id="valider"><span class="fa fa-save"></span> {{ __('common.btn_save') }}</button>
               </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">{{ __('pages.pub_list') }}</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
                <th scope="col">{{ __('pages.pub_th_address') }}</th>
                <th scope="col">{{ __('pages.pub_th_area') }}</th>
                <th scope="col">{{ __('pages.pub_th_contact') }}</th>
                <th scope="col">{{ __('pages.pub_th_price') }}</th>
                <th scope="col">{{ __('pages.pub_th_images') }}</th>
                <th scope="col">{{ __('common.th_actions') }}</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('voir-detail-publicite')
                @if(isset($allpub))
                 @foreach($allpub as $item)
              <tr id="row-pub-{{ $item->id }}" data-pub-id="{{ $item->id }}">
                <td>{{ $item->localisation }}</td>
                <td>{{ $item->Superficie }} m²</td>
                <td>{{ $item->telephone }}</td>
                <td>{{ number_format($item->price ,"0",",",".") }} XOF</td>
                <td>
                    <span class="badge bg-info" id="img-count-{{ $item->id }}">{{ $item->image_count }} / 4</span>
                    @can('ajouter-image')
                        @if($item->image_count < 4)
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill ms-1" title="{{ __('pages.pub_title_add_img') }}" onclick="openAddImageModal({{ $item->id }})">
                                <i class="bx bx-plus"></i>
                            </button>
                        @endif
                    @endcan
                </td>
                <td>
                   @can('modifier-publicite')
                    @if($item->status == '')
                      <button class="btn rounded-pill btn-primary" title="{{ __('common.title_edit') }}" onclick="openEditModal({{ $item->id }})">
                        <i class="bx bx-edit-alt me-1"></i>
                      </button>
                     @endif
                   @endcan

                   @can('supprimer-publicite')
                    @if($item->status == '')
                      <button class="btn rounded-pill btn-danger btn-supprimer-pub" title="{{ __('common.title_delete') }}"
                              data-id="{{ $item->id }}" data-localisation="{{ $item->localisation }}">
                        <i class="bx bx-trash me-1"></i>
                      </button>
                    @endif
                   @endcan

                    @can('voir-detail-publicite')
                        <button type="button" class="btn rounded-pill btn-primary" onclick="openDetailModal({{ $item->id }})">
                            <i class="bx bx-zoom-in me-1"></i>
                        </button>
                    @endcan
                </td>
              </tr>

            @endforeach
          @endcan
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Hoverable Table rows -->

    @endif

    <!-- Modal Détail Global -->
    <div class="modal fade" id="globalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center w-100" id="globalDetailTitle">{{ __('pages.pub_detail_modal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="gedetail-description" class="mb-3 text-break" style="max-height: 200px; overflow-y: auto;"></div>
                    <div id="gedetail-images" class="row g-2 mb-3"></div>
                    <div class="mt-2">
                        <p class="mb-1"><strong>{{ __('pages.pub_detail_area') }}</strong> <span id="gedetail-superficie"></span> m²</p>
                        <p class="mb-1"><strong>{{ __('pages.pub_detail_price') }}</strong> <span id="gedetail-prix"></span> XOF</p>
                        <p class="mb-1"><strong>{{ __('pages.pub_detail_contact') }}</strong> <span id="gedetail-telephone"></span></p>
                        <p class="mb-1" id="gedetail-publie-row"><strong>{{ __('pages.pub_detail_published') }}</strong> <span id="gedetail-publie"></span></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification Global -->
    <div class="modal fade" id="globalModifier" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ __('pages.pub_edit_modal') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" id="globalModifierForm" enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="id" id="gedit-id">
              <div class="col-6">
                <label class="form-label">{{ __('pages.pub_label_address') }}<span style="color: red;">*</span></label>
                <input type="text" name="adresse" id="gedit-adresse" class="form-control" required>
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.pub_label_area') }}<span style="color: red;">*</span></label>
                <input type="text" name="superficie" id="gedit-superficie" class="form-control" onkeypress="return /[0-9]/i.test(event.key)" required>
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.pub_label_price') }}<span style="color: red;">*</span></label>
                <input type="text" name="prix_vente" id="gedit-prix" class="form-control" onkeypress="return /[0-9]/i.test(event.key)" required>
              </div>
              <div class="col-6">
                <label class="form-label">{{ __('pages.pub_label_phone') }}<span style="color: red;">*</span></label>
                <input type="tel" name="telephone" id="gedit-telephone" class="form-control" required>
              </div>
              <div class="col-12">
                <label class="form-label">{{ __('pages.pub_label_description') }}<span style="color: red;">*</span></label>
                <textarea class="form-control" name="description" id="gedit-description-input" rows="4" required></textarea>
              </div>
              <div class="col-12">
                <label class="form-label">{{ __('pages.pub_edit_images_label') }}</label>
                <div class="row g-2">
                  @foreach([1,2,3,4] as $gnum)
                  @php
                    $gInputName = $gnum === 1 ? 'image_up' : 'image_up'.$gnum;
                    $gOldName   = $gnum === 1 ? 'image_ancien' : 'image_ancien'.$gnum;
                  @endphp
                  <div class="col-6 col-md-3 edit-col">
                    <div class="edit-img-box" id="gedit-box-{{ $gnum }}">
                      <div class="edit-placeholder">
                        <i class="bx bx-image-add" style="font-size:1.5rem;"></i>
                        <small>Image {{ $gnum }}</small>
                      </div>
                    </div>
                    <input type="hidden" name="{{ $gOldName }}" id="gedit-old-{{ $gnum }}" value="">
                    <input type="file" name="{{ $gInputName }}" id="gedit-file-{{ $gnum }}" class="edit-file-overlay" accept="image/jpeg,image/png,image/jpg" onchange="previewEditImage(this, 'gedit', {{ $gnum }})">
                    <button type="button" class="btn btn-sm btn-danger edit-action-btn d-none" id="gedit-del-{{ $gnum }}" title="Supprimer">
                      <i class="bx bx-trash"></i>
                    </button>
                  </div>
                  @endforeach
                </div>
                <small class="text-muted mt-1 d-block">{!! __('pages.pub_edit_images_hint') !!}</small>
              </div>
              <div class="modal-footer w-100">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                @can('modifier-publicite')
                <button type="button" class="btn btn-primary" id="globalModifierBtn" onclick="updatePub()">
                  <span class="fa fa-save"></span> {{ __('common.btn_save') }}
                </button>
                @endcan
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Ajout d'image rapide -->
    <div class="modal fade" id="addImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('pages.pub_add_image_modal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="add-image-pub-id">
                    <div class="mb-3">
                        <label class="form-label">{{ __('pages.pub_add_image_label') }}</label>
                        <input type="file" class="form-control" id="add-image-file" accept="image/jpeg,image/png,image/jpg,image/svg+xml">
                    </div>
                    <small class="text-muted">{{ __('pages.pub_add_image_hint') }}</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
                    @can('ajouter-publicite')
                        <button type="button" class="btn btn-primary" onclick="submitAddImage()">
                            <span id="add-image-spinner" class="d-none"><i class="fa fa-spinner fa-pulse"></i></span>
                            {{ __('pages.pub_btn_add') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>


    <script>

        var PUB_I18N = {
            validationError:  '{{ __('pages.pub_validation_error') }}',
            inProgress:       '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> {{ __('pages.pub_in_progress') }}',
            inProgressShort:  '<i class="fa fa-spinner fa-pulse"></i> {{ __('pages.pub_in_progress') }}',
            btnSave:          '<span class="fa fa-save"></span> {{ __('common.btn_save') }}',
            success:          '{{ __('common.swal_success') }}',
            attention:        '{{ __('common.swal_warning') }}',
            error:            '{{ __('common.swal_error') }}',
            deleteTitle:      '{{ __('pages.pub_delete_title') }}',
            imgDeleteTitle:   '{{ __('pages.pub_img_delete_title') }}',
            deleteYes:        '{{ __('pages.pub_delete_yes') }}',
            cancel:           '{{ __('common.btn_cancel') }}',
            deleted:          '{{ __('pages.pub_deleted') }}',
            selectImage:      '{{ __('pages.pub_select_image') }}',
            unexpected:       '{{ __('pages.pub_unexpected') }}',
            unexpectedDetail: '{{ __('pages.pub_unexpected_detail') }}',
            detailPrefix:     '{{ __('pages.pub_detail_prefix') }}',
            titleEdit:        '{{ __('common.title_edit') }}',
            titleDelete:      '{{ __('common.title_delete') }}',
            titleAddImg:      '{{ __('pages.pub_title_add_img') }}',
        };

        if (typeof jQuery.fn.inputmask !== 'undefined') {
            jQuery("#telephone").inputmask({ "mask": "99 99 99 99 99" });
        }

        $(document).off('.page');
        var CSRF_TOKEN   = '{{ csrf_token() }}';
        var URL_STORE    = '{{ route("store_pub") }}';
        var URL_UPDATE   = '{{ route("update_pub") }}';
        var URL_DESTROY  = '{{ route("destroy_pub") }}';
        var CAN_MODIFIER = {{ auth()->user()->can('modifier-publicite')     ? 'true' : 'false' }};
        var CAN_SUPPR    = {{ auth()->user()->can('supprimer-publicite')    ? 'true' : 'false' }};
        var CAN_DETAIL   = {{ auth()->user()->can('voir-detail-publicite')  ? 'true' : 'false' }};

        // ============================================
        // DONNÉES PUBLICITÉS (indexées par ID)
        // ============================================
        var pubData = @json($allpub->keyBy('id'));
        var storageBase = "{{ asset('storage') }}/";
        var canAjouterImage = {{ Auth::user()->can('ajouter-image') ? 'true' : 'false' }};
        var currentEditPubId = null;

        function getImageCount(pub) {
            var count = 0;
            ['image_url','image_url2','image_url3','image_url4'].forEach(function(f){ if(pub[f]) count++; });
            return count;
        }

        function formatPrice(n) {
            var s = Math.round(Number(n)).toString();
            return s.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ' XOF';
        }

        function esc(s) {
            var d = document.createElement('div');
            d.textContent = s || '';
            return d.innerHTML;
        }

        // ============================================
        // FERMETURE PROPRE DES MODALS
        // ============================================
        function closeModalClean(id) {
            var modalEl = document.getElementById(id);
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                modalEl.removeEventListener('hidden.bs.modal', handler);
            });
        }

        // ============================================
        // CRÉATION : gestion des slots d'images
        // ============================================
        function previewCreateImage(fileInput, num) {
            var slot = document.getElementById('create-slot-' + num);
            var ph = document.getElementById('create-ph-' + num);
            if (!slot || !fileInput.files || !fileInput.files[0]) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                if (ph) ph.style.display = 'none';
                var oldImg = slot.querySelector('.preview-img');
                if (oldImg) oldImg.remove();
                var oldBtn = slot.querySelector('.slot-remove');
                if (oldBtn) oldBtn.remove();
                var img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'preview-img';
                slot.appendChild(img);
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn btn-sm btn-danger slot-remove';
                btn.innerHTML = '<i class="bx bx-x"></i>';
                btn.onclick = function(ev) {
                    ev.preventDefault(); ev.stopPropagation();
                    fileInput.value = ''; img.remove(); btn.remove();
                    if (ph) ph.style.display = '';
                };
                slot.appendChild(btn);
            };
            reader.readAsDataURL(fileInput.files[0]);
        }

        var ajouterModal = document.getElementById('AjouerPub');
        if (ajouterModal) {
            ajouterModal.addEventListener('hidden.bs.modal', function() {
                for (var n = 1; n <= 4; n++) {
                    var f = document.getElementById('create-file-' + n);
                    if (f) f.value = '';
                    var ph = document.getElementById('create-ph-' + n);
                    if (ph) ph.style.display = '';
                    var slot = document.getElementById('create-slot-' + n);
                    if (slot) {
                        var pi = slot.querySelector('.preview-img'); if (pi) pi.remove();
                        var rb = slot.querySelector('.slot-remove'); if (rb) rb.remove();
                    }
                }
                document.getElementById('formulaire').reset();
            });
        }

        // ============================================
        // AJOUT (fetch — pattern maison)
        // ============================================
        $(document).on('submit.page', '#formulaire', function(e) {
            e.preventDefault();
            var form = this;
            var data = new FormData(form);

            var btnValider = document.querySelector('#AjouerPub button#valider');
            var btnClose   = document.querySelector('#AjouerPub button#close');
            btnValider.disabled = true;
            btnValider.innerHTML = PUB_I18N.inProgress;
            btnClose.disabled = true;

            fetch(URL_STORE, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: data
            })
            .then(function(r) { return r.json(); })
            .then(function(resp) {
                btnValider.disabled = false;
                btnValider.innerHTML = PUB_I18N.btnSave;
                btnClose.disabled = false;

                var errors = resp.error || resp.errors;
                if (errors && Object.keys(errors).length > 0) {
                    var msgs = [];
                    Object.keys(errors).forEach(function(k) { var v = errors[k]; msgs.push(Array.isArray(v) ? v[0] : v); });
                    Swal.fire({ icon: 'warning', title: PUB_I18N.validationError, html: msgs.join('<br>') });
                    return;
                }
                if (resp.status) {
                    closeModalClean('AjouerPub');
                    if (resp.data) {
                        pubData[resp.data.id] = resp.data;
                        addPubRow(resp.data);
                    }
                    Swal.fire({ icon: 'success', title: PUB_I18N.success, text: resp.message, timer: 2500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'warning', title: PUB_I18N.attention, text: resp.message });
                }
            })
            .catch(function() {
                btnValider.disabled = false;
                btnValider.innerHTML = PUB_I18N.btnSave;
                btnClose.disabled = false;
                Swal.fire({ icon: 'error', title: PUB_I18N.error, text: PUB_I18N.unexpected });
            });
        });

        // ============================================
        // AJOUT D'UNE LIGNE DANS LE TABLEAU (pattern propriétaire)
        // ============================================
        function addPubRow(d) {
            var cnt = d.image_count !== undefined ? d.image_count : getImageCount(d);
            var addImgBtn = (canAjouterImage && cnt < 4)
                ? `<button type="button" class="btn btn-sm btn-outline-success rounded-pill ms-1" title="${PUB_I18N.titleAddImg}" onclick="openAddImageModal(${d.id})"><i class="bx bx-plus"></i></button>`
                : '';
            var actions = '';
            if (CAN_MODIFIER) {
                actions += `<button class="btn rounded-pill btn-primary me-1" title="${PUB_I18N.titleEdit}" onclick="openEditModal(${d.id})"><i class="bx bx-edit-alt me-1"></i></button>`;
            }
            if (CAN_SUPPR) {
                actions += `<button class="btn rounded-pill btn-danger btn-supprimer-pub me-1" title="${PUB_I18N.titleDelete}" data-id="${d.id}" data-localisation="${esc(d.localisation || '')}"><i class="bx bx-trash me-1"></i></button>`;
            }
            if (CAN_DETAIL) {
                actions += `<button class="btn rounded-pill btn-primary" onclick="openDetailModal(${d.id})"><i class="bx bx-zoom-in me-1"></i></button>`;
            }

            const tr = document.createElement('tr');
            tr.id = 'row-pub-' + d.id;
            tr.setAttribute('data-pub-id', d.id);
            tr.innerHTML = `
                <td>${esc(d.localisation || '')}</td>
                <td>${esc(d.Superficie || '')} m²</td>
                <td>${esc(d.telephone || '')}</td>
                <td>${formatPrice(d.price)}</td>
                <td><span class="badge bg-info" id="img-count-${d.id}">${cnt} / 4</span>${addImgBtn}</td>
                <td>${actions}</td>
            `;

            if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                $('#example').DataTable().row.add(tr).draw(false);
            } else {
                document.querySelector('#example tbody').prepend(tr);
            }
        }

        // ============================================
        // OUVERTURE MODAL MODIFICATION (global)
        // ============================================
        var imgFields = ['image_url','image_url2','image_url3','image_url4'];

        function openEditModal(id) {
            var pub = pubData[id];
            if (!pub) return;
            currentEditPubId = id;

            $('#gedit-id').val(pub.id);
            $('#gedit-adresse').val(pub.localisation);
            $('#gedit-superficie').val(pub.Superficie);
            $('#gedit-prix').val(pub.price);
            $('#gedit-telephone').val(pub.telephone);
            $('#gedit-description-input').val(pub.description);

            for (var i = 1; i <= 4; i++) {
                var imgUrl  = pub[imgFields[i - 1]];
                var box     = document.getElementById('gedit-box-' + i);
                var delBtn  = document.getElementById('gedit-del-' + i);
                var fileIn  = document.getElementById('gedit-file-' + i);
                var oldIn   = document.getElementById('gedit-old-' + i);
                if (fileIn) fileIn.value = '';
                if (imgUrl) {
                    box.innerHTML = '<img src="' + storageBase + imgUrl + '">';
                    if (oldIn) oldIn.value = imgUrl;
                    if (delBtn) {
                        delBtn.className = 'btn btn-sm btn-danger edit-action-btn';
                        delBtn.classList.remove('d-none');
                        delBtn.title = 'Supprimer';
                        delBtn.innerHTML = '<i class="bx bx-trash"></i>';
                        delBtn.onclick = (function(n){ return function(){ event.stopPropagation(); deleteServerImageGlobal(n); }; })(i);
                    }
                } else {
                    box.innerHTML = '<div class="edit-placeholder"><i class="bx bx-image-add" style="font-size:1.5rem;"></i><small>Image ' + i + '</small></div>';
                    if (oldIn) oldIn.value = '';
                    if (delBtn) delBtn.classList.add('d-none');
                }
            }
            new bootstrap.Modal(document.getElementById('globalModifier')).show();
        }

        document.getElementById('globalModifier').addEventListener('hidden.bs.modal', function() {
            document.getElementById('globalModifierForm').reset();
            for (var i = 1; i <= 4; i++) {
                var box = document.getElementById('gedit-box-' + i);
                if (box) box.innerHTML = '<div class="edit-placeholder"><i class="bx bx-image-add" style="font-size:1.5rem;"></i><small>Image ' + i + '</small></div>';
                var delBtn = document.getElementById('gedit-del-' + i);
                if (delBtn) delBtn.classList.add('d-none');
            }
            currentEditPubId = null;
        });

        // ============================================
        // MODIFICATION : preview images
        // ============================================
        function previewEditImage(fileInput, uid, num) {
            var box = document.getElementById(uid + '-box-' + num);
            if (!box || !fileInput.files || !fileInput.files[0]) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                box.innerHTML = '<img src="' + e.target.result + '">';
                var delBtn = document.getElementById(uid + '-del-' + num);
                if (delBtn) {
                    delBtn.className = 'btn btn-sm btn-warning edit-action-btn';
                    delBtn.classList.remove('d-none');
                    delBtn.title = 'Annuler';
                    delBtn.innerHTML = '<i class="bx bx-undo"></i>';
                    delBtn.onclick = function() { restoreEditImage(num); };
                }
            };
            reader.readAsDataURL(fileInput.files[0]);
        }

        function restoreEditImage(num) {
            if (!currentEditPubId || !pubData[currentEditPubId]) return;
            var imgUrl = pubData[currentEditPubId][imgFields[num - 1]];
            var box    = document.getElementById('gedit-box-' + num);
            var delBtn = document.getElementById('gedit-del-' + num);
            var fileIn = document.getElementById('gedit-file-' + num);
            var oldIn  = document.getElementById('gedit-old-' + num);
            if (fileIn) fileIn.value = '';
            if (imgUrl) {
                box.innerHTML = '<img src="' + storageBase + imgUrl + '">';
                if (oldIn) oldIn.value = imgUrl;
                if (delBtn) {
                    delBtn.className = 'btn btn-sm btn-danger edit-action-btn';
                    delBtn.classList.remove('d-none');
                    delBtn.title = 'Supprimer';
                    delBtn.innerHTML = '<i class="bx bx-trash"></i>';
                    delBtn.onclick = (function(n){ return function(){ event.stopPropagation(); deleteServerImageGlobal(n); }; })(num);
                }
            } else {
                box.innerHTML = '<div class="edit-placeholder"><i class="bx bx-image-add" style="font-size:1.5rem;"></i><small>Image ' + num + '</small></div>';
                if (oldIn) oldIn.value = '';
                if (delBtn) delBtn.classList.add('d-none');
            }
        }

        function deleteServerImageGlobal(num) {
            if (!currentEditPubId) return;
            var slotName = imgFields[num - 1];
            Swal.fire({
                title: PUB_I18N.imgDeleteTitle, icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                confirmButtonText: PUB_I18N.deleteYes, cancelButtonText: PUB_I18N.cancel
            }).then(result => {
                if (!result.isConfirmed) return;
                const fd = new FormData();
                fd.append('_token', CSRF_TOKEN);
                fd.append('id', currentEditPubId);
                fd.append('slot', slotName);
                fetch('{{ route("pub_remove_image") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: fd
                })
                .then(r => r.json())
                .then(response => {
                    if (response.status) {
                        Swal.fire({ icon: 'success', title: PUB_I18N.success, text: response.message, timer: 2500, showConfirmButton: false });
                        const box = document.getElementById('gedit-box-' + num);
                        if (box) box.innerHTML = `<div class="edit-placeholder"><i class="bx bx-image-add" style="font-size:1.5rem;"></i><small>Image ${num}</small></div>`;
                        const delBtn = document.getElementById('gedit-del-' + num);
                        if (delBtn) delBtn.classList.add('d-none');
                        const fileIn = document.getElementById('gedit-file-' + num);
                        if (fileIn) fileIn.value = '';
                        const oldIn = document.getElementById('gedit-old-' + num);
                        if (oldIn) oldIn.value = '';
                        if (pubData[currentEditPubId]) pubData[currentEditPubId][slotName] = null;
                        if (response.image_count !== undefined)
                            document.getElementById('img-count-' + currentEditPubId).textContent = response.image_count + ' / 4';
                    } else {
                        Swal.fire({ icon: 'error', title: PUB_I18N.error, text: response.message });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: PUB_I18N.error, text: PUB_I18N.unexpectedDetail }));
            });
        }

        // ============================================
        // ENVOI MODIFICATION (fetch — pattern maison)
        // ============================================
        function updatePub() {
            var data = new FormData(document.getElementById('globalModifierForm'));
            var btn = document.getElementById('globalModifierBtn');
            btn.disabled = true;
            btn.innerHTML = PUB_I18N.inProgressShort;

            fetch(URL_UPDATE, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: data
            })
            .then(function(r) { return r.json(); })
            .then(function(resp) {
                btn.disabled = false;
                btn.innerHTML = PUB_I18N.btnSave;

                if (resp.errors && Object.keys(resp.errors).length > 0) {
                    var msgs = [];
                    Object.keys(resp.errors).forEach(function(k) { var v = resp.errors[k]; msgs.push(Array.isArray(v) ? v[0] : v); });
                    Swal.fire({ icon: 'warning', title: PUB_I18N.validationError, html: msgs.join('<br>') });
                    return;
                }
                if (resp.status) {
                    closeModalClean('globalModifier');
                    Swal.fire({ icon: 'success', title: PUB_I18N.success, text: resp.message, timer: 2500, showConfirmButton: false });
                    if (resp.data) {
                        var d = resp.data;
                        // Mettre à jour pubData (texte + images)
                        if (pubData[d.id]) {
                            pubData[d.id].localisation = d.localisation;
                            pubData[d.id].Superficie   = d.Superficie;
                            pubData[d.id].price        = d.price;
                            pubData[d.id].telephone    = d.telephone;
                            pubData[d.id].description  = d.description;
                            pubData[d.id].image_url    = d.image_url;
                            pubData[d.id].image_url2   = d.image_url2;
                            pubData[d.id].image_url3   = d.image_url3;
                            pubData[d.id].image_url4   = d.image_url4;
                            pubData[d.id].image_count  = d.image_count;
                        }
                        // Mettre à jour le tableau (sans colonne Agence)
                        var row = document.getElementById('row-pub-' + d.id);
                        if (row) {
                            var cells = row.querySelectorAll('td');
                            cells[0].textContent = d.localisation;
                            cells[1].textContent = d.Superficie + ' m²';
                            cells[2].textContent = d.telephone;
                            cells[3].textContent = formatPrice(d.price);
                            if (d.image_count !== undefined)
                                cells[4].querySelector('.badge').textContent = d.image_count + ' / 4';
                            try { $('#example').DataTable().row(row).invalidate().draw(false); } catch(e) {}
                        }
                    }
                } else {
                    Swal.fire({ icon: 'warning', title: PUB_I18N.attention, text: resp.message });
                }
            })
            .catch(function() {
                btn.disabled = false;
                btn.innerHTML = PUB_I18N.btnSave;
                Swal.fire({ icon: 'error', title: PUB_I18N.error, text: PUB_I18N.unexpected });
            });
        }

        // ============================================
        // SUPPRESSION (délégation — pattern propriétaire)
        // ============================================
        $(document).on('click.page', '.btn-supprimer-pub', function() {
            const id  = this.dataset.id;
            const loc = this.dataset.localisation;

            Swal.fire({
                title: PUB_I18N.deleteTitle,
                html: `<strong class="text-danger">${loc}</strong>`,
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
                confirmButtonText: PUB_I18N.deleteYes, cancelButtonText: PUB_I18N.cancel
            }).then(result => {
                if (!result.isConfirmed) return;
                const data = new FormData();
                data.append('_token', CSRF_TOKEN);
                data.append('id', id);
                fetch(URL_DESTROY, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: data
                })
                .then(r => r.json())
                .then(res => {
                    if (res.status) {
                        Swal.fire({ icon: 'success', title: PUB_I18N.deleted, text: res.message, timer: 2500, showConfirmButton: false });
                        if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                            $('#example').DataTable().row('#row-pub-' + id).remove().draw(false);
                        } else {
                            const row = document.getElementById('row-pub-' + id);
                            if (row) row.remove();
                        }
                        delete pubData[id];
                    } else {
                        Swal.fire({ icon: 'error', title: PUB_I18N.error, text: res.message });
                    }
                })
                .catch(() => Swal.fire({ icon: 'error', title: PUB_I18N.error, text: PUB_I18N.unexpectedDetail }));
            });
        });

        // ============================================
        // DÉTAIL (global)
        // ============================================
        function openDetailModal(id) {
            var pub = pubData[id];
            if (!pub) return;
            $('#globalDetailTitle').text(PUB_I18N.detailPrefix + (pub.localisation || ''));
            $('#gedetail-description').text(pub.description || '');
            $('#gedetail-superficie').text(pub.Superficie || '');
            $('#gedetail-prix').text(formatPrice(pub.price).replace(' XOF', ''));
            $('#gedetail-telephone').text(pub.telephone || '');
            var publie = pub.published_at || '';
            $('#gedetail-publie').text(publie);
            $('#gedetail-publie-row').toggle(!!publie);

            var imgsHtml = '';
            imgFields.forEach(function(f) {
                if (pub[f]) {
                    var url = storageBase + pub[f];
                    imgsHtml += '<div class="col-6 col-md-3"><div style="height:150px;overflow:hidden;border-radius:8px;border:1px solid #eee;">' +
                        '<img src="' + url + '" alt="Image publicité" class="w-100 h-100" style="object-fit:cover;cursor:pointer;" onclick="window.open(\'' + url + '\',\'_blank\')">' +
                        '</div></div>';
                }
            });
            $('#gedetail-images').html(imgsHtml);
            new bootstrap.Modal(document.getElementById('globalDetail')).show();
        }

        // ============================================
        // AJOUT RAPIDE (depuis le tableau)
        // ============================================
        function openAddImageModal(pubId) {
            $('#add-image-pub-id').val(pubId);
            $('#add-image-file').val('');
            new bootstrap.Modal(document.getElementById('addImageModal')).show();
        }

        function submitAddImage() {
            var pubId = $('#add-image-pub-id').val();
            var fileInput = document.getElementById('add-image-file');
            if (!fileInput.files || !fileInput.files[0]) {
                Swal.fire({ icon: 'warning', title: PUB_I18N.attention, text: PUB_I18N.selectImage });
                return;
            }
            var data = new FormData();
            data.append('_token', '{{ csrf_token() }}');
            data.append('id', pubId);
            data.append('image', fileInput.files[0]);
            document.getElementById('add-image-spinner').classList.remove('d-none');
            fetch('{{ route("pub_add_image") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                body: data
            })
            .then(r => r.json())
            .then(response => {
                document.getElementById('add-image-spinner').classList.add('d-none');
                if (response.status) {
                    Swal.fire({ icon: 'success', title: PUB_I18N.success, text: response.message, timer: 2500, showConfirmButton: false });
                    bootstrap.Modal.getInstance(document.getElementById('addImageModal')).hide();
                    if (response.image_count !== undefined)
                        document.getElementById('img-count-' + pubId).textContent = response.image_count + ' / 4';
                    if (pubData[pubId] && response.image_url) {
                        for (var i = 0; i < imgFields.length; i++) {
                            if (!pubData[pubId][imgFields[i]]) { pubData[pubId][imgFields[i]] = response.image_url; break; }
                        }
                        pubData[pubId].image_count = response.image_count;
                    }
                    if (response.image_count >= 4) {
                        const addBtn = document.querySelector(`tr[data-pub-id="${pubId}"] .btn-outline-success`);
                        if (addBtn) addBtn.remove();
                    }
                } else {
                    Swal.fire({ icon: 'error', title: PUB_I18N.error, text: response.message });
                }
            })
            .catch(() => {
                document.getElementById('add-image-spinner').classList.add('d-none');
                Swal.fire({ icon: 'error', title: PUB_I18N.error, text: PUB_I18N.unexpectedDetail });
            });
        }

        $(':input').on('input', function() { var id = $(this).attr("id"); if (id) $('.' + id + '_err').hide(); });
        $(':input').on('change', function() { var id = $(this).attr("id"); if (id) $('.' + id + '_err').hide(); });
        $('select').on('change', function() { var id = $(this).attr("id"); if (id) $('.' + id + '_err').hide(); });

        // ============================================
        // DATATABLES INIT
        // ============================================
        if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }
        $('#example').DataTable();

    </script>
@endsection
