{{-- Invoice Duplicate Result --}}
@php
	$duplicateFailMsg = ($saved == true) ? '' : ($LANG['save_invoice_failure'] ?? '');
@endphp
@include('shared.save_alert', [
	'success' => ($saved == true),
	'title' => ($saved == true) ? ($LANG['duplicate_invoice'] ?? 'Duplicate Invoice') : ($LANG['save_invoice_failure'] ?? 'Error'),
	'message' => outhtml(($saved == true) ? ($LANG['duplicate_invoice_success'] ?? 'Invoice duplicated successfully. Redirecting to the new invoice...') : $duplicateFailMsg)
])
@if($saved == true && $new_id)
<meta http-equiv="refresh" content="2;URL=index.php?module=invoices&amp;view=quick_view&amp;id={{ urlencode($new_id) }}" />
@endif