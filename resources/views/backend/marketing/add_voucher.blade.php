@extends('backend.layout', [
'namePage' => 'Coupons',
'activePage' => 'vouchers_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add Coupon Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Add Coupon Page</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <div class="card card-primary">
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
                                <div class="card-body">
                                    <form action="/admin/marketing/voucher/add" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-9"><h4>Coupon</h4></div>
                                            <div class="col-3 text-right">
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Coupon Code</label>
                                                <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Allotment</label>
                                                <input type="text" class="form-control" name="allotment" placeholder="Allotment" required>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection