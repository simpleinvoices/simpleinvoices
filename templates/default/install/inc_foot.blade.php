			@if(!($install_new_domain_bootstrap ?? false))
			@php
				$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
			@endphp
			<div class="alert alert-info mt-4 mb-0" role="alert">
				<strong>{{ $LANG['note'] ?? '' }}:</strong>
				{{ $LANG['install_help'] ?? '' }}
				<a href="{{ $appWebsite }}" target="_blank" rel="noopener" class="alert-link">{{ $LANG['install_documentation'] ?? '' }}</a>
				( <a href="{{ $appWebsite }}" target="_blank" rel="noopener" class="alert-link">{{ $appWebsite }}</a> )
			</div>
			@endif
		</div>
	</div>
</div>
