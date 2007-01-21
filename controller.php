<?php

$module = $_GET['module'];
$view = $_GET['view'];
$action = $_GET['case'];


/*
if (($section != null ) AND ($view != null) AND ($case != null)) {
        include("./src/$section/$view.php?$case");
}
*/

/*dont include the header is requested file is an invoice template - for print preview etc.. header is not needed */
if (($module == "invoices" ) AND (strstr($view,"templates"))) {
        include("./src/$module/$view.php");
}
/*$module = the folder in src and view = the file in the requested folder
 *the below if grabs the requested folder and file based on the $_GET info in the url 
 */
else if (($module != null ) AND ($view != null)) {
        include("./src/include/design/header.inc.php");
        include("./src/$module/$view.php");
        include("./src/include/design/footer.inc.php");
}

/*If all else fals show the start.php page */
else {
        include("./src/include/design/header.inc.php");
        include("start.php");
        include("./src/include/design/footer.inc.php");
}

?>
