@extends('backend.layout', [
'namePage' => 'Holiday',
'activePage' => 'holiday_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Holiday List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active"><a href="/admin/holiday/list">Holiday List</a></li>
                                <li class="breadcrumb-item active">Register New Holiday</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <form action="/admin/holiday/new" method="POST">
                                    <div class="card-body">
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
                                        <h4>Register Holiday</h4>
                                        @csrf
                                        <div class="form-group col-md-6">
                                            <label for="name">Holiday Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="name">Holiday Date</label>
                                            <input type="text" name="date" id="holiday" class="form-control" required/>
                                        </div>
                                            
                                    </div>
                                    <div class="card-footer text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">SUBMIT</button>
                                    </div>
                                </form>
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
        $(document).ready(function() {
            $('#holiday').daterangepicker({
                "singleDatePicker": true,
                placeholder: 'Select Date',
                locale: {
                    format: 'YYYY-MM-DD'
                    // format: 'MMM-DD',
                    },
                }, function(start, end, label) {
            });
        });
    </script>
@endsection
