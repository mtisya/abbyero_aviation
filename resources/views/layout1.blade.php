@extends('layout')

@section('content')
<section class="aircraft-hero-carousel py-4">
    <div class="container">
        <div class="swiper myHeroSwiper">
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/images/index/' . $image) }}" class="img-fluid rounded shadow" alt="Aircraft" />
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Desktop Gallery with Thumbnails -->
<section class="aircraft-gallery desktop-only py-5">
    <div class="container">
        <div class="swiper myDesktopSlider mb-3">
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/images/index/' . $image) }}" class="gallery-img" alt="Aircraft Gallery" />
                    </div>
                @endforeach
            </div>
        </div>

        <div class="swiper myThumbsSlider mt-3">
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <div class="swiper-slide" style="height: 100px;">
                        <img src="{{ asset('assets/images/index/' . $image) }}" class="w-100 h-100 object-fit-cover rounded" />
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Mobile Gallery -->
<section class="aircraft-gallery mobile-only py-5">
    <div class="container">
        <div class="swiper myMobileSlider">
            <div class="swiper-wrapper">
                @foreach($images as $image)
                    <div class="swiper-slide">
                        <img src="{{ asset('assets/images/index/' . $image) }}" class="gallery-img" alt="Aircraft Mobile View" />
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endsection
