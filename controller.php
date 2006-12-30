<?php

$module = $_GET['module'];
$view = $_GET['view'];

$action = $_GET['action'];



if (($section != null ) AND ($view != null) AND ($action != null)) {
        include("./src/$section/$view.php?$action");
}
else if (($module != null ) AND ($view != null)) {
        include("./src/$module/$view.php");
}
else {
        include("start.php");
}

?>
