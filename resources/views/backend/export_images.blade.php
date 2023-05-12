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
                        <div class="col-2">
                            <div class="card card-primary">
                                <div class="card-body">
                                    <h6>Step 1. Click 'Export Images'</h6>
                                    <a href="/admin/export_images/1" class="btn btn-primary w-100"><i class="fa fa-file-export"></i> Export Images</a>
                                    <br><br>
                                    @php
                                        $btn = 'secondary';
                                        $download_link = $ath_link = '#';
                                        if (Storage::disk('public')->exists('/athena_images.zip')) {
                                            $btn = 'primary';
                                            $download_link = '/admin/download_images';
                                            $ath_link = 'http://athenaerp.fumaco.local/import_from_ecommerce';
                                        }
                                    @endphp
                                    <h6>Step 2. Download Zip File</h6>
                                    <a href="{{ $download_link }}" class="btn btn-{{ $btn }} w-100"><i class="fa fa-download"></i> Download Zip</a>
                                    <br><br>
                                    <h6>Step 3. Import to AthenaERP</h6>
                                    <a href="{{ $ath_link }}"  {{ $ath_link != '#' ? 'target="_blank"' : null }} class="btn btn-{{ $btn }} w-100"><i class="fas fa-external-link-alt"></i> AthenaERP</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-10">
                            @if ($exported_jpg or $exported_webp or $webp_unable_to_export)
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
                                    @if (count($jpg_unable_to_export) + count($webp_unable_to_export) > 0)
                                        <div class="row">
                                            <div class="container-fluid">
                                                Possible reasons why some images were unable to be exported:
                                                <ul>
                                                    <li>The file has already been exported</li>
                                                    <li>The file does not exist in storage</li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-3 border border-outline-secondary">
                                            <label>Exported JPG ({{ count($exported_jpg) }} item(s))</label>
                                            <div class="container-fluid overflow-auto" style="max-height: 65vh">
                                                @forelse ($exported_jpg as $jpg)
                                                    <p>{{ $jpg['image'] }}</p>
                                                @empty
                                                    <p>No Item(s)</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="col-3 border border-outline-secondary">
                                            <label>Unable to Export .jpg Image ({{ count($jpg_unable_to_export) }} item(s))</label>
                                            <div class="container-fluid overflow-auto" style="max-height: 65vh">
                                                @forelse ($jpg_unable_to_export as $img)
                                                    <p>{{ $img }}</p>
                                                @empty
                                                    <p>No Item(s)</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="col-3 border border-outline-secondary">
                                            <label>Exported Webp ({{ count($exported_webp) }} item(s))</label>
                                            <div class="container-fluid overflow-auto" style="max-height: 65vh">
                                                @forelse ($exported_webp as $webp)
                                                    <p>{{ $webp }}</p>
                                                @empty
                                                    <p>No Item(s)</p>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="col-3 border border-outline-secondary">
                                            <label>Unable to Export .webp Image ({{ count($webp_unable_to_export) }} item(s))</label>
                                            <div class="container-fluid overflow-auto" style="max-height: 65vh">
                                                @forelse ($webp_unable_to_export as $img)
                                                    <p>{{ $img }}</p>
                                                @empty
                                                    <p>No Item(s)</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            @endif
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
