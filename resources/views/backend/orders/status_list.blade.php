@extends('backend.layout', [
	'namePage' => 'Order Status',
	'activePage' => 'order_status'
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
							<li class="breadcrumb-item active">Order Status List</li>
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
                                <div class="row">
                                    <div class="col-8">&nbsp;</div>
                                    <div class="col-4 text-right">
                                        <a href="/admin/order/status/add_form" class="btn btn-primary">Add</a>
                                    </div>
                                </div>
                                <br/>
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Status ID</td>
                                        <td>Status</td>
                                        <td>Status Description</td>
                                        <td>Updates Stock</td>
                                        <td>Action</td>
                                    </tr>
                                    @forelse ($status_list as $status)
                                        <tr>
                                            <td>{{ $status->order_status_id }}</td>
                                            <td>{{ $status->status }}</td>
                                            <td>{{ $status->status_description }}</td>
                                            <td>{{ $status->update_stocks ? 'Yes' : 'No' }}</td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                      <a class="dropdown-item" href="/admin/order/status/{{ $status->order_status_id }}/edit_form">View Details</a>
                                                      <a class="dropdown-item" href="#" data-toggle="modal" data-target="#dm{{ $status->order_status_id }}"><small>Delete</small></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>                                          
                                          <!-- Modal -->
                                        <div class="modal fade" id="dm{{ $status->order_status_id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Delete {{ $status->status }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <a href="/admin/order/status/{{ $status->order_status_id }}/delete" class="btn btn-danger">Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan=4>No Order Status(es)</td>
                                        </tr>
                                    @endforelse
                                </table>
                                <div class="float-right mt-4">
                                    {{ $status_list->withQueryString()->links('pagination::bootstrap-4') }}
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