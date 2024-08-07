<section id="services" class="services">
    <div class="container" data-aos="fade-up">

      <div class="section-title">
        <h2>Services</h2>
        <h3>Vérifiez nos <span> Services</span></h3>
        <p class="fst-italic">L'université de muanda possède en son sein les départements que voici :</p>
      </div>

      <div class="row">
        @foreach ($Departements as $depart)
            <div class="col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box">
                <div class="icon"><i class="bx bx-arch"></i></div>
                <h4><a href="">{{ $depart->lib }}</a></h4>
                <p class="text-secondary fst-italic">@if($depart->description ){{ $depart->description}}@else{{ "il n'y a pas une description associée au présent département" }}@endif </p>
            </div>
            </div>

        @endforeach




      </div>

    </div>
  </section>
