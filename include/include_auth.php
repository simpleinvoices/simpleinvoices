<?php

// 1 = config->auth->enabled == "true"
if ($config->authentication->enabled == 1 ) {
	if (isset($_GET['location']) && $_GET['location'] == 'pdf' ) {
		include('../include/auth/auth.php');
	} 
	else {
		include('./include/auth/auth.php');
	}
}

?>
