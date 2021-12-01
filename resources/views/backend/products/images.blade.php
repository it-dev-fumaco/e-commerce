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
                            <div class="row">
                                <div class="col-12">
                                    <div class="card-body">
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
                                            <div class="col-12">
                                                <h4>Product</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <p><b>Item Code:</b> {{ $details->f_idcode }}</p>
                                                <p><b>Item Name:</b> {{ $details->f_name_name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- /.card -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h4>Product Images List</h4>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#uploadProductImagesModal">Upload</button>
                                    </div>

                                    <div class="modal fade" id="uploadProductImagesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form role="form" action="/admin/add_product_images" method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="img_primary">Image Primary</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="img_primary" id="img_primary" required>
                                                                        <label id="primary_label" class="custom-file-label">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="img_zoom">Image Zoom</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="img_zoom" id="img_zoom" required>
                                                                        <label id="zoom_label" class="custom-file-label">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Upload</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <input type="text" value="{{ $details->f_idcode }}" name="item_code" hidden/>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <table class="table">
                                        <tr>
                                            <th></th>
                                            <th>Image Primary</th>
                                            <th>Image Zoom</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($img_arr as $img)
                                        <tr>
                                            <td style="width: 10%">
                                                <picture>
                                                    <source srcset="{!!  asset('/storage/item_images/'.$details->f_idcode.'/gallery/preview/'.explode(".", $img['primary'])[0] .'.webp') !!}" type="image/webp" class="img-responsive card-img-top" style="width: 100% !important;">
                                                    <source srcset="{!!  asset('/storage/item_images/'.$details->f_idcode.'/gallery/preview/'.$img['primary']) !!}" type="image/jpeg" class="img-responsive card-img-top" style="width: 100% !important;">
                                                    <div class="hover-container"><img src="{!!  asset('/storage/item_images/'.$details->f_idcode.'/gallery/preview/'.$img['primary']) !!}" alt="{{ Str::slug(explode(".", $img['primary'])[0], '-') }}" class="img-responsive card-img-top hover" style="width: 100% !important;"></div>
                                                </picture>
                                            </td>
                                            <td>{{ $img['primary'] }}</td>
                                            <td>{{ $img['zoom'] }}</td>
                                            <td>
                                                <a href="/admin/delete_product_image/{{ $img['img_id'] }}" class="btn btn-sm btn-danger">DELETE</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end-->

                    <div class="col-6">
                        <div class="card card-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <h4>Promotion Images List</h4>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#uploadPromoImagesModal">Upload</button>
                                    </div>

                                    <div class="modal fade" id="uploadPromoImagesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Promotion Image</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form role="form" action="/admin/add_product_images" method="post" enctype="multipart/form-data">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="img_zoom">Promotion Image</label>
                                                                <div class="input-group">
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="promotion_image" required>
                                                                        <label id="zoom_label" class="custom-file-label">Choose file</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <input type="text" value="{{ $details->f_idcode }}" name="item_code" hidden/>
                                                        <button type="submit" class="btn btn-primary">Upload</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <table class="table">
                                        <tr>
                                            <th></th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($promo_arr as $promo_img)
                                            <tr>
                                                <td style="width: 10%">
                                                    @if(Storage::disk('public')->exists('/item_images/'.$details->f_idcode.'/gallery/social/'))
                                                        <picture>
                                                            <source srcset="{!! asset('/storage/item_images/'.$details->f_idcode.'/gallery/social/'.explode(".", $promo_img['zoom'])[0] .'.webp') !!}" type="image/webp" class="img-responsive card-img-top" style="width: 100% !important;">
                                                            <source srcset="{!! asset('/storage/item_images/'.$details->f_idcode.'/gallery/social/'.$promo_img['zoom']) !!}" type="image/jpeg" class="img-responsive card-img-top" style="width: 100% !important;">
                                                            <div class="hover-container"><img src="{!! asset('/storage/item_images/'.$details->f_idcode.'/gallery/social/'.$promo_img['zoom']) !!}" alt="{{ Str::slug(explode(".",$promo_img['zoom'])[0], '-') }}" class="img-responsive card-img-top hover" style="width: 100% !important;"></div>
                                                        </picture>
                                                    @endif
                                                </td>
                                                <td style="width: 80%">{{ $promo_img['zoom'] }}</td>
                                                <td>
                                                    <a href="/admin/delete_product_image/{{ $promo_img['img_id'] }}/social" class="btn btn-sm btn-danger">DELETE</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=3 class="text-center">No Promotion Image(s)</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
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
    $(document).ready(function() {
        $('#img_zoom').change(function(){
            $('#zoom_label').text($('#img_zoom').val().slice(12));
        });

        $('#img_primary').change(function(){
            $('#primary_label').text($('#img_primary').val().slice(12));
        });

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").change(function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
	});
</script>
@endsection
