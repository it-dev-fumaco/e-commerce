@extends('backend.layout', [
'namePage' => $policy->page_name,
'activePage' => $policy->slug
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit {{ $policy->page_name }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Edit {{ $policy->page_name }}</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
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
                            <div class="card card-primary">
                                <form action="/admin/edit/{{ $policy->page_id }}" method="POST">
                                    <div class="card-body">
                                        <h4>{{ $policy->page_name }}</h4>
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Page Name *</label>
                                                <input type="text" name="name" class="form-control" value="{{ $policy->page_name }}" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Page Title *</label>
                                                <input type="text" name="title" class="form-control" value="{{ $policy->page_title }}" required/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Header</label>
                                                <input type="text" name="header" value="{{ $policy->header }}" class="form-control"/>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Slug *</label>
                                                <input type="text" name="slug" value="{{ $policy->slug }}" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <div class="form-group">
                                                    <label for="content1">Content 1 *</label>
                                                    <textarea class="form-control page-content" rows="10" name="content1">{{ $policy->content1 }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Content 2</label>
                                                <textarea name="content_1" rows="10" class="form-control page-content">{{ $policy->content2 }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Content 3</label>
                                                <textarea name="content_1" rows="10" class="form-control page-content">{{ $policy->content3 }}</textarea>
                                            </div>
                                        </div>
                                        <br>
                                        <h5 class="mt-3">Search Engine Optimization (SEO)</h5>
                                        <hr>
                                        <div class="form-group">
                                            <label for="product-keywords">Meta Keywords</label>
                                            <textarea class="form-control" rows="3" name="meta_keywords">{{ $policy->meta_keywords }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="product-meta-description">Meta Description</label>
                                            <textarea class="form-control" rows="3" name="meta_description">{{ $policy->meta_description }}</textarea>
                                        </div>
                                        <div class="float-right font-italic">
                                            <small>Last modified by: {{ $policy->last_modified_by }} - {{ $policy->date_updated }}</small>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@section('script')
<script>
    (function() {
       $(".page-content").summernote({
          dialogsInBody: true,
          dialogsFade: true,
          height: "500px",
       });
    })();
 </script>
@endsection
