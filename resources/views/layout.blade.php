<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO & OG -->
    <meta name="description" content="Abbyero Aviation LLC is the best choice for online Aviation school training.">
    <meta property="og:title" content="Abbyero Aviation LLC">
    <meta property="og:description"
        content="Abbyero Aviation LLC is the best choice for online Aviation school training.">
    <meta property="og:image" content="{{ asset('assets/images/index/home-thumbnail.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('assets/images/fav-icons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('assets/images/fav-icons/favicon-16x16.png') }}" sizes="16x16">

    <!-- CSS -->
    <link href="{{ asset('common/bootstrap/5.3/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/aa-branding.v2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/navbar.v2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/footer.v2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/index.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style_home.css') }}" rel="stylesheet">

    <!-- Font Awesome & Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Swiper CSS (general) -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">

    <style>
        /* =========================================================
   LOGBOOK MODAL - KEEP ABOVE NAVBAR
========================================================= */

        #createLogbookModal {
            z-index: 1060 !important;
        }

        #createLogbookModal .modal-dialog {
            margin-top: 80px;
            margin-bottom: 30px;
        }

        .modal-backdrop {
            z-index: 1050 !important;
        }

        /* Keep modal content above the navbar */
        #createLogbookModal .modal-content {
            position: relative;
            z-index: 1061;
        }

        /* Allow the modal to scroll without being hidden */
        #createLogbookModal .modal-body {
            max-height: calc(100vh - 190px);
            overflow-y: auto;
        }
    </style>

    <title>Abbyero Aviation LLC</title>

    <!-- Tawk.to Script -->
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

    @stack('styles')
</head>


</head>

<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('common/bootstrap/5.3/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/navbar-solid.v2.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            popoverTriggerList.forEach(function (popoverTriggerEl) {
                new bootstrap.Popover(popoverTriggerEl)
            });

            // Swiper (general)
            const desktopSwiper = new Swiper(".myDesktopSlider", {
                loop: true,
                spaceBetween: 20,
                speed: 800,
                autoplay: { delay: 3000, disableOnInteraction: false },
                navigation: { nextEl: ".desktop-only .swiper-button-next", prevEl: ".desktop-only .swiper-button-prev" },
                pagination: { el: ".desktop-only .swiper-pagination", clickable: true },
                breakpoints: { 320: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
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
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
                thumbs: { swiper: thumbsSwiper },
            });

            // DataTables (general)
            $('table.data-table').DataTable({
                responsive: true,
                autoWidth: false,
            });

            // Confirm delete
            window.confirmDelete = function (flightId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#5bc0de',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + flightId).submit();
                    }
                });
            }
        });
    </script>

    @stack('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ajaxError(function (event, xhr) {
            if (xhr.status === 409) {
                event.preventDefault();
            }
        });
    </script>
</body>