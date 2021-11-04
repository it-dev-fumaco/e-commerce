@extends('backend.layout', [
'namePage' => 'Products Categories',
'activePage' => 'product_category'
])

@section('content')
<div class="wrapper">
	<div class="content-wrapper">
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1>Categories List Page</h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
							<li class="breadcrumb-item active">Categories List Page</li>
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
							<div class="card-header">
								<h3 class="card-title">List Category</h3>
							</div>
							<div class="card-body">
								@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
								@endif
								@if(session()->has('error'))
								<div class="alert alert-danger">
									{{ session()->get('error') }}
								</div>
								@endif
								<table id="example2" data-pagination="true" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>Code</th>
											<th>Name</th>
											<th>image</th>
											<th>slug</th>
											<th>Action</th>
											<th class="text-center">Publish</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($categories as $c)
										<tr>
											<td>{{ $c->id }}</td>
											<td>{{ $c->name }}</td>
											<td><img src="{{ asset('assets/site-img/icon/')."/".$c->image }}" width="30" ></td>
											<td>{{ $c->slug }}</td>
											<td>
												<button type="button" class="btn btn-info btn-sm active" data-toggle="modal" data-target="#PPPEdit{{ $c->id }}">Edit</button>
												<div id="PPPEdit{{ $c->id }}" class="modal fade" role="dialog">
													<div class="modal-dialog">
														<div class="modal-content">
															<div class="modal-header">
																<h4 class="modal-title">Edit : {{ $c->id }}</h4>
																<button type="button" class="close" data-dismiss="modal">&times;</button>
															</div>
															<div class="modal-body">
																<div class="col-md-12">
																	<div class="card card-primary">
																		<div class="card-header">
																			<h3 class="card-title">Edit Category</h3>
																		</div>
																		<form role="form" action="/admin/category/edit/{{ $c->id }}" method="post">
																			@csrf
																			<div class="card-body">
																				<div class="form-group">
																					<label for="edit_cat_name">Category Name :</label>
																					<input type="text" class="form-control" id="edit_cat_name" name="edit_cat_name" value="{{ $c->name }}" required>
																				</div>
																				<div class="form-group">
																					<label for="x2">Image : </label>
																					<br>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav1.jpg" required {{ ($c->image == "nav1.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav1.jpg') }}" width="30" ></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav2.jpg" required {{ ($c->image == "nav2.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav2.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav3.jpg" required {{ ($c->image == "nav3.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav3.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav4.jpg" required {{ ($c->image == "nav4.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav4.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav5.jpg" required {{ ($c->image == "nav5.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav5.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav6.jpg" required {{ ($c->image == "nav6.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav6.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav7.jpg" required {{ ($c->image =="nav7.jpg" ) ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav7.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav8.jpg" required {{ ($c->image == "nav8.jpg" ) ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav8.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav9.jpg" required {{ ($c->image =="nav9.jpg" ) ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav9.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav10.jpg" required {{ ($c->image == "nav10.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav10.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav11.jpg" required {{ ($c->image == "nav11.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav11.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav12.jpg" required {{ ($c->image == "nav12.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav12.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav13.jpg" required {{ ($c->image == "nav13.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav13.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav14.jpg" required {{ ($c->image == "nav14.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav14.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav16.jpg" required {{ ($c->image == "nav16.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav16.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav17.jpg" required {{ ($c->image == "nav17.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav17.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav18.jpg" required {{ ($c->image == "nav18.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav18.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav19.jpg" required {{ ($c->image == "nav19.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav19.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_27_Fumaco-Water.jpg" required {{ ($c->image == "icons_27_Fumaco-Water.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_27_Fumaco-Water.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_26_Wall-lights.jpg" required {{ ($c->image == "icons_26_Wall-lights.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon//icons_26_Wall-lights.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_25_Tracklights.jpg" required {{ ($c->image == "icons_25_Tracklights.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_25_Tracklights.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_24_Striplights.jpg" required {{ ($c->image == "icons_24_Striplights.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_24_Striplights.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_23_Bollard.jpg" required {{ ($c->image == "icons_23_Bollard.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_23_Bollard.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_22_Downlight-Recessed.jpg" required {{ ($c->image == "icons_22_Downlight-Recessed.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_22_Downlight-Recessed.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_21_Electrical-Boxes.jpg" required {{ ($c->image == "icons_21_Electrical-Boxes.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_21_Electrical-Boxes.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_20_Sockets.jpg" required {{ ($c->image == "icons_20_Sockets.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_20_Sockets.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_19_Switches.jpg" required {{ ($c->image == "icons_19_Switches.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_19_Switches.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_18_Panel-Board.jpg" required {{ ($c->image == "icons_18_Panel-Board.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_18_Panel-Board.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_17_Circuit-Breaker.jpg" required {{ ($c->image == "icons_17_Circuit-Breaker.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_17_Circuit-Breaker.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_16_Batten Type.jpg" required {{ ($c->image == "icons_16_Batten Type.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon//icons_16_Batten Type.jpg') }}" width="30"></label>
																					</div>
																					<div class="form-check form-check-inline">
																						<input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_15_IP-rated-Luminaire.jpg" required {{ ($c->image == "icons_15_IP-rated-Luminaire.jpg") ? 'checked' : '' }}>
																						<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_15_IP-rated-Luminaire.jpg') }}" width="30"></label>
																					</div>
																				</div>
																				<div class="form-check mt-4 mb-3">
																					<input type="checkbox" class="form-check-input edit_is_external_link" id="externalLink{{ $c->id }}" name="edit_is_external_link" value="1" {{ ($c->external_link) ? 'checked' : '' }} data-tid="external-link{{ $c->id }}">
																					<label class="form-check-label" for="externalLink{{ $c->id }}">External Link</label>
																				 </div>
																				<div class="form-group {{ (!$c->external_link) ? 'd-none' : '' }}">
																					<label for="external-link{{ $c->id }}">External Link : </label>
																					<input type="text" class="form-control" id="external-link{{ $c->id }}" name="external_link" value="{{ $c->external_link }}">
																				</div>
																				<div class="form-group">
																					<label for="edit_cat_slug">Slug : </label>
																					<input type="text" class="form-control" id="edit_cat_slug" name="edit_cat_slug" value="{{ $c->slug }}">
																				</div>
																				<div class="form-group text-right">
																					<input class="form-check-input" name="hide_na" type="checkbox" {{ ($c->hide_none == 1) ? 'checked' : '' }}/>
																					<label for="edit_cat_slug">Hide "N/A" Attribute Values </label>
																				</div>
																			</div>
																			<!-- /.card-body -->
																			<div class="card-footer">
																				<input type="submit" class="btn btn-primary" value="Update">
																			</div>
																		</form>
																	</div>
																	<!-- /.card -->
																</div>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-default"
																data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</div>
												<a {{ (!$c->external_link) ? 'href="/admin/category/settings/' . $c->id .'"' : '' }} class="btn btn-success btn-sm active  {{ ($c->external_link) ? 'disabled' : '' }}" role="button" aria-pressed="true">Sort Items</a>
												<a href="/admin/category/delete/{{ $c->id }}" class="float-right btn btn-danger btn-sm active" role="button" aria-pressed="true">Delete</a>
											</td>
											<td class="col-sm-1">
												<center>
													<label class="switch">
														<input type="checkbox" class="toggle" id="toggle_{{ $c->id }}" name="publish" {{ ($c->publish == 1) ? 'checked' : '' }} value="{{ $c->id }}"/>
														<span class="slider round"></span>
													</label>
												</center>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-md-12">{{-- Add Category --}}
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">Add Category</h3>
							</div>
							<form role="form" action="/admin/category/add" method="post">
								@csrf
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="add_cat_name">Category Name : </label>
												<input type="text" class="form-control" id="add_cat_name" name="add_cat_name" value="" required>
											</div>
											<div class="form-group">
												<label for="x2">Image : </label>
												<br/>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav1.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav1.jpg') }}" width="30" ></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav2.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav2.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav3.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav3.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav4.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav4.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav5.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav5.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav6.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav6.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav7.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav7.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav8.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav8.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav9.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav9.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav10.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav10.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav11.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav11.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav12.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav12.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav13.jpg">
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav13.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav14.jpg">
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav14.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav16.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav16.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav17.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav17.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav18.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav18.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="nav19.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav19.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_27_Fumaco-Water.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_27_Fumaco-Water.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_26_Wall-lights.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon//icons_26_Wall-lights.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_25_Tracklights.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_25_Tracklights.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_24_Striplights.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_24_Striplights.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_23_Bollard.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_23_Bollard.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_22_Downlight-Recessed.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_22_Downlight-Recessed.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_21_Electrical-Boxes.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_21_Electrical-Boxes.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_20_Sockets.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_20_Sockets.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_19_Switches.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_19_Switches.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_18_Panel-Board.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_18_Panel-Board.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_17_Circuit-Breaker.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_17_Circuit-Breaker.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_16_Batten Type.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon//icons_16_Batten Type.jpg') }}" width="30"></label>
												</div>
												<div class="form-check form-check-inline">
													<input class="form-check-input" type="radio" name="add_cat_icon" id="x2" value="icons_15_IP-rated-Luminaire.jpg" required>
													<label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_15_IP-rated-Luminaire.jpg') }}" width="30"></label>
												</div>
												<br>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check mt-4 mb-3">
												<input type="checkbox" class="form-check-input" id="externalLink" name="is_external_link" value="1">
												<label class="form-check-label" for="externalLink">External Link</label>
											 </div>
											<div class="form-group d-none" id="external-link-div">
												<label for="external-link">External Link : </label>
												<input type="text" class="form-control" id="external-link" name="external_link">
											</div>
											<div class="form-group">
												<label for="add_cat_slug">Slug : </label>
												<input type="text" class="form-control" id="add_cat_slug" name="add_cat_slug">
											</div>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="submit" class="btn btn-primary" value="Add Category">
								</div>
							</form>
						</div>
						<!-- /.card -->
					</div>
				</div>
			</div>
		</section>
	</div>
</div> 
<style>
	.switch {
		position: relative;
		display: inline-block;
		width: 30px;
		height: 16px;
	}

	.switch input { 
		opacity: 0;
		width: 0;
		height: 0;
	}

	.slider {
		position: absolute;
		cursor: pointer;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: #ccc;
		-webkit-transition: .4s;
		transition: .4s;
	}

	.slider:before {
		position: absolute;
		content: "";
		height: 10px;
		width: 10px;
		left: 3px;
		bottom: 3px;
		background-color: white;
		-webkit-transition: .4s;
		transition: .4s;
	}

	input:checked + .slider {
		background-color: #2196F3;
	}

	input:focus + .slider {
		box-shadow: 0 0 1px #2196F3;
	}

	input:checked + .slider:before {
		-webkit-transform: translateX(16px);
		-ms-transform: translateX(16px);
		transform: translateX(16px);
	}

	/* Rounded sliders */
	.slider.round {
		border-radius: 34px;
	}

	.slider.round:before {
		border-radius: 50%;
	}
</style>
@endsection
@section('script')
<script>
  	$(document).ready(function() {
		$('input[name="is_external_link"]').prop('checked', false);
	  	$('input[name="is_external_link"]').click(function(){
		  if ($(this).prop('checked')) {
			  $('#external-link-div').removeClass('d-none');
			  $('#external-link').attr('required', true);
		  } else {
			$('#external-link-div').addClass('d-none');
			$('#external-link').removeAttr('required');
		  }
	  });

	  $(document).on('click', '.edit_is_external_link', function(){
		  var tid = $(this).data('tid');
		  console.log(tid);
		  if ($(this).prop('checked')) {
			  $('#' + tid).closest('.form-group').removeClass('d-none');
			  $('#' + tid).attr('required', true);
		  } else {
			$('#' + tid).closest('.form-group').addClass('d-none');
			$('#' + tid).removeAttr('required');
		  }
	  });

    $(".toggle").change(function(){
      var data = {
        'publish': $(this).prop('checked') == true ? 1 : 0,
        'cat_id': $(this).val(),
        '_token': "{{ csrf_token() }}",
      }
      // console.log(data);
      $.ajax({
          type:'POST',
          url:'/admin/category/publish',
          data: data,
          success: function (response) {
            console.log(status);
          },
          error: function () {
              alert('An error occured.');
          }
      });
    });
  });
</script>
@endsection