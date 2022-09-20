@extends('frontend.layout', [
  'namePage' => 'Subscribe',
  'activePage' => 'newsletter_subscription'
])

@section('content')
@php
    $page_title = 'THANK YOU';
@endphp
@include('frontend.header')
<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
    <div class="container"><br/>&nbsp;
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card text-center" style="width: 100%">
                    <div class="card-body">
                        <h5 class="card-title">Thank You</h5>
                        <br>
                        <p class="card-text">Thank you for subscribing to our newsletter. You'll receive updates straight to your inbox!</p>
                        <br/>
                        <p class="card-text">You may also like and follow us on our social media accounts.</p>
                        <br>
                        <i class="fa fa-twitter" aria-hidden="true" style="font-size: 20pt !important"></i>&nbsp;&nbsp;<i class="fa fa-facebook-square" aria-hidden="true" style="font-size: 20pt !important"></i>&nbsp;&nbsp;<i class="fa fa-instagram" aria-hidden="true" style="font-size: 20pt !important"></i>
                        <br>
                        <br>
                        <a href="/" class="btn btn-primary">RETURN TO HOMEPAGE</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
