<?php

ob_start();
var_dump($_POST);
error_log(ob_get_contents());
ob_end_clean();

?>