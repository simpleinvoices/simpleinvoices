{{-- /*
* View: manage_sqlpatches (Blade)
* 	 Manage sql patches template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
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
					<th>{{ $LANG['patch_id'] ?? '' }}</th>
					<th>{{ $LANG['description'] ?? '' }}</th>
					<th>{{ $LANG['release'] ?? '' }}</th>
				</tr>
			</thead>
			<tbody>
@foreach(($patches ?? []) as $patch)
				<tr>
					<td>{{ $patch['sql_patch_ref'] ?? '' }}</td>
					<td>{!! nl2br($patch['sql_patch'] ?? '') !!}</td>
					<td>{{ $patch['sql_release'] ?? '' }}</td>
				</tr>
@endforeach
			</tbody>
		</table>
	</div>
</div>
