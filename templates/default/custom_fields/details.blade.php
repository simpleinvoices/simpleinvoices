{{-- * Script: details.tpl
* 	Custom fields details template
*
* Website:
* 	 http://www.simpleinvoices.org
*
* License:
*	 GPL v3 or above --}}

<form name="frmpost" action="index.php?module=custom_fields&amp;view=save&amp;id={{ urlencode(get('id')) }}" method="POST" onsubmit="return frmpost_Validator(this);">


@if(get('action') == "view" )

<div class="si_form si_form_view">	
	<table>
		<tr>
			<th>{{ $LANG['id'] ?? '' }}</th>
			<td>{{ $cf['cf_id'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['custom_field_db_field_name'] ?? '' }}</th>
			<td>{{ $cf['cf_custom_field'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['custom_field'] ?? '' }}</th>
			<td>{{ $cf['name'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['custom_label'] ?? '' }}</th>
			<td>{{ $cf['cf_custom_label'] ?? '' }}</td>
		</tr>
	</table>
</div>

	<div class="si_toolbar si_toolbar_form">
		<a href="./index.php?module=custom_fields&amp;view=details&amp;id={{ urlencode($cf['cf_id'] ?? '') }}&amp;action=edit" class="positive">
			<img src="./images/common/tick.png" alt="" />
			{{ $LANG['edit'] ?? '' }}
		</a>
	</div>

@endif




@if(get('action') == "edit" )

<div class="card">
	<div class="card-header">
		<h3 class="card-title">{{ $LANG['edit'] ?? '' }} {{ $LANG['custom_field'] ?? '' }}</h3>
	</div>
	<div class="card-body">
	<table class="table table-vcenter">
        <tr>
                <th>{{ $LANG['id'] ?? '' }}</th>
				<td>{{ $cf['cf_id'] ?? '' }}</td>
		</tr>
		<tr>
                <th>{{ $LANG['custom_field_db_field_name'] ?? '' }}</th>
                <td>{{ $cf['cf_custom_field'] ?? '' }}</td>
        </tr>
        <tr>
                <th>{{ $LANG['custom_field'] ?? '' }}</th>
                <td>{{ $cf['name'] ?? '' }}</td>
        </tr>
		<tr>
			<th>{{ $LANG['custom_label'] ?? '' }}</th>
			<td><input type="text" name="cf_custom_label" size="25" value="{{ $cf['cf_custom_label'] ?? '' }}" class="form-control" /></td>
		</tr>
	</table>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary" name="save_custom_field" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		<a href="./index.php?module=custom_fields&amp;view=manage" class="btn btn-outline-secondary"><i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}</a>
	</div>
</div>

<input type="hidden" name="op" value="edit_custom_field">
@endif
</form>
