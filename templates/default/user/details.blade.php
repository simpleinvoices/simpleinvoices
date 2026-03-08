{{-- * Script: details.tpl
* 	Biller details template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above --}}
<form name="frmpost" action="index.php?module=user&view=save&id={{ urlencode(get('id')) }}" method="post" id="frmpost" onsubmit="return checkForm(this);">
@if(get('action')== 'view' )

<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<th>{{ $LANG['email'] ?? '' }}</th>
			<td>{{ $user['email'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['role'] ?? '' }}</th>
			<td>{{ $user['role_name'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['password'] ?? '' }}</th>
			<td>*********</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>{{ $user['lang_enabled'] ?? '' }}</td>
		</tr>
		<tr>
			<th>{{ $LANG['users'] ?? '' }}</th>
			<td>{{ $user['user_id'] ?? '' }}</td>
		</tr>
	</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=user&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=user&amp;view=details&amp;id={{ urlencode($user['id'] ?? '') }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? 'Edit' }}</a>
		</div>
	</div>
</div>
@endif



@if(get('action')== 'edit' )
<div class="card">
	<div class="card-body">
	<table class="table table-vcenter">
		<tr>
			<th>{{ $LANG['email'] ?? '' }} 
			<a 
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field"
					title="{{ $LANG['required_field'] ?? '' }}"
			>
			<i class="ti ti-alert-circle text-danger"></i>
			</a>	
			</th>
			<td><input type="text" name="email" autocomplete="off" value="{{ $user['email'] ?? '' }}" size="35" id="email" class="form-control validate[required]" /></td>
		</tr>
		<tr>
			<th>{{ $LANG['role'] ?? '' }} 
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role"
					title="{{ $LANG['role'] ?? '' }}"
				> 
				<i class="ti ti-help"></i>
				</a>
			</th>
			<td>
					<select name="role" class="form-select">
						@foreach(($roles ?? []) as $role)
							<option @if($role['id'] == $user['role_id']) selected @endif value="{{ $role['id'] ?? '' }}">{{ $role['name'] ?? '' }}</option>
						@endforeach
					</select>
			</td>
		</tr>
		<tr>
			<th>
				{{ $LANG['new_password'] ?? '' }}
				<a
					class="cluetip"
					href="#"
					rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password"
					title="{{ $LANG['new_password'] ?? '' }}"
				> 
				<i class="ti ti-help"></i>
				</a>
			</th>
			<td>
			<input type="password" name="password_field" value="" size="25" class="form-control" />
		</td>
		</tr>
		<tr>
			<th>{{ $LANG['enabled'] ?? '' }}</th>
			<td>{html_options name=enabled options=$enabled selected=$user['enabled'] class="form-select"}</td>
		</tr>
		<tr>
			<th>{{ $LANG['users'] ?? '' }}</th>
			<td><input type="text" name="user_id" autocomplete="off" value="{{ $user['user_id'] ?? '' }}" size="12" id="user_id" class="form-control validate[required]" /></td>
		</tr>
	</table>

	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=user&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_user"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_user" />
<input type="hidden" name="id" value="{{ $user['id'] ?? '' }}" />
@endif
</form>
