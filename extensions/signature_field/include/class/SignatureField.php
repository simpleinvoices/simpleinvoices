<?php
/**
 * SignatureField class.
 * @author Rich
 *
 */
class SignatureField {

    /**
     * Add the signature field to the si_biller table if it is not already there.
     */
    public static function addSignatureField() {
        $table = TB_PREFIX . "biller";
        $field = 'signature';
        if (!checkFieldExists($table, $field)) {
            $sql = "ALTER TABLE $table ADD `$field` varchar(255) DEFAULT NULL";
            if (!($sth = dbQuery($sql))) {
                $arr = $sth->errorInfo();
                // @formatter:off
                error_log("Signaturefield - addSignatureField(): Unable to add \"$field\" to the $table table.");
                error_log("                                    : Error array[" . print_r($arr, true));
                error_log("                                    : sql[$sql]");
               // @formatter:on
            }
        }
    }
}
