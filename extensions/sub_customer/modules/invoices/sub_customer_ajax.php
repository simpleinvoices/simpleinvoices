<?php


function getSubCustomer($parent_customer_id='') {
	global $db_server;
	global $auth_session;
	global $db;
	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE parent_customer_id = :parent_customer_id and domain_id = :domain_id ;";
	$sth = $db->query($sql, ':domain_id',$auth_session->domain_id, ':parent_customer_id',$parent_customer_id);
	
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
