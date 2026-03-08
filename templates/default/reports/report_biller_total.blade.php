<div class="card">
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter si_report_table">
	<thead>
		<tr>
			<th colspan="2">{{ $LANG['biller_sales_total'] ?? '' }}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" class="PAGE_LAYER">{{ $LANG['total_sales'] ?? '' }}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{{ siLocal::number($total_sales ?? 0) ?: '-' }}</span></td>
		</tr>
	</tfoot>
	<tbody>
	@foreach(item=biller from=$data)
		<tr class="tr_{cycle values="A,B"}">
			<td>{{ $biller['name'] ?? '' }}</td>
			<td>{{ siLocal::number($biller['sum_total'] ?? 0) ?: '-' }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
