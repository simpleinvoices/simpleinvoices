<?php 
	if ($_GET['id'])
	{
		$category_id = $_GET['id'];
		$sql = sprintf("SELECT * FROM ".TB_PREFIX."products p
WHERE p.category_id IN (SELECT ch.category_id 
                                        FROM ".TB_PREFIX."categories_taxonomy ch 
                                          INNER JOIN
                                                 ".TB_PREFIX."categories pr 
                                          ON ch.parent=pr.category_id
                                          WHERE pr.category_id=$category_id)
OR p.category_id=$category_id", $_GET['id']);
		$products = dbQuery($sql);
		$output = null;
		if ($products->rowCount() > 0)
		{
			$rows = $products->fetchAll();
			$output = $rows;
		}
		else
		{
			$output = "";
		}
		
		echo json_encode($output);
	exit();
	}
	else
	{
		echo "";
	}
?>
