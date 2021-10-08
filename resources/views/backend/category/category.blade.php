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

                                                        <a href="/admin/category/delete/{{ $c->id }}" class="btn btn-danger btn-sm active" role="button" aria-pressed="true">Delete</a>

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
                                                                                            <label for="edit_cat_name">Category Name :
                                                                                            </label>
                                                                                            <input type="text" class="form-control" id="edit_cat_name" name="edit_cat_name" value="{{ $c->name }}" required>
                                                                                        </div>

                                                                                        <div class="form-group">
                                                                                            <label for="x2">Image : </label>
                                                                                            <br>
                                                                                            <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav1.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav1.jpg') }}" width="30" ></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav2.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav2.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav3.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav3.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav4.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav4.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav5.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav5.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav6.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav6.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav7.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav7.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav8.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav8.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav9.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav9.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav10.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav10.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav11.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav11.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav12.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav12.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav13.jpg">
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav13.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav14.jpg">
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav14.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav16.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav16.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav17.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav17.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav18.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav18.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="nav19.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/nav19.jpg') }}" width="30"></label>
                                                                                              </div>

                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_27_Fumaco-Water.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_27_Fumaco-Water.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_26_Wall-lights.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon//icons_26_Wall-lights.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_25_Tracklights.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_25_Tracklights.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_24_Striplights.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_24_Striplights.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_23_Bollard.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_23_Bollard.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_22_Downlight-Recessed.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_22_Downlight-Recessed.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_21_Electrical-Boxes.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_21_Electrical-Boxes.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_20_Sockets.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_20_Sockets.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_19_Switches.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_19_Switches.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_18_Panel-Board.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_18_Panel-Board.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_17_Circuit-Breaker.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_17_Circuit-Breaker.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_16_Batten Type.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon//icons_16_Batten Type.jpg') }}" width="30"></label>
                                                                                              </div>
                          
                          
                                                                                              <div class="form-check form-check-inline">
                                                                                                <input class="form-check-input" type="radio" name="edit_cat_icon" id="x2" value="icons_15_IP-rated-Luminaire.jpg" required>
                                                                                                <label class="form-check-label" for="inlineRadio1"><img src="{{ asset('assets/site-img/icon/icons_15_IP-rated-Luminaire.jpg') }}" width="30"></label>
                                                                                              </div>                          
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <label for="edit_cat_slug">Slug : </label>
                                                                                            <input type="text" class="form-control" id="edit_cat_slug" name="edit_cat_slug" value="{{ $c->slug }}">
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">

                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Add Category</h3>
                                </div>

                                <form role="form" action="/admin/category/add" method="post">
                                  @csrf
                                    <div class="card-body">

                                        <div class="form-group">
                                            <label for="add_cat_name">Category Name : </label>
                                            <input type="text" class="form-control" id="add_cat_name" name="add_cat_name" value="" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="x2">Image : </label>
                                            <br/>
                                            <!--<input type="text" class="form-control" id="x2" name="x2" value="" required>-->
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

                                        <div class="form-group">
                                            <label for="add_cat_slug">Slug : </label>
                                            <input type="text" class="form-control" id="add_cat_slug" name="add_cat_slug" value="">
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

@endsection
