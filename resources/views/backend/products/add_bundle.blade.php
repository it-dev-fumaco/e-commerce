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
					<h1 class="m-0">Create New Product Bundle</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/product/list">Products</a></li>
						<li class="breadcrumb-item active">Create New Product Bundle</li>
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
        <form action="/admin/product/save" method="POST">
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
            <input type="hidden" name="item_type" value="product_bundle" id="item-type">
            <!-- general form elements -->
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <h4>Get Product Code From ERP <span class="float-right" id="item-code-text"></span></h4>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="search-item-code">Search Item Code</label>
                      <select class="form-control select2" id="search-item-code" style="width: 100%;" required></select>
                    </div>
                    <div class="form-group">
                      <label>Description</label>
                      <p id="item-description-text">-</p>
                    </div>
                  </div>

                  <input type="hidden" id="item-code" name="item_code" value="{{ old('item_code') }}">
                  <input type="hidden" id="item-name" name="item_name" value="{{ old('item_name') }}">
                  <textarea id="item-description" name="item_description" readonly hidden>{{ old('item_description') }}</textarea>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="item-brand">* Brand</label>
                          <input type="text" class="form-control" id="item-brand" name="brand" value="{{ old('brand') }}" readonly required>
                        </div>
                        <div class="form-group">
                          <label for="product-price">Product Price (ERP Website Price List)</label>
                          <input type="text" class="form-control" id="product-price" name="price" value="{{ old('price') }}" readonly required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="item-classification">Item Classification</label>
                          <input type="text" class="form-control" id="item-classification" name="item_classification" value="{{ old('item_classification') }}" readonly required>
                        </div>
                        <div class="form-group">
                          <label for="stock-uom">Stock UoM</label>
                          <input type="text" class="form-control" id="stock-uom" name="stock_uom" value="{{ old('stock_uom') }}" readonly required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <h5>Product Bundle</h5>
                <hr>
                <table class="table table-striped table-bordered" id="bundle-table">
                  <thead>
                    <tr>
                      <th style="width: 5%;" class="text-center">No.</th>
                      <th style="width: 85;">Item Description</th>
                      <th style="width: 10%;" class="text-center">Quantity</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
                <br>
                <h5>Product Weight & Dimensions</h5>
                <hr>
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="weight-uom">* Weight UoM</label>
                          <input type="text" class="form-control" id="weight-uom" name="weight_uom" value="{{ old('weight_uom') ? old('weight_uom') : 'Kg' }}" readonly>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="weight-per-unit">* Weight per Unit</label>
                          <input type="text" class="form-control" id="weight-per-unit" name="weight_per_unit" value="{{ old('weight_per_unit') }}">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="package-weight">* Package Weight</label>
                          <input type="text" class="form-control" id="package-weight" name="package_weight" value="{{ old('package_weight') }}">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="package-length">* Package Length</label>
                          <input type="text" class="form-control" id="package-length" name="package_length" value="{{ old('package_length') }}" readonly required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="package-width">* Package Width</label>
                          <input type="text" class="form-control" id="package-width" name="package_width" value="{{ old('package_width') }}" readonly required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="package-height">* Package Height</label>
                          <input type="text" class="form-control" id="package-height" name="package_height" value="{{ old('package_height') }}" readonly required>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="package-dimension-uom">* Package Dimension UoM</label>
                          <input type="text" class="form-control" id="package-dimension-uom" name="package_dimension_uom" value="{{ old('package_dimension_uom') }}" readonly required>
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
                      <label for="warehouse">* Warehouse</label>
                      <input type="text" class="form-control" id="warehouse" name="warehouse" value="{{ old('warehouse') }}" readonly required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group mb-0">
                      <label for="stock-qty"> * Bundle Stock Quantity (Actual Quantity)</label>
                      <input type="number" class="form-control" id="stock-qty" name="stock_qty" value="{{ old('stock_qty') ? old('stock_qty') : 0 }}" min="0" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="alert-qty">* Min. Stock Level</label>
                      <input type="number" class="form-control" id="alert-qty" name="alert_qty" value="{{ old('alert_qty') ? old('alert_qty') : 0 }}" min="0" required>
                    </div>
                  </div>
                </div>
                <h5>Website Product Details</h5>
                <hr>
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="product-name">* Product Name</label>
                      <input type="text" class="form-control" id="product-name" name="product_name" value="{{ old('product_name') }}" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="product-category">* Category</label>
                      <select name="product_category" id="product-category" class="form-control" required>
                        <option value="">Select Category</option>
                        @forelse ($item_categories as $item_category)
                        <option value="{{ $item_category->id }}">{{ $item_category->name }}</option>
                        @empty
                        <option>No categories found.</option>
                        @endforelse
                      </select>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="website-caption">* Website Caption (more information section)</label>
                  <textarea class="form-control" rows="6" id="website-caption" name="website_caption">{{ old('website_caption') }}</textarea>
                </div>
                <div class="form-group">
                  <label for="full-detail">Full Detail</label>
                  <textarea class="form-control" rows="6" id="full-detail" name="full_detail">{{ old('full_detail') }}</textarea>
                </div>
                <h5 class="mt-3">Search Engine Optimization (SEO)</h5>
                <hr>
                <div class="form-group">
                  <label for="product-keywords">Meta Keywords</label>
                  <textarea class="form-control" rows="3" id="product-keywords" name="keywords">{{ old('keywords') }}</textarea>
                </div>
                <div class="form-group">
                  <label for="product-url">* URL Title</label>
                  <input type="text" class="form-control" id="product-url" name="url_title" value="{{ old('url_title') }}">
                </div>
                <div class="form-group">
                  <label for="product-url">* Slug</label>
                  <input type="text" class="form-control" id="product-slug" name="slug" value="{{ old('slug') }}">
                </div>
                <div class="form-group">
                  <label for="product-meta-description">Meta Description</label>
                  <textarea class="form-control" rows="3" id="product-meta-description" name="meta_description">{{ old('meta_description') }}</textarea>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
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
    $('#is-manual').click(function(){
      if($(this).prop('checked')) {
        $('#stock-qty').removeAttr('readonly').attr('required', true);
      } else {
        $('#stock-qty').removeAttr('required').attr('readonly', true);
      }
    });

    $('#search-item-code').select2({
      placeholder: 'Search Item',
      ajax: {
        url: '/admin/product/search',
        method: 'GET',
        dataType: 'json',
        data: function (data) {
          return {
            q: data.term, // search term
            item_type: $('#item-type').val()
          };
        },
        processResults: function (response) {
          return {
            results: response
          };
        },
        error: function () {
          console.log('An error occured.');
        },
        cache: true
      }
    });

    $(document).on('select2:select', '#search-item-code', function(e){
      var data = e.params.data;
      $('#custom-overlay').fadeIn();
      $('#bundle-table tbody').empty();
      $.ajax({
        type:"GET",
        url:"/admin/product/" + data.id + '/' + $('#item-type').val(),
        success:function(response){
          // if status = 0 (error)
          if (response.status || response.status === 0) {
            console.log('An error occured.');
          } else {
            $('#item-code').val(response.item_code);
            $('#item-code-text').text(response.item_code);
            $('#item-name').val(response.item_name);
            $('#item-classification').val(response.item_classification);
            $('#item-brand').val(response.brand);
            $('#stock-uom').val(response.stock_uom);
            $('#warehouse').val(response.warehouse);
            $('#item-description').text(response.item_description);
            $('#item-description-text').text(response.item_description);
            $('#stock-qty').val(response.stock_qty);
            $('#product-price').val(response.item_price);
            if (response.weight_uom) {
              $('#weight-uom').val(response.weight_uom);
            }
            $('#weight-per-unit').val(response.weight_per_unit);
            $('#package-weight').val(response.package_weight);
            $('#package-length').val(response.package_length);
            $('#package-width').val(response.package_width);
            $('#package-height').val(response.package_height);
            $('#package-dimension-uom').val(response.package_dimension_uom);
            $('#product-name').val(response.product_name);
            $('#full-detail').summernote('code', response.web_long_description);

            var bundle = '';
            $(response.bundle_items).each(function(i, d) {
              bundle += '<tr><td class="text-center">' + d.idx + '</td><td><b>' + d.item_code + '</b> - ' + d.description + '</td><td class="text-center">' + d.qty + ' ' + d.uom + '</td></tr>';
            });

            $('#bundle-table tbody').append(bundle);
          }

          $('#custom-overlay').fadeOut();
        }
      });
    });

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
