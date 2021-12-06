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
                                            <div class="col-4">
                                                <label>Sale Name</label>
                                                <input type="text" class="form-control" name="sale_name" placeholder="Sale Name" value="{{ $on_sale->sale_name }}" required>
                                            </div>
                                            <div class="col-4">
                                                <label>Set Sale Duration</label>
                                                <input type="text" class="form-control set_duration" id="daterange" name="sale_duration"/>
                                            </div>
                                            <div class="col-4">
                                                @php
                                                    $discount_for = array('General', 'Member', 'Guest');
                                                @endphp
                                                <label>Discount For</label>
                                                <select class="form-control" name="discount_for" required>
                                                    <option disabled selected value="">Discount For</option>
                                                    @foreach ($discount_for as $for)
                                                        <option value="{{ $for }}" {{ $for == $on_sale->discount_for ? 'selected' : '' }}>{{ $for }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <br/>
                                        <div class="row">
                                            <div class="col-1">
                                                <img class="img-thumbnail" src="{{ asset('/assets/site-img/'.$on_sale->banner_image) }}" alt="" style="width: 100%">
                                            </div>
                                            <div class="col-5">
                                                <label>Banner Image</label>
                                                <div class="custom-file mb-3">
                                                    <input type="file" class="custom-file-input" id="customFile" name="banner_img">
                                                    <label class="custom-file-label" for="customFile">{{ $on_sale->banner_image ? $on_sale->banner_image : 'Choose File' }}</label>
                                                </div>
                                            </div>
                                            
                                            <div class="col-6">
                                                <label>Apply Discount To</label>
                                                <select class="form-control" name="apply_discount_to" id="apply_discount_to" required>
                                                    <option disabled selected value="">Apply Discount To</option>
                                                    <option value="Per Category">Per Category</option>
                                                    <option value="All Items">All Items</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" id="for_all_items">
                                            
                                            <div class="col-6 mx-auto">
                                                <br/>
                                                <label>Discount Type</label>
                                                @php
                                                    $discount_type = array('Fixed Amount', 'By Percentage');
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
                                                    <input type="text" class="form-control" id="discount_amount" name="discount_amount" value="{{ $on_sale->discount_type == 'Fixed Amount' ? $on_sale->discount_rate : '' }}" placeholder="Amount">
                                                </div>
                                                <div id="percentage" class="row">
                                                    <div class="col-12"><br/></div>
                                                    <div class="col-6">
                                                        <label>Percentage</label>
                                                        <input type="text" class="form-control" id="discount_percentage" name="discount_percentage" value="{{ $on_sale->discount_type == 'By Percentage' ? $on_sale->discount_rate : '' }}" placeholder="Percentage">
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Capped Amount</label>
                                                        <input type="text" class="form-control" id="capped_amount" name="capped_amount" value="{{ $on_sale->capped_amount }}" placeholder="Capped Amount"/>
                                                    </div>
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
                                                    @php
                                                        $discount_type = array('Fixed Amount', 'By Percentage');
                                                    @endphp
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
                                                                    <th style="width: 30%;" scope="col" class="text-center">Category Name</th>
                                                                    <th style="width: 20%;" scope="col" class="text-center">Discount Type</th>
                                                                    <th style="width: 20%;" scope="col" class="text-center">Amount/Rate</th>
                                                                    <th style="width: 20%;" scope="col" class="text-center capped_amount">Capped Amount</th>
                                                                    <th class="text-center" style="width: 10%;"><button class="btn btn-outline-primary btn-sm" id="add-categories-btn">Add</button></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($discounted_categories as $sale_cat) 
                                                                <tr>
                                                                   <td class="p-2">
                                                                        <select class="form-control" name="selected_category[]">
                                                                            <option disabled value="">Select Category</option>
                                                                            @foreach ($categories as $category)
                                                                                <option value="{{ $category->id }}" {{ $sale_cat->id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="p-2">
                                                                        <select class="form-control category_discount_type" name="selected_discount_type[]" id="selected_discount_type">
                                                                            <option disabled selected value="">Select Discount Type</option>
                                                                            @foreach ($discount_type as $discount)
                                                                                <option value="{{ $discount }}" {{ $discount == $sale_cat->discount_type ? 'selected' : '' }}>{{ $discount }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="p-2">
                                                                        <input type="text" name="category_discount_rate[]" class="form-control" value="{{ $sale_cat->discount_rate }}" placeholder="Amount/Rate" required>
                                                                    </td>
                                                                    <td class="p-2">
                                                                        <input type="text" name="category_capped_amount[]" class="form-control cap_amount" value="{{ $sale_cat->capped_amount }}" placeholder="Capped Amount">
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
                                        </div>
                                        <div class="row">
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
        $('#apply_discount_to').val("{{ $on_sale->apply_discount_to }}");

        discountType();
        applyDiscountTo();

        var start = "{{ $on_sale->start_date ? date('m/d/Y', strtotime($on_sale->start_date)) : null  }}";
        var end = "{{ $on_sale->end_date ? date('m/d/Y', strtotime($on_sale->end_date)) : null }}";
        console.log(start + end);
        $(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                placeholder: 'Select Date Range',
                startDate: start ? start : moment(),
                endDate: end ? end : moment().add(7, 'days'),
            });
        });

        $('#apply_discount_to').change(function(){
            applyDiscountTo();
        });

        $('#discount_type').change(function(){
            discountType();
        });

        $(document).on('change', '.category_discount_type', function(e){
			e.preventDefault();
            if($(this).val() == 'Fixed Amount'){
                $(this).closest('td').next('td').next('td').find('input').prop('readonly', true);
            }else{
                $(this).closest('td').next('td').next('td').find('input').prop('readonly', false);
            }
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

        function applyDiscountTo(){
            if($('#apply_discount_to').val() == 'All Items'){
                $('#for_all_items').slideDown();
                $('#categories').slideUp();
                $('#discount_type').prop('required', true);
            }else if($('#apply_discount_to').val() == 'Per Category'){
                $('#for_all_items').slideUp();
                $('#categories').slideDown();
                $('#discount_type').prop('required', false);
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }else{
                $('#for_all_items').slideUp();
                $('#categories').slideUp();
                $('#discount_type').prop('required', false);
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }
        }

        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").change(function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
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