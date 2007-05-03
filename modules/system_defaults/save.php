<?php

checkLogin();

# Deal with op and add some basic sanity checking

$saved = false;

if (isset($_POST['op']) && $_POST['op'] == 'update_system_defaults' ) {
	
	if(updateDefault($_POST['name'],$_POST['value'])) {
		$saved = true;
	}
}

$smarty -> assign("saved",$saved);

?>