<div class="card">
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter table-striped si_report_table">
	<thead>
		<tr>
			<th colspan="2">{{ $LANG['products_sold_total'] ?? '' }}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" class="PAGE_LAYER">{{ $LANG['total'] ?? '' }}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{{ siLocal::number($total_quantity ?? 0) ?: '-' }}</span></td>
		</tr>
	</tfoot>
	<tbody>
	@foreach(item=customer from=$data)
		<tr class="tr_{cycle values="A,B"}">
			<td>{{ $customer['description'] ?? '' }}</td>
			<td>{{ siLocal::number($customer['sum_quantity'] ?? 0) ?: '-' }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
