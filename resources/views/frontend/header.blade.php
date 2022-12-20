<div id="header-filler"></div>
<main id="page-header" class="page-header" style="background-color:#0062A5; height: 8rem; display: flex; justify-content: center; align-items: center;">
    @if (!isset($banner_image))
        <div class="text-container w-75 m-2">
            <h3 style="text-transform: uppercase; color: #fff; font-weight: 300; text-shadow: 2px 2px 8px #000">{{ $page_title }}</h3>
        </div>    
    @endif
</main>
@php
    $bg = 'assets/site-img/header3-sm.png';
    if(isset($banner_image)){
        $bg = 'storage/banner_images/'.$banner_image;
    }
@endphp
<style>
    #page-header{
        background-image: url("{{ asset($bg) }}");
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