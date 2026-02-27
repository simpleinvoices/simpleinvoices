<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['payment'] ?? '' }} {{ $LANG['details'] ?? 'Details' }}</h3>
	</div>
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<th>{{ $LANG['payment_id'] ?? '' }}</th><td>{{ $payment['id'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['invoice_id'] ?? '' }}</th><td><a href='index.php?module=invoices&amp;view=quick_view&amp;id={{ $payment['ac_inv_id'] ?? '' }}&amp;action=view'>{{ $payment['ac_inv_id'] ?? '' }}</a></td>
			</tr>
			<tr>
				<th>{{ siLocal::number($LANG['amount']) }}</th><td>{{ $payment['ac_amount'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['date_upper'] ?? '' }}</th><td>{{ $payment['date'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['biller'] ?? '' }}</th><td>{{ $payment['biller'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['customer'] ?? '' }}</th><td>{{ $payment['customer'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['payment_type'] ?? '' }}</th><td>{{ $paymentType['pt_description'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['online_payment_id'] ?? '' }}</th><td>{{ $payment['online_payment_id'] ?? '' }}</td>
			</tr>
			<tr>
				<th>{{ $LANG['notes'] }}</th><td>{{ $payment['ac_notes'] ?? '' }}</td>
			</tr>
		</table>
	</div>
	<div class="card-footer text-end">
		<a href="./index.php?module=payments&view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>
</div>
