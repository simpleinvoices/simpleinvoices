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


function saveCustomField($id, $category, $name, $description) {
	$sql = "INSERT INTO ".TB_PREFIX."customFields  (pluginId, categorieId, name, description) 
		VALUES (:id, :category, :name, :description)";
	dbQuery($sql, ':id', $id, ':category', $category, ':name', $name, ':description', $description);
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

function getPluginsByCategorie($categoryId) {
	$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE categorieID = :category";
	$sth = dbQuery($sql, ':category', $categoryId);
	
	$plugins = null;
	
	for($i=0; $field = $sth->fetch(); $i++) {
		$plugins[$i] = getPluginById($field['pluginId']);
		$plugins[$i]->setFieldId($field['id']);
	}
	
	return $plugins;
}

function showCustomFields($categoryId) {
	$sql = "SELECT * FROM ".TB_PREFIX."customFields WHERE categorieID = :category";
	$sth = dbQuery($sql, ':category', $categoryId);
	
	while($field = $sth->fetch()) {
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
	global $dbh;
	$sql = "SELECT * FROM ".TB_PREFIX."customFields;";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	
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

	while($customField = $sth->fetch()) {
		echo '
		<tr>
			<td>'.htmlsafe($customField[id]).'</td>
			<td>'.htmlsafe($customField[name]).'</td>
			<td>'.htmlsafe($customField[description]).'</td>
			<td>'.htmlsafe($customField[categorieId]).'</td>
			<td>'.htmlsafe($customField[pluginId]).'</td>
			<td>'.htmlsafe($customField[active]).'</td>
		</tr>
';
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
	global $dbh;
	$sql = "SELECT * FROM ".TB_PREFIX."customFieldCategories";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	
	for($i=0;$cat = $sth->fetch();$i++) {
		$categories[$i] = $cat;
	}
	return $categories;
}

function printCategories() {
	$cats = getCategories();
	
	$out = '<select name="categorie">';
	
	foreach($cats as $cat) {
		$out .= "<option value='".htmlsafe($cat[id])."' >".htmlsafe($cat[name])."</option>";
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
		$out .= "<option value='".htmlsafe($plugin->id)."'>".htmlsafe($plugin->name)."</option>";
	}
	
	$out .= "</select>";
	echo $out."<br />";
}
