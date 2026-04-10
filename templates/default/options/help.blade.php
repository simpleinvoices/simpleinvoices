{{-- /*
* View: help (Blade)
* 	 Help page template
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
			$appName = $config->app?->name ?? ($LANG['simple_invoices'] ?? '');
			$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
			$appForum = $config->app?->footer_link2_url ?? 'http://www.simpleinvoices.org/forum';
		@endphp
		<p>{{ sprintf($LANG['help_options_menu'] ?? '', e($appName)) }}</p>
		<p>{{ sprintf($LANG['help_other_queries'] ?? '', e($appName), '<a href="' . e($appWebsite) . '" target="_blank" rel="noopener">' . e($appWebsite) . '</a>', e($appName), '<a href="' . e($appForum) . '" target="_blank" rel="noopener">' . e($appForum) . '</a>') }}</p>
	</div>
</div>
