<?php

//if pdfing the print preview dont include the auth as its already been done and its chuck a spazz
if ($_GET['location'] === 'pdf' ) {
	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();
}
// not pdfing so include the auth and all the others
else {
	include('./include/include_auth.php');
	include('./config/config.php');
	ob_start();
	include("./lang/$language.inc.php");
	ob_end_clean();
}

?>
