@extends('backend.layout', [
'namePage' => 'Blogs',
'activePage' => 'blog_list'
])

@section('content')
<style>
    .bootstrap-tagsinput{
        width: 100%;
    }

    .label-info{
        background-color: #007BFF;
    }

    .label {
        display: inline-block;
        padding: .25em .4em;
        font-size: 16px;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,
        border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    </style>
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit Blog Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Edit Blog Page</li>
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
                                <form action="/admin/blog/edit/{{ $id }}" method="POST">
                                    @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="blog_title">Title</label>
                                            <input type="text" class="form-control" name="blog_title" placeholder="Blog Title" value="{{ $blog->blogtitle }}" required>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="blog_type">Slug</label>
                                            <input type="text" class="form-control" name="slug" placeholder="Slug" value="{{ $blog->slug }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="blog_type">Blog Type</label>
                                            <select class="form-control" name="blog_type" required>
                                                <option disabled value="">Select Blog Type</option>
                                                <option value="In Applications" {{ $blog->blogtype == "In Applications" ? 'selected' : '' }}>In Applications</option>
                                                <option value="Solutions" {{ $blog->blogtype == "Solutions" ? 'selected' : '' }}>Solutions</option>
                                                <option value="Products" {{ $blog->blogtype == "Products" ? 'selected' : '' }}>Products</option>
                                            </select>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="blog_caption">Caption</label>
                                            <textarea class="form-control page-content" rows="8" name="blog_caption">{!! $blog->blog_caption !!}</textarea>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="blogcontent">Content</label>
                                            <textarea class="form-control page-content" rows="10" name="blog_content">{!! $blog->blogcontent !!}</textarea>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="tags">Tags</label>
                                            <input type="text" data-role="tagsinput" name="tags" class="form-control" value="{{ $tags }}">
                                        </div>
                                    </div>
                                    <div class="float-right font-italic">
                                        <small>Last modified by: {{ $blog->last_modified_by }} - {{ $blog->last_modified_at }}</small><br>
                                        <small>Created by: {{ $blog->created_by }} - {{ $blog->created_at }}</small>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <center><button type="submit" class="btn btn-lg btn-primary">Save Changes</button></center>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <form action="/admin/blog/images/edit/{{ $id }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Primary Image: (1920 x 720)</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="img_primary" {{ $blog->blogprimaryimage ? '' : 'required' }}>
                                                    <label class="custom-file-label" for="customFile">{{ $blog->blogprimaryimage ? $blog->blogprimaryimage : 'Choose File' }}</label>
                                                </div>
                                                @if($blog->blogprimaryimage)
                                                    <p>Saved image: <b>{{ $blog->blogprimaryimage }}</b><button type="button" class="btn btn-sm btn-danger ml-3" data-toggle="modal" data-target="#deletePrimaryImageModal">Delete</button></p>
                                                @else
                                                    <p>No saved image</p>
                                                @endif
                                                <div class="modal fade" id="deletePrimaryImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $blog->blogprimaryimage }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Delete Primary Image?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a href="/admin/blog/images/img-delete/{{ $id }}/blogprimaryimage" type="button" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tablet Image: (1024 x 720)</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="img_tab" {{ $blog->{'blogprimayimage-tab'} ? '' : 'required' }}>
                                                    <label class="custom-file-label" for="customFile">{{ $blog->{'blogprimayimage-tab'} ? $blog->{'blogprimayimage-tab'} : 'Choose File' }}</label>
                                                </div>
                                                @if($blog->{'blogprimayimage-tab'})
                                                    <p>Saved image: <b>{{ $blog->{'blogprimayimage-tab'} }}</b><button type="button" class="btn btn-sm btn-danger ml-3" data-toggle="modal" data-target="#deleteTabletImageModal">Delete</button></p>
                                                @else
                                                    <p>No saved image</p>
                                                @endif
                                                <div class="modal fade" id="deleteTabletImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $blog->{'blogprimayimage-tab'} }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Delete Tablet Image?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a href="/admin/blog/images/img-delete/{{ $id }}/blogprimayimage-tab" type="button" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Mobile Image: (420 x 640)</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="img_mb" {{ $blog->{'blogprimayimage-mob'} ? '' : 'required' }}>
                                                    <label class="custom-file-label" for="customFile">{{ $blog->{'blogprimayimage-mob'} ? $blog->{'blogprimayimage-mob'} : 'Choose File' }}</label>
                                                </div>
                                                @if($blog->{'blogprimayimage-mob'})
                                                    <p>Saved image: <b>{{ $blog->{'blogprimayimage-mob'} }}</b><button type="button" class="btn btn-sm btn-danger ml-3" data-toggle="modal" data-target="#deleteMobileImageModal">Delete</button></p>
                                                @else
                                                    <p>No saved image</p>
                                                @endif
                                                <div class="modal fade" id="deleteMobileImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $blog->{'blogprimayimage-mob'} }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Delete Mobile Image?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a href="/admin/blog/images/img-delete/{{ $id }}/blogprimayimage-mob" type="button" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> 
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Homepage Image: (420 x 231)</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="img_home" {{ $blog->{'blogprimayimage-home'} ? '' : 'required' }}>
                                                    <label class="custom-file-label" for="customFile">{{ $blog->{'blogprimayimage-home'} ? $blog->{'blogprimayimage-home'} : 'Choose File' }}</label>
                                                </div>
                                                @if($blog->{'blogprimayimage-home'})
                                                    <p>Saved image: <b>{{ $blog->{'blogprimayimage-home'} }}</b><button type="button" class="btn btn-sm btn-danger ml-3" data-toggle="modal" data-target="#deleteHomeImageModal">Delete</button></p>
                                                @else
                                                    <p>No saved image</p>
                                                @endif
                                                <div class="modal fade" id="deleteHomeImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $blog->{'blogprimayimage-home'} }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Delete Homepage Image?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a href="/admin/blog/images/img-delete/{{ $id }}/blogprimayimage-home" type="button" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Journals Page Image: (439 x 291)</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="img_journals" {{ $blog->{'blogprimayimage-journal'} ? '' : 'required' }}>
                                                    <label class="custom-file-label" for="customFile">{{ $blog->{'blogprimayimage-journal'} ? $blog->{'blogprimayimage-journal'} : 'Choose File' }}</label>
                                                </div>
                                                @if($blog->{'blogprimayimage-journal'})
                                                    <p>Saved image: <b>{{ $blog->{'blogprimayimage-journal'} }}</b><button type="button" class="btn btn-sm btn-danger ml-3" data-toggle="modal" data-target="#deleteJournalsImageModal">Delete</button></p>
                                                @else
                                                    <p>No saved image</p>
                                                @endif
                                                <div class="modal fade" id="deleteJournalsImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $blog->{'blogprimayimage-journal'} }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Delete Journals Page Image?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <a href="/admin/blog/images/img-delete/{{ $id }}/blogprimayimage-journal" type="button" class="btn btn-danger">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <center><button type="submit" class="btn btn-lg btn-primary">Save Changes</button></center>
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