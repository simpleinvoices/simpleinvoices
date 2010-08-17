<?php

//stop the direct browsing to this file - let index.php handle which files get displayed
checkLogin();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;


#insert product
$saved = false;

if (  $op === 'insert_product' ) {
	
	if($id = insertProduct()) {
		echo "ID:".$product_id = lastInsertId();
 		$saved = true;
 		saveCustomFieldValues($_POST['categorie'], lastInsertId());
 	}
 	

 	$i = 1;
 	while ($i <= 3 )
 	{
 		if(!empty($_POST['attribute_'.$i]))
 		{
	 		$sql = "INSERT into
				".TB_PREFIX."products_matrix
			VALUES
				(
					NULL,
					:product_id,
					:product_attribute_number,
					:attribute_id
			 	)";
	
			dbQuery($sql,
		  		':product_id', $product_id, 
				':product_attribute_number', $i,
				':attribute_id', $_POST['attribute_'.$i]
		  	);
 		}
	  	$i++;
 	}
 	
}

if ($op === 'edit_product' ) {
	if (isset($_POST['save_product']) && updateProduct()) {
		$saved = true;
		updateCustomFieldValues($_POST['categorie'],$_GET['id']);
	}
	
 	$i = 1;
 	while ($i <= 3 )
 	{
		/* 1 - check if attribue exists for that product in the matrix - 2 if not INSERT 3 if is UPDATE*/

/*		$sql1 = "select attribute_id from ".TB_PREFIX."products_matrix where product_id = ".$_GET['id'];
		$result1 = dbQuery($sql1);
		$attributes = $result1->fetch(PDO::FETCH_ASSOC);		
		foreach ($attributes as $key=>$value)
		{
			if ($value == $_POST['attribute_'.$i])
			{
			echo "<br /><br /><b>".$_POST['attribute_'.$i]."in db</b><br />";
			}
			echo $key." ".$value>"<br />";
		}

		print_r($attributes) ;
*/
 		if($_POST['attribute_'.$i] != "")
		{
			#echo "<br /> Attr:".$_POST['attribute_'.$i]." is not empty";
	
			$sql = "select count(id) as count from ".TB_PREFIX."products_matrix where product_id = ".$_GET['id']." and product_attribute_number = ".$i;
			$count_result = dbQuery($sql);
			$number_of_rows = $count_result->fetch();
			$number_of_rows = $number_of_rows['count'];
			print_r($number_of_rows);
			//eacho $number_of_rows = $result;		
			

			if($number_of_rows > 0)
			{	
				#echo "<br /> Attr:".$_POST['attribute_'.$i]." updating";
				$sql = "UPDATE
								".TB_PREFIX."products_matrix
				SET
						attribute_id = :attribute_id
				WHERE 
					product_id = :product_id
					and
					product_attribute_number = :product_attribute_number
					";
				dbQuery($sql,
					':product_id', $_GET['id'], 
					':product_attribute_number', $i,
					':attribute_id', $_POST['attribute_'.$i]
				);
			} 
			if($number_of_rows == 0)
			{
				#echo "<br /> Attr:".$_POST['attribute_'.$i]." insert";
				$sql = "INSERT into
					".TB_PREFIX."products_matrix
				VALUES
					(
						NULL,
						:product_id,
						:product_attribute_number,
						:attribute_id
					)";
		
				dbQuery($sql,
					':product_id', $_GET['id'], 
					':product_attribute_number', $i,
					':attribute_id', $_POST['attribute_'.$i]
				);
			} # inner !empty - insert
		} #!empty - end
 		if($_POST['attribute_'.$i] =="")
		{
			#echo "<br /> Attr:".$_POST['attribute_'.$i]." is empty";
/*
			function checkRows()
			{
				$sql = "select * from ".TB_PREFIX."products_matrix where product_id = ".$_GET['id']." and product_attribute_number = ".$_POST['attribute_'.$i];
				$result = dbQuery($sql);
				$number_of_rows = $result->rowCount();		
			}
#			if value for attrx = null - check it is in db - if in db then delete
			if(checkRows())
			{	
*/
				#echo "<br />Deleting  Attr:".$_POST['attribute_'.$i];
				$sql = "DELETE FROM
								".TB_PREFIX."products_matrix
				WHERE 
					product_id = :product_id
					and
					product_attribute_number = :product_attribute_number
					";
				dbQuery($sql,
					':product_id', $_GET['id'], 
					':product_attribute_number', $i
				);
		#	} #inner empy - delete
		} #end empty
	
	$i++;
 	}
	
	
}



/*if (mysqlQuery($sql, $conn)) {
	$display_block = $LANG['save_product_success'];
	 saveCustomFieldValues($_POST['categorie'],mysql_insert_id());

} else {
	$display_block = $LANG['save_product_failure'];
}

	$refresh_total = "<meta http-equiv='refresh' content='1;URL=index.php?module=products&amp;view=manage' />";
}



/*#edit product

else if (  $op === 'edit_product' ) {


	if (isset($_POST['save_product'])) {
		
		if (mysqlQuery($sql, $conn)) {
			 updateCustomFieldValues($_POST['categorie'],mysql_insert_id());

			$display_block = $LANG['save_product_success'];
		} else {
			$display_block = $LANG['save_product_failure'];
		}

		$refresh_total = "<meta http-equiv='refresh' content='0;url=index.php?module=products&amp;view=manage' />";
		}

	else if (isset($_POST['cancel'])) {
	
		$refresh_total = "<meta http-equiv='refresh' content='0;url=index.php?module=products&amp;view=manage' />";
	}
}*/


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';


$pageActive = "products";
//$smarty->assign('pageActive', $pageActive);
$smarty->assign('saved',$saved);
//$smarty -> assign('display_block',$display_block); 
//$smarty -> assign('refresh_total',$refresh_total); 

?>
