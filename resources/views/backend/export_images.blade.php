@extends('backend.layout', [
    'namePage' => 'Export Images',
    'activePage' => 'export_images'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Export Images</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Export Images</li>
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
                                    @if(session()->has('error'))
                                        <div class="row">
                                            <div class="col">
                                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                                    {!! session()->get('error') !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <a href="/admin/export_images/1" class="btn btn-primary d-inline">Export Images</a>
                                    @if ($exported_jpg or $exported_webp or $webp_unable_to_export)
                                        @if (Storage::disk('public')->exists('/athena_images.zip'))
                                            <a href="/admin/download_images" class="btn btn-primary d-inline">Download Zip</a>
                                            <a href="http://athenaerp.fumaco.local/import_from_ecommerce" target="_blank" class="btn btn-primary float-right">Import to AthenaERP&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></a>
                                        @endif
                                        <div class="container-fluid mt-4">
                                            <div class="row">
                                                <div class="scroll col-2 border border-outline-secondary">
                                                    <label>Exported JPG ({{ count($exported_jpg) }} item(s))</label>
                                                    @forelse ($exported_jpg as $jpg)
                                                        <p>{{ $jpg['image'] }}</p>
                                                    @empty
                                                        <p>No Item(s)</p>
                                                    @endforelse
                                                </div>

                                                <div class="scroll col-4 border border-outline-secondary">
                                                    <label>Unable to Export .jpg Image ({{ count($jpg_unable_to_export) }} item(s))</label>
                                                    @forelse ($jpg_unable_to_export as $img)
                                                        <p>{{ $img }}</p>
                                                    @empty
                                                        <p>No Item(s)</p>
                                                    @endforelse
                                                </div>

                                                <div class="scroll col-2 border border-outline-secondary">
                                                    <label>Exported Webp ({{ count($exported_webp) }} item(s))</label>
                                                    @forelse ($exported_webp as $webp)
                                                        <p>{{ $webp }}</p>
                                                    @empty
                                                        <p>No Item(s)</p>
                                                    @endforelse
                                                </div>

                                                <div class="scroll col-4 border border-outline-secondary">
                                                    <label>Unable to Export .webp Image ({{ count($webp_unable_to_export) }} item(s))</label>
                                                    @forelse ($webp_unable_to_export as $img)
                                                        <p>{{ $img }}</p>
                                                    @empty
                                                        <p>No Item(s)</p>
                                                    @endforelse
                                                </div>
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
    <style>
        .scroll{
            height: 65vh;
            overflow-y: scroll;
        }
    </style>
@endsection
