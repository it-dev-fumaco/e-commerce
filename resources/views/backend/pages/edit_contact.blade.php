@extends('backend.layout', [
'namePage' => 'Contact Us',
'activePage' => 'contact_us'
])

@section('content')
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit Contact Us</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Edit Contact Us</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
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
                            <div class="card card-primary">
                                <form action="/admin/pages/contact/update/{{ $address->id }}" method="POST">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-9"><h4>Contact Us</h4></div>
                                            <div class="col-md-3 text-right">
                                                <a href="/admin/pages/contact/add_form" class="btn btn-secondary">Add an Address</a>
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Title *</label>
                                                <input type="text" name="title" class="form-control" value="{{ $address->office_title }}" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Address *</label>
                                                <input type="text" name="address" class="form-control" value="{{ $address->office_address }}" required/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-8 mx-auto">
                                                <button class="btn btn-primary float-right m-2" id="add-contact-numbers-btn"><i class="fa fa-plus"></i> Add Contact Info</button>
                                                <table class="table table-striped" id="contact-numbers-table">
                                                    <thead>
                                                        <tr>
                                                            <th class='text-center' style="width: 30%;">Type</th>
                                                            <th class='text-center' style="width: 50%">Contact Info</th>
                                                            <th class='text-center' style="width: 20%">-</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $types = ['Phone', 'Mobile', 'Email'];
                                                            $messaging_platforms = ['WhatsApp', 'Viber'];
                                                        @endphp
                                                        <select class="form-control d-none" id="type-selection">
                                                            <option value="" disabled selected>Select Contact Type</option>
                                                            @foreach ($types as $type)
                                                                <option value="{{ $type }}">{{ $type }}</option>
                                                            @endforeach
                                                        </select>
                                                        <div class="d-none">
                                                            <div id="msg-content"> 
                                                                Other Messaging Platforms:&nbsp;&nbsp;
                                                                @foreach ($messaging_platforms as $msg)
                                                                    <input type="checkbox" name="messaging_platform[row-1][{{ $msg }}]"> <label>{{ $msg }}</label>&nbsp;&nbsp;
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @foreach ($contact_info as $i => $info)
                                                            <tr class="contact-row">
                                                                @php
                                                                    $a = $i + 1;
                                                                    $selected_platforms = $info->messaging_apps ? explode(',', $info->messaging_apps) : [];
                                                                @endphp
                                                                <td class="text-center">
                                                                    <select name="type[row-{{ $a }}]" class="form-control type-select" data-row="row-{{ $a }}" required>
                                                                        <option value="" disabled>Select Contact Type</option>
                                                                        @foreach ($types as $type)
                                                                            <option value="{{ $type }}" {{ $type == $info->type ? 'selected' : null }}>{{ $type }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="text" name="contact[row-{{ $a }}]" class="form-control" value="{{ $info->contact }}" required>
                                                                    <div id="row-{{ $a }}-msg-container" class="text-left p-2" style="display: {{ $info->type != 'Mobile' ? 'none' : null}}">
                                                                        <div>
                                                                            <i>Display Available Messaging Platforms:&nbsp;&nbsp;</i>
                                                                            @foreach ($messaging_platforms as $msg)
                                                                                <input type="checkbox" name="messaging_platform[row-{{ $a }}][{{ $msg }}]" {{ in_array($msg, $selected_platforms) ? 'checked' :  null }}> <label>{{ $msg }}</label>&nbsp;&nbsp;
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-outline-danger w-100 remove-row" data-row="row-{{ $a }}">Remove</button>
                                                                </td>
                                                            </tr> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="float-right font-italic">
                                            <small>Last modified by: {{ $address->last_modified_by }} - {{ $address->last_modified_at }}</small><br>
                                            <small>Created by: {{ $address->created_by }} - {{ $address->created_at }}</small>
                                        </div>
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
        $(document).ready(function(){
            $('#add-contact-numbers-btn').click(function(e){
                e.preventDefault();
                var row_count = $('.contact-row').length + 1;

                var clone_select = $('#type-selection').html();
                var row = '<tr class="contact-row" id="row-' + row_count + '">' +
                    '<td class="text-center">' +
                        '<select name="type[row-' + row_count + ']" class="form-control type-select" id="type-selection" data-row="row-' + row_count + '" required>' + clone_select + '</select>' +
                    '</td>' +
                    '<td class="text-center">' +
                        '<input type="text" name="contact[row-' + row_count + ']" class="form-control" required>' +
                        '<div id="row-' + row_count + '-msg-container" class="text-left p-2" style="display: none">' +
                            'Other Messaging Platforms:&nbsp;&nbsp;' +
                            @foreach ($messaging_platforms as $msg)
                                '<input type="checkbox" name="messaging_platform[row-' + row_count + '][{{ $msg }}]"> <label>{{ $msg }}</label>&nbsp;&nbsp;' +
                            @endforeach
                        '</div>' + 
                    '</td>' +
                    '<td>' +
                        '<button type="button" class="btn btn-outline-danger w-100 remove-row" data-row="row-' + row_count + '">Remove</button>' +
                    '</td>' +
                '</tr>';

                $('#contact-numbers-table tbody').append(row);
            });

            $('#contact-numbers-table').on('change', '.type-select', function(){
                var row = '#' + $(this).data('row');
                if($(this).val() == 'Mobile'){
                    $(row + '-msg-container').slideDown();
                }else{
                    $(row + '-msg-container').slideUp();
                }
            });

            $('#contact-numbers-table').on('click', '.remove-row', function (){
                var row = '#' + $(this).data('row');
                $(row).remove();
            });
        });
    </script>
@endsection