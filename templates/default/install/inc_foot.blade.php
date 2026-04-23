			@if(!($install_new_domain_bootstrap ?? false))
			@php
				$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
			@endphp
			<div class="text-center mt-4 text-secondary small">
				Need help? Visit <a href="{{ $appWebsite }}" target="_blank" rel="noopener">{{ $appWebsite }}</a> for documentation and support.
			</div>
			@endif
		</div>
	</div>
</div>
