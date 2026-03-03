<?php
/**
 * Smarty plugin: print_if_not_null
 * Blade-compatible: delegates to blade_print_if_not_null() and echoes result.
 * Use in Blade: {print_if_not_null ...} (precompiled) or @print_if_not_null(...) directive.
 */

if (!function_exists('smarty_function_print_if_not_null')) {
function smarty_function_print_if_not_null($params, $smarty = null) {
    if (!function_exists('blade_print_if_not_null')) {
        require_once __DIR__ . '/../blade_helpers.php';
    }
    echo blade_print_if_not_null($params);
}
}
?>
