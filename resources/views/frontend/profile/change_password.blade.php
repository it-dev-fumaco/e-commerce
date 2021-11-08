@extends('frontend.layout', [
	'namePage' => 'My Profile - Change Password',
	'activePage' => 'myprofile_change_password'
])

@section('content')
     <main style="background-color:#0062A5;">
        <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" style="height: 13rem !important;">
                    <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important;">
                    <div class="container">
                        <div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                            <center><h3 class="carousel-header-font">MY PROFILE</h3></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <main style="background-color:#ffffff;" class="products-head">
        <div class="container-fluid">
            <div class="row" style="padding-left: 15%; padding-right: 0%; padding-top: 25px;">
                <div class="col-lg-2">
                    <p class="caption_2"><a href="/myprofile/account_details" style="text-decoration: none; color: #000000;">Account Details</a></p>
                    <hr>
                    <p class="caption_2" style="color:#186EA9 !important; font-weight:400 !important;"><i class="fas fa-angle-double-right"></i> <span style="margin-left: 8px;">Change Password</span></p>
                    <hr>
                    <p class="caption_2"><a href="/myprofile/address" style="text-decoration: none; color: #000000;">Address</a></p>
                    <hr>
                    <p class="caption_2"><a href="/logout" style="text-decoration: none; color: #000000;">Sign Out</a></p>
                    <hr>
                </div>
                <div class="col-lg-8">
                    @if(session()->has('success'))
					<div class="row">
						<div class="col">
							<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
								{{ session()->get('success') }}
						  	</div>
						</div>
					</div>
					@endif
                    @if(session()->has('error'))
					<div class="row">
						<div class="col">
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								{{ session()->get('error') }}
						  	</div>
						</div>
					</div>
					@endif
                    @if(count($errors->all()) > 0)
                    <div class="row">
						<div class="col">
							<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
								@foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach 
						  	</div>
						</div>
					</div>
                    @endif
                    <form action="/myprofile/change_password/{{ Auth::user()->id }}/update" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 offset-md-4 mb-2">
                                <label for="newpassword" class="myprofile-font-form">Current Password :</label>
                                <input type="password" class="form-control caption_1" name="current_password" required>
                            </div>
                            <div class="col-md-4 offset-md-4 mb-2">
                                <label for="newpassword" class="myprofile-font-form">New Password :</label>
                                <input type="password" class="form-control caption_1" name="new_password" required>
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <label for="newpassword" class="myprofile-font-form">Confirm New Password :</label>
                                <input type="password" class="form-control caption_1" name="new_password_confirmation" required>

                                <button type="submit" class="btn btn-primary mt-3 caption_1">UPDATE</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('style')
<style>
    .products-head {
        margin-top: 10px !important;
        padding-left: 40px !important;
        padding-right: 40px !important;
    }
    .he1 {
        font-weight: 300 !important;
        font-size: 12px !important;
    }
    .he2 {
        font-weight: 200 !important;
        font-size: 10px !important;
    }
    .btmp {
        margin-bottom: 15px !important;
    }
    .caption_1 {
        font-weight: 200 !important;
        font-size: 14px !important;
    }
    .caption_2 {
        font-weight: 500 !important;
        font-size: 14px !important;
    }
    .order-font {
        font-weight: 200 !important;
        font-size: 14px !important;
    }
    .order-font-sub {
        font-weight: 200 !important;
        font-size: 10px !important;
    }
    .order-font-sub-b {
        font-weight: 300 !important;
        font-size: 14px !important;
    }
    .order-font-sub-b {
        font-weight: 300 !important;
        font-size: 14px !important;
    }
    .tbls{
        vertical-align: center !important;
    }
    .myprofile-font-form {
        font-weight: 500 !important;
        font-size: 14px !important;
    }
</style>
@endsection
