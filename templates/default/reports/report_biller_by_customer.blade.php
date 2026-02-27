<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['biller_sales_by_customer_totals'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter table-striped si_report_table">
	<thead>
		<tr>
			<th colspan="2">{{ $LANG['biller_sales_by_customer_totals'] ?? '' }}</th>
		</tr>
	</thead>
	<tbody>
	@foreach(item=biller from=$data)
		<tr>
			<th colspan="2">{{ $LANG['biller_name'] ?? '' }}: {{ $biller['name'] ?? '' }}</th>
		</tr>
		<tr>
			<th>{{ $LANG['customer_name'] ?? '' }}</th>
			<th>{{ $LANG['sales'] ?? '' }}</th>
		</tr>
		@foreach(($biller['customers'] ?? []) as $customer)
			<tr>
				<td>{{ $customer['name'] ?? '' }}</td>
				<td>{{ siLocal::number($customer['sum_total'] ?? 0) ?: '-' }}</td>
			</tr>
		@endforeach
		<tr>
			<td>{{ $LANG['total'] ?? '' }}</td>
			<td>{{ siLocal::number($biller['total_sales'] ?? 0) ?: '-' }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
