<?php

set_include_path(get_include_path() . PATH_SEPARATOR . ".");
set_include_path(get_include_path() . PATH_SEPARATOR . "./sys/include/class");
set_include_path(get_include_path() . PATH_SEPARATOR . "./lib/");
set_include_path(get_include_path() . PATH_SEPARATOR . "./lib/pdf");
set_include_path(get_include_path() . PATH_SEPARATOR . "./sys/include/");

$include_dir ='';

// need to add code here to determine which si host it will use - app by defaults - you copy this file and change it to app2 etc..
$app = 'app';

include($app .'/index.php');

?>
