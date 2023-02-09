@extends('backend.layout', [
'namePage' => 'Product Comparison List',
'activePage' => 'product_comparison'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Product Comparison List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Product Comparison List Page</li>
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
                                <div class="card-body">
                                    @if(session()->has('success'))
                                        <div class="row">
                                            <div class="col">
                                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                                    {!! session()->get('success') !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                        <div class="row">
                                            <div class="col">
                                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                                    {!! session()->get('error') !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-4">
                                            <form action="" class="text-center" method="GET">
                                                <div class="form-group row">
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" name="search" placeholder="Search" aria-describedby="basic-addon2" value="{{request()->get('q')}}">
                                                    </div>
                                                    <div class="col-sm-4 text-left">
                                                        <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-7"></div>
                                        <div class="col-1">
                                            <a href="/admin/products/compare/add" class="btn btn-primary mx-auto w-100"><i class="fa fa-plus"></i>&nbsp;Add</a>
                                        </div>
                                    </div>
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th class="text-center">ID</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Item Codes</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        @forelse ($comparison_arr as $compare)
                                            <tr>
                                                <td>{{ $compare['comparison_id'] }}</td>
                                                <td>{{ $compare['category_name'] }}</td>
                                                <td>
                                                    @foreach ($compare['item_codes'] as $codes)
                                                        <span class="badge badge-primary" style="font-size: 12pt">{{ $codes->item_code }}</span>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <center>
                                                        <label class="switch">
                                                            <input type="checkbox" class="toggle" name="publish" {{ ($compare['status'] == 1) ? 'checked' : '' }} value="{{ $compare['comparison_id'] }}"/>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </center>
                                                </td>
                                                <td class="text-center">
                                                    <a href="/admin/products/compare/{{ $compare['comparison_id'] }}/edit" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#dm{{ $compare['comparison_id'] }}"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="dm{{ $compare['comparison_id'] }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-trash"></i> Delete Comparison {{ $compare['comparison_id'] }}?</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Delete Comparison {{ $compare['comparison_id'] }}?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                                                            <a href="/admin/products/compare/{{ $compare['comparison_id'] }}/delete" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan=5 class="text-center">No Product Comparison(s)</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $product_comparison_id->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 30px;
            height: 16px;
        }
    
        .switch input { 
            opacity: 0;
            width: 0;
            height: 0;
        }
    
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }
    
        .slider:before {
            position: absolute;
            content: "";
            height: 10px;
            width: 10px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }
    
        input:checked + .slider {
            background-color: #2196F3;
        }
    
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
    
        input:checked + .slider:before {
            -webkit-transform: translateX(16px);
            -ms-transform: translateX(16px);
            transform: translateX(16px);
        }
    
        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }
    
        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(".toggle").change(function(){
                var data = {
                    'status': $(this).prop('checked') == true ? 1 : 0,
                    'compare_id': $(this).val(),
                    '_token': "{{ csrf_token() }}",
                }
                // console.log(data);
                $.ajax({
                    type:'POST',
                    url:'/admin/products/compare/set_status',
                    data: data,
                    success: function (response) {
                        console.log('success');
                    },
                    error: function () {
                        alert('An error occured.');
                    }
                });
            });
        });
    </script>
@endsection
