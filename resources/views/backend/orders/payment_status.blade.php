@extends('backend.layout', [
	'namePage' => 'Payment Status',
	'activePage' => 'payment_status_list'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Payment Status</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Payment Status List</li>
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
                                        <a href="/admin/payment/status/add/form" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
                                    </div>
                                </div>
                                <br/>
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <th class="text-center align-middle">Status ID</th>
                                        <th class="text-center align-middle">Status</th>
                                        <th class="text-center align-middle">Status Description</th>
                                        <th class="text-center align-middle">Updates Order Status</th>
                                        <th class="text-center align-middle">Action</th>
                                    </thead>
                                    @forelse ($status_list as $status)
                                        <tr>
                                            <td class="text-center align-middle">{{ $status->id }}</td>
                                            <td class="text-center align-middle">{{ $status->status }}</td>
                                            <td class="text-justify align-middle">{{ $status->status_description }}</td>
                                            <td class="text-center align-middle">{{ $status->updates_status ? 'Yes' : 'No' }}</td>
                                            <td class="text-center align-middle">
                                                <a href="/admin/payment/status/{{ $status->id }}/edit/form" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                                                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#dm{{ $status->id }}"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                          <!-- Modal -->
                                        <div class="modal fade" id="dm{{ $status->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-trash"></i> Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Delete {{ $status->status }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                        <a href="/admin/payment/status/{{ $status->id }}/delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan=5>No Payment Status(es)</td>
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