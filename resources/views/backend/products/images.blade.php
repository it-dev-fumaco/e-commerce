@extends('backend.layout', [
	'namePage' => 'Products',
	'activePage' => 'product_list'
])

@section('content')
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Upload Product Images</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Products</li>
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
                                <h3 class="card-title">Multiple Images</h3>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="card-body">
                                        <p><b>Item Code:</b> {{ $details->f_idcode }}</p>
                                        <p><b>Item Name:</b> {{ $details->f_name_name }}</p>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <form role="form" action="/admin/add_product_images" method="post" enctype="multipart/form-data">
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
                                                <label for="img_primary">Image Primary</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="img_primary" id="img_primary" required>
                                                        <label id="primary_label" class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <div class="form-group">
                                                <label for="img_zoom">Image Zoom</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" name="img_zoom" id="img_zoom" required>
                                                        <label id="zoom_label" class="custom-file-label">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <input type="text" value="{{ $details->f_idcode }}" name="item_code" hidden/>
                                            <input type="submit" class="btn btn-primary" value="Upload">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <!-- /.card -->
                    </div>

                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Images List</h3>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <th>Code</th>
                                        <th>Image Primary</th>
                                        <th>Image Zoom</th>
                                        <th>Action</th>
                                    </tr>
                                    @foreach ($img_arr as $img)
                                    <tr>
                                        <td>{{ $img['item_code'] }}</td>
                                        <td>{{ $img['primary'] }}</td>
                                        <td>{{ $img['zoom'] }}</td>
                                        <td>
                                            <form action="/admin/delete_product_image" method="post">
                                                @csrf
                                                <input type="text" value="{{ $img['img_id'] }}" name="img_id" hidden/>
                                                <button type="submit"  class="btn btn-success">DELETE</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
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

@section('script')
<script>
    $(document).ready(function() {
        $('#img_zoom').change(function(){
            $('#zoom_label').text($('#img_zoom').val().slice(12));
        });

        $('#img_primary').change(function(){
            $('#primary_label').text($('#img_primary').val().slice(12));
        });
	});
</script>
@endsection
