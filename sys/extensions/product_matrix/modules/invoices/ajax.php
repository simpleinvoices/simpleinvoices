<?php

header("Content-Type: application/json; charset=UTF-8");

$json = array();

// Lets get the variables we passed
$search = isset($_GET['search']) ? (string) $_GET['search'] : '';

$str = isset($_GET['_value']) ? (string) $_GET['_value'] : '';

// Cleans query string before we run the selects below.
// Allows A-Z, a-z, 0-9, whitespace, minus/hyphen, equals, ampersand, underscore, and period/full stop.
$str = preg_replace("/[^A-Za-z0-9\s\-\=\&\_\.]/","", $str);

switch ($search) {

   case "attr1":


	  $sql = "select 
					CONCAT(p.id, '-', a.id, '-', v.id) as id, 
					CONCAT(a.display_name, '-',v.value) as display 
				from 
					si_products_attributes a, 
					si_products_values v,
					si_products_matrix m,
					si_products p

				where 
					p.id = m.product_id 
					and 
					a.id = m.attribute_id 
					and 
					v.attribute_id = a.id 
					and
					p.id= '{$str}'
					and
					m.product_attribute_number = '1' 
				";

	  $sth =  dbQuery($sql);
//	  $matrix = $sth->fetchAll();


		foreach($sth as $row) {
         $json[] =  array($row['id'] => $row['display']);
      }
	  echo json_encode( $json );
   break;

   case "attr2":

/*	$str_before = substr($str, 0, strpos($str, '-'));     
	$str_after = substr($str, -1, strpos($str, '-'));      */
	
		$sql = "select 
					CONCAT(p.id, '-', a.id, '-', v.id) as id, 
					CONCAT(a.display_name, '-',v.value) as display 
				from 
					si_products_matrix m, 
					si_products_attributes a, 
					si_products p, 
					si_products_values v 
				where 
					p.id = m.product_id 
					and 
					a.id = m.attribute_id 
					and 
					v.attribute_id = a.id 
					and 
					p.id = '{$str}'
					and
					m.product_attribute_number = '2' 
				";
		$sth =  dbQuery($sql);
		foreach($sth as $row) {
        	$json[] =  array($row['id'] => $row['display']);
      	}

	  echo json_encode( $json );
   break;

   case "attr3":
/*
	$str_before = substr($str, 0, strpos($str, '-'));     
	$str_after = substr($str, -1, strpos($str, '-'));      
*/	
		$sql = "select 
					CONCAT(p.id, '-', a.id, '-', v.id) as id, 
					CONCAT(a.display_name, '-',v.value) as display 
				from 
					si_products_matrix m, 
					si_products_attributes a, 
					si_products p, 
					si_products_values v 
				where 
					p.id = m.product_id 
					and 
					a.id = m.attribute_id 
					and 
					v.attribute_id = a.id 
					and 
					p.id = '{$str}'
					and
					m.product_attribute_number = '3' 
				";
		$sth =  dbQuery($sql);
		foreach($sth as $row) 
		{
     	    $json[] =  array($row['id'] => $row['display']);
	    }

	  echo json_encode( $json );

	break;
/*
      while ($db->next_record()) {

         $json[] = '{"optionValue": "'.$db->f("id").'", "optionDisplay": "'.$db->f("display").'"}';
      }

	  echo json_encode( $json );

	*/

   default:


      echo "Something has gone wrong!";


   break;

}
?>
