<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 13rem !important;">
                <picture>
                    <source srcset="{{ asset('/assets/site-img/header3-sm.webp') }}" type="image/webp">
                    <source srcset="{{ asset('/assets/site-img/header3-sm.png') }}" type="image/jpeg">
                    <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; bottom: 0 !important;left: 0;min-width: 100%; height: 100% !important;">
                </picture>
                <div class="container">
                    <div class="carousel-caption text-start"
                    style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                    <center>
                        <h3 class="carousel-header-font" style="text-transform: uppercase">{{ $page_title }}</h3>
                    </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>