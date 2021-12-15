<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th style="width: 5%;"></th>
			<th style="width: 10%;" class="text-center">Image</th>
			<th style="width: 70%;" class="text-center">Item Description</th>
			<th style="width: 15%;" class="text-center">Price</th>
		</tr>
	</thead>
	<tbody>
		@forelse ($list as $item)
		@php
			$image = ($item['image']) ? '/storage/item_images/'. $item['item_code'] .'/gallery/preview/'.$item['image'] : '/storage/no-photo-available.png';
		@endphp
		<tr>
			<td class="text-center align-middle">
				<input type="checkbox" value="{{ $item['item_code'] }}" name="selected_products[]">
			</td>
			<td class="text-center align-middle">
				<img src="{{ asset($image) }}" class="img-responsive rounded img-thumbnail d-inline-block" width="70" height="70">
			</td>
			<td><span class="d-block font-weight-bold">{{ $item['item_code'] }}</span>{{ $item['item_description'] }}</td>
			<td class="text-center">P {{ number_format($item['original_price'], 2) }}</td>
		</tr>
		@empty
		<tr>
			<td colspan="4" class="text-center text-muted">No products found.</td>
		</tr>
		@endforelse
	</tbody>
</table>
<div class="float-right mt-4">
    {{ $query->withQueryString()->links('pagination::bootstrap-4') }}
</div>