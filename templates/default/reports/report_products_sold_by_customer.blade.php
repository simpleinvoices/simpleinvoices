<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['products_sold_customer_total'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter table-striped si_report_table">
	<thead>
		<tr>
			<th colspan="2">{{ $LANG['products_sold_customer_total'] ?? '' }}</th>
		</tr>
	</thead>
	<tbody>
	@foreach(item=customer from=$data)
		<tr>
			<td colspan="2">{{ $customer['name'] ?? '' }}</td>
		</tr>
		@foreach(($customer['products'] ?? []) as $product)
			<tr>
				<td>{{ $product['description'] ?? '' }}</td>
				<td>{{ siLocal::number($product['sum_quantity'] ?? 0) ?: '-' }}</td>
			</tr>
		@endforeach
		<tr>
			<td>{{ $LANG['total'] ?? '' }}</td>
			<td>{{ siLocal::number($customer['total_quantity'] ?? 0) ?: '-' }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
