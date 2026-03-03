<?php
/**
 * Smarty plugin: merge_address
 * Blade-compatible: delegates to blade_merge_address() and echoes result.
 * Use in Blade: {merge_address ...} (precompiled) or @merge_address(...) directive.
 */

if (!function_exists('smarty_function_merge_address')) {
function smarty_function_merge_address($params, $smarty = null) {
    if (!function_exists('blade_merge_address')) {
        require_once __DIR__ . '/../blade_helpers.php';
    }
    echo blade_merge_address($params);
}
}
