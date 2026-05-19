/**
 * Help modal – opens Rspress documentation in an iframe.
 * Maps help page IDs from cluetip rel URLs to Rspress routes.
 */
(function () {
    'use strict';

    // Map help_xxx page IDs to Docsify hash routes
    var routeMap = {
        // Help: Invoice Types
        help_invoice_types:         '/help/invoice-types',
        help_delete:                '/help/invoice-types',

        // Help: Custom Fields
        help_custom_fields:         '/help/custom-fields',
        help_what_are_custom_fields:'/help/custom-fields',
        help_manage_custom_fields:  '/help/custom-fields',
        help_invoice_custom_fields: '/help/custom-fields',

        // Help: Invoice Preferences
        // Tab 1 – Details
        help_inv_pref_description:              '/help/invoice-preferences?id=tab-1-details',
        help_inv_pref_currency_sign:            '/help/currency-settings',
        help_inv_pref_status:                   '/help/invoice-preferences?id=tab-1-details',
        help_inv_pref_invoice_enabled:          '/help/invoice-preferences?id=tab-1-details',

        // Tab 2 – Numbering (dedicated pages)
        help_inv_pref_invoice_numbering_group:  '/help/invoice-numbering-groups',

        // Tab 3 – Localization
        help_inv_pref_language:                 '/help/invoice-preferences?id=tab-3-localization',
        help_inv_pref_locale:                   '/help/invoice-preferences?id=tab-3-localization',

        // Tab 4 – Wording
        help_inv_pref_invoice_heading:          '/help/invoice-preferences?id=tab-4-wording',
        help_inv_pref_invoice_wording:          '/help/invoice-preferences?id=tab-4-wording',
        help_inv_pref_invoice_detail_heading:   '/help/invoice-preferences?id=tab-4-wording',
        help_inv_pref_invoice_detail_line:      '/help/invoice-preferences?id=tab-4-wording',

        // Tab 5 – Payment
        help_inv_pref_invoice_payment_method:   '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line1_name:       '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line1_value:      '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line2_name:       '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line2_value:      '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line0_name:       '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line0_value:      '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line3_name:       '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line3_value:      '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line4_name:       '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line4_value:      '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line5_name:       '/help/invoice-preferences?id=tab-5-payment',
        help_inv_pref_payment_line5_value:      '/help/invoice-preferences?id=tab-5-payment',

        // General / top-level
        help_inv_pref_what_the:                 '/help/invoice-preferences',
        help_inv_pref:                          '/help/invoice-preferences',
        help_invoice_preference:                '/help/invoice-preferences',
        help_wheres_the_edit_button:            '/help/invoice-preferences',
        help_default_invoice:                   '/help/invoice-preferences',
        help_default_invoice_template_text:     '/help/invoice-preferences',
        help_insert_biller_text:                '/help/invoice-preferences',

        // Help: Tax Rates
        help_tax_rate_sign:         '/help/tax-rates',
        help_tax_rate:              '/help/tax-rates',

        // Help: User Roles
        help_user_role:             '/help/user-roles',
        help_required_field:        '/help/user-roles',
        help_new_password:          '/help/user-roles',

        // Help: Database Backup
        help_backup_database:       '/help/database-backup',
        help_text:                  '/help/database-backup',

        // Help: Email Settings
        help_email_bcc:             '/help/email-settings',
        help_email_cc:              '/help/email-settings',
        help_email_from:            '/help/email-settings',
        help_email_to:              '/help/email-settings',

        // Help: Payment Types
        help_payment_type:          '/help/payment-types',

        // Help: Currency Settings
        help_currency_code:         '/help/currency-settings',
        help_currency_sign:         '/help/currency-settings',
        help_inv_pref_currency_sign:'/help/currency-settings',

        // Help: Product Attributes
        help_product_attributes:    '/help/product-attributes',
        help_cost:                  '/help/product-attributes',

        // Help: Recurring Invoices
        help_cron:                  '/help/recurring-invoices',

        // Help: System Preferences
        help_logging:               '/help/system-preferences',
        help_delete:                '/help/delete',
        help_confirm_delete_line_item: '/help/delete',
        help_export_template:       '/help/export-template',
        help_default_invoice:       '/help/invoice-preferences',
        help_default_invoice_template_text: '/help/invoice-preferences',
        help_process_payment_paypal:'/guide/payments',
        help_process_payment_auto_amount: '/guide/payments',
        help_process_payment_inv_id:      '/guide/payments',
        help_payment_gateway:       '/guide/payments',

        // Guide: Reports
        help_reports_xsl:           '/guide/reports',
        help_age:                   '/guide/reports',

        // Guide: Installation
        help_install:               '/guide/installation',
        help_mysql4:                '/guide/installation',

        // Guide: Settings
        help_options_menu:          '/guide/settings',

        // Guide: Customers
        help_customer_contact:      '/guide/customers',
        help_street2:               '/guide/customers',

        // Guide: Products
        help_cost:                  '/guide/products',

        // Help: Payment Terms
        help_payment_terms:         '/help/payment-terms',
        help_due_date:              '/help/due-date',

        // Help: Tokens
        help_tokens:                '/help/tokens',

        // Help: Tax IDs
        help_tax_ids:               '/help/tax-ids',

        // Help: Invoice Denorm
        help_invoice_denorm:        '/help/invoice-denorm',

        // Help: Invoice ID
        help_invoice_id:            '/help/invoice-id',

        // Help: Invoice Currency
        help_invoice_currency:      '/help/currency-settings',

        // Guide: Invoices
        help_invoice_create:        '/guide/invoices',

        // Top-level pages
        help_simple_invoices:       '/',
        help_si_help:               '/help/index',
        help_other_queries:         '/help/index',
        help_blog:                  '/',
        help_community_forums:      '/',
        help_mailing_list:          '/'
    };

    function getRoute(relUrl) {
        var m = relUrl.match(/[?&]page=([^&]+)/);
        if (!m) return '/help/index';
        var pageId = decodeURIComponent(m[1]);
        if (routeMap[pageId]) return routeMap[pageId];
        // Any help_inv_pref_* not in routeMap → invoice-preferences
        if (pageId.indexOf('help_inv_pref_') === 0) return '/help/invoice-preferences';
        // help_xxx → /help/xxx (auto-slug fallback)
        if (pageId.indexOf('help_') === 0) {
            var slug = pageId.slice(5).replace(/_/g, '-');
            return '/help/' + slug;
        }
        return '/help/index';
    }

    function ensureHelpModal() {
        if (document.getElementById('si_help_modal')) return;
        var modal = document.createElement('div');
        modal.id = 'si_help_modal';
        modal.className = 'modal fade';
        modal.setAttribute('tabindex', '-1');
        modal.setAttribute('aria-hidden', 'true');
        modal.innerHTML =
            '<div class="modal-dialog modal-xl modal-dialog-centered">' +
            '  <div class="modal-content shadow-lg">' +
            '    <div class="modal-header bg-light border-bottom">' +
            '      <h5 class="modal-title fw-bold">' +
            '        <i class="ti ti-book-2 me-2 text-primary"></i><span id="si_help_modal_title">Documentation</span>' +
            '      </h5>' +
            '      <a id="si_help_open_link" href="./docs/index.html" target="_blank" rel="noopener"' +
            '         class="btn btn-sm btn-ghost-secondary d-none d-sm-inline-flex" title="Open in new tab">' +
            '        <i class="ti ti-external-link me-1"></i>Open in Tab' +
            '      </a>' +
            '      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
            '    </div>' +
            '    <div class="modal-body p-0 position-relative">' +
            '      <div id="si_help_loading" class="position-absolute top-50 start-50 translate-middle text-center" style="z-index:1;">' +
            '        <div class="spinner-border text-primary mb-2" role="status" style="width:3rem;height:3rem;"></div>' +
            '        <div class="text-muted small">Loading documentation…</div>' +
            '      </div>' +
            '      <iframe id="si_help_iframe" src="about:blank"' +
            '        style="width:100%;height:80vh;border:1px solid var(--tblr-border-color, #e6e7e9);border-radius:0;"></iframe>' +
            '    </div>' +
            '  </div>' +
            '</div>';
        document.body.appendChild(modal);

        var iframe = modal.querySelector('#si_help_iframe');
        var loading = modal.querySelector('#si_help_loading');

        // Hide loading spinner once iframe has loaded
        iframe.addEventListener('load', function () {
            if (loading) loading.style.display = 'none';
        });

        // Show spinner when navigating
        iframe.addEventListener('beforeunload', function () {
            if (loading) loading.style.display = '';
        });

        // Reset on modal hide
        modal.addEventListener('hidden.bs.modal', function () {
            iframe.src = 'about:blank';
            if (loading) loading.style.display = '';
        });
    }

    function showHelpModal(relUrl, title) {
        ensureHelpModal();
        var modal    = document.getElementById('si_help_modal');
        var iframe   = modal.querySelector('#si_help_iframe');
        var loading  = modal.querySelector('#si_help_loading');
        var route    = getRoute(relUrl);
        // Rspress static site: route is /help/invoice-types → ./docs/help/invoice-types.html
        var qidx     = route.indexOf('?');
        var cleanRoute = qidx >= 0 ? route.substring(0, qidx) : route;
        var query    = qidx >= 0 ? route.substring(qidx) : '';
                var embedQs  = query ? query + '&embed=1' : '?embed=1';
                var docsUrl  = './docs' + cleanRoute + '.html' + embedQs;

        // Show spinner
        if (loading) loading.style.display = '';

        iframe.src = docsUrl;

        // "Open in new tab" link in header → docs homepage
        var rootDocsUrl = './docs/index.html?embed=1';
        var openLink  = modal.querySelector('#si_help_open_link');
        if (openLink)  openLink.href  = rootDocsUrl;

        var titleEl = modal.querySelector('.modal-title');
        if (titleEl && title) {
            titleEl.innerHTML = '<i class="ti ti-book-2 me-2 text-primary"></i>' + title;
        }

        if (window.tabler && window.tabler.Modal) {
            var inst = window.tabler.Modal.getOrCreateInstance(modal);
            inst.show();
        }
    }

    function initHelpLinks() {
        var links = document.querySelectorAll('a.cluetip[rel]');
        links.forEach(function (el) {
            var url = el.getAttribute('rel');
            var title = el.getAttribute('title') || '';
            if (!url) return;
            // Remove old popover if any
            if (window.tabler && window.tabler.Popover) {
                var oldPop = window.tabler.Popover.getInstance(el);
                if (oldPop) oldPop.dispose();
            }
            // Add click handler
            el.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                showHelpModal(url, title);
            });
        });
    }

    // Also add handler for any "Help" buttons that open docs
    function handleHelpButtons() {
        document.querySelectorAll('.si-open-docs').forEach(function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault();
                var route = el.getAttribute('data-docs-route') || '/';
                ensureHelpModal();
                var modal    = document.getElementById('si_help_modal');
                var iframe   = modal.querySelector('#si_help_iframe');
                var loading  = modal.querySelector('#si_help_loading');
                var qidx     = route.indexOf('?');
                var cleanRoute = qidx >= 0 ? route.substring(0, qidx) : route;
                var query    = qidx >= 0 ? route.substring(qidx) : '';
        var embedQs  = query ? query + '&embed=1' : '?embed=1';
        var docsUrl  = './docs' + cleanRoute + '.html' + embedQs;
                if (loading) loading.style.display = '';
                iframe.src = docsUrl;
                var rootDocsUrl = './docs/index.html?embed=1';
                var openLink  = modal.querySelector('#si_help_open_link');
                if (openLink)  openLink.href  = rootDocsUrl;
                if (window.tabler && window.tabler.Modal) {
                    var inst = window.tabler.Modal.getOrCreateInstance(modal);
                    inst.show();
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initHelpLinks();
            handleHelpButtons();
        });
    } else {
        initHelpLinks();
        handleHelpButtons();
    }
})();
