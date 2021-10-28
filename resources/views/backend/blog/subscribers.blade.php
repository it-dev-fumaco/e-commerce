@extends('backend.layout', [
'namePage' => 'Subscribers',
'activePage' => 'subscribers_list'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Subscribers List Page</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Subscribers List Page</li>
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
                                    <form action="/admin/blog/subscribers" method="GET">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <input type="text" class="form-control" id="search-box" name="email" placeholder="Search" value="{{request()->get('q')}}">
                                            </div>
                                                
                                            <div class="col-sm-3">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>Membership Status
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($subs_arr as $subs)
                                                @php
                                                    $badge = "danger";
                                                    $status = "Disabled";
                                                    if($subs['status'] == 1){
                                                        $badge = "primary";
                                                        $status = "Active";
                                                    }
                                                @endphp
                                                <tr>
                                                    <td>{{ $subs['email'] }}</td>
                                                    <td>{{ $subs['membership_status'] }}</td>
                                                    <td><span class="badge bg-{{ $badge }}">{{ $status }}</span></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan=2 class="text-center">No Subscribers</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $subscribers->withQueryString()->links('pagination::bootstrap-4') }}
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
