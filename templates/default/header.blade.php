@include('templates.default.hooks')
<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $tmp_lang_module = $LANG['title_module_'.$module] ?? $LANG[$module] ?? $module;
        $tmp_lang_view = $LANG['title_view_'.$view] ?? $LANG[$view] ?? $view;
    @endphp
    @stack('hook_head_start')
    <title>{{ $tmp_lang_module }} : {{ $tmp_lang_view }} - {{ $LANG['simple_invoices'] ?? 'Simple Invoices' }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="robots" content="noindex, nofollow" />
    <link rel="shortcut icon" href="./images/common/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://preview.tabler.io/dist/css/tabler.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="./templates/default/css/si-tabler.css" />
    <link rel="stylesheet" href="./templates/default/css/print.css" media="print" />
    <script src="./include/jquery/jquery.init.js"></script>
    <script src="./include/jquery/si-tabler-grid.js"></script>
    <script src="./include/jquery/si-bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hugerte@1.0.10/hugerte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker@2.0.12/dist/litepicker.js"></script>
    <script src="./include/jquery/si-litepicker.js"></script>
    <script src="./include/jquery/si-autocomplete.js"></script>
    @include('include.jquery.jquery_functions_js')
    @include('include.jquery.jquery_conf_js')
    <script src="./include/jquery/si-validate.js"></script>
    @stack('hook_head_end')
</head>
<body data-bs-no-jquery="true">
@stack('hook_body_start')
<div class="page">
