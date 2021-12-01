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
                        <div class="col-12">
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
                                            <div class="col-6">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $coupon->name }}" required>
                                            </div>

                                            <div class="col-6">
                                                <label>Coupon Code</label>
                                                <input type="text" class="form-control" name="coupon_code" value="{{ $coupon->code }}" placeholder="Coupon Code" required>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-6">
                                                <label><input type="checkbox" name="require_validity" id="require_validity" {{ $coupon->validity_date_start ? 'checked' : '' }}> Set Validity</label>
                                                <input type="text" class="form-control set_duration" id="daterange" name="validity" disabled/>
                                            </div>

                                            <div class="col-6">
                                                <label><input type="checkbox" name="unlimited_allotment" id="unlimited_allotment" {{ $coupon->unlimited == 1 ? 'checked' : '' }}> Unlimited Allotment</label>
                                                <input type="number" class="form-control" name="allotment" id="allotment" value="{{ $coupon->total_allotment }}" placeholder="Allotment">
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Discount Type *</label>
                                                @php
                                                    $discount_type = array('Free Delivery', 'Fixed Amount', 'By Percentage');
                                                @endphp
                                                <select class="form-control" name="discount_type" id="discount_type" required>
                                                    <option disabled value="">Discount Type</option>
                                                    @foreach ($discount_type as $discount)
                                                        <option value="{{ $discount }}" {{ $coupon->discount_type == $discount ? 'selected' : '' }}>{{ $discount }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <label>Minimum Spend</label>
                                                <input type="text" class="form-control" name="minimum_spend" value="{{ $coupon->minimum_spend }}" placeholder="Minimum Spend">
                                            </div>
                                        </div>
                                        <div id="fixed_amount" class="row">
                                            <br>&nbsp;
                                            <div class="col-12">
                                                <label>Amount *</label>
                                                <input type="text" class="form-control" id="discount_amount" value="{{ $coupon->discount_type == 'Fixed Amount' ? $coupon->discount_rate : ''  }}" name="discount_amount" placeholder="Amount">
                                            </div>
                                        </div>
                                        <div id="percentage" class="row">
                                            <div class="col-12"><br/></div>
                                            <div class="col-6">
                                                <label>Percentage *</label>
                                                <input type="text" class="form-control" id="discount_percentage" value="{{ $coupon->discount_type == 'By Percentage' ? $coupon->discount_rate : ''  }}" name="discount_percentage" placeholder="Percentage">
                                            </div>
                                            <div class="col-6">
                                                <label>Capped Amount</label>
                                                <input type="text" class="form-control" name="capped_amount" value="{{ $coupon->capped_amount }}" id="capped_amount" placeholder="Capped Amount"/>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Remarks</label>
                                                <textarea name="remarks" cols="3" rows="5" class="form-control" placeholder="Remarks">{{ $coupon->remarks }}</textarea>
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
        validityDate();
        discountType();
        discountFor();

        $('#discount_type').change(function(){
            discountType();
        });

        $('#discount_for').change(function(){
            discountFor();
        });

        $('#unlimited_allotment').click(function(){
            allotment();
        });

        $('#require_validity').click(function(){
            validityDate();
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

        function validityDate(){
            if($('#require_validity').is(':checked')){
                $("#daterange").prop('required',true);
                $("#daterange").prop('disabled',false);
            }else{
                $("#daterange").prop('required',false);
                $("#daterange").prop('disabled',true);
            }
        }

        function discountType(){
            if($('#discount_type').val() == 'Fixed Amount'){
                $('#fixed_amount').slideDown();
                $("#discount_amount").prop('required',true);
                $("#discount_percentage").prop('required',false);
                $('#percentage').slideUp();
            }else if($('#discount_type').val() == 'By Percentage'){
                $('#percentage').slideDown();
                $("#discount_percentage").prop('required',true);
                $("#discount_amount").prop('required',false);
                $('#fixed_amount').slideUp();
            }else{
                $('#fixed_amount').slideUp();
                $('#percentage').slideUp();
                $("#discount_amount").prop('required',false);
                $("#discount_percentage").prop('required',false);
            }
        }

        function discountFor(){
            if($('#discount_for').val() == 'Per Category'){
                $('#categories').slideDown();
                $("#selected_categories").prop('required',true);
            }else{
                $('#categories').slideUp();
                $("#selected_categories").prop('required',false);
            }
        }

        var start = "{{ $coupon->validity_date_start != null ? date('m/d/Y', strtotime($coupon->validity_date_start)) : null  }}";
        var end = "{{ $coupon->validity_date_end != null ? date('m/d/Y', strtotime($coupon->validity_date_end)) : null }}";

        $('#daterange').daterangepicker({
            opens: 'left',
            placeholder: 'Select Date Range',
            startDate: start ? start : moment(),
            endDate: end ? end : moment().add(7, 'days'),
        });
    });
    </script>
@endsection