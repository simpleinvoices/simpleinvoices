/**
 * Help modal – replaces popover with Docsify iframe.
 * Maps help page IDs from cluetip rel URLs to Docsify hash routes.
 */
(function () {
    'use strict';

    // Map help_xxx page IDs to Docsify hash routes
    var routeMap = {
        // Direct help pages
        help_invoice_types:         '/help/invoice-types',
        help_custom_fields:         '/help/custom-fields',
        help_what_are_custom_fields:'/help/custom-fields',
        help_manage_custom_fields:  '/help/custom-fields',
        help_inv_pref_what_the:     '/help/invoice-preferences',
        help_tax_rate_sign:         '/help/tax-rates',
        help_user_role:             '/help/user-roles',
        help_required_field:        '/help/user-roles',
        help_backup_database:       '/help/database-backup',
        help_email_bcc:             '/help/email-settings',
        help_email_cc:              '/help/email-settings',
        help_email_from:            '/help/email-settings',
        help_email_to:              '/help/email-settings',
        help_payment_type:          '/help/payment-types',
        help_currency_code:         '/help/currency-settings',
        help_inv_pref_currency_sign:'/help/currency-settings',
        help_process_payment_paypal:'/guide/payments',
        help_product_attributes:    '/help/product-attributes',
        help_cron:                  '/help/recurring-invoices',
        help_reports_xsl:           '/guide/reports',
        help_install:               '/guide/installation',
        help_options_menu:          '/guide/settings',
        help_simple_invoices:       '/',
        help_si_help:               '/help/index',
        help_logging:               '/help/system-preferences',
        help_mysql4:                '/guide/installation',
        help_new_password:          '/help/user-roles',
        help_street2:               '/guide/customers',
        help_cost:                  '/guide/products',
        help_delete:                '/help/invoice-types',
        help_invoice_create:        '/guide/invoices',
        help_customer_contact:      '/guide/customers',
        help_other_queries:         '/help/index',
        help_blog:                  '/',
        help_community_forums:      '/',
        help_mailing_list:          '/',
        help_text:                  '/help/database-backup',
        help_age:                   '/guide/reports',
        help_wheres_the_edit_button:'/help/invoice-preferences',
        help_invoice_custom_fields: '/help/custom-fields',
        help_inv_pref:              '/help/invoice-preferences',
        help_tax_rate:              '/help/tax-rates',
        help_payment_gateway:       '/guide/payments',
        help_default_invoice:       '/help/invoice-preferences'
    };

    function getRoute(relUrl) {
        // Extract page parameter from rel URL
        var m = relUrl.match(/[?&]page=([^&]+)/);
        if (!m) return '/help/index';
        var pageId = decodeURIComponent(m[1]);
        // Check exact match
        if (routeMap[pageId]) return routeMap[pageId];
        // Try stripping help_ prefix: help_xxx -> /help/xxx
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
            '  <div class="modal-content">' +
            '    <div class="modal-header">' +
            '      <h5 class="modal-title"><i class="ti ti-book me-2"></i>Documentation</h5>' +
            '      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
            '    </div>' +
            '    <div class="modal-body p-0">' +
            '      <iframe id="si_help_iframe" src="about:blank" style="width:100%;height:72vh;border:none;"></iframe>' +
            '    </div>' +
            '    <div class="modal-footer justify-content-start">' +
            '      <a id="si_help_open_link" href="#" target="_blank" rel="noopener" class="btn btn-ghost-secondary btn-sm me-auto">Open in new tab <i class="ti ti-external-link ms-1"></i></a>' +
            '      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>' +
            '    </div>' +
            '  </div>' +
            '</div>';
        document.body.appendChild(modal);
        var iframe = modal.querySelector('#si_help_iframe');
        // Clean up on hide
        modal.addEventListener('hidden.bs.modal', function () {
            iframe.src = 'about:blank';
        });
    }

    function showHelpModal(relUrl, title) {
        ensureHelpModal();
        var modal = document.getElementById('si_help_modal');
        var iframe = modal.querySelector('#si_help_iframe');
        var openLink = modal.querySelector('#si_help_open_link');
        var route = getRoute(relUrl);
        var docsUrl = './docs/index.html#' + route;
        iframe.src = docsUrl;
        if (openLink) {
            openLink.href = docsUrl;
        }
        // Update title
        var titleEl = modal.querySelector('.modal-title');
        if (titleEl && title) {
            titleEl.innerHTML = '<i class="ti ti-book me-2"></i>' + title;
        }
        if (window.bootstrap && window.bootstrap.Modal) {
            var inst = window.bootstrap.Modal.getOrCreateInstance(modal);
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
            if (window.bootstrap && window.bootstrap.Popover) {
                var oldPop = window.bootstrap.Popover.getInstance(el);
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
                var modal = document.getElementById('si_help_modal');
                var iframe = modal.querySelector('#si_help_iframe');
                iframe.src = './docs/index.html#' + route;
                var openLink = modal.querySelector('#si_help_open_link');
                if (openLink) openLink.href = './docs/index.html#' + route;
                if (window.bootstrap && window.bootstrap.Modal) {
                    var inst = window.bootstrap.Modal.getOrCreateInstance(modal);
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
