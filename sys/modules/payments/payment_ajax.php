<?php

if($_GET['id'])
{
	$invoice = getInvoice($_GET['id']);
	
	$output['owing'] = $invoice['owing'];	

	echo json_encode($output);
	
	exit();
} else {

echo "";
}


?>

