{{-- Install wizard – Tabler layout, single step (database + essential data) --}}
<div class="page-wrapper">
	<div class="page-body">
		<div class="container-xl">
			<div class="text-center mb-4">
				<a href="index.php?module=install&view=index" class="navbar-brand navbar-brand-autodark">
					@if(!empty($config->app?->logo))
						<img src="{{ $config->app->logo }}" alt="{{ $config->app?->name ?? 'Simple Invoices' }}" class="mb-2" style="max-width: 280px;" />
					@else
						<img src="images/common/simple_invoices_logo.jpg" alt="{{ $config->app?->name ?? 'Simple Invoices' }}" class="mb-2" style="max-width: 280px;" />
					@endif
				</a>
			</div>
