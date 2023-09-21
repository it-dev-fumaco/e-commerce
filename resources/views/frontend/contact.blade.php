@extends('frontend.layout', [
  'namePage' => 'Contact Us',
  'activePage' => 'contact'
])

@section('meta')
  <meta property="og:url" content="https://www.fumaco.com/about" />
	<meta property="og:title" content="Fumaco | Contact" />
  @if ($image_for_sharing)
	<meta property="og:image" content="{{ $image_for_sharing }}" />
	@endif
@endsection


@section('content')
@php
    $page_title = 'contact us';
@endphp
@include('frontend.header')
<main style="background-color:#ffffff;" class="products-head">
  <div class="container">
    &nbsp;
    <br>
    <br>
    <br>
    <div>
    <div class="row" style="padding-left: 5% !important; padding-right: 5% !important;">
      @foreach($contact_info as $contact)
        <div class="col-md-6 animated animatedFadeInUp fadeInUp">
          <center>
            <p style="color:#186EA9 !important;" class="fumacoFont_card_title">{{ $contact['title'] }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">{{ $contact['address'] }}</p>
            @foreach ($contact['info'] as $info)
              <p style="color:#58595A !important; line-height: 10px !important;" class="fumacoFont_card_caption {{ $info['type'] == 'Mobile' ? 'd-inline' : '' }}">
                {{ $info['type'] }}: {{ $info['contact'] }}
                @if ($info['type'] == 'Mobile' && $info['apps'])
                @php
                    $apps = explode(',',$info['apps']);
                @endphp
                <div class="row d-inline" style="margin-left: 10px;">
                  @foreach($apps as $img)
                    @php
                      $image = '/storage/contacts/'.strtolower($img).'.png';
                      $image_webp = '/storage/contacts/'.strtolower($img).'.webp';
                    @endphp
                      <div class="d-inline" style="position: relative !important; width: 30px; height: 30px"><picture>
                        <source srcset="{{ asset($image_webp) }}" type="image/webp" style="object-fit: cover;">
                        <source srcset="{{ asset($image) }}" type="image/jpeg" style="object-fit: cover;">
                        <img src="{{ asset($image) }}" style="object-fit: cover; max-height: 100%;max-width: 90%;width: auto;height: auto;position: absolute;top: 0;bottom: 0;left: 0;right: 0;margin: auto;" loading='lazy'>
                      </picture></div>
                    @endforeach
                </div>
              @endif
              </p>
                
              @endforeach
          </center>
          <br>
          <br>
        </div>
      @endforeach
    </div>
    <div class="row" style="padding-left: 5% !important; padding-right: 5% !important;">
      <div class="col-md-12">
        <iframe src="{{ $fumaco_map->map_url }}" width="100%" height="480"></iframe>
      </div>
    </div>
    <div class="row" style="padding-left: 5% !important; padding-right: 5% !important;">
      <br>
      <br>
      <div class="col-md-12">
        <center>
          <br>
          <br>
          <p style="color:#186EA9 !important;" class="fumacoFont_card_title animated animatedFadeInUp fadeInUp">SOCIAL</p>
          <p style="color:#58595A !important;" class="fumacoFont_card_caption animated animatedFadeInUp fadeInUp">Like & Follow Us</p>
          <p style="color:#58595A !important;" class="fumacoFont_card_caption animated animatedFadeInUp fadeInUp">
            <a href="https://www.facebook.com/fumaco.inc/"><i class="fa fa-facebook-square" aria-hidden="true" style="font-size: 28pt !important" ></a></i>&nbsp;&nbsp;<a href="https://twitter.com/fumaco_lights?lang=en"><i class="fa fa-twitter" aria-hidden="true" style="font-size: 28pt !important"></i></a>
          </p>
        </center>
        <br>
        <br>
      </div>
    </div>
  </div>
</main>

<main style="background-color:#ffffff;" class="products-head">
  <div class="container">
    <br>
    <div class="row" style="padding-left: 5% !important; padding-right: 5% !important;">
      <div class="col-md-12">
        <center>
          <p style="color:#186EA9 !important; font-size:21px !important;"class="fumacoFont_card_title animated animatedFadeInUp fadeInUp">GET IN TOUCH</p>
          <p style="color:#58595A !important;" class="fumacoFont_card_caption animated animatedFadeInUp fadeInUp">We collaborate with ambitious brands and people; we'd love to build something great together.</p>
          <br>
        </center>
        @if(session()->has('success'))
        <div class="alert alert-success">
          {{ session()->get('success') }}
        </div>
      @endif
      @if(session()->has('error'))
        <div class="alert alert-warning">
          {{ session()->get('error') }}
        </div>
      @endif

      @if(count($errors->all()) > 0)
      <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
        @foreach ($errors->all() as $error)
          <span class="d-block">{{ $error }}</span>
        @endforeach
      </div>
    @endif
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <form action="add_contact" method="post" id="contact-form">
        @csrf
        <div class="row">
          <div class="col-lg-4 animated animatedFadeInUp fadeInUp">
            <input type="text" class="form-control caption_1" placeholder="Name *" name="name" value="{{ old('name') }}" required>
            <br>
          </div>
          <div class="col-lg-4 animated animatedFadeInUp fadeInUp">
            <input type="email" class="form-control caption_1" placeholder="Email *" name="email" value="{{ old('email') }}" required>
            <br>
          </div>
          <div class="col-lg-4 animated animatedFadeInUp fadeInUp">
            <input type="text" class="form-control caption_1" placeholder="Phone" name="phone" value="{{ old('phone') }}" required>
            <br>
          </div>
        </div>
        <div class="row animated animatedFadeInUp fadeInUp">
          <div class="col">
            <input type="text" class="form-control caption_1" placeholder="Subject" name="subject" value="{{ old('subject') }}" required>
          </div>
        </div>
        <br>
        <div class="row animated animatedFadeInUp fadeInUp">
          <div class="col">
            <textarea class="form-control caption_1" rows="5" id="comment" name="comment" placeholder="Message" required>{{ old('comment') }}</textarea>
          </div>
        </div>
        <br>
        {!! htmlFormSnippet() !!}
        <center>
          <button type="submit" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp disabled-btn" id="submitBtn" disabled>Submit</button>
        </center>
      </form>
      &nbsp;<br>&nbsp;<br>
    </div>
  </div>
</main>
@endsection

@section('style')
  <style>
    .disabled-btn{
      opacity: .5 !important;
    }
  </style>
@endsection

@section('script')
  <script type="text/javascript">
    function captchaSuccess(response){
      $('#submitBtn').removeClass('disabled-btn').prop('disabled', false)
    }

    function captchaFail(response){
      $('#submitBtn').addClass('disabled-btn').prop('disabled', true)
    }
  </script>
@endsection