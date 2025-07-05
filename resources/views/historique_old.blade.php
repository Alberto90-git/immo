@extends('layouts.template')
@section('content')
<div class="pagetitle">
  <h1>Logs des actions</h1>
</div>

<section class="section dashboard">
        <div class="col-12">
        <div class="col-12 mt-5">
            <form action="{{ route('chekhistorique') }}" method="post">
               @csrf
              <div class="form-row">

                  <div class="form-group col-md-3">
                     <select class="form-select" id="choix" name="choix" onchange="displayChoix();" required>
                      <option disabled selected value="">Choix de recherche</option>
                      <option value="by_user">Par utilisateur</option>
                      <option value="by_date">Par date</option>
                    </select>
                  </div>

                  <div class="form-group col-md-3" id="date_debutDiv" style="display: none;">
                    <input type="date" name="date_debut"  id="date_debut" class="form-control">
                  </div>

                  <div class="form-group col-md-3"  id="date_finDiv" style="display: none;">
                    <input type="date" name="date_fin" id="date_fin" class="form-control">
                  </div>


                  <div class="form-group col-md-6"  style="display: none;" id="user_nameDiv" >
                     <select class="form-select" id="user_name" name="user_name"  aria-label="Floating label select example">
                      <option disabled selected value="">Séléctionner un utilisateur</option>
                      @if(isset($users))
                        @foreach($users as $val)
                          <option value="{{ $val->id }}">{{ $val->nom }} {{ $val->prenom }}</option>
                        @endforeach
                      @endif
                    </select>
                  </div>


                   <div class="col-2 center">
                    <button class="btn sbg1 shadow" type="submit">Recherche</button>
                  </div> 

              </div>
            </form>
        </div>

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- End Sales Card -->

            <div class="col-12">
              <div class="card recent-sales overflow-auto">
                <div class="card-body">
                  <h5 class="card-title text-center">Logs des utilisateurs</h5>

                  <table class="table datatable border-primary">
                    <thead>
                      <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Description de l'action</th>
                        <th scope="col">Date opération</th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(isset($all))
                       @foreach($all as $items)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $items->description }}</td>
                          <td>{{ Carbon\Carbon::parse($items->created_at)->format('d/m/Y à H:m') }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                  </table>

                </div>

              </div>
            </div>
            
          </div>
        </div>

      </div>
       </div>
</section>

<script type="text/javascript">

 


   function displayChoix() {

    
    let val = document.getElementById('choix').value;
    if (val == 'by_user') {

      document.getElementById('user_nameDiv').style.display = 'block';
      document.getElementById('date_debutDiv').style.display = 'none';
      document.getElementById('date_finDiv').style.display = 'none';

      $("#user_name").attr('required', true);
      $("#date_debut").attr('required', false);
      $("#date_fin").attr('required', false);

    } else {

      document.getElementById('user_nameDiv').style.display = 'none';
      document.getElementById('date_debutDiv').style.display = 'block';
      document.getElementById('date_finDiv').style.display = 'block';

      $("#user_name").attr('required', false);
      $("#date_debut").attr('required', true);
      $("#date_fin").attr('required', true);

    }

  } 
</script>
@endsection
