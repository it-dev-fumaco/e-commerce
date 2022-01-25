<div class="container-fluid">
    <div class="row">
        <div class="col-12">&nbsp;</div>
        @php
            $products = $search_arr ? collect($search_arr)->where('type', 'Products') : [];
            $blogs = $search_arr ? collect($search_arr)->where('type', 'Blogs') : [];
        @endphp
        @if ($products or $blogs)
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
                            <div class="col-{{ $item['screen'] == 'desktop' ? '2' : '3' }}">
                                <picture>
                                    <source srcset="{{ asset($image_webp) }}" type="image/webp">
                                    <source srcset="{{ asset($image) }}" type="image/jpeg">
                                    <img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $item['image'])[0], '-') }}" class="autocomplete-image">
                                </picture>
                            </div>
                            <div class="col-{{ $item['screen'] == 'desktop' ? '10' : '9' }} product-desc">
                                <p class="search-name">{{ $item['name'] }}</p>
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
    </div>
</div>

<style>
    .search-row, .search-link, .product-desc, .blogs{
        color: #000 !important;
        text-transform: none !important;
        text-decoration: none !important;
        transition: .4s !important;
    }
    .search-row:hover .product-desc, .search-row:hover .blogs{
        text-decoration: underline !important;
    }
    .search-name{
        font-size: 11pt !important;
    }
    .autocomplete-image{
        width: 70% !important;
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