 <!-- ======= Featured Services Section ======= -->
 <section id="featured-services" class="featured-services">
    <div class="container" data-aos="fade-up">

      <div class="row">

      @foreach($Sections as $section)
          <div class="col-md-6 col-lg-4 d-flex align-items-stretch mb-5 mb-lg-0">
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
