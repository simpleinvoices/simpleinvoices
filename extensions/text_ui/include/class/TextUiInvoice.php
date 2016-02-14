<?php
class TextUiInvoice {

    public static function getInvoiceItems($domain_id = '') {
        // $domain_id is a parent class member
        $domain_id = domain_id::get($domain_id);
        $last_id = (isset($_SESSION['text_ui_last_index_id']) ? $_SESSION['text_ui_last_index_id'] : 0);
        $id = 0;
        $cnt = 0;
        $invoiceItems = array();
        
        $sql = "SELECT id, index_id FROM " . TB_PREFIX . "invoices 
                WHERE domain_id = :domain_id
                ORDER BY index_id;";
        if ($sth = dbQuery($sql, ":domain_id", $domain_id)) {
            $found = false;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $cnt++;
                if (!$found && $row['index_id'] > $last_id) {
                    $_SESSION['text_ui_last_index_id'] = $row['index_id'];
                    $id = $row['id'];
                    $found = true;
                }
            }
        }
        
        $invoiceItems[] = $cnt;
        if ($id != 0) {
            // @formatter:off
            $sql = "SELECT * FROM " . TB_PREFIX . "invoice_items
                    WHERE invoice_id = :id
                      AND domain_id  = :domain_id";
            $sth = dbQuery($sql, ':id'       , $id,
                                 ':domain_id', $domain_id);
            // @formatter:on
            
            while ($invoiceItem = $sth->fetch()) {
                // @formatter:off
                $sql = "SELECT * FROM " . TB_PREFIX . "products
                        WHERE id = :id AND domain_id = :domain_id";
                $tth = dbQuery($sql, ':id'       , $invoiceItem['product_id'],
                                     ':domain_id', $domain_id);
                $invoiceItem['product'] = $tth->fetch();
                
                $attr_sql = "SELECT CONCAT(a.display_name, '-',v.value) AS display
                             FROM " . TB_PREFIX . "products_attributes a
                             INNER JOIN " . TB_PREFIX . "products_values v ON (a.id = v.attribute_id)
                             WHERE v.id = :attr_id";
                
                $attr1 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_1']);
                $invoiceItem['attr1'] = $attr1->fetch();
    
                $attr2 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_2']);
                $invoiceItem['attr2'] = $attr2->fetch();
    
                $attr3 = dbQuery($attr_sql, ':attr_id', $invoiceItem['attribute_3']);
                $invoiceItem['attr3'] = $attr3->fetch();
                
                $invoiceItems[] = $invoiceItem;
                // @formatter:on
            }
        }
        return $invoiceItems;
    }
}
