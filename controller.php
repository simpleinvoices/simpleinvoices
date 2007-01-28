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
	if (file_exists("./src/$module/$view.php")) {
	        include("./src/$module/$view.php");
	}
	else {
		echo "The file that you requested doesn't exist";
	}
	
}
/*$module = the folder in src and view = the file in the requested folder
 *the below if grabs the requested folder and file based on the $_GET info in the url 
 */
else if (($module != null ) AND ($view != null)) {
	
	if (file_exists("./src/$module/$view.php")) {
	        include("./src/include/design/header.inc.php");
	        include("./src/$module/$view.php");
        	include("./src/include/design/footer.inc.php");
	}
	else {
		include("./src/include/design/header.inc.php");
		echo "The file that you requested doesn't exist";
		include("./src/include/design/footer.inc.php");
	}
}

/*If all else fails show the start.php page */
else {
        include("./src/include/design/header.inc.php");
        include("start.php");
        include("./src/include/design/footer.inc.php");
}

?>
