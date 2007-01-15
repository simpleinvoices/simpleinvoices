<?php

$module = $_GET['module'];
$view = $_GET['view'];
$action = $_GET['case'];


/*
if (($section != null ) AND ($view != null) AND ($case != null)) {
        include("./src/$section/$view.php?$case");
}
*/
if (($module != null ) AND ($view != null)) {
        include("header.inc.php");
        include("./src/$module/$view.php");
}
else {
        include("header.inc.php");
        include("start.php");
}

?>
