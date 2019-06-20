<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @author Empretel, www.empretel.net
 */

/**
 * Invoice referencia plugin
 * 
 * @param mixed $params Array with 'categoryId'.
 * @param Object $smarty
 * @example {invoice_referencia categoryId=$invoice.category_id}
 */

function smarty_function_invoice_fecha($params, &$smarty)
{
	if(empty($params['invoiceId']))
	{
		$params['invoiceId'] = getInvoice($params['invoiceId']);
	}
 
 $fecha = getInvoice($params['invoiceId']);
 
 $fecha = strtotime($fecha['date']);
 $formato = 'd/m/Y';
 $fecha = date($formato,$fecha);

 echo $fecha;
}
?>