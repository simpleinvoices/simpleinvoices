<?php
/*
* Script: index.php
* 	All manage functions for the custom field system (global)
*
* Authors:
*	 Nicolas Ruflin
*
* Last edited:
* 	 2007-09-10
*
* License:
*	 GPL v2 or above
*/
include("./include/CustomField.php");


function saveCustomField($id, $categorie,$name,$description) {
	$sql = "INSERT INTO ".TB_PREFIX."customFields  ( `pluginId` ,  `categorieId` ,  `name` ,  `description` ) 
		VALUES ('$id','$categorie','$name','$description');";
	mysqlQuery($sql);
	echo "SAVED<br />";
}


/** 
 * Stores all values of a page into the database.
 * The POST values are in the form cf$id to catch all values
 */
function saveCustomFieldValues($categorieId,$itemId) {
	
	$plugins = getPluginsByCategorie($categorieId);
	
	if($plugins == null) {
		return;
	}
	
	foreach($plugins as $plugin) {
		$id = $plugin->fieldId;
		$plugin->saveInput($_POST["cf$id"],$itemId);
	}
}

function updateCustomFieldValues($categorieId,$itemId) {
	$plugins = getPluginsByCategorie($categorieId);
	
	if($plugins == null) return;
	
	foreach($plugins as $plugin) {
		$id = $plugin->fieldId;
		$plugin->updateInput($_POST["cf$id"],$itemId);
	}
}

function getPluginsByCategorie($categorieId) {
	$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE categorieID = $categorieId;";
	$query = mysqlQuery($sql);
	
	$plugins = null;
	
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

/**
 * Reads in all plugins an create an instance for each.
 */
function getPluginArray() {
	
	$plugins = includePlugins();

	$classes = null;
	$i = 0;
	
	foreach($plugins as $plugin) {
		$path = basename($plugin,".php");
		$plugin = new $path();
		$classes[$plugin->id] = $plugin;
	}
	return $classes;
}


/*****
 TODO: Custom field output. Should be in Smarty.
 ******/
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


/**
 * Returns an array with all names of the plugins. 
 */
function getPlugins() {
	$files = scandir("./modules/customFields/plugins/");	//CustomFields directory plugins
	$plugins = null;
	
	for($i=0;$i<count($files);$i++) {
		$file = $files[$i];
		if(preg_match("/\.php$/",$file)) {
			$plugins[$i] = "./modules/customFields/plugins/$file";
		}
	}
	
	return $plugins;
}

/**
 * Reads (includes) the plugin files
 */
function includePlugins() {
	$plugins = getPlugins();
	
	foreach($plugins as $plugin) {
		include_once($plugin);
	}
	return $plugins;
}


function getPluginById($id) {
		
	$plugins = getPluginArray();
	
	
	if($plugins[$id] != null) {
		return $plugins[$id];
	}
	
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