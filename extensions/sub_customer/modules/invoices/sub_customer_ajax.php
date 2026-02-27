<?php


function getSubCustomer($parent_customer_id='') {
	global $dbh;
	global $db_server;
	global $auth_session;
	
	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE parent_customer_id = :parent_customer_id and domain_id = :domain_id ;";
	$sth = dbQuery($sql, ':domain_id',$auth_session->domain_id, ':parent_customer_id',$parent_customer_id) or die(htmlsafe(end($dbh->errorInfo())));
	
    $code = $sth->fetchAll();
    
    $code_description[]= '';
    $output .= "<option value=''></option>";

    foreach($code as $key=>$value)
    {
                $output .= "<option value='" . $value['id'] . "'>". $value['name'] . "</option>";
	
    }
	echo json_encode($output);
	
	exit();


}

getSubCustomer($_GET['id']);
