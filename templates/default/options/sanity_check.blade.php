{{-- /*
* View: sanity_check (Blade)
* 	 Sanity check template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/ --}}

<div class="card">
	<div class="card-body">
		@php
			$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
		@endphp
		<p>{{ sprintf($LANG['sanity_check_wip'] ?? 'This feature is still a work-in-progress, please refer to our homepage: %s for updates.', '<a href="' . e($appWebsite) . '" target="_blank" rel="noopener">' . e($appWebsite) . '</a>') }}</p>
	</div>
</div>
