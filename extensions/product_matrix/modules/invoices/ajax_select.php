<?php

header("Content-Type: application/json; charset=UTF-8");

$json = array();

// Lets get the variables we passed
$search = isset($_GET['search']) ? (string) $_GET['search'] : '';

$str = isset($_GET['str']) ? (string) $_GET['str'] : '';

// Cleans query string before we run the selects below.
// Allows A-Z, a-z, 0-9, whitespace, minus/hyphen, equals, ampersand, underscore, and period/full stop.
$str = preg_replace("/[^A-Za-z0-9\s\-\=\&\_\.]/","", $str);

switch ($search) {

   case "country":


	  $sql = "select 
					CONCAT(a.id, '-', v.id) as id, 
					CONCAT(a.name, '-',v.value) as display 
				from 
					si_products_attributes a, 
					si_products_values v 
				where 
					a.id = v.attribute_id 
					AND 
					a.id= '{$str}'
				";

	  $sth =  dbQuery($sql);
//	  $matrix = $sth->fetchAll();


      while ($sth->nextRowset()) {

         $json[] = '{"optionValue": "'.$db->f("id").'", "optionDisplay": "'.$db->f("display").'"}';
      }
      echo '[' . implode(',', $json) . ']';

   break;

   case "state":

	$str_before = substr($str, 0, strpos($str, '-'));     
	$str_after = substr($str, -1, strpos($str, '-'));      
	
		$db->query("select 
					CONCAT(a.id, '-', v.id) as id, 
					CONCAT(a.name, '-',v.value) as display 
				from 
					si_products_attributes a, 
					si_products_values v 
				where 
					a.id = v.attribute_id 
					AND 
					a.id= '{$str_before}'
					AND
					v.id != '{$str_after}'
				");

      while ($db->next_record()) {

         $json[] = '{"optionValue": "'.$db->f("id").'", "optionDisplay": "'.$db->f("display").'"}';
      }

      echo '[' . implode(',', $json) . ']';

   break;

   default:


      echo "Something has gone wrong!";


   break;

}
?>
