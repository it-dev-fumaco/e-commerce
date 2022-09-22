@extends('frontend.layout', [
    'namePage' => 'About Us',
    'activePage' => 'about_page'
])

@section('meta')
  <meta property="og:url" content="https://www.fumaco.com/about" />
	<meta property="og:title" content="Fumaco | About Us" />
  @if ($image_for_sharing)
	<meta property="og:image" content="{{ $image_for_sharing }}" />
	@endif
@endsection

@section('content')
<main>
    <section class="py-5 container">

    </section>

    <div class="container-fluid padabout">
        <div class="row">
            <div class="col-lg-12 aboutalign animated animatedFadeInUp fadeInUp" style="padding-top:20px; text-align: right !important;">
                <p class="abt_standard font1color about-main">{!! $about_data->title !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp" style="font-family: 'poppins', sans-serif !important; text-align: left !important; color:#00a7ff !important;">
                <p class="fumacoFont_about_title">{!! $about_data->{'1_title_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p class="abt_standard font1color fumacoFont_about_sub_title">{!! $about_data->{'1_caption_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br></div>
        </div>
        <div class="row">
            <div class="col-lg-6 animated animatedFadeInUp fadeInUp">
                <p class="abt_standard font1color fumacoFont_about_caption">{!! $about_data->{'1_caption_2'} !!}<br>{!! $about_data->{'1_caption_3'} !!}</p>
            </div>
            <div class="col-lg-6">
                <div class="col-md-12"><p class="abt_standard font1color fumacoFont_about_caption"></p></div>
                <div class="col-md-12"><br></div>
                <div class="row">
                    <div class="col-sm-3 animated animatedFadeInUp fadeInUp" style="text-align: center;">
                        <p class="abt_standard font1color about-number">
                            <img src="{{ asset('/storage/about/30plus.png') }}" alt="" class="responsive" loading="lazy">
                        </p>
                    </div>
                    <div class="col-sm-8 animated animatedFadeInUp fadeInUp">
                        <p class="abt_standard font1color fumacoFont_about_caption" style="vertical-align: baseline; padding: 30px 0;">{!! $about_data->{'1_year_1_details'} !!}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"><br></div>
                </div>
                <div class="row">
                    <div class="col-sm-3 animated animatedFadeInUp fadeInUp" style="text-align: center;">
                        <p class="abt_standard font1color about-number">
                            <img src="{{ asset('/storage/about/7companies.png') }}" alt="" class="responsive" loading="lazy">
                        </p>
                    </div>
                    <div class="col-sm-8 animated animatedFadeInUp fadeInUp">
                        <p class="abt_standard font1color fumacoFont_about_caption" style="vertical-align: baseline; padding: 30px 0;">{!! $about_data->{'1_year_2_details'} !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><hr style="border: 1px solid #ffffff;"></div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
    </div>
</main>
<!-- end maing page 1 -->

<main class="bodybg1">
    <!-- maing page 2 -->
    <div class="container-fluid padabout">
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp" style="font-family: 'poppins', sans-serif !important; text-align: left !important; color:#00a7ff !important;">
                <p class="fumacoFont_about_title">{!! $about_data->{'2_title_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p class="abt_standard font1color fumacoFont_about_sub_title">{!! $about_data->{'2_caption_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br></div>
        </div>
        <div class="row">
            <div class="col-lg-6 animated animatedFadeInUp fadeInUp">
                <p class="abt_standard font1color fumacoFont_about_caption">{!! $about_data->{'2_caption_2'} !!}</p>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-3 animated animatedFadeInUp fadeInUp" style="text-align: center;">
                        <p class="abt_standard font1color about-number">
                            <img src="{{ asset('/storage/about/28percent.png') }}" alt="" class="responsive" loading="lazy">
                        </p>
                    </div>
                    <div class="col-sm-8 animated animatedFadeInUp fadeInUp">
                        <p class="abt_standard font1color fumacoFont_about_caption" style="vertical-align: baseline; padding: 30px 0;">{!! $about_data->{'2_year_1_details'} !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><hr style="border: 1px solid #ffffff;"></div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
    </div>
</main>
<!-- end maing page 2 -->

<main class="bodybg2">
    <!-- maing page 3 -->
    <div class="container-fluid padabout">
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp" style="font-family: 'poppins', sans-serif !important; text-align: left !important; color:#00a7ff !important;">
                <p class="fumacoFont_about_title">{!! $about_data->{'3_title_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp">
                <p class="abt_standard font1color fumacoFont_about_sub_title">{!! $about_data->{'3_caption_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br></div>
        </div>
        <div class="row">
            <div class="col-lg-6 animated animatedFadeInUp fadeInUp">
                <p class="abt_standard font1color fumacoFont_about_caption">{!! $about_data->{'3_caption_2'} !!}</p>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-md-12"><br></div>
                </div>
                <div class="row">
                    <div class="col-sm-3 animated animatedFadeInUp fadeInUp" style="text-align: center;">
                        <p class="abt_standard font1color about-number"><img src="{{ asset('/storage/about/13companies.png') }}" alt="" class="responsive" loading="lazy"></p>
                    </div>
                    <div class="col-sm-8 animated animatedFadeInUp fadeInUp">
                        <p class="abt_standard font1color fumacoFont_about_caption" style="vertical-align: baseline; padding: 30px 0;">{!! $about_data->{'3_year_1_details'} !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br></div>
        </div>
    </div>
</main>
<!-- end maing page 3 -->

<main class="bodybg3">
    <!-- maing page 3 -->
    <div class="container-fluid padabout">
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp" style="text-align: center !important;"><br><br>
                <p class="abt_standard font1color about-quote-title"><i class="fas fa-quote-left" style="font-size:12px !important; vertical-align: text-top !important;"></i>&nbsp;&nbsp;<i>{!! $about_data->slogan_title !!}</i>&nbsp;&nbsp;<i class="fas fa-quote-right" style="font-size:12px !important; vertical-align: text-top !important;"></i></p><br><br><br>
            </div>
        </div>
    </div>
</main>

<main class="bodybg4">
    <div class="container-fluid padabout">
        <div class="row">
            <div class="col-lg-12"><br><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12 animated animatedFadeInUp fadeInUp" style="font-family: 'poppins', sans-serif !important; text-align: left !important; color:#00a7ff !important;">
                <p class="fumacoFont_about_title">{!! $about_data->{'4_title_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12"><br></div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <p class="abt_standard font1color fumacoFont_about_sub_title animated animatedFadeInUp fadeInUp">{!! $about_data->{'4_caption_1'} !!}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12"><br></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row animated animatedFadeInUp fadeInUp display-flex">
                    @foreach ($partners as $partner)
                    @php
                        $image = ($partner->image) ? '/storage/sponsors/'.$partner->image : '/storage/no-photo-available.png';
                        $image_webp = ($partner->image) ? '/storage/sponsors/'.explode(".", $partner->image)[0] .'.webp' : '/storage/no-photo-available.png';
                    @endphp

                    <div class="col-md-2" style="padding-bottom: 20px;">
                        <picture>
                            <source srcset="{{ asset($image_webp) }}" type="image/webp">
                            <source srcset="{{ asset($image) }}" type="image/jpeg">
                            <img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $partner->image)[0], '-') }}" class="img-thumbnail" loading="lazy">

                        </picture>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align: right !important;"><br><br></div>
        </div>
    </div>
</main>
<main>
<br>
</main>
<main style="background-color:#0563a0;">
    <div class="container marketing">
        <section class="py-5 text-center container" style="padding-top: 0rem !important; padding-bottom: 1rem !important;"></section>
    </div>
</main>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('/page_css/about_page.min.css') }}">
<style>
    body:after{
        content:"";
        position:fixed; /* stretch a fixed position to the whole screen */
        top:0;
        height:100vh; /* fix for mobile browser address bar appearing disappearing */
        left:0;
        right:0;
        z-index:-1; /* needed to keep in the background */
        background: url('{{ asset('/storage/about/'.$about_data->background_1) }}') center center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .bodybg1:after {
        content:"";
        position:fixed; /* stretch a fixed position to the whole screen */
        top:0;
        height:100vh; /* fix for mobile browser address bar appearing disappearing */
        left:0;
        right:0;
        z-index:-1; /* needed to keep in the background */
        background: url('{{ asset('/storage/about/'.$about_data->background_2) }}')   center center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: 100% 100%;
    }
    .bodybg2 {
        background: url('{{ asset('/storage/about/'.$about_data->background_2) }}') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }
    .bodybg3 {
        background: rgb(0,13,43);
        background: linear-gradient(90deg, rgba(0,13,43,1) 0%, rgba(5,67,210,1) 50%, rgba(0,13,43,1) 100%);
    }
    .bodybg4 {
        background: url('{{ asset('/storage/about/'.$about_data->background_3) }}') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

</style>
@endsection