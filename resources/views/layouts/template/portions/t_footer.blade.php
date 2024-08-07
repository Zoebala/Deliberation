<footer id="footer">



    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>IST Mbanza-Ngungu<span>.</span></h3>
            <p>
              A108 Adam Street <br>
              New York, NY 535022<br>
              Ville de Muanda <br><br>
              <strong>Phone:</strong> +1 5589 55488 55<br>
              <strong>Email:</strong> istmbanzangungu@example.com<br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Liens importants</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#hero">Accueil</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#about">Apropos</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#services">Services</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#testimonials">Actualités</a></li>
              {{-- <li><i class="bx bx-chevron-right"></i> <a href="#">Privacy policy</a></li> --}}
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Nos Sections</h4>
            <ul>

                @forelse ($Sections as $Section)
                    <li><i class="bx bx-chevron-right"></i> <a href="#">{{ $Section->lib }}</a></li>

                @empty
                    <li>aucun département identifié pour le moment</li>
                @endforelse


            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Nos Réseaux Sociaux</h4>
            <p>Nos différentes pages sur les réseaux sociaux</p>
            <div class="social-links mt-3">
              <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
              <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container py-4">
      <div class="copyright">
        &copy; Copyright <strong><span>Institut Supérieur Technique</span></strong>. Tous droits reservés.
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/bizland-bootstrap-business-template/ -->
        Designed by <a href="#">BZ</a>
      </div>
    </div>
  </footer>
