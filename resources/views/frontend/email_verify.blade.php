@extends('frontend.layout', [
  'namePage' => 'Verify your Email Address',
  'activePage' => 'email_verify'
])

@section('content')
@php
    $page_title = 'VERIFY EMAIL ADDRESS';
@endphp
@include('frontend.header')
<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
    <div class="container"><br/>&nbsp;
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card text-center" style="width: 100%">
                    <div class="card-body">
                        <p class="card-text m-4">An email has been sent to <b>{{ session()->get('email') }}</b>. Please check your email for a verification link.<br>If you did not receive the email then <a href="/resend_verification/{{ session()->get('email') }}">resend the verification email</a>.</p>

                        @if (session('resend'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Email has been resent
                        </div>
                        @endif
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
