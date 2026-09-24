<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Abbyero Aviation is the best choice for online Aviation school training. Its revolutionary approach makes it top for Private, Instrument & Remote Pilot.">
    <meta property="og:title" content="Abbyero Aviation Aviation School">
    <meta property="og:description" content="Abbyero Aviation is the best choice for online Aviation school training.">
    <meta property="og:image" content="{{ asset('assets/images/index/home-thumbnail.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Abbyero Aviation Aviation School">
    <meta name="twitter:description" content="Online aviation training for Private, Instrument, and Remote Pilot.">
    <meta name="twitter:image" content="{{ asset('assets/images/index/home-thumbnail.png') }}">

    <!-- Preload Key Images -->
    <link rel="preload" as="image" href="{{ asset('assets/images/index/billboard-container-backAviation.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-private-aircraft.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-instrument-aircraft.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-rusty-aircraft.webp') }}" type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-remote-aircraft.webp') }}" type="image/webp">

    <!-- Favicons & Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/fav-icons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/images/fav-icons/gs-gm-favicos/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/images/fav-icons/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/images/fav-icons/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/images/fav-icons/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/images/fav-icons/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/images/fav-icons/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/images/fav-icons/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/fav-icons/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/images/fav-icons/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/fav-icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/images/fav-icons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/fav-icons/favicon-16x16.png') }}">
    <!-- CSS Stylesheets -->
    <link href="{{ asset('common/bootstrap/5.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/aa-branding.v2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.v2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/footer.v2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/index.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style_home.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


    <!-- Plugin Styles -->
    <link href="{{ asset('common/video-js/8.0.4/video-js.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/sweetalert2/11.4.8/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/datatables/media/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/datatables/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/datatables/extensions/Responsive/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
    .accordion-header-btn.collapsed .accordion-toggle-icon {
        transform: rotate(0deg);
        transition: transform 0.3s;
    }
    .accordion-header-btn:not(.collapsed) .accordion-toggle-icon {
        transform: rotate(90deg);
        transition: transform 0.3s;
    }
    </style>


    <title>Instructor | Abbyero Aviation LLC</title>
    <script type="text/javascript">
    var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
    (function () {
        var e = document.createElement("script"),
            t = document.getElementsByTagName("script")[0];
        e.async = true;
        e.src = "https://embed.tawk.to/65788ecd70c9f2407f7f3188/1hhferkee";
        e.charset = "UTF-8";
        e.setAttribute("crossorigin", "*");
        t.parentNode.insertBefore(e, t);
    })();
</script>

</head>

<body>

    @include('partials.navbarinstructor')
    <div id="toast-container" 
        class="position-fixed top-0 end-0 p-3" 
        style="z-index: 9999;">
    </div>
    
    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- JS Libraries -->
    <script src="{{ asset('assets/js/analytics.min.js') }}"></script>
    <script src="{{ asset('common/bootstrap/5.3/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/navbar-solid.v2.min.js') }}"></script>
    <script src="{{ asset('assets/js/frontFunctions.v2.min.js') }}"></script>
    <script src="{{ asset('assets/js/index.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/datatables/media/js/jquery.dataTables.min.js') }}" defer></script>
    <!-- <script src="{{ asset('assets/datatables/media/js/dataTables.bootstrap.min.js') }}" defer></script> -->
    <script src="{{ asset('assets/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}" defer></script>
    <script src="{{ asset('assets/datatables/extensions/Responsive/js/responsive.bootstrap.min.js') }}" defer></script>
    <script src="{{ asset('assets/datatables/media/js/datatables.demo.min.js') }}" defer></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        popoverTriggerList.forEach(function (popoverTriggerEl) {
            new bootstrap.Popover(popoverTriggerEl)
        })
    });
    </script>
    
    @stack('scripts')
</body>
