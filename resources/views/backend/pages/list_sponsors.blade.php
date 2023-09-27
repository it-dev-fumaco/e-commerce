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
                            @if(session()->has('error'))
                                <div class="alert alert-warning fade show" role="alert">
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                            
                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <h4><i class="fas fa-users"></i> Sponsors</h4>
                                        </div>
                                        <div class="col-md-3 text-right">
                                            <a href="#add_sponsor" class="btn btn-primary"><i class="fas fa-plus"></i> Add a Sponsor</a>
                                        </div>
                                    </div>
                                    <br/>
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <th class="text-center">Name</th>
                                            <th class="text-center">Image</th>
                                            <th class="text-center">URL</th>
                                            <th class="text-center">Sorting</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        @forelse ($sponsors as $sponsor)
                                            <tr>
                                                <td class="text-center align-middle" style="width: 10%">{{ $sponsor->name_img }}</td>
                                                <td class="text-center align-middle" style="width: 7%"><img src="{{ asset('/storage/sponsors/'.$sponsor->image) }}" alt="{{ $sponsor->name_img }}" width='100%'></td>
                                                <td class="align-middle" style="width: 50%"><a href="{{ $sponsor->url }}" target="_blank">{{ $sponsor->url }}</a></td>
                                                <td class="text-center align-middle" style="width: 20%">
                                                    <form action="/admin/edit/page/about_us/sponsor/sort/{{ $sponsor->id }}" method="post">
                                                        <div class="row">
                                                            @csrf
                                                            <div class="col-md-6 offset-1">
                                                                <select class="form-control form-control-sm formslabelfnt" id="row_select" aria-label="Default select example" name="item_row" required>
                                                                    <option selected disabled value="">Order No.</option>
                                                                    @for ($i = 0; $i < $sponsors_count; $i++)
                                                                        @php
                                                                            $option = $i + 1;
                                                                        @endphp
                                                                        <option value="{{ $option }}" {{ ($sponsor->partners_sort == $option) ? 'selected disabled' : '' }}>{{ $option }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                         
                                                            <div class="col-md-4 p-0">
                                                                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-check"></i></button>
                                                                <a href="#" class="btn btn-sm btn-secondary" role="button" data-toggle="modal" data-target="#resetModal{{ $sponsor->id }}"><i class="fas fa-undo"></i></a>
                                                                <div class="modal fade" id="resetModal{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-undo"></i> Reset Sorting</h5>
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Reset sorting for {{ $sponsor->name_img }}?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                                                <a href="/admin/edit/page/about_us/sponsor/reset/{{ $sponsor->id }}" class="btn btn-danger" role="button"><i class="fas fa-undo"></i> Reset</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="text-center align-middle" style="width: 5%">
                                                    <div class="btn-group">
                                                        <a href="#" class="btn btn-sm btn-primary" role="button" data-toggle="modal" data-target="#editModal{{ $sponsor->id }}"><i class="fas fa-edit"></i></a>&nbsp;
                                                        <a href="#" class="btn btn-sm btn-secondary" role="button" data-toggle="modal" data-target="#deleteModal{{ $sponsor->id }}"><i class="fas fa-trash-alt"></i></a>
                                                    </div>
                                                    <div class="modal fade" id="editModal{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit"></i> Edit {{ $sponsor->name_img }}</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="/admin/edit/page/about_us/sponsor/edit/{{ $sponsor->id }}" method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div class="modal-body text-left">
                                                                        <div class="col-4 mx-auto">
                                                                            <img id="img-{{ $sponsor->id }}" src="{{ asset('/storage/sponsors/'.$sponsor->image) }}" alt="{{ $sponsor->name_img }}" width='100%'>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Sponsor Image</label>
                                                                            <div class="custom-file">
                                                                                <input type="file" class="custom-file-input" id="customFile" name="sponsor_img" data-preview="#img-{{ $sponsor->id }}">
                                                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="name">Name:</label>
                                                                            <input type="text" class="form-control" name="sponsor_name" value="{{ $sponsor->name_img }}" required>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="name">URL:</label>
                                                                            <input type="text" class="form-control" name="sponsor_url" value="{{ $sponsor->url }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal fade" id="deleteModal{{ $sponsor->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-trash"></i> Delete Sponsor</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Delete {{ $sponsor->name_img }}?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                                    <a href="/admin/edit/page/about_us/sponsor/delete/{{ $sponsor->id }}" class="btn btn-danger" role="button"><i class="fas fa-trash"></i> Delete</a>
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
                                        <small>Last modified by: {{ $last_mod ? $last_mod->last_modified_by.' - '.$last_mod->last_modified_at : '-' }}</small><br>
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
                                                <h4><i class="fas fa-user"></i> Add a Sponsor</h4>
                                            </div>
                                            <div class="col-md-3 text-right">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Sponsor Name <span class="text-danger">*</span></label>
                                                <input type="text" name="sponsor_name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Sponsor URL <span class="text-danger">*</span></label>
                                                <input type="text" name="sponsor_url" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Sponsor Image <span class="text-danger">*</span></label>
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
        if(typeof $(this).data('preview') !== 'undefined'){
            readURL(this, $(this).data('preview'));
        }
    });

    function readURL(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                console.log(e);
                $(previewId).attr('src', e.target.result);
                $(previewId).hide();
                $(previewId).fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection