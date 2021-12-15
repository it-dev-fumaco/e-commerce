@extends('backend.layout', [
'namePage' => $details->price_list_name,
'activePage' => 'pricelist'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>{{ $details->price_list_name }}</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item"><a href="/admin/price_list">Item Price List</a></li>
                            <li class="breadcrumb-item active">{{ $details->price_list_name }}</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-8">
						<div class="card">
							<div class="card-body">
                                <form action="/admin/item_prices/{{ $details->id }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group row">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-secondary">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <th class="text-center" style="width: 10%;">ID</th>
                                        <th class="text-center" style="width: 70%;">Item Descrption</th>
                                        <th class="text-center" style="width: 20%;">Price</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($list as $row)
                                        <tr>
                                            <td class="text-center">{{ $row->id }}</td>
                                            <td class="text-left"><b>{{ $row->item_code }}</b> - {{ $row->f_name_name }}</td>
                                            <td class="text-center">₱ {{ number_format(str_replace(",","",$row->price), 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No records found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="float-right mt-3">
                                    {{ $list->withQueryString()->links('pagination::bootstrap-4') }}
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