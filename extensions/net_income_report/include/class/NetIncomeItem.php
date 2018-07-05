<?php
class NetIncomeItem {
    public $amount;
    public $description;
    public $cflags;
    public $non_inc_amt;
    
    public function __construct($amount, $description, $cflags) {
        // @formatter:off
        $this->amount      = $amount;
        $this->description = $description;
        $this->non_inc_amt = 0;
        $this->cflags = array();
        if (isset($cflags)) {
            for ($i=0;$i<10;$i++) {
                $this->cflags[$i] = substr($cflags,$i,1);
            }
        }
        // @formatter:on
    }
}
