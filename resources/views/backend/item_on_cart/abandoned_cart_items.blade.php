<div class="row">
    <div class="col-md-6 offset-md-6" style="margin-top: -50px;">
        <div class="text-right">
            Total: <b>{{ $abandoned_cart->total() }}</b>
        </div>
    </div>
</div>
<table class="table table-bordered table-hover" style="font-size: 11pt;">
    <col style="width: 5%;">
    <col style="width: 35%;">
    <col style="width: 15%;">
    <col style="width: 10%;">
    <col style="width: 20%;">
    <col style="width: 15%;">
    <thead>
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Item Description</th>
            <th class="text-center">User Account</th>
            <th class="text-center">IP Address</th>
            <th class="text-center">Location</th>
            <th class="text-center">Timestamp</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($abandoned_cart as $row)
        <tr>
            <td class="text-center">{{ $row->id }}</td>
            <td>{!! '<b>'. $row->item_code .'</b> - '. $row->item_description !!}</td>
            <td class="text-center">{{ ($row->user_type == 'guest') ? $row->user_type : $row->user_email }}</td>
            <td class="text-center">{{ $row->ip }}</td>
            <td class="text-center">{{ $row->city .' '. $row->region .' '. $row->country }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($row->last_modified_at)->format('M d, Y - h:i A') }}</td>
        </tr>   
        @empty
        <tr>
            <td colspan="7" class="text-center text-muted">No items found.</td>
        </tr>
        @endforelse                                     
    </tbody>
</table>
<div class="float-right mt-4 ml-3" id="abandoned-cart-paginate">
    {{ $abandoned_cart->withQueryString()->links('pagination::bootstrap-4') }}
</div>