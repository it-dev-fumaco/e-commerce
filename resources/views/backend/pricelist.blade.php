@extends('backend.layout', [
'namePage' => 'Price List',
'activePage' => 'pricelist'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Price List</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Price List</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12 col-lg-9">
						<div class="card">
							<div class="card-body">
                                <div class="alert text-center d-none" id="alert-box"></div>
								@if(session()->has('success'))
								<div class="alert alert-success text-center">
									{{ session()->get('success') }}
								</div>
								@endif
								@if(session()->has('error'))
								<div class="alert alert-danger text-center">
									{{ session()->get('error') }}
								</div>
								@endif
                                @if(count($errors->all()) > 0)
                                <div class="alert alert-warning text-center">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach 
                                </div>
                                @endif
                                <form action="/admin/price_list" method="GET">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group row">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-info"><i class="fas fa-search"></i> Search</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="float-right">
                                                <button class="btn btn-warning" type="button" id="sync-prices-btn"><i class="fa fa-sync"></i>&nbsp; Sync Prices</button>
                                                <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#add-price-list"><i class="fa fa-plus"></i>&nbsp; Add Price List</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <th class="text-center" style="width: 8%;">ID</th>
                                        <th class="text-center" style="width: 15%;">Customer Group</th>
                                        <th class="text-center" style="width: 20%;">Price List</th>
                                        <th class="text-center" style="width: 20%;">Created by</th>
                                        <th class="text-center" style="width: 20%;">Date</th>
                                        <th class="text-center" style="width: 17%;">Action</th>
                                    </thead>
                                    <tbody>
                                        @forelse ($price_list as $row)
                                        <tr>
                                            <td class="text-center">{{ $row->id }}</td>
                                            <td class="text-center">{{ $row->customer_group_name }}</td>
                                            <td class="text-center">{{ $row->price_list_name }}</td>
                                            <td class="text-center">{{ $row->last_modified_by }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($row->last_modified_at)->format('M d, Y - h:i A') }}</td>
                                            <td class="text-center">
                                                <a href="/admin/item_prices/{{ $row->id }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                                                <button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#delete{{ $row->id }}"><i class="fa fa-trash"></i></button>
                                                <div class="modal fade" id="delete{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="disablemodal" aria-hidden="true">
                                                    <form action="/admin/price_list/delete/{{ $row->id }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"><i class="fas fa-trash"></i> Delete Price List</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Delete <b>{{ $row->price_list_name }}</b>?</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No records found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="float-right mt-3">
                                    {{ $price_list->withQueryString()->links('pagination::bootstrap-4') }}
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div> 

<div class="modal fade" id="add-price-list" tabindex="-1" role="dialog" aria-labelledby="disablemodal" aria-hidden="true">
    <form action="/admin/price_list/create" method="POST">
        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Price List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="customer-group">Select Customer Group</label>
                        <select name="customer_group" class="form-control" id="customer-group">
                            @foreach ($customer_groups as $customer_group)
                            <option value="{{ $customer_group->id }}">{{ $customer_group->customer_group_name }}</option>
                            @endforeach
                        </select>
                    </div>
					<div class="form-group">
                        <label for="customer-group">Select Price List</label>
						<select name="pricelist" id="search-price-list" class="form-control select2 w-100" required></select>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>


<div id="custom-overlay" style="display: none;">
    <div class="custom-spinner"></div>
    <br/>
    Syncing...
  </div>
  
  <style>
    #custom-overlay {
    background: #ffffff;
    color: #666666;
    position: fixed;
    height: 100%;
    width: 100%;
    z-index: 5000;
    top: 0;
    left: 0;
    float: left;
    text-align: center;
    padding-top: 25%;
    opacity: .80;
  }
  
  .custom-spinner {
      margin: 0 auto;
      height: 64px;
      width: 64px;
      animation: rotate 0.8s infinite linear;
      border: 5px solid firebrick;
      border-right-color: transparent;
      border-radius: 50%;
  }
  @keyframes rotate {
      0% {
          transform: rotate(0deg);
      }
      100% {
          transform: rotate(360deg);
      }
  }
  </style>
@endsection
@section('script')
<script>
    (function() {
        $('#search-price-list').select2({
            dropdownParent: $('#add-price-list'),
            placeholder: 'Select Price List',
            ajax: {
            url: '/admin/get_price_list',
            method: 'GET',
            dataType: 'json',
            data: function (data) {
                return {
                    q: data.term, // search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            },
            error: function () {
                console.log('An error occured.');
            },
            cache: true
            }
        });

        $('#sync-prices-btn').click(function(e){
            e.preventDefault();
            $('#custom-overlay').fadeIn();
            $.ajax({
                url: '/admin/sync_price_list',
                type:"GET",
                success: function(response) {
                    $('#custom-overlay').fadeOut();
                    $('#alert-box').removeClass('d-none alert-danger').addClass('alert-success').text(response.message);
                },
                error : function(data) {
                    $('#custom-overlay').fadeOut();
                    $('#alert-box').removeClass('d-none alert-success').addClass('alert-danger').text('An error occured. Please try again.');
                }
            });
        });
    })();
</script>
@endsection