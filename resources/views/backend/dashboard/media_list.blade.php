@extends('backend.layout', [
	'namePage' => 'Media',
	'activePage' => 'list_media'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>List Media</h1>
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
                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-danger">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                            <div class="row">
                                @if(count($media) < 1)
                                    <p class="text-center">No media files saved</p>
                                @else
                                    @foreach ($media as $m)
                                        @if($m->add_extension == 'mp4')
                                            <div class="col-md-2 d-flex align-items-stretch">
                                                <div class="thumbnail" style="background-color: #f1f1f1; padding-left: 10px; padding-right: 10px; padding-bottom: 10px; padding-top: 10px; border: solid #7d7d7d 1px;">
                                                    <a href="{{asset('/assets/gallery/')."/".$m->mediafiles }}" target="_blank">
                                                        <video width="100%" controls>
                                                            <source src="{{asset('/assets/gallery/')."/".$m->mediafiles }}" type="video/mp4">
                                                            Your browser does not support HTML video.
                                                        </video>
                                                        <div class="caption">
                                                            <p style="color: #333232; font-size: 20px;">{{ $m->medianame }}</p>
                                                            <form action="/admin/delete_media" method="post">
                                                                @csrf
                                                                <input type="text" name="media_id" value="{{ $m->id }}" hidden>
                                                                <button type="submit" class="btn btn-info">DELETE</button>
                                                            </form>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @else 
                                            <div class="col-md-2 d-flex align-items-stretch">
                                                <div class="thumbnail" style="background-color: #f1f1f1; padding-left: 10px; padding-right: 10px; padding-bottom: 10px; padding-top: 10px; border: solid #7d7d7d 1px;">
                                                    <a href="{{asset('/assets/gallery/')."/".$m->mediafiles }}" target="_blank">
                                                        <img src="{{asset('/assets/gallery/')."/".$m->mediafiles }}" alt="{{ $m->medianame }}" style="width:100%">
                                                        <div class="caption">
                                                            <p style="color: #333232; font-size: 20px;">{{ $m->medianame }}</p>
                                                            <form action="/admin/delete_media" method="post">
                                                                @csrf
                                                                <input type="text" name="media_id" value="{{ $m->id }}" hidden>
                                                                <button type="submit" class="btn btn-info align-self-end">DELETE</button>
                                                            </form>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <!--end-->
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection