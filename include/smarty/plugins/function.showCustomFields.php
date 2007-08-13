<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {config_load} function plugin
 *
 * </pre>
 * @param Smarty
 */
function smarty_function_showCustomFields($params, &$smarty)
{
	echo "<input type='hidden' name='categorie' value='$params[categorieId]'>";
	
	$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE categorieID = $params[categorieId];";
	$query = mysqlQuery($sql);
	
	while($field = mysql_fetch_array($query)) {
		$plugin = getPluginById($field['pluginId']);
		error_log($field['id']."  ".$params['itemId']."sss");
		$plugin->printInputField($field['id'],$params['itemId']);
	}

}

/* vim: set expandtab: */

?>
