@extends('frontend.layout', [
'namePage' => $pages->page_title,
'activePage' => $pages->slug
])
@section('meta')
<meta name="description" content="{{ $pages->meta_description }}">
	<meta name="keywords" content="{{ $pages->meta_keywords }}" />
@endsection

@section('content')
<main style="background-color:#0062A5;">
    <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">    
            <div class="carousel-item active" style="height: 13rem !important;">
            <img src="{{ asset('/assets/site-img/header3-sm.png') }}" alt="" style="position: absolute; top: 0;left: 0;min-width: 100%; height: unset !important; ">
            <div class="container">
                <div class="carousel-caption text-start" style="bottom: 1rem !important; right: 25% !important; left: 25%; !important;">
                    <center><h3 class="carousel-header-font">{{ $pages->page_title }}</h3></center>
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
                <h1><strong>{{ $pages->header }}</strong></h1>
                <br>
                {!! $pages->content1 !!}
                <br/>
                {!! $pages->content2 !!}
                <br/>
                {!! $pages->content3 !!}
                <br/>
            </div>
        </div>
    </div>
</main>
@endsection