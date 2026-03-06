<div class="card">
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter table-striped si_report_table">
	<thead>
		<tr>
			<th colspan="7">{{ $LANG['debtors_by_amount_owed'] ?? '' }}</th>
		</tr>
		<tr>
			<th>{{ $LANG['invoice_id'] ?? '' }}</th>
			<th>{{ $LANG['invoice'] ?? '' }}</th>
			<th>{{ $LANG['biller'] ?? '' }}</th>
			<th>{{ $LANG['customer'] ?? '' }}</th>
			<th>{{ $LANG['total'] ?? '' }}</th>
			<th>{{ $LANG['paid'] ?? '' }}</th>
			<th>{{ $LANG['owing'] ?? '' }}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td align="RIGHT" colspan="6" class="PAGE_LAYER">{{ $LANG['total_owed'] ?? '' }}</td>
			<td align="LEFT" class="PAGE_LAYER"><span class="BOLD">{{ siLocal::number($total_owed ?? 0) ?: '-' }}</span></td>
		</tr>
	</tfoot>
	<tbody>
	@foreach(item=invoice from=$data)
		<tr>
			<td>{{ $invoice['id'] ?? '' }}</td>
			<td>{{ $invoice['pref_inv_wording'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</td>
			<td>{{ $invoice['biller'] ?? '' }}</td>
			<td>{{ $invoice['customer'] ?? '' }}</td>
			<td>{{ siLocal::number($invoice['inv_total'] ?? 0) }}</td>
			<td>{{ siLocal::number($invoice['inv_paid'] ?? 0) }}</td>
			<td>{{ siLocal::number($invoice['inv_owing'] ?? 0) }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
