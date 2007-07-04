<?php

//To turn authentification on uncomment (remove the /* and */) the lines below

if (isset($_GET['location']) && $_GET['location'] == 'pdf' ) {
	include('../include/auth/auth.php');
} 
else {
	include('./include/auth/auth.php');
}

?>
