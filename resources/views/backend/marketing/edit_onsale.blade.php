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
                                <div class="alert alert-success fade show" role="alert">{{ session()->get('success') }}</div>
                            @endif
                            @if(session()->has('error'))
                                <div class="alert alert-warning fade show" role="alert">{{ session()->get('error') }}</div>
                            @endif
                            <div class="card-body">
                                <form action="/admin/marketing/on_sale/{{ $on_sale->id }}/edit" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @php
                                    switch ($on_sale->apply_discount_to) {
                                        case 'Per Shipping Service':
                                            $child_table = 'fumaco_on_sale_shipping_service';
                                            break;
                                        case 'Per Category':
                                            $child_table = 'fumaco_on_sale_categories';
                                            break;
                                        case 'Per Customer Group':
                                            $child_table = 'fumaco_on_sale_customer_group';
                                            break;
                                        case 'Selected Items':
                                            $child_table = 'fumaco_on_sale_items';
                                            break;
                                        default:
                                            $child_table = null;
                                            break;
                                    }
                                    @endphp
                                    <input type="text" class="d-none" name="child_table" value="{{ $child_table }}">
                                    <div class="row">
                                        <div class="col-9"><h4>On Sale Details</h4></div>
                                        <div class="col-3 text-right">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Sale Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="sale_name" placeholder="Sale Name" value="{{ $on_sale->sale_name }}" required>
                                            <div class="form-check mt-1">
                                                <input type="checkbox" class="form-check-input" id="is-clearance-sale" name="is_clearance_sale" value="1" {{ $on_sale->is_clearance_sale == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is-clearance-sale">Is Clearance Sale</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label>Set Sale Duration <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control set_duration" id="daterange" name="sale_duration"/>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-6">
                                            @php
                                                $types = ['Per Customer Group', 'Per Shipping Service', 'Per Category', 'Selected Items', 'All Items'];
                                                if ($on_sale->is_clearance_sale == 1) {
                                                    $apply_discount_to = 'Selected Items';
                                                } else {
                                                    $apply_discount_to = $on_sale->apply_discount_to;
                                                }
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
                                                        <input type="text" class="form-control" id="discount_amount" name="discount_amount" value="{{ $on_sale->discount_type == 'Fixed Amount' ? $on_sale->discount_rate : '' }}" placeholder="Amount">
                                                    </div>
                                                </div>
                                                <div class="row mt-4" id="percentage" style="display: none;">
                                                    <div class="col-6">
                                                        <label>Percentage <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="discount_percentage" name="discount_percentage" value="{{ $on_sale->discount_type == 'By Percentage' ? $on_sale->discount_rate : '' }}" placeholder="Percentage">
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Capped Amount</label>
                                                        <input type="text" class="form-control" id="capped_amount" name="capped_amount" value="{{ $on_sale->capped_amount }}" placeholder="Capped Amount"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($on_sale->banner_image)
                                        <div class="col-1">
                                            <img class="img-thumbnail" src="{{ asset('/assets/site-img/'.$on_sale->banner_image) }}" alt="" style="width: 100%">
                                        </div>
                                        @endif
                                        <div class="col-{{ $on_sale->banner_image ? '5' : '6' }}">
                                            <label>Banner Image</label>
                                            <div class="custom-file mb-3">
                                                <input type="file" class="custom-file-input" id="customFile" name="banner_img">
                                                <label class="custom-file-label" for="customFile">{{ $on_sale->banner_image ? $on_sale->banner_image : 'Choose File' }}</label>
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
                                                    <table class="table table-bordered" id="categories-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30%;" scope="col" class="text-center p-2 align-middle">Category Name</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Discount Type</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Amount/Rate</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle capped_amount">Capped Amount</th>
                                                                <th class="text-center p-2 align-middle" style="width: 10%;">
                                                                    <button type="button" class="btn btn-outline-primary btn-sm add-row-btn" id="add-categories-btn" data-table="#categories-table" data-select="#category_select" data-reference="category">Add</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($discounted_categories as $sale_cat) 
                                                            <tr>
                                                                <td class="p-2">
                                                                    <select class="form-control" name="selected_reference[category][]">
                                                                        <option disabled value="">Select Category</option>
                                                                        @foreach ($categories as $category)
                                                                            <option value="{{ $category->id }}" {{ $sale_cat->id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <select class="form-control category_discount_type" name="selected_discount_type[category][]" id="selected_discount_type">
                                                                        <option disabled selected value="">Select Discount Type</option>
                                                                        @foreach ($discount_type as $discount)
                                                                            <option value="{{ $discount }}" {{ $discount == $sale_cat->discount_type ? 'selected' : '' }}>{{ $discount }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_discount_rate[category][]" class="form-control" value="{{ $sale_cat->discount_rate }}" placeholder="Amount/Rate" required>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_capped_amount[category][]" class="form-control cap_amount" value="{{ $sale_cat->capped_amount }}" placeholder="Capped Amount">
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
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
                                                    <table class="table table-bordered" id="selected-items-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 30%;" scope="col" class="text-center p-2 align-middle">Item Code</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Discount Type</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">Amount/Rate</th>
                                                                <th style="width: 20%;" scope="col" class="text-center p-2 align-middle capped_amount">Capped Amount</th>
                                                                <th class="text-center p-2 align-middle" style="width: 10%;">
                                                                    <button type="button" class="add-row-btn btn btn-outline-primary btn-sm" id="add-selected-items-btn" data-table="#selected-items-table" data-select="#item_code_select">Add Row</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($discounted_selected_items as $sale_selected_item) 
                                                            <tr>
                                                                <td class="p-2">
                                                                    <select class="form-control" name="selected_reference[item_code][]">
                                                                        <option disabled value="">Select Item</option>
                                                                        @foreach ($items as $item)
                                                                        <option value="{{ $item->f_idcode }}" {{ $sale_selected_item->item_code == $item->f_idcode ? 'selected' : '' }}>{{ $item->f_idcode }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <select class="form-control category_discount_type" name="selected_discount_type[item_code][]" id="selected_discount_type">
                                                                        <option disabled selected value="">Select Discount Type</option>
                                                                        @foreach ($discount_type as $discount)
                                                                            <option value="{{ $discount }}" {{ $discount == $sale_selected_item->discount_type ? 'selected' : '' }}>{{ $discount }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_discount_rate[item_code][]" class="form-control" value="{{ $sale_selected_item->discount_rate }}" placeholder="Amount/Rate" required>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_capped_amount[item_code][]" class="form-control cap_amount" value="{{ $sale_selected_item->capped_amount }}" placeholder="Capped Amount">
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
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
                                                                <th class="text-center p-2 align-middle" style="width: 10%;">
                                                                    <button type="button" class="btn btn-outline-primary btn-sm add-row-btn" id="add-customer-group-btn" data-table="#customer-group-table" data-select="#customer-group-select" data-reference="customer_group">Add Row</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($discounted_customer_group as $cg)
                                                            <tr>
                                                                <td class="p-2">
                                                                    <select class="form-control" name="selected_reference[customer_group][]">
                                                                        <option disabled value="">Select Customer Group</option>
                                                                        @foreach ($customer_groups as $custgroup)
                                                                            <option value="{{ $custgroup->id }}" {{ $cg->id == $custgroup->id ? 'selected' : '' }}>{{ $custgroup->customer_group_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <select class="form-control category_discount_type" name="selected_discount_type[customer_group][]">
                                                                        <option disabled selected value="">Select Discount Type</option>
                                                                        @foreach ($discount_type as $discount)
                                                                            <option value="{{ $discount }}" {{ $discount == $cg->discount_type ? 'selected' : '' }}>{{ $discount }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_discount_rate[customer_group][]" class="form-control" value="{{ $cg->discount_rate }}" placeholder="Amount/Rate" required>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_capped_amount[customer_group][]" class="form-control cap_amount" value="{{ $cg->capped_amount }}" placeholder="Capped Amount">
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
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
                                                                <th class="text-center p-2 align-middle" style="width: 10%;">
                                                                    <button type="button" class="btn btn-outline-primary btn-sm add-row-btn" id="add-shipping-btn" data-table="#shipping-table" data-select="#shipping_select" data-reference="shipping_service">Add Row</button>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($discounted_shipping_services as $cg)
                                                            <tr>
                                                                <td class="p-2">
                                                                    <select class="form-control" name="selected_reference[shipping_service][]">
                                                                        <option disabled value="">Select Shipping Service</option>
                                                                        @foreach ($shipping_services as $shipping)
                                                                            <option value="{{ $shipping }}" {{ $cg->shipping_service == $shipping ? 'selected' : '' }}>{{ $shipping }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <select class="form-control category_discount_type" name="selected_discount_type[shipping_service][]">
                                                                        <option disabled selected value="">Select Discount Type</option>
                                                                        @foreach ($discount_type as $discount)
                                                                            <option value="{{ $discount }}" {{ $discount == $cg->discount_type ? 'selected' : '' }}>{{ $discount }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_discount_rate[shipping_service][]" class="form-control" value="{{ $cg->discount_rate }}" placeholder="Amount/Rate" required>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="selected_capped_amount[shipping_service][]" class="form-control cap_amount" value="{{ $cg->capped_amount }}" placeholder="Capped Amount">
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <h4>Email Notification</h4>
                                        </div>
                                        <div class="col-4">
                                            <label>Email Template</label>
                                            <select class="form-control" name="email_template" required>
                                                <option disabled value="">Select Template</option>
                                                @foreach ($templates as $template)
                                                    @if (!$template['template_id'] or $template['template_type'] != 'user')
                                                        @continue
                                                    @endif
                                                    <option value="{{ $template['template_id'] }}" {{ $selected_template == $template['template_id'] ? 'selected' : null }}>{{ $template['template_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label>Tag</label>
                                            <select class="form-control" name="email_tag" required>
                                                <option disabled value="">Select a Tag</option>
                                                @foreach ($tags as $tag)
                                                    @if (!$tag['list_id'])
                                                        @continue
                                                    @endif
                                                    <option value="{{ $tag['list_id'] }}" {{ $selected_tag == $tag['list_id'] ? 'selected' : null}}>{{ $tag['list_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label>Email Notification Schedule</label>
                                            <input type="text" class="form-control" id="notif-schedule" name="notif_schedule" required/>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
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
        $('#apply_discount_to').val("{{ $apply_discount_to }}");

        discountType();
        applyDiscountTo();

        var start = "{{ $on_sale->start_date ? date('m/d/Y', strtotime($on_sale->start_date)) : null  }}";
        var end = "{{ $on_sale->end_date ? date('m/d/Y', strtotime($on_sale->end_date)) : null }}";
        var notif_sched = "{{ $on_sale->notification_schedule ? date('m/d/Y', strtotime($on_sale->notification_schedule)) : null }}";
        $(function() {
            var year = new Date().getFullYear();

            $('#daterange').daterangepicker({
                opens: 'left',
                placeholder: 'Select Date Range',
                startDate: start ? start : moment(),
                endDate: end ? end : moment().add(7, 'days'),
            });

            $('#notif-schedule').daterangepicker({
                placeholder: 'Select Date',
                singleDatePicker: true,
                showDropdowns: true,
                startDate: notif_sched ? notif_sched : moment(),
                minYear: year,
                maxYear: parseInt(year) + 10
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
                $('#customer-groups').slideUp();
                $('#categories').slideUp();
                $('#selected-items').slideUp();
                $('#shipping-service').slideUp();
                $('#discount_type').prop('required', true);
            }else if($('#apply_discount_to').val() == 'Per Category'){
                $('#for_all_items').slideUp();
                $('#customer-groups').slideUp();
                $('#selected-items').slideUp();
                $('#categories').slideDown();
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
                $('#selected-items').slideUp();
                $('#customer-groups').slideUp();
                $('#shipping-service').slideUp();
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

        function clone_table(table, select, reference){
            var clone_select = $(select).html();
            var clone_discount_type = $('#discount_type_select').html();
			var row = '<tr>' +
				'<td class="p-2">' +
					'<select name="selected_reference[' + reference + '][]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
				'</td>' +
				'<td class="p-2">' +
					'<select name="selected_discount_type[' + reference + '][]" class="form-control w-100 category_discount_type" style="width: 100%;" required>' + clone_discount_type + '</select>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="number" name="selected_discount_rate[' + reference + '][]" class="form-control" placeholder="Amount/Rate" required>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="number" name="selected_capped_amount[' + reference + '][]" class="form-control cap_amount" value="0" placeholder="Capped Amount">' +
				'</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
				'</td>' +
			'</tr>';

			$(table).append(row);
        }

        $(document).on('click', '.add-row-btn', function (e){
			e.preventDefault();
            var table = $(this).data('table') + ' tbody';
            var select = $(this).data('select');
            var reference = $(this).data('reference');
            clone_table(table, select, reference);
		});

        $(document).on('click', '.remove-td-row', function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
		});

        $(document).on('click', '#is-clearance-sale', function(e) {
            if ($(this).is(":checked")) {
                $('#apply_discount_to').attr('disabled', true).val('Selected Items').trigger('change');
            } else {
                $('#apply_discount_to').attr('disabled', false).val('').trigger('change');
            }
        });

        $(document).on('change', '#apply_discount_to', function(e) {
            if ($(this).val() == 'Selected Items') {
                $('#is-clearance-sale').prop('checked', true);
            } else {
                $('#is-clearance-sale').prop('checked', false);
            }
        });
    });
    </script>
@endsection