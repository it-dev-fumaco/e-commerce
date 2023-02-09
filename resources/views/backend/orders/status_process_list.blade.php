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
						<h1>Order Status Sequence</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Order Status Sequence List</li>
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
                                <div class="row">
                                    <div class="col-8">&nbsp;</div>
                                    <div class="col-4 text-right">
                                        <a href="/admin/order/sequence_list/add_form" class="btn btn-primary"><i class="fas fa-plus"></i> Add</a>
                                    </div>
                                </div>
                                <br/>
                                <div class="col-6">
                                    
                                </div>
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <th class="text-center align-middle">Shipping Method</th>
                                        <th class="text-center align-middle">Sequence</th>
                                        <th class="text-center align-middle">Action</th>
                                    </thead>
                                    @forelse ($sequence_arr as $shipping)
                                        <tr>
                                            <td class="text-center align-middle">{{ $shipping['shipping_method'] }}</td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#view{{ Str::slug($shipping['shipping_method'], '-') }}Modal">
                                                    <i class="fas fa-eye"></i> Sequence
                                                </button>
                                                {{--  --}}
                                                <!-- Modal -->
                                                <div class="modal fade" id="view{{ Str::slug($shipping['shipping_method'], '-') }}Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Order Sequence</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <center>
                                                                <table class="table table-hover table-bordered">
                                                                    <tr>
                                                                        <td>1</td>
                                                                        <td>Order Placed</td>
                                                                    </tr>
                                                                    @foreach ($shipping['status_sequence'] as $key => $sequence)
                                                                        <tr>
                                                                            <td>{{ $key + 2 }}</td>
                                                                            <td>{{ $sequence->status }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </table>
                                                            </center>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#dm{{ Str::slug($shipping['shipping_method'], '-') }}"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>                                          
                                          <!-- Modal -->
                                        <div class="modal fade" id="dm{{ Str::slug($shipping['shipping_method'], '-') }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-trash"></i> Delete</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Delete {{ $shipping['shipping_method'] }}?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                        <a href="/admin/order/sequence_list/{{ $shipping['shipping_method'] }}/delete" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan=4>No Order Status Sequences</td>
                                        </tr>
                                    @endforelse
                                </table>
                                <div class="float-right mt-4">
                                    {{ $shipping_method->withQueryString()->links('pagination::bootstrap-4') }}
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