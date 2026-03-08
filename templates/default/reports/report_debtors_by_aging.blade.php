<div class="card">
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter si_report_table">
	<thead>
		<th colspan="10">{{ $LANG['debtors_by_aging_periods'] ?? '' }}</th>
	</thead>
	<tfoot>
		<tr>
			<th colspan="6">{{ $LANG['total_owed'] ?? '' }}</th>
			<td>{{ siLocal::number($total_owed ?? 0) ?: '-' }}</td>
			<td colspan="3"></td>
		</tr>
	</tfoot>
	<tbody>
		@foreach(item=period from=$data)
			<tr>
				<th>{{ $LANG['aging'] ?? '' }}:</th>
				<td colspan="9">{{ $period['name'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['invoice_id'] ?? '' }}</th>
				<th>{{ $LANG['invoice'] ?? '' }}</th>
				<th>{{ $LANG['biller'] ?? '' }}</th>
				<th>{{ $LANG['customer'] ?? '' }}</th>
				<th>{{ $LANG['total'] ?? '' }}</th>
				<th>{{ $LANG['paid'] ?? '' }}</th>
				<th>{{ $LANG['owing'] ?? '' }}</th>
				<th>{{ ($LANG['date'] ?? '') | ucfirst }}</th>
				<th>{{ $LANG['age'] ?? '' }}</th>
				<th>{{ $LANG['aging'] ?? '' }}</th>
			</tr>

			@foreach(($period['invoices'] ?? []) as $invoice)
			<tr>
				<td>{{ $invoice['id'] ?? '' }}</td>
				<td>{{ $invoice['pref_inv_wording'] ?? '' }} {{ $invoice['index_id'] ?? '' }}</td>
				<td>{{ $invoice['biller'] ?? '' }}</td>
				<td>{{ $invoice['customer'] ?? '' }}</td>
				<td>{{ siLocal::number($invoice['inv_total'] ?? 0) ?: '-' }}</td>
				<td>{{ siLocal::number($invoice['inv_paid'] ?? 0) ?: '-' }}</td>
				<td>{{ siLocal::number($invoice['inv_owing'] ?? 0) ?: '-' }}</td>
				<td>{{ $invoice['date'] ?? '' }}</td>
				<td>{{ $invoice['age'] ?? '' }}</td>
				<td>{{ $invoice['Aging'] ?? '' }}</td>
			</tr>
			@endforeach

			<tr>
				<th colspan="6">{{ $LANG['total'] ?? '' }}</th>
				<td>{{ siLocal::number($period['sum_total'] ?? 0) ?: '-' }}</td>
				<td colspan="3"></td>
			</tr>

		@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>