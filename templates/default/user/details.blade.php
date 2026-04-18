{{-- User details / edit template --}}
<form name="frmpost" action="index.php?module=user&view=save&id={{ urlencode(get('id')) }}" method="post" id="frmpost" autocomplete="off" class="needs-validation" novalidate>

@if(get('action') == 'view')

<div class="card">
	<div class="card-body">
		<table class="table table-vcenter">
			<tr>
				<th>{{ $LANG['name'] ?? '' }}</th>
				<td>{{ $user['name'] ?? '' }}</td>
			</tr>
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
		</table>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=user&amp;view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<a href="./index.php?module=user&amp;view=details&amp;id={{ urlencode($user['id'] ?? '') }}&amp;action=edit" class="btn btn-primary ms-auto"><i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}</a>
		</div>
	</div>
</div>

@endif

@if(get('action') == 'edit')

<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['name'] ?? '' }}</label>
			<input type="text" name="name" autocomplete="off" value="{{ $user['name'] ?? '' }}" id="name" class="form-control" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['email'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}">
					<i class="ti ti-asterisk text-danger"></i>
				</a>
			</label>
			<input type="text" name="email" autocomplete="off" value="{{ $user['email'] ?? '' }}" id="email" class="form-control" required />
			<div class="invalid-feedback">{{ $LANG['required_field'] ?? 'Required' }}</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['role'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role" title="{{ $LANG['role'] ?? '' }}">
					<i class="ti ti-help"></i>
				</a>
			</label>
			<select name="role" class="form-select">
				@foreach(($roles ?? []) as $role)
					<option @if($role['id'] == $user['role_id']) selected @endif value="{{ $role['id'] ?? '' }}">{{ $role['name'] ?? '' }}</option>
				@endforeach
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['new_password'] ?? '' }}
				<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_new_password" title="{{ $LANG['new_password'] ?? '' }}">
					<i class="ti ti-help"></i>
				</a>
			</label>
			<input type="password" name="password_field" value="" autocomplete="new-password" class="form-control"
			       minlength="4" />
			<div class="invalid-feedback">New password must be at least 4 characters when set.</div>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
			{html_options name=enabled options=$enabled selected=$user['enabled'] class="form-select"}
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=user&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="save_user"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
		</div>
	</div>
</div>

<input type="hidden" name="op" value="edit_user" />
<input type="hidden" name="id" value="{{ $user['id'] ?? '' }}" />
@if(!empty($saveReturnModule ?? '') && !empty($saveReturnView ?? ''))
<input type="hidden" name="return_module" value="{{ $saveReturnModule }}" />
<input type="hidden" name="return_view" value="{{ $saveReturnView }}" />
@endif
<input type="hidden" name="csrfprotectionbysr" value="{{ $userSaveCsrfToken ?? '' }}" />

@endif
</form>
