{{--
* Script: manage.blade.php - Manage invoices template
* License: GPL v2 or above
* Website: http://www.simpleinvoices.org
--}}

@if(($number_of_invoices['count'] ?? 0) == 0)
	<div class="card">
		<div class="alert alert-info mb-0">
			{{ $LANG['no_invoices'] ?? '' }}
		</div>
	</div>
@else
	<div class="card">
	<div class="card-table">
		<div class="card-header d-flex flex-wrap align-items-center gap-2">
			<div class="segmented-control">
				<label class="segmented-control-item" onclick="window.location='index.php?module=invoices&amp;view=manage'">
					<input type="radio" class="segmented-control-input" name="invoice_filter" @if((get('having')) == '') checked @endif>
					<span class="segmented-control-label"><i class="ti ti-list me-1"></i>{{ $LANG['all'] ?? '' }}</span>
				</label>
				<label class="segmented-control-item" onclick="window.location='index.php?module=invoices&amp;view=manage&amp;having=money_owed'">
					<input type="radio" class="segmented-control-input" name="invoice_filter" @if((get('having')) == 'money_owed') checked @endif>
					<span class="segmented-control-label"><i class="ti ti-clock-dollar me-1"></i>{{ $LANG['due'] ?? '' }}</span>
				</label>
				<label class="segmented-control-item" onclick="window.location='index.php?module=invoices&amp;view=manage&amp;having=paid'">
					<input type="radio" class="segmented-control-input" name="invoice_filter" @if((get('having')) == 'paid') checked @endif>
					<span class="segmented-control-label"><i class="ti ti-circle-check me-1"></i>{{ $LANG['paid'] ?? '' }}</span>
				</label>
				<label class="segmented-control-item" onclick="window.location='index.php?module=invoices&amp;view=manage&amp;having=draft'">
					<input type="radio" class="segmented-control-input" name="invoice_filter" @if((get('having')) == 'draft') checked @endif>
					<span class="segmented-control-label"><i class="ti ti-file-pencil me-1"></i>{{ $LANG['draft'] ?? '' }}</span>
				</label>
				<label class="segmented-control-item" onclick="window.location='index.php?module=invoices&amp;view=manage&amp;having=real'">
					<input type="radio" class="segmented-control-input" name="invoice_filter" @if((get('having')) == 'real') checked @endif>
					<span class="segmented-control-label"><i class="ti ti-file-invoice me-1"></i>{{ $LANG['real'] ?? '' }}</span>
				</label>
			</div>
			<div id="manageGridToolbar" class="d-flex flex-wrap gap-2 align-items-center ms-auto"></div>
		</div>
		<div id="manageGrid"></div>
	</div>
	</div>
	@include('templates.default.invoices.manage_js')

	<div class="modal fade" id="export_dialog" tabindex="-1" aria-labelledby="export_dialog_title" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="export_dialog_title">{{ $LANG['export'] ?? '' }}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $LANG['close'] ?? '' }}"></button>
				</div>
				<div class="modal-body d-flex gap-2 flex-wrap">
					<a href="#" title='{{ $LANG['export_tooltip'] ?? '' }} {{ $LANG['export_pdf_tooltip'] ?? '' }}' class='btn btn-outline-danger export_pdf export_window'>
						<i class="ti ti-file-certificate me-1"></i>{{ $LANG['export_pdf'] ?? '' }}
					</a>
					<a href="#" title='{{ $LANG['export_tooltip'] ?? '' }} {{ $LANG['export_xls_tooltip'] ?? '' }} .{{ $defaults['spreadsheet'] ?? 'xlsx' }}' class='btn btn-outline-success export_xls export_window'>
						<i class="ti ti-file-spreadsheet me-1"></i>{{ $LANG['export_xls'] ?? '' }}
					</a>
					<a href="#" title='{{ $LANG['export_tooltip'] ?? '' }} {{ $LANG['export_doc_tooltip'] ?? '' }} .{{ $defaults['wordprocessor'] ?? 'docx' }}' class='btn btn-outline-primary export_doc export_window'>
						<i class="ti ti-file-text me-1"></i>{{ $LANG['export_doc'] ?? '' }}
					</a>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $LANG['cancel'] ?? '' }}</button>
				</div>
			</div>
		</div>
	</div>
@endif
