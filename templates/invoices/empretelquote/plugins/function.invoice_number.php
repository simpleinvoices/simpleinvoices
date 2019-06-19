<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @author Gelderblom Webdesign, www.gelderblomwebdesign.nl
 */

/**
 * Invoice number plugin
 * 
 * @param mixed $params Array with 'invoiceId' and 'length'. Length will be defaulted to 6, if not set.
 * @param Object $smarty
 * @example {invoiceNumber invoiceId=$invoice.id length=8}
 */

function smarty_function_invoice_number($params, &$smarty)
{
 	
	if(empty($params['length']))
	{
		$params['length'] = 3;
	}
	$comp = $params['length'] - strlen($params['invoiceId']);
	for($i = 1; $i <= $comp; $i++) echo '0';
	echo $params['invoiceId'];
}

?>