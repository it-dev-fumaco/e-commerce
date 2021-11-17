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
                            <h1>Edit Contact Us</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Edit Contact Us</li>
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
                                <form action="/admin/pages/contact/update/{{ $address->id }}" method="POST">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-9"><h4>Contact Us</h4></div>
                                            <div class="col-md-3 text-right">
                                                <a href="/admin/pages/contact/add_form" class="btn btn-secondary">Add an Address</a>
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Title *</label>
                                                <input type="text" name="title" class="form-control" value="{{ $address->office_title }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Address *</label>
                                                <input type="text" name="address" class="form-control" value="{{ $address->office_address }}" required/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Office Phone *</label>
                                                <input type="text" name="phone" class="form-control" value="{{ $address->office_phone }}" required/>
                                            </div>
     
                                            <div class="form-group col-md-4">
                                                <label>Office Mobile *</label>
                                                <input type="text" name="mobile" class="form-control" value="{{ $address->office_mobile }}" required/>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Office Email *</label>
                                                <input type="email" name="email" class="form-control" value="{{ $address->office_email }}" required/>
                                            </div>
                                        </div>
                                        <div class="float-right font-italic">
                                            <small>Last modified by: {{ $address->last_modified_by }} - {{ $address->last_modified_at }}</small><br>
                                            <small>Created by: {{ $address->created_by }} - {{ $address->created_at }}</small>
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
