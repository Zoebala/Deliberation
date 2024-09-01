<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config("app.name") }}</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ 'images/logoist.jpeg' }}" rel="icon">
  <link href="template/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  {{-- <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet"> --}}

  <!-- Vendor CSS Files -->
  <link href="template/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="template/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="template/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="template/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="template/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="template/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="template/assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: BizLand
  * Template URL: https://bootstrapmade.com/bizland-bootstrap-business-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Top Bar ======= -->
<section id="topbar" class="d-flex align-items-center">
    <div class="container d-flex justify-content-center justify-content-md-between">
      <div class="contact-info d-flex align-items-center">
        <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">istmbanza@example.com</a></i>
        {{-- <i class="bi bi-phone d-flex align-items-center ms-4"><span>+1 5589 55488 55</span></i> --}}
      </div>
      <div class="social-links d-none d-md-flex align-items-center">
        @auth
            <a href="#" class="twitter"><i class="bi bi-twitter"></i> Connecté en tant que </a>

            @if($Etudiant==null)
                 @if(Auth()->user()->hasRole("Etudiant"))
                    <a href="{{ '/admin' }}" class="img-fluid"><img src="{{ 'images/avatar.png' }}" alt="avatar" width="20" class="img-fluid rounded-circle">  <span class="ms-2 mt-1">{{ $User->name }}</span></a>
                @else
       
                    <a href="{{ '/admin' }}" class="img-fluid" title="Mon Profil"><img src="@if($User->profile ){{ 'storage/'.$User->profile}}@else{{ 'images/avatar.png' }}@endif" alt="avatar" width="30"  class="img-fluid rounded-circle">  <span class="ms-2 mt-1">{{ $Etudiant->nom ?? $User->name}}</span></a>

                @endif
            @else

                <a href="{{ '/admin' }}" class="img-fluid" title="Mon Profil"><img src="@if($User->profile ){{ 'storage/'.$User->profile}}@else{{ 'images/avatar.png' }}@endif" alt="avatar" width="30"  class="img-fluid rounded-circle">  <span class="ms-2 mt-1">{{ $Etudiant->nom ?? $User->name}}</span></a>


            @endif
        @endauth

      </div>
    </div>
</section>
