<?php
/*
* Script: manageCustonFields.php
* 	new manage custom fields page
*
* Authors:
*	 Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

if(isset($_POST['save'])) {
	saveCustomField($_POST[plugin],$_POST[categorie],$_POST[name],$_POST[description]);
}


echo '<form method="post" action="index.php?module=customFields&view=manageCustomFields">';

echo "Plugins: ";
printPlugins();
echo "Categorie: ";
printCategories();


//Note: If input is language specific it has to be in the form: {$LANG['value']} or {$LANG["value"]}

echo <<<EOD
	Name: <input type="text" name="name"><br />
	Description: <input type="text" name="description"><br />
	<input type="submit" name="save">
	</form>
EOD;


printCustomFieldsList();
//showCustomFields(3);

//$plugins = getPlugins();

//getPluginArray();

/*print_r($plugins);

$path = pathinfo($plugins[2]);
$path = $path['filename'];
echo $path;

$filed = new $path();
echo "..".$filed->id."..";*/

//echo "test";


?>
