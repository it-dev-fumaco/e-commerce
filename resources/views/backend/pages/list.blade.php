@extends('backend.layout', [
'namePage' => 'Pages List',
'activePage' => 'pages_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Pages List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Pages List</li>
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
                                    @if(session()->has('success'))
                                        <div class="alert alert-success fade show" role="alert">
                                            {{ session()->get('success') }}
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                        <div class="alert alert-warning fade show" role="alert">
                                            {{ session()->get('error') }}
                                        </div>
                                    @endif
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Page Name</th>
                                                <th>Page Title</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pages as $page)
                                                <tr>
                                                    <td>{{ $page->page_name }}</td>
                                                    <td>{{ $page->page_title }}</td>
                                                    <td><a href="/admin/pages/edit/{{ $page->page_id }}" class="btn btn-primary"><i class="fa fa-edit"></i></a></td>
                                                </tr> 
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('script')

@endsection
