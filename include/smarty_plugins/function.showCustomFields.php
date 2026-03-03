<?php
/**
 * Smarty plugin: showCustomFields
 * Blade-compatible: delegates to showCustomFieldsForBlade() and echoes result.
 * Use in Blade: @showCustomFields(categorieId, itemId) directive.
 */

if (!function_exists('smarty_function_showCustomFields')) {
function smarty_function_showCustomFields($params, $smarty = null) {
    $categorieId = $params['categorieId'] ?? '';
    $itemId = $params['itemId'] ?? '';
    if (!function_exists('showCustomFieldsForBlade')) {
        require_once __DIR__ . '/../manageCustomFields.php';
    }
    echo showCustomFieldsForBlade($categorieId, $itemId);
}
}
?>
