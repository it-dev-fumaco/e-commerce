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
        @foreach ($items as $item)
            <tr>
                <td>{{ $item->f_idcode }}</td>
            </tr>
        @endforeach
	</tbody>
</table>
{{ $items->withQueryString()->links('pagination::bootstrap-4') }}
