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
                            <div class="alert alert-success fade show" role="alert">{{ session()->get('success') }}</div>
                            @endif
                            @if(session()->has('error'))
                            <div class="alert alert-warning fade show" role="alert">{{ session()->get('error') }}</div>
                            @endif
                            <div class="card-body">
                                <form action="/admin/marketing/on_sale/add" method="post" enctype="multipart/form-data" autocomplete="off">
                                    @csrf
                                    <div class="row">
                                        <div class="col-9"><h4>On Sale Details</h4></div>
                                        <div class="col-3 text-right">
                                            <button class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Submit</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Sale Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="sale_name" placeholder="Sale Name" required>
                                        </div>
                                        <div class="col-6">
                                            <label>Set Sale Duration <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control set_duration" id="daterange" name="sale_duration" required/>
                                            <div class="form-check mt-1">
                                                <input type="checkbox" class="form-check-input" id="ignore-sale-duration" name="ignore_sale_duration" value="1">
                                                <label class="form-check-label" for="ignore-sale-duration">Ignore Sale Duration</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        {{-- <div class="col-6">
                                            @php
                                                $sale_types = ['Regular Sale', 'Clearance Sale'];
                                            @endphp
                                            <label>Sale Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="sale_type" id="sale-type" required>
                                                <option disabled selected value="">Sale Type</option>
                                                @foreach ($sale_types as $s_type)
                                                <option value="{{ $s_type }}">{{ $s_type }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col-6">
                                            <label>Desktop Banner Image (1920 x 377)</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile" name="banner_img">
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label>Mobile Banner Image (428 x 100)</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile2" name="mob_banner_img">
                                                <label class="custom-file-label" for="customFile">Choose File</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            @php
                                                $sale_types = ['Regular Sale', 'Clearance Sale'];
                                            @endphp
                                            <label>Sale Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="sale_type" id="sale-type" required>
                                                <option disabled selected value="">Sale Type</option>
                                                @foreach ($sale_types as $s_type)
                                                <option value="{{ $s_type }}">{{ $s_type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            @php
                                                $types = ['Per Customer Group', 'Per Shipping Service', 'Per Category', 'Selected Items', 'All Items'];
                                            @endphp
                                            <label>Apply Discount To <span class="text-danger">*</span></label>
                                            <select class="form-control" name="apply_discount_to" id="apply_discount_to" required>
                                                <option disabled selected value="">Apply Discount To</option>
                                                @foreach ($types as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                            <div id="for_all_items">
                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <label>Discount Type <span class="text-danger">*</span></label>
                                                        @php
                                                            $discount_type = array('Fixed Amount', 'By Percentage');
                                                        @endphp
                                                        <select class="form-control" name="discount_type" id="discount_type">
                                                            <option disabled selected value="">Discount Type</option>
                                                            @foreach ($discount_type as $discount)
                                                            <option value="{{ $discount }}">{{ $discount }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-4" id="fixed_amount">
                                                    <div class="col-6">
                                                        <label>Amount <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="discount_amount" name="discount_amount" placeholder="Amount">
                                                    </div>
                                                </div>
                                                <div class="row mt-4" id="percentage" style="display: none;">
                                                    <div class="col-6">
                                                        <label>Percentage <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control discount-rate" id="discount_percentage" name="discount_percentage" placeholder="Percentage">
                                                        <span class="text-danger d-none" style="font-size: 9pt;">Percentage discount cannot be more than or equal to 100%</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Capped Amount</label>
                                                    <input type="text" class="form-control" name="capped_amount" id="capped_amount" placeholder="Capped Amount"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="categories">
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
                                                    <table class="table table-bordered" id="categories-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Category Name</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Discount Type</th>
                                                                <th style="width: 25%;" scope="col" class="text-center p-2 align-middle">Amount/Rate</th>
                                                                <th style="width: 25%;" scope="col" class="text-center p-2 align-middle capped_amount">Capped Amount</th>
                                                                <th style="width: 10%;" scope="col" class="text-center p-2 align-middle">
                                                                    <button type="button" class="add-row-btn btn btn-outline-primary btn-sm" id="add-categories-btn" data-table="#categories-table" data-select="#category_select" style="white-space: nowrap"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="customer-groups">
                                        <div class="col-12">
                                            <div class="row">
                                                <select class="d-none form-control" id="customer-group-select">
                                                    <option disabled selected value="">Select Customer Group</option>
                                                    @foreach ($customer_groups as $cg)
                                                        <option value="{{ $cg->id }}">{{ $cg->customer_group_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="col-8 mx-auto">
                                                    <table class="table table-bordered" id="customer-group-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Customer Group</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Discount Type</th>
                                                                <th style="width: 25%;" scope="col" class="text-center p-2 align-middle">Amount/Rate</th>
                                                                <th style="width: 25%;" scope="col" class="text-center p-2 align-middle capped_amount">Capped Amount</th>
                                                                <th style="width: 10%;" scope="col" class="text-center p-2 align-middle">
                                                                    <button type="button" class="add-row-btn btn btn-outline-primary btn-sm" id="add-customer-group-btn" data-table="#customer-group-table" data-select="#customer-group-select" style="white-space: nowrap"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="shipping-service">
                                        <div class="col-12">
                                            <div class="row">
                                                <select class="d-none form-control" name="shipping_select" id="shipping_select">
                                                    <option disabled selected value="">Select a Shipping Service</option>
                                                    @foreach ($shipping_services as $shipping_service)
                                                        <option value="{{ $shipping_service }}">{{ $shipping_service }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="col-8 mx-auto">
                                                    <table class="table table-bordered" id="shipping-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Shipping Service Name</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Discount Type</th>
                                                                <th style="width: 25%;" scope="col" class="text-center p-2 align-middle">Amount/Rate</th>
                                                                <th style="width: 25%;" scope="col" class="text-center p-2 align-middle capped_amount">Capped Amount</th>
                                                                <th style="width: 10%;" scope="col" class="text-center p-2 align-middle">
                                                                    <button type="button" class="btn btn-outline-primary btn-sm add-row-btn" id="add-shipping-btn" data-table="#shipping-table" data-select="#shipping_select" style="white-space: nowrap"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3" id="selected-items">
                                        <div class="col-12">
                                            <div class="row">
                                                <select class="d-none form-control" name="item_code_select" id="item_code_select">
                                                    <option disabled selected value="">Select Item</option>
                                                    @foreach ($items as $item)
                                                    <option value="{{ $item->f_idcode }}">{{ $item->f_idcode }}</option>
                                                    @endforeach
                                                </select>
                                                <select class="d-none form-control" name="discount_type_select" id="discount_type_select">
                                                    <option disabled selected value="">Select Discount Type</option>
                                                    @foreach ($discount_type as $discount)
                                                    <option value="{{ $discount }}">{{ $discount }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="col-9 mx-auto">
                                                    <table class="table table-bordered" id="selected-items-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Item Code</th>
                                                                <th style="width: 24%;" scope="col" class="text-center p-2 align-middle">Item Description</th>
                                                                <th style="width: 18%;" scope="col" class="text-center p-2 align-middle">Discount Type</th>
                                                                <th style="width: 15%;" scope="col" class="text-center p-2 align-middle">Amount/Rate</th>
                                                                <th style="width: 15%;" scope="col" class="text-center p-2 align-middle capped_amount">Capped Amount</th>
                                                                <th style="width: 8%;" scope="col" class="text-center p-2 align-middle">
                                                                    <button type="button" class="add-row-btn btn btn-outline-primary btn-sm" id="add-selected-items-btn" data-table="#selected-items-table" data-select="#item_code_select" style="white-space: nowrap"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <hr>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h4>Email Notification</h4>
                                        </div>
                                        <div class="col-4">
                                            <label>Email Template</label>
                                            <select class="form-control" name="email_template" required>
                                                <option disabled selected value="">Select Template</option>
                                                @foreach ($templates as $template)
                                                    @if (!$template['template_id'] or $template['template_type'] != 'user')
                                                        @continue
                                                    @endif
                                                    <option value="{{ $template['template_id'] }}">{{ $template['template_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label>Tag</label>
                                            <select class="form-control" name="email_tag" required>
                                                <option disabled selected value="">Select a Tag</option>
                                                @foreach ($tags as $tag)
                                                    @if (!$tag['list_id'])
                                                        @continue
                                                    @endif
                                                    <option value="{{ $tag['list_id'] }}">{{ $tag['list_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label>Email Notification Schedule</label>
                                            <input type="text" class="form-control" id="notif-schedule" name="notif_schedule" required/>
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
            var year = new Date().getFullYear();

            $('#daterange').daterangepicker({
                opens: 'left',
                placeholder: 'Select Date Range',
                startDate: moment(), endDate: moment().add(7, 'days'),
                minDate: moment()
            });

            $('#notif-schedule').daterangepicker({
                placeholder: 'Select Date',
                singleDatePicker: true,
                showDropdowns: true,
                minYear: year,
                maxYear: parseInt(year) + 10,
                startDate: moment()
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
                $('#customer-groups').slideUp();
                $('#categories').slideUp();
                $('#selected-items').slideUp();
                $('#shipping-service').slideUp();
                $('#discount_type').prop('required', true);
            }else if($('#apply_discount_to').val() == 'Per Category'){
                $('#for_all_items').slideUp();
                $('#customer-groups').slideUp();
                $('#categories').slideDown();
                $('#selected-items').slideUp();
                $('#shipping-service').slideUp();
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }else if($('#apply_discount_to').val() == 'Per Customer Group'){
                $('#for_all_items').slideUp();
                $('#categories').slideUp();
                $('#selected-items').slideUp();
                $('#customer-groups').slideDown();
                $('#shipping-service').slideUp();
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }else if($('#apply_discount_to').val() == 'Per Shipping Service'){
                $('#for_all_items').slideUp();
                $('#categories').slideUp();
                $('#selected-items').slideUp();
                $('#customer-groups').slideUp();
                $('#shipping-service').slideDown();
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }else if($('#apply_discount_to').val() == 'Selected Items'){
                $('#for_all_items').slideUp();
                $('#categories').slideUp();
                $('#customer-groups').slideUp();
                $('#shipping-service').slideUp();
                $('#selected-items').slideDown();
                $('#discount_type').prop('required', false);
                $('#discount_rate').prop('required', false);
                $('#capped_amount').prop('required', false);
            }else{
                $('#for_all_items').slideUp();
                $('#categories').slideUp();
                $('#customer-groups').slideUp();
                $('#shipping-service').slideUp();
                $('#selected-items').slideUp();
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

            discount_rate_checker($(this).closest('td').next('td').find('input'));
		});

        $(document).on('keyup', '.discount-rate', function (){
            discount_rate_checker($(this));
        });

        $(document).on('select2:select', '.custom-select-2', function(e){
            e.preventDefault();
            $(this).closest('td').next('td').next('td').find('input').data('price', e.params.data.default_price);
        });

        function discount_rate_checker(el){
            el.removeClass('border').removeClass('border-danger');
            el.closest('td').find('span').addClass('d-none');

            var cap = 0;
            var err = '';
            switch (el.closest('td').prev().find('select').val()) {
                case 'By Percentage':
                    cap = 100;
                    err = 'Percentage discount cannot be more than or equal to 100%.';
                    break;
                default:
                    cap = el.data('price');
                    err = 'Discount amount cannot be more than the item price.';
                    break;
            }

            if(el.val() >= cap){
                el.addClass('border').addClass('border-danger');
                el.closest('td').find('span').removeClass('d-none').text(err);
            }
        }
        
        function clone_table(table, select){
            var clone_select = $(select).html();
            var clone_discount_type = $('#discount_type_select').html();
            var select_class = select != '#item_code_select' ? select : 'custom-select-2';

            var img_col = '';
            if (select == '#item_code_select') {
                img_col = '<td class="p-2">' +
                    '<div class="d-flex flex-row">' +
                        '<div class="col-3 itemimg"></div>' +
                        '<div class="col-9 itemdesc" style="font-size: 10pt;"></div>' +
                    '</div>'
				'</td>';
            } 
			var row = '<tr>' +
				'<td class="p-2">' +
					'<select name="selected_reference[]" class="form-control w-100 ' + select_class + '" style="width: 100%;" required>' + clone_select + '</select>' +
				'</td>' +
                img_col +
				'<td class="p-2">' +
					'<select name="selected_discount_type[]" class="form-control w-100 category_discount_type" style="width: 100%;" required>' + clone_discount_type + '</select>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="number" name="selected_discount_rate[]" class="form-control discount-rate" placeholder="Amount/Rate" required>' +
                    '<span class="text-danger d-none" style="font-size: 9pt;">Percentage discount cannot be more than or equal to 100%</span>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="number" name="selected_capped_amount[]" class="form-control cap_amount" value="0" placeholder="Capped Amount">' +
				'</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
				'</td>' +
			'</tr>';
            
			$(table).append(row);

            if (select == '#item_code_select') {
                $('.custom-select-2').select2({
                    templateResult: formatState,
                    placeholder: 'Select an Item',
    
                    ajax: {
                        url: '/admin/product/search_item',
                        method: 'GET',
                        dataType: 'json',
                        data: function (data) {
                            return {
                                q: data.term, // search term
                            };
                        },
                        processResults: function (response) {
                            var items = response.items;
                            return {
                                results: items
                            };
                        },
                        cache: true
                    }
                });
            }
        }

        function formatState (opt) {
            var optimage = opt.image;
            if(!optimage){
                return opt.text;
            } else {
                var $opt = $(
                '<div class="d-flex flex-row">' +
	                '<div class="col-4"><img src="' + optimage + '" width="40px" /></div>' +
	                '<div class="col-8" style="font-size: 9pt;">' + opt.text + '</div>' +
                    '</div>'
                );
                return $opt;
            }
        };

        $(document).on('click', '.add-row-btn', function (e){
			e.preventDefault();
            var table = $(this).data('table') + ' tbody';
            var select = $(this).data('select');
            clone_table(table, select);
		});

        $(document).on('click', '.remove-td-row', function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
		});

        $(document).on('change', '#sale-type', function(e) {
            if ($(this).val() == 'Clearance Sale') {
                $('#apply_discount_to').attr('disabled', true).val('Selected Items').trigger('change');
            } else {
                $('#apply_discount_to').attr('disabled', false).val('').trigger('change');
            }
        });

        $(document).on('click', '#ignore-sale-duration', function(e) {
            if ($(this).is(":checked")) {
                $('#daterange').attr('disabled', true);
            } else {
                $('#daterange').removeAttr('disabled');
            }
        });

        $(document).on('select2:select', '.custom-select-2', function(e){
            var data = e.params.data;
            var row = $(this).closest('tr');

            row.find('.itemdesc').eq(0).text(data.description);
            row.find('.itemimg').eq(0).html('<img src="'+ data.image +'" class="img-responsive rounded d-inline-block" width="45" height="45">');
        });
    });
</script>
@endsection