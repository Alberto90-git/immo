@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: "{{ Session::get('success') }}",
        showConfirmButton: true,
        confirmButtonText: 'Fermer',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

@if (Session::has('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: "{{ Session::get('error') }}",
        showConfirmButton: true,
        confirmButtonText: 'Fermer',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif
