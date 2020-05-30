<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>HospitALL</title>

    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <!-- sass file compilation -->

    <link rel="stylesheet" href="{{asset('app/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('app/css/custom.css')}}">

    <link rel="stylesheet" href="css/leaflet.css"/>
    <script src="js/leaflet.js"></script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
      integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"> </script>

    <script src="js/bootstrap.min.js"> </script>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>

    <script src="js/leaflet.markercluster.js"></script>
    <link rel="stylesheet" href="css/MarkerCluster.css">
    <link rel="stylesheet" href="css/MarkerCluster.Default.css">

    <script src="js/bootstrap.bundle.min.js"> </script>


    <!--[if lt IE 9]>
        <script src="assets/bower_components/html5shiv/dist/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body class="">
    <aside class="ct-sidebar">
        <div class="ct-sidebar__brand">
            <a href="login.php">
                <!-- <img src="" alt=""> -->
            </a>
        </div>

        <div class="ct-sidebar__content">
            <div class="ct-activity__search_wrapper scrollFixed">
                <div class="ct-activity__search_group">
                    <span class="search__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input class="form-control ct-activity__search_input" type="text" placeholder="Search your hospital...">
                </div>
            </div>

            <h1 class="mt-5">HospitALL</h1>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
        </div>

        <p class="copyright">&copy; HospitALL. All Rights Reserved 2020.</p>
    </aside>
    <main class="ct-main__content map__container">
      @yield('page-body')
      <footer>
          <p class="copyright">&copy; HospitALL. All Rights Reserved 2020.</p>
      </footer>

  </main>
  
  @include('modals.onboarding')
  
  <script src="{{asset('app/js/vendor/jquery-3.4.1.min.js')}}"></script>
  <script src="{{asset('app/js/vendor/popper.min.js')}}"></script>
  <script src="{{asset('app/js/vendor/bootstrap.min.js')}}"></script>
  <script src="{{asset('app/js/vendor/jquery.mask.min.js')}}"></script>
  <script src="{{asset('app/js/vendor/jquery.sticky.js')}}"></script>
  <script src="{{asset('app/js/vendor/remodal.js')}}"></script>

  <script src="{{asset('app/js/plugins.js')}}"></script>
  <script src="{{asset('app/js/app.js')}}"></script>

  <script src="{{asset('app/js/custom.js')}}"></script>

  @stack('scripts')
</body>
</html>
