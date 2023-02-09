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
                                            <div class="col-9"><h4>Coupon Details</h4></div>
                                            <div class="col-3 text-right">
                                                <button class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Submit</button>
                                            </div>
                                        </div><hr/>
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
                                                <input type="text" class="form-control" name="minimum_spend" value="{{ $coupon->minimum_spend }}" placeholder="Minimum Spend" required>
                                            </div>
                                        </div>
                                        <div id="fixed_amount" class="row mt-3">
                                            <div class="col-6">
                                                <label>Amount *</label>
                                                <input type="text" class="form-control" id="discount_amount" value="{{ $coupon->discount_type == 'Fixed Amount' ? $coupon->discount_rate : ''  }}" name="discount_amount" placeholder="Amount">
                                            </div>
                                        </div>
                                        <div id="percentage" class="row mt-3">
                                            <div class="col-6">
                                                <label>Percentage *</label>
                                                <input type="text" class="form-control" id="discount_percentage" value="{{ $coupon->discount_type == 'By Percentage' ? $coupon->discount_rate : ''  }}" name="discount_percentage" placeholder="Percentage">
                                            </div>
                                            <div class="col-6">
                                                <label>Capped Amount</label>
                                                <input type="text" class="form-control" name="capped_amount" value="{{ $coupon->capped_amount }}" id="capped_amount" placeholder="Capped Amount"/>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <h4>Validity and Usage</h4>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-4">
                                                <label><input type="checkbox" name="require_validity" id="require_validity" {{ $coupon->validity_date_start ? 'checked' : '' }}> Set Validity</label>
                                                <input type="text" class="form-control set_duration" id="daterange" name="validity" disabled/>
                                            </div>

                                            <div class="col-4">
                                                <label><input type="checkbox" name="unlimited_allotment" id="unlimited_allotment" {{ $coupon->unlimited == 1 ? 'checked' : '' }}> Unlimited Allotment</label>
                                                <input type="number" class="form-control" name="allotment" id="allotment" value="{{ $coupon->total_allotment }}" placeholder="Allotment">
                                            </div>
                                            
                                            <div class="col-4">
                                                <label>Priority Number</label>
                                                <input type="text" class="form-control" name="order_no" placeholder="Priority Number" value="{{ $coupon->order_no != 'P' ? $coupon->order_no : null }}">
                                            </div> 
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Coupon Description *</label>
                                                <textarea class="form-control page-content" rows="10" name="coupon_description">{{ $coupon->description }}</textarea>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <h4>Coupon Type</h4>
                                        <hr>
                                        <br/>
                                        <div class="row">
                                            <div class="col-6 mx-auto">
                                                <div class="row">
                                                    <div class="col-8 mx-auto">
                                                        <label>Coupon Type *</label>
                                                        @php
                                                            $coupon_type = array('Promotional', 'Per Category', 'Per Item', 'Per Customer Group');
                                                        @endphp
                                                        <select class="form-control" name="coupon_type" id="coupon_type" required>
                                                            <option disabled value="">Coupon Type</option>
                                                            @foreach ($coupon_type as $coup_type)
                                                                <option value="{{ $coup_type }}" {{ $coup_type == $coupon->coupon_type ? 'selected' : '' }}>{{ $coup_type }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <label>Allowed Usage per Account *</label>
                                                        <input type="text" class="form-control" name="allowed_usage" id="allowed_usage" placeholder="Allowed Usage" value="{{ $coupon->allowed_usage }}" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="categories" class="row">
                                            <select class="d-none form-control" name="categories_select" id="categories_select">
                                                <option disabled selected value="">Select a Category</option>
                                                @foreach ($categories_list as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="col-6 mx-auto">
                                                <br/>
                                                <table class="table table-bordered" id="categories-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 40%;" scope="col" class="text-center">Category</th>
                                                            <th class="text-center" style="width: 10%;"><button class="btn btn-outline-primary btn-sm" id="add-categories-btn">Add</button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($selected_categories as $selected_category) 
                                                            <tr>
                                                                <td class="p-2">
                                                                    <select class="form-control" name="selected_category[]" id="categories_select">
                                                                        <option disabled value="">Select a Category</option>
                                                                        @foreach ($categories_list as $category)
                                                                            <option value="{{ $category->id }}" {{ $category->id == $selected_category->exclusive_to ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="items" class="row">
                                            <div class="col-4 mx-auto">
                                                <br/>
                                                <select class="coupon_per_item w-100" name="selected_item[]" id="items_select" multiple="multiple">
                                                    @foreach ($selected_items as $selected_item)
                                                        @php
                                                            $product_name = collect($item_list)->where('f_idcode', $selected_item->exclusive_to)->pluck('f_name_name')->first();
                                                        @endphp
                                                        <option value="{{ $selected_item->exclusive_to }}" selected>{{ $selected_item->exclusive_to.' - '.$product_name }}</option>
                                                    @endforeach
                                                    @foreach ($item_list as $item)
                                                        <option value="{{ $item->f_idcode }}">{{ $item->f_idcode.' - '.$item->f_name_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="customer-group" class="row">
                                            <div class="col-6 mx-auto">
                                                <select class="d-none w-100" id="customer_group_select">
                                                    @foreach ($customer_groups as $group)
                                                        <option value="{{ $group->customer_group_name }}">{{ $group->customer_group_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="col-6 mx-auto">
                                                    <br/>
                                                    <table class="table table-bordered" id="customer-group-table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">Customer Group</th>
                                                                <th class="text-center" style="width: 10%;"><button class="btn btn-outline-primary btn-sm" id="add-customer-group-btn">Add</button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($selected_customer_groups as $selected_customer_group) 
                                                                <tr>
                                                                    <td class="p-2">
                                                                        <select class="form-control" name="selected_customer_group[]" id="customer_group_select">
                                                                            <option disabled value="">Select a Category</option>
                                                                            @foreach ($customer_groups as $customer_group)
                                                                                <option value="{{ $customer_group->id }}" {{ $customer_group->id == $selected_customer_group->exclusive_to ? 'selected' : '' }}>{{ $customer_group->customer_group_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="for_promotional" class="row mt-3">
                                            <div class="col-6 row mx-auto">
                                                <div class="col-6">
                                                    <label><input type="checkbox" name="require_signin" id="require_signin" {{ $coupon->require_signin ? 'checked' : '' }}> Require Sign in</label>
                                                </div>
                                                <div class="col-6">
                                                    <label><input type="checkbox" name="auto_apply" {{ $coupon->auto_apply ? 'checked' : '' }}> Auto-apply in Checkout</label>
                                                </div>
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
        $('.coupon_per_item').select2({placeholder: 'Select Item(s)'});
        allotment();
        validityDate();
        discountType();
        discountFor();
        couponType();

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

        $('#coupon_type').click(function(){
            couponType();
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

        function couponType(){
            if($('#coupon_type').val() == 'Per Category'){
                $('#categories').slideDown();
                $('#for_promotional').slideUp();
                $('#items').slideUp();
                $('#customer-group').slideUp();
            }else if($('#coupon_type').val() == 'Per Item'){
                $('#items').slideDown();
                $('#categories').slideUp();
                $('#for_promotional').slideUp();
                $('#customer-group').slideUp();
            }else if($('#coupon_type').val() == 'Per Customer Group'){
                $('#customer-group').slideDown();
                $('#items').slideUp();
                $('#categories').slideUp();
                $('#for_promotional').slideUp();
            }else{
                $('#for_promotional').slideDown();
                $('#categories').slideUp();
                $('#items').slideUp();
                $('#customer-group').slideUp();
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

        $(".page-content").summernote({
            dialogsInBody: true,
            dialogsFade: true,
            height: "500px",
        });

        $('#add-categories-btn').click(function(e){
			e.preventDefault();

			var clone_select = $('#categories_select').html();
			var row = '<tr>' +
				'<td class="p-2">' +
					'<select name="selected_category[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
				'</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$('#categories-table tbody').append(row);
		});

        $('#add-customer-group-btn').click(function(e){
			e.preventDefault();

			var clone_select = $('#customer_group_select').html();
			var row = '<tr>' +
				'<td class="p-2">' +
					'<select name="selected_customer_group[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
				'</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$('#customer-group-table tbody').append(row);
		});

        $(document).on('click', '.remove-td-row', function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
		});
    });
    </script>
@endsection