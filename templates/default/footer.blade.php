        </div>{{-- /container-xl --}}
    </div>{{-- /page-body --}}
    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
                <div class="col-lg-auto ms-lg-auto">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item"><a href="http://www.simpleinvoices.org" class="link-secondary" target="_blank">{{ $LANG['simple_invoices'] ?? 'Simple Invoices' }}</a></li>
                        <li class="list-inline-item"><a href="http://www.simpleinvoices.org/+" class="link-secondary" target="_blank">{{ $LANG['forum'] ?? 'Forum' }}</a></li>
                        <li class="list-inline-item"><a href="http://www.simpleinvoices.org/blog" class="link-secondary" target="_blank">{{ $LANG['blog'] ?? 'Blog' }}</a></li>
                    </ul>
                </div>
                <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                    <ul class="list-inline list-inline-dots mb-0">
                        <li class="list-inline-item">{{ $LANG['thank_you_inv'] ?? 'Thank you for using' }} <a href="http://www.simpleinvoices.org" class="link-secondary">Simple Invoices</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>{{-- /page-wrapper --}}
</div>{{-- /page --}}
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js" defer></script>
@stack('hook_body_end')
</body>
</html>
