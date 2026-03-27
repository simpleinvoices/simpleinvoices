			@php
				$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
			@endphp
			<div class="alert alert-info mt-4 mb-0" role="alert">
				<strong>{{ $LANG['note'] ?? 'Note' }}:</strong>
				{{ $LANG['install_help'] ?? 'If you have any problems or queries re installation please refer to the' }}
				<a href="{{ $appWebsite }}" target="_blank" rel="noopener" class="alert-link">install documentation</a>
				( <a href="{{ $appWebsite }}" target="_blank" rel="noopener" class="alert-link">{{ $appWebsite }}</a> )
			</div>
		</div>
	</div>
</div>
