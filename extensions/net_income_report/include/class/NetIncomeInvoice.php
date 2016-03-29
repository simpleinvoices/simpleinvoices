<?php
require_once 'extensions/net_income_report/include/class/NetIncomeItem.php';
require_once 'extensions/net_income_report/include/class/NetIncomePayment.php';

class NetIncomeInvoice {
    public $customer;
    public $date;
    public $id;
    public $items;
    public $number;
    public $pymts;
    public $total_amount;
    public $total_payments;
    public $total_period_payments;
    
    public function __construct($id, $number, $date, $customer) {
        // @formatter:off
        $this->id                    = $id;
        $this->number                = $number;
        $this->date                  = $date;
        $this->customer              = $customer;
        $this->total_invoice         = 0;
        $this->total_payments        = 0;
        $this->total_period_payments = 0;
        $this->items                 = array();
        $this->pymts                 = array();
        // @formatter:on
    }
    
    public function addItem($amount, $description, $cflags) {
        $this->items[] = new NetIncomeItem($amount, $description, $cflags);
        $this->total_amount += $amount;
    }
    
    public function addPayment($amount, $date, $in_period) {
        $this->pymts[] = new NetIncomePayment($amount, $date);
        $this->total_payments += $amount;
        if ($in_period) $this->total_period_payments += $amount;
    }
}
    