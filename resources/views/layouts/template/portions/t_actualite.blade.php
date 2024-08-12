<section id="testimonials" class="testimonials">
    <div class="container" data-aos="zoom-in">
        <div class="section-title">
            <h2>Nos Nouvelles</h2>

          </div>
      <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
        <div class="swiper-wrapper">
            @forelse ($Actualites as $Actualite)

                <div class="swiper-slide">
                    <div class="testimonial-item">

                    <img src="{{asset('storage/'.$Actualite->photo)}}" width="130px;" height="130px;" class="rounded-circle"  alt="logo">
                    <h3>{{ $Actualite->objet }}</h3>
                    <h4>Publié le  {{ $Actualite->created_at->format("d/m/Y à H:i:s")  }} </h4>
                    <p>
                        <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                            {{ $Actualite->description }}
                        <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                    </p>
                    </div>
                </div>
            @empty
                <p>
                    <i class="bx bxs-quote-alt-left quote-icon-left"></i>
                        Aucune actualité publiée pour le moment!
                    <i class="bx bxs-quote-alt-right quote-icon-right"></i>
                </p>

            @endforelse
          <!-- End testimonial item -->

        </div>
        <div class="swiper-pagination"></div>
      </div>

    </div>
  </section>
