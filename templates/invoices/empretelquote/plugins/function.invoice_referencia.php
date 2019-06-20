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

function smarty_function_invoice_referencia($params, &$smarty)
{
	if(empty($params['categoryId']))
	{
		$params['categoryId'] = getCategoryParent($params['categoryId']);
	}
 $referencia = getCategoryParent($params['categoryId']);
 
 echo $referencia;
}
?>