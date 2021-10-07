@extends('backend.layout', [
	'namePage' => 'Dashboard',
	'activePage' => 'add_media'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add Media</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Main</a></li>
                            <li class="breadcrumb-item active">View Media</li>
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
                                <div class="card-header">
                                    <h3 class="card-title">Media</h3>
                                </div>
                
                                <form role="form" action="/admin/add_media_records" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        @if(session()->has('success'))
                                            <div class="alert alert-success">
                                                {{ session()->get('success') }}
                                            </div>
                                        @endif
                                        @if(session()->has('image_error'))
                                            <div class="alert alert-danger">
                                                {{ session()->get('image_error') }}
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="x1">Media Name</label>
                                            <input type="text" class="form-control" id="" name="media_name" value="" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="fileToUpload">File input</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload" required>
                                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="">Add Record</span>
                                                </div>
                                            </div>
                                        </div>
                    
                                    </div>
                                    <!-- /.card-body -->
                    
                                    <div class="card-footer">
                                        <input type="submit" class="btn btn-primary" value="Upload">
                                    </div>
                                </form>
                            </div>
                        <!-- /.card -->
                        </div>
                        <!--end-->
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection