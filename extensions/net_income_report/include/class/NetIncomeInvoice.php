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

    /**
     * Adjust the amount paid to exclude billed items that were flagged
     * as non-income. This is typically items that were pre-paid for by
     * the client so the cost is straight pass through. The only income
     * on these items will be in the shipping and handling changes assessed.
     * Example: Invoice was for $527.50. Of this, $500 was cost of computer,
     * and $27.50 was shipping and handling. The client paid $505 up front in
     * in the month prior to the beginning of the report selection period.
     * The TV was delivered and the final $22.50 was paid during the report
     * period. To the <b>total_payments<b> is $527.50, the <b>total_amount</b>
     * is $27.50 (income amount), the <b>total_period_payments<b> is $22.50.
     * @example For the report we show:
     *     Invoice Total:          $27.50 (does not include non-income amount)  $this->total_amount
     *     Total Paid:             $27.50 (include pre-period and post)         $this->total_payments up to $this->total_amount
     *     Total Paid This Period: $22.50 (net_income for this period)          $this->total_period_payments max of $this->total_payments
     */
    public function adjustPymtsForNonIncome() {
        if ($this->total_payments > $this->total_amount) $this->total_payments = $this->total_amount;
        if ($this->total_period_payments > $this->total_payments) $this->total_period_payments = $this->total_payments;
    }
}
    