{{--
* Script: manage.blade.php - Manage invoices template
* License: GPL v2 or above
* Website: http://www.simpleinvoices.org
--}}

@if(($number_of_invoices['count'] ?? 0) == 0)
	<div class="card">
		<div class="card-body">
			<div class="alert alert-info mb-0">
				{{ $LANG['no_invoices'] ?? 'No invoices' }}
			</div>
		</div>
	</div>
@else
	<div class="card">
		<div class="card-header d-flex flex-wrap align-items-center gap-2">
			<ul class="nav nav-pills card-header-pills mb-0" role="tablist">
				<li class="nav-item">
					<a href="index.php?module=invoices&amp;view=manage" class="nav-link @if((get('having')) == '') active @endif">{{ $LANG['all'] ?? 'All' }}</a>
				</li>
				<li class="nav-item">
					<a href="index.php?module=invoices&amp;view=manage&amp;having=money_owed" class="nav-link @if((get('having')) == 'money_owed') active @endif">{{ $LANG['due'] ?? 'Due' }}</a>
				</li>
				<li class="nav-item">
					<a href="index.php?module=invoices&amp;view=manage&amp;having=paid" class="nav-link @if((get('having')) == 'paid') active @endif">{{ $LANG['paid'] ?? 'Paid' }}</a>
				</li>
				<li class="nav-item">
					<a href="index.php?module=invoices&amp;view=manage&amp;having=draft" class="nav-link @if((get('having')) == 'draft') active @endif">{{ $LANG['draft'] ?? 'Draft' }}</a>
				</li>
				<li class="nav-item">
					<a href="index.php?module=invoices&amp;view=manage&amp;having=real" class="nav-link @if((get('having')) == 'real') active @endif">{{ $LANG['real'] ?? 'Real' }}</a>
				</li>
			</ul>
			<div id="manageGridToolbar" class="d-flex flex-wrap gap-2 align-items-center ms-auto"></div>
		</div>
		<div id="manageGrid"></div>
	</div>
	@include('templates.default.invoices.manage_js')

	<div class="modal fade" id="export_dialog" tabindex="-1" aria-labelledby="export_dialog_title" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="export_dialog_title">{{ $LANG['export'] ?? 'Export' }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body d-flex gap-2 flex-wrap">
					<a href="#" title='{{ $LANG['export_tooltip'] ?? '' }} {{ $LANG['export_pdf_tooltip'] ?? '' }}' class='btn btn-outline-danger export_pdf export_window'>
						<i class="ti ti-file-certificate me-1"></i>{{ $LANG['export_pdf'] ?? 'PDF' }}
					</a>
					<a href="#" title='{{ $LANG['export_tooltip'] ?? '' }} {{ $LANG['export_xls_tooltip'] ?? '' }} .{{ $config->export->spreadsheet ?? 'xls' }}' class='btn btn-outline-success export_xls export_window'>
						<i class="ti ti-file-spreadsheet me-1"></i>{{ $LANG['export_xls'] ?? 'XLS' }}
					</a>
					<a href="#" title='{{ $LANG['export_tooltip'] ?? '' }} {{ $LANG['export_doc_tooltip'] ?? '' }} .{{ $config->export->wordprocessor ?? 'doc' }}' class='btn btn-outline-primary export_doc export_window'>
						<i class="ti ti-file-text me-1"></i>{{ $LANG['export_doc'] ?? 'DOC' }}
					</a>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $LANG['cancel'] ?? 'Cancel' }}</button>
				</div>
			</div>
		</div>
	</div>
@endif
