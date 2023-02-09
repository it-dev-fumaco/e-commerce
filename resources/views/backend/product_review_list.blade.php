@extends('backend.layout', [
'namePage' => 'Product Review List',
'activePage' => 'product_reviews'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Product Review List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Product Reviews</li>
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
                                    <form action="/admin/product/reviews" class="text-left" method="GET">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group row">
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="{{request()->get('q')}}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <th class="text-center" style="width: 5%;">ID</th>
                                            <th class="text-center" style="width: 25%;">Item Code</th>
                                            <th class="text-center" style="width: 10%;">Rating</th>
                                            <th class="text-center" style="width: 18%;">Message</th>
                                            <th class="text-center" style="width: 12%;">User Account</th>
                                            <th class="text-center" style="width: 13%;">Date</th>
                                            <th class="text-center" style="width: 10%;">Show in Website</th>
                                            <th class="text-center" style="width: 7%;">Action</th>
                                        </thead>
                                        @forelse ($list as $row)
                                        <tr>
                                            <td class="text-center">{{ $row->rid }}</td>
                                            <td class="text-left"><span class="d-block font-weight-bold">{{ $row->item_code }}</span>{{ $row->f_name_name }}</td>
                                            <td class="text-center">
                                                @for ($i = 0; $i < 5; $i++)
                                                @if ($row->rating <= $i)
                                                <span class="fa fa-star starcolorgrey" style="color:  #d6dbdf ;"></span>
                                                @else
                                                <span class="fa fa-star" style="color: #FFD600;"></span>
                                                @endif
                                                @endfor
                                            </td>
                                            <td class="text-left">{{Str::limit($row->message, 50, $end='...')}}</td>
                                            <td class="text-center">{{ $row->user_email }}</td>
                                            <td class="text-center">{{ \Carbon\Carbon::parse($row->review_date)->format('M d, Y - h:i A') }}</td>
                                            <td class="text-center">
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input toggle" id="toggle_{{ $row->rid }}" {{ ($row->status == 'approved') ? 'checked' : '' }} value="{{ $row->rid }}">
                                                        <label class="custom-control-label" for="toggle_{{ $row->rid }}"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#v{{ $row->rid }}"><i class="fa fa-eye"></i></a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="v{{ $row->rid }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Product Review Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <dl class="row">
                                                                <dt class="col-sm-3 text-right">Item Description</dt>
                                                                <dd class="col-sm-9"><span class="d-block font-weight-bold">{{ $row->item_code }}</span>{{ $row->f_name_name }}</dd>
                                                                <dt class="col-sm-3 text-right">Rating</dt>
                                                                <dd class="col-sm-9">
                                                                    @for ($i = 0; $i < 5; $i++)
                                                                    @if ($row->rating <= $i)
                                                                    <span class="fa fa-star starcolorgrey" style="color: #d6dbdf;"></span>
                                                                    @else
                                                                    <span class="fa fa-star" style="color: #FFD600;"></span>
                                                                    @endif
                                                                    @endfor
                                                                </dd>
                                                                <dt class="col-sm-3 text-right">Message</dt>
                                                                <dd class="col-sm-9">{{ $row->message }}</dd>
                                                                <dt class="col-sm-3 text-right">User Account</dt>
                                                                <dd class="col-sm-9">{{ $row->user_email }}</dd>
                                                                <dt class="col-sm-3 text-right">Date Reviewed</dt>
                                                                <dd class="col-sm-9">{{ \Carbon\Carbon::parse($row->review_date)->format('M d, Y - h:i A') }}</dd>
                                                            </dl>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-uppercase text-muted">No records found</td>
                                        </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
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

@section('script')
 	<script>
		$(document).on('change', '.toggle', function(e){
			e.preventDefault();
			$.ajax({
				type:'get',
				url:'/admin/product/toggle/' + $(this).val(),
			});
		});
	</script>
@endsection
