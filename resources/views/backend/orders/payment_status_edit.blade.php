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
							<li class="breadcrumb-item active">Edit Payment Status</li>
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
                                <form action="/admin/payment/status/{{ $status->id }}/edit" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-9"><h4>Edit Payment Status</h4></div>
                                        <div class="col-3 text-right">
                                            <button class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Status Name *</label>
                                            <input type="text" class="form-control" name="status_name" value="{{ $status->status }}" placeholder="Status Name" required>
                                        </div>
                                        <br/>&nbsp;
                                        <div class="col-12">
                                            <label>Status Description *</label>
                                            <input type="text" class="form-control" name="status_description" placeholder="Status Description" value="{{ $status->status_description }}" required>
                                        </div>
                                        <br/>&nbsp;
                                        <div class="col-12">
                                            <label><input type="checkbox" name="updates_status" {{ $status->updates_status == 1 ? 'checked' : null }}> Updates Payment Status</label>
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