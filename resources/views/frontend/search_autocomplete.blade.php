<div class="container-fluid">
    <div class="row">
        <div class="col-12">&nbsp;</div>
        @php
            $products = $search_arr ? collect($search_arr)->where('type', 'Products') : [];
            $blogs = $search_arr ? collect($search_arr)->where('type', 'Blogs') : [];
        @endphp
        @if ($products or $blogs)
            <div class="col-sm-12 col-md-7 mx-auto">
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
                                    <img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $item['image'])[0], '-') }}" width="90%">
                                </picture>
                            </div>
                            <div class="col-{{ $item['screen'] == 'desktop' ? '10' : '9' }} product-desc">
                                <p>{{ $item['name'] }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <center>
                        <h5 class="text-muted m-4">No product(s) found.</h5>
                    </center>
                @endforelse
            </div>
            <div class="col-sm-12 col-md-5 mx-auto search-link">
                <h5 class="text-muted">Blogs</h5>
                <hr>
                @forelse ($blogs as $item)
                    @php
                        $image = ($item['image']) ? '/storage/journals/'.$item['image'] : '/storage/no-photo-available.png';
                        $image_webp = ($item['image']) ? '/storage/journals/'.explode(".", $item['image'])[0] .'.webp' : '/storage/no-photo-available.png';
                    @endphp
                    <a href="/blog/{{ $item['slug'] ? $item['slug'] : $item['id'] }}" class="search-link">
                        <div class="row search-row mb-2" style="height: {{ $item['screen'] == 'desktop' ? '60px' : '80px' }} !important">
                            <div class="blogs col-3 p-0">
                                <picture>
                                    <source srcset="{{ asset($image_webp) }}" type="image/webp">
                                    <source srcset="{{ asset($image) }}" type="image/jpeg">
                                    <img src="{{ asset($image) }}" alt="{{ Str::slug(explode(".", $item['image'])[0], '-') }}"  class="w-100 m-0" height="100%">
                                </picture>
                            </div>
                            <div class="blogs col-9">
                                <p style="font-size: 11pt">{{ $item['name'] }}</p>
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
        background-color: #0062A5 !important;
        color: #fff !important;
    }
</style>