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
                            <h1>Add Contact Address</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Add Contact Address</li>
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
                                <form action="/admin/pages/contact/add" method="POST" enctype="multipart/form-data">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-9"><h4>Contact Us</h4></div>
                                            <div class="col-md-3 text-right">
                                                <button class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Title *</label>
                                                <input type="text" name="title" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label>Office Address *</label>
                                                <input type="text" name="address" class="form-control" required/>
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
                                                        <tr class="contact-row">
                                                            <td class="text-center">
                                                                @php
                                                                    $types = ['Phone', 'Mobile', 'Email'];
                                                                    $messaging_platforms = ['WhatsApp', 'Viber'];
                                                                @endphp
                                                                <select name="type[row-1]" class="form-control type-select" id="type-selection" data-row="row-1" required>
                                                                    <option value="" disabled selected>Select Contact Type</option>
                                                                    @foreach ($types as $type)
                                                                        <option value="{{ $type }}">{{ $type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td colspan=2 class="text-center">
                                                                <input type="text" name="contact[row-1]" class="form-control" required>
                                                                <div id="row-1-msg-container" class="text-left p-2" style="display: none">
                                                                    <div id="msg-content">
                                                                       <i> Display Available Messaging Platforms:&nbsp;&nbsp;<i>
                                                                        @foreach ($messaging_platforms as $msg)
                                                                            <input type="checkbox" name="messaging_platform[row-1][{{ $msg }}]"> <label>{{ $msg }}</label>&nbsp;&nbsp;
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
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
                var clone_msg_platform = $('#msg-content').html();
                var row = '<tr class="contact-row" id="row-' + row_count + '">' +
                    '<td class="text-center">' +
                        '<select name="type[row-' + row_count + ']" class="form-control type-select" data-row="row-' + row_count + '" id="type-selection" required>' + clone_select + '</select>' +
                    '</td>' +
                    '<td class="text-center">' +
                        '<input type="text" name="contact[row-' + row_count + ']" class="form-control" required>' +
                        '<div id="row-' + row_count + '-msg-container" class="text-left p-2" style="display: none">' + clone_msg_platform + '</div>' + 
                    '</td>' +
                    '<td>' +
                        '<button type="button" class="btn btn-outline-danger w-100 remove-row" data-row="row-' + row_count + '">Remove</button>' +
                    '</td>' +
                '</tr>';

                $('#contact-numbers-table tbody').append(row);
            });

            $('#contact-numbers-table').on('click', '.remove-row', function (){
                var row = '#' + $(this).data('row');
                $(row).remove();
            });

            $('#contact-numbers-table').on('change', '.type-select', function(){
                var row = '#' + $(this).data('row');
                if($(this).val() == 'Mobile'){
                    $(row + '-msg-container').slideDown();
                }else{
                    $(row + '-msg-container').slideUp();
                }
            });

            // Add the following code if you want the name of the file appear on select
            $(".custom-file-input").change(function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });
    </script>
@endsection