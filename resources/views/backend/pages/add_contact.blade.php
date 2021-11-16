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
                            <h1>Add Contact Address</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Add Contact Address</li>
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
                                <form action="/admin/pages/contact/add" method="POST">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-9"><h4>Contact Us</h4></div>
                                            <div class="col-md-3 text-right">
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Title *</label>
                                                <input type="text" name="title" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Address *</label>
                                                <input type="text" name="address" class="form-control" required/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Office Phone *</label>
                                                <input type="text" name="phone" class="form-control" required/>
                                            </div>
     
                                            <div class="form-group col-md-4">
                                                <label>Office Mobile *</label>
                                                <input type="text" name="mobile" class="form-control" required/>
                                            </div>

                                            <div class="form-group col-md-4">
                                                <label>Office Email *</label>
                                                <input type="email" name="email" class="form-control" required/>
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
