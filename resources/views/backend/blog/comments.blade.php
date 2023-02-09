@extends('backend.layout', [
'namePage' => 'Blog Comments',
'activePage' => 'blog_comments_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Blog Comments List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Blog Comments List Page</li>
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
                                    <form action="/admin/blog/comments" method="GET">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                            </div>
                                                
                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center align-middle" style="width: 15%">Author</th>
                                                <th class="text-center align-middle" style="width: 24%">Blog Title</th>
                                                <th class="text-center align-middle" style="width: 38%">Comment</th>
                                                <th class="text-center align-middle" style="width: 10%">Date</th>
                                                <th class="text-center align-middle" style="width: 8%">Approve</th>
                                                <th class="text-center align-middle" style="width: 5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($comments_arr as $c)
                                                <tr>
                                                    <td>
                                                        {{ $c['blog_name'] }}<br/>
                                                        {{ $c['blog_email'] }}<br/>
                                                        {{ $c['blog_ip'] }}
                                                    </td>
                                                    <td class="text-center align-middle">{{ $c['blog_title'] }}</td>
                                                    <td class="text-justify align-middle">
                                                        {{ $c['blog_comments'] }}
                                                    </td>
                                                    <td class="text-center align-middle">{{ \Carbon\Carbon::parse($c['blog_date'])->format('M d, Y') }}</td>
                                                    <td class="align-middle">
                                                        <center>
                                                            <label class="switch">
                                                                <input type="checkbox" class="toggle" id="toggle_{{ $c['id']}}" name="approve" {{ ($c['blog_status'] == 0) ? '' : 'checked' }} value="{{ $c['id'] }}"/>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </center>
                                                    </td>
                                                    <td class="align-middle">
                                                        <center>
                                                            <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteComment-{{ $c['id'] }}"><i class="fa fa-trash"></i></a>
                                                        </center>

                                                        <div class="modal fade" id="deleteComment-{{ $c['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">{{ $c['blog_email'] }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Delete Comment?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                                                    <a href="/admin/blog/comment/delete/{{ $c['id'] }}" type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @if ($c['replies'])
                                                    @foreach ($c['replies'] as $r)
                                                        <tr>
                                                            <td>{{ $r['blog_id'] }}</td>
                                                            <td>
                                                                <small>Replying to <b>{{ $c['blog_name'] }}</b></small><br/>
                                                                {{ $r['blog_name'] }}<br/>
                                                                {{ $r['blog_email'] }}<br/>
                                                                {{ $r['blog_ip'] }}
                                                            </td>
                                                            <td>
                                                                {{ $r['blog_comments'] }}
                                                            </td>
                                                            <td>{{ \Carbon\Carbon::parse($r['blog_date'])->format('M d, Y') }}</td>
                                                            <td>
                                                                <center>
                                                                    <label class="switch">
                                                                        <input type="checkbox" class="toggle" id="toggle_{{ $r['id']}}" name="approve" {{ ($r['blog_status'] == 1) ? 'checked' : '' }} value="{{ $r['id'] }}"/>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                </center>
                                                            </td>
                                                            <td>
                                                                <center>
                                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteComment-{{ $r['id'] }}"><i class="fa fa-trash"></i></a>
                                                                </center>
        
                                                                <div class="modal fade" id="deleteComment-{{ $r['id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $r['blog_email'] }}</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Delete Comment?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                            <a href="/admin/blog/comment/delete/{{ $r['id'] }}" type="button" class="btn btn-primary">Delete</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan=5 class="text-center">No Comment(s) Listed</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $comments->withQueryString()->links('pagination::bootstrap-4') }}
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

        .table{
            font-size: 11.5pt !important;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".toggle").change(function(){
                var data = {
                    'approve': $(this).prop('checked') == true ? 1 : 0,
                    'comment_id': $(this).val(),
                    '_token': "{{ csrf_token() }}",
                }
                // console.log(data);
                $.ajax({
                    type:'POST',
                    url:'/admin/blog/comment/approve',
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