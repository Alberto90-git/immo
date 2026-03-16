@extends('layouts.template')


@section('content')
    @section('title')
    <title>Gestion publicité</title>
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion publicité</h4>

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
            <h5 class="modal-title">Ajouter une publicité</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_pub();" id="formulaire">
                @csrf

                 <div class="col-6">
                   <label class="form-label">Adresse du bien immobilier<span style="color: red;">*</span></label>
                   <input type="text" name="adresse" class="form-control" id="adresse" required="">
                   <span class="invalid-feedback adresse_err" role="alert"></span>
                 </div>
                 <div class="col-6">
                   <label class="form-label">Superficie (m²)<span style="color: red;">*</span></label>
                   <input type="text" name="superficie" class="form-control" id="superficie" onkeypress="return /[0-9]/i.test(event.key)" required="">
                   <span class="invalid-feedback superficie_err" role="alert"></span>
                 </div>
                 <div class="col-6">
                   <label class="form-label">Prix de vente<span style="color: red;">*</span></label>
                   <input type="text" name="prix_vente" class="form-control" id="prix_vente" onkeypress="return /[0-9]/i.test(event.key)" required="">
                   <span class="invalid-feedback prix_vente_err" role="alert"></span>
                 </div>

                 <div class="col-6">
                   <label class="form-label">Numéro à contacter<span style="color: red;">*</span></label>
                   <input type="tel" name="telephone" class="form-control" id="telephone" required="">
                   <span class="invalid-feedback telephone_err" role="alert"></span>
                 </div>
                 <div class="col-12">
                   <label class="form-label">Description du bien<span style="color: red;">*</span></label>
                   <textarea class="form-control" name="description" id="description" rows="4" required></textarea>
                   <span class="invalid-feedback description_err" role="alert"></span>
                 </div>

                 <!-- Zone d'upload multi-images (4 slots fixes) -->
                 <div class="col-12">
                     <label class="form-label">Images <small class="text-muted">(4 max — Image 1 obligatoire)</small></label>
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
                     <small class="text-muted d-block mt-1">Formats: jpeg, png, jpg (Max 2Mo par image)</small>
                 </div>

               <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" id="close" data-bs-dismiss="modal">Fermer</button>
                 <button class="btn btn-primary" id="valider"><span class="fa fa-save"></span> Enregistrer</button>
               </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">Liste des publicités</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
                <th scope="col">Agence</th>
                <th scope="col">Adresse du bien</th>
                <th scope="col">Superficie</th>
                <th scope="col">Contact</th>
                <th scope="col">Prix</th>
                <th scope="col">Images</th>
                <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('voir-detail-publicite')
                @if(isset($allpub))
                 @foreach($allpub as $item)
              <tr>
                <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                <th scope="row">{{ $item->localisation }}</th>
                <td>{{ $item->Superficie }} m²</td>
                <td>{{ $item->telephone }}</td>
                <td>{{ number_format($item->price ,"0",",",".") }} XOF</td>
                <td>
                    <span class="badge bg-info">{{ $item->image_count }} / 4</span>
                    @can('ajouter-image')
                        @if($item->image_count < 4)
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill ms-1" title="Ajouter une image" onclick="openAddImageModal({{ $item->id }})">
                                <i class="bx bx-plus"></i>
                            </button>
                        @endif
                    @endcan
                </td>
                <td>
                   @can('modifier-publicite')
                    @if($item->status == '')
                      <a class="btn rounded-pill btn-primary"
                      title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                        <i class="bx bx-edit-alt me-1"></i>
                       </a>
                     @endif
                   @endcan

                   @can('supprimer-publicite')
                    @if($item->status == '')
                      <a class="btn rounded-pill btn-danger"
                      title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                      <i class="bx bx-trash me-1"></i>
                       </a>
                    @endif
                   @endcan

                    @can('voir-detail-publicite')
                        <button type="button" class="btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                            <i class="bx bx-zoom-in me-1"></i>
                        </button>
                    @endcan
                </td>
              </tr>

              <!-- Modal Détail -->
              <div class="modal fade" id="disablebackdrop{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center w-100">Détails - {{ $item->localisation }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3 text-break" style="max-height: 200px; overflow-y: auto;">
                              {{ $item->description }}
                            </div>
                            @php $itemImages = $item->images; @endphp
                            @if(count($itemImages) > 0)
                            <div class="row g-2">
                                @foreach($itemImages as $img)
                                <div class="col-6 col-md-3">
                                    <div style="height:150px; overflow:hidden; border-radius:8px; border:1px solid #eee;">
                                        <img src="{{ asset('storage/'.$img) }}" alt="Image publicité"
                                             class="w-100 h-100" style="object-fit:cover; cursor:pointer;"
                                             onclick="window.open('{{ asset('storage/'.$img) }}', '_blank')">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                            <div class="mt-3">
                                <p class="mb-1"><strong>Superficie:</strong> {{ $item->Superficie }} m²</p>
                                <p class="mb-1"><strong>Prix:</strong> {{ number_format($item->price, 0, ',', '.') }} XOF</p>
                                <p class="mb-1"><strong>Contact:</strong> {{ $item->telephone }}</p>
                                @if($item->published_at)
                                <p class="mb-1"><strong>Publié le:</strong> {{ $item->published_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
              </div>

            @endforeach
          @endcan
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Hoverable Table rows -->

    @foreach($allpub as $items)
      <!-- Modal Suppression -->
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="{{ route('destroy_pub') }}">
                    Voulez-vous vraiment supprimer cette ligne ?
                    @csrf
                 <input type="hidden" name="id" class="form-control" value="{{ $items->id }}">
                 <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
                  <button type="submit" class="btn btn-outline-danger">Oui</button>
                 </div>
                </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Modification -->
      <div class="modal fade" id="modifier{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Modification</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="{{ route('update_pub') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $items->id }}">

                    <div class="col-6">
                      <label class="form-label">Adresse du bien immobilier<span style="color: red;">*</span></label>
                      <input type="text" name="adresse" class="form-control" value="{{ $items->localisation }}" required="">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Superficie (m²)<span style="color: red;">*</span></label>
                      <input type="text" onkeypress="return /[0-9]/i.test(event.key)" name="superficie" class="form-control" value="{{ $items->Superficie }}" required="">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Prix de vente<span style="color: red;">*</span></label>
                      <input type="text" name="prix_vente" class="form-control" value="{{ $items->price }}" onkeypress="return /[0-9]/i.test(event.key)" required="">
                    </div>
                    <div class="col-6">
                      <label class="form-label">Numéro à contacter<span style="color: red;">*</span></label>
                      <input type="tel" name="telephone" class="form-control" value="{{ $items->telephone }}" required="">
                    </div>
                    <div class="col-12">
                      <label class="form-label">Description du bien<span style="color: red;">*</span></label>
                      <textarea class="form-control" name="description" rows="4" required>{{ $items->description }}</textarea>
                    </div>

                    <!-- Images existantes + upload -->
                    <div class="col-12">
                        <label class="form-label">Images (cliquez pour remplacer)</label>
                        <div class="row g-2">
                            @php
                                $editSlots = [
                                    ['field' => 'image_url', 'input' => 'image_up', 'old' => 'image_ancien', 'slot_name' => 'image_url', 'num' => 1],
                                    ['field' => 'image_url2', 'input' => 'image_up2', 'old' => 'image_ancien2', 'slot_name' => 'image_url2', 'num' => 2],
                                    ['field' => 'image_url3', 'input' => 'image_up3', 'old' => 'image_ancien3', 'slot_name' => 'image_url3', 'num' => 3],
                                    ['field' => 'image_url4', 'input' => 'image_up4', 'old' => 'image_ancien4', 'slot_name' => 'image_url4', 'num' => 4],
                                ];
                                $editUid = 'edit' . $items->id;
                            @endphp
                            @foreach($editSlots as $slot)
                            <div class="col-6 col-md-3 edit-col">
                                <div class="edit-img-box" id="{{ $editUid }}-box-{{ $slot['num'] }}">
                                    @if(!empty($items->{$slot['field']}))
                                        <img src="{{ asset('storage/'.$items->{$slot['field']}) }}">
                                        <input type="hidden" name="{{ $slot['old'] }}" value="{{ $items->{$slot['field']} }}">
                                    @else
                                        <div class="edit-placeholder" id="{{ $editUid }}-ph-{{ $slot['num'] }}">
                                            <i class="bx bx-image-add" style="font-size:1.5rem;"></i>
                                            <small>Image {{ $slot['num'] }}</small>
                                        </div>
                                    @endif
                                </div>
                                <input type="file" name="{{ $slot['input'] }}" id="{{ $editUid }}-file-{{ $slot['num'] }}" class="edit-file-overlay" accept="image/jpeg,image/png,image/jpg,image/svg+xml" onchange="previewEditImage(this, '{{ $editUid }}', {{ $slot['num'] }})">
                                @if(!empty($items->{$slot['field']}))
                                <button type="button" class="btn btn-sm btn-danger edit-action-btn" id="{{ $editUid }}-del-{{ $slot['num'] }}" title="Supprimer"
                                        onclick="event.stopPropagation(); deleteServerImage({{ $items->id }}, '{{ $slot['slot_name'] }}', '{{ $editUid }}', {{ $slot['num'] }})">
                                    <i class="bx bx-trash"></i>
                                </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <small class="text-muted mt-1 d-block">Cliquez sur un slot pour ajouter/remplacer, <i class="bx bx-trash"></i> pour supprimer</small>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                      @can('modifier-publicite')
                       <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Enregistrer</button>
                      @endcan
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    @endif

    <!-- Modal Ajout d'image rapide -->
    <div class="modal fade" id="addImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="add-image-pub-id">
                    <div class="mb-3">
                        <label class="form-label">Sélectionner une image</label>
                        <input type="file" class="form-control" id="add-image-file" accept="image/jpeg,image/png,image/jpg,image/svg+xml">
                    </div>
                    <small class="text-muted">Formats: jpeg, png, jpg,(Max 2Mo par image)</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    @can('ajouter-publicite')
                        <button type="button" class="btn btn-primary" onclick="submitAddImage()">
                            <span id="add-image-spinner" class="d-none"><i class="fa fa-spinner fa-pulse"></i></span>
                            Ajouter
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>


    <script>

        jQuery("#telephone").inputmask({
          "mask": "99 99 99 99 99"
        });

        // ============================================
        // CREATION : gestion des slots d'images
        // ============================================
        var maxImages = 4;

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
                    ev.preventDefault();
                    ev.stopPropagation();
                    fileInput.value = '';
                    img.remove();
                    btn.remove();
                    if (ph) ph.style.display = '';
                };
                slot.appendChild(btn);
            };
            reader.readAsDataURL(fileInput.files[0]);
        }

        // Reset le modal d'ajout à la fermeture
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
                        var pi = slot.querySelector('.preview-img');
                        if (pi) pi.remove();
                        var rb = slot.querySelector('.slot-remove');
                        if (rb) rb.remove();
                    }
                }
                document.getElementById('formulaire').reset();
            });
        }

        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }

        function save_pub() {
            var data = new FormData();

            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });

            document.querySelectorAll('#image-upload-zone input[type="file"]').forEach(function(input) {
                if (input.files && input.files[0]) {
                    data.append(input.name, input.files[0]);
                }
            });

            $.ajax({
                url: "{{ route('store_pub') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function() {
                    $("#AjouerPub button#close").prop("disabled", true);
                    $("#AjouerPub button#valider").prop("disabled", true);
                    $("#AjouerPub button#valider").html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> En cours...');
                },
                success: function(data) {
                    $("#AjouerPub button#close").prop("disabled", false);
                    $("#AjouerPub button#valider").prop("disabled", false);
                    $("#AjouerPub button#valider").html('<span class="fa fa-save"></span> Enregistrer');

                    var errors = data.error || data.errors;
                    if (errors && !$.isEmptyObject(errors)) {
                        var msgs = [];
                        $.each(errors, function(key, value) {
                            msgs.push(Array.isArray(value) ? value[0] : value);
                        });
                        display_message("Erreur de validation", msgs.join("<br>"), "warning", "btn btn-danger");
                        return;
                    }
                    try {
                        if (data.status) {
                            display_message("Super !!", data.message, "success", "btn btn-primary");
                            setTimeout(function() { location.reload(); }, 1500);
                        } else {
                            display_message("Erreur !!", data.message, "warning", "btn btn-danger");
                        }
                    } catch (error) {}
                },
                error: function(xhr) {
                    $("#AjouerPub button#close").prop("disabled", false);
                    $("#AjouerPub button#valider").prop("disabled", false);
                    $("#AjouerPub button#valider").html('<span class="fa fa-save"></span> Enregistrer');

                    var msgs = [];
                    try {
                        var resp = xhr.responseJSON;
                        var errors = resp.errors || resp.error;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                msgs.push(Array.isArray(value) ? value[0] : value);
                            });
                        } else if (resp.message) {
                            msgs.push(resp.message);
                        }
                    } catch(e) {}

                    if (msgs.length > 0) {
                        display_message("Erreur de validation", msgs.join("<br>"), "warning", "btn btn-danger");
                    } else {
                        display_message("Erreur !!", "Une erreur est survenue, veuillez réessayer", "warning", "btn btn-danger");
                    }
                }
            });
        }

        // ============================================
        // MODIFICATION : preview + suppression
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
                    delBtn.title = 'Annuler';
                    delBtn.innerHTML = '<i class="bx bx-undo"></i>';
                    delBtn.onclick = function() { location.reload(); };
                }
            };
            reader.readAsDataURL(fileInput.files[0]);
        }

        function deleteServerImage(pubId, slotName, editUid, num) {
            Swal.fire({
                title: 'Supprimer cette image ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                $.ajax({
                url: "{{ route('pub_remove_image') }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: pubId,
                    slot: slotName
                },
                success: function(response) {
                    if (response.status) {
                        display_message("Super !!", response.message, "success", "btn btn-primary");
                        var box = document.getElementById(editUid + '-box-' + num);
                        if (box) {
                            box.innerHTML =
                                '<div class="edit-placeholder" id="' + editUid + '-ph-' + num + '">' +
                                    '<i class="bx bx-image-add" style="font-size:1.5rem;"></i>' +
                                    '<small>Image ' + num + '</small>' +
                                '</div>';
                        }
                        var delBtn = document.getElementById(editUid + '-del-' + num);
                        if (delBtn) delBtn.remove();
                        var fileInput = document.getElementById(editUid + '-file-' + num);
                        if (fileInput) fileInput.value = '';
                    } else {
                        display_message("Erreur !!", response.message, "warning", "btn btn-danger");
                    }
                },
                error: function() {
                    display_message("Erreur !!", "Erreur lors de la suppression", "warning", "btn btn-danger");
                }
            });
            });
        }

        // ============================================
        // AJOUT RAPIDE (depuis le tableau)
        // ============================================
        function openAddImageModal(pubId) {
            $('#add-image-pub-id').val(pubId);
            $('#add-image-file').val('');
            var modal = new bootstrap.Modal(document.getElementById('addImageModal'));
            modal.show();
        }

        function submitAddImage() {
            var pubId = $('#add-image-pub-id').val();
            var fileInput = document.getElementById('add-image-file');

            if (!fileInput.files || !fileInput.files[0]) {
                display_message("Erreur", "Veuillez sélectionner une image", "warning", "btn btn-danger");
                return;
            }

            var data = new FormData();
            data.append('_token', '{{ csrf_token() }}');
            data.append('id', pubId);
            data.append('image', fileInput.files[0]);

            $('#add-image-spinner').removeClass('d-none');

            $.ajax({
                url: "{{ route('pub_add_image') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                success: function(response) {
                    $('#add-image-spinner').addClass('d-none');
                    if (response.status) {
                        display_message("Super !!", response.message, "success", "btn btn-primary");
                        bootstrap.Modal.getInstance(document.getElementById('addImageModal')).hide();
                        setTimeout(function() { location.reload(); }, 1500);
                    } else {
                        display_message("Erreur !!", response.message, "warning", "btn btn-danger");
                    }
                },
                error: function() {
                    $('#add-image-spinner').addClass('d-none');
                    display_message("Erreur !!", "Erreur lors de l'ajout", "warning", "btn btn-danger");
                }
            });
        }

        $(':input').on('input', function() {
            var id = $(this).attr("id");
            if (id) $('.' + id + '_err').hide();
        });

        $(':input').on('change', function() {
            var id = $(this).attr("id");
            if (id) $('.' + id + '_err').hide();
        });

        $('select').on('change', function() {
            var id = $(this).attr("id");
            if (id) $('.' + id + '_err').hide();
        });

    </script>
@endsection
