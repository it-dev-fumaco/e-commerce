@extends('backend.layout', [
	'namePage' => 'Dashboard',
	'activePage' => 'admin_dashboard'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
	  <div class="container-fluid">
		 <div class="row mb-2">
			<div class="col-sm-6">
			  <h1 class="m-0">Dashboard</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
			  <ol class="breadcrumb float-sm-right">
				 <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
				 <li class="breadcrumb-item active">Dashboard</li>
			  </ol>
			</div><!-- /.col -->
		 </div><!-- /.row -->
	  </div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
	  <div class="container-fluid">
		 <!-- Small boxes (Stat box) -->
		 <div class="row">
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-info">
				 <div class="inner">
					<h3>{{ $new_orders }}</h3>

					<p>New Order(s)</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-bag"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-success">
				 <div class="inner">
					<h3>{{ $total_orders }}</h3>

					<p>Total Order(s)</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-stats-bars"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-warning">
				 <div class="inner">
					<h3>{{ $users }}</h3>

					<p>User Registration(s)</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-person-add"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-6">
			  <!-- small box -->
			  <div class="small-box bg-danger">
				 <div class="inner">
					<h3>₱ {{ number_format($total_sales, 2, '.', ',') }}</h3>

					<p>Total Sales</p>
				 </div>
				 <div class="icon">
					<i class="ion ion-pie-graph"></i>
				 </div>
			  </div>
			</div>
			<!-- ./col -->
		 </div>
		 <!-- /.row -->
		 <!-- Main row -->
		 <div class="row">
			<!-- Left col -->
			<section class="col-6 connectedSortable">
			  <!-- Custom tabs (Charts with tabs)-->
			  <div class="card">
				 <div class="card-header">
					<h3 class="card-title">
					  <i class="fas fa-chart-pie mr-1"></i> Online Sales 
					</h3>
					<div class="card-tools">
						<ul class="nav nav-pills ml-auto">
							<li class="nav-item">
								{{-- <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Total Sales</a> --}}
								<form action="/admin/dashboard" method="get">
									<div class="btn-group col-12" role="group">
										<select name="year" class="form-control" required> 
											<option value="" disabled>Year</option>
											@foreach ($sales_year as $year)
												@php
													$selected = null;
													if(!request()->get('year')){
														if(\Carbon\Carbon::now()->format('Y') == $year->{'YEAR(order_date)'}){
															$selected = 'selected';
														}
													}else{
														if($year->{'YEAR(order_date)'} == request()->get('year')){
															$selected = 'selected';
														}
													}
												@endphp
												<option {{ $selected }} value="{{ $year->{'YEAR(order_date)'} }}">{{ $year->{'YEAR(order_date)'} }}</option>
											@endforeach
										</select>
										<button type="submit" class="btn btn-primary btn-sm pl-3 pr-2"><i class="fas fa-search mr-1"></i></button>
									</div>
								</form>
							</li>
						</ul>
					</div>
				 </div><!-- /.card-header -->
				 <div class="card-body">
					<div class="tab-content p-0">
					  	<canvas id="myChart" style="width:100%;"></canvas>
					</div>
				 </div><!-- /.card-body -->
			  </div>
			  <!-- /.card -->
			</section>
			<!-- /.Left col -->

			<div class="col-6">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							<i class="fas fa-search mr-1"></i> Most Searched Terms 
						</h3>
					</div>
					<div class="card-body">
						<table class="table table-hover table-bordered">
							<tr>
								<th>Search Term</th>
								<th>Frequency</th>
							</tr>
							@foreach ($most_searched as $search)
								<tr>
									<td>{{ $search->search_term }}</td>
									<td>{{ $search->frequency }}</td>
								</tr>
							@endforeach
						</table>
					</div>
				</div>
			</div>
		  
		 </div>
		 <!-- /.row (main row) -->
	  </div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>
@endsection
@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

<script>
	var xValues = ["January", "February", 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var yValues = [{{ collect($sales_arr)->pluck('sales')->implode(',') }}];
	new Chart("myChart", {
		type: "line",
		data: {
			labels: xValues,
			datasets: [{
			backgroundColor: "rgba(0,123,255,0)",
			borderColor: "rgba(0,123,255,1)",
			data: yValues
			}]
		},
		options: {
			legend: {display: false},
			title: {
				display: true,
				text: "Monthly Sales Report"
			},
			tooltips: {
				callbacks: {
					label: function(tooltipItem) {
						return "₱ " + Number(tooltipItem.yLabel).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
					}
				}
			}
		}
	});
</script>
@endsection