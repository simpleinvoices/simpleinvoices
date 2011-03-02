<?php


if($_GET['id'])
{
	//sleep(2);
	$sql = sprintf('SELECT unit_price, default_tax_id, default_tax_id_2 FROM si_products WHERE id = %d LIMIT 1', $_GET['id']);
	$states = dbQuery($sql);
//	$output = '';
	if($states->rowCount() > 0)
	{	
		$row = $states->fetch();

	//	print_r($row);
	//		$output .= '<input id="state" class="field select two-third addr" value="'.$row['unit_price'].'"/>';
			/*Format with decimal places with precision as defined in config.ini*/
			$output['unit_price'] = siLocal::number_clean($row['unit_price']);
			$output['default_tax_id'] = $row['default_tax_id'];
			$output['default_tax_id_2'] = $row['default_tax_id_2'];
	//		$output .= $_POST['id'];
		
	}
	else
	{
		$output .= '';
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
