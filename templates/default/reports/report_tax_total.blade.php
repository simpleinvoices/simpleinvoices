<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['total_taxes'] ?? '' }}</h3>
	</div>
	<div class="card-body">
		<div class="table-responsive">
<table class="table table-vcenter table-striped si_report_table">
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
