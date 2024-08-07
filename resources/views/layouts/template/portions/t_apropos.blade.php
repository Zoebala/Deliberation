<section id="about" class="about section-bg">
    <div class="container" data-aos="fade-up">

      <div class="section-title">
        <h2>Apropos</h2>
        <h3>Découvrez plus <span>Apropos de nous</span></h3>
        <p>Voici de manière concise un bref resumé  relatif à l'université de Muanda.</p>
      </div>

      <div class="row">
        <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
          <img src="{{ 'images/muanda2.jpg' }}"  class="img-fluid rounded" alt="université de muanda" width="100%">
        </div>
        <div class="col-lg-6 pt-4 pt-lg-0 content d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="100">
          <h3>Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h3>
          <p class="fst-italic">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
            magna aliqua.
          </p>
          <ul>
            <li>
              <i class="bx bx-store-alt"></i>
              <div>
                <h5>Ullamco laboris nisi ut aliquip consequat</h5>
                <p>Magni facilis facilis repellendus cum excepturi quaerat praesentium libre trade</p>
              </div>
            </li>
            <li>
              <i class="bx bx-images"></i>
              <div>
                <h5>Magnam soluta odio exercitationem reprehenderi</h5>
                <p>Quo totam dolorum at pariatur aut distinctio dolorum laudantium illo direna pasata redi</p>
              </div>
            </li>
          </ul>
          <p>
            Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
            culpa qui officia deserunt mollit anim id est laborum
          </p>
        </div>
      </div>

    </div>
</section>
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
