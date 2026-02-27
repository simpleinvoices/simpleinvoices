<?php

function smarty_function_markup_percentage($params, &$smarty)
{

	
	$subtotal_tax = 0;
	$subtotal_total = 0;
	foreach ($params['cost'] as $key=>$value)
	{

		if ($value['product']['custom_field1'] == $params['group'])
		{
			$subtotal_tax = $value['tax_amount'] + $subtotal_tax;
			$subtotal_total = $value['gross_total'] + $subtotal_total;
		}
	}
	$subtotal = round(($subtotal_tax/$subtotal_total)*100,0);	
	//$subtotal = siLocal::number($subtotal);	
	return htmlsafe($subtotal);	

}


?>
