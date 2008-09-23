<?php

if ($config->authentication == 'true' ) {
	if (isset($_GET['location']) && $_GET['location'] == 'pdf' ) {
		include('../include/auth/auth.php');
	} 
	else {
		include('./include/auth/auth.php');
	}
}

?>
