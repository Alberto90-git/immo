@if (Session::has("success"))
<div class="col-md-6 p-4">
  <div class="toast-container">
  <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
      <i class="bx bx-bell me-2"></i>
      <div class="me-auto fw-semibold">SUCCES</div>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">
        {{ Session::get('success') }}
      </div>
  </div>
  </div>
</div>
@elseif (Session::has("error"))
  <div class="col-md-6 p-4">
      <div class="toast-container">
      <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header">
          <i class="bx bx-bell me-2"></i>
          <div class="me-auto fw-semibold">ERREUR</div>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
          <div class="toast-body">
            {{ Session::get('error') }}
          </div>
      </div>
      </div>
  </div>
@endif