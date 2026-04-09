@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ __('pages.flash_success') }}',
        text: "{{ Session::get('success') }}",
        showConfirmButton: true,
        confirmButtonText: '{{ __('pages.flash_close') }}',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

@if (Session::has('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: '{{ __('pages.flash_error') }}',
        text: "{{ Session::get('error') }}",
        showConfirmButton: true,
        confirmButtonText: '{{ __('pages.flash_close') }}',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif
