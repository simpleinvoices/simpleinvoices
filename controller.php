<?php

$section = $_GET['section'];
$view = $_GET['view'];
$action = $_GET['action'];

/* - not needed any more
if (($section != null ) AND ($view != null) AND ($action != null)) {
        include("./src/$section/$view.php?$action");
}
*/
if (($module != null ) AND ($view != null)) {
        include("./src/$module/$view.php");
}
else {
        include("start.php");
}

?>
