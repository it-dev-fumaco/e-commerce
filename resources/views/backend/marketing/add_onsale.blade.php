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
                                            <div class="col-4">
                                                <label>Sale Name *</label>
                                                <input type="text" class="form-control" name="sale_name" placeholder="Sale Name" required>
                                            </div>
                                            <div class="col-4">
                                                <label>Set Sale Duration</label>
                                                <input type="text" class="form-control set_duration" id="daterange" name="sale_duration" required/>
                                            </div>
                                            <div class="col-4">
                                                @php
                                                    $discount_for = array('General', 'Member', 'Guest');
                                                @endphp
                                                <label>Discount For *</label>
                                                <select class="form-control" name="discount_for" required>
                                                    <option disabled selected value="">Discount For</option>
                                                    @foreach ($discount_for as $for)
                                                        <option value="{{ $for }}">{{ $for }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Apply Discount To *</label>
                                                <select class="form-control" name="apply_discount_to" id="apply_discount_to" required>
                                                    <option disabled selected value="">Apply Discount To</option>
                                                    <option value="Per Category">Per Category</option>
                                                    <option value="All Items">All Items</option>
                                                </select>
                                                <div id="for_all_items">
                                                    <br/>&nbsp;
                                                    <label>Discount Type *</label>
                                                    @php
                                                        $discount_type = array('Fixed Amount', 'By Percentage');
                                                    @endphp
                                                    <select class="form-control" name="discount_type" id="discount_type">
                                                        <option disabled selected value="">Discount Type</option>
                                                        @foreach ($discount_type as $discount)
                                                            <option value="{{ $discount }}">{{ $discount }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div id="fixed_amount">
                                                        <br>&nbsp;
                                                        <label>Amount *</label>
                                                        <input type="text" class="form-control" id="discount_amount" name="discount_amount" placeholder="Amount">
                                                    </div>
                                                    <div id="percentage" style="display: none">
                                                        <br>
                                                        <label>Percentage *</label>
                                                        <input type="text" class="form-control" id="discount_percentage" name="discount_percentage" placeholder="Percentage">
                                                        <br/>
                                                        <label>Capped Amount</label>
                                                        <input type="text" class="form-control" name="capped_amount" id="capped_amount" placeholder="Capped Amount"/>
                                                    </div>
                                                </div>
                                                <br/>
                                            </div>
                                            <div class="col-6">
                                                <label>Banner Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="banner_img">
                                                    <label class="custom-file-label" for="customFile">Choose File</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="categories">
                                            <div class="col-12">
                                                <div class="row">
                                                    <select class="d-none form-control" name="category_select" id="category_select">
                                                        <option disabled selected value="">Select a Category</option>
                                                        @foreach ($categories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select class="d-none form-control" name="discount_type_select" id="discount_type_select">
                                                        <option disabled selected value="">Select Discount Type</option>
                                                        @foreach ($discount_type as $discount)
                                                            <option value="{{ $discount }}">{{ $discount }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="col-8 mx-auto">
                                                        <br/>
                                                        <table class="table table-bordered" id="categories-table">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width: 20%;" scope="col" class="text-center">Category Name</th>
                                                                    <th style="width: 20%;" scope="col" class="text-center">Discount Type</th>
                                                                    <th style="width: 25%;" scope="col" class="text-center">Amount/Rate</th>
                                                                    <th style="width: 25%;" scope="col" class="text-center capped_amount">Capped Amount</th>
                                                                    <th class="text-center" style="width: 10%;"><button class="btn btn-outline-primary btn-sm" id="add-categories-btn">Add</button></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
    
                                                            </tbody>
                                                        </table>
                                                    </div>
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
        discountType();
        applyDiscountTo();
        requireCoupon();

        $(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                placeholder: 'Select Date Range',
                startDate: moment(), endDate: moment().add(7, 'days'),
            });
        });

        $('#discount_type').change(function(){
            discountType();
        });

        $('#apply_discount_to').change(function(){
            applyDiscountTo();
        });

        $('#require_coupon').click(function(){
            requireCoupon();
        });
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").change(function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
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

        function requireCoupon(){
            if($('#require_coupon').is(':checked')){
                $("#coupon").prop('required',true);
                $("#coupon").prop('disabled',false);
            }else{
                $("#coupon").prop('required',false);
                $("#coupon").prop('disabled',true);
            }
        }

        function applyDiscountTo(){
            if($('#apply_discount_to').val() == 'All Items'){
                $('#for_all_items').slideDown();
                $('#categories').slideUp();
                $('#discount_type').prop('required', true);
            }else if($('#apply_discount_to').val() == 'Per Category'){
                $('#for_all_items').slideUp();
                $('#categories').slideDown();
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }else{
                $('#for_all_items').slideUp();
                $('#categories').slideUp();
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }
        }

        $(document).on('change', '.category_discount_type', function(e){
			e.preventDefault();
            if($(this).val() == 'Fixed Amount'){
                $(this).closest('td').next('td').next('td').find('input').prop('readonly', true);
            }else{
                $(this).closest('td').next('td').next('td').find('input').prop('readonly', false);
            }
		});

        $('#add-categories-btn').click(function(e){
			e.preventDefault();

			var clone_select = $('#category_select').html();
            var clone_discount_type = $('#discount_type_select').html();
			var row = '<tr>' +
				'<td class="p-2">' +
					'<select name="selected_category[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
				'</td>' +
				'<td class="p-2">' +
					'<select name="selected_discount_type[]" class="form-control w-100 category_discount_type" style="width: 100%;" required>' + clone_discount_type + '</select>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="number" name="category_discount_rate[]" class="form-control" placeholder="Amount/Rate" required>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="number" name="category_capped_amount[]" class="form-control cap_amount" value="0" placeholder="Capped Amount">' +
				'</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$('#categories-table tbody').append(row);
		});

        $(document).on('click', '.remove-td-row', function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
		});
    });
    </script>
@endsection