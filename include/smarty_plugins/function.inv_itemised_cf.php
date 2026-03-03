<?php
/**
 * Smarty plugin: inv_itemised_cf
 * Blade-compatible: delegates to blade_inv_itemised_cf() and echoes result.
 * Use in Blade: {inv_itemised_cf ...} (precompiled) or @inv_itemised_cf(...) directive.
 */

if (!function_exists('smarty_function_inv_itemised_cf')) {
function smarty_function_inv_itemised_cf($params, $smarty = null) {
    if (!function_exists('blade_inv_itemised_cf')) {
        require_once __DIR__ . '/../blade_helpers.php';
    }
    echo blade_inv_itemised_cf($params);
}
}
