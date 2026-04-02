<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
      <a href="{{ route('home') }}" class="app-brand-link">
        <span class="app-brand-logo demo">
          <svg
            width="25"
            viewBox="0 0 25 42"
            version="1.1"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
          >
            <defs>
              <path
                d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                id="path-1"
              ></path>
              <path
                d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                id="path-3"
              ></path>
              <path
                d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                id="path-4"
              ></path>
              <path
                d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                id="path-5"
              ></path>
            </defs>
            <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
              <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                <g id="Icon" transform="translate(27.000000, 15.000000)">
                  <g id="Mask" transform="translate(0.000000, 8.000000)">
                    <mask id="mask-2" fill="white">
                      <use xlink:href="#path-1"></use>
                    </mask>
                    <use fill="#696cff" xlink:href="#path-1"></use>
                    <g id="Path-3" mask="url(#mask-2)">
                      <use fill="#696cff" xlink:href="#path-3"></use>
                      <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                    </g>
                    <g id="Path-4" mask="url(#mask-2)">
                      <use fill="#696cff" xlink:href="#path-4"></use>
                      <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                    </g>
                  </g>
                  <g
                    id="Triangle"
                    transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) "
                  >
                    <use fill="#696cff" xlink:href="#path-5"></use>
                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                  </g>
                </g>
              </g>
            </g>
          </svg>
        </span>
        <span class="app-brand-text demo menu-text fw-bolder ms-2">Lokativ</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
        <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">

      <!-- Accueil -->
      <li class="menu-item {{ request()->routeIs('home') ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-smile"></i>
          <div data-i18n="Accueil">Accueil</div>
        </a>
      </li>

      @can('parametrage')
        <li class="menu-item {{ request()->routeIs('parametrage') ? 'active' : '' }}">
          <a href="{{ route('parametrage') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-cog"></i>
            <div data-i18n="Parametrage">Parametrage</div>
          </a>
        </li>
      @endcan

      <!-- Gestion utilisateur -->
      @can('gestion-role-utilisateur')
        <li class="menu-item {{ request()->is('roles*') || request()->is('gerer-user*') || request()->is('password-off-line') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-user-circle"></i>
            <div data-i18n="Gestion utilisateur">Gestion utilisateur</div>
          </a>
         
          <ul class="menu-sub">
            @can('gestion-role')
              <li class="menu-item {{ request()->is('roles*') ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}" class="menu-link" data-spa-prefix="/roles">
                  <div data-i18n="Fonction">Fonction</div>
                </a>
              </li>
            @endcan

            @can('gestion-utilisateur')
              <li class="menu-item {{ request()->is('gerer-user*') ? 'active' : '' }}">
                <a href="{{ route('getUserView') }}" class="menu-link" data-spa-prefix="/gerer-user">
                  <div data-i18n="Utilisateur">Utilisateur</div>
                </a>
              </li>
            @endcan
          </ul>
        </li>
      @endcan

      <!-- Super Admin -->
      @can('config-paiement')
        @php $nbContactNonLus = \App\ContactMessage::countUnread(); @endphp
        <li class="menu-item {{ request()->routeIs('super_admin.config_paiement') || request()->routeIs('getViewCompte') || request()->routeIs('super_admin.config_plans') || request()->routeIs('super_admin.contact_messages') || request()->routeIs('super_admin.messaging_rates') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-shield-quarter"></i>
            <div data-i18n="Super Admin">Super Admin</div>
            @if($nbContactNonLus > 0)
              <span class="badge rounded-pill bg-danger ms-auto">{{ $nbContactNonLus }}</span>
            @endif
          </a>
          <ul class="menu-sub">
            <li class="menu-item {{ request()->routeIs('super_admin.contact_messages') ? 'active' : '' }}">
              <a href="{{ route('super_admin.contact_messages') }}" class="menu-link d-flex align-items-center">
                <i class="bx bx-envelope me-2" style="font-size:1rem;"></i>
                <div>Messages de contact</div>
                @if($nbContactNonLus > 0)
                  <span class="badge rounded-pill bg-danger ms-auto">{{ $nbContactNonLus }}</span>
                @endif
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('super_admin.config_paiement') ? 'active' : '' }}">
              <a href="{{ route('super_admin.config_paiement') }}" class="menu-link">
                <div data-i18n="Prestataire de paiement">Prestataire de paiement</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('super_admin.config_plans') ? 'active' : '' }}">
              <a href="{{ route('super_admin.config_plans') }}" class="menu-link">
                <div data-i18n="Plans abonnement">Plans d'abonnement</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('super_admin.messaging_rates') ? 'active' : '' }}">
              <a href="{{ route('super_admin.messaging_rates') }}" class="menu-link">
                <div data-i18n="Tarifs SMS/WA">Tarifs SMS &amp; WhatsApp</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('getViewCompte') ? 'active' : '' }}">
              <a href="{{ route('getViewCompte') }}" class="menu-link">
                <div data-i18n="Gestion entreprise">Gestion entreprise</div>
              </a>
            </li>
          </ul>
        </li>
      @endcan

      @can('gestion-proprietaire')
        <li class="menu-item {{ request()->is('gerer-proprietaire*') || request()->routeIs('proprietaires.index') ? 'active' : '' }}">
          <a href="{{ route('proprietaires.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-pin"></i>
            <div data-i18n="Gestion proprietaire">Gestion proprietaire</div>
          </a>
        </li>
      @endcan

      @can('gestion-maison')
        <li class="menu-item {{ request()->is('gerer-maison*') ? 'active' : '' }}">
          <a href="{{ route('get_maisonView') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-home-alt"></i>
            <div data-i18n="Gestion des maisons">Gestion des maisons</div>
          </a>
        </li>
      @endcan

      @can('gestion-chambre')
        <li class="menu-item {{ request()->is('gerer-chambre*') ? 'active' : '' }}">
          <a href="{{ route('get_chambreView') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-door-open"></i>
            <div data-i18n="Gestion des chambres">Gestion des chambres</div>
          </a>
        </li>
      @endcan

      @can('gestion-locataire') 
        <li class="menu-item {{ request()->is('gerer-locataire*') ? 'active' : '' }}">
          <a href="{{ route('get_locataireView') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-user-check"></i>
            <div data-i18n="Gestion des locataires">Gestion des locataires</div>
          </a>
        </li>
      @endcan

      @can('gestion-paiement')
        <li class="menu-item {{ request()->is('gerer-facture*') ? 'active' : '' }}">
          <a href="{{ route('get_factureView') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-money"></i>
            <div data-i18n="Gestion des loyers">Gestion des loyers</div>
          </a>
        </li>
      @endcan

      @can('envoi-document')
        <li class="menu-item {{ request()->is('envoi-document*') ? 'active' : '' }}">
          <a href="{{ route('envoi_document.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-message-dots"></i>
            <div data-i18n="Communications">Communications</div>
          </a>
        </li>
      @endcan

      @can('gestion-dossier')
        <li class="menu-item {{ request()->is('client') || request()->is('parcelle') ? 'active open' : '' }}">
          <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons bx bx-folder-open"></i>
            <div data-i18n="Gestion des dossiers">Gestion des besoins & annonces</div>
          </a>
          <ul class="menu-sub">
            @can('dossier-client')
              <li class="menu-item {{ request()->is('client') ? 'active' : '' }}">
                <a href="{{ route('getViewClient') }}" class="menu-link">
                  <div data-i18n="Dossier client">Besoins client</div>
                </a>
              </li>
            @endcan
            @can('dossier-parcelle')
              <li class="menu-item {{ request()->is('parcelle') ? 'active' : '' }}">
                <a href="{{ route('getViewParcelle') }}" class="menu-link">
                  <div data-i18n="Dossier parcelle">Annonces des biens</div>
                </a>
              </li>
            @endcan
          </ul>
        </li>
      @endcan

      @can('gestion-statistique')
      <li class="menu-item {{ request()->is('gerer-statistique*') || request()->is('finance') || request()->is('statistique-recu') || request()->is('statistique-dossier') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
          <div data-i18n="Gestion reporting">Gestion reporting</div>
        </a>
        <ul class="menu-sub">
          @can('proprio-house-chambre-locataire')
            <li class="menu-item {{ request()->is('gerer-statistique*') ? 'active' : '' }}">
              <a href="{{ route('get_statistiqueView') }}" class="menu-link">
                <div data-i18n="P/M/C/L">P/M/C/L</div>
              </a>
            </li>
          @endcan

          @can('financier')
            <li class="menu-item {{ request()->is('finance') ? 'active' : '' }}">
              <a href="{{ route('getFinance') }}" class="menu-link">
                <div data-i18n="Finance">Finance</div>
              </a>
            </li>
          @endcan

          @can('ancien-recu')
            <li class="menu-item {{ request()->is('statistique-recu') ? 'active' : '' }}">
              <a href="{{ route('getRecu') }}" class="menu-link">
                <div data-i18n="Recu">Recu</div>
              </a>
            </li>
          @endcan

          @can('gestion-sta-dossier')
            <li class="menu-item {{ request()->is('statistique-dossier') ? 'active' : '' }}">
              <a href="{{ route('getDossier') }}" class="menu-link">
                <div data-i18n="Gestion dossier">Gestion des besoins & annonces</div>
              </a>
            </li>
          @endcan
        </ul>
      </li>
      @endcan

      <!-- Gestion publicite -->
      @can('gestion-publicite')
        <li class="menu-item {{ request()->is('publicite*') ? 'active' : '' }}">
          <a href="{{ route('pub_displaying') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-speaker"></i>
            <div data-i18n="Gestion publicite">Gestion publicité</div>
          </a>
        </li>
      @endcan

      @can('historique')
      <li class="menu-item {{ request()->is('historique') ? 'active' : '' }}">
        <a href="{{ route('historique') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-history"></i>
          <div data-i18n="Log connexion">Log connexion</div>
        </a>
      </li>
      @endcan

      <!-- Mon Abonnement -->
      @can('gestion-abonnement')
        <li class="menu-item {{ request()->routeIs('plans.index') ? 'active' : '' }}">
          <a href="{{ route('plans.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-credit-card"></i>
            <div data-i18n="Mon Abonnement">Mon Abonnement</div>
          </a>
        </li>
      @endcan
      

      <!-- Mon Profil -->
      <li class="menu-item {{ request()->routeIs('profileView') ? 'active' : '' }}">
        <a href="{{ route('profileView') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Mon Profil">Mon Profil</div>
        </a>
      </li>

      <!-- Deconnexion -->
      <li class="menu-item">
        <a class="menu-link" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
              <i class="menu-icon tf-icons bx bx-power-off text-danger"></i>
              <span class="text-danger">Quitter</span>
        </a>
      </li>


    </ul>
  </aside>