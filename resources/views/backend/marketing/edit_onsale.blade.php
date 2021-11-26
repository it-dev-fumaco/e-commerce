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
                            <h1>Edit On Sale Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Edit On Sale Page</li>
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
                                    <form action="/admin/marketing/on_sale/{{ $on_sale->id }}/edit" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-9"><h4>On Sale</h4></div>
                                            <div class="col-3 text-right">
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6 ">
                                                <label>Sale Name</label>
                                                <input type="text" class="form-control" name="sale_name" placeholder="Sale Name" value="{{ $on_sale->sale_name }}" required>
                                                <br/>
                                                <label>Discount Type</label>
                                                @php
                                                    $discount_type = array('Free Delivery', 'Fixed Amount', 'By Percentage');
                                                @endphp
                                                <select class="form-control" name="discount_type" id="discount_type" required>
                                                    <option disabled selected value="">Discount Type</option>
                                                    @foreach ($discount_type as $discount)
                                                        <option value="{{ $discount }}">{{ $discount }}</option>
                                                    @endforeach
                                                </select>
                                                <div id="fixed_amount" class="col-12">
                                                    <br/>&nbsp;
                                                    <label>Amount</label>
                                                    <input type="text" class="form-control" id="discount_amount" name="discount_amount" value="{{ $on_sale->discount_rate }}" placeholder="Amount">
                                                </div>
                                                <div id="percentage" class="row">
                                                    <div class="col-12"><br/></div>
                                                    <div class="col-6">
                                                        <label>Percentage</label>
                                                        <input type="text" class="form-control" id="discount_percentage" name="discount_percentage" value="{{ $on_sale->discount_rate }}" placeholder="Percentage">
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Capped Amount</label>
                                                        <input type="text" class="form-control" id="capped_amount" name="capped_amount" value="{{ $on_sale->capped_amount }}" placeholder="Capped Amount"/>
                                                    </div>
                                                </div>
                                                <br/>
                                                <label>Discount For</label>
                                                <select class="form-control" name="discount_for" id="discount_for" required>
                                                    <option disabled selected value="">Discount For</option>
                                                    <option value="Per Category">Per Category</option>
                                                    <option value="All Items">All Items</option>
                                                </select>
                                                <br/>
                                                <div id="categories" style="display: none">
                                                    <label>Categories</label><br/>
                                                    <input class="d-none" type="text" name="selected_categories" id="selected_categories" value="{{ $on_sale->apply_discount_to }}">
                                                    @forelse ($categories as $cat)
                                                        <input type="checkbox" name="category" class="categories" value="{{ $cat->id }}" {{ in_array($cat->id, $discounted_categories) ? 'checked' : '' }}> {{ $cat->name }}<br/>
                                                    @empty
                                                        No Published Category
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="col-6 ">
                                                <label><input type="checkbox" name="set_duration" id="set_duration" {{ $on_sale->start_date ? 'checked' : '' }}> Set Sale Duration</label>
                                                <input type="text" class="form-control set_duration" id="daterange" name="sale_duration" {{ $on_sale->start_date ? '' : 'disabled' }}/>
                                                <br/>
                                                <label><input type="checkbox" name="require_coupon" id="require_coupon" {{ count($vouchers) == 0 ? 'disabled' : '' }} {{ $on_sale->coupon ? 'checked' : '' }}> Require Coupon</label>
                                                <select class="form-control" name="coupon" id="coupon" {{ $on_sale->coupon ? '' : 'disabled' }}>
                                                    <option disabled selected value="">Select a Voucher</option>
                                                    @forelse ($vouchers as $voucher)
                                                        <option value="{{ $voucher->id }}" {{ $voucher->id == $on_sale->coupon ? 'selected' : '' }}>{{ $voucher->name }}</option>
                                                    @empty
                                                        <option disabled value="">No Vouchers</option>
                                                    @endforelse
                                                </select>
                                                <br/>
                                                <label>Banner Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="banner_img">
                                                    <label class="custom-file-label" for="customFile">{{ $on_sale->banner_image ? $on_sale->banner_image : 'Choose File' }}</label>
                                                </div>
                                                <div class="col-6 mx-auto">
                                                    <img class="img-thumbnail" src="{{ asset('/assets/site-img/'.$on_sale->banner_image) }}" alt="" style="width: 100%">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <br/>
                                                <div class="float-right font-italic">
                                                    <small>Last modified by: {{ $on_sale->last_modified_by }} - {{ $on_sale->last_modified_at }}</small><br>
                                                    <small>Created by: {{ $on_sale->created_by }} - {{ $on_sale->created_at }}</small>
                                                </div>
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
        $('#discount_type').val("{{ $on_sale->discount_type }}");
        $('#discount_for').val("{{ $on_sale->discount_for }}");

        discountType();
        discountFor();

        var start = "{{ $on_sale->start_date }}";
        var end = "{{ $on_sale->end_date }}";

        $(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                placeholder: 'Select Date Range',
                startDate: start ? start : moment(),
                endDate: end ? end : moment().add(7, 'days'),
            });
        });

        $('#discount_type').change(function(){
            discountType();
        });

        $('#discount_for').change(function(){
            discountFor();
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

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").change(function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
    </script>
@endsection