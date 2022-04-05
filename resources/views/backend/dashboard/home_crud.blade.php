@extends('backend.layout', [
	'namePage' => 'Dashboard',
	'activePage' => 'home_crud'
])

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Home Page</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Main</a></li>
              <li class="breadcrumb-item active">Home Page</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">List Carousel</h3>
                        </div>
                        <div class="card-body">
                            <div class="container-fluid text-right mb-2">
                                @if(session()->has('success'))
                                    <div class="alert alert-success fade show text-left" role="alert">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @if(session()->has('error'))
                                    <div class="alert alert-warning fade show text-left" role="alert">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                    Add header carousel
                                </button>

                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Add Header Carousel</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form role="form" action="/admin/add_carousel" method="post" enctype="multipart/form-data">
                                                <div class="modal-body text-left">
                                                    @csrf
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12 mb-2">
                                                                <label>Text Color (Default: White)</label>
                                                                <input type="text" class="color-picker" name="text_color" value="#FFF">
                                                            </div>
                                                            <div class="col-12 mb-2">
                                                                <label for="heading">Heading 1 *</label>
                                                                <input type="text" class="form-control" id="heading" name="heading" value="" required>
                                                            </div>

                                                            <div class="col-12 mb-2">
                                                                <label for="caption">Caption 1</label>
                                                                <textarea class="form-control" rows="3" id="caption" name="caption"></textarea>
                                                            </div>

                                                            <div class="col-6 mb-2">
                                                                <label for="btn_name">Button Name *</label>
                                                                <input type="text" class="form-control" id="btn_name" name="btn_name" value="" required>
                                                            </div>

                                                            <div class="col-6 mb-2">
                                                                @php
                                                                    $btn_position = ['Left', 'Center', 'Right'];
                                                                @endphp
                                                                <label>Button Position</label>
                                                                <select class="form-control" name="btn_position">
                                                                    @foreach ($btn_position as $position)
                                                                        <option value="{{ $position }}">{{ $position }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-12 mb-2">
                                                                <label for="url">URL *</label>
                                                                <input type="text" class="form-control" id="url" name="url" value="" required>
                                                            </div>

                                                            <div class="col-6 mb-2">
                                                                <label>Desktop Image (1920 x 720) *</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                      <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                                                    </div>
                                                                    <div class="custom-file">
                                                                      <input type="file" class="custom-file-input" name="fileToUpload" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" required>
                                                                      <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-6 mb-2">
                                                                <label>Mobile Image (360 x 640) *</label>
                                                                <div class="input-group">
                                                                    <div class="input-group-prepend">
                                                                      <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                                                    </div>
                                                                    <div class="custom-file">
                                                                      <input type="file" class="custom-file-input" name="mobile_image" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01" required>
                                                                      <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                        
                                                    </div>
                                                    <!-- /.card-body -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table id="example2" data-pagination="true" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Btn Caption</th>
                                    <th>Url</th>
                                    <th>Active</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($carousel_arr as $carousel)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-toggle="modal" data-target="#image-preview-Modal{{ $carousel['id'] }}">
                                                    <img src="{{ asset('/storage/journals/'.$carousel['lg_img']) }}" class="img-thumbnail" alt="{{ Str::slug(explode(".", $carousel['lg_img'])[0], '-') }}">
                                                </a>

                                                <div class="modal fade" id="image-preview-Modal{{ $carousel['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-3">
                                                                        <label>Mobile Image</label>
                                                                        <img src="{{ asset('/storage/journals/'.$carousel['sm_img']) }}" class="img-thumbnail w-100" alt="{{ Str::slug(explode(".", $carousel['sm_img'])[0], '-') }}">
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <label>Desktop Image</label>
                                                                        <img src="{{ asset('/storage/journals/'.$carousel['lg_img']) }}" class="img-thumbnail w-100" alt="{{ Str::slug(explode(".", $carousel['lg_img'])[0], '-') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                 </div>
                                            </td>
                                            <td>{{ $carousel['title'] }}</td>
                                            <td>{{ $carousel['btn_name'] }}</td>
                                            <td>{{ $carousel['url'] }}</td>
                                            <td>
                                                <span class="badge badge-{{ $carousel['is_active'] }}">{{ $carousel['is_active'] ? 'Active' : ''}}</span>
                                            </td>
                                            <td><span class="badge badge-{{ $carousel['status'] }}">{{ $carousel['status'] != 'danger' ? 'OK' : 'DISABLED'}}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#header-{{ $carousel['id'] }}-Modal">Edit Carousel Item</a>
                                                        <a class="dropdown-item" href="/admin/set_active/{{ $carousel['id'] }}">Set Active</a>
                                                        <a class="dropdown-item" href="/admin/remove_active/{{ $carousel['id'] }}">Remove Active</a>
                                                        <a class="dropdown-item" href="/admin/delete_header/{{ $carousel['id'] }}">Delete</a>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="header-{{ $carousel['id'] }}-Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-xl" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="/admin/edit_carousel/{{ $carousel['id'] }}" method="post" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    @csrf
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-12 mb-2">
                                                                                <label>Text Color (Default: White)</label>
                                                                                <input type="text" class="color-picker" name="text_color" value="{{ $carousel['text-color'] }}">
                                                                            </div>
                                                                            <div class="col-12 mb-2">
                                                                                <label for="heading">Heading 1 *</label>
                                                                                <input type="text" class="form-control" id="heading" name="heading" value="{{ $carousel['title'] }}" required>
                                                                            </div>
                
                                                                            <div class="col-12 mb-2">
                                                                                <label for="caption">Caption 1</label>
                                                                                <textarea class="form-control" rows="3" id="caption" name="caption">{{ $carousel['caption'] }}</textarea>
                                                                            </div>
                
                                                                            <div class="col-6 mb-2">
                                                                                <label for="btn_name">Button Name *</label>
                                                                                <input type="text" class="form-control" id="btn_name" name="btn_name" value="{{ $carousel['btn_name'] }}" required>
                                                                            </div>
                
                                                                            <div class="col-6 mb-2">
                                                                                @php
                                                                                    $btn_position = ['Left', 'Center', 'Right'];
                                                                                @endphp
                                                                                <label>Button Position</label>
                                                                                <select class="form-control" name="btn_position">
                                                                                    @foreach ($btn_position as $position)
                                                                                        <option value="{{ $position }}" {{ $position == $carousel['btn_position'] ? 'selected' : null }}>{{ $position }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                
                                                                            <div class="col-12 mb-2">
                                                                                <label for="url">URL *</label>
                                                                                <input type="text" class="form-control" id="url" name="url" value="{{ $carousel['url'] }}" required>
                                                                            </div>
                
                                                                            <div class="col-6 mb-2">
                                                                                <div class="row">
                                                                                    <div class="col-4">
                                                                                        <img src="{{ asset('/storage/journals/'.$carousel['lg_img']) }}" class="img-thumbnail" alt="{{ Str::slug(explode(".", $carousel['lg_img'])[0], '-') }}">
                                                                                    </div>
                                                                                    <div class="col-8">
                                                                                        <label>Desktop Image (1920 x 720) *</label>
                                                                                        <div class="input-group">
                                                                                            <div class="input-group-prepend">
                                                                                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                                                                            </div>
                                                                                            <div class="custom-file">
                                                                                            <input type="file" class="custom-file-input" name="fileToUpload" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                                                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                                                            </div>
                                                                                        </div><br>
                                                                                        <label>Saved Image: {{ $carousel['lg_img'] }}</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                
                                                                            <div class="col-6 mb-2">
                                                                                <div class="row">
                                                                                    <div class="col-4">
                                                                                        <img src="{{ asset('/storage/journals/'.$carousel['sm_img']) }}" class="img-thumbnail" alt="{{ Str::slug(explode(".", $carousel['sm_img'])[0], '-') }}">
                                                                                    </div>
                                                                                    <div class="col-8">
                                                                                        <label>Mobile Image (360 x 640) *</label>
                                                                                        <div class="input-group">
                                                                                            <div class="input-group-prepend">
                                                                                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                                                                            </div>
                                                                                            <div class="custom-file">
                                                                                            <input type="file" class="custom-file-input" name="mobile_image" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                                                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                                                            </div>
                                                                                        </div><br>
                                                                                        <label>Saved Image: {{ $carousel['sm_img'] }}</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="modal fade" id="myModal{{ $carousel['id'] }}" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">{{ $carousel['title'] }}</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $carousel['caption'] }}</p>
                                                        <br>
                                                        <br>
                                                        <p>Large Image</p>
                                                        <br>
                                                        <img src="'{{asset('/assets/site-img/').$carousel['lg_img'] }}'" alt="" width="400">
                                                        <br>
                                                        <br>
                                                        <p>Mobile Image</p>
                                                        <br>
                                                        <img src="'{{asset('/assets/site-img/').$carousel['sm_img'] }}'" alt="" width="200">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="float-right mt-4">
                                {{ $carousel_data->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Meta Data/Description</h3>
                        </div>
                        <form role="form" action="/admin/edit/3" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <h4>Home Page</h4>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>Page Name *</label>
                                        <input type="text" name="name" class="form-control" value="{{ $page->page_name }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Page Title *</label>
                                        <input type="text" name="title" class="form-control" value="{{ $page->page_title }}" required/>
                                    </div>
                                </div>
                                <h5 class="mt-3">Search Engine Optimization (SEO)</h5>
                                <hr>
                                <div class="form-group">
                                    <label for="product-meta-description">Meta Description *</label>
                                    <textarea class="form-control" rows="3" name="meta_description" required>{{ $page->meta_description }}</textarea>
                                    <input type="text" name="content1" value="for homepage" hidden>
                                    <input type="text" name="slug" value="/" hidden>
                                </div>
                                <div class="form-group">
                                    <label for="product-keywords">Meta Keywords *</label>
                                    <textarea class="form-control" rows="3" name="meta_keywords" required>{{ $page->meta_keywords }}</textarea>
                                </div>

                                <div class="float-right font-italic">
                                    <small>Last modified by: {{ $page->last_modified_by }} - {{ $page->date_updated }}</small>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer text-center">
                                <button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
  </div>
  <aside class="control-sidebar control-sidebar-dark">

  </aside>

</div>
<script src="{{ asset('/color_picker/jquery.minicolors.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/color_picker/jquery.minicolors.css') }}">
<style>
    .dropbtn {
      background-color: #3498DB;
      color: white;
      padding: 16px;
      font-size: 16px;
      border: none;
      cursor: pointer;
    }

    .dropbtn:hover, .dropbtn:focus {
      background-color: #2980B9;
    }

    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f1f1f1;
      min-width: 160px;
      overflow: auto;
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.4);
      z-index: 1;
      right: 0px;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown a:hover {background-color: #ddd;}

    .show {display: block;}

    .menu{
        border: none;
        padding-top: 13px;
        padding-bottom: 13px;
        padding-left: 13px;
        width: 100%;
        text-align: left;
        transition: .4s;
    }
    .menu:hover{
        background-color: #d1d1d1;
        transition: .4s;
    }
    .color-picker{
        cursor: pointer;
        border: none !important;
    }
</style>
<script>
    $('.color-picker').minicolors();
    $(".custom-file-input").change(function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endsection