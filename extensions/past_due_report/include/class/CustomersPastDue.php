<?php
/**
 * Class for customer's past due calculation
 * @author Rich
 */
class CustomersPastDue {
    /**
     * Collect past dues information for each customer
     * @param string $language Language code (ex "en_US") used to set currency type.
     * @return CustInfo[] Array of past due information for customers.
     */
    public static function getCustInfo ($language) {
        $currency = new Zend_Currency($language);
        
        $domain_id = domain_id::get();
    
        // Get date for 30 days ago. Only invoices with a date prior to this date are included.
        $past_due_date = (date("Y-m-d", strtotime('-30 days')) . ' 00:00:00');
        
        $cust_info = array();
        // @formatter:off
        $sql = "SELECT id AS cid,
                       name
                FROM " . TB_PREFIX . "customers
                WHERE domain_id = :domain_id
                ORDER BY cid;";
        if ($cth = dbQuery($sql, ':domain_id', $domain_id)) {
            while ($cust_row = $cth->fetch(PDO::FETCH_ASSOC)) {
                $cid = $cust_row['cid'];
                $name = $cust_row['name'];
    
                $sql = "SELECT iv.id AS id,
                               SUM(IF(pr.status = 1, COALESCE(p.ac_amount, 0), 0)) AS paid
                       FROM " . TB_PREFIX . "invoices iv
                       LEFT JOIN " . TB_PREFIX . "preferences pr
                              ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
                       LEFT JOIN " . TB_PREFIX . "payment p
                              ON (p.ac_inv_id = iv.id AND p.domain_id = iv.domain_id)
                       WHERE iv.customer_id = :cid
                         AND iv.date        < :past_due_date
                         AND iv.domain_id   = :domain_id
                       GROUP BY id;";
                
                $payments = array();
                if (($pth = dbQuery($sql,
                                ':cid'          , $cid,
                                ':past_due_date', $past_due_date,
                                ':domain_id'    , $domain_id)) !== false) {
                    while ($py_row = $pth->fetch(PDO::FETCH_ASSOC)) {
                        $payments[$py_row['id']] = doubleval($py_row['paid']);
                    }
                }
                
                $sql = "SELECT iv.id AS id,
                               SUM(IF(pr.status = 1, COALESCE(ii.total, 0), 0)) AS billed 
                       FROM " . TB_PREFIX . "invoices iv
                       LEFT JOIN " . TB_PREFIX . "preferences pr
                              ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
                       LEFT JOIN " . TB_PREFIX . "invoice_items ii
                              ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
                       WHERE iv.customer_id = :cid
                         AND iv.date        < :past_due_date
                         AND iv.domain_id   = :domain_id
                       GROUP BY invoice_id;";

                $tot_billed = 0;
                $tot_paid   = 0;
                $inv_info   = array();
                if (($ith = dbQuery($sql, 
                                    ':cid'          , $cid,
                                    ':past_due_date', $past_due_date,
                                    ':domain_id'    , $domain_id)) !== false) {
                    while ($iv_row = $ith->fetch(PDO::FETCH_ASSOC)) {
                        $id     = $iv_row['id'];
                        $billed =  doubleval($iv_row['billed']);
                        $paid   = (empty($payments[$id]) ? 0.00 : $payments[$id]);
                        $owed   = $billed - $paid;
                        if ($owed != 0) {
                            $fmtd_billed = $currency->toCurrency(doubleval($billed));
                            $fmtd_paid   = $currency->toCurrency(doubleval($paid));
                            $fmtd_owed   = $currency->toCurrency(doubleval($owed));
                            
                            $inv_info[] = new InvInfo($id, $fmtd_billed, $fmtd_paid, $fmtd_owed);
                            
                            $tot_billed += $billed;
                            $tot_paid   += $paid;
                        }
                    }
                }
                
                if (!empty($inv_info)) {
                    $tot_owed    = $tot_billed - $tot_paid;
                    $fmtd_billed = $currency->toCurrency(doubleval($tot_billed));
                    $fmtd_paid   = $currency->toCurrency(doubleval($tot_paid));
                    $fmtd_owed   = $currency->toCurrency(doubleval($tot_owed));
                    
                    $cust_info[$cid] = new CustInfo($name, $fmtd_billed, $fmtd_paid, $fmtd_owed, $inv_info);
                }
            }
        }
        // @formatter:on
        return $cust_info;
    }

    /**
     * Get the past dues amount for an invoice
     * @param integer $cid Customer ID value.
     * @param integer $invoice_id Invoice ID value.
     * @param string $invoice_dt Date before which invoices must have been issues.
     *               Format is "yyyy-mm-dd hh:mm:ss".
     * @return number Past due amount.
     */
    public static function getCustomerPastDue($cid, $invoice_id, $invoice_dt) {
        $domain_id = domain_id::get();
    
        // @formatter:off
        $sql = "SELECT iv.id,
                       SUM(IF(pr.status = 1, COALESCE(ii.total, 0), 0)) AS billed
                FROM " . TB_PREFIX . "invoices iv
                LEFT JOIN " . TB_PREFIX . "preferences pr
                       ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
                LEFT JOIN " . TB_PREFIX . "invoice_items ii
                       ON (ii.invoice_id = iv.id AND ii.domain_id = iv.domain_id)
                WHERE iv.customer_id  = :cid
                  AND iv.id          <> :invoice_id
                  AND iv.date         < :invoice_dt
                  AND iv.domain_id    = :domain_id
                GROUP BY iv.id;";
        $sth = dbQuery($sql,
                       ':cid'       , $cid,
                       ':invoice_id', $invoice_id,
                       ':invoice_dt', $invoice_dt,
                       ':domain_id' , $domain_id);
        // @formatter:on
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $billed = 0;
        foreach ($rows as $row) {
            $billed += doubleval($row['billed']);
        }
    
        // @formatter:off
        $sql = "SELECT iv.id,
                       SUM(COALESCE(IF(pr.status = 1, p.ac_amount, 0),  0)) AS paid
                FROM " . TB_PREFIX . "invoices iv
                LEFT JOIN " . TB_PREFIX . "preferences pr
                       ON (pr.pref_id = iv.preference_id AND pr.domain_id = iv.domain_id)
                LEFT JOIN " . TB_PREFIX . "payment p
                       ON (p.ac_inv_id = iv.id AND p.domain_id = iv.domain_id)
                WHERE iv.customer_id  = :cid
                  AND iv.id          <> :invoice_id
                  AND iv.date         < :invoice_dt
                  AND iv.domain_id    = :domain_id
                GROUP BY iv.id;";
        $sth = dbQuery($sql,
                       ':cid'       , $cid,
                       ':invoice_id', $invoice_id,
                       ':invoice_dt', $invoice_dt,
                       ':domain_id' , $domain_id);
        // @formatter:on
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        $paid = 0;
        foreach ($rows as $row) {
            $paid += doubleval($row['paid']);
        }
        
        $owed = round($billed - $paid, 2);
    
        return $owed;
    }

}
