@extends('backend.layout', [
'namePage' => 'On Sale',
'activePage' => 'on_sale_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Add On Sale Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Add On Sale Page</li>
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
                                    <form action="/admin/marketing/on_sale/add" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-9"><h4>On Sale</h4></div>
                                            <div class="col-3 text-right">
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Sale Name *</label>
                                                <input type="text" class="form-control" name="sale_name" placeholder="Sale Name" required>
                                            </div>
                                            <div class="col-6">
                                                <label>Discount Type *</label>
                                                @php
                                                    $discount_type = array('Free Delivery', 'Fixed Amount', 'By Percentage');
                                                @endphp
                                                <select class="form-control" name="discount_type" id="discount_type" required>
                                                    <option disabled selected value="">Discount Type</option>
                                                    @foreach ($discount_type as $discount)
                                                        <option value="{{ $discount }}">{{ $discount }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- <div class="col-4">
                                                <label>Banner Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="banner_img">
                                                    <label class="custom-file-label" for="customFile">Choose File</label>
                                                </div>
                                            </div> --}}
                                        </div>
                                        <div id="fixed_amount" class="row" style="display: none">
                                            <br>&nbsp;
                                            <div class="col-12">
                                                <label>Amount *</label>
                                                <input type="text" class="form-control" id="discount_amount" name="discount_amount" placeholder="Amount">
                                            </div>
                                        </div>
                                        <div id="percentage" class="row" style="display: none">
                                            <div class="col-12"><br></div>
                                            <div class="col-6">
                                                <label>Percentage *</label>
                                                <input type="text" class="form-control" id="discount_percentage" name="discount_percentage" placeholder="Percentage">
                                            </div>
                                            <div class="col-6">
                                                <label>Capped Amount</label>
                                                <input type="text" class="form-control" name="capped_amount" id="capped_amount" placeholder="Capped Amount"/>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-6">
                                                <label><input type="checkbox" name="set_duration" id="set_duration"> Set Sale Duration</label>
                                                <input type="text" class="form-control set_duration" id="daterange" name="sale_duration" disabled/>
                                            </div>
                                            <div class="col-6">
                                                <label><input type="checkbox" name="require_coupon" id="require_coupon" {{ count($vouchers) == 0 ? 'disabled' : '' }}> Require Coupon</label>
                                                {{-- <input type="text" class="form-control" name="coupon" id="coupon" placeholder="Coupon" disabled/> --}}
                                                <select class="form-control" name="coupon" id="coupon" disabled>
                                                    <option disabled selected value="">Select a Voucher</option>
                                                    @forelse ($vouchers as $voucher)
                                                        <option value="{{ $voucher->id }}">{{ $voucher->name }}</option>
                                                    @empty
                                                        <option disabled value="">No Vouchers</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Discount For *</label>
                                                <select class="form-control" name="discount_for" id="discount_for" required>
                                                    <option disabled selected value="">Discount For</option>
                                                    <option value="Per Category">Per Category</option>
                                                    <option value="All Items">All Items</option>
                                                </select>
                                            </div>
                                            <div id="categories" class="col-6" style="display: none">
                                                <label>Categories *</label><br/>
                                                <input class="d-none" type="text" name="selected_categories" id="selected_categories">
                                                @forelse ($categories as $cat)
                                                    <input type="checkbox" name="category" class="categories" value="{{ $cat->id }}"> {{ $cat->name }}<br/>
                                                @empty
                                                    No Published Category
                                                @endforelse
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
@section('script')
<script>
    $(document).ready(function(){
        $(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                placeholder: 'Select Date Range',
                startDate: moment(), endDate: moment().add(7, 'days'),
            });
        });

        $('#discount_type').change(function(){
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
        });

        $('#discount_for').change(function(){
            if($('#discount_for').val() == 'Per Category'){
                $('#categories').slideDown();
                $("#selected_categories").prop('required',true);
            }else{
                $('#categories').slideUp();
                $("#selected_categories").prop('required',false);
            }
        });

        $('#require_coupon').click(function(){
            if($(this).is(':checked')){
                $("#coupon").prop('required',true);
                $("#coupon").prop('disabled',false);
            }else{
                $("#coupon").prop('required',false);
                $("#coupon").prop('disabled',true);
            }
        });

        $('#set_duration').click(function(){
            if($(this).is(':checked')){
                $(".set_duration").prop('disabled',false);
            }else{
                $(".set_duration").prop('disabled',true);
            }
        });

        $checks = $(".categories");
        $checks.on('change', function() {
            var string = $checks.filter(":checked").map(function(i,v){
                return this.value;
            }).get().join(",");
            $('#selected_categories').val(string);
        });

        // Add the following code if you want the name of the file appear on select
        // $(".custom-file-input").change(function() {
        //     var fileName = $(this).val().split("\\").pop();
        //     $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        // });
    });
    </script>
@endsection