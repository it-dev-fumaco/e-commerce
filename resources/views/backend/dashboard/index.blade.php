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
					<h3>0</h3>

					<p>Web Site Visitors</p>
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
			<section class="col-lg-12 connectedSortable">
			  <!-- Custom tabs (Charts with tabs)-->
			  <div class="card">
				 <div class="card-header">
					<h3 class="card-title">
					  <i class="fas fa-chart-pie mr-1"></i> Online Sales 
					</h3>
					<div class="card-tools">
					  <ul class="nav nav-pills ml-auto">
						 <li class="nav-item">
							<a class="nav-link active" href="#revenue-chart" data-toggle="tab">Total Sales</a>
						 </li>
					  </ul>
					</div>
				 </div><!-- /.card-header -->
				 <div class="card-body">
					<div class="tab-content p-0">
					  <!-- Morris chart - Sales -->
					  <div class="chart tab-pane active" id="revenue-chart"
							 style="position: relative; height: 300px;">
							<canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
						</div>
					  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
						 <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
					  </div>
					</div>
				 </div><!-- /.card-body -->
			  </div>
			  <!-- /.card -->
			</section>
			<!-- /.Left col -->
		  
		 </div>
		 <!-- /.row (main row) -->
	  </div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>
@endsection