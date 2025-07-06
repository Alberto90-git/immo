@if (Session::has("message"))
    <div class="alert alert-primary alert-dismissible" role="alert">
        {{ Session::get('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@elseif (Session::has("error"))
    <div class="alert alert-danger alert-dismissible" role="alert">
        {{ Session::get('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif