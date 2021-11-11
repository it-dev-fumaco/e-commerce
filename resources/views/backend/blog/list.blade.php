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
                            <h1>Blogs List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Blogs List Page</li>
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
                                <div class="card-body">
                                    <form action="/admin/blog/subscribers" method="GET">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="search-box" name="email" placeholder="Search" value="{{request()->get('q')}}">
                                            </div>
                                                
                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>

                                            <div class="col-sm-6">
                                                <a href="/admin/blog/new" class="btn btn-primary float-right">Add New Blog</a>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="font-size: 10pt;">Title</th>
                                                <th style="font-size: 10pt;">Type</th>
                                                <th style="font-size: 10pt;">Author</th>
                                                <th style="font-size: 10pt;">Date Created</th>
                                                <th style="font-size: 10pt;">Created By</th>
                                                <th style="font-size: 10pt;" class="text-center">Status</th>
                                                <th style="font-size: 10pt;" class="text-center">Is featured</th>
                                                <th style="font-size: 10pt;">Publish</th>
                                                <th style="font-size: 10pt;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($blogs as $b)
                                            @php
                                                $toggle = '';
                                                if($b->blog_active == 1 or $b->blog_status == 0){
                                                    $toggle = "disabled";
                                                }
                                            @endphp
                                                <tr>
                                                    <td>{{ $b->blogtitle }}</td>
                                                    <td>{{ $b->blogtype }}</td>
                                                    <td>{{ $b->blog_by }}</td>
                                                    <td>{{ $b->created_at }}</td>
                                                    <td>{{ $b->created_by }}</td>
                                                    <td class="text-center">
                                                        <span class="badge badge-primary {{ $b->blog_active == 1 ? '' : 'd-none' }}">ACTIVE</span>
                                                        <br/>
                                                        <span class="badge badge-{{ $b->blog_status == 1 ? 'primary' : 'warning' }}">{{ $b->blog_status == 1 ? '' : 'NOT ' }}COMPLETE</span>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <label class="switch">
                                                                <input type="checkbox" class="feature-toggle" id="toggle_{{ $b->id }}" name="featured" {{ ($b->blog_featured == 1) ? 'checked' : '' }} value="{{ $b->id }}" {{ $toggle }}/>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <label class="switch">
                                                                <input type="checkbox" class="toggle" id="toggle_{{ $b->id }}" name="publish" {{ ($b->blog_enable == 1) ? 'checked' : '' }} value="{{ $b->id }}" {{ $toggle }}/>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <div class="dropdown">
                                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                    <a href="/admin/blog/edit/form/{{ $b->id }}" class="dropdown-item">View Details</a>
                                                                    <a href="/admin/blog/set_active/{{ $b->id }}" class="dropdown-item"><small>Set Active</small></a>
                                                                    <a href="/blog/{{ $b->slug ? $b->slug : $b->id }}" class="dropdown-item"><small>Preview</small></a>
                                                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#deleteBlog-{{ $b->id }}"><small>Delete</small></a>
                                                                </div>
                                                            </div>
                                                        </center>

                                                        <div class="modal fade" id="deleteBlog-{{ $b->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Blog {{ $b->id }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Delete Blog {{ $b->id }}?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <a href="/admin/blog/delete/{{ $b->id }}" type="button" class="btn btn-primary">Delete</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan=7 class="text-center">No Blogs</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $blogs->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 16px;
        }
    
        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
    
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }
    
        .slider:before {
            position: absolute;
            content: "";
            height: 10px;
            width: 10px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
    
        input:checked + .slider {
            background-color: #2196F3;
        }
    
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
    
        input:checked + .slider:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }
    
        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }
    
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".toggle").change(function(){
                var data = {
                    'publish': $(this).prop('checked') == true ? 1 : 0,
                    'blog_id': $(this).val(),
                    '_token': "{{ csrf_token() }}",
                }
                $.ajax({
                    type:'POST',
                    url:'/admin/blog/publish',
                    data: data,
                    success: function (response) {
                        console.log(status);
                    },
                    error: function () {
                        alert('An error occured.');
                    }
                });
            });

            $(".feature-toggle").change(function(){
                var data = {
                    'feature': $(this).prop('checked') == true ? 1 : 0,
                    'blog_id': $(this).val(),
                    '_token': "{{ csrf_token() }}",
                }
                $.ajax({
                    type:'POST',
                    url:'/admin/blog/feature',
                    data: data,
                    success: function (response) {
                        console.log(status);
                    },
                    error: function () {
                        alert('An error occured.');
                    }
                });
            });
        });
    </script>
@endsection