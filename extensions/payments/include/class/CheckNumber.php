<?php
class CheckNumber {
    /**
     * Add the <b>ac_check_number</b> field to the payment table
     * if needed. If added, populate it from check numbers in the
     * <b>ac_notes</b> field. Note that the check numbers must begin
     * in first position of the field and can begin with a <b>#</b> (hashtag).
     */
    public static function addNewFields() {
        $table = TB_PREFIX . "payment";

        if (checkFieldExists($table, "ac_check_number")) return;

        $sql = "ALTER TABLE `$table`
        ADD `ac_check_number` varchar(10) NOT NULL COMMENT 'Check number for CHECK payment types';";
        if (!($sth = dbQuery($sql))) {
            $arr = $sth->errorInfo();
            error_log("payment - addNewFields(): Unable to add check_number field to $table. sql[$sql]");
            error_log("                        : Error array[" . print_r($arr, true));
            return;
        }

        $domain_id = domain_id::get();
        $sql = "SELECT pmt.id,pmt.ac_notes,pmt.ac_check_number FROM " . TB_PREFIX . "payment pmt
                INNER JOIN " . TB_PREFIX . "payment_types typ ON (typ.pt_id=pmt.ac_payment_type)
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
}
