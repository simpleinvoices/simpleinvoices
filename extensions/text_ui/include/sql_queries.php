<?php
class text_ui_invoice extends invoice {
    function getInvoiceItems($id) {

        $sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id";
        $sth = dbQuery($sql, ':id', $id);

        $invoiceItems = null;

        for($i=0;$invoiceItem = $sth->fetch();$i++) {

            $invoiceItem['quantity'] = $invoiceItem['quantity'];
            $invoiceItem['unit_price'] = $invoiceItem['unit_price'];
            $invoiceItem['tax_amount'] = $invoiceItem['tax_amount'];
            $invoiceItem['gross_total'] = $invoiceItem['gross_total'];
            $invoiceItem['total'] = $invoiceItem['total'];

            $sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id";
            $tth = dbQuery($sql, ':id', $invoiceItem['product_id']) or die(htmlsafe(end($dbh->errorInfo())));
            $invoiceItem['product'] = $tth->fetch();
			$attr_sql = "select 
                    CONCAT(a.display_name, '-',v.value) as display
                from
                    si_products_attributes a,
                    si_products_values v
                where
                    a.id = v.attribute_id 
                    and
                    v.id = :attr_id";
			$attr1 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_1']) or die(htmlsafe(end($dbh->errorInfo())));
            $invoiceItem['attr1'] = $attr1->fetch();
			$attr2 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_2']) or die(htmlsafe(end($dbh->errorInfo())));
            $invoiceItem['attr2'] = $attr2->fetch();
			$attr3 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_3']) or die(htmlsafe(end($dbh->errorInfo())));
            $invoiceItem['attr3'] = $attr3->fetch();
		
            $invoiceItems[$i] = $invoiceItem;
        }

        return $invoiceItems;
    }
}
?>
