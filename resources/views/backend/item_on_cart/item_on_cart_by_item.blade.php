<div class="row">
    <div class="col-md-6">
        <div class="text-left">
            <h5 class="font-weight-bold">Items on Cart <small class="text-muted font-italic">(grouped by item)</small></h5>
        </div>
    </div>
    <div class="col-md-6">
        <div class="text-right">
            Total: <b>{{ $list_per_item->total() }}</b>
        </div>
    </div>
</div>
<table class="table table-bordered table-hover" style="font-size: 11pt;">
    <col style="width: 80%;">
    <col style="width: 20%;">
    <thead>
        <tr>
            <th class="text-center">Item Description</th>
            <th class="text-center">No. of Carts</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($list_per_item as $row)
        <tr>
            <td>{!! '<b>'. $row->item_code .'</b> - '. $row->item_description !!}</td>
            <td class="text-center">{{ $row->count }}</td>
        </tr>   
        @empty
        <tr>
            <td colspan="2" class="text-center text-muted">No items found.</td>
        </tr>
        @endforelse                                     
    </tbody>
</table>
<div class="float-right mt-4 ml-3" id="iocbi-paginate">
    {{ $list_per_item->withQueryString()->links('pagination::bootstrap-4') }}
</div>