<?php
class invoice {
	
	function getInvoiceItems($id) {
	
		global $logger;
		$sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id order by id";
		$sth = dbQuery($sql, ':id', $id);
		
		$invoiceItems = null;
		
		for($i=0;$invoiceItem = $sth->fetch();$i++) {
		
			$invoiceItem['quantity'] = $invoiceItem['quantity'];
			$invoiceItem['unit_price'] = $invoiceItem['unit_price'];
			$invoiceItem['tax_amount'] = $invoiceItem['tax_amount'];
			$invoiceItem['gross_total'] = $invoiceItem['gross_total'];
			$invoiceItem['total'] = $invoiceItem['total'];
			
			$sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id";
			$tth = dbQuery($sql, ':id', $invoiceItem['product_id']) or die(htmlspecialchars(end($dbh->errorInfo())));
			$invoiceItem['product'] = $tth->fetch();	

			$tax = taxesGroupedForInvoiceItem($invoiceItem['id']);

			foreach ($tax as $key => $value)
			{
				$invoiceItem['tax'][$key] = $value['tax_id'];
				$logger->log('Invoice: '.$invoiceItem['invoice_id'].' Item id: '.$invoiceItem['id'].' Tax '.$key.' Tax ID: '.$value['tax_id'], Zend_Log::INFO);
			}
			$invoiceItems[$i] = $invoiceItem;
		}
		
		return $invoiceItems;
	}
}
