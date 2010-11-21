<?php

class payment
{

    public $start_date;
    public $end_date;
    public $filter;
    public $online_payment_id;
    public $domain_id;

    public function count()
    {
        global $auth_session;

        $domain_id = domain_id::get($this->domain_id);

        if($this->filter == "online_payment_id")
        {
            $where = "and ap.online_payment_id = '$this->online_payment_id'";
        }

        $sql = "SELECT 
                    COUNT(DISTINCT ap.id) AS count
                FROM 
                    ".TB_PREFIX."payment ap
                WHERE 
                    domain_id = :domain_id
        $where";

        $sth = dbQuery($sql, ':domain_id', $domain_id);
        $payment = $sth->fetch();

        return $payment['count'];
    }


    public function select_all() {

        global $auth_session;
        $domain_id = domain_id::get($this->domain_id);
        
        if($this->filter == "date")
        {
            $where = "and ap.ac_date between '$this->start_date' and '$this->end_date'";
        }
        if($this->filter == "online_payment_id")
        {
            $where = "and ap.online_payment_id = '$this->online_payment_id'";

        }

        $sql = "SELECT 
                    ap.*, 
                    iv.index_id as index_id,
                    iv.id as invoice_id,
                    pref.pref_description as preference,
                    pt.pt_description as type,
                    c.name as cname, 
                    b.name as bname
                from 
                    ".TB_PREFIX."payment ap, 
                    ".TB_PREFIX."payment_types pt, 
                    ".TB_PREFIX."invoices iv, 
                    ".TB_PREFIX."customers c, 
                    ".TB_PREFIX."biller b,
                    ".TB_PREFIX."preferences pref
                WHERE 
                    ap.ac_inv_id = iv.id 
                    AND 
                    ap.ac_payment_type = pt.pt_id 
                    AND 
                    iv.customer_id = c.id 
                    and 
                    iv.biller_id = b.id 
                    and 
                    iv.preference_id = pref.pref_id
                    and
                    ap.domain_id = :domain_id
                    $where
                ORDER BY ap.id DESC";
        
        return dbQuery($sql,':domain_id',$domain_id);
    }

    public function insert()
    {
        global $db;
        global $auth_session;

        $domain_id = domain_id::get($this->domain_id);
        
        $sql = "INSERT INTO ".TB_PREFIX."payment (
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
        $sth = $db->query($sql,
            ':ac_inv_id',$this->ac_inv_id,
            ':ac_amount',$this->ac_amount,
            ':ac_notes',$this->ac_notes,
            ':ac_date',$this->ac_date,
            ':ac_payment_type',$this->ac_payment_type,
            ':online_payment_id',$this->online_payment_id,
            ':domain_id',$domain_id 
        ) or die();
    
       return $sth;
    }

}
