<?php
/**
* Function: print_if_not_null
* 
* Used in the print preview to determine if a row/field gets printed, basically if the field is null dont print it else do
*
* Arguments:
* label		- The name of the field, ie. Custom Field 1, Email, etc..
* field		- The actual value from the db ie, test@test.com for email etc...
* class1	- the css class of the first td
* class2	- the css class of the second td
* colspan	- the colspan of the last td
**/
//function print_if_not_null($label,$field,$class1,$class2,$colspan) {
	
function smarty_function_online_payment_link($params, &$smarty) {
	global $LANG;

	$url = getURL();
        if (in_array("paypal",explode(",", $params['type'])))
	{

		$link = "<a 
				href=\"https://www.paypal.com/xclick/business=".$params['business']."&item_name=".$params['item_name']."&invoice=".$params['invoice']."&amount=".number_format($params['amount'], 2, '.', '')."&currency_code=".$params['currency_code']."&notify_url=".$params['notify_url']."&return=".$params['return_url']."&no_shipping=1&no_note=1\">";
		
		if($params['include_image'] == "true")
		{
			$link .= "<img src='".$url."/images/common/pay_with_paypal.gif'/>";
		} else {
			$link .= $params['link_wording'];
		} 
		
		$link .= "</a>";
       
		echo $link;
	}

        if (in_array("eway",explode(",", $params['type'])))
	{

		$link = "<a 
				href=\"https://www.paypal.com/xclick/business=".$params['business']."
				&item_name=".$params['item_name']."&invoice=".$params['invoice']."
				&amount=".number_format($params['amount'], 2, '.', '')."&currency_code=".$params['currency_code']."
				&return=http://vcsweb.com.au&no_shipping=1&no_note=1\">";
		
		if($params['include_image'] == "true")
		{
			$link .= "<img src='".$url."/images/common/pay_with_eway.gif'/>";
		} else {
			$link .= $params['link_wording'];
		} 
		
		$link .= "</a>";
       
		echo $link;
	}

}

?>
