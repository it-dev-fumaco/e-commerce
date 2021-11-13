@extends('backend.layout', [
'namePage' => 'About Us',
'activePage' => 'about_us'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit About Us</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Edit About Us</li>
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
                                <form action="/admin/edit/page/about_us" method="POST">
                                    <div class="card-body">
                                        <h4>Content</h4>
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Title *</label>
                                                <input type="text" name="title" class="form-control" value="{{ $about->title }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Title 1 - 1 *</label>
                                                <textarea class="form-control page-content" rows="5" name="title_1_1">{{ $about->{'1_title_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 1 - 1 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_1_1">{{ $about->{'1_caption_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 2 - 1 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_2_1">{{ $about->{'1_caption_2'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 3 - 1 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_3_1">{{ $about->{'1_caption_3'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 4 Head - 1 *</label>
                                                <input type="text" name="caption4_head1" class="form-control" value="{{ $about->{'1_year_1'} }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 4 Caption - 1 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption4_caption1">{{ $about->{'1_year_1_details'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 5 Head - 1 *</label>
                                                <input type="text" name="caption5_head1" class="form-control" value="{{ $about->{'1_year_2'} }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 5 Caption - 1 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption5_caption1">{{ $about->{'1_year_2_details'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Title 1 - 2 *</label>
                                                <textarea class="form-control page-content" rows="5" name="title_1_2">{{ $about->{'2_title_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 1 - 2 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_1_2">{{ $about->{'2_caption_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 2 - 2 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_2_2">{{ $about->{'2_caption_2'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 4 Head - 2 *</label>
                                                <input type="text" name="caption4_head2" class="form-control" value="{{ $about->{'2_year_1'} }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 4 Caption - 2 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption4_caption2">{{ $about->{'2_year_1_details'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Title 1 - 3 *</label>
                                                <textarea class="form-control page-content" rows="5" name="title_1_3">{{ $about->{'3_title_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 1 - 3 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_1_3">{{ $about->{'3_caption_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 2 - 3 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_2_3">{{ $about->{'3_caption_2'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 4 Head - 3 *</label>
                                                <input type="text" name="caption4_head3" class="form-control" value="{{ $about->{'3_year_1'} }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 4 - 3 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_4_3">{{ $about->{'3_year_1_details'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Slogan - Title *</label>
                                                <textarea class="form-control page-content" rows="5" name="slogan_title">{{ $about->slogan_title }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Title 1 - 4 *</label>
                                                <textarea class="form-control page-content" rows="5" name="title_1_4">{{ $about->{'4_title_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Caption 1 - 4 *</label>
                                                <textarea class="form-control page-content" rows="5" name="caption_1_4">{{ $about->{'4_caption_1'} }}</textarea>
                                            </div>
                                        </div>
                                        <div class="float-right font-italic">
                                            <small>Last modified by: {{ $about->last_modified_by }} - {{ $about->last_modified_at }}</small><br>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <form action="/admin/edit/page/about_us/image" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <h4>Background Images</h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>1st Background Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="first_bg">
                                                    <label class="custom-file-label" for="customFile">{{ $about->background_1 ? $about->background_1 : 'Choose File' }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>2nd Background Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="second_bg">
                                                    <label class="custom-file-label" for="customFile">{{ $about->background_2 ? $about->background_2 : 'Choose File' }}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>3rd Background Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="third_bg">
                                                    <label class="custom-file-label" for="customFile">{{ $about->background_3 ? $about->background_3 : 'Choose File' }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <h4>Add a sponsor</h4>
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
                                                <label>Sponsor Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="sponsor_img" required>
                                                    <label class="custom-file-label" for="customFile">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-body">
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
                                                <td style="width: 20%">{{ $sponsor->image }}</td>
                                                <td style="width: 40%">{{ $sponsor->url }}</td>
                                                <td style="width: 20%">
                                                    @php
                                                        $count = count($sponsors)
                                                    @endphp
                                                    <form action="" method="post">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <select class="form-control formslabelfnt" id="row_select" aria-label="Default select example" name="item_row" required>
                                                                    <option selected disabled value="">Select Order No.</option>
                                                                    @for ($i = 0; $i < $count; $i++)
                                                                        @php
                                                                            $option = $i + 1;
                                                                        @endphp
                                                                        <option value="{{ $option }}" {{ ($sponsor->partners_sort == $option) ? 'disabled' : '' }}>{{ $option }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <br/>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <input type="text" name="item_code" id="item_code" value="{{ $sponsor->id }}" required hidden/>
                                                                <button type="submit" class="btn btn-primary" style="width: 100%">Apply</button>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <a href="#" class="btn btn-warning" style="width: 100%" role="button" data-toggle="modal" data-target="#resetModal{{ $sponsor->id }}">Reset</a>
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
                                                                                <a href="/admin/edit/page/about_us/sort/{{ $sponsor->id }}" class="btn btn-danger" role="button">Reset</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td style="width: 10%">
                                                    <a href="#" class="btn btn-danger" style="width: 100%" role="button" data-toggle="modal" data-target="#deleteModal{{ $sponsor->id }}">Delete</a>

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
                                                                    <a href="/admin/edit/page/about_us/delete/{{ $sponsor->id }}" class="btn btn-danger" role="button">Reset</a>
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
                                    <div class="float-right mt-4">
                                        {{ $sponsors->withQueryString()->links('pagination::bootstrap-4') }}
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
    (function() {
       $(".page-content").summernote({
          dialogsInBody: true,
          dialogsFade: true,
          height: "200px",
       });
    })();

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").change(function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
 </script>
@endsection