<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a  class="logo d-flex align-items-center">
        <!-- <img src="{{ asset('assets/img/logo.png') }}" alt=""> -->
        <span class="d-none d-lg-block">Immo Manager</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    
      

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
      
        <li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i>
          </a>
        </li>

        @php
         $liste = get_locataire_liste();
        @endphp


        @can('manager-contrat')
            <button type="button" class="btn sbg1 rounded-pill ri-user-add-line shadow" 
            data-bs-toggle="modal" data-bs-target="#contrat">Gérer contrat</button> <br/>
        @endcan

          <div class="modal fade" id="contrat" tabindex="-1" data-bs-backdrop="false">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header text-white" style="background-color: #012970;">
                  <h5 class="modal-title">Imprimer le contrat d'un locatire</h5>
                </div>
                <form action="{{ route('download_contrat') }}" method="post">
                    @csrf
                  <div class="modal-body">
                    <div class="row mb-3">
                      <div class="col-sm-10">
                        <select class="form-select" name="idlocataire" aria-label="multiple select example" required>
                          <option value="" disabled selected>--Choisissez un locataire--</option>
                          @if(isset($liste))
                            @foreach($liste as $list)
                              <option value="{{ $list->id }}">{{ $list->nom }}  {{ $list->prenom }} </option>
                            @endforeach 
                          @endif
                        </select>
                      </div>
                    </div>

                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn sbg1">Valder</button>
                    </div>
                </form>
              </div>
            </div> 
        </div>

            

        


        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <!-- <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle"> -->
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->nom }} 
              {{ Auth::user()->prenom }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
             
              <span>{{ Auth::user()->grade }}</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('profileView') }}">
                <i class="bi bi-person"></i>
                <span>Mon Profile</span>
              </a>
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
              onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <i class="bi bi-box-arrow-right"></i>
                <span>Quitter</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header>