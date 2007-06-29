<?php

if(isset($_POST['save'])) {
	saveCustomField();
}

include("./include/CustomField.php");

echo '<form method="post" action="index.php?module=customFields&view=manageCustomFields">';

printPlugins();
printCategories();

echo <<<EOD
	Name: <input type="text" name="name"><br />
	Description: <input type="text" name="description"><br />
	<input type="submit" name="save">
	</form>
EOD;


printCustomFieldsList();

//$plugins = getPlugins();

//getPluginArray();

/*print_r($plugins);

$path = pathinfo($plugins[2]);
$path = $path['filename'];
echo $path;

$filed = new $path();
echo "..".$filed->id."..";*/

//echo "test";

function saveCustomField() {
	$sql = "INSERT INTO ".TB_PREFIX."customFields  ( `pluginId` ,  `categorieId` ,  `name` ,  `description` ) 
		VALUES ('$_POST[plugin]','$_POST[categorie]','$_POST[name]','$_POST[description]');";
	mysqlQuery($sql);
	echo "SAVED<br />";
}

function getPluginArray() {
	$plugins = getPlugins();

	foreach($plugins as $plugin) {
		include($plugin);
	}

	//$test = new CustomNumber();
	

	$plugins = getPlugins();
	$classes = null;
	$i = 0;
	
	foreach($plugins as $plugin) {
		$path = pathinfo($plugin);
		$path = $path['filename'];
		$classes[$i] = new $path();
		$i++;
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
		if($file != ".." && $file != ".") {
			$plugins[$i] = "./modules/customFields/plugins/$file";
		}
	}
	
	return $plugins;
}
	
function includePlugins() {
	$plugins = getPlugins();
	
	foreach($plugins as $plugin) {
		include($plugin);
	}
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

function initPlugin($id) {
	
}


function showCustomFields($categorieId) {
	//show all custom_fields that are enabled for this page
}

?>