
<div class="row">
    <div class="col-md-6">
        <div class="text-left">
            <h5 class="font-weight-bold">Items on Cart <small class="text-muted font-italic">(grouped by location)</small></h5>
        </div>
    </div>
    <div class="col-md-6">
        <div class="text-right">
            Total: <b>{{ count($cart_per_loc) }}</b>
        </div>
    </div>
</div>
<table class="table table-bordered table-hover" style="font-size: 11pt;">
    <thead>
        <tr>
            <th class="text-center">Location</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($cart_per_loc as $row)
        <tr>
            <td class="text-center">{{ $row['location'] }}</td>
            <td class="text-center">
                <a href="#" data-toggle="modal" data-target="#modal-{{ Str::slug($row['location'], '-') }}">View Items <span class="badge badge-secondary">{{ count($row['items']) }}</span></a>
                <div class="modal fade" id="modal-{{ Str::slug($row['location'], '-') }}">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $row['location'] }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>Item Code</th>
                                        <th>Description</th>
                                        <th>No. of Carts</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($row['items'] as $item)
                                        <tr>
                                            <td>{{ $item['item_code'] }}</td>
                                            <td class="text-left">{{ $item['item_description'] }}</td>
                                            <td>{{ $row['item_codes'][$item['item_code']] }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>   
        @empty
        <tr>
            <td colspan="2" class="text-center text-muted">No records found.</td>
        </tr>
        @endforelse                                     
    </tbody>
</table>
<div class="float-right mt-4 ml-3" id="iocbl-paginate">
    {{ $cart_per_loc->withQueryString()->links('pagination::bootstrap-4') }}
</div>
