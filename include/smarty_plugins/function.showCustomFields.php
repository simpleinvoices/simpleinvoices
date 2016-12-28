<?php
/**
 * Smarty function plugin
 * Load custom fields for this domain.
 */
function smarty_function_showCustomFields($params, &$smarty) {
    global $pdoDb;

    echo "<input type='hidden' name='categorie' value='$params[categorieId]'>";

    $pdoDb->addSimpleWhere("categorieID", $params['categorieId']);
    $rows = $pdoDb->request("SELECT", "customFields");
    foreach ($rows as $row) {
        $plugin = getPluginById($row['pluginId']);
        $plugin->printInputField($row['id'],$params['itemId']);
    }
}
