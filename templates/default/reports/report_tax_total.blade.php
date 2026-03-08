<div class="card">
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter si_report_table">
	<thead>
		<tr>
			<th>{{ $LANG['total_taxes'] ?? '' }}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="align_center">{{ siLocal::number($total_taxes ?? 0) ?: '-' }}</td>
		</tr>
	</tbody>
</table>
		</div>
	</div>
</div>
