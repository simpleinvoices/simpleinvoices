<?php
// Fails on overriding parent public static class method of same name
// Late binding absent in general in PHP
class text_ui_invoice extends invoice {
    public static function getInvoiceItems($id, $domain_id='') {

// $domain_id is a parent class member
        $domain_id = domain_id::get($domain_id);
        $sql = "SELECT * FROM ".TB_PREFIX."invoice_items WHERE invoice_id = :id AND domain_id = :domain_id";
        $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);

        $invoiceItems = null;

        for($i=0;$invoiceItem = $sth->fetch();$i++) {

            $sql = "SELECT * FROM ".TB_PREFIX."products WHERE id = :id AND domain_id = :domain_id";
            $tth = dbQuery($sql, ':id', $invoiceItem['product_id'], ':domain_id', $domain_id);
            $invoiceItem['product'] = $tth->fetch();
// a.display_name was in old schema and is now a.name
			$attr_sql = "select 
                    CONCAT(a.display_name, '-',v.value) as display
                FROM
                    ".TB_PREFIX."products_attributes a INNER JOIN 
                    ".TB_PREFIX."products_values v ON (a.id = v.attribute_id)
                WHERE
                    v.id = :attr_id";

// $invoiceItem['attribute_#'] (# = 1,2,3) was in old schema

			$attr1 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_1']);
            $invoiceItem['attr1'] = $attr1->fetch();
			$attr2 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_2']);
            $invoiceItem['attr2'] = $attr2->fetch();
			$attr3 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_3']);
            $invoiceItem['attr3'] = $attr3->fetch();
		
            $invoiceItems[$i] = $invoiceItem;
        }

        return $invoiceItems;
    }
}
?>
