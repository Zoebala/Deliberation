@if(!Auth()->user())
 <section id="contact" class="contact">
    <div class="container" data-aos="fade-up">

      <div class="section-title">
        <h2><i class="bi bi-plus-circle"></i> Identification</h2>
        <h3><span>Indentifiez vous ici</span></h3>
        <p>Veuillez renseigner ici vos identifiants de connexion</p>
      </div>

      <div class="row" data-aos="fade-up" data-aos-delay="100">
        <div class="col-lg-6">
          <div class="info-box mb-4">
            <i class="bx bx-user"></i>
            <h3>Votre Nom</h3>
            <p>Ex: John Dupon</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="info-box  mb-4">
            <i class="bx bx-envelope"></i>
            <h3>Votre adresse mail</h3>
            <p>Ex: adresse@example.com</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="info-box  mb-4">
            <i class="bx bx-fingerprint"></i>
            <h3>Votre mot de passe</h3>
            <p>xxxxxxxx</p>
          </div>
        </div>

      </div>

      <div class="row" data-aos="fade-up" data-aos-delay="100">

        <div class="col-lg-6 ">
          <iframe class="mb-4 mb-lg-0" src="{{ 'images/identification.png' }}" frameborder="0" style="border:0; width: 100%; height: 100%;" allowfullscreen></iframe>
        </div>

        @livewire("identification")

      </div>

    </div>
  </section>
@endif
