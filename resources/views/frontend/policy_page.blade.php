@extends('frontend.layout', [
'namePage' => $pages->page_title,
'activePage' => $pages->slug
])
@section('meta')
<meta name="description" content="{{ $pages->meta_description }}">
	<meta name="keywords" content="{{ $pages->meta_keywords }}" />
@endsection

@section('content')
@php
    $page_title = $pages->page_title;
@endphp
@include('frontend.header')
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