<br />	 

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['recurrence'] ?? 'Cron' }} {{ $LANG['details'] ?? 'Details' }}</h3>
		<div class="card-actions">
			<a href="./index.php?module=cron&amp;view=edit&amp;id={{ urlencode($cron['id'] ?? '') }}" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
			<a href="./index.php?module=cron&view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
		</div>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<td class="details_screen">{{ $LANG['invoice'] ?? '' }}</td>
			<td>
				<a href="index.php?module=invoices&view=quick_view&id={{ $cron['invoice_id'] ?? '' }}">{{ $cron['index_name'] ?? '' }}</a>
			</td>
		</tr>
		<tr wrap="nowrap">
			<td class="details_screen">{{ $LANG['start_date'] ?? '' }}</td>
			<td>{{ $cron['start_date'] ?? '' }}</td>
		</tr>
		<tr wrap="nowrap">
			<td class="details_screen">{{ $LANG['end_date'] ?? '' }}</td>
			<td>{{ $cron['end_date'] ?? '' }} </td>
		</tr>
		<tr>
			<td class="details_screen">{{ $LANG['recur_each'] ?? '' }}</td>
			<td>{{ $cron['recurrence'] ?? '' }} {{ $cron['recurrence_type'] ?? '' }}</td>
		</tr>
		<tr>
			<td class="details_screen">{{ $LANG['email_biller_after_cron'] ?? '' }}</td>
			<td>
				@if($cron['email_biller'] == '1'){{ $LANG['yes'] ?? '' }}@endif
				@if($cron['email_biller'] == '0'){{ $LANG['no'] ?? '' }}@endif
			</td>
		</tr>
		<tr>
			<td class="details_screen">{{ $LANG['email_customer_after_cron'] ?? '' }}</td>
			<td>
				@if($cron['email_biller'] == '1'){{ $LANG['yes'] ?? '' }}@endif
				@if($cron['email_biller'] == '0'){{ $LANG['no'] ?? '' }}@endif
			</td>
		</tr>
	</table>
	</div>
</div>
