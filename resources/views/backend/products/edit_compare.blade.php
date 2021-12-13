@extends('backend.layout', [
'namePage' => 'Product Comparison',
'activePage' => 'product_comparison'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Compare Products</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Compare Products</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
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
                            <form action="/admin/products/compare/save" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-4">
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <label>Selected Category: {{ $category->name }}</label>
                                            </div>
                                        </div>
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>Select Items</label>
                                                    </div>
                                                </div>
                                                <br/>
                                                <input type="text" value="{{ $category_id }}" name="selected_category" hidden>
                                                <select class="d-none form-control" name="selected_items" id="selected_items">
                                                    <option disabled selected value="">Select Items to Compare (Max: 4)</option>
                                                    @foreach ($items as $item)
                                                        <option value="{{ $item->f_idcode }}">{{ $item->f_idcode.' - '.$item->f_name_name }}</option>
                                                    @endforeach
                                                </select>
                                                <table class="table table-bordered" id="items-table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center">Item Code</th>
                                                            <th class="text-center" style="width: 10%;"><button class="btn btn-outline-primary btn-sm" id="add-items-btn">Add</button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if ($item_codes)
                                                            @foreach ($item_codes as $selected)
                                                                <tr>
                                                                    <td class="p-2">
                                                                        <select class="form-control" name="selected_items[]" id="selected_items" required>
                                                                            <option disabled selected value="">Select Items to Compare (Max: 4)</option>
                                                                            @foreach ($items as $item)
                                                                                <option value="{{ $item->f_idcode }}" {{ $selected == $item->f_idcode ? 'selected' : '' }}>{{ $item->f_idcode.' - '.$item->f_name_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            @for ($i = 0; $i < 2; $i++)
                                                                <tr>
                                                                    <td class="p-2">
                                                                        <select class="form-control" name="selected_items[]" id="selected_items" required>
                                                                            <option disabled selected value="">Select Items to Compare (Max: 4)</option>
                                                                            @foreach ($items as $item)
                                                                                <option value="{{ $item->f_idcode }}">{{ $item->f_idcode.' - '.$item->f_name_name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                    </td>
                                                                </tr>
                                                            @endfor
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <button type="submit" class="btn btn-primary float-right">Save</button>
                                                <br/>&nbsp;
                                                <div class="d-none">
                                                    <input type="text" name="product_comparison_id" value="{{ collect($product_comparison)->pluck('product_comparison_id')->first() }}">
                                                    <select class="form-control" name="attribute_names" id="attribute_names">
                                                        <option disabled selected value="">Select an Attribute</option>
                                                        @foreach ($attribute_names as $attrib_name)
                                                            <option value="{{ $attrib_name->id }}">{{ $attrib_name->attribute_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" value="{{ $category->name }}" name="selected_category">
                                                    <input type="checkbox" name="compare_edit" checked readonly>
                                                </div>
                                                <table class="table table-bordered" id="attributes-table">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" class="text-center">Item Attributes</th>
                                                            <th class="text-center"><button class="btn btn-outline-primary btn-sm" id="add-attributes-btn">Add</button></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($attributes as $selected_attrib)
                                                            <tr>
                                                                <td class="p-2 text-center">
                                                                    {{ $selected_attrib->attribute_name }}
                                                                    <input type="text" name="attribute_names[]" value="{{ $selected_attrib->id }}" hidden>
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
                            </form>
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
            $('#add-items-btn').click(function(e){
                e.preventDefault();

                var row_count = $('#items-table tr').length;
                if(row_count <= 4){
                    addTableRows();
                }else{
                    alert('Maximum number of products to compare reached!')
                }
            });

            function addTableRows(){
                var items_clone_select = $('#selected_items').html();
                var row = '<tr>' +
                    '<td class="p-2 text-center">' +
                        '<select name="selected_items[]" class="form-control w-100" style="width: 100%;" required>' + items_clone_select + '</select>' +
                    '</td>' +
                    '<td class="text-center">' +
                        '<button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
                    '</td>' +
                '</tr>';

                $('#items-table tbody').append(row);
            }

            $('#add-attributes-btn').click(function(e){
                e.preventDefault();

                var clone_select = $('#attribute_names').html();
                var row = '<tr>' +
                    '<td class="p-2">' +
                        '<select name="attribute_names[]" class="form-control w-100" style="width: 100%;" required>' + clone_select + '</select>' +
                    '</td>' +
                    '<td class="text-center">' +
                        '<button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>' +
                    '</td>' +
                '</tr>';

                $('#attributes-table tbody').append(row);
            });

            $(document).on('click', '.remove-td-row', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });
    </script>
@endsection