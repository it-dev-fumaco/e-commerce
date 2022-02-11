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
                                    <form action="/admin/marketing/voucher/add" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-9"><h4>Coupon Details</h4></div>
                                            <div class="col-3 text-right">
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div><hr/>
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Name *</label>
                                                <input type="text" class="form-control" name="name" placeholder="Name" required>
                                            </div>

                                            <div class="col-6">
                                                <label>Coupon Code *</label>
                                                <input type="text" class="form-control" name="coupon_code" placeholder="Coupon Code" required>
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
                                                    <option disabled selected value="">Discount Type</option>
                                                    @foreach ($discount_type as $discount)
                                                        <option value="{{ $discount }}">{{ $discount }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-6">
                                                <label>Minimum Spend *</label>
                                                <input type="text" class="form-control" name="minimum_spend" placeholder="Minimum Spend" required>
                                            </div>
                                        </div>
                                        <div id="fixed_amount" class="row">
                                            <br>&nbsp;
                                            <div class="col-12">
                                                <label>Amount *</label>
                                                <input type="text" class="form-control" id="discount_amount" name="discount_amount" placeholder="Amount">
                                            </div>
                                        </div>
                                        <div id="percentage" class="row">
                                            <div class="col-12"><br/></div>
                                            <div class="col-6">
                                                <label>Percentage *</label>
                                                <input type="text" class="form-control" id="discount_percentage" name="discount_percentage" placeholder="Percentage">
                                            </div>
                                            <div class="col-6">
                                                <label>Capped Amount</label>
                                                <input type="text" class="form-control" name="capped_amount" id="capped_amount" value="0" placeholder="Capped Amount"/>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <h4>Validity and Usage</h4>
                                        <hr/>
                                        <div class="row">
                                            <div class="col-6">
                                                <label><input type="checkbox" name="require_validity" id="require_validity"> Set Validity</label>
                                                <input type="text" class="form-control set_duration" id="daterange" name="validity" disabled/>                                                
                                            </div>

                                            <div class="col-6">
                                                <label><input type="checkbox" name="unlimited_allotment" id="unlimited_allotment" checked> Unlimited Allotment</label>
                                                <br/>
                                                <input type="number" class="form-control" name="allotment" id="allotment" placeholder="Allotment">
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Coupon Description *</label>
                                                <textarea class="form-control page-content" rows="10" name="coupon_description"></textarea>
                                            </div>
                                        </div>
                                        <br/><br/>
                                        <h4>Coupon Type</h4>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6 mx-auto">
                                                <div class="row">
                                                    @php
                                                        $coupon_type = array('Promotional', 'Per Category', 'Per Item', 'Per Customer Group');
                                                    @endphp
                                                    <div class="col-8 mx-auto">
                                                        <label>Coupon Type *</label>
                                                        <select class="form-control" name="coupon_type" id="coupon_type" required>
                                                            <option disabled value="">Coupon Type</option>
                                                            @foreach ($coupon_type as $coupon)
                                                                <option value="{{ $coupon }}" {{ $coupon == 'Promotional' ? 'selected' : '' }}>{{ $coupon }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-4">
                                                        <label>Allowed Usage per Account *</label>
                                                        <input type="text" class="form-control" name="allowed_usage" id="allowed_usage" placeholder="Allowed Usage" value="1" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="categories" class="row">
                                            <select class="d-none form-control" name="category_select" id="category_select">
                                                <option disabled selected value="">Select a Category</option>
                                                @foreach ($category_list as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="col-6 mx-auto">
                                                <br/>
                                                <table class="table table-bordered" id="categories-table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center">Category</th>
                                                            <th class="text-center" style="width: 10%;"><button class="btn btn-outline-primary btn-sm" id="add-categories-btn">Add</button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div id="for_promotional" class="row">
                                            <div class="col-6 mx-auto">
                                                <br/>
                                                <label><input type="checkbox" name="require_signin" id="require_signin"> Require Sign in</label>
                                            </div>
                                        </div>
                                        <div id="items" class="row">
                                            <div class="col-4 mx-auto">
                                                <br/>
                                                <select class="coupon_per_item w-100" name="selected_item[]" id="items_select" multiple="multiple">
                                                    @foreach ($item_list as $item)
                                                        <option value="{{ $item->f_idcode }}">{{ $item->f_idcode.' - '.$item->f_name_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div id="customer-group" class="row">
                                            <div class="col-6 mx-auto">
                                                <select class="d-none w-100" id="customer_group_select">
                                                    @foreach ($customer_group as $group)
                                                        <option value="{{ $group->id }}">{{ $group->customer_group_name }}</option>
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
    
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-12">
                                                <label>Remarks</label>
                                                <textarea name="remarks" cols="3" rows="3" class="form-control" placeholder="Remarks"></textarea>
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

        $('#daterange').daterangepicker({
            opens: 'left',
            placeholder: 'Select Date Range',
            startDate: moment(), endDate: moment().add(7, 'days'),
        });

        $(".page-content").summernote({
            dialogsInBody: true,
            dialogsFade: true,
            height: "300px",
        });

        $('#add-categories-btn').click(function(e){
			e.preventDefault();

			var clone_select = $('#category_select').html();
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