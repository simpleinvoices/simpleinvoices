@include('templates.default.hooks')
<!DOCTYPE html>
<html lang="en" data-bs-theme-base="slate">
<head>
    <script>
        (function () {
            var t = localStorage.getItem('siTheme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        }());
    </script>
    @php
        $tmp_lang_module = $LANG['title_module_'.$module] ?? $LANG[$module] ?? $module;
        $tmp_lang_view = $LANG['title_view_'.$view] ?? $LANG[$view] ?? $view;
    @endphp
    @stack('hook_head_start')
    <title>{{ $tmp_lang_module }} : {{ $tmp_lang_view }} - {{ $config->app?->name ?? ($LANG['simple_invoices'] ?? '') }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="robots" content="noindex, nofollow" />
    <link rel="shortcut icon" href="{{ $siUrl }}/images/common/favicon.ico" />
    <link rel="stylesheet" href="{{ $siUrl }}/templates/default/vendor/inter/inter.css" />
    <link rel="stylesheet" href="{{ $siUrl }}/templates/default/vendor/tabler-core/tabler.min.css" />
    <link rel="stylesheet" href="{{ $siUrl }}/templates/default/vendor/tabler-icons/tabler-icons.min.css" />
    <link rel="stylesheet" href="{{ $siUrl }}/templates/default/vendor/tom-select/tom-select.bootstrap5.min.css" />
    <link rel="stylesheet" href="{{ $siUrl }}/templates/default/css/si-tabler.css" />
    <link rel="stylesheet" href="{{ $siUrl }}/templates/default/css/print.css" media="print" />
    <script src="{{ $siUrl }}/include/js/si-init.js"></script>
    @php
        $si_grid_lang = [
            'search' => $LANG['grid_search'] ?? '',
            'search_placeholder' => $LANG['grid_search_placeholder'] ?? $LANG['grid_search'] ?? '',
            'reload' => $LANG['grid_reload'] ?? '',
            'sort_by' => $LANG['grid_sort_by'] ?? '',
            'records' => $LANG['grid_records'] ?? '',
            'invalid_xml' => $LANG['grid_invalid_xml'] ?? '',
            'connection_error' => $LANG['grid_connection_error'] ?? '',
            'pagestat_fallback' => $LANG['displaying_items'] ?? '',
        ];
    @endphp
    <script>
        window.SI_GRID_LANG = @json($si_grid_lang);
    </script>
    <script src="{{ $siUrl }}/include/js/si-tabler-grid.js"></script>
    <script src="{{ $siUrl }}/include/js/si-bootstrap.js"></script>
    <script src="{{ $siUrl }}/templates/default/vendor/hugerte/hugerte.min.js"></script>
    <script src="{{ $siUrl }}/templates/default/vendor/litepicker/litepicker.js"></script>
    <script src="{{ $siUrl }}/include/js/si-litepicker.js"></script>
    <script src="{{ $siUrl }}/include/js/si-autocomplete.js"></script>
    <script src="{{ $siUrl }}/templates/default/vendor/tom-select/tom-select.complete.min.js"></script>
    @include('include.js.si-functions')
    @include('include.js.si-conf')
    <script src="{{ $siUrl }}/include/js/si-validate.js"></script>
    <script src="{{ $siUrl }}/include/js/si-product-select.js"></script>
    <script src="{{ $siUrl }}/include/js/si-select.js"></script>
    <script>
        function siToggleTheme(e) {
            if (e) e.preventDefault();
            var next = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', next);
            localStorage.setItem('siTheme', next);
            document.documentElement.dispatchEvent(new CustomEvent('si-theme-changed'));
        }
    </script>
    @stack('hook_head_end')
</head>
<body data-bs-no-jquery="true">
@stack('hook_body_start')
<div class="page">
