<div class="container-fluid">
    <div class="row">
        <div class="col-12">&nbsp;</div>
        @php
            $products = $search_arr ? collect($search_arr)->where('type', 'Products') : [];
            $blogs = $search_arr ? collect($search_arr)->where('type', 'Blogs') : [];
        @endphp
        @if (count($products) > 0 or count($blogs) > 0)
            <div class="col-sm-12 col-md-8 mx-auto">
                <h5 class="text-muted">Products</h5>
                <hr>
                @forelse ($products as $item)
                    @php
                        $image = ($item['image']) ? '/storage/item_images/'.$item['id'].'/gallery/preview/'.$item['image'] : '/storage/no-photo-available.png';
                        $image_webp = ($item['image']) ? '/storage/item_images/'.$item['id'].'/gallery/preview/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.png';
                    @endphp
                    <a href="/product/{{ $item['slug'] ? $item['slug'] : $item['id'] }}" class="search-link">
                        <div class="row search-row mb-2">
                            <div class="col-3 col-lg-2 text-center p-0">
                                <picture>
                                    <source srcset="{{ asset($image_webp) }}" type="image/webp">
                                    <source srcset="{{ asset($image) }}" type="image/jpeg">
                                    <img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $item['image'])[0], '-') }}" class="autocomplete-image" style="width: 100%">
                                </picture>
                            </div>
                            <div class="col-9 col-lg-10 product-desc d-flex align-items-center">
                                <div class="box">
                                    <span class="search-name">{{ $item['name'] }}</span>
                                    @if ($item['is_discounted'] == 1)
                                        <br/><span class="search-name">{{ $item['discounted_price'] }} <del>{{ $item['default_price'] }}</del></span> <span style="border-radius: 7px; background-color: #FF0000; color: #fff; padding: 2px; font-size: 10pt;" class="discount-display"><b>{{ $item['discount_display'] }}</b></span>
                                    @else
                                        <br/><span class="search-name">{{ $item['default_price'] }}</span>
                                    @endif
                                    <span style="font-size: 9pt; font-style: italic">In {{ $item['category'] }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <center>
                        <h5 class="text-muted m-4">No product(s) found.</h5>
                    </center>
                @endforelse
            </div>
            <div class="col-sm-12 col-md-4 mx-auto search-link">
                <h5 class="text-muted">Blogs</h5>
                <hr>
                @forelse ($blogs as $item)
                    <a href="/blog/{{ $item['slug'] ? $item['slug'] : $item['id'] }}" class="search-link">
                        <div class="row search-row">
                            <div class="blogs col-12">
                                <p class="search-name">{{ $item['name'] }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <center>
                        <h5 class="text-muted m-4">No blog(s) found.</h5>
                    </center>
                @endforelse
            </div>
            
        @else
            <center>
                <h5 class="text-muted m-4">No result(s) found.</h5>
            </center>
        @endif
        <div class="container p-2">
            <center>
                <a href="{{ $search_arr['results_count'] == 0 ? '/?s=' : '#' }}" class="see-all-btn" id="{{ $search_arr['results_count'] == 0 ? '' : 'FUMACO-form-submit' }}" style="color: #0062A5">
                    See All Search Results
                </a>
            </center>
        </div>
    </div>
</div>

<style>
    .search-row, .search-link, .product-desc, .blogs{
        color: #000 !important;
        text-transform: none !important;
        text-decoration: none !important;
        transition: .4s !important;
    }
    .search-row:hover .product-desc .box .search-name, .search-row:hover .blogs{
        text-decoration: underline !important;
    }
    .search-name{
        font-size: 11pt !important;
    }
    .autocomplete-image{
        width: 70% !important;
    }
    .see-all-btn{
        text-decoration: none;
        transition: .4s;
    }
    .see-all-btn:hover{
        text-decoration: underline;
    }
    @media (max-width: 575.98px) {
        .search-name{
            font-size: 10pt !important;
        }
        .autocomplete-image{
            width: 100% !important;
        }
    }
  	@media (max-width: 767.98px) {
        .search-name{
            font-size: 10pt !important;
        }
        .autocomplete-image{
            width: 100% !important;
        }
    }
</style>
<script>
    @php
        if($search_arr['screen'] == 'desktop'){
            $form = '#desk-search-bar-form';
            $input = '#desk-search-form-input';
        }else{
            $form = '#mob-search-bar-form';
            $input = '#mob-autocomplete-search';
        }
    @endphp
    $(document).ready(function(){
        $("{{ $form }}").on('click', '#FUMACO-form-submit', function(e) {
            e.preventDefault();
            $("{{ $form }}").submit();
        });
    });
</script>