@extends('frontend.layout', [
  'namePage' => 'Contact',
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
      @if(session()->has('message'))
        <div class="alert alert-success">
          {{ session()->get('message') }}
        </div>
      @endif
      {{-- <div class="alert alert-danger">
        <strong>Fail!</strong> Please check information below, make sure email is correct.<br>
      </div>
    </div> --}}

    <div class="row" style="padding-left: 5% !important; padding-right: 5% !important;">
      @foreach($fumaco_contact as $contact)
        <div class="col-md-6 animated animatedFadeInUp fadeInUp">
          <center>
            <p style="color:#186EA9 !important;" class="fumacoFont_card_title">{{ $contact->office_title }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">{{ $contact->office_address }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">Phone: {{ $contact->office_phone }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">Mobile: {{ $contact->office_mobile }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">Fax: {{ $contact->office_fax }}</p>
            <p style="color:#58595A !important;" class="fumacoFont_card_caption">Email: {{ $contact->office_email }}</p>
          </center>
          <br>
          <br>
        </div>
      @endforeach
    </div>
    {{-- $contact_data0_fumaco = $data_contact_1["office_title"];
    $contact_data1_fumaco = $data_contact_1["office_address"];
    $contact_data2_fumaco = $data_contact_1["office_phone"];
    $contact_data3_fumaco = $data_contact_1["office_mobile"];
    $contact_data4_fumaco = $data_contact_1["office_fax"];
    $contact_data5_fumaco = $data_contact_1["office_email"]; --}}
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
            <i class="fa fa-twitter" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-facebook-square" aria-hidden="true"></i>&nbsp;&nbsp;<i class="fa fa-instagram" aria-hidden="true"></i>
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
          <p style="color:#58595A !important;" class="fumacoFont_card_caption animated animatedFadeInUp fadeInUp">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
            dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
            ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
            fugiat nulla pariatur. </p>
          <br>
        </center>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <form action="add_contact" method="post">
        @csrf
        <div class="row">
          <div class="col-lg-4 animated animatedFadeInUp fadeInUp">
            <input type="text" class="form-control caption_1" placeholder="Name *" name="name" required>
            <br>
          </div>
          <div class="col-lg-4 animated animatedFadeInUp fadeInUp">
            <input type="email" class="form-control caption_1" placeholder="Email *" name="email" required>
            <br>
          </div>
          <div class="col-lg-4 animated animatedFadeInUp fadeInUp">
            <input type="text" class="form-control caption_1" placeholder="Phone" name="phone" required>
            <br>
          </div>
        </div>
        <div class="row animated animatedFadeInUp fadeInUp">
          <div class="col">
            <input type="text" class="form-control caption_1" placeholder="Subject" name="subject" required>
          </div>
        </div>
        <br>
        <div class="row animated animatedFadeInUp fadeInUp">
          <div class="col">
            <textarea class="form-control caption_1" rows="5" id="comment" name="comment" placeholder="Message" required></textarea>
          </div>
        </div>
        <br>
       <div class="row animated animatedFadeInUp fadeInUp">
          <div class="col">
            <div class="g-recaptcha" data-sitekey="6LfbWpwcAAAAAEPssgZuCMj8MKeVy7UVAXFkIbME"></div>
          </div>
        </div>

       <center>
          <button type="submit" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp">&nbsp;&nbsp;&nbsp;Submit&nbsp;&nbsp;&nbsp;</button>
        </center>
      </form>
      &nbsp;<br>&nbsp;<br>
    </div>
  </div>
</main>
@endsection
