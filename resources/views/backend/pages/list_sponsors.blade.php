@extends('backend.layout', [
'namePage' => 'About Us - Sponsors',
'activePage' => 'about_us_sponsors'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>About Us - Sponsors</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">About Us - Sponsors</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            @if(session()->has('success'))
                                <div class="alert alert-success fade show" role="alert">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                            @if(session()->has('image_error'))
                                <div class="alert alert-warning fade show" role="alert">
                                    {{ session()->get('image_error') }}
                                </div>
                            @endif
                            
                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h4>Sponsors</h4>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <a href="#add_sponsor" class="btn btn-primary">Add a Sponsor</a>
                                        </div>
                                    </div>
                                    <br/>
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>URL</th>
                                            <th>Sorting</th>
                                            <th>Action</th>
                                        </tr>
                                        @forelse ($sponsors as $sponsor)
                                            <tr>
                                                <td style="width: 10%">{{ $sponsor->name_img }}</td>
                                                <td style="width: 7%"><img src="{{ asset('/storage/sponsors/'.$sponsor->image) }}" alt="{{ $sponsor->name_img }}" width='100%'></td>
                                                <td style="width: 40%"><a href="{{ $sponsor->url }}" target="_blank">{{ $sponsor->url }}</a></td>
                                                <td style="width: 30%">
                                                    <form action="/admin/edit/page/about_us/sponsor/sort/{{ $sponsor->id }}" method="post">
                                                        <div class="row">
                                                            @csrf
                                                            <div class="col-md-6">
                                                                <select class="form-control formslabelfnt" id="row_select" aria-label="Default select example" name="item_row" required>
                                                                    <option selected disabled value="">Order No.</option>
                                                                    @for ($i = 0; $i < $sponsors_count; $i++)
                                                                        @php
                                                                            $option = $i + 1;
                                                                        @endphp
                                                                        <option value="{{ $option }}" {{ ($sponsor->partners_sort == $option) ? 'selected disabled' : '' }}>{{ $option }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <button type="submit" class="btn btn-sm btn-primary" style="width: 100%">Apply</button>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <a href="#" class="btn btn-sm btn-secondary" style="width: 100%" role="button" data-toggle="modal" data-target="#resetModal{{ $sponsor->id }}">Reset</a>
                                                                <div class="modal fade" id="resetModal{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">Reset Sorting</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Reset sorting for {{ $sponsor->name_img }}?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                <a href="/admin/edit/page/about_us/sponsor/reset/{{ $sponsor->id }}" class="btn btn-danger" role="button">Reset</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td style="width: 5%">
                                                    <center><a href="#" class="btn btn-sm btn-danger" role="button" data-toggle="modal" data-target="#deleteModal{{ $sponsor->id }}"><i class="fas fa-trash-alt"></i></a></center>

                                                    <div class="modal fade" id="deleteModal{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Delete Sponsor</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Delete {{ $sponsor->name_img }}?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <a href="/admin/edit/page/about_us/sponsor/delete/{{ $sponsor->id }}" class="btn btn-danger" role="button">Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr> 
                                        @empty
                                            <tr>
                                                <td colspan=5 class="text-center">No Sponsor(s)</td>
                                            </tr> 
                                        @endforelse
                                    </table>
                                    <div class="float-right font-italic">
                                        <small>Last modified by: {{ $last_mod->last_modified_by }} - {{ $last_mod->last_modified_at }}</small><br>
                                    </div>
                                    <br/>
                                    <div class="float-right mt-4">
                                        {{ $sponsors->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="add_sponsor">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <form action="/admin/edit/page/about_us/sponsor/add" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-9">
                                                <h4>Add a sponsor</h4>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Sponsor Name *</label>
                                                <input type="text" name="sponsor_name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Sponsor URL *</label>
                                                <input type="text" name="sponsor_url" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Sponsor Image *</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="sponsor_img" required>
                                                    <label class="custom-file-label" for="customFile">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").change(function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
 </script>
@endsection