<?php

include ('extensions/sub_customer/include/class/sub_customer.php');

function getSubCustomers($customer_id)
{
    global $db;
    $domain_id = domain_id::get();
    
    $sql="SELECT * FROM ".TB_PREFIX."customers
          WHERE parent_customer_id = :customer_id
          AND domain_id = :domain_id";

	$sth = $db->query($sql, ':customer_id', $customer_id, ':domain_id',$domain_id);
	return $sth->fetchAll();
}
