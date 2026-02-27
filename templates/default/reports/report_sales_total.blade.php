<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['total_sales'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter table-striped si_report_table">
	<thead>
		<tr>
			<th class="align_left">{{ $LANG['invoice_preferences'] ?? '' }}</th>
			<th class="align_right">{{ $LANG['invoices'] ?? '' }}</th>
			<th class="align_right">{{ $LANG['total_sales'] ?? '' }}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" colspan="2" class="PAGE_LAYER">{{ $LANG['total_sales'] ?? '' }}: </td>
			<td align="RIGHT" class="PAGE_LAYER"><span class="BOLD">{{ siLocal::number($grand_total_sales ?? 0) ?: '-' }}</span></td>
		</tr>
	</tfoot>
	<tbody>
	@foreach(item=total_sales from=$data)
		<tr>
			<td class="align_left">{{ $total_sales['template'] ?? '' }}</td>
			<td class="align_right">{{ siLocal::number($total_sales['count'] ?? 0) ?: '-' }}</td>
			<td class="align_right">{{ siLocal::number($total_sales['sum_total'] ?? 0) ?: '-' }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
