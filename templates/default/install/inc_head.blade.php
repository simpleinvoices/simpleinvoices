{{-- Install wizard – Tabler, centred layout (matches auth pages) --}}
<div class="page-wrapper bg-body-tertiary">
	<div class="page-body d-flex flex-column align-items-center py-4 py-lg-5 min-vh-100">
		<div class="container-xl px-3 px-lg-4">
			<div class="text-center mb-4">
				<a href="index.php?module=install&view=index" class="navbar-brand navbar-brand-autodark d-inline-flex flex-column align-items-center text-decoration-none">
					@if(!empty($config->app?->logo))
						<img src="{{ $config->app->logo }}" alt="{{ $config->app?->name ?? 'Simple Invoices' }}" class="mb-2" style="max-width: 280px; max-height: 56px; object-fit: contain;" />
					@else
						<img src="images/common/simple_invoices_logo.jpg" alt="{{ $config->app?->name ?? 'Simple Invoices' }}" class="mb-2" style="max-width: 280px;" />
					@endif
					<span class="text-secondary small mt-1">{{ $LANG['installation'] ?? 'Installation' }}</span>
				</a>
			</div>
