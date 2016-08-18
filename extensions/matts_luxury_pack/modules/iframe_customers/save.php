<?php
//error:******
/*
* Script: ./extensions/matts_luxury_pack/modules/iframe_customers/save.php
* 	Customers save page
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-19
*
* License:
*	 GPL v2 or above
*
* Website:
* 	http://www.simpleinvoices.org
 */

//stop the direct browsing to this file - let index.php handle which files get displayed
//checkLogin();
$cwd = getcwd();
chdir(dirname(__FILE__)."/../../../..");
require_once("./library/encryption.php");
require_once("./include/sql_queries.php");

function update_Customer() {
	global $config;
$domain_id = $auth_session->domain_id;//	$domain_id = domain_id::get();


//	$encrypted_credit_card_number = '';
	$is_new_cc_num = ($_POST['credit_card_number_new'] !='');

	$sql = "UPDATE
				".TB_PREFIX."customers
			SET
				domain_id = :domain_id,
				name = :name,
				attention = :attention,
				street_address = :street_address,
				street_address2 = :street_address2,
				city = :city,
				state = :state,
				zip_code = :zip_code,
				country = :country,
				phone = :phone,
				mobile_phone = :mobile_phone,
				fax = :fax,
				email = :email,
				credit_card_holder_name = :credit_card_holder_name,
                " . (($is_new_cc_num) ? 'credit_card_number = :credit_card_number,' : '') . "
				credit_card_expiry_month = :credit_card_expiry_month,
				credit_card_expiry_year = :credit_card_expiry_year,
				notes = :notes,
				custom_field1 = :custom_field1,
				custom_field2 = :custom_field2,
				custom_field3 = :custom_field3,
				custom_field4 = :custom_field4,
				enabled = :enabled
			WHERE
				id = :id";

	if ($is_new_cc_num)
	{
		$credit_card_number = $_POST['credit_card_number_new'];
        
	        //cc
        	$enc = new encryption();
        	$key = $config->encryption->default->key;	
        	$encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);

		return dbQuery($sql,
			':domain_id', $domain_id,
			':name', $_POST['name'],
			':attention', $_POST['attention'],
			':street_address', $_POST['street_address'],
			':street_address2', $_POST['street_address2'],
			':city', $_POST['city'],
			':state', $_POST['state'],
			':zip_code', $_POST['zip_code'],
			':country', $_POST['country'],
			':phone', $_POST['phone'],
			':mobile_phone', $_POST['mobile_phone'],
			':fax', $_POST['fax'],
			':email', $_POST['email'],
			':notes', $_POST['notes'],
			':credit_card_holder_name', $_POST['credit_card_holder_name'],
			':credit_card_number', $encrypted_credit_card_number,
			':credit_card_expiry_month', $_POST['credit_card_expiry_month'],
			':credit_card_expiry_year', $_POST['credit_card_expiry_year'],
			':custom_field1', $_POST['custom_field1'],
			':custom_field2', $_POST['custom_field2'],
			':custom_field3', $_POST['custom_field3'],
			':custom_field4', $_POST['custom_field4'],
			':enabled', $_POST['enabled'],
			':id', $_GET['id']
		);
	} else {
		return dbQuery($sql,
			':domain_id', $domain_id,
			':name', $_POST['name'],
			':attention', $_POST['attention'],
			':street_address', $_POST['street_address'],
			':street_address2', $_POST['street_address2'],
			':city', $_POST['city'],
			':state', $_POST['state'],
			':zip_code', $_POST['zip_code'],
			':country', $_POST['country'],
			':phone', $_POST['phone'],
			':mobile_phone', $_POST['mobile_phone'],
			':fax', $_POST['fax'],
			':email', $_POST['email'],
			':notes', $_POST['notes'],
			':credit_card_holder_name', $_POST['credit_card_holder_name'],
			':credit_card_expiry_month', $_POST['credit_card_expiry_month'],
			':credit_card_expiry_year', $_POST['credit_card_expiry_year'],
			':custom_field1', $_POST['custom_field1'],
			':custom_field2', $_POST['custom_field2'],
			':custom_field3', $_POST['custom_field3'],
			':custom_field4', $_POST['custom_field4'],
			':enabled', $_POST['enabled'],
			':id', $_GET['id']
		);
	}
}

function insert_Customer() {
    global $config;
$domain_id = $auth_session->domain_id;//	$domain_id = domain_id::get();

	extract( $_POST );
	$sql = "INSERT INTO 
			".TB_PREFIX."customers
			(
				domain_id, attention, name, street_address, street_address2,
				city, state, zip_code, country, phone, mobile_phone,
				fax, email, notes,
				credit_card_holder_name, credit_card_number,
				credit_card_expiry_month, credit_card_expiry_year, 
				custom_field1, custom_field2,
				custom_field3, custom_field4, enabled
			)
			VALUES 
			(
				:domain_id ,:attention, :name, :street_address, :street_address2,
				:city, :state, :zip_code, :country, :phone, :mobile_phone,
				:fax, :email, :notes, 
				:credit_card_holder_name, :credit_card_number,
				:credit_card_expiry_month, :credit_card_expiry_year, 
				:custom_field1, :custom_field2,
				:custom_field3, :custom_field4, :enabled
			)";
	//cc
	$enc = new encryption();
	$key = $config->encryption->default->key;	
	$encrypted_credit_card_number = $enc->encrypt($key, $credit_card_number);

	return dbQuery($sql,
		':attention', $_POST['attention'],
		':name', $_POST['name'],
		':street_address', $_POST['street_address'],
		':street_address2', $_POST['street_address2'],
		':city', $_POST['city'],
		':state', $_POST['state'],
		':zip_code', $_POST['zip_code'],
		':country', $_POST['country'],
		':phone', $_POST['phone'],
		':mobile_phone', $_POST['mobile_phone'],
		':fax', $_POST['fax'],
		':email', $_POST['email'],
		':notes', $_POST['notes'],
		':credit_card_holder_name', $_POST['credit_card_holder_name'],
		':credit_card_number', $encrypted_credit_card_number,
		':credit_card_expiry_month', $_POST['credit_card_expiry_month'],
		':credit_card_expiry_year', $_POST['credit_card_expiry_year'],
		':custom_field1', $_POST['custom_field1'],
		':custom_field2', $_POST['custom_field2'],
		':custom_field3', $_POST['custom_field3'],
		':custom_field4', $_POST['custom_field4'],
		':enabled', $_POST['enabled'],
		':domain_id',$domain_id
	);
}

function search_Customers($search) {
//TODO remove this function - note used anymore
	global $db_server;
	$domain_id = domain_id::get();

	$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id AND name LIKE :search";
	if ($db_server == 'pgsql') {
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id AND  name ILIKE :search";
	}
	$sth = dbQuery($sql, ':domain_id',$domain_id, ':search', "%$search%");
	
	$customers = null;
	
	for($i=0; $customer = $sth->fetch(); $i++) {
		$customers[$i] = $customer;
	}
	//echo $sql;
	
	//print_r($customers);
	return $customers;
}


# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert customer

$saved = false;

if ($op === "insert_customer") {

	if (insert_Customer()) {
		$saved = true;
		// saveCustomFieldValues($_POST['categorie'],lastInsertId());
	}
}

if ($op === 'edit_customer') {

	if (isset($_POST['save_customer'])) {
		
		if (update_Customer()) {

			$saved = true;
			//updateCustomFieldValues($_POST['categorie'],$_GET['customer']);
		}
	}
}
$smarty -> assign('name',$_POST['name']);
$smarty -> assign('saved',$saved); 
$smarty -> assign('last_id',$_POST['last_id']);
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('active_tab', '#people');

$my_tpl = "../templates/default/iframe_customers/save.tpl";
$smarty -> $smarty_output($my_tpl);
chdir($cwd);

