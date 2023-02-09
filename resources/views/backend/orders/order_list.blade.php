@extends('backend.layout', [
	'namePage' => 'Orders',
	'activePage' => 'Order List'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Order List</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Orders</li>
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
							<div class="card-body">
								<div class="container-fluid">
									<!-- Nav tabs -->
									<div class="row">
										<div class="col-9">
											<ul class="nav nav-tabs" id="tabs" role="tablist" style="cursor: pointer">
												@foreach ($status_arr as $status)
													<li class="nav-item">
														<a class="nav-link pt-3 pb-3 nav-ctrl {{ $loop->first ? 'active' : null }}" data-status="{{ $status }}" style="font-size: 13pt;"><b>{{ $status }}</b></a>
													</li>
												@endforeach
											</ul>
										</div>
										<div class="col-3 ">
											<input type="hidden" id="current-status" value="{{ $status }}">
											<input type="text" class="form-control" id="order-search" placeholder="Search" value="{{ session()->has("for_confirmation") ? session()->get("for_confirmation") : null }}">
										</div>
									</div>
									<div id="orders-container" class="container-fluid p-0"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>
	<style>
	.modal{
		background: rgba(0, 0, 0, .7);
	}
	.stat-label {
		height: 100%;
		padding: 0 10px;
		white-space: normal;
		word-break: break-word;
		display: flex;
		align-items: center;
	}
	.badge-completed{
		background-color: #fd6300 !important;
		color: #fff;
	}
	</style>
@endsection

@section('script')
@if (session()->has('for_confirmation'))
	<script>
		$(document).ready(function(){
			$('#order-{{ session()->get("for_confirmation") }}').modal('show');
		});
	</script>
@endif
<script>
	$(function () {
		bsCustomFileInput.init();

		$(document).on('change', '.img-upload-btn', function() {
			var img_div = $(this).data('id');
			const file1 = this.files[0];
			if (file1){
				let reader = new FileReader();
				reader.onload = function(event){
					$('#' + img_div).attr('src', event.target.result);
				}

				reader.readAsDataURL(file1);
			}
		});
		order_list('Order Placed', 1);
		$(document).on('click', '.nav-ctrl', function (){
			$('.nav-ctrl').removeClass('active');
			$('.nav-container').removeClass('active');

			$(this).addClass('active');
			$($(this).data('target')).addClass('active');

			$('#current-status').val($(this).data('status'));

			order_list($(this).data('status'), 1);
		});

		$(document).on('keyup', '#order-search', function (){
			order_list($('#current-status').val(), 1);
		});

		$(document).on('click', '#orders-table-pagination a', function(event){
            event.preventDefault();

            var page = $(this).attr('href').split('page=')[1];
			var status = $(this).closest('#orders-table-pagination').data('status');
			order_list(status, page);
        });

		function order_list(status, page){
			$.ajax({
				type:"GET",
				url:"/admin/order/list/" + status + '?page=' + page,
				data: {
					search_str: $('#order-search').val()
				},
				success:function(response){
					$('#orders-container').html(response);
				}
			});
		}

		$(document).on('click', '.view-order-btn', function(e){
			var id = $(this).data('so-status');
			var so = $(this).data('so');
			if (so) {
				$.ajax({
					type:"GET",
					url:"/admin/erp_sales_order_status/" + so,
					success:function(response){
						$(id).text(response.status);
						$(id).addClass(response.badge);
					}
				});
			}
		});
	});
</script>
@endsection