@extends('backend.layout', [
	'namePage' => 'Order Status Sequence',
	'activePage' => 'order_status_sequence'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Order Status</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Add Order Status Sequence</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-6">
						<div class="card card-primary">
							<div class="card-body">
                                @if(session()->has('success'))
                                    <div class="alert alert-success fade show" role="alert">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @if(session()->has('error'))
                                    <div class="alert alert-warning fade show" role="alert">
                                        {{ session()->get('error') }}
                                    </div>
                                @endif
                                <form action="/admin/order/sequence_list/add" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-9"><h4>Add Order Status Sequence</h4></div>
                                        <div class="col-3 text-right">
                                            <button class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Submit</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Shipping Method Name *</label>
                                            <input type="text" class="form-control" name="shipping_name" placeholder="Shipping Method Name" required>
                                        </div>
                                        <br/>&nbsp;
                                        <div class="col-12">
                                            <label>Order Status Sequence *</label>
                                            <ul id="sortable" class="p-0">
                                                @foreach ($order_status as $status)
                                                    <li class="ui-state-default border border-secondary card p-3">
                                                        <label><input type="checkbox" name="status[]" value="{{ $status->order_status_id }}">&nbsp;{{ $status->status }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </form>
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
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"></script>
<script>
    $( function() {
      $( "#sortable" ).sortable();
    } );
</script>
@endsection