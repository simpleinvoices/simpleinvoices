<?php
include("./include/CustomField.php");


function saveCustomField() {
	$sql = "INSERT INTO ".TB_PREFIX."customFields  ( `pluginId` ,  `categorieId` ,  `name` ,  `description` ) 
		VALUES ('$_POST[plugin]','$_POST[categorie]','$_POST[name]','$_POST[description]');";
	mysqlQuery($sql);
	echo "SAVED<br />";
}

function saveCustomFieldValues($categorieId,$itemId) {
	
	$plugins = getPluginsByCategorie($categorieId);
	
	foreach($plugins as $plugin) {
		$id = $plugin->fieldId;
		//error_log("IIDDD".$itemId);
		$plugin->saveInput($_POST["cf$id"],$itemId);
	}
}

function updateCustomFieldValues($categorieId,$itemId) {
	$plugins = getPluginsByCategorie($categorieId);
	
	foreach($plugins as $plugin) {
		$id = $plugin->fieldId;
		//error_log("IIDDD".$itemId);
		$plugin->updateInput($_POST["cf$id"],$itemId);
	}
}

function getPluginsByCategorie($categorieId) {
	$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE categorieID = $categorieId;";
	$query = mysqlQuery($sql);
	
	for($i=0;$field = mysql_fetch_array($query);$i++) {	
		$plugins[$i] = getPluginById($field['pluginId']);
		$plugins[$i]->setFieldId($field['id']);
	}
	
	return $plugins;
}

function showCustomFields($categorieId) {
	$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE categorieID = $categorieId;";
	$query = mysqlQuery($sql);
	
	while($field = mysql_fetch_array($query)) {
		
		$plugin = getPluginById($field['pluginId']);		
		$plugin->printInputField($field['id']);
	}
}

function getPluginArray() {
	
	$plugins = getPlugins();

	foreach($plugins as $plugin) {
		include_once($plugin);
	}

	$classes = null;
	$i = 0;
	
	foreach($plugins as $plugin) {
		$path = pathinfo($plugin);
		$path = $path['filename'];
		//$classes[$i] = new $path();
		$plugin = new $path();
		
		$classes[$plugin->id] = $plugin;
		
		//$i++;
	}
	
	return $classes;
}


function printCustomFieldsList() {
	$sql = "SELECT * FROM ".TB_PREFIX."customFields;";
	$query = mysql_query($sql);
	
	echo <<<EOD
	<table>
		<tr>
			<th>id</th>
			<th>Name</th>
			<th>Description</th>
			<th>categorie</th>
			<th>plugin</th>
			<th>active</th>
		</tr>
EOD;

	while($customField = mysql_fetch_array($query)) {
		echo <<<EOD
		<tr>
			<td>$customField[id]</td>
			<td>$customField[name]</td>
			<td>$customField[description]</td>
			<td>$customField[categorieId]</td>
			<td>$customField[pluginId]</td>
			<td>$customField[active]</td>
		</tr>
EOD;
	}
	
	echo "</table>";
}

function listCustomFields() {
}

function addCustomField() {
	echo <<<EOD
		<form>
			<input type="text">
EOD;
/*- select PlugIn
- select Categorie
- set Name
- add Description*/

}

function readPlugins() {
}

function getCategories() {
	$sql = "SELECT * FROM si_customFieldCategories";
	$query = mysql_query($sql);
	
	for($i=0;$cat = mysql_fetch_array($query);$i++) {
		$categories[$i] = $cat;
	}
	return $categories;
}

function printCategories() {
	$cats = getCategories();
	
	$out = '<select name="categorie">';
	
	foreach($cats as $cat) {
		$out .= "<option value='$cat[id]' >$cat[name]</option>";
	}
	
	$out .= "</select>";
	echo $out."<br />";
}

function getPlugins() {
	
	$files = scandir("./modules/customFields/plugins/");
	$plugins = null;
	
	for($i=0;$i<count($files);$i++) {
		$file = $files[$i];
		if($file != ".." && $file != "." && $file != ".svn") {
			$plugins[$i] = "./modules/customFields/plugins/$file";
		}
	}
	
	return $plugins;
}
	
function includePlugins() {
	$plugins = getPlugins();
	
	foreach($plugins as $plugin) {
		include_once($plugin);
	}
}

function getPluginById($id) {
		
	$plugins = getPluginArray();
	
	if($plugins[$id] != null) {
		return $plugins[$id];
	}
	
	/*foreach($plugins as $plugin) {
		if($plugin->id == $id) {
			return $plugin;
		}
	}*/
	return null;
}

function printPlugins() {
	
	$plugins = getPluginArray();
	//$plugins = getPlugins();

	//print_r($plugins);
	$out = '<select name="plugin">';
	
	foreach($plugins as $plugin) {		
		$out .= "<option value='$plugin->id'>$plugin->name</option>";
	}
	
	$out .= "</select>";
	echo $out."<br />";
}

?>