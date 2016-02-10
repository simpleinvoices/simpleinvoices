<?php
class payment {
    public $start_date;
    public $end_date;
    public $filter;
    public $online_payment_id;
    public $domain_id;

    public function __construct() {
        $this->domain_id = domain_id::get($this->domain_id);
    }

    public function count() {
        $where = '';
        
        if ($this->filter == "online_payment_id") {
            $where .= " AND ap.online_payment_id = '$this->online_payment_id'";
        }
        
        $sql = "SELECT 
                    COUNT(DISTINCT ap.id) AS count
                FROM 
                    " . TB_PREFIX . "payment ap
                WHERE 
                    domain_id = :domain_id
                    $where";
        
        $sth = dbQuery($sql, ':domain_id', $this->domain_id);
        $payment = $sth->fetch();
        
        return $payment['count'];
    }

    public function select_all() {
        $where = '';
        if ($this->filter == "date") {
            $where .= " AND ap.ac_date BETWEEN '$this->start_date' AND '$this->end_date'";
        }
        if ($this->filter == "online_payment_id") {
            $where .= " AND ap.online_payment_id = '$this->online_payment_id'";
        }
        
        // @formatter:off
        $sql = "SELECT 
                    ap.*, 
                    iv.index_id as index_id,
                    iv.id as invoice_id,
                    pref.pref_description as preference,
                    pt.pt_description as type,
                    c.name as cname, 
                    b.name as bname
                FROM " . TB_PREFIX . "payment ap
                LEFT JOIN " . TB_PREFIX . "payment_types pt ON (ap.ac_payment_type = pt.pt_id)
                LEFT JOIN " . TB_PREFIX . "invoices iv      ON (ap.ac_inv_id       = iv.id        AND ap.domain_id = iv.domain_id)
                LEFT JOIN " . TB_PREFIX . "customers c      ON (iv.customer_id     = c.id         AND iv.domain_id = c.domain_id)
                LEFT JOIN " . TB_PREFIX . "biller b         ON (iv.biller_id       = b.id         AND iv.domain_id = b.domain_id)
                LEFT JOIN " . TB_PREFIX . "preferences pref ON (iv.preference_id   = pref.pref_id AND iv.domain_id = pref.domain_id)
                WHERE 
                    ap.domain_id = :domain_id
                    $where
                ORDER BY ap.id DESC";
        // @formatter:on
        return dbQuery($sql, ':domain_id', $this->domain_id);
    }

    public function insert() {
        $sql = "INSERT INTO " . TB_PREFIX . "payment (
            ac_inv_id,
            ac_amount,
            ac_notes,
            ac_date,
            ac_payment_type,
            online_payment_id,
            domain_id
        ) VALUES (
            :ac_inv_id,
            :ac_amount,
            :ac_notes,
            :ac_date,
            :ac_payment_type,
            :online_payment_id,
            :domain_id
        )";
        // @formatter:off
        $sth = dbQuery($sql,
                       ':ac_inv_id'        ,$this->ac_inv_id,
                       ':ac_amount'        ,$this->ac_amount,
                       ':ac_notes'         ,$this->ac_notes,
                       ':ac_date'          ,$this->ac_date,
                       ':ac_payment_type'  ,$this->ac_payment_type,
                       ':online_payment_id',$this->online_payment_id,
                       ':domain_id'        , $this->domain_id);
        // @formatter:on
        return $sth;
    }
}
