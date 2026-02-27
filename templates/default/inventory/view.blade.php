<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['inventory'] ?? 'Inventory' }} {{ $LANG['details'] ?? 'Details' }}</h3>
		<div class="card-actions">
			<a href="./index.php?module=inventory&amp;view=edit&amp;id={{ urlencode($inventory['id'] ?? '') }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
			<a href="./index.php?module=inventory&view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
	<tr>
		<th>{{ $LANG['date_upper'] ?? '' }}</th>
		<td>{{ $inventory['date'] ?? '' }}</td>
	</tr>
	<tr>
		<th>{{ $LANG['product'] ?? '' }}</th>
		<td>{{ $inventory['description'] ?? '' }}</td>
	</tr>
	<tr>
		 <th>{{ $LANG['quantity'] ?? '' }}</th>
		<td>{{ siLocal::number($inventory['quantity'] ?? '') }}</td>
	</tr>
	<tr>
		<th>{{ $LANG['cost'] ?? '' }}</th>
		<td>{{ siLocal::number($inventory['cost'] ?? '') }}</td>
	</tr>
	<tr>
		<th>{{ $LANG['notes'] ?? '' }}</th>
		<td>{{ $inventory['note'] }}</td>
	</tr>
	</table>
	</div>
</div>
