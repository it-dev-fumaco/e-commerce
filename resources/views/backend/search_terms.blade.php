@extends('backend.layout', [
'namePage' => 'Search Terms List',
'activePage' => 'search_terms_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Search Terms List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Search Terms List Page</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="float-right">
                                            <form action="/admin/marketing/search/list" class="text-center" method="GET">
                                                <div class="form-group row">
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                    </div>
                                                        
                                                    <div class="col-sm-2">
                                                        <button type="submit" class="btn btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Search Term</th>
                                            <th class="text-center">Frequency</th>
                                            <th class="text-center">No. of Results</th>
                                            <th class="text-center">Results</th>
                                            <th class="text-center">Date</th>
                                        </tr>
                                        @forelse ($search_arr as $terms)
                                            <tr>
                                                <td class="text-center">{{ $terms['id'] }}</td>
                                                <td>{{ $terms['search_term'] }}</td>
                                                <td class="text-center">{{ $terms['frequency'] }}</td>
                                                <td class="text-center">{{ $terms['results_count'] }}</td>
                                                <td class="text-center">
                                                    <a href="#" data-toggle="modal" data-target="#search{{ $terms['id'] }}Modal">
                                                        View Results
                                                    </a>
                                                    
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="search{{ $terms['id'] }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl" role="document">
                                                            <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Search Results</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @if($terms['product_results'])
                                                                    <div class="col-12 text-left">
                                                                        <h4>Product Results <span class="badge badge-primary">{{ count($terms['product_results']) }}</span></h4>
                                                                        <br>
                                                                        <div class="row">
                                                                            @foreach ($terms['product_results'] as $item)
                                                                                <div class="col-3">
                                                                                    <div class="row">
                                                                                        <div class="col-4">
                                                                                            @php
                                                                                                $image = ($item['image']) ? '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'. $item['image'] : '/storage/no-photo-available.png';
                                                                                            @endphp
                                                                                            <picture>
                                                                                                <img src="{{ asset($image) }}" alt="{{ $item['item_code'] }}" class="img-thumbnail rounded d-inline-block  border border-secondary" alt="" style="width: 100%">
                                                                                            </picture>
                                                                                        </div>
                                                                                        <div class="col-8">
                                                                                            {{ \Illuminate\Support\Str::limit($item['product_name'], $limit = 50, $end = '...') }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                <br/>
                                                                @if ($terms['blog_results'])
                                                                    <div class="col-12 text-left">
                                                                        <h4>Blog Results <span class="badge badge-primary">{{ count($terms['blog_results']) }}</span></h4>
                                                                        <br>
                                                                        @foreach ($terms['blog_results'] as $blog)
                                                                            <div class="col-8">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        {{ $blog['title'] }}
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $terms['last_search_date'] ? date('M d, Y H:i A', strtotime($terms['last_search_date'])) : '' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=4 class="text-center">No Listed Search Terms</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $search_list->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
