<?php
class NetIncomeItem {
    public $amount;
    public $description;
    public $cflags;
    
    public function __construct($amount, $description, $cflags) {
        // @formatter:off
        $this->amount      = $amount;
        $this->description = $description;
        $this->cflags      = (isset($cflags) ? $cflags : array());
        // @formatter:on
    }
}
?>
