<?php
function smarty_function_online_payment_link($params, &$smarty) {
	global $LANG;
	$domain_id = domain_id::get($params['domain_id']);

	$url = getURL();
        if (in_array("paypal",explode(",", $params['type'])))
	{

		$link = "<a 
				href=\"https://www.paypal.com/xclick/?business=".urlencode($params['business'])."&item_name=".urlencode($params['item_name'])."&invoice=".urlencode($params['invoice'])."&amount=".urlencode(number_format($params['amount'], 2, '.', ''))."&currency_code=".urlencode($params['currency_code'])."&notify_url=".urlencode($params['notify_url'])."&return=".urlencode($params['return_url'])."&no_shipping=1&no_note=1&custom=domain_id:".urlencode($domain_id)."; \">";
		
		if($params['include_image'] == "true")
		{
			$link .= "<img border='0' src='".urlsafe($url)."/images/common/pay_with_paypal.gif'/>";
		} else {
			$link .= htmlsafe($params['link_wording']);
		} 
		
		$link .= "</a>";
       
		echo $link;
	}

        if (in_array("eway_shared",explode(",", $params['type'])))
	{

		$link = "<a 
				href=\"https://www.paypal.com/xclick/?business=".urlencode($params['business']."
				&item_name=".urlencode($params['item_name'])."&invoice=".urlencode($params['invoice'])."
				&amount=".urlencode(number_format($params['amount'], 2, '.', ''))."&currency_code=".$params['currency_code'])."
				&return=http://vcsweb.com.au&no_shipping=1&no_note=1\">";
		
		if($params['include_image'] == "true")
		{
			$link .= "<img border='0' src='".urlsafe($url)."/images/common/pay_with_eway.gif'/>";
		} else {
			$link .= htmlsafe($params['link_wording']);
		} 
		
		$link .= "</a>";
       
		echo $link;
	}

}

?>
