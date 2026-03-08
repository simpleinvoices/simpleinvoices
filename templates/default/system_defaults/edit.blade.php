<div class="card">
	<form name="frmpost" action="index.php?module=system_defaults&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">
		<div class="card-body">
			<div class="mb-3">
				<label class="form-label">{{ $description ?? '' }}</label>
				<div>{!! $value !!}</div>
			</div>
		</div>
		<div class="card-footer">
			<div class="d-flex">
				<a href="./index.php?module=system_defaults&view=manage" class="btn btn-link">{{ $LANG['cancel'] ?? '' }}</a>
				<button type="submit" class="btn btn-primary ms-auto" name="submit" value="{{ $LANG['save'] ?? '' }}"><i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}</button>
			</div>
		</div>
		<input type="hidden" name="name" value="{{ $default ?? '' }}">
		<input type="hidden" name="op" value="update_system_defaults" />
	</form>
</div>
