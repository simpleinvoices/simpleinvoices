<?php

if(isset($_POST['save'])) {
	saveCustomField();
}


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
showCustomFields(3);
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