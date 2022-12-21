<div id="header-filler"></div>
@php
    $default_banner = !isset($banner_image) ? 1 : 0;
    $bg = isset($banner_image) ? $banner_image : 'header3-sm.png';
@endphp
@if ($default_banner)
    <main id="page-header" class="page-header" style="background-color:#0062A5; height: 8rem; display: flex; justify-content: center; align-items: center;">
        @if (!isset($banner_image))
            <div class="text-container w-75 m-2">
                <h3 style="text-transform: uppercase; color: #fff; font-weight: 300; text-shadow: 2px 2px 8px #000">{{ $page_title }}</h3>
            </div>    
        @endif
    </main>
@else
    <div class="page-header">
        <img src="{{ asset('assets/site-img/'.$bg) }}" width="100%">
    </div>   
@endif
<style>
    #page-header{
        background-image: url("{{ asset('assets/site-img/'.$bg) }}");
        background-color: #cccccc;
        background-position: center;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        position: relative;
    }
    .text-container{
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
    }
</style>