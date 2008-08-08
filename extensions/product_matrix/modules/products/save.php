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
 		if(!empty($_POST['attribute_'.$i]))
 		{
	 		$sql = "UPDATE
	 						".TB_PREFIX."products_matrix
			SET
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



/*if (mysqlQuery($sql, $conn)) {
	$display_block = $LANG['save_product_success'];
	 saveCustomFieldValues($_POST['categorie'],mysql_insert_id());

} else {
	$display_block = $LANG['save_product_failure'];
}

	$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=1;URL=index.php?module=products&view=manage>";
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

		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";
		}

	else if (isset($_POST['cancel'])) {
	
		$refresh_total = "<META HTTP-EQUIV=REFRESH CONTENT=0;URL=index.php?module=products&view=manage>";
	}
}*/


$refresh_total = isset($refresh_total) ? $refresh_total : '&nbsp';


$pageActive = "products";
//$smarty->assign('pageActive', $pageActive);
$smarty->assign('saved',$saved);
//$smarty -> assign('display_block',$display_block); 
//$smarty -> assign('refresh_total',$refresh_total); 

?>
