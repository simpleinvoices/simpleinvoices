<div class="card">
	<form name="frmpost" action="index.php?module=system_defaults&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">
		<div class="card-body">
			<div class="mb-3">
				<label class="form-label">{{ $description ?? '' }}</label>
				<div>{!! $value !!}</div>
			</div>
		</div>
		<div class="card-footer d-flex gap-2">
			<button type="submit" class="btn btn-primary" name="submit" value="{{ $LANG['save'] ?? '' }}">
				<i class="ti ti-check me-1"></i>{{ $LANG['save'] ?? '' }}
			</button>
			<a href="./index.php?module=system_defaults&view=manage" class="btn btn-outline-secondary">
				<i class="ti ti-x me-1"></i>{{ $LANG['cancel'] ?? '' }}
			</a>
		</div>
		<input type="hidden" name="name" value="{{ $default ?? '' }}">
		<input type="hidden" name="op" value="update_system_defaults" />
	</form>
</div>
