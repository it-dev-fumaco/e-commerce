<div class="row" style="min-height: 200px;">
    @forelse ($address_list as $address)
    <div class="col-md-4">
        <div class="card">
            <div class="card-body p-1">
                <dl class="row m-1">
                    <dt class="col-sm-12">{{ $address->add_type }} <span class="badge badge-primary {{ $address->xdefault != 1 ? 'd-none' : '' }}">Default</span></dt>
                    <dd class="col-sm-12">
                        @php
                            $bill_address2 = str_replace(' ', '', $address->xadd2) ? $address->xadd2.', ' : null;
                        @endphp
                        <p class="m-0">{!! $address->xadd1.', '.$bill_address2.$address->xbrgy.', '.$address->xcity.', <br> '.$address->xprov.', '.$address->xcountry.' '.$address->xpostal !!}</p>
                        <p class="m-0">{{ $address->xcontactname1 .' ' . $address->xcontactlastname1 }}</p>
                        <p class="m-0">{{ $address->xcontactemail1 }}</p>
                        <p class="m-0">{{ $address->xcontactnumber1 }}</p>
                        <p class="m-0">{{ $address->xmobile_number }}</p>
                    </dd>
                  </dl>
            </div>
        </div>
    </div>
    @empty
    <div class="col-md-12">
        <h4 class="text-center text-muted m-5">No address found.</h4>
    </div>
    @endforelse
</div>
<div class="float-right" id="{{ ($address_type == 'Delivery') ? 'shipping' : 'billing' }}-address-paginate">
    {{ $address_list->links('pagination::bootstrap-4') }}
</div>

