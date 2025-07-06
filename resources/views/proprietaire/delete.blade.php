<div class="modal fade" id="supprimer{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Confirmation de suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Fermer"></button>
            </div>

            <div class="modal-body text-center">
                <form method="POST" action="{{ route('destroy_proprio') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $items->id }}">

                    <p class="mb-3">
                        Voulez-vous vraiment supprimer ce propriétaire ?<br>
                        <strong class="text-danger">{{ $items->nom }} {{ $items->prenom }}</strong>
                    </p>

                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-45" data-bs-dismiss="modal">
                            <i class="bx bx-x"></i> Non
                        </button>
                        <button type="submit" class="btn btn-outline-danger btn-sm w-45">
                            <i class="bx bx-trash"></i> Oui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
