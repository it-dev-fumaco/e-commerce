@extends('backend.layout', [
'namePage' => 'Products Categories',
'activePage' => 'product_category'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Sort Products per Category</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Product Categories</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- /.card-header -->
                                <div class="card-body">
                                    @if(session()->has('success'))
                                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                            {!! session()->get('success') !!}
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                            {!! session()->get('error') !!}
                                        </div>
                                    @endif
                                    <form action="/admin/product/settings" method="GET">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group row">
                                                    <div class="col-md-8">
                                                    <input type="text" class="form-control" id="search-box" name="q" placeholder="Search" value="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Item Code</th>
                                                <th class="text-center">Item Name</th>
                                                <th class="text-center">Row</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($items as $item)
                                            <tr>
                                                <form action="/admin/category/set_row/{{ $item->f_cat_id }}" method="post">
                                                    @csrf
                                                    <td class="text-center" style="width: 20%;" >{{ $item->f_idcode }}</td>
                                                    <td class="text-center" style="width: 50%;" >{{ $item->f_name_name }}</td>
                                                    <td class="text-center" style="width: 30%;" >
                                                        <div class="form-group row">
                                                            <div class="col-md-8">
                                                                <select class="form-control formslabelfnt" id="row_select" aria-label="Default select example" name="item_row" required>
                                                                    @php
                                                                        if($item->f_order_by == 'P'){
                                                                            $select = 'Select Row';
                                                                        }else{
                                                                            $select = 'Currently in: Row '.$item->f_order_by;
                                                                        }
                                                                    @endphp
                                                                    <option selected disabled value="">{{ $select }}</option>
                                                                    <option value="A">Row A</option>
                                                                    <option value="B">Row B</option>
                                                                    <option value="C">Row C</option>
                                                                    <option value="D">Row D</option>
                                                                    <option value="E">Row E</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input type="text" name="item_code" id="item_code" value="{{ $item->f_idcode }}" required hidden/>
                                                                <button type="submit" id="send" class="btn btn-primary">Change Row</button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </form>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center">No records found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $items->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                                  <!-- /.card-body -->
                            </div>
                          <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>
        </div>
    </div>
@endsection
