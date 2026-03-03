@if($saved == 'true' )
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{{ $LANG['save_eway_success'] ?? '' }}
<br />
<br />
@endif
@if($saved == 'check_failed' )
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{{ $LANG['save_eway_check_failed'] ?? '' }}
<br />
<br />
@endif
@if($saved == 'false' )
<meta http-equiv="refresh" content="2;URL=index.php?module=payments&amp;view=manage" />
<br />
{{ $LANG['save_eway_failure'] ?? '' }}
<br />
<br />
@endif

@if($saved == false)

    @if(post('op') == 'add' AND post('invoice_id') == '')
        <div class="alert alert-warning"><i class="ti ti-alert-triangle me-1"></i>You must select an invoice</div>
    @endif


<form name="frmFpost" action="index.php?module=payments&view=eway" method="POST" id="frmpost">
<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['process_payment_via_eway'] ?? 'Eway Payment' }}</h3>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
<tr>
<td class="details_screen">{{ $LANG['invoice'] ?? '' }}</td>
<td>
<select name="invoice_id" class="form-select validate[required]">
<option value=''></option>
@foreach(($invoice_all ?? []) as $invoice)
<option value="{{ $invoice['id'] ?? '' }}" @if(get('id') == $invoice['id']) selected @endif >{{ $invoice['index_name'] ?? '' }}</option>
@endforeach
</select>
</td>
</tr>
<tr>
    <td colspan=2>
        <br />
        {{ $LANG['warning_eway'] ?? '' }}
        <br />
    </td>
</tr>
</table>
	</table>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="id" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		<input type="hidden" name="op" value="add" />
		<a href="./index.php?module=payments&view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>
</div>
</form>
@endif


