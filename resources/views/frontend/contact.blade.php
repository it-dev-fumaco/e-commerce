@extends('frontend.layout', [
  'namePage' => 'Contact Us',
  'activePage' => 'contact'
])

@section('content')
<main style="background-color:#0062A5;">
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="height: 13rem !important;">
        <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
        <div class="container">
          <div class="carousel-caption text-start"
            style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
            <center>
              <h3 class="carousel-header-font">CONTACT US</h3>
            </center>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<main style="background-color:#ffffff;" class="products-head">
  <div class="container">
    &nbsp;
    <br>
    <br>
    <br>
    <div>
    <div class="row" style="padding-left: 5% !important; padding-right: 5% !important;">
      @foreach($fumaco_contact as $contact)
        <div class="col-md-6 animated animatedFadeInUp fadeInUp">
          <center>
            <p style="color:#186EA9 !important;" class="fumacoFont_card_title">{{ $contact->office_title }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">{{ $contact->office_address }}</p>
            <p style="color:#58595A !important; line-height: 10px !important;" class="fumacoFont_card_caption">Phone: {{ $contact->office_phone }}</p>
            <p style="color:#58595A !important; line-height: 10px !important;" class="fumacoFont_card_caption">Mobile: {{ $contact->office_mobile }}</p>
            <p style="color:#58595A !important; line-height: 10px !important;" class="fumacoFont_card_caption">Fax: {{ $contact->office_fax }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">Email: {{ $contact->office_email }}</p>
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
            <i class="fa fa-twitter" aria-hidden="true" style="font-size: 15pt !important"></i>&nbsp;&nbsp;<i class="fa fa-facebook-square" aria-hidden="true" style="font-size: 15pt !important"></i>&nbsp;&nbsp;<i class="fa fa-instagram" aria-hidden="true" style="font-size: 15pt !important"></i>
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
        @if(session()->has('message'))
        <div class="alert alert-success">
          {{ session()->get('message') }}
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
       <div class="row animated animatedFadeInUp fadeInUp">
          <div class="col">
            @if(config('services.recaptcha.key'))
            <div class="g-recaptcha" data-sitekey="{{config('services.recaptcha.key')}}"></div>
            @endif
            {{-- <div class="g-recaptcha" data-sitekey="6LfbWpwcAAAAAEPssgZuCMj8MKeVy7UVAXFkIbME"></div> --}}
          </div>
        </div>
       <center>
        <button class="g-recaptcha"
        data-sitekey="6LfSLPscAAAAAE1XtyL_cYR5tU1P68qZt2ikcm2j"
        data-callback='onSubmit'
        data-action='submit'>Submit</button>
    
          <button type="submit" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp">&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;</button>
        </center>
      </form>
      &nbsp;<br>&nbsp;<br>
    </div>
  </div>
</main>
@endsection

@section('script')
<script>
  function onSubmit(token) {
    alert();
    document.getElementById("contact-form").submit();
  }
</script>



@endsection
