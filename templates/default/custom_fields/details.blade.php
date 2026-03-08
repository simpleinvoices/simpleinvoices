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

<div class="card">
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
			<td>{{ $cf['cf_custom_label'] ?? '' }}</td>
		</tr>
	</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=custom_fields&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=custom_fields&amp;view=details&amp;id={{ urlencode($cf['cf_id'] ?? '') }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? 'Edit' }}</a>
		</div>
	</div>
</div>

@endif




@if(get('action') == "edit" )

<div class="card">
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
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=custom_fields&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_custom_field" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_custom_field">
@endif
</form>
