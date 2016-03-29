<?php
require_once 'extensions/net_income_report/include/class/NetIncomeInvoice.php';

class NetIncomeReport {
    public $domain_id;

    public function select_rpt_items($start_date, $stop_date, $non_income_item_flag) {
        $custom_flags_enabled = isExtensionEnabled('custom_flags');
        $domain_id = domain_id::get($this->domain_id);
        
        if ($custom_flags_enabled && isset($non_income_item_flag) && $non_income_item_flag > 0) {
            // Make a regex string that tests for "0" in the specified position
            $flgs = array('.','.','.','.','.','.','.','.','.','.');
            $flgs[$non_income_item_flag - 1] = '0';
            $exp = '^';
            foreach($flgs as $flg) {
                $exp .= $flg;
            }
        } else {
            $exp = '.*'; // Basically ignores custom flag setting
        }
        
        // Find all invoices that recvieved payments in this reporting period.
        $iv_ids = array();
        // @formatter:off
        $sql = "SELECT ac_inv_id
                FROM " . TB_PREFIX . "payment
                WHERE ac_date BETWEEN '$start_date' AND '$stop_date'
                  AND domain_id = :domain_id
                ORDER BY ac_inv_id ASC;";
        // @formatter:on
        $sth = dbQuery($sql, ':domain_id', $domain_id);
        $last_inv_id = 0;
        while ($ids = $sth->fetch(PDO::FETCH_ASSOC)) {
            $curr_inv_id = $ids['ac_inv_id'];
            if ($last_inv_id != $curr_inv_id) {
                $last_inv_id = $curr_inv_id;
                $iv_ids[] = $curr_inv_id;
            }
        }

        // Get all invoices that had payments made in the current reporting period.
        $invoices = array();
        foreach($iv_ids as $id) {
            // @formatter:off
            $sql = "SELECT iv.id, iv.index_id as iv_number, iv.date as iv_date, cu.name as customer
                    FROM " . TB_PREFIX . "invoices  AS iv
                    JOIN " . TB_PREFIX . "customers AS cu ON (cu.id = iv.customer_id AND cu.domain_id = iv.domain_id)
                    WHERE iv.id        = :id
                      AND iv.domain_id = :domain_id;";
            // @formatter:on

            $sth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
            while ($iv = $sth->fetch(PDO::FETCH_ASSOC)) {

                // Create an invoice object for the report. This object holds the payments and
                // invoice items for the invoice. We know that a payment to this invoice was
                // made in this reporting period. However, it is possible that not all payments
                // were made in this reporting period. So we will keep the payment info so we can
                // report only the payment that were made in this period.
                $invoice = new NetIncomeInvoice($id, $iv['iv_number'], $iv['iv_date'], $iv['customer']);

                // Get all the payments made for this invoice. We do this so we can calculate what
                // if any payments are left for the invoice, as well as have payment detail to
                // include in the report.
                // @formatter:off
                $sql = "SELECT ac_amount, ac_date
                        FROM " . TB_PREFIX . "payment
                        WHERE ac_inv_id = :id
                          AND domain_id = :domain_id
                        ORDER BY ac_date ASC;";
                // @formatter:on

                $tth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
                while ($py = $tth->fetch(PDO::FETCH_ASSOC)) {
                    $in_period = ($start_date <= $py['ac_date'] &&
                                  $stop_date  >= $py['ac_date']);
                    $invoice->addPayment($py['ac_amount'], $py['ac_date'], $in_period);
                }
                
                // Now get all the invoice items with the exception of those flagged
                // as non-income items provided the option to exclude them was specified.
                // @formatter:off
                $sql = "SELECT ii.total as amount,
                               pr.description as description" .
                               ($custom_flags_enabled ? ", pr.custom_flags " :" ").
                       "FROM " . TB_PREFIX . "invoice_items AS ii
                        JOIN " . TB_PREFIX . "products      AS pr ON (pr.id = ii.product_id AND pr.domain_id = ii.domain_id)
                        WHERE ii.invoice_id = :id
                          AND ii.domain_id = :domain_id
                          AND " . ($custom_flags_enabled ? "pr.custom_flags REGEXP '$exp' " : "") .
                       "ORDER BY ii.invoice_id, pr.description;";
                // @formatter:on

                               $tth = dbQuery($sql, ':id', $id, ':domain_id', $domain_id);
                while ($py = $tth->fetch(PDO::FETCH_ASSOC)) {
                    $invoice->addItem($py['amount'], $py['description'],
                                      ($custom_flags_enabled ? $py['custom_flags'] : NULL));
                }
                $invoices[] = $invoice;
            }
        }
        return $invoices;
    }
}
