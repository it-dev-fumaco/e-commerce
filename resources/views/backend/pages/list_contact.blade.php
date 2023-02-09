@extends('backend.layout', [
'namePage' => 'Contact Us',
'activePage' => 'contact_us'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Contact Us</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Contact Us</li>
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
                                        <div class="col-md-9"></div>
                                        <div class="col-md-3 text-right">
                                            <a href="/admin/pages/contact/add_form" class="btn btn-primary"><i class="fa fa-plus"></i> Add an Address</a>
                                        </div>
                                    </div>
                                    <br/>
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <th class="text-center">Title</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        @forelse ($address as $add)
                                            <tr>
                                                <td class="text-center align-middle">{{ $add->office_title }}</td>
                                                <td class="text-left align-middle">{{ $add->office_address }}</td>
                                                <td class="text-center align-middle">
                                                    <a href="/admin/pages/contact/edit/{{ $add->id }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                                    <a href="#" data-toggle="modal" data-target="#a{{ $add->id }}-Modal" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                                      <!-- Modal -->
                                                    <div class="modal fade" id="a{{ $add->id }}-Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Delete Address</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Delete {{ $add->office_title }}?
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                                                                    <a href="/admin/pages/contact/delete/{{ $add->id }}" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=6 class="text-center">No Address(es)</td>
                                            </tr> 
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $address->withQueryString()->links('pagination::bootstrap-4') }}
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