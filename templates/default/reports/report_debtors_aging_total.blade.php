<div class="card">
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter si_report_table">
	<thead>
		<tr>
			<th colspan="4">{{ $LANG['total_by_aging_periods'] ?? '' }}</th>
		</tr>
		<tr>
			<th>{{ $LANG['total'] ?? '' }}</th>
			<th>{{ $LANG['paid'] ?? '' }}</th>
			<th>{{ $LANG['owing'] ?? '' }}</th>
			<th>{{ $LANG['aging'] ?? '' }}</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td>{{ siLocal::number($sum_total ?? 0) ?: '-' }}</td>
			<td>{{ siLocal::number($sum_paid ?? 0) ?: '-' }}</td>
			<td>{{ siLocal::number($sum_owing ?? 0) ?: '-' }}</td>
			<td></td>
		</tr>
	</tfoot>
	<tbody>
	@foreach(item=period from=$data)
		<tr>
			<td>{{ siLocal::number($period['inv_total'] ?? 0) ?: '-' }}</td>
			<td>{{ siLocal::number($period['inv_paid'] ?? 0) ?: '-' }}</td>
			<td>{{ siLocal::number($period['inv_owing'] ?? 0) ?: '-' }}</td>
			<td>{{ $period['aging'] }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
		</div>
	</div>
</div>
