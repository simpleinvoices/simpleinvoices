{{-- /*
* Script: manage_cronlogs.tpl
* 	 Manage Cron Logs template
*
* Authors:
*	 Ap.Muthu
*
* Last edited:
* 	 2013-10-20
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
	<div class="table-responsive">
		<table class="table table-vcenter card-table" id="live-grid">
			<thead>
				<tr>
					<th>ID</th>
					<th>Date</th>
					<th>Cron ID</th>
				</tr>
			</thead>
			<tbody>
@foreach(($cronlogs ?? []) as $cronlog)
				<tr>
					<td>{{ $cronlog['id'] ?? '' }}</td>
					<td>{{ $cronlog['run_date'] ?? '' }}</td>
					<td><a href="index.php?module=cron&view=view&id={{ $cronlog['cron_id'] ?? '' }}">{{ $cronlog['cron_id'] ?? '' }}</a></td>
				</tr>
@endforeach
			</tbody>
		</table>
	</div>
</div>
