{{-- /*
* View: details (Blade)
* 	 Payment type details template
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<form name="frmpost" action="index.php?module=payment_types&amp;view=save&amp;id={{ get('id') }}" method="post" onsubmit="return frmpost_Validator(this)">

@if(get('action') == "view" )
	
<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<th>{{ $LANG['description'] ?? '' }}</th>
			<td>{{ $paymentType['pt_description'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>{{ $paymentType['enabled'] ?? '' }}</td>
		</tr>
	</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payment_types&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=payment_types&amp;view=details&amp;id={{ $paymentType['pt_id'] ?? '' }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
</div>

@endif



@if(get('action') == "edit")

<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<td class="details_screen">{{ $LANG['description'] ?? '' }} <a href="index.php?module=documentation&amp;view=view&amp;page=help_required_field" rel="gb_page_center[350, 150]"><i class="ti ti-alert-circle text-danger"></i></a></td>
			<td>
				<input type="text" name="pt_description" value="{{ $paymentType['pt_description'] ?? '' }}" size="30" class="form-control validate[required]" />
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }} </th>
			<td>
			{{-- displayblock enabled --}}
			<select name="pt_enabled" class="form-select">
				<option value="{{ $paymentType['pt_enabled'] ?? '' }}" selected style="font-weight: bold">{{ $paymentType['enabled'] ?? '' }}</option>
				<option value="1">{{ $LANG['enabled'] ?? '' }}</option>
				<option value="0">{{ $LANG['disabled'] ?? '' }}</option>
			</select>
			{{-- /displayblock enabled --}}
			</td>
		</tr>
	</table>

	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=payment_types&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_payment_type" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_payment_type">
@endif
</form>