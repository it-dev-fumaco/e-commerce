@extends('frontend.layout', [
  'namePage' => 'Upload Deposit Slip',
  'activePage' => 'upload_deposit_slip'
])

@section('content')
@php
    $page_title = 'UPLOAD DEPOSIT SLIP / PROOF OF PAYMENT';
@endphp
@include('frontend.header')
<main style="background-color:#ffffff; min-height: 500px;" class="products-head">
    <div class="container"><br/>&nbsp;
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                @if(!session()->has('success'))
                @if ($is_invalid)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! $reason !!}
                </div>

                <a href="/" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp">RETURN TO HOMEPAGE</a>
                @endif
                @endif
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session()->get('success') !!}
                    </div>

                    <a href="/" class="btn btn-primary mt-3 fumacoFont_btn animated animatedFadeInUp fadeInUp">RETURN TO HOMEPAGE</a>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {!! session()->get('error') !!}
                    </div>
                @endif
                @if(!session()->has('success'))
                @if (!$is_invalid)
                <form action="/upload_deposit_slip/{{ $order_details->deposit_slip_token }}" method="POST" enctype="multipart/form-data" id="form-upload">
                    @csrf
                    <div class="card-body">
                        <h5>Order No.: <b>{{ $order_details->order_number }}</b></h5>
                        <h6>Customer Name: <b>{{ $order_details->order_name . ' ' . $order_details->order_lastname }}</b></h6>
                        <div class="uploadbox-container">
                            <div class="uploadbox-card">
                                <div class="uploadbox-drop_box">
                                    <h4 class="uploadbox-text">Select file here</h4>
                                    <p class="uploadbox-text">Files Supported: JPG, JPEG, PNG.</p>
                                    <img src="{{ asset('/storage/no-photo-available.png') }}" id="img-preview" class="img-thumbnail w-75 d-none">
                                    <input type="file" hidden accept=".jpg,.jpeg,.png" style="display:none;" name="image">
                                    <div class="d-flex flex-row">
                                        <div class="p-2">
                                            <button class="btn btn-primary btn-outline-primary bg-secondary" id="browse-btn" type="button">Choose File</button>
                                        </div>
                                        <div class="p-2">
                                            <button class="btn btn-primary btn-outline-primary d-none" id="submit-btn" type="submit">Upload</button>
                                        </div>
                                        <div class="p-2">
                                            <button class="btn btn-secondary btn-outline-primary bg-danger d-none" id="remove-btn" type="button">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @endif
                @endif
            </div>
        </div>
    </div>
</main>

<style>
    .uploadbox-container .btn{
        border: none !important;
    }
    .uploadbox-container {
        width: 100%;
        align-items: center;
        display: flex;
        justify-content: center;
    }
    .uploadbox-card {
        border-radius: 10px;
        width: 100%;
        background-color: #ffffff;
        padding: 10px 30px 40px;
    }
    .uploadbox-drop_box {
        margin: 10px 0;
        padding: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        border: 3px dotted #a3a3a3;
        border-radius: 5px;
    }
    .uploadbox-drop_box h4 {
        font-size: 16px;
        font-weight: 400;
        color: #2e2e2e;
    }
    .uploadbox-drop_box p {
        margin-top: 10px;
        margin-bottom: 20px;
        font-size: 12px;
        color: #a3a3a3;
    }
    .uploadbox-btn {
        text-decoration: none;
        background-color: #005af0;
        color: #ffffff;
        padding: 10px 20px;
        border: none;
        outline: none;
        transition: 0.3s;
    }
    .uploadbox-btn:hover{
        text-decoration: none;
        background-color: black;
        color: white;
        padding: 10px 20px;
        border: none;
        outline: 1px solid #010101;
    }
    .uploadbox-form input {
        margin: 10px 0;
        width: 100%;
        background-color: #e2e2e2;
        border: none;
        outline: none;
        padding: 12px 20px;
        border-radius: 4px;
    }
    .custom-margin{
        margin-top: 0;
    }
    @media (max-width: 575.98px) {
        .custom-margin{
            margin-top: 75px;
        }
    }
    @media (max-width: 767.98px) {
        .custom-margin{
            margin-top: 75px;
        }
    }
    @media only screen and (min-device-width : 768px) and (max-device-width : 1024px) and (orientation : portrait) {
        .custom-margin{
            margin-top: 75px;
        }
    }
</style>
@endsection

@section('script')
<script>
    $(function () {
        const dropArea = document.querySelector(".uploadbox-drop_box"),
            button = dropArea.querySelector("button"),
            input = dropArea.querySelector("input");

        let file;
        var filename;

        button.onclick = () => {
            input.click();
        };

        input.addEventListener("change", function (e) {
            var fileName = e.target.files[0].name;
            const file = e.target.files[0];
            if (file){
                let reader = new FileReader();
                reader.onload = function(event){
                    $('#img-preview').attr('src', event.target.result);
                }

                reader.readAsDataURL(file);

                $('#submit-btn').removeClass('d-none');
                $('#remove-btn').removeClass('d-none');
                $('#img-preview').removeClass('d-none');
                $('.uploadbox-text').addClass('d-none');
            }
        });

        $('#remove-btn').click(function() {
            $('#submit-btn').addClass('d-none');
            $('#remove-btn').addClass('d-none');
            $('#img-preview').addClass('d-none');
            $('.uploadbox-text').removeClass('d-none');
            $('#form-upload').trigger("reset");
        });
	});
</script>
@endsection
