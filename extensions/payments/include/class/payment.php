<?php
class payment {
    public $ac_amount;
    public $ac_check_number;
    public $ac_date;
    public $ac_inv_id;
    public $ac_notes;
    public $ac_payment_type;
    public $domain_id;
    public $end_date;
    public $filter;
    public $online_payment_id;
    public $start_date;

    public function __construct($f = 0) {
        $this->domain_id = domain_id::get($this->domain_id);
    }

    /**
     * Add the <b>ac_check_number</b> field to the payment table
     * if needed. If added, populate it from check numbers in the
     * <b>ac_notes</b> field. Note that the check numbers must begin
     * in first position of the field and can begin with a <b>#</b> (hashtag).
     */
    public static function addNewFields() {
        $table = TB_PREFIX . "payment";

        if (checkFieldExists($table, "ac_check_number")) {
            return;
        }

        $sql = "ALTER TABLE `$table`
                    ADD `ac_check_number` varchar(10) NOT NULL COMMENT 'Check number for CHECK payment types';";
        if (!($sth = dbQuery($sql))) {
            $arr = $sth->errorInfo();
            error_log("payment - addNewFields(): Unable to add check_number field to $table. sql[$sql]");
            error_log("                        : Error array[" . print_r($arr, true));
            return;
        }

        $domain_id = domain_id::get();
        $sql = "SELECT pmt.id,pmt.ac_notes,pmt.ac_check_number FROM si_payment pmt
                INNER JOIN si_payment_types typ ON (typ.pt_id=pmt.ac_payment_type)
                WHERE pmt.domain_id=:domain_id
                  AND LCASE(typ.pt_description)='check'
                  AND MID(pmt.ac_notes,1,1) IN ('#','1','2','3','4','5','6','7','8','9','0');";
        $sth = dbQuery($sql, ":domain_id", $domain_id);
        $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $note = $row['ac_notes'];
            $beg = 0;
            if (substr($note, 0, 1) == '#') $beg = 1;
            $len = strpos($note, ' ');
            if ($len == 0) {
                $clear_it = true;
                $len = strlen($row['ac_notes']);
            } else {
                $len -= $beg;
                $clear_it = false;
            }
            $check_num = substr($note, $beg, $len);
            if ($clear_it) {
                $note = '';
            } else {
                $beg += $len + 1;
                $note = substr($note, $beg);
            }
            $sql = "UPDATE $table
                    SET ac_check_number = :check_num,
                        ac_notes = :note
                    WHERE id=:id";
            dbQuery($sql, ':check_num', $check_num, ':note', $note, ':id', $row['id']);
        }
    }

    public function count() {
        $where = '';

        if ($this->filter == "online_payment_id") {
            $where .= " AND ap.online_payment_id = '$this->online_payment_id'";
        }

        // @formatter:off
        $sql = "SELECT COUNT(DISTINCT ap.id) AS count
                FROM " . TB_PREFIX . "payment ap
                WHERE domain_id = :domain_id
                      $where";
        // @formatter:on

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
        $sql = "SELECT ap.*,
                       iv.index_id           as index_id,
                       iv.id                 as invoice_id,
                       pref.pref_description as preference,
                       pt.pt_description     as type,
                       c.name                as cname,
                       b.name                as bname
                FROM " . TB_PREFIX . "payment ap
                LEFT JOIN " . TB_PREFIX . "payment_types pt ON (ap.ac_payment_type = pt.pt_id)
                LEFT JOIN " . TB_PREFIX . "invoices iv      ON (ap.ac_inv_id       = iv.id        AND ap.domain_id = iv.domain_id)
                LEFT JOIN " . TB_PREFIX . "customers c      ON (iv.customer_id     = c.id         AND iv.domain_id = c.domain_id)
                LEFT JOIN " . TB_PREFIX . "biller b         ON (iv.biller_id       = b.id         AND iv.domain_id = b.domain_id)
                LEFT JOIN " . TB_PREFIX . "preferences pref ON (iv.preference_id   = pref.pref_id AND iv.domain_id = pref.domain_id)
                WHERE ap.domain_id = :domain_id
                      $where
                ORDER BY ap.id DESC";
        // @formatter:on
        return dbQuery($sql, ':domain_id', $this->domain_id);
    }

    public function insert() {
        // @formatter:off
        $sql = "INSERT INTO " . TB_PREFIX . "payment (
                    ac_inv_id,
                    ac_amount,
                    ac_notes,
                    ac_date,
                    ac_payment_type,
                    ac_check_number,
                    online_payment_id,
                    domain_id
                ) VALUES (
                    :ac_inv_id,
                    :ac_amount,
                    :ac_notes,
                    :ac_date,
                    :ac_payment_type,
                    :ac_check_number,
                    :online_payment_id,
                    :domain_id
                )";
        $sth = dbQuery($sql,
                       ':ac_inv_id'        , $this->ac_inv_id,
                       ':ac_amount'        , $this->ac_amount,
                       ':ac_notes'         , $this->ac_notes,
                       ':ac_date'          , $this->ac_date,
                       ':ac_payment_type'  , $this->ac_payment_type,
                       ':ac_check_number'  , $this->ac_check_number,
                       ':online_payment_id', $this->online_payment_id,
                       ':domain_id'        , $this->domain_id);
        // @formatter:on
        return $sth;
    }
}
