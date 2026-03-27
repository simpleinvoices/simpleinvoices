{{-- /*
* Script: help.tpl
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
			$appName = $config->app?->name ?? 'Simple Invoices';
			$appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
			$appForum = $config->app?->footer_link2_url ?? 'http://www.simpleinvoices.org/forum';
		@endphp
		<p>For information regarding the setup, installation, and use of {{ $appName }} please refer to the Instructions sub-menu in the Option menu.</p>
		<p>For other queries please refer to the {{ $appName }} website <a href="{{ $appWebsite }}" target="_blank" rel="noopener">{{ $appWebsite }}</a> and the {{ $appName }} forum at <a href="{{ $appForum }}" target="_blank" rel="noopener">{{ $appForum }}</a></p>
	</div>
</div>
