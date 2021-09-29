@extends('frontend.layout', [
'namePage' => 'Terms and Conditions',
'activePage' => 'terms_conditions'
])

@section('content')
<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">    
            <div class="carousel-item active" style="height: 13rem !important;">
            <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
            <div class="container">
                <div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                    <center><h3 class="carousel-header-font">Terms and conditions</h3></center>
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
        <div class="row" style="padding-left: 5% !important; padding-right: 5% !important;">
            <div class="col-md-12">
                <h1><strong>Fumaco Terms &amp; Conditions</strong></h1>
                <p>&nbsp;</p>
                <p><strong>RETURNS AND EXCHANGE POLICY</strong></p>
                <p>We do not provide refunds for items. Instead items may be exchanged, or an accompanying credit memo is issued for future purchases.</p>
                <p>We accept items for return and exchange within 7 days for the following reasons:</p>
                <ol>
                <li>damaged or defective; or&nbsp;</li>
                <li>wrong items delivered</li>
                </ol>
                <p>&nbsp;</p>
                <p>Items should be in its original packaging and in good condition when returned, items that are no longer in good condition will be sent back at the expense of clients.</p>
                <p>&nbsp;</p>
                <p>A notification thru app, email or text will be sent to the customer once the request has been approved.</p>
                <p>&nbsp;</p>
                <p>Returns and Exchanges Do not apply for the following:</p>
                <ol>
                <li>Customized orders</li>
                <li>Indent order items</li>
                <li>Shipping/delivery/return fees</li>
                <li>Services and Installation Fees</li>
                </ol>
                <p>&nbsp;</p>
                <p>Returns may be sent to the following addresses:</p>
                <p>&nbsp;</p>
                <p>35 Pleasant View Drive, Bagbaguin, Caloocan City or;</p>
                <p>&nbsp;</p>
                <p>420 Ortigas Avenue, San Juan, Metro Manila</p>
                <p>&nbsp;</p>
                <p><strong>Shipping Policy</strong></p>
                <p>&nbsp;</p>
                <p>Fumaco calculates shipping costs based on location, weight and size. All calculations are made prior to check out.</p>
            </div>
        </div>
    </div>
</main>
<main style="background-color:#ffffff;">
    <br>
    <br>
    <br>
</main>
@endsection