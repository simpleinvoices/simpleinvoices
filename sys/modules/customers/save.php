<?php
/*
* Script: save.php
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
checkLogin();

$SI_CUSTOMERS = new SimpleInvoices_Db_Table_Customers();

# Deal with op and add some basic sanity checking

$op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;

#insert customer

$saved = false;

if (($op === "insert_customer") || ($op === 'edit_customer')) {
    $customer_data = array(
        'attention'                 => $_POST['attention'],
        'name'                      => $_POST['name'],
        'tax_id'                    => $_POST['tax_id'],
        'street_address'            => $_POST['street_address'],
        'street_address2'           => $_POST['street_address2'],
        'city'                      => $_POST['city'],
        'state'                     => $_POST['state'],
        'zip_code'                  => $_POST['zip_code'],
        'country'                   => $_POST['country'],
        'phone'                     => $_POST['phone'],
        'mobile_phone'              => $_POST['mobile_phone'],
        'fax'                       => $_POST['fax'],
        'email'                     => $_POST['email'],
        'credit_card_holder_name'   => $_POST['credit_card_holder_name'],
        'credit_card_expiry_month'  => $_POST['credit_card_expiry_month'],
        'credit_card_expiry_year'   => $_POST['credit_card_expiry_year'],
        'notes'                     => $_POST['notes'],
        'custom_field1'             => $_POST['custom_field1'],
        'custom_field2'             => $_POST['custom_field2'],
        'custom_field3'             => $_POST['custom_field3'],
        'custom_field4'             => $_POST['custom_field4'],
        'enabled'                   => $_POST['enabled']
    );
    
    if($_POST['credit_card_number_new'] !='') {
        $customer_data['credit_card_number'] = $_POST['credit_card_number_new'];
    }
    
    // New
    if ($op === "insert_customer") {
        $saved = $SI_CUSTOMERS->insert($customer_data);
    }
    
    // Edit
    if ( $op === 'edit_customer' ) {
        if (isset($_POST['save_customer']) && isset($_GET['id'])) {
            if (is_numeric($_GET['id'])) {
                $saved = $SI_CUSTOMERS->update($customer_data, $_GET['id']);    
            }
        }
    }
}

$smarty -> assign('saved',$saved);
$smarty -> assign('pageActive', 'customer');
$smarty -> assign('active_tab', '#people');
?>