<div class="card">
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter table-striped si_report_table">
	<thead>
		<tr>
			<th colspan="5">{{ $LANG['debtors_by_amount_owing_customer'] ?? '' }}</th>
		</tr>
		<tr>
			<th>{{ $LANG['id'] ?? '' }}</th>
			<th>{{ $LANG['customer'] ?? '' }}</th>
			<th>{{ $LANG['total'] ?? '' }}</th>
			<th>{{ $LANG['paid'] ?? '' }}</th>
			<th>{{ $LANG['owing'] ?? '' }}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="4">{{ $LANG['total_owing'] ?? '' }}</th>
			<td>{{ siLocal::number($total_owed ?? 0) ?: '-' }}</td>
		</tr>
	</tfoot>
	<tbody>
	@foreach(item=customer from=$data)
		<tr>
			<td>{{ $customer['cid'] ?? '' }}</td>
			<td>{{ $customer['customer'] ?? '' }}</td>
			<td>{{ siLocal::number($customer['inv_total'] ?? 0) ?: '-' }}</td>
			<td>{{ siLocal::number($customer['inv_paid'] ?? 0) ?: '-' }}</td>
			<td>{{ siLocal::number($customer['inv_owing'] ?? 0) ?: '-' }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
