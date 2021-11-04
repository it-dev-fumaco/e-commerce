@extends('backend.layout', [
	'namePage' => 'Payment Status',
	'activePage' => 'payment_status'
])

@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0">Check Payment Status</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
						<li class="breadcrumb-item active">Check Payment Status</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->

	<!-- Main content -->
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<!-- left column -->
				<div class="col-md-4">
					@if(session()->has('success'))
						<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
							{{ session()->get('success') }}
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
								{{ $error }}
							@endforeach 
						</div>
					@endif
					<!-- general form elements -->
					<div class="card card-primary">
						<!-- form start -->
						<form action="/admin/order/payment_status" method="POST" autocomplete="off">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="payment-id">Enter Payment Reference ID</label>
									<input type="text" class="form-control" id="payment-id" name="payment_id" value="{{ $payment_id }}" required>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</form>
					</div>
				<!-- /.card -->
				</div>
                @if ($output)
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">Payment Details</h5>
                            <dl class="row">
                                <dt class="col-sm-3">Payment ID</dt>
                                <dd class="col-sm-9">{{ $output['PaymentID'] }}</dd>

                                <dt class="col-sm-3">Order No.</dt>
                                <dd class="col-sm-9">{{ $output['OrderNumber'] }}</dd>
                              
                                <dt class="col-sm-3">Payment Method</dt>
                                <dd class="col-sm-9">{{ $details->order_payment_method }}</dd>
                              
                                <dt class="col-sm-3">Amount</dt>
                                <dd class="col-sm-9">{{ $output['CurrencyCode']  . ' ' . number_format($output['Amount'], 2, ".", ",") }}</dd>
                              
                                <dt class="col-sm-3">Issuing Bank</dt>
                                <dd class="col-sm-9">{{ $output['IssuingBank'] }}</dd>

                                <dt class="col-sm-3">Response Time</dt>
                                <dd class="col-sm-9">{{ $output['RespTime'] }}</dd>

                                @php
                                    $txnstatus = $output['TxnStatus'];
                                    switch ($txnstatus) {
                                        case 0:
                                            $status = 'Transaction Successful';
                                            break;
                                        case 1:
                                            $status = 'Transaction Failed';
                                            break;
                                        case 2:
                                            $status = 'Sale Pending';
                                            break;
                                        case 10:
                                            $status = 'Transaction Refunded';
                                            break;
                                        case 15:
                                            $status = 'Transaction Authorized';
                                            break;
                                        case 16:
                                            $status = 'Transaction Captured';
                                            break;
                                        case 31:
                                            $status = 'Reversal Pending';
                                            break;
                                        case 9:
                                            $status = 'Transaction Reversed';
                                            break;
                                        case -1:
                                            $status = 'Transaction not exists / not found';
                                            break;
                                        default:
                                            $status = 'Internal sustem error';
                                            break;
                                    }
                                @endphp

                                <dt class="col-sm-3">Status</dt>
                                <dd class="col-sm-9">{{ $status }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>  
                @endif
			</div>
			<!-- /.row -->
		</div><!-- /.container-fluid -->
	</section>
	<!-- /.content -->
 </div>



@endsection