<nav id="navbar" class="navbar">
    <ul>
      <li><a class="nav-link scrollto active" href="#hero">Accueil</a></li>
      <li><a class="nav-link scrollto " href="#testimonials">Actualités</a></li>
      <li><a class="nav-link scrollto" href="#about">Apropos</a></li>
      <li><a class="nav-link scrollto" href="#featured-services">Sections</a></li>
      @if(!Auth()->user())
        <li><a class="nav-link scrollto" title="Créez votre compte" href="#contact">S'identifier</a></li>


        <li><a class="nav-link scrollto" href="admin/login">Connexion</a></li>
      @endif

      @if(Auth()->user())
          @if(Auth()->user()->hasRole('Etudiant'))
            <li class="d-md-none" ><a class="nav-link scrollto" href="/admin"> Mon Profil</a></li>
          @else
            <li class="d-md-none" ><a class="nav-link scrollto" href="/admin"> Mon Profil</a></li>

          @endif
      @endif
    </ul>
    <i class="bi bi-list mobile-nav-toggle"></i>
  </nav>
