<?php

//if pdfing the print preview dont include the auth as its already been done and its chuck a spazz
if ($_GET['location'] === 'pdf' ) {
	include('./config/config.php');
	include("./lang/$language.inc.php");
}
// not pdfing so include the auth and all the others
else {
	include('./include/include_auth.php');
	include('./config/config.php');
	include("./lang/$language.inc.php");
}

?>
