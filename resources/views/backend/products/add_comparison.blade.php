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
                            <div class="row">
                                <div class="col-4">
                                    <div class="card card-primary">
                                        <div class="card-body">
                                            <label>Select a Category</label>
                                            <form action="/admin/products/compare/add" method="get">
                                                <select class="form-control" name="selected_category">
                                                    <option disabled {{ !$selected_category ? 'selected' : '' }} value="">Select a Category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->name }}" {{ $selected_category == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <br/>
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </form>
                                        </div>
                                    </div>
                                    @if ($items)
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <label>Select Items</label>
                                                <form action="/admin/products/compare/add" method="get">
                                                    <input type="text" value="{{ $selected_category }}" name="selected_category" hidden>
                                                    <select class="d-none form-control" name="selected_items" id="selected_items">
                                                        <option disabled selected value="">Select Items to Compare (Max: 4)</option>
                                                        @foreach ($items as $item)
                                                            <option value="{{ $item->f_idcode }}">{{ $item->f_idcode.' - '.$item->f_name_name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @php
                                                        $selected_items = request()->get('selected_items') ? request()->get('selected_items') : [];
                                                    @endphp
                                                    <table class="table table-bordered" id="items-table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="text-center">Item Code</th>
                                                                <th class="text-center" style="width: 10%;"><button class="btn btn-outline-primary btn-sm" id="add-items-btn">Add</button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($selected_items)
                                                                @foreach ($selected_items as $selected)
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
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-4 mx-auto">
                                    @if ($attribute_names)
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <form action="/admin/products/compare/save" method="post">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <br/>&nbsp;
                                                    @csrf
                                                    <div class="d-none">
                                                        <input type="text" value="{{ $selected_category }}" name="selected_category">
                                                        @foreach ($selected_items as $item)
                                                            <input type="text" name="selected_items[]" value ="{{ $item }}">
                                                        @endforeach
                                                    </div>
                                                    <table class="table table-bordered" id="attributes-table">
                                                        <thead>
                                                            <tr>
                                                                <th colspan=2 scope="col" class="text-center">Item Attributes</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($attribute_names as $attrib_name)
                                                                <tr>
                                                                    <td class="p-2 text-center">
                                                                        {{ $attrib_name->attribute_name }}
                                                                        <input type="text" name="attribute_names[]" value="{{ $attrib_name->id }}" hidden>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-td-row">Remove</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
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

            $(document).on('click', '.remove-td-row', function(e){
                e.preventDefault();
                $(this).closest("tr").remove();
            });
        });
    </script>
@endsection