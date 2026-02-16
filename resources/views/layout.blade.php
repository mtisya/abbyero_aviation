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
    <link rel="preload" as="image" href="{{ asset('assets/images/index/billboard-container-backAviation.webp') }}"
        type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-private-aircraft.webp') }}"
        type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-instrument-aircraft.webp') }}"
        type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-rusty-aircraft.webp') }}"
        type="image/webp">
    <link rel="preload" as="image" href="{{ asset('assets/images/index/skyscraper-remote-aircraft.webp') }}"
        type="image/webp">

    <!-- Favicons & Apple Touch Icons -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/images/fav-icons/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60"
        href="{{ asset('assets/images/fav-icons/gs-gm-favicos/apple-icon-60x60.png') }}">
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
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Plugin Styles -->
    <link href="{{ asset('common/video-js/8.0.4/video-js.min.css') }}" rel="stylesheet">
    <link href="{{ asset('common/sweetalert2/11.4.8/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/datatables/media/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/datatables/media/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/datatables/extensions/Responsive/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet">
    <style>
        .accordion-header-btn.collapsed .accordion-toggle-icon {
            transform: rotate(0deg);
            transition: transform 0.3s;
        }

        .accordion-header-btn:not(.collapsed) .accordion-toggle-icon {
            transform: rotate(90deg);
            transition: transform 0.3s;
        }

        .cover-400 {
            height: 400px;
            object-fit: cover;
        }

        .aircraft-hero-carousel .swiper {
            width: 100%;
            height: 500px;
            /* You can adjust to 400px or any fixed height */
            position: relative;
        }

        .aircraft-hero-carousel .swiper-slide {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .aircraft-hero-carousel .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .swiper-slide img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 10px;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #000;
        }

        .gallery-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .swiper-wrapper {
            margin: 0;
        }

        .swiper-slide {
            margin-right: 0 !important;
        }

        .swiper-slide {
            border: 1px dashed red;
        }

        .solution-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .solution-card:hover,
        .solution-card:focus-within {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            border-color: #007bff;
        }

        .solution-card h3 {
            margin-bottom: 10px;
        }

        .solution-card ul {
            padding-left: 20px;
            margin-bottom: 15px;
        }

        .solution-card p {
            flex-grow: 1;
            margin-bottom: 15px;
        }

        @media (hover: none) {

            /* Mobile touch "hover" effect */
            .solution-card:active {
                transform: translateY(-3px) scale(1.01);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }
        }

        #main-maintenance-image {
            transition: opacity 0.5s ease-in-out;
        }

        #main-maintenance-image.fade {
            opacity: 0;
        }
        .support-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .support-card:hover,
        .support-card:focus-within {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 6px 15px rgba(0,0,0,0.15);
            border-color: #007bff;
        }

        @media (hover: none) {
            .support-card:active {
                transform: translateY(-3px) scale(1.01);
                box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            }
        }

    </style>


    <title>Abbyero Aviation School</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.10/index.global.min.css">

@stack('styles')

</head>

<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- JS Libraries (Cleaned, No Duplicates) -->
    <script src="{{ asset('common/bootstrap/5.3/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/navbar-solid.v2.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/frontFunctions.v2.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/index.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>

    <!-- DataTables -->
    <script src="{{ asset('assets/datatables/media/js/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('assets/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}" defer></script>
    <script src="{{ asset('assets/datatables/media/js/datatables.demo.min.js') }}" defer></script>

    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            popoverTriggerList.forEach(function (popoverTriggerEl) {
                new bootstrap.Popover(popoverTriggerEl)
            })
        });
        function confirmDelete(flightId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to undo this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5bc0de',  // Light blue
                cancelButtonColor: '#6c757d',   // Default Bootstrap secondary
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + flightId).submit();
                }
            });
        }
        const desktopSwiper = new Swiper(".myDesktopSlider", {
            loop: true,
            spaceBetween: 20,
            speed: 800,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".desktop-only .swiper-button-next",
                prevEl: ".desktop-only .swiper-button-prev",
            },
            pagination: {
                el: ".desktop-only .swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                }
            }
        });
        const thumbsSwiper = new Swiper('.myThumbsSlider', {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
        });

        const gallerySwiper = new Swiper('.myDesktopSlider', {
            loop: true,
            spaceBetween: 10,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            thumbs: {
                swiper: thumbsSwiper,
            },
        });

    </script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline@6.1.10/index.global.min.js"></script>

    @stack('scripts')
</body>