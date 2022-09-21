@extends('backend.layout', [
'namePage' => 'Blogs',
'activePage' => 'blog_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add New Blog Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Add New Blog Page</li>
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
                                @if(session()->has('success'))
                                    <div class="alert alert-success fade show" role="alert">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @if(session()->has('image_error'))
                                    <div class="alert alert-warning fade show" role="alert">
                                        {{ session()->get('image_error') }}
                                    </div>
                                @endif
                                <form action="/admin/blog/add" method="POST" enctype="multipart/form-data">
                                    @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="blog_title">Title</label>
                                            <input type="text" class="form-control" name="blog_title" placeholder="Blog Title" required>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="blog_type">Slug</label>
                                            <input type="text" class="form-control" name="slug" placeholder="Slug" >
                                        </div>
                                        <div class="col-md-6">
                                            <label for="blog_type">Blog Type</label>
                                            <select class="form-control" name="blog_type" required>
                                                <option disabled value="">Select Blog Type</option>
                                                <option value="In Applications">In Applications</option>
                                                <option value="Solutions">Solutions</option>
                                                <option value="Products">Products</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Primary Image:</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile" name="img_primary" required>
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tablet Image:</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile" name="img_tab" required>
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Mobile Image:</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile" name="img_mb" required>
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                            </div>
                                        </div> 
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Homepage Image:</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile" name="img_home" required>
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Journals Page Image:</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile" name="img_journals" required>
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="blog_caption">Caption</label>
                                            <textarea class="form-control" rows="8" name="blog_caption"></textarea>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="blogcontent">Content</label>
                                            <textarea class="form-control page-content" rows="10" name="blog_content"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <center><button type="submit" class="btn btn-lg btn-primary">Save</button></center>
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

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").change(function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endsection