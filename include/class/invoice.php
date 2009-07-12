<?php
class invoice {
	
    public static function get_all()
    {
		global $logger;
	    global $auth_session;

		$sql = "SELECT id FROM ".TB_PREFIX."invoices WHERE domain_id = :domain_id order by id";
		$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

        return $sth->fetchAll();

    }

	public static function getInvoiceItems($id) {
	
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
    

    /**
    * Function: are_there_any
    * 
    * Used to see if there are any invoices in the database for a given domain
    **/
    public static function are_there_any()
    {
	    global $auth_session;

		$sql = "SELECT count(*) as count FROM ".TB_PREFIX."invoices where domain_id = :domain_id limit 2";
		$sth = dbQuery($sql, ':domain_id', $auth_session->domain_id);

        $count = $sth->fetch();
        return $count['count'];
    }

    /**
    * Function getInvoiceGross
    * 
    * Used to get the gross total for a given invoice number
    **/
    public static function getInvoiceGross($invoice_id) {
        global $LANG;
        
        $sql ="SELECT SUM(gross_total) AS gross_total FROM ".TB_PREFIX."invoice_items WHERE invoice_id =  :invoice_id";
        $sth = dbQuery($sql, ':invoice_id', $invoice_id);
        $res = $sth->fetch();
        //echo "TOTAL".$res['total'];
        return $res['gross_total'];
    }
}
