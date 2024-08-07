<section id="hero" class="d-flex align-items-center">
    <div class="container" data-aos="zoom-out" data-aos-delay="100">
      <h1 class="text-center"><span class="typed-words"></span></h1>
      <h2 class="text-center">Nous sommes à votre entière disposition</h2>
      <div class="d-flex offset-5">
        <a href="#about" class="btn-get-started scrollto fst-italic"><i class="bi bi-play-circle"></i> Explorez notre site</a>

      </div>
    </div>
  </section>
<!-- End Hero -->

<main id="main">

    <!-- ======= Featured Services Section ======= -->
    <section id="featured-services" class="featured-services">
      <div class="container" data-aos="fade-up">

        <div class="row">

        @foreach($Sections as $section)
            <div class="col-md-6 col-lg-3 d-flex align-items-stretch mb-5 mb-lg-0">
                <div class="icon-box" data-aos="fade-up" data-aos-delay="400">
                <div class="icon"><i class="bx bx-world"></i></div>
                <h4 class="title"><a href="">{{ $section->lib }}</a></h4>
                <p class="description fst-italic">@if($section->description ){{ $section->description}}@else{{ "il n'y a pas une description associée à la présente section" }}@endif </p>
                </div>
            </div>
        @endforeach
        </div>

      </div>
</section>
