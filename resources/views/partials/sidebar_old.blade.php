 <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      @can('parametrage')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('parametrage')) }}" href="{{ route('parametrage') }}">
          <i class="ri-settings-2-fill"></i>
          <span>Paramétrage</span>
        </a>
      </li>
      
    @endcan

    @can('gestion-proprietaire')
    <li class="nav-item">
      <a class="nav-link" href="{{ route('getViewCompte') }}">
        <i class="ri-user-add-fill"></i>
        <span>Gestion entreprise</span>
      </a>
    </li>
 @endcan


      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('home')) }}" href="{{ route('home') }}">
          <i class="bi bi-grid"></i>
          <span>Accueil</span>
        </a>
      </li>
      
    @can('gestion-role-utilisateur')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('roles') || request()->is('roles/create') || request()->is('gerer-user/utilisateur') || request()->is('gerer-user/add')  || request()->is('password-off-line') ) }}" data-bs-target="#user" data-bs-toggle="collapse" href="#">
        
          <i class="ri-group-fill"></i><span>Gestion utilisateur</span><i class="bi bi-chevron-down ms-auto"></i>
          
        </a>
        <ul id="user" class="nav-content collapse {{ set_show(request()->is('roles') || request()->is('roles/create') || request()->is('gerer-user/utilisateur') || request()->is('gerer-user/add')  || request()->is('password-off-line') ) }}" data-bs-parent="#sidebar-nav">
       
        @can('gestion-role')
          <li>
            <a class="{{ set_active(request()->is('roles') || request()->is('roles/create')) }}" href="{{ route('roles.index') }}">
              <i class="bi bi-circle"></i><span>Gestion des rôles</span>
            </a>
          </li>
        @endcan  
      
        @can('gestion-utilisateur')
          <li>
            <a class="{{ set_active(request()->is('gerer-user/utilisateur') || request()->is('gerer-user/add')) }}" href="{{ route('getUserView') }}">
              <i class="bi bi-circle"></i><span>Gestion des utilisateurs</span>
            </a>
          </li>
        @endcan
        @can('gestion-mot-passeBloque')
          <li>
            <a class="{{ set_active( request()->is('password-off-line') ) }}" href="{{ route('passwordOffLine') }}">
              <i class="bi bi-circle"></i><span>Gestion mot de passe</span>
            </a>
          </li>
        @endcan
        

        </ul>
      </li>
    @endcan

    @can('gestion-proprietaire')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('gerer-proprietaire/create')) }}" href="{{ route('get_proprioView') }}">
          <i class="ri-user-add-fill"></i>
          <span>Gestion propriétaire</span>
        </a>
      </li>
   @endcan
    
    @can('gestion-maison')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('gerer-maison/create')) }}" href="{{ route('get_maisonView') }}">
          <i class="ri-home-4-fill"></i>
          <span>Gestion des maisons</span>
        </a>
      </li>
    @endcan   
    
    @can('gestion-chambre')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('gerer-chambre/create')) }}" href="{{ route('get_chambreView') }}">
          <i class="ri-home-4-fill"></i>
          <span>Gestion des chambres</span>
        </a>
      </li>
    @endcan

    @can('gestion-prix')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('gerer-prix/create')) }}" href="{{ route('get_prixView') }}">
          <i class="ri-money-dollar-box-fill"></i>
          <span>Gestion des prix</span>
        </a>
      </li>
    @endcan

    @can('gestion-locataire')  
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('gerer-locataire/create')) }}" href="{{ route('get_locataireView') }}">
          <i class="ri-user-2-fill"></i>
          <span>Gestion des locataires</span>
        </a>
      </li>
    @endcan  

    @can('gestion-paiement')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('gerer-facture/create')) }}" href="{{ route('get_factureView') }}">
          <i class="ri-hand-coin-fill"></i>
          <span>Gestion des paiements</span>
        </a>
      </li>
    @endcan


   @can('gestion-dossier')
    <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('parcelle') ||  request()->is('client') ) }}" data-bs-target="#terrain" data-bs-toggle="collapse" href="#">
          <i class="ri-folder-3-fill"></i><span>Gestion des dossiers</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="terrain" class="nav-content collapse  {{ set_show(request()->is('parcelle') || request()->is('client') ) }}"  data-bs-parent="#sidebar-nav">

          @can('dossier-client')
          <li>
            <a  class="{{ set_active(request()->is('client')) }}" href="{{ route('getViewClient') }}">
              <i class="bi bi-gear"></i>
              <span>Dossier client</span>
            </a>
          </li>
          @endcan

          @can('dossier-parcelle')
          <li>
            <a class="{{ set_active(request()->is('parcelle')) }}"  href="{{ route('getViewParcelle') }}">
              <i class="bi bi-circle"></i><span title="Parcelle">Dossier parcelle</span>
            </a>
          </li>
          @endcan

        </ul>
      </li>
    @endcan
     
    @can('gestion-statistique')
      <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('gerer-statistique-list') || request()->is('statistique-recu') || request()->is('finance') || request()->is('statistique-dossier') )}}" data-bs-target="#statistique" data-bs-toggle="collapse" href="#">
          <i class="ri-list-ordered"></i><span>Gestion statistique</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="statistique" class="nav-content collapse  {{ set_show(request()->is('gerer-statistique-list') || request()->is('statistique-recu')  || request()->is('finance')  || request()->is('statistique-dossier') )}}" data-bs-parent="#sidebar-nav">

        @can('proprio-house-chambre-locataire')
          <li>
            <a class="{{ set_active(request()->is('gerer-statistique-list')) }}" href="{{ route('get_statistiqueView') }}">
              <i class="bi bi-circle"></i><span title="Propriétaire / Maison / Chambre / Locataire">P/M/C/L</span>
            </a>
          </li>
        @endcan

        @can('financier')
          <li>
            <a class="{{ set_active(request()->is('finance')) }}" href="{{ route('getFinance') }}">
              <i class="bi bi-gear"></i>
              <span>Finance</span>
            </a>
          </li>
        @endcan

        @can('ancien-recu')
          <li>
            <a class="{{ set_active(request()->is('statistique-recu')) }}" href=" {{ route('getRecu') }}">
              <i class="bi bi-gear"></i>
              <span>Réçu</span>
            </a>
          </li>
        @endcan

        @can('gestion-sta-dossier')
          <li>
            <a class="{{ set_active(request()->is('statistique-dossier')) }}" href=" {{ route('getDossier') }}">
              <i class="bi bi-gear"></i>
              <span>Gestion dossier</span>
            </a>
          </li>
        @endcan
        </ul>
      </li>
    @endcan

    @can('historique')
    <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('historique')) }}" href="{{ route('pub_displaying') }}">
          <i class="ri-chat-history-fill"></i>
          <span>Gestion publicité</span>
        </a>
    </li>
    @endcan



      
    

    @can('historique')
    <li class="nav-item">
        <a class="nav-link {{ set_collapsed(request()->is('historique')) }}" href="{{ route('historique') }}">
          <i class="ri-chat-history-fill"></i>
          <span>Historique</span>
        </a>
    </li>
    @endcan


      <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('logout') }}"
              onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
                <i class="bi bi-box-arrow-right"></i>
                <span>Quitter</span>
          </a>
      </li>

    </ul>

  </aside>




                          