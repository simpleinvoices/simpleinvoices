{{-- Single system-default row: icon, label (optional help), value, edit button --}}
<li class="list-group-item">
	<div class="row align-items-center">
		<div class="col">
			<div class="d-flex align-items-center">
				<span class="avatar avatar-sm bg-primary-lt text-primary me-3 rounded">
					<i class="ti {{ $icon ?? 'ti-settings' }}"></i>
				</span>
				<div>
					<span class="text-body fw-medium">
						{{ $label ?? '' }}
						@if(!empty($help_url))
							<a class="cluetip ms-1 text-secondary" href="#" rel="{{ $help_url }}" title="{{ $help_title ?? $label }}"><i class="ti ti-help small"></i></a>
						@endif
					</span>
				</div>
			</div>
		</div>
		<div class="col-auto text-secondary">
			{{ $value ?? '—' }}
		</div>
		<div class="col-auto">
			<a href="index.php?module=system_defaults&amp;view=edit&amp;submit={{ urlencode($edit_param ?? '') }}" class="btn btn-outline-primary" title="{{ $LANG['edit'] ?? '' }}">
				<i class="ti ti-edit me-1"></i>{{ $LANG['edit'] ?? '' }}
			</a>
		</div>
	</div>
</li>
