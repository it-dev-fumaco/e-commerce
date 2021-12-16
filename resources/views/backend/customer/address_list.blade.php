<table class="table table-hover table-bordered" style="font-size: 11pt;">
    <thead>
        <tr>
            <th style="width: 15%" class="text-center">Address Type</th>
            <th style="width: 15%" class="text-center">Contact Person</th>
            <th style="width: 15%" class="text-center">Contact No.</th>
            <th style="width: 15%" class="text-center">Contact Email</th>
            <th style="width: 40%" class="text-center">Address</th>
        </tr>
    </thead>
    @forelse ($address_list as $address)
    <tr>
        <td class="text-center">
            <span class="d-block">{{ $address->add_type }}</span>
            <span class="badge badge-primary {{ $address->xdefault != 1 ? 'd-none' : '' }}">Default</span>
        </td>
        <td class="text-center">{{ $address->xcontactname1.' '.$address->xcontactlastname1 }}</td>
        <td class="text-center">{{ $address->xcontactnumber1 != 0 ? $address->xcontactnumber1 : null }}</td>
        <td class="text-center">{{ $address->xcontactemail1 }}</td>
        <td>
            @php
                $bill_address2 = str_replace(' ', '', $address->xadd2) ? $address->xadd2.', ' : null;
            @endphp
            {{ $address->xadd1.', '.$bill_address2.$address->xbrgy.', '.$address->xcity.', '.$address->xprov.', '.$address->xcountry.' '.$address->xpostal }}
        </td>
    </tr>
    @empty
    <tr>
        <td class="text-center" colspan="7">No Billing Address(es)</td>
    </tr>
    @endforelse
</table>
<div class="float-right" id="{{ ($address_type == 'Delivery') ? 'shipping' : 'billing' }}-address-paginate">
    {{ $address_list->links('pagination::bootstrap-4') }}
</div>