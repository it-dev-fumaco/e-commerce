@extends('backend.layout', [
'namePage' => 'System Logs',
'activePage' => 'system_logs'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>System Logs</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">System Logs</li>
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
                                    <table class="table table-hover table-bordered">
                                        <tr>
                                            <th class="text-center">Operation</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Last Sync Date</th>
                                        </tr>
                                        @forelse ($logs as $log)
                                            <tr>
                                                <td class="text-center">{{ $log->operation }}</td>
                                                <td class="text-center"><span class="badge badge-{{ $log->status == 'successful' ? 'success' : 'danger' }}" style="font-size: 10pt">{{ $log->status }}</span></td>
                                                <td class="text-center">{{ Carbon\Carbon::parse($log->last_sync_date)->format('M d, Y h:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan=3 class="text-center">No Saved Logs</td>
                                            </tr>
                                        @endforelse
                                    </table>
                                    <div class="float-right mt-4">
                                        {{ $logs->withQueryString()->links('pagination::bootstrap-4') }}
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
