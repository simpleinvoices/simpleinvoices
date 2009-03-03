<?php

function smarty_function_subtotal($params, &$smarty)
{
	$subtotal = 0;
	foreach ($params['cost'] as $key=>$value)
	{

	//	print_r($value);
		if ($value['product']['custom_field1'] == $params['group'])
		{
			$subtotal = $value['gross_total'] + $subtotal;
		}
	}
	$subtotal = siLocal::number($subtotal);	
	return $subtotal;	

}


?>
