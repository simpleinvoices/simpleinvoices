<?php
/*
* Script: save.php
* 	Biller save page
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
checkLogin();

$SI_BILLER = new SimpleInvoices_Db_Table_Biller();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert biller

$saved = false;

if ( $op === 'insert_biller') {
    
	$biller_data = array(
        'name'                  => $_POST['name'], 
        'tax_id'                => $_POST['tax_id'], 
        'street_address'        => $_POST['street_address'],
        'street_address2'       => $_POST['street_address2'],
        'city'                  => $_POST['city'],
        'state'                 => $_POST['state'], 
        'zip_code'              => $_POST['zip_code'],
        'country'               => $_POST['country'],
        'phone'                 => $_POST['phone'], 
        'mobile_phone'          => $_POST['mobile_phone'],
        'fax'                   => $_POST['fax'],
        'email'                 => $_POST['email'],
        'logo'                  => $_POST['logo'],
        'footer'                => $_POST['footer'],
        'paypal_business_name'  => $_POST['paypal_business_name'],
        'paypal_notify_url'     => $_POST['paypal_notify_url'],
        'paypal_return_url'     => $_POST['paypal_return_url'],
        'eway_customer_id'      => $_POST['eway_customer_id'], 
        'notes'                 => $_POST['notes'], 
        'custom_field1'         => $_POST['custom_field1'],
        'custom_field2'         => $_POST['custom_field2'],
        'custom_field3'         => $_POST['custom_field3'],
        'custom_field4'         => $_POST['custom_field4'],
        'enabled'               => $_POST['enabled']
    );
    
	$saved = $SI_BILLER->insert($biller_data);
}

if ($op === 'edit_biller' ) {
    if (isset($_POST['save_biller']) && isset($_GET['id']) && is_numeric($_GET['id'])) {
        $biller_data = array(
            'name'                  => $_POST['name'], 
            'tax_id'                => $_POST['tax_id'], 
            'street_address'        => $_POST['street_address'],
            'street_address2'       => $_POST['street_address2'],
            'city'                  => $_POST['city'],
            'state'                 => $_POST['state'], 
            'zip_code'              => $_POST['zip_code'],
            'country'               => $_POST['country'],
            'phone'                 => $_POST['phone'], 
            'mobile_phone'          => $_POST['mobile_phone'],
            'fax'                   => $_POST['fax'],
            'email'                 => $_POST['email'],
            'logo'                  => $_POST['logo'],
            'footer'                => $_POST['footer'],
            'paypal_business_name'  => $_POST['paypal_business_name'],
            'paypal_notify_url'     => $_POST['paypal_notify_url'],
            'paypal_return_url'     => $_POST['paypal_return_url'],
            'eway_customer_id'      => $_POST['eway_customer_id'], 
            'notes'                 => $_POST['notes'], 
            'custom_field1'         => $_POST['custom_field1'],
            'custom_field2'         => $_POST['custom_field2'],
            'custom_field3'         => $_POST['custom_field3'],
            'custom_field4'         => $_POST['custom_field4'],
            'enabled'               => $_POST['enabled']
        );
        
        $saved = $SI_BILLER->update($biller_data, $_GET['id']);
    }
}


$smarty -> assign('saved',$saved);

$smarty -> assign('pageActive', 'biller');
$smarty -> assign('active_tab', '#people');
?>