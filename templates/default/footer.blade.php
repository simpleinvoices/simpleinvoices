        </div>{{-- /container-xl --}}
    </div>{{-- /page-body --}}
    @php
        $appName = $config->app?->name ?? $LANG['simple_invoices'] ?? 'Simple Invoices';
        $appWebsite = $config->app?->website ?? 'http://www.simpleinvoices.org';
        $footerText = $config->app?->footer_text ?? ($LANG['thank_you_inv'] ?? 'Thank you for using');
        $footerLinks = [
            ['label' => $config->app?->footer_link1_label ?? $appName, 'url' => $config->app?->footer_link1_url ?? $appWebsite],
            ['label' => $config->app?->footer_link2_label ?? ($LANG['forum'] ?? 'Forum'), 'url' => $config->app?->footer_link2_url ?? 'http://www.simpleinvoices.org/+'],
            ['label' => $config->app?->footer_link3_label ?? ($LANG['blog'] ?? 'Blog'), 'url' => $config->app?->footer_link3_url ?? 'http://www.simpleinvoices.org/blog'],
            ['label' => $config->app?->footer_link4_label ?? 'Support', 'url' => $config->app?->footer_link4_url ?? 'http://www.simpleinvoices.org/forum'],
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
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js" defer></script>
<script src="./include/jquery/si-help-popover.js" defer></script>
@stack('hook_body_end')
</body>
</html>
