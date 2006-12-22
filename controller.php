<?php

$section = $_GET['section'];
$view = $_GET['view'];
$action = $_GET['action'];

if (($section != null ) AND ($view != null) AND ($action != null)) {
        include("./src/$section/$view.php?$action");
}
else if (($section != null ) AND ($view != null)) {
        include("./src/$section/$view.php");
}
else {
        include("start.php");
}

?>
