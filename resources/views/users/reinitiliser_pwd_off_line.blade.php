@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item active">Réinitialiser mot de passe</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
@if (Session::has("success"))
  <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      {{ Session::get('success') }}
  </div>
  @elseif (Session::has("failed"))
  <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show">
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      {{ Session::get('failed') }}
  </div>
@endif

  <div class="card recent-sales overflow-auto">
    <div class="card-body">
      <h2 class="text-center"><u>Liste des utilisateurs</u></h2>
      <table class="table datatable border-primary">
          <thead>
            <tr>
              <th scope="col">Nom & Prénom</th>
              <th scope="col">E-mail</th>
              <th scope="col">Dernière connexion</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            @if(isset($data))
              @foreach($data as $t)
                <tr>
                  <td>{{ $t->nom }}  {{ $t->prenom }} </td>
                  <td>{{ $t->email }}</td>
                  <td>{{ Carbon\Carbon::parse($t->last_login)->format('d/m/Y à H:i') }}</td>
                  <td>
                    @if(get_status_line() != 'OneLine')
                        @can('action-reinitialiser')
                         <a class="btn btn-danger shadow"
                          title="Réinitiliser" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                               <i class="ri-hammer-fill"></i>
                           </a>
                        @endcan
                    @endif
                  </td>
                </tr>
              @endforeach
          </tbody>
      </table>

    </div>
  </div>

      <!--DEBUT MODAL SUPPRESSION -->
    @foreach($data as $items)
    <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header text-white" style="background-color: #012970;">
            <h5 class="modal-title">Réinitialisation</h5>
          </div>
          <div class="modal-body">

                <form class="row g-3" method="post" action="{{ route('detruit') }}">
                   Voulez-vous vraiment réinitialiser le mot de passe ? 
                 @csrf
                  <input type="hidden" name="user_id" class="form-control" id="user_id" value="{{ $items->id}} ">
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                 <button type="submit" class="btn btn-danger" >Oui</button>
              </div>
             </form>
          </div>
        </div>
      </div>
       </div>
     <!--FIN MODAL SUPPRESSION -->
     @endforeach
   @endif
</section>

<script>

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

  function save_parametre() {

      var data = new FormData();

      //Form data
      var form_data = $('#formulaire').serializeArray();
      $.each(form_data, function (key, input) {
          data.append(input.name, input.value);
      });



      $.ajax({
          url: "{{ route('store_param') }}",
          method: "POST",
          processData: false,
          contentType: false,
          data: data,
          beforeSend: function(data) {
              $("button#close").prop("disabled", true);
              $("button#valider").prop("disabled", true);
              $("button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
          },
          success: function(data) {


              $("button#close").prop("disabled", false);
              $("button#valider").prop("disabled", false);
              $("button#valider").html('Enregistrer');


              if (!$.isEmptyObject(data.error)) {
                  printErrorMsg(data.error);
              }
              try {
                  if (data.status) {
                   // rempliretableau();

                     // alert(data.message);
                      //$("div#afficher").html(data.message)
                      swal('Super !!',data.message,'success');

                      $("form#formulaire")[0].reset();

                      setInterval(function(){
                          window.location.reload()
                      }, 1000)
                  } else {
                     // $("div#afficher").html(data.message)
                      swal('Erreur !!',data.message,'warning');

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

