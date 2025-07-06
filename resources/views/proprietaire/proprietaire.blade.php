@extends('layouts.template')


@section('content')

@section('title')
    <title>Gestion propriétaire</title>
@endsection

@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion propriétaire</h4>
    @can('ajoute-proprietaire')
        <div class="col-md-6">
            <div class="demo-inline-spacing">
                <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#AjouerProprietaire">
                    <span class="bx bx-plus"></span>
                </button>
            </div>
        </div><br />
    @endcan

    @include('proprietaire.ajouter')

    @include('proprietaire.liste')

    @include('proprietaire.modifier')


    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: [{
                        extend: 'copy',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    },
                    'colvis'
                ]
            }).buttons().container().appendTo('#example .col-md-6:eq(0)');
        });


        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }


        function limit(element) {
            var max_chars = 8;
            if (element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }

        function save_proprietaire() {

            const formattedPhone = validateAndFormatPhone('telephone');
            if (!formattedPhone) return;



            var data = new FormData();

            var form_data = $('#formulaireProprietaire').serializeArray();
            $.each(form_data, function(key, input) {

                if (input.name === "telephone") {
                    data.append("telephone", formattedPhone);
                } else {
                    data.append(input.name, input.value);
                }
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            /*var data = new FormData();

            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            }); */

            $.ajax({
                url: "{{ route('store_propre') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("#AjouerProprietaire button#close").prop("disabled", true);
                    $("#AjouerProprietaire button#valider").prop("disabled", true);
                    $("#AjouerProprietaire button#valider").html(
                        '<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
                },
                success: function(data) {


                    $("#AjouerProprietaire button#close").prop("disabled", false);
                    $("#AjouerProprietaire button#valider").prop("disabled", false);
                    $("#AjouerProprietaire button#valider").html('Enregistrer');


                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                    }
                    try {
                        if (data.status) {
                            // rempliretableau();

                            // alert(data.message);
                            // $("#AjouerProprietaire div#afficher").html(data.message)
                            display_message("Super !!", data.message, "success", "btn btn-primary");

                            $("#AjouerProprietaire form#formulaireProprietaire")[0].reset();
                        } else {
                            //$("#AjouerProprietaire div#afficher").html(data.message)
                            display_message("Erreur !!", data.message, "warning", "btn btn-danger");

                        }

                    } catch (error) {

                    }

                },
                error: function(data) {

                }
            });

        }



        function printErrorMsg(msg) {
            const items = [];
            for (const [key, value] of Object.entries(msg)) {
                $('.' + key + '_err').text(value).show();
                var elmnt = $('.' + key + '_err');
                items.push(elmnt.closest('.form-group'))
            }
        }

        $(':input').on('input', function() {
            $('.' + $(this).attr("id") + '_err').hide();
        });


        $(':input').on('change', function() {
            $('.' + $(this).attr("id") + '_err').hide();
        });


        $('select').on('change', function() {
            $('.' + $(this).attr("id") + '_err').hide();
        });
    </script>
@endsection
