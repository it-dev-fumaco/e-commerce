@extends('backend.layout', [
    'namePage' => 'Edit Price Rule',
    'activePage' => 'pricing_rule'
])

@section('content')
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Edit Price Rule</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Edit Price Rule</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary border">
                            <div class="alert alert-success fade show m-2 d-none" role="alert" id="custom-alert"></div>
                            <div class="card-body">
                                <form action="/admin/marketing/pricing_rule/{{ $price_rule->price_rule_id }}/update" method="POST" autocomplete="off">
                                    @csrf
                                    <div class="row">
                                        <div class="col-9 p-0"><h4 class="ml-1 mb-0 p-1">Price Rule Details</h4></div>
                                        <div class="col-3 text-right">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i>&nbsp;Submit</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="mt-3 mb-0"><strong>Note:</strong> If item has existing discount, discount will be based on regular price.</p>
                                        </div>
                                    </div>
                                    <hr class="mt-2">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="price-rule-name">Price Rule Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="price-rule-name" name="name" value="{{ $price_rule->name }}" required/>
                                                </div>
                                                <div class="col-6 pt-4">
                                                    <div class="form-check ml-2 mt-2">
                                                        <input type="checkbox" class="form-check-input" id="is-enabled" name="is_enabled" value="1" {{ $price_rule->enabled == 1 ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is-enabled">Enabled</label>
                                                    </div>
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <input type="hidden" name="old_apply_on" value="{{ $price_rule->apply_on }}">
                                                    @php
                                                        $types = ['Item Code', 'Category', 'Any'];
                                                    @endphp
                                                    <label for="apply-on">Applicable To <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="apply_on" id="apply-on" required>
                                                        <option disabled selected value=""></option>
                                                        @foreach ($types as $type)
                                                        <option value="{{ $type }}" {{ $price_rule->apply_on == $type ? 'selected' : '' }}>{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mt-2">
                                                    <label for="daterange">Set Duration <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control set_duration" id="daterange" name="duration" required/>
                                                </div>
                                                <div class="col-6 mt-3">
                                                    @php
                                                        $discount_types = ['Percentage', 'Amount'];
                                                    @endphp
                                                    <label for="discount-type">Discount Type <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="discount_type" id="discount-type" required>
                                                        <option disabled selected value=""></option>
                                                        @foreach ($discount_types as $discount_type)
                                                        <option value="{{ $discount_type }}" {{ $price_rule->discount_type == $discount_type ? 'selected' : '' }}>{{ $discount_type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-6 mt-3">
                                                    @php
                                                        $conditions_based_on = ['Order Qty', 'Total Amount'];
                                                    @endphp
                                                    <label for="conditions-based-on">Apply Discount Based On <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="conditions_based_on" id="conditions-based-on" required>
                                                        <option disabled selected value=""></option>
                                                        @foreach ($conditions_based_on as $cbo)
                                                        <option value="{{ $cbo }}" {{ $price_rule->conditions_based_on == $cbo ? 'selected' : '' }}>{{ $cbo }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 mt-3">
                                            <div class="row mt-3" id="apply-rule-on-item-code">
                                                <div class="col-8 mx-auto">
                                                    <span class="d-block mb-1 font-weight-bold">Apply Rule on Item Code</span>
                                                    <table class="table table-bordered" id="item-code-table">
                                                        <thead>
                                                            <th style="width: 80%;" scope="col" class="text-center p-2 align-middle">Item Code</th>
                                                            <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">
                                                                <button type="button" class="add-row-btn btn btn-outline-primary btn-sm" data-table="#item-code-table" data-ref="item_code" style="white-space: nowrap"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @if ($price_rule->apply_on == 'Item Code')
                                                            @foreach ($price_rule_applied_on as $r)
                                                            <tr>
                                                                <td class="p-2">
                                                                    <select name="applied_on[{{ $r->price_rule_applied_on_id }}]" class="form-control w-100 custom-select-2" required>
                                                                        @foreach ($items as $item)
                                                                        <option value="{{ $item->f_idcode }}" {{ $item->f_idcode == $r->applied_on ? 'selected' : '' }}>{{ $r->applied_on . ' - ' . $item->f_name_name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row mt-3" id="apply-rule-on-category">
                                                <div class="col-8 mx-auto">
                                                    <span class="d-block mb-1 font-weight-bold">Apply Rule on Category</span>
                                                    <table class="table table-bordered" id="category-table">
                                                        <thead>
                                                            <th style="width: 80%;" scope="col" class="text-center p-2 align-middle">Category</th>
                                                            <th style="width: 20%;" scope="col" class="text-center p-2 align-middle">
                                                                <button type="button" class="add-row-btn btn btn-outline-primary btn-sm" data-table="#category-table" data-ref="category" style="white-space: nowrap"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @if ($price_rule->apply_on == 'Category')
                                                            @foreach ($price_rule_applied_on as $r)
                                                            <tr>
                                                                <td class="p-2">
                                                                    <select name="applied_on[{{ $r->price_rule_applied_on_id }}]" class="form-control w-100 custom-select-2" required>
                                                                        @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}" {{ $category->id == $r->applied_on ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mt-3">
                                        <div class="col-9"><h4>Conditions</h4></div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-8 mx-auto">
                                                    <small class="font-italic text-right d-block mb-1">Asterisk (*) means any quantity or amount</small>
                                                    <table class="table table-bordered" id="conditions-table">
                                                        <thead>
                                                            <th style="width: 30%;" scope="col" class="text-center p-2 align-middle">Range From</th>
                                                            <th style="width: 30%;" scope="col" class="text-center p-2 align-middle">Range To</th>
                                                            <th style="width: 30%;" scope="col" class="text-center p-2 align-middle" id="conditions-text">{{ $price_rule->discount_type == 'Percentage' ? 'Discount Percentage' : 'Discount Amount' }}</th>
                                                            <th style="width: 10%;" scope="col" class="text-center p-2 align-middle">
                                                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-conditions-row-btn" style="white-space: nowrap"><i class="fa fa-plus"></i>&nbsp;Add Row</button>
                                                            </th>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($price_rule_condition as $s)
                                                            <tr>
                                                                <td class="p-2">
                                                                    <input type="text" name="range_from[{{ $s->price_rule_condition_id }}]" class="form-control" placeholder="Range From" value="{{ $s->range_from }}" required>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="range_to[{{ $s->price_rule_condition_id }}]" class="form-control" placeholder="Range To" value="{{ $s->range_to }}" required>
                                                                </td>
                                                                <td class="p-2">
                                                                    <input type="text" name="rate[{{ $s->price_rule_condition_id }}]" class="form-control" placeholder="Rate" value="{{ $s->rate }}" required>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="float-right font-italic">
                                                <small>Last modified by: {{ $price_rule->last_modified_by . ' - ' . \Carbon\Carbon::parse($price_rule->last_modified_at)->format('Y-m-d h:i A') }}</small><br>
                                                <small>Created by: {{ $price_rule->created_by . ' - ' . \Carbon\Carbon::parse($price_rule->created_at)->format('Y-m-d h:i A') }}</small>
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
        applyOn();

        loadSelect2('{{ $price_rule->apply_on == "Item Code" ? "item_code" : "category" }}');

        var start = "{{ $price_rule->valid_from ? date('m/d/Y', strtotime($price_rule->valid_from)) : null  }}";
        var end = "{{ $price_rule->valid_to ? date('m/d/Y', strtotime($price_rule->valid_to)) : null }}";
        $(function() {
            $('#daterange').daterangepicker({
                opens: 'left',
                placeholder: 'Select Date Range',
                startDate: start ? start : moment(),
                endDate: end ? end : moment().add(7, 'days'),
            });
        });

        function applyOn() {
            if ($('#apply-on').val() == 'Item Code') {
                $('#apply-rule-on-item-code').slideDown();
                $('#apply-rule-on-category').slideUp();
            }else if ($('#apply-on').val() == 'Category') {
                $('#apply-rule-on-item-code').slideUp();
                $('#apply-rule-on-category').slideDown();
            } else {
                $('#apply-rule-on-item-code').slideUp();
                $('#apply-rule-on-category').slideUp();
            }
        }

        $('#apply-on').change(function(){
            applyOn();
        });

        $('#discount-type').click(function() {
            if ($(this).val() == 'Percentage') {
                $('#conditions-text').text('Discount Percentage');
            } else {
                $('#conditions-text').text('Discount Amount');
            }
        });

        $('#add-conditions-row-btn').click(function(e){
            e.preventDefault();
            var row = '<tr>' +
				'<td class="p-2">' +
					'<input type="text" name="new_range_from[]" class="form-control" placeholder="Range From" required>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="text" name="new_range_to[]" class="form-control" placeholder="Range To" required>' +
				'</td>' +
                '<td class="p-2">' +
					'<input type="text" name="new_rate[]" class="form-control" placeholder="Rate" required>' +
				'</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
				'</td>' +
			'</tr>';
            
			$('#conditions-table tbody').append(row);
        });
        
        function clone_table(table, select){
			var row = '<tr>' +
				'<td class="p-2">' +
					'<select name="new_applied_on[]" class="form-control w-100 custom-select-2" required></select>' +
				'</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-outline-danger btn-sm remove-td-row"><i class="fa fa-trash"></i></button>' +
				'</td>' +
			'</tr>';
            
			$(table).append(row);

            loadSelect2(select);
        }

        function formatState (opt) {
            var optimage = opt.image;
            if(!optimage){
                return opt.text;
            } else {
                var $opt = $(
                '<div class="d-flex flex-row">' +
	                '<div class="col-2"><img src="' + optimage + '" width="40px" /></div>' +
	                '<div class="col-10" style="font-size: 9pt;">' + opt.text + '</div>' +
                    '</div>'
                );
                return $opt;
            }
        };

        $(document).on('click', '.add-row-btn', function (e){
			e.preventDefault();
            var table = $(this).data('table') + ' tbody';
            var select = $(this).data('ref');
            clone_table(table, select);
		});

        $(document).on('click', '.remove-td-row', function(e){
			e.preventDefault();
			$(this).closest("tr").remove();
		});

        $('form').submit(function (e) {
            e.preventDefault();

            if ($('#apply-on').val() == 'Item Code') {
                $('#category-table tbody').empty();
            }

            if ($('#apply-on').val() == 'Category') {
                $('#item-code-table tbody').empty();
            }

            if ($('#apply-on').val() == 'Any') {
                $('#item-code-table tbody').empty();
                $('#category-table tbody').empty();
            }

            $.ajax({
                url: $(this).attr('action'),
                type:"POST",
                data: $(this).serialize(),
                success:function(response){
                    if (response.status) {
                        $('#custom-alert').removeClass('alert-danger d-none').addClass('alert-success').html(response.message);
                    } else {
                        $('#custom-alert').removeClass('alert-success d-none').addClass('alert-danger').html(response.message);
                    }
                },
                error : function(data) {
                    $('#custom-alert').removeClass('alert-success d-none').addClass('alert-danger').html('Something went wrong. Please try again.');
                }
            });
        });

        function loadSelect2(select) {
            if (select == 'item_code') {
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
                            return {
                                results: response.items
                            };
                        },
                        cache: true
                    }
                });
            } 

            if (select == 'category') {
                $('.custom-select-2').select2({
                    placeholder: 'Select Category',
                    ajax: {
                        url: '/admin/category/search',
                        method: 'GET',
                        dataType: 'json',
                        data: function (data) {
                            return {
                                q: data.term, // search term
                            };
                        },
                        processResults: function (response) {
                            return {
                                results: response.items
                            };
                        },
                        cache: true
                    }
                });
            }
        }
    });
    </script>
@endsection