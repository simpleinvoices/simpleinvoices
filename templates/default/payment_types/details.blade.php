{{-- /*
* Script: details.tpl
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
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['payment_type'] ?? '' }} {{ $LANG['details'] ?? 'Details' }}</h3>
		<div class="card-actions">
			<a href="./index.php?module=payment_types&amp;view=details&amp;id={{ $paymentType['pt_id'] }}&amp;action=edit" class="btn btn-primary"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
			<a href="./index.php?module=payment_types&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
		</div>
	</div>
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
</div>

@endif



@if(get('action') == "edit")

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['edit'] ?? '' }} {{ $LANG['payment_type'] ?? '' }}</h3>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<td class="details_screen">{{ $LANG['description'] ?? '' }} <a href="index.php?module=documentation&amp;view=view&amp;page=help_required_field" rel="gb_page_center[350, 150]"><i class="ti ti-alert-circle text-danger"></i></a></td>
			<td>
				<input type="text"  class="validate[required]"  name="pt_description" value="{{ $paymentType['pt_description'] ?? '' }}" size="30" />
			</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }} </th>
			<td>
			{{-- displayblock enabled --}}
			<select name="pt_enabled">
				<option value="{{ $paymentType['pt_enabled'] ?? '' }}" selected style="font-weight: bold">{{ $paymentType['enabled'] ?? '' }}</option>
				<option value="1">{{ $LANG['enabled'] ?? '' }}</option>
				<option value="0">{{ $LANG['disabled'] ?? '' }}</option>
			</select>
			{{-- /displayblock enabled --}}
			</td>
		</tr>
	</table>

	<div class="card-footer text-end">
				<button type="submit" class="btn btn-primary" name="save_payment_type" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
				<a href="./index.php?module=payment_types&amp;view=manage" class="btn btn-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_payment_type">
@endif
</form>