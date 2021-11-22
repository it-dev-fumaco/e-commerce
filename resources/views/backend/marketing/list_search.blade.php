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
                                            <form action="/admin/marketing/search/list class="text-center"" method="GET">
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
                                            <th class="text-center">IP Address</th>
                                            <th class="text-center">Date</th>
                                        </tr>
                                        @forelse ($search_list as $terms)
                                            <tr>
                                                <td class="text-center">{{ $terms->id }}</td>
                                                <td>{{ $terms->search_term }}</td>
                                                <td class="text-center">{{ $terms->frequency }}</td>
                                                <td class="text-center">{{ $terms->ip }}</td>
                                                <td class="text-center">{{ date('M d, Y H:i A', strtotime($terms->created_at)) }}</td>
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
