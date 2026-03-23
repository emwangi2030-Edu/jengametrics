<!DOCTYPE html>
<html lang="en-US" dir="ltr" data-navigation-type="default" data-navbar-horizontal-shape="default">

<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title> A Financial Management Solution for Your Business</title>

    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    
    <link rel="apple-touch-icon" sizes="180x180" href="{{ favicon_url() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ favicon_url() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ favicon_url() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ favicon_url() }}">
    <link rel="manifest" href="{{ asset('assets/metrics') }}/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="{{ asset('assets/metrics') }}/assets/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('assets/metrics') }}/vendors/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/assets/js/config.js"></script>

    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link href="{{ asset('assets/metrics') }}/vendors/mapbox-gl/mapbox-gl.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('assets/metrics') }}/vendors/simplebar/simplebar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/b2b') }}/{{ asset('assets/b2b') }}/../unicons.iconscout.com/release/v4.0.8/css/line.css">
    <link href="{{ asset('assets/metrics') }}/assets/css/theme-rtl.min.css" type="text/css" rel="stylesheet" id="style-rtl">
    <link href="{{ asset('assets/metrics') }}/assets/css/theme.min.css" type="text/css" rel="stylesheet" id="style-default">
    <link href="{{ asset('assets/metrics') }}/assets/css/user-rtl.min.css" type="text/css" rel="stylesheet" id="user-style-rtl">
    <link href="{{ asset('assets/metrics') }}/assets/css/user.min.css" type="text/css" rel="stylesheet" id="user-style-default">
    <script>
      var phoenixIsRTL = window.config.config.phoenixIsRTL;
      if (phoenixIsRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
      } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
      }
    </script>

    <style>
/* For all links with class 'text-primary' */
.text-primary {
    color: #027333 !important;
}

/* For links within specific containers */
.support-chat-container .text-primary {
    color: #027333 !important;
}

/* For other possible link styles */
a.text-primary {
    color: #027333 !important;
}

a.text-primary:hover,
a.text-primary:focus {
    color: #025a1f !important; /* Optional: different color on hover/focus */
}


a.text-primary {
    color: #027333 !important;
}

a.text-primary:hover,
a.text-primary:focus {
    color: #025a1f !important; /* Optional: different color on hover/focus */
}

.btn-link {
  color: #027333; 
}


    

.jm-welcome-body {
  --phoenix-scroll-margin-top: 1.2rem;
}

.jm-bg-contain {
  background-size: contain;
}

.jm-bg-b2b-23 {
  background-image: url("{{ asset('assets/b2b/assets/img/bg/bg-23.png') }}");
}

.jm-bg-left-15 {
  background-image: url("{{ asset('assets/metrics') }}/assets/img/bg/bg-left-15.png");
  background-position: left;
  background-size: auto;
}

.jm-bg-right-15 {
  background-image: url("{{ asset('assets/metrics') }}/assets/img/bg/bg-right-15.png");
  background-position: right;
  background-size: auto;
}

.jm-bg-18-right {
  background-image: url("{{ asset('assets/metrics') }}/assets/img/bg/bg-18.png");
  background-position: right;
  background-size: auto;
}

.jm-bg-19 {
  background-image: url("{{ asset('assets/metrics') }}/assets/img/bg/bg-19.png");
  background-size: auto;
}

.jm-bg-right-20 {
  background-image: url("{{ asset('assets/metrics') }}/assets/img/bg/bg-right-20.png");
  background-position: right;
  background-size: auto;
}

.jm-bg-left-20 {
  background-image: url("{{ asset('assets/metrics') }}/assets/img/bg/bg-left-20.png");
  background-position: left;
  background-size: auto;
}

.jm-border-opacity-20 {
  --phoenix-border-opacity: .2;
}

</style>
  </head>

  <body class="jm-welcome-body">
    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <div class="bg-body-emphasis sticky-top" data-navbar-shadow-on-scroll="data-navbar-shadow-on-scroll">
        <nav class="navbar navbar-expand-lg container-small px-3 px-lg-7 px-xxl-3"><a class="navbar-brand flex-1 flex-lg-grow-0" href="{{ url('/') }}">
            <div class="d-flex align-items-center"><img src="{{ favicon_url() }}" alt="phoenix" width="27" />
              <h5 class="logo-text ms-2 text-primary">JengaMetrics</h5>
            </div>
          </a>
          <div class="d-lg-none">
            <div class="theme-control-toggle fa-icon-wait px-2"><input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggleSm" /><label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggleSm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Switch theme" class="jm-theme-toggle-btn"><span class="icon" data-feather="moon"></span></label><label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggleSm" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Switch theme" class="jm-theme-toggle-btn"><span class="icon" data-feather="sun"></span></label></div>
          </div><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="border-bottom border-translucent border-bottom-lg-0 mb-2">
              <div class="search-box d-inline d-lg-none">
                <form class="position-relative"><input class="form-control search-input search rounded-pill my-4" type="search" placeholder="Search" aria-label="Search" />
                  <span class="fas fa-search search-box-icon"></span>
                </form>
              </div>
            </div>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">


   
            </ul>
            <div class="d-grid d-lg-flex align-items-center">
            
             <a class="nav-link lh-1 py-0 fs-9 fw-bold py-3" href="#feature">Features</a>
            <a class="btn btn-link text-body order-1 order-lg-0 ps-4 me-lg-2" href="{{ route('login') }}">Sign in</a>
            <a class="btn btn-phoenix-success order-0" href="{{ route('register') }}"><span class="fw-bold">Sign up</span></a>
            </div>
          </div>
        </nav>
      </div>



      <section class="bg-body-emphasis pb-8" id="home">
  <div class="container-small hero-header-container px-lg-7 px-xxl-3">
    <div class="row align-items-center">
      <!-- Image Section -->
      <div class="col-12 col-lg-auto order-0 order-md-1 text-end order-1">
        <!-- Mobile Image -->
        <div class="position-relative p-5 p-md-7 d-lg-none">
          <div class="bg-holder jm-bg-b2b-23 jm-bg-contain"></div>
          <div class="position-relative">
            <img class="w-100 shadow-lg d-dark-none rounded-2" src="{{ asset('assets/b2b/assets/img/bg/bg-28.webp') }}" alt="hero-header" />
            <img class="w-100 shadow-lg d-light-none rounded-2" src="{{ asset('assets/b2b/assets/img/bg/bg-30.png') }}" alt="hero-header" />
          </div>
        </div>
        <!-- Desktop Image -->
        <div class="hero-image-container position-absolute top-0 bottom-0 end-0 d-none d-lg-block">
          <div class="position-relative h-100 w-100">
            <div class="position-absolute h-100 top-0 d-flex align-items-center end-0 hero-image-container-bg">
              <img class="pt-7 pt-md-0 w-100" src="{{ asset('assets/b2b/assets/img/bg/bg-1-2.png') }}" alt="hero-header" />
            </div>
            <div class="position-absolute h-100 top-0 d-flex align-items-center end-0">
              <img class="pt-7 pt-md-0 w-100 shadow-lg d-dark-none rounded-2" src="{{ asset('assets/b2b/assets/img/bg/bg-28.webp') }}" alt="hero-header" />
              <img class="pt-7 pt-md-0 w-100 shadow-lg d-light-none rounded-2" src="{{ asset('assets/b2b/assets/img/bg/bg-29.png') }}" alt="hero-header" />
            </div>
          </div>
        </div>
      </div>
      <!-- Text Section -->
      <div class="col-12 col-lg-6 text-lg-start text-center pt-8 pb-6 order-0 position-relative">
        <h1 class="fs-3 fs-lg-2 fs-md-1 fs-lg-2 fs-xl-1 fw-black mb-4">
          <span class="text-primary me-3">A Financial Management</span><br /> Solution for Your Business
        </h1>
        <p class="mb-5">
          A comprehensive, modern, and elegant financial management solution tailored to meet the needs of your business. Sign up now or check out the demo below.
        </p>
        <a class="btn btn-lg btn-success rounded-pill me-3" href="{{ route('register') }}" role="button">Sign up</a>
        <a class="btn btn-link me-2 fs-8 p-0" href="{{ route('login') }}" role="button">
          Sign in<span class="fa-solid fa-angle-right ms-2 fs-9"></span>
        </a>
      </div>
    </div>
  </div>
</section>




<!-- ============================================-->
<!-- <section> begin ============================-->
<section class="pt-15 pb-0" id="feature">
  <div class="container-small px-lg-7 px-xxl-3">
    <div class="position-relative z-2">
      <div class="row">
        <div class="col-lg-6 text-center text-lg-start pe-xxl-3">
          <h4 class="text-primary fw-bolder mb-4">Features</h4>
          <h2 class="mb-3 text-body-emphasis lh-base">Advanced Construction Management</h2>
          <p class="mb-5">JengaMetrics provides a comprehensive solution for the construction industry, allowing you to efficiently manage projects, costs, materials, and communication all in one platform.</p>
          <a class="btn btn-lg btn-outline-success rounded-pill me-2" href="#!" role="button">Discover More<i class="fa-solid fa-angle-right ms-2"></i></a>
        </div>
        <div class="col-sm-6 col-lg-3 mt-7 text-center text-lg-start">
          <div class="h-100 d-flex flex-column justify-content-between">
            <div class="border-start-lg border-translucent border-dashed ps-4">
              <img class="mb-4" src="{{ asset('assets/metrics') }}/assets/img/icons/illustrations/bolt.png" width="48" height="48" alt="" />
              <div>
                <h5 class="fw-bolder mb-2">Streamlined Processes</h5>
                <p class="fw-semibold lh-sm">Enhance your project efficiency with tools designed to streamline every stage of your construction process.</p>
              </div>
              <div><a class="btn btn-link me-2 p-0 fs-9" href="#!" role="button">Learn More<span class="fa-solid fa-angle-right ms-2"></span></a></div>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-lg-3 mt-7 text-center text-lg-start">
          <div class="h-100 d-flex flex-column">
            <div class="border-start-lg border-translucent border-dashed ps-4">
              <img class="mb-4" src="{{ asset('assets/metrics') }}/assets/img/icons/illustrations/pie.png" width="48" height="48" alt="" />
              <div>
                <h5 class="fw-bolder mb-2">Holistic Project Management</h5>
                <p class="fw-semibold lh-sm">Manage all aspects of your construction projects from cost estimation to completion in one platform.</p>
              </div>
              <div><a class="btn btn-link me-2 p-0 fs-9" href="#!" role="button">Learn More<i class="fa-solid fa-angle-right ms-2"></i></a></div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-12 align-items-center justify-content-between text-center text-lg-start mb-6 mb-lg-0">
        <div class="col-lg-5">
          <img class="feature-image img-fluid mb-9 mb-lg-0 d-dark-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/22_2.png" alt="" />
          <img class="feature-image img-fluid mb-9 mb-lg-0 d-light-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/dark_22.png" alt="" />
        </div>
        <div class="col-lg-6">
          <h6 class="text-primary mb-2 ls-2">REAL-TIME MONITORING</h6>
          <h3 class="fw-bolder mb-3">Instant Updates</h3>
          <p class="mb-4 px-md-7 px-lg-0">Keep track of every detail with real-time updates on your project's progress and costs.</p>
          <a class="btn btn-link me-2 p-0 fs-9" href="#!" role="button">Learn More<i class="fa-solid fa-angle-right ms-2"></i></a>
        </div>
      </div>
      <div class="row mt-2 align-items-center justify-content-between text-center text-lg-start mb-6 mb-lg-0">
        <div class="col-lg-5 order-0 order-lg-1">
          <img class="feature-image img-fluid mb-9 mb-lg-0 d-dark-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/23_2.png" height="394" alt="" />
          <img class="feature-image img-fluid mb-9 mb-lg-0 d-light-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/dark_23.png" height="394" alt="" />
        </div>
        <div class="col-lg-6">
          <h6 class="text-primary mb-2 ls-2">PERFORMANCE TRACKING</h6>
          <h3 class="fw-bolder mb-3">Growth Monitoring</h3>
          <p class="mb-4 px-md-7 px-lg-0">Monitor your project's performance and growth with in-depth analytics and detailed reports.</p>
          <a class="btn btn-link me-2 p-0 fs-9" href="#!" role="button">Learn More<i class="fa-solid fa-angle-right ms-2"></i></a>
        </div>
      </div>
      <div class="row mt-2 align-items-center justify-content-between text-center text-lg-start mb-6 mb-lg-0">
        <div class="col-lg-5">
          <img class="feature-image img-fluid mb-9 mb-lg-0 d-dark-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/24_2.png" height="394" alt="" />
          <img class="feature-image img-fluid mb-9 mb-lg-0 d-light-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/dark_24.png" height="394" alt="" />
        </div>
        <div class="col-lg-6 text-center text-lg-start">
          <h6 class="text-primary mb-2 ls-2">DETAILED ANALYSIS</h6>
          <h3 class="fw-bolder mb-3">Comprehensive Reporting</h3>
          <p class="mb-4 px-md-7 px-lg-0">Generate in-depth reports to gain valuable insights into your project’s financial and operational performance.</p>
          <a class="btn btn-link me-2 p-0 fs-9" href="#!" role="button">Learn More<i class="fa-solid fa-angle-right ms-2"></i></a>
        </div>
      </div>
    </div>
  </div><!-- end of .container-->
</section><!-- <section> close ============================-->
<!-- ============================================-->





<section class="bg-body-emphasis pt-lg-0 pt-xl-8">
    <div class="bg-holder d-none d-md-block jm-bg-left-15"></div>
    <!--/.bg-holder-->
    <div class="bg-holder d-none d-md-block jm-bg-right-15"></div>
    <!--/.bg-holder-->
    <div class="container-small position-relative px-lg-7 px-xxl-3">
        <div class="mb-4 text-center text-sm-start">
            <h4 class="text-primary fw-bolder mb-3">Pricing</h4>
            <h2>Choose the best deal for you</h2>
        </div>
        <p class="column-md-2 text-center text-sm-start">
            Entice your customers with our pricing plans. Choose the one that best fits your needs and start managing your business with ease.
        </p>
        <div class="row pt-9 g-3 g-xl-0">
            <!-- Starter Plan -->
            <div class="col-md-6 col-xl-6">
                <div class="card h-100 rounded-end-xl-0 rounded-start">
                    <div class="card-body px-6">
                        <div class="px-5">
                            <div class="text-center pt-5">
                                <img src="{{ asset('assets/metrics') }}/assets/img/icons/illustrations/pie.png" width="48" height="48" alt="" />
                                <h3 class="fw-semibold my-4">Starter Plan</h3>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold text-primary">KES<span class="fw-bolder">0</span><span class="text-body-emphasis fs-7 ms-1 fw-bolder">USD</span></h1>
                                <button class="btn btn-lg mb-6 w-100 btn-outline-success">Get started now</button>
                            </div>
                        </div>
                        <ul class="fa-ul pricing-list">
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Create unlimited estimates, invoices, bills, and bookkeeping records</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Option to accept online payments</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Invoice on-the-go via the fedhatrac app</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Manage cash flow and customers in one dashboard</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Pro Plan -->
            <div class="col-md-6 col-xl-6">
                <div class="card h-100 rounded-top-0 rounded-xl-0 border border-2 border-success mt-5 mt-md-0">
                    <div class="position-absolute d-flex flex-center bg-success-subtle rounded-top py-1 end-0 start-0 badge-pricing">
                        <p class="text-success-dark mb-0">Recommended</p>
                    </div>
                    <div class="card-body px-6">
                        <div class="px-5">
                            <div class="text-center pt-5">
                                <img src="{{ asset('assets/metrics') }}/assets/img/icons/illustrations/bolt.png" width="48" height="48" alt="" />
                                <h3 class="fw-semibold my-4">Pro Plan</h3>
                            </div>
                            <div class="text-center">
                                <h1 class="fw-semibold text-primary">KES<span class="fw-bolder">1500</span><span class="text-body-emphasis fs-7 ms-1 fw-bolder">USD/month</span></h1>
                                <button class="btn btn-lg mb-6 w-100 btn-success">Get started now</button>
                            </div>
                        </div>
                        <ul class="fa-ul pricing-list">
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Everything in Starter, plus:</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Option to accept online payments at a discounted rate</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Auto-import bank transactions</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Auto-merge and categorize bank transactions</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Digitally capture unlimited receipts and track expenses</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="fa-li"><span class="fas fa-check text-primary"></span></span>
                                <span class="text-body-secondary">Automate late payment reminders</span>
                            </li>
                            <li class="mb-4 d-flex align-items-center">
                                <span class="text-body-secondary">And more!</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>









      <!-- ============================================-->
<!-- ============================================-->
<!-- <section> begin ============================-->
<section class="bg-body-emphasis pb-0">
    <div class="container-small px-lg-7 px-xxl-3">
        <div class="row justify-content-center">
            <div class="col-12 text-center">
                <div class="card py-md-9 px-md-13 border-0 z-1 shadow-lg cta-card">
                    <div class="bg-holder jm-bg-18-right"></div>
                    <!--/.bg-holder-->
                    <div class="card-body position-relative">
                        <img class="img-fluid mb-5 d-dark-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/27.png" width="210" alt="..." />
                        <img class="img-fluid mb-5 d-light-none" src="{{ asset('assets/metrics') }}/assets/img/spot-illustrations/dark_27.png" width="210" alt="..." />
                        <div class="d-flex align-items-center fw-bold justify-content-center mb-3">
                            <p class="mb-0">Experience seamless efficiency with our top-notch tools and support.</p>
                        </div>
                        <h1 class="fs-6 fs-sm-4 fs-lg-2 fw-bolder lh-sm mb-3">Do what you love. <span class="text-primary mx-2">Leave the rest to us.</span></h1>
                        <a href="{{ route('register') }}" class="btn btn-success" type="submit">Get started</a>
                        <p>Best support in the world, Only Fedhatrac can ensure</p>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end of .container-->
</section><!-- <section> close ============================-->
<!-- ============================================-->


      <div class="position-relative">
        <div class="bg-holder footer-bg jm-bg-19"></div>
        <!--/.bg-holder-->
        <div class="bg-holder jm-bg-right-20"></div>
        <!--/.bg-holder-->
        <div class="bg-holder jm-bg-left-20"></div>
        <!--/.bg-holder-->
        <div class="position-relative"><svg class="w-100 text-white dark__text-gray-1100" preserveAspectRatio="none" viewBox="0 0 1920 368" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1920 0.44L0 367.74V0H1920V0.44Z" fill="currentColor"></path>
          </svg>

<!-- ============================================-->
<!-- <section> begin ============================-->
<section class="footer-default">
    <div class="container-small px-lg-7 px-xxl-3">
        <div class="row position-relative">
            <div class="col-12 col-sm-12 col-lg-3 mb-4 order-0 order-sm-0">
                <a href="#"><img class="mb-3" src="{{ favicon_url() }}" height="48" alt="" /></a>
                <h3 class="text-white">JengaMetrics</h3>
                <p class="text-white opacity-50">Connecting you with the best. From innovative solutions to exceptional support, we're here to make your experience seamless.</p>
            </div>
            <div class="col-lg-9">
                <div class="row justify-content-between">
                    <!-- Features -->
                    <div class="col-6 col-sm-4 col-lg-3 mb-3">
                        <div class="border-dashed border-start border-primary-light ps-3 jm-border-opacity-20">
                            <h5 class="fw-bolder mb-2 text-light">Features</h5>
                            <ul class="list-unstyled mb-3">
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Accounting software</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Invoicing software</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Fedhatrac app</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Mobile receipts</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Payments</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Fedhatrac Advisors</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Pricing</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Mpesa payments</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Bank payments</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Recurring billing</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Wave -->
                    <div class="col-6 col-sm-4 col-lg-3 mb-3">
                        <div class="border-dashed border-start border-primary-light ps-3 jm-border-opacity-20">
                            <h5 class="fw-bolder mb-2 text-light">Wave</h5>
                            <ul class="list-unstyled mb-3">
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">About Us</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Careers</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Press</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Sitemap</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Helpful Links -->
                    <div class="col-6 col-sm-4 col-lg-3 mb-3">
                        <div class="border-dashed border-start border-primary-light ps-3 jm-border-opacity-20">
                            <h5 class="fw-bolder mb-2 text-light">Helpful Links</h5>
                            <ul class="list-unstyled mb-3">
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Blog</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Help Center</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Invoice templates</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Estimate templates</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Accounting education center</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">QuickBooks alternative</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Freelance Hub</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Small business study</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Support & Tools -->
                    <div class="col-6 col-sm-4 col-lg-3 mb-3">
                        <div class="border-dashed border-start border-primary-light ps-3 jm-border-opacity-20">
                            <h5 class="fw-bolder mb-2 text-light">Support & Tools</h5>
                            <ul class="list-unstyled mb-3">
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">How support works</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">System status</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Accounting calculators</a></li>
                                <li class="mb-1"><a class="text-body-quaternary" href="#!" data-bs-theme="light">Invoicing generators</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- <section> close ============================-->
<!-- ============================================-->


        </div>
      </div>



    </main><!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->



    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="{{ asset('assets/metrics') }}/vendors/popper/popper.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/bootstrap/bootstrap.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/anchorjs/anchor.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/is/is.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/fontawesome/all.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/lodash/lodash.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/list.js/list.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/feather-icons/feather.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/dayjs/dayjs.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/mapbox-gl/mapbox-gl.js"></script>
    <script src="{{ asset('assets/metrics') }}/assets/js/phoenix.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/isotope-packery/packery-mode.pkgd.min.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/bigpicture/BigPicture.js"></script>
    <script src="{{ asset('assets/metrics') }}/vendors/countup/countUp.umd.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbaQGvhe7Af-uOMJz68NWHnO34UjjE7Lo&amp;callback=initMap" async></script>
    <script src="{{ asset('assets/b2b') }}/{{ asset('assets/b2b') }}/../smtpjs.com/v3/smtp.js"></script>
  </body>


</html>