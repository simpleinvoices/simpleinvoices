<?php
class CIS3000
{
	public static function insertInvoiceItem_payment($invoice_id,$quantity='1',$product_id='6',$line_number,$line_item_tax_id,$description="", $unit_price="",$date,$type) {

		global $logger;
		global $LANG;
		//do taxes

		
		$tax_total = getTaxesPerLineItem($line_item_tax_id,$quantity, $unit_price);

		$logger->log(' ', Zend_Log::INFO);
		$logger->log(' ', Zend_Log::INFO);
		$logger->log('Invoice: '.$invoice_id.' Tax '.$line_item_tax_id.' for line item '.$line_number.': '.$tax_total, Zend_Log::INFO);
		$logger->log('Description: '.$description, Zend_Log::INFO);
		$logger->log(' ', Zend_Log::INFO);

		//line item gross total
		$gross_total = $unit_price  * $quantity;

		//line item total
		$total = $gross_total + $tax_total;	

		//Remove jquery auto-fill description - refer jquery.conf.js.tpl autofill section
		if ($description == $LANG['description'])
		{	
			$description ="";
		}


		if ($db_server == 'mysql' && !_invoice_items_check_fk(
			$invoice_id, $product_id, $tax['tax_id'])) {
			return null;
		}
		$sql = "INSERT INTO ".TB_PREFIX."invoice_items 
				(
					invoice_id, 
					quantity, 
					product_id, 
					unit_price, 
					tax_amount, 
					gross_total, 
					description, 
					total,
					date,
					type
				) 
				VALUES 
				(
					:invoice_id, 
					:quantity, 
					:product_id, 
					:unit_price, 
					:tax_amount, 
					:gross_total, 
					:description, 
					:total,
					:date,
					:type
				)";

		//echo $sql;
		dbQuery($sql,
			':invoice_id', $invoice_id,
			':quantity', $quantity,
			':product_id', $product_id,
			':unit_price', $unit_price,
		//	':tax_id', $tax[tax_id],
		//	':tax_percentage', $tax[tax_percentage],
			':tax_amount', $tax_total,
			':gross_total', $gross_total,
			':description', $description,
			':total', $total,
			':date', $date,
			':type', $type
			);
		
		invoice_item_tax(lastInsertId(),$line_item_tax_id,$unit_price,$quantity,"insert");

		//TODO fix this
		return true;
	}


	public static function getActiveCustomers() {
		global $LANG;
		global $dbh;
		global $db_server;
		global $auth_session;
		
		
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled != 0 AND is_sub_contractor != '1' AND domain_id = :domain_id ORDER BY name";
		$sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

		return $sth->fetchAll();
	}
	public static function getActiveSubContractors() {
		global $LANG;
		global $dbh;
		global $db_server;
		global $auth_session;
		
		
		$sql = "SELECT * FROM ".TB_PREFIX."customers WHERE enabled != '0' AND is_sub_contractor = '1' AND domain_id = :domain_id ORDER BY name";
		$sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

		return $sth->fetchAll();
	}
}
?>
