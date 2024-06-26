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
					<h1 class="m-0">View Simple Product</h1>
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
         <form action="/admin/product/{{ $details->id }}/update" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
            
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
                     <div class="card-header">
                        <div class="d-flex flex-row align-items-center justify-content-between">
                           <div>
                              <h4 class="d-inline-block m-0">Product Information</h4>
                              <p class="p-0 m-0">Last Sync: {{ $details->last_sync_date ? Carbon\Carbon::parse($details->last_sync_date)->format('M. d, Y h:i A') : '-' }}</p>
                           </div>
                           <div>
                              <div class="dropdown d-inline-block mr-2">
                                 <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdowncreate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Create New Product
                                 </button>
                                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdowncreate" style="width: 100%;">
                                    <a class="dropdown-item" href="/admin/product/add/simple_product">Simple Product</a>
                                    <a class="dropdown-item" href="/admin/product/add/product_bundle">Product Bundle</a>
                                 </div>
                              </div>
                              <button type="submit" class="btn btn-primary mr-2">Update</button>
                           </div>
                        </div>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <div class="col-md-2">
                              @php
                                 $image = ($item_image) ? '/storage/item_images/'. $details->f_idcode .'/gallery/original/'.explode(".", $item_image)[0] .'.webp' : '/storage/no-photo-available.png';
                              @endphp
                              <img src="{{ asset($image) }}" class="img-responsive rounded img-thumbnail" alt="" width="250" height="250">
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
                                       <input type="text" class="form-control" id="product-price" value="{{ $details->f_default_price }}" readonly>
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
                                    <div class="form-group">
                                       <label><input type="checkbox" id="set_as_new_item" name="is_new_item" {{ $details->f_new_item == 1 ? 'checked' : '' }}> New on this duration</label>
                                       <div class="col-12">
                                          <input type="text" class="form-control" id="new_item_date" name="new_item_duration"/>
                                       </div>
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
                                       <input type="text" class="form-control" id="weight-uom" value="{{ $details->f_weight_uom }}" readonly>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="weight-per-unit">Weight per Unit</label>
                                       <input type="text" class="form-control" id="weight-per-unit" value="{{ $details->f_weight_per_unit }}" readonly>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label for="package-weight">Package Weight</label>
                                       <input type="text" class="form-control" id="package-weight" value="{{ $details->f_package_weight }}" readonly>
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
                                 <select class="form-control select2" name="warehouse" id="warehouse" style="width: 100%;" required></select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group mb-0">
                                 <label for="stock-qty">Stock Quantity (Actual Quantity)</label>
                                 <input type="number" class="form-control" id="stock-qty" name="stock_qty" value="{{ $details->f_qty }}"  {{ ($details->stock_source) ? 'readonly' : '' }} required>
                              </div>
                              <div class="form-check mt-1">
                                 <input type="checkbox" class="form-check-input" id="is-manual" name="is_manual" value="1" {{ ($details->stock_source) ? '' : 'checked' }}>
                                 <label class="form-check-label" for="is-manual">Manual input stocks (ERP stocks is not integrated)</label>
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
                           <textarea class="form-control" rows="6" id="website-caption" name="website_caption">{{ old('website_caption') }}{{ $details->f_caption }}</textarea>
                        </div>
                        <div class="form-group">
                           <label for="featured-image"><input type="checkbox" name="add_featured" id="add-featured" {{ $details->f_featured_image ? 'checked' : null }}> Featured Image (Optional)</label>
                           <div class="row">
                              @if ($details->f_featured_image)
                                 <div class="col-1">
                                    @php
                                       $img = $details->f_featured_image ? '/storage/item_images/'. $details->f_idcode .'/gallery/featured/'. $details->f_featured_image : '/storage/no-photo-available.png';
                                       $img_webp = $details->f_featured_image ? '/storage/item_images/'. $details->f_idcode .'/gallery/featured/'. explode(".", $details->f_featured_image)[0] .'.webp' : '/storage/no-photo-available.png';
                                    @endphp
                                    <picture>
                                       <source srcset="{{ asset($img_webp) }}" type="image/webp" class="img-responsive" style="width: 100% !important;">
                                       <source srcset="{{ asset($img) }}" type="image/jpeg" class="img-responsive" style="width: 100% !important;">
                                       <img src="{{ asset($img) }}" alt="{{ Str::slug(explode(".", $details->f_featured_image)[0], '-') }}" class="img-responsive" style="width: 100% !important;">
                                    </picture>
                                 </div>
                              @endif
                              <div class="col-{{ $details->f_featured_image ? '11' : '12' }}">
                                 <div class="custom-file mb-3">
                                    <input type="file" class="custom-file-input" id="customFile" name="featured_image">
                                    <label class="custom-file-label" for="customFile">Choose File</label>
                                 </div>
                                 <label for="featured">Selected Image: {{ $details->f_featured_image }}</label>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label for="full-detail">Full Details</label>
                           <textarea class="form-control" rows="6" id="full-detail" name="full_detail">{{ old('website_caption') }}{{ $details->f_full_description }}</textarea>
                        </div>
                        <h5>Product Specifications / Attributes</h5>
                        <hr>
                        <table class="table table-striped table-bordered" id="attributes-table">
                           <thead>
                              <tr>
                                 <th style="width: 5%;" class="text-center">No.</th>
                                 <th style="width: 50%;">Specification / Attribute Name</th>
                                 <th style="width: 45%;">Value</th>
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
                        <h5 class="mt-3">Related Product(s)</h5>
                        <hr>
                        <div class="float-left mb-2">
                           <button type="button" class="btn btn-primary btn-sm" id="show-srpm">Add Related Product(s)</button>
                        </div>
                        <table class="table table-bordered table-hover">
                           <thead>
                              <tr>
                                 <th style="width: 5%;" class="text-center">#</th>
                                 <th style="width: 10%;" class="text-center">Image</th>
                                 <th style="width: 55%;" class="text-center">Item Description</th>
                                 <th style="width: 15%;" class="text-center">Price</th>
                                 <th style="width: 15%;" class="text-center">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              @forelse ($related_products as $a => $related_product)
                              @php
                                 $image_r = ($related_product['image']) ? '/storage/item_images/'. $related_product['item_code'] .'/gallery/preview/'.$related_product['image'] : '/storage/no-photo-available.png';
                              @endphp
                              <tr>
                                 <td class="text-center align-middle">
                                    {{ $a + 1 }}
                                 </td>
                                 <td class="text-center align-middle">
                                    <img src="{{ asset($image_r) }}" class="img-responsive rounded img-thumbnail d-inline-block" width="70" height="70">
                                 </td>
                                 <td><span class="d-block font-weight-bold">{{ $related_product['item_code'] }}</span>{{ $related_product['item_description'] }}</td>
                                 <td class="text-center align-middle">
                                    ₱ {{ number_format(str_replace(",","",$related_product['original_price']), 2) }}
                                 </td>
                                 <td class="text-center align-middle">
                                    <button class="btn btn-danger btn-sm remove-rel" data-id="{{ $related_product['id'] }}">Remove</button>
                                 </td>
                              </tr>
                              @empty
                              <tr>
                                 <td colspan="5" class="text-center text-muted">No products found.</td>
                              </tr>
                              @endforelse
                           </tbody>
                        </table>
                        <h5 class="mt-3">Product(s) for Cross-sell</h5>
                        <hr>
                        <div class="col-12 mx-auto">
                           <select class="select-cross-sell w-100" name="selected_for_cross_sell[]" multiple="multiple">
                              @foreach ($cross_sell_arr as $cross_sell)
                                 <option selected value="{{ $cross_sell['cross_sell_item_code'] }}">{{$cross_sell['cross_sell_item_code'].' - '.$cross_sell['cross_sell_description'] }}</option>
                              @endforeach
                              @foreach ($products_for_cross_sell as $cs)
                                 <option value="{{ $cs->f_idcode }}">{{ $cs->f_idcode.' - '.$cs->f_name_name }}</option>
                              @endforeach
                           </select>
                        </div>
                        <br>
                        <h5 class="mt-3">Search Engine Optimization (SEO)</h5>
                        <hr>
                        <div class="form-group">
                           <label for="product-keywords">Meta Keywords</label>
                           <textarea class="form-control" rows="3" id="product-keywords" name="keywords">{{ old('keywords') }}{{ $details->keywords }}</textarea>
                         </div>
                         <div class="form-group">
                           <label for="product-url">URL Title</label>
                           <input type="text" class="form-control" id="product-url" name="url_title" value="{{ old('url_title') }}{{ $details->url_title }}">
                         </div>
                         <div class="form-group">
                           <label for="product-url">Slug</label>
                           <input type="text" class="form-control" id="product-url" name="slug" value="{{ old('slug') }}{{ $details->slug }}">
                         </div>
                         <div class="form-group">
                           @php
                               $alt = $details->image_alt ? $details->image_alt : Str::slug($details->f_item_name, '-');
                           @endphp
                           <label for="img-alt">Image Alt</label>
                           <input type="text" class="form-control" id="img-alt" name="alt" value="{{ old('alt') }}{{ $alt }}">
                         </div>
                         <div class="form-group">
                           <label for="product-meta-description">Meta Description</label>
                           <textarea class="form-control" rows="3" id="product-meta-description" name="meta_description">{{ old('meta_description') }}{{ $details->meta_description }}</textarea>
                         </div>
                         <div class="float-right font-italic">
                           <small>Last modified by: {{ $details->last_modified_by }} - {{ $details->last_modified_at }}</small><br>
                           <small>Created by: {{ $details->created_by }} - {{ $details->created_at }}</small>
                       </div>
                     </div>

                     <!-- /.card-body -->
                  </div>
               <!-- /.card -->
               </div>
            </div>
         </form>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>

{{-- modal related products --}}
 <div class="modal fade" id="related-products-modal">
   <div class="modal-dialog modal-xl" style="min-width: 80%;">
      <form action="/admin/product/{{ $details->f_idcode }}/save_related_products" method="POST">
      @csrf

     <div class="modal-content">
       <div class="modal-header">
         <h4 class="modal-title">Select Related Product(s)</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body"></div>
       <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Selected</button>
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
     </div>
   </form>
     <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
 </div>
 <!-- /.modal -->

 <div class="modal fade" id="remove-related-product" tabindex="-1" role="dialog" aria-labelledby="delItemModal" aria-hidden="true">
   <form action="#" method="POST">
      @csrf
      @method('delete')
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title" id="delItemModal">Remove Related Product</h5>
            </div>
            <div class="modal-body text-center">
               <p>Remove this item from related products?</p>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Confirm</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </form>
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
      setAsNewItem();
      addFeatured();
      $('.select-cross-sell').select2();

      $('#set_as_new_item').click(function(){
         setAsNewItem();
      });


      $('#is-manual').click(function(){
         if($(this).prop('checked')) {
            $('#stock-qty').removeAttr('readonly').attr('required', true);
         } else {
            $('#stock-qty').removeAttr('required').attr('readonly', true);
         }
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

      $('#show-srpm').click(function (e) {
         e.preventDefault();
         load_select_related_products();
      });

      $(document).on('click', '.remove-rel', function(e){
         e.preventDefault();
         var id = $(this).data('id');
         $('#remove-related-product form').attr('action', '/admin/product/remove_related/' + id);
         $('#remove-related-product').modal('show');
      });

      function load_select_related_products() {
         $('#custom-overlay').fadeIn();
         var data = {
            parent: '{{ $details->f_idcode }}'
         }
         $.ajax({
				url: '/admin/select_related_products/{{ $details->f_cat_id }}',
				type:"GET",
            data: data,
				success:function(data){
               $('#custom-overlay').fadeOut();
               $('#related-products-modal .modal-body').html(data);
               $('#related-products-modal').modal('show');
				},
				error : function(data) {
					alert('An error occured.');
				}
			});
      }

      function setAsNewItem(){
         if($('#set_as_new_item').is(':checked')){
               $("#new_item_date").prop('required',true);
               $("#new_item_date").slideDown('fast');
         }else{
               $("#new_item_date").prop('required',false);
               $("#new_item_date").slideUp('fast');
         }
      }
      // Is new Item date
      var start = "{{ $details->f_new_item_start ? date('m/d/Y', strtotime($details->f_new_item_start)) : '' }}";
      var end = "{{ $details->f_new_item_end ? date('m/d/Y', strtotime($details->f_new_item_end)) : '' }}";
      $('#new_item_date').daterangepicker({
            opens: 'left',
            placeholder: 'Select Date Range',
            startDate: start ? start : moment(),
            endDate: end ? end : moment().add(7, 'days'),
      });

      // Add the following code if you want the name of the file appear on select
      $(".custom-file-input").change(function() {
         var fileName = $(this).val().split("\\").pop();
         $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
      });

      $('#add-featured').click(function(){
         addFeatured();
      });

      function addFeatured(){
         if($('#add-featured').is(':checked')){
            $("#customFile").prop('disabled', false);
         }else{
            $("#customFile").prop('disabled', true);
         }
      }

      $('#warehouse').select2({
         placeholder: 'Search Warehouse',
         ajax: {
            url: '/admin/warehouse/search',
            method: 'GET',
            dataType: 'json',
            data: function (data) {
               return {
                  q: data.term, // search term
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

      var newOption = new Option('{{ $details->f_warehouse }}', '{{ $details->f_warehouse }}', false, false);
      $('#warehouse').append(newOption).trigger('change');
   })();
</script>
@endsection
