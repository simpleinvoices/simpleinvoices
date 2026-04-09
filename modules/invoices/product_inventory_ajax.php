<?php


if($_GET['id'])
{
	//sleep(2);
	$product_id = (int)$_GET['id']; // Cast to integer to prevent SQL injection
	$sql = "SELECT cost FROM ".TB_PREFIX."products WHERE id = :id AND domain_id = :domain_id LIMIT 1";
	$states = dbQuery($sql, ':id', $product_id, ':domain_id', $auth_session->domain_id);
//	$output = '';
	$row = $states->fetch();
	if($row !== false)
	{
			/*Format with decimal places with precision as defined in config.php*/
			$output['cost'] = siLocal::number_formatted($row['cost']);
	}

	echo json_encode($output);
	
	exit();
} else {

echo "";
}

// Perform teh Queries!
//$sql = 'SELECT * FROM si_products';
//$country = mysqlQuery($sql) or die('Query Failed:' . mysql_error());


?>
