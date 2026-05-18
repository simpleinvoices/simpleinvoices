        </div>{{-- /container-xl --}}
    </div>{{-- /page-body --}}
    @php
        $appName = $config->app?->name ?? ($LANG['simple_invoices'] ?? '');
        $appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
        $footerText = $config->app?->footer_text ?? ($LANG['thank_you_for_using'] ?? '');
        $footerLinks = [
            ['label' => $config->app?->footer_link1_label ?? $appName, 'url' => $config->app?->footer_link1_url ?? $appWebsite],
            ['label' => $config->app?->footer_link2_label ?? ($LANG['forum'] ?? ''), 'url' => $config->app?->footer_link2_url ?? 'http://www.simpleinvoices.org/+'],
            ['label' => $config->app?->footer_link3_label ?? ($LANG['blog'] ?? ''), 'url' => $config->app?->footer_link3_url ?? 'http://www.simpleinvoices.org/blog'],
            ['label' => $config->app?->footer_link4_label ?? ($LANG['support'] ?? ''), 'url' => $config->app?->footer_link4_url ?? 'http://www.simpleinvoices.org/forum'],
        ];
    @endphp
    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ms-lg-auto">
                    <ul class="list-inline list-inline-dots mb-0">
                        @foreach($footerLinks as $footerLink)
                            @if(!empty($footerLink['label']) && !empty($footerLink['url']))
                                <li class="list-inline-item"><a href="{{ $footerLink['url'] }}" class="link-secondary" target="_blank" rel="noopener">{{ $footerLink['label'] }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">{{ $footerText }} <a href="{{ $appWebsite }}" class="link-secondary" target="_blank" rel="noopener">{{ $appName }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>{{-- /page-wrapper --}}
</div>{{-- /page --}}
<script src="{{ $siUrl }}/templates/default/vendor/tabler-core/tabler.min.js" defer></script>
<script src="{{ $siUrl }}/include/js/si-help-modal.js" defer></script>
@if(($module ?? '') === 'reports')
<script src="{{ $siUrl }}/include/js/si-report-sort.js" defer></script>
@endif
{{-- Global preview modal (invoice / payment print preview) --}}
<div class="modal fade" id="si_preview_modal" tabindex="-1" aria-labelledby="si_preview_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="si_preview_modal_label">{{ $LANG['preview'] ?? '' }}</h5>
                {{-- Icon-only action buttons shown only on mobile (footer is off-screen on small viewports) --}}
                <div class="d-flex gap-1 ms-auto me-2">
                    <a href="#" id="si_preview_newtab_icon" target="_blank" rel="noopener"
                       class="btn btn-sm btn-ghost-secondary" title="{{ $LANG['open_in_new_tab'] ?? '' }}">
                        <i class="ti ti-external-link"></i>
                    </a>
                    <a href="#" id="si_preview_pdf_icon" target="_blank" rel="noopener"
                       class="btn btn-sm btn-ghost-danger d-none" title="{{ $LANG['pdf'] ?? '' }}">
                        <i class="ti ti-file-type-pdf"></i>
                    </a>
                    <button type="button" class="btn btn-sm btn-ghost-primary"
                            title="{{ $LANG['print_action'] ?? '' }}"
                            onclick="var f=document.getElementById('si_preview_iframe');if(f&&f.contentWindow)f.contentWindow.print();">
                        <i class="ti ti-printer"></i>
                    </button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $LANG['close'] ?? '' }}"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="si_preview_iframe" src="about:blank" style="width:100%;height:72vh;border:0;display:block;"></iframe>
            </div>
            <div class="modal-footer justify-content-start">
                <button type="button" class="btn" data-bs-dismiss="modal">{{ $LANG['close'] ?? '' }}</button>
            </div>
        </div>
    </div>
</div>
<script>
function siPreviewModal(url, title, pdfUrl) {
    var modal    = document.getElementById('si_preview_modal');
    var iframe   = document.getElementById('si_preview_iframe');
    var newTab   = document.getElementById('si_preview_newtab_link');
    var newTabIcon = document.getElementById('si_preview_newtab_icon');
    var pdfLink  = document.getElementById('si_preview_pdf_link');
    var pdfIcon  = document.getElementById('si_preview_pdf_icon');
    var titleEl  = document.getElementById('si_preview_modal_label');
    if (titleEl) titleEl.textContent = title || @json($LANG['preview'] ?? '');
    if (newTab)     newTab.href = url;
    if (newTabIcon) newTabIcon.href = url;
    if (iframe)     iframe.src  = url;
    if (pdfLink) {
        if (pdfUrl) {
            pdfLink.href = pdfUrl;
            pdfLink.classList.remove('d-none');
        } else {
            pdfLink.classList.add('d-none');
        }
    }
    if (pdfIcon) {
        if (pdfUrl) {
            pdfIcon.href = pdfUrl;
            pdfIcon.classList.remove('d-none');
        } else {
            pdfIcon.classList.add('d-none');
        }
    }
    var bsModal = window.tabler && window.tabler.Modal
        ? window.tabler.Modal.getOrCreateInstance(modal) : null;
    if (bsModal) bsModal.show();
    modal.addEventListener('hidden.bs.modal', function handler() {
        if (iframe) iframe.src = 'about:blank';
        modal.removeEventListener('hidden.bs.modal', handler);
    });
}
// Event delegation: any <a class="si-preview-link"> opens in the preview modal
document.addEventListener('click', function (e) {
    var link = e.target.closest('.si-preview-link');
    if (!link) return;
    e.preventDefault();
    siPreviewModal(link.href, link.dataset.previewTitle || '', link.dataset.previewPdf || '');
});
</script>
@stack('hook_body_end')
</body>
</html>
