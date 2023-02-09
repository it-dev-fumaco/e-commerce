@extends('backend.layout', [
	'namePage' => 'Items on Cart',
	'activePage' => 'items_on_cart'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Items on Cart Report</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Items on Cart Report</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		
		<section class="content">
			<div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                      <!-- Custom Tabs -->
                      <div class="card">
                        <div class="card-header d-flex p-0">
                          <ul class="nav nav-pills p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Items on Cart</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Abandoned Items on Cart</a></li>
                          </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                          <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">
                                    <div class="col-md-6 pr-3" id="iocl"></div>
                                    <div class="col-md-6 pl-3" id="ioci"></div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group mb-3">
                                                    <input type="text" class="form-control" name="search" aria-describedby="button-addon2" placeholder="Search" id="search-abandoned">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-info" type="button" id="search-btn"><i class="fas fa-search"></i> Search</button>
                                            </div>
                                        </div>
                                        <div id="aioc"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                          </div>
                          <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                      </div>
                      <!-- ./card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('script')
<script>
    (function() {
        loadIocl();
        loadIoci();
        loadAio();

        $('#search-btn').click(function(e){
            e.preventDefault();
            loadAio();
        });
		function loadIocl(page) {
            $.ajax({
                type:'GET',
                url:'/admin/items_on_cart_by_location?page=' + page,
                success: function (response) {
                    $('#iocl').html(response);
                }
            });
        }

        function loadIoci(page) {
            $.ajax({
                type:'GET',
                url:'/admin/items_on_cart_by_item?page=' + page,
                success: function (response) {
                    $('#ioci').html(response);
                }
            });
        }

        function loadAio(page) {
            var q = $('#search-abandoned').val();
            $.ajax({
                type:'GET',
                url:'/admin/abandoned_items_on_cart',
                data: {page, q},
                success: function (response) {
                    $('#aioc').html(response);
                }
            });
        }

        $(document).on('click', '#iocbi-paginate a', function(event){
            event.preventDefault(); 
            var page = $(this).attr('href').split('page=')[1];
            loadIoci(page);
        });

        $(document).on('click', '#iocbl-paginate a', function(event){
            event.preventDefault(); 
            var page = $(this).attr('href').split('page=')[1];
            loadIocl(page);
        });

        $(document).on('click', '#abandoned-cart-paginate a', function(event){
            event.preventDefault(); 
            var page = $(this).attr('href').split('page=')[1];
            loadAio(page);
        });
  	})();
</script>
@endsection