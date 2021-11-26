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
                            <h1>Edit Coupon Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Edit Coupon Page</li>
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
                                    <form action="/admin/marketing/voucher/{{ $coupon->id }}/edit" method="post">
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
                                                <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $coupon->name }}" required>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Coupon Code</label>
                                                <input type="text" class="form-control" name="coupon_code" value="{{ $coupon->code }}" placeholder="Coupon Code" required>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-12">
                                                <label><input type="checkbox" name="unlimited_allotment" id="unlimited_allotment" {{ $coupon->unlimited == 1 ? 'checked' : '' }}> Unlimited Allotment</label>
                                                <br/>
                                                <label>Allotment</label>
                                                <input type="number" class="form-control" name="allotment" id="allotment" value="{{ $coupon->total_allotment }}" placeholder="Allotment">
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="float-right font-italic">
                                            <small>Last modified by: {{ $coupon->last_modified_by }} - {{ $coupon->last_modified_at }}</small><br>
                                            <small>Created by: {{ $coupon->created_by }} - {{ $coupon->created_at }}</small>
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
@section('script')
<script>
    $(document).ready(function(){
        allotment();

        $('#unlimited_allotment').click(function(){
            allotment();
        });

        function allotment(){
            if($('#unlimited_allotment').is(':checked')){
                $("#allotment").prop('required',false);
                $("#allotment").prop('disabled',true);
            }else{
                $("#allotment").prop('required',true);
                $("#allotment").prop('disabled',false);
                
            }
        }
    });
    </script>
@endsection