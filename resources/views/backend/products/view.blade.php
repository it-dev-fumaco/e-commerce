@extends('backend.layout', [
	'namePage' => 'Products',
	'activePage' => 'product_list'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">View Product</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                     <li class="breadcrumb-item"><a href="/admin/product/list">Products</a></li>
						<li class="breadcrumb-item active">{{ $details->f_idcode }}</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
            <form action="/admin/product/{{ $details->id }}/update" method="POST">
               @csrf
               <!-- left column -->
               <div class="col-md-12">
                  @if(session()->has('success'))
                  <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                     {!! session()->get('success') !!}
                  </div>
                  @endif
                  @if(session()->has('error'))
                  <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                     {!! session()->get('error') !!}
                  </div>
                  @endif
                  @if(count($errors->all()) > 0)
                  <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">
                     @foreach ($errors->all() as $error)
                     <span class="d-block">{{ $error }}</span>
                     @endforeach 
                  </div>
                  @endif
                  <!-- general form elements -->
                  <div class="card">
                     <div class="card-body">
                        <h4 class="d-inline-block">Product Information</h4>
                        <div class="float-right">
                           <a href="/admin/product/add" class="btn btn-primary">Add Product</a>
                        </div>
                        <hr>
                        <div class="row">
                           <div class="col-md-2">
                              <img src="{{ asset('/item/images/'. $details->f_idcode .'/gallery/original/'.$item_image) }}" class="img-responsive rounded img-thumbnail" alt="" width="250" height="250">
                           </div>
                           <div class="col-md-4">
                              <div class="row">
                                 <div class="col-md-8">
                                    <div class="form-group">
                                       <label for="item-code">Item Code</label>
                                       <input type="text" class="form-control" id="item-code" value="{{ $details->f_idcode }}" readonly required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-check p-4">
                                       <input type="checkbox" class="form-check-input" id="disable-checkbox" name="is_disabled" {{ ($details->f_status == 0) ? 'checked' : '' }}>
                                       <label class="form-check-label" for="disable-checkbox">Disabled</label>
                                    </div>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label for="item-description">Description</label>
                                 <textarea rows="6" class="form-control" id="item-description" readonly>{{ $details->f_description }}</textarea>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="parent-item-code">Parent Item Code</label>
                                       <input type="text" class="form-control" id="parent-item-code" value="{{ $details->f_parent_code }}" readonly>
                                    </div>
                                    <div class="form-group">
                                       <label for="item-brand">Brand</label>
                                       <input type="text" class="form-control" id="item-brand" value="{{ $details->f_brand }}" readonly required>
                                    </div>
                                    <div class="form-group">
                                       <label for="product-price">Product Price</label>
                                       <input type="text" class="form-control" id="product-price" value="{{ $details->f_original_price }}" readonly>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="item-classification">Item Classification</label>
                                       <input type="text" class="form-control" id="item-classification" value="{{ $details->f_item_classification }}" readonly required>
                                    </div>
                                    <div class="form-group">
                                       <label for="stock-uom">Stock UoM</label>
                                       <input type="text" class="form-control" id="stock-uom" value="{{ $details->f_stock_uom }}" readonly required>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <h5>Product Weight & Dimensions</h5>
                        <hr>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="weight-uom">Weight UoM</label>
                                       <input type="text" class="form-control" id="weight-uom" value="{{ $details->f_weight_uom }}" readonly required>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="weight-per-unit">Weight per Unit</label>
                                       <input type="text" class="form-control" id="weight-per-unit" value="{{ $details->f_weight_per_unit }}" readonly required>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="package-weight">Package Weight</label>
                                       <input type="text" class="form-control" id="package-weight" value="{{ $details->f_package_weight }}" readonly required>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="package-length">Package Length</label>
                                       <input type="text" class="form-control" id="package-length" value="{{ $details->f_package_length }}" readonly required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="package-width">Package Width</label>
                                       <input type="text" class="form-control" id="package-width" value="{{ $details->f_package_width }}" readonly required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="package-height">Package Height</label>
                                       <input type="text" class="form-control" id="package-height" value="{{ $details->f_package_height }}" readonly required>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label for="package-dimension-uom">Package Dimension UoM</label>
                                       <input type="text" class="form-control" id="package-dimension-uom" value="{{ $details->f_package_d_uom }}" readonly required>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <h5>Inventory Details</h5>
                        <hr>
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="warehouse">Warehouse</label>
                                 <input type="text" class="form-control" id="warehouse" value="{{ $details->f_warehouse }}" readonly required>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="stock-qty">Stock Quantity (from website warehouse)</label>
                                 <input type="number" class="form-control" id="stock-qty" value="{{ $details->f_qty }}" readonly required>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="alert-qty">Min. Stock Level</label>
                                 <input type="number" class="form-control" id="alert-qty" name="alert_qty" value="{{ $details->f_alert_qty }}" required>
                              </div>
                           </div>
                        </div>
                        <h5>Website Product Details</h5>
                        <hr>
                        <div class="row">
                           <div class="col-md-8">
                              <div class="form-group">
                                 <label for="product-name">Product Name</label>
                                 <input type="text" class="form-control" id="product-name" name="product_name" value="{{ $details->f_name_name }}" required>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label for="product-category">Category</label>
                                 <select name="product_category" id="product-category" class="form-control" required>
                                    <option>Select Category</option>
                                    @forelse ($item_categories as $item_category)
                                    <option value="{{ $item_category->id }}" {{ ($details->f_cat_id == $item_category->id) ? 'selected' : '' }}>{{ $item_category->name }}</option>
                                    @empty
                                    <option>No categories found.</option>
                                    @endforelse
                                 </select>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="website-caption">Website Caption (more information section)</label>
                           <textarea class="form-control" rows="6" id="website-caption" name="website_caption">{{ old('website_caption') }}</textarea>
                        </div>
                        <div class="form-group">
                           <label for="full-detail">Full Detail</label>
                           <textarea class="form-control" rows="6" id="full-detail" name="full_detail">{{ old('full_detail') }}</textarea>
                        </div>
                        <h5>Product Specifications / Attributes</h5>
                        <hr>
                        <table class="table table-striped table-bordered" id="attributes-table">
                           <thead>
                              <tr>
                                 <th style="width: 10%;">No.</th>
                                 <th style="width: 50%;">Specification / Attribute Name</th>
                                 <th style="width: 40%;">Value</th>
                              </tr>
                           </thead>
                           <tbody>
                              @forelse ($attributes as $attr)
                              <tr>
                                 <td class="text-center">{{ $attr->idx }}</td>
                                 <td>{{ $attr->attribute_name }}</td>
                                 <td>{{ $attr->attribute_value }}</td>
                              </tr>
                              @empty
                              <tr>
                                 <td colspan="3" class="text-center text-muted">No product attributes found.</td>
                              </tr>
                              @endforelse
                           </tbody>
                        </table>
                     </div>
                     <!-- /.card-body -->
                     <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary btn-lg">UPDATE</button>
                     </div>
                  </div>
               <!-- /.card -->
               </div>
            </form>
			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>

 <div id="custom-overlay" style="display: none;">
  <div class="custom-spinner"></div>
  <br/>
  Loading...
</div>

<style>
  #custom-overlay {
  background: #ffffff;
  color: #666666;
  position: fixed;
  height: 100%;
  width: 100%;
  z-index: 5000;
  top: 0;
  left: 0;
  float: left;
  text-align: center;
  padding-top: 25%;
  opacity: .80;
}

.custom-spinner {
    margin: 0 auto;
    height: 64px;
    width: 64px;
    animation: rotate 0.8s infinite linear;
    border: 5px solid firebrick;
    border-right-color: transparent;
    border-radius: 50%;
}
@keyframes rotate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
@endsection

@section('script')
<script>
  (function() {
    $('#website-caption').summernote('code', '{!! $details->f_caption !!}');
    $('#full-detail').summernote('code', '{!! $details->f_full_description !!}');
    $("#website-caption").summernote({
			dialogsInBody: true,
			dialogsFade: true,
			height: "200px",
		});

    $("#full-detail").summernote({
			dialogsInBody: true,
			dialogsFade: true,
			height: "200px",
		});
  })();

</script>
@endsection