<?php
class NetIncomePayment {
    public $amount;
    public $cflags;
    public $date;
    
    public function __construct($amount, $date, $cflags) {
        $this->amount = $amount;
        $this->date = $date;
        $this->cflags = array();
        if (isset($cflags)) {
            for ($i=0;$i<10;$i++) {
                $this->cflags[$i] = substr($custom_flags,$i,1);
            }
        }
    }
}