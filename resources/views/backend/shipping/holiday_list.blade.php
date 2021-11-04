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
                                <li class="breadcrumb-item active">Holiday List</li>
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
                                <div class="card-body">

                                    <div class="row">

                                    </div>
                                    <form action="/admin/holiday/list" method="GET">
                                        <div class="form-group row">
                                            <div class="col-sm-2">
                                                <input type="text" class="form-control" id="search-box" name="holiday" placeholder="Search" value="{{request()->get('holiday')}}">
                                            </div>

                                            <div class="col-sm-2">
                                                @php
                                                    $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                                @endphp
                                                <select class="form-control" name="holiday_month">
                                                    <option selected disabled value="">Select Month</option>
                                                    @foreach ($months as $key => $m)
                                                        <option {{ (request()->get('holiday_month') == $key + 1 ) ? 'selected' : '' }} value="{{ $key + 1 }}">{{ $m }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-sm-2">
                                                <select class="form-control" name="holiday_year">
                                                    <option {{ request()->get('holiday_year') == "" ? 'selected' : '' }} disabled value="">Select Year</option>
                                                    @foreach ($years as $y)
                                                        <option {{ $y == $year_now ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                            <div class="col-sm-3">
                                                <a href="/admin/holiday/add_form" class="btn btn-primary float-right">Register New Holiday</a>
                                            </div>
                                        </div>
                                    </form>
                                    @if(session()->has('success'))
                                        <div class="alert alert-success">
                                        {{ session()->get('success') }}
                                        </div>
                                    @endif
                                    @if(session()->has('error'))
                                        <div class="alert alert-warning">
                                        {{ session()->get('error') }}
                                        </div>
                                    @endif
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-md-3">Name</th>
                                                <th class="col-md-3">Date</th>
                                                <th class="col-md-3">Year</th>
                                                <th class="col-md-2 text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($holidays_arr as $holiday)
                                                <tr>
                                                    <td>{{ $holiday['name'] }}</td>
                                                    <td>{{ $holiday['date'] }}</td>
                                                    <td>{{ $holiday['year'] }}</td>
                                                    <td>
                                                        <div class="row">
                                                            <button class="btn btn-primary mx-auto" type="button" data-toggle="modal" data-target="#holidayModal-{{ $holiday['id'] }}">Edit</button>
                                                            <a href="/admin/holiday/delete/{{ $holiday['id'] }}" class="btn btn-danger mx-auto">Delete</a>
                                                        </div>

                                                        <div class="modal fade" id="holidayModal-{{ $holiday['id'] }}" tabindex="-1" aria-labelledby="holidayModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="holidayModalLabel">Edit {{ $holiday['name'] }} Holiday</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="/admin/holiday/edit" method="post">
                                                                            @csrf
                                                                            <div class="form-group">
                                                                                <label for="name">Holiday Name</label>
                                                                                <input type="text" name="name" class="form-control" value="{{ $holiday['name'] }}" required>
                                                                                <input type="text" name="id" class="form-control" value="{{ $holiday['id'] }}" hidden readonly required>

                                                                                <label for="name">Holiday Date</label>
                                                                                <input type="text" name="date" id="holiday-edit" class="edit-holiday form-control" value="{{ date('Y-m-d', strtotime($holiday['date'])) }}" required/>
                                                                                <br/>
                                                                                <button type="submit" class="btn btn-primary float-right">Save</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan=2 class="text-center">No Holidays Listed</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $holidays->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
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

            $('.edit-holiday').daterangepicker({
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
