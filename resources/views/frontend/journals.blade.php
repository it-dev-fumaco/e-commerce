@extends('frontend.layout', [
  'namePage' => 'Blogs',
  'activePage' => 'journals'
])

@section('content')
<main class="abt_standard" style="background-color:#000;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($blog_carousel as $key => $carousel)
                <li data-bs-target="#myCarousel" data-bs-slide-to="{{$key}}" class="{{ $loop->first ? "active" : "" }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach($blog_carousel as $carousel)
                @php
                    $string = strip_tags($carousel->blog_caption);
                    if (strlen($string) > 250) {

                    // truncate string
                    $stringCut = substr($string, 0, 180);
                    $endPoint = strrpos($stringCut, ' ');

                    //if the string doesn't contain any space then it will cut without word basis.
                    $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                    $string .= '...';
                    }
                @endphp
                <div class="carousel-item {{ $loop->first ? "active" : "" }}">
                    <img src="{{ asset('/assets/journal/'. $carousel->blogprimaryimage) }}" alt="{{ $carousel->blogtitle }}" style="object-fit: cover; opacity: 0.6;">
                    <div class="container">
                        <div class="carousel-caption text-start">
                            <h3 class="fumacoFont1" style="font-family: 'poppins', sans-serif !important;">{{ $carousel->blogtitle }}</h3>
                            <p class="abt_standard fumacoFont2" style="text-align: left; text-justify: inter-word;">{{ $string }}</p>
                            <p><a class="abt_standard btn btn-lg btn-primary btn-fumaco fumacoFont_btn" href="blog?id={{ $carousel->id }}" role="button">Read More</a></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>
<main style="background-color:#ffffff;" class="products-head">

    <div class="container">
      <div class="row" style="padding-left: 0px !important; padding-right: 0px !important;">

        <div class="col-lg-12">
          <br>
          <br>
          <p style="color:#373b3e !important; font-weight: 400 !important; font-size:12px !important;">
            Home&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;Blogs</p>
          <hr>
        </div>

        <div class="col-lg-12" style="display: flex; justify-content: center;">
          <br>
          <center>
            <ul class="list-group list-group-horizontal-md">
              <li class="list-group-item" style="border: 0px !important; ">
                <a href="journals" style="color:#373b3e !important; font-weight: 400 !important; font-size:14px !important; text-decoration: none !important;">
                  All Blog Posts</a>
                <span style="color:#b2b2b2 !important;">
                    {{ $blog_count->count() }}
                </span>
              </li>
              <li class="list-group-item" style="border: 0px !important; ">
                <a href="journals?type=In Applications" style="color:#373b3e !important; font-weight: 400 !important; font-size:14px !important; text-decoration: none !important;">Applications</a>
                <span style="color:#b2b2b2 !important;">
                    {{ $app_count->count() }}
                </span></li>
              <li class="list-group-item" style="border: 0px !important; "><a href="journals?type=Solutions" style="color:#373b3e !important; font-weight: 400 !important; font-size:14px !important; text-decoration: none !important;">Solutions</a>
                <span style="color:#b2b2b2 !important;">
                    {{ $soln_count->count() }}
                </span></li>
              <li class="list-group-item" style="border: 0px !important; "><a href="journals?type=Products" style="color:#373b3e !important; font-weight: 400 !important; font-size:14px !important; text-decoration: none !important;">Products</a>
                <span style="color:#b2b2b2 !important;">
                    {{ $prod_count->count() }}
                </span></li>
            </ul>
          </center>
        </div>
      </div>
    </div>
</main>
<main style="background-color:#ffffff;" class="products-head">
    <br>
    <br>
    <div class="container">
        <div class="row" style="margin-bottom: 20px !important;">
            @foreach($blogs_arr as $blogs)
                <div class="col-lg-4 d-flex align-items-stretch animated animatedFadeInUp fadeInUp">
                    <div class="card" style="border: 0px solid rgba(0, 0, 0, 0.125) !important; padding: 20px !important">
                        <img class="card-img-top" src="{{ asset('/assets/journal/'. $blogs['image']) }}" alt="">
                        <div class="card-body align-items-stretch">
                            {{-- <p style="color:#000 !important; font-size: 10pt !important; font-weight: 300;" class="abt_standard">{{ $blogs['publish_date'] }} | {{ $blogs['comment_count'] }} Comment(s)</p> --}}
                            <a href="blog?id={{ $blogs['id'] }}" style="text-decoration: none !important;">
                                <p style="color:#373b3e !important;" class="abt_standard fumacoFont_card_title">{{ $blogs['title'] }}</p></a>

                          <div class="text ellipsis">
                            <p class="text-concat abt_standard">{{ $blogs['caption'] }}</p>
                          </div>

                          <a href="/blog?id={{ $blogs['id'] }}" class="text-concat mx-auto read-more">Read More</a>
                        </div>
                        {{-- <p style="color:#000 !important; font-size: 10pt !important; font-weight: 300;" class="abt_standard">&nbsp;&nbsp;&nbsp;{{ $blogs['type'] }}</p> --}}
                      </div>
                </div>
            @endforeach
            
        </div>
        
    </div>
</main>

@endsection

@section('style')
<style>
  .text {
    position: relative;
    font-size: 12px !important;
    width: 100%;
  }

  .text-concat {
    position: relative;
    display: inline-block;
    word-wrap: break-word;
    overflow: hidden;
    max-height: 4.8em;
    line-height: 1.5em;
    text-align: left;
    font-weight: 300 !important;
    color:#404040 !important;
  }

  .text.ellipsis::after {
    position: absolute;
    right: -12px;
    bottom: 4px;
  }

  .read-more{
    text-decoration: none !important;
    font-size: 12px !important;
    border-bottom: 1px solid #404040;
  }
  .abt_standard{
    font-family: 'poppins', sans-serif !important;
    text-decoration: none !important;
  }
</style>
@endsection