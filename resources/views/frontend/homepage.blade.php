@extends('frontend.layout', [
    'namePage' => 'Home',
    'activePage' => 'homepage'
])

@section('content')
    <main style="background-color:#0062A5;">

    
        <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
            <ol class="carousel-indicators">
              <li data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></li>
              <li data-bs-target="#myCarousel" data-bs-slide-to="1"></li>
              <li data-bs-target="#myCarousel" data-bs-slide-to="2"></li>
            </ol>
          
            <div class="carousel-inner">

                @foreach ($carousel_data as $carousel)
                @php
                      $string = strip_tags($carousel->fumaco_caption);
                    if (strlen($string) > 250) {
          
                      // truncate string
                      $stringCut = substr($string, 0, 180);
                      $endPoint = strrpos($stringCut, ' ');
          
                      //if the string doesn't contain any space then it will cut without word basis.
                      $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                      $string .= '...';
                    }
          
                @endphp
                <div class="carousel-item active" style="background: black;">
                    <img src="{{ asset('/assets/site-img/'. $carousel->fumaco_image1) }}" alt="{{ $carousel->fumaco_title }}" style="object-fit: cover;opacity: 0.6;">
        
                    <div class="container">
                      <div class="carousel-caption text-start">
                        <h3 class="carousel-header-font fumacoFont1">{{ $carousel->fumaco_title }}</h3>
                        <p></p>
                        <p class="carousel-caption-font fumacoFont2" style="text-align: left; text-justify: inter-word; letter-spacing: 1px;">{{ $string }}</p>
                        <p><p></p><a class="btn btn-lg btn-primary btn-fumaco fumacoFont_btn" href="{{ $carousel->fumaco_url }}" role="button">{{ $carousel->fumaco_btn_name }}</a></p>
                      </div>
                    </div>
                  </div>
                @endforeach
          
          
          
          
          
            </div>

          
          </div>
          
        
        
            <div class="container marketing">
                <section class="py-5 text-center container">
                  <div class="row py-lg-5">
                    <div class="col-lg-6 col-md-8 mx-auto">
                      <h4 class="fw-light font-b2 fumacoFont1">LASTEST ARTICLES</h4>
                    </div>
                  </div>
                </section>
              
              
              
                <div class="row">
              
              
              
              
              
                </div>
              
              </div>
            </main>
            
    @endsection