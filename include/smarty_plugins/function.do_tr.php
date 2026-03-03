<?php
/**
 * Smarty plugin: do_tr
 * Blade-compatible: delegates to blade_do_tr() and echoes result.
 * Use in Blade: {do_tr ...} (precompiled) or @do_tr(...) directive.
 */

if (!function_exists('smarty_function_do_tr')) {
function smarty_function_do_tr($params, $smarty = null) {
    if (!function_exists('blade_do_tr')) {
        require_once __DIR__ . '/../blade_helpers.php';
    }
    echo blade_do_tr($params);
}
}
