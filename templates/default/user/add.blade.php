{{-- * Script: add.tpl
* 	User add template
*
* Last edited:
* 	 2008-08-25
*
* License:
*	 GPL v3 or above --}}


@if(post('email') != null && form_submitted() )
	@include('templates.default.user.save')
@else

<form name="frmpost" action="index.php?module=user&amp;view=add" method="post" id="frmpost">
<div class="card">
	<div class="card-body">
		<div class="mb-3">
			<label class="form-label">{{ $LANG['name'] ?? 'Name' }}</label>
			<input type="text" name="name" value="{{ post('name') }}" id="name" autocomplete="off" class="form-control" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['email'] ?? '' }}
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_required_field" title="{{ $LANG['required_field'] ?? '' }}">
				<i class="ti ti-asterisk text-danger"></i>
			</a>
			</label>
			<input type="text" name="email" value="{{ post('email') }}" id="email" autocomplete="off" class="form-control validate[required]" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['role'] ?? '' }}
			<a class="cluetip" href="#" rel="index.php?module=documentation&amp;view=view&amp;page=help_user_role" title="{{ $LANG['role'] ?? '' }}">
				<i class="ti ti-help"></i>
			</a>
			</label>
			<select name="role" class="form-select">
				@foreach(($roles ?? []) as $role)
					<option value="{{ $role['id'] ?? '' }}">{{ $role['name'] ?? '' }}</option>
				@endforeach
			</select>
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['password'] ?? '' }}</label>
			<input type="password" name="password_field" value="{{ post('password_field') }}" class="form-control" />
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['enabled'] ?? '' }}</label>
			{html_options name=enabled options=$enabled selected=1 class="form-select"}
		</div>
		<div class="mb-3">
			<label class="form-label">{{ $LANG['users'] ?? '' }}</label>
			<input type="text" name="user_id" value="{{ post('user_id') }}" size="12" id="user_id" autocomplete="off" class="form-control validate[required]" />
		</div>
	</div>
	<div class="card-footer">
		<div class="d-flex">
			<a href="./index.php?module=user&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
			<button type="submit" class="btn btn-primary ms-auto" name="submit" value="Insert User"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
		</div>
	</div>
</div>
<input type="hidden" name="op" value="insert_user" />
<input type="hidden" name="csrfprotectionbysr" value="{{ $userSaveCsrfToken ?? '' }}" />
</form>
@endif
