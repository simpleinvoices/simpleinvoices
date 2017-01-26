<?php
/**
 * CustInfo class for past due invoices.
 * @author Rich
 */
class CustInfo {
    public $name;
    public $billed;
    public $paid;
    public $owed;
    public $inv_info;

    public function __construct($name, $billed, $paid, $owed, $inv_info) {
        // @formatter:off
        $this->name     = $name;
        $this->billed   = $billed;
        $this->paid     = $paid;
        $this->owed     = $owed;
        $this->inv_info = $inv_info;
        // @formatter:on
    }
}
