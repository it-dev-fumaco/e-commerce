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
                            @if(session()->has('active_success'))
                                <div class="alert alert-success">
                                    {{ session()->get('active_success') }}
                                </div>
                            @elseif(session()->has('active'))
                                <div class="alert alert-success">
                                    {{ session()->get('active') }}
                                </div>
                            @elseif(session()->has('active'))
                                <div class="alert alert-success">
                                    {{ session()->get('active') }}
                                </div>
                            @endif

                            @if(session()->has('remove_success'))
                                <div class="alert alert-success">
                                    {{ session()->get('remove_success') }}
                                </div>
                            @elseif(session()->has('remove_active'))
                                <div class="alert alert-success">
                                    {{ session()->get('remove_active') }}
                                </div>
                            @endif
                            
                            @if(session()->has('remove_header'))
                                <div class="alert alert-success">
                                    {{ session()->get('remove_header') }}
                                </div>
                            @endif

                            @if(session()->has('image_error'))
                                <div class="alert alert-success">
                                    {{ session()->get('image_error') }}
                                </div>
                            @endif

                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            <table id="example2" data-pagination="true" class="table table-bordered table-hover">
                                <thead>
                                <tr>
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
                                            <td>{{ $carousel['title'] }}</td>
                                            <td>{{ $carousel['btn_name'] }}</td>
                                            <td>{{ $carousel['url'] }}</td>
                                            <td>
                                                <span class="badge badge-{{ $carousel['is_active'] }}">{{ $carousel['is_active'] ? 'Active' : ''}}</span>
                                            </td>
                                            <td><span class="badge badge-{{ $carousel['status'] }}">{{ $carousel['status'] != 'danger' ? 'OK' : 'DISABLED'}}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button onclick="myFunction{{ $carousel['id'] }}()" class="dropbtn">Action</button>
                                                    <div id="myDropdown{{ $carousel['id'] }}" class="dropdown-content" style="z-index: 9;">
                                                        <form action="/admin/set_active" method="post">
                                                            @csrf
                                                            <input type="text" name="id" value="{{ $carousel['id'] }}" readonly hidden/>
                                                            <input type="submit" role="menuitem" value="Set Active" class="menu"/>
                                                        </form>
                                                        <form action="/admin/remove_active" method="post">
                                                            @csrf
                                                            <input type="text" name="id" value="{{ $carousel['id'] }}" readonly hidden/>
                                                            <input type="submit" role="menuitem" value="Remove Active" class="menu"/>
                                                        </form>
                                                        <form action="/admin/delete_header" method="post">
                                                            @csrf
                                                            <input type="text" name="id" value="{{ $carousel['id'] }}" readonly hidden/>
                                                            <input type="submit" role="menuitem" value="Delete" class="menu"/>
                                                        </form>
                                                        {{-- <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal{{ $carousel['id'] }}" style="width: 100%;padding: 12px 16px; text-decoration: none;display: block;">View</button>
                                                        <hr style="margin-top: 0.5rem;margin-bottom: 0.5rem;">
                                                        <form action="/admin/delete_header" method="post">
                                                            @csrf
                                                            <input type="text" name="id" value="{{ $carousel['id'] }}" readonly hidden/>
                                                            <button type="submit" class="btn btn-warning btn-sm active" style="width: 100%; padding-top: 13px; padding-bottom: 13px;">Delete</button>
                                                        </form>
                                                        <hr style="margin-top: 0.5rem;margin-bottom: 0.5rem;">
                                                        <form action="/admin/set_active" method="post">
                                                            @csrf
                                                            <input type="text" name="id" value="{{ $carousel['id'] }}" readonly hidden/>
                                                            <button type="submit" class="btn btn-success btn-sm active" style="width: 100%; padding-top: 13px; padding-bottom: 13px;">Set Active</button>
                                                        </form>
                                                        <hr style="margin-top: 0.5rem;margin-bottom: 0.5rem;">
                                                        <form action="/admin/remove_active" method="post">
                                                            @csrf
                                                            <input type="text" name="id" value="{{ $carousel['id'] }}" readonly hidden/>
                                                            <button type="submit" class="btn btn-danger btn-sm active" style="width: 100%; padding-top: 13px; padding-bottom: 13px;">Remove Active</button>
                                                        </form> --}}
                                                    </div>
                                                </div>
                                                <script>
                                                    function myFunction{{ $carousel['id'] }}() {
                                                        document.getElementById("myDropdown{{ $carousel['id'] }}").classList.toggle("show");
                                                    }
                                                    // Close the dropdown if the user clicks outside of it
                                                    window.onclick = function(event) {
                                                        if (!event.target.matches('.dropbtn')) {
                                                            var dropdowns = document.getElementsByClassName("dropdown-content");
                                                            var i;
                                                            for (i = 0; i < dropdowns.length; i++) {
                                                            var openDropdown = dropdowns[i];
                                                            if (openDropdown.classList.contains('show')) {
                                                                openDropdown.classList.remove('show');
                                                            }
                                                            }
                                                        }
                                                    }
                                                </script>
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
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Add Header Carousel</h3>
                        </div>
                        <form role="form" action="/admin/add_carousel" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="heading">Heading 1</label>
                                    <input type="text" class="form-control" id="heading" name="heading" value="" required>
                                </div>

                                <div class="form-group">
                                    <label for="caption">Caption 1</label>
                                    <textarea class="form-control" rows="6" id="caption" name="caption" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="btn_name">Button Name</label>
                                    <input type="text" class="form-control" id="btn_name" name="btn_name" value="" required>
                                </div>

                                <div class="form-group">
                                    <label for="url">URL</label>
                                    <input type="text" class="form-control" id="url" name="url" value="" required>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="fileToUpload">File input</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="">Add Record</span>
                                        </div>
                                    </div>
                                </div> --}}

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
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <input type="submit" class="btn btn-primary" value="Upload">
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
</style>
@endsection