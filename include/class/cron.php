<?php
class cron {

    public static function insert() {
        $today = date('Y-m-d');
        $sql = "INSERT INTO " . TB_PREFIX . "cron (
                        domain_id,
                        invoice_id,
                        start_date,
                        end_date,
                        recurrence,
                        recurrence_type,
                        email_biller,
                        email_customer)
                VALUES (
                        :domain_id,
                        :invoice_id,
                        :start_date,
                        :end_date,
                        :recurrence,
                        :recurrence_type,
                        :email_biller,
                        :email_customer)";
        $sth = dbQuery($sql, ':domain_id'      , domain_id::get(),
                             ':invoice_id'     , $_POST['invoice_id'],
                             ':start_date'     , $_POST['start_date'],
                             ':end_date'       , $_POST['end_date'],
                             ':recurrence'     , $_POST['recurrence'],
                             ':recurrence_type', $_POST['recurrence_type'],
                             ':email_biller'   , $_POST['email_biller'],
                             ':email_customer' , $_POST['email_customer']);
        return $sth;
    }

    public static function update() {
        $sql = "UPDATE " . TB_PREFIX . "cron
                SET invoice_id      = :invoice_id,
                    start_date      = :start_date,
                    end_date        = :end_date,
                    recurrence      = :recurrence,
                    recurrence_type = :recurrence_type,
                    email_biller    = :email_biller,
                    email_customer  = :email_customer
                WHERE id        = :id
                  AND domain_id = :domain_id";
        $sth = dbQuery($sql, ':id'             , $_GET['id'],
                             ':domain_id'      , domain_id::get(),
                             ':invoice_id'     , $_POST['invoice_id'],
                             ':start_date'     , $_POST['start_date'],
                             ':end_date'       , $_POST['end_date'],
                             ':recurrence'     , $_POST['recurrence'],
                             ':recurrence_type', $_POST['recurrence_type'],
                             ':email_biller'   , $_POST['email_biller'],
                             ':email_customer' , $_POST['email_customer']);
        return $sth;
    }

    public static function delete() {
    }

    public static function select_all($type, $sort, $dir, $rp, $page) {
        global $LANG;
        $valid_search_fields = array('iv.id', 'b.name', 'cron.id', 'aging');

        $start = (($page - 1) * $rp);
        $limit = "LIMIT " . $start . ", " . $rp;

        $where = "";
        $query = isset($_POST['query']) ? $_POST['query'] : null;
        $qtype = isset($_POST['qtype']) ? $_POST['qtype'] : null;
        if (!empty($qtype) && !empty($query)) {
            if (in_array($qtype, $valid_search_fields)) {
                $where = " AND $qtype LIKE :query ";
            }
        }

        if (empty($sort)) $sort = "id";

        if ($type == "count" || $type == "no_limit") $limit = "";

        $sql = "SELECT cron.* , cron.id as cron_id,
                       (SELECT CONCAT(pf.pref_description,' ',iv.index_id)) as index_name
                FROM " . TB_PREFIX . "cron cron
                INNER JOIN " . TB_PREFIX . "invoices    iv ON (cron.invoice_id  = iv.id      AND cron.domain_id = iv.domain_id)
                INNER JOIN " . TB_PREFIX . "preferences pf ON (iv.preference_id = pf.pref_id AND iv.domain_id   = pf.domain_id)
                WHERE cron.domain_id = :domain_id
                      $where
                GROUP BY cron.id
                ORDER BY $sort $dir
                $limit";
        if (empty($query)) {
            $sth = dbQuery($sql, ':domain_id', domain_id::get());
        } else {
            $sth = dbQuery($sql, ':domain_id', domain_id::get(), ':query', "%$query%");
        }

        if ($type == "count") {
            return $sth->rowCount ();
        } else {
            return $sth->fetchAll ();
        }
    }

    public static function select_crons_to_run() {
        // Use this function to select crons that need to run each day across all domain_id values
        $sql = "SELECT cron.* , cron.id as cron_id,
                       (SELECT CONCAT(pf.pref_description,' ',iv.index_id)) as index_name
                FROM " . TB_PREFIX . "cron cron
                INNER JOIN " . TB_PREFIX . "invoices iv    ON (cron.invoice_id  = iv.id      AND cron.domain_id = iv.domain_id)
                INNER JOIN " . TB_PREFIX . "preferences pf ON (iv.preference_id = pf.pref_id AND iv.domain_id   = pf.domain_id)
                WHERE NOW() BETWEEN cron.start_date AND cron.end_date
                GROUP BY cron.id, cron.domain_id";
        $sth = dbQuery($sql);
        return $sth->fetchAll();
    }

    public static function select() {
        global $LANG;

        $sql = "SELECT cron.*, (SELECT CONCAT(pf.pref_description,' ',iv.index_id)) as index_name
                FROM " . TB_PREFIX . "cron cron
                INNER JOIN " . TB_PREFIX . "invoices iv    ON (cron.invoice_id  = iv.id      AND cron.domain_id = iv.domain_id)
                INNER JOIN " . TB_PREFIX . "preferences pf ON (iv.preference_id = pf.pref_id AND iv.domain_id   = pf.domain_id)
                WHERE cron.domain_id = :domain_id
                  AND cron.id = :id;";
        $sth = dbQuery($sql, ':domain_id', domain_id::get(), ':id', $_GET['id']);
        return $sth->fetch ();
    }

    private function getEmailSendAddresses($src_array, $customer_email, $biller_email) {
        $email_to_addresses = Array ();
        if ($src_array['email_customer'] == "1") $email_to_addresses[] = $customer_email;
        if ($src_array['email_biller']   == "1") $email_to_addresses[] = $biller_email;
        return implode(";", $email_to_addresses);
    }

    public static function run() {
        global $db;

        $today = date('Y-m-d');
        $data  = self::select_crons_to_run();

        $result['cron_message'] = "Cron started";
        $number_of_crons_run = "0";

        $cron_log = new cronlog ();
        $cron_log->run_date = $today;

        foreach($data as $key => $value) {
            $cron_log->cron_id   = $value['id'];
            $cron_log->domain_id = $domain_id = $value['domain_id'];
            $check_cron_log      = $cron_log->check ();

            $i = "0";
            if ($check_cron_log == 0) {
                // only proceed if cron has not been run for today
                $run_cron   = false;
                $start_date = date('Y-m-d', strtotime($value['start_date']));
                $end_date   = $value['end_date'];

                // Seconds in a day = 60 * 60 * 24 = 86400
                $diff = number_format((strtotime($today) - strtotime($start_date)) / 86400, 0);

                // only check if diff is positive
                if (($diff >= 0) && ($end_date == "" || $end_date >= $today)) {
                    if ($value['recurrence_type'] == 'day') {
                        $modulus = $diff % $value['recurrence'];
                        if ($modulus == 0) {
                            $run_cron = true;
                        }
                    }

                    if ($value['recurrence_type'] == 'week') {
                        $period  = 7 * $value['recurrence'];
                        $modulus = $diff % $period;
                        if ($modulus == 0) {
                            $run_cron = true;
                        }
                    }

                    if ($value['recurrence_type'] == 'month') {
                        $start_day   = date('d', strtotime($value['start_date']));
                        $start_month = date('m', strtotime($value['start_date']));
                        $start_year  = date('Y', strtotime($value['start_date']));
                        $today_day   = date('d');
                        $today_month = date('m');
                        $today_year  = date('Y');

                        $months  = ($today_month - $start_month) + 12 * ($today_year - $start_year);
                        $modulus = $months % $value['recurrence'];
                        if (($modulus == 0) && ($start_day == $today_day)) {
                            $run_cron = true;
                        }
                    }

                    if ($value['recurrence_type'] == 'year') {
                        $start_day   = date('d', strtotime($value['start_date']));
                        $start_month = date('m', strtotime($value['start_date']));
                        $start_year  = date('Y', strtotime($value['start_date']));
                        $today_day   = date('d');
                        $today_month = date('m');
                        $today_year  = date('Y');

                        $years   = $today_year - $start_year;
                        $modulus = $years % $value['recurrence'];
                        if (($modulus == 0) && ($start_day == $today_day) && ($start_month == $today_month)) {
                            $run_cron = true;
                        }
                    }

                    // run the recurrence for this invoice
                    if ($run_cron) {
                        $number_of_crons_run ++;
                        $result['cron_message_' . $value['cron_id']] = "Cron ID: " . $value['cron_id'] . " - Cron for " . $value['index_name'] . " with start date of " . $value['start_date'] . ", end date of " . $value['end_date'] . " where it runs each " . $value['recurrence'] . " " . $value['recurrence_type'] . " was run today :: Info diff=" . $diff;
                        $i++;

                        $ni = new invoice ();
                        $ni->id         = $value['invoice_id'];
                        $ni->domain_id  = $domain_id;
                        // $domain_id gets propagated from invoice to be copied from
                        $new_invoice_id = $ni->recur ();

                        $cron_log->insert ();

                        $invoiceobj = new invoice ();
                        $invoice               = $invoiceobj->select($new_invoice_id, $domain_id);
                        $preference            = getPreference($invoice['preference_id'], $domain_id);
                        $biller                = getBiller($invoice['biller_id'], $domain_id);
                        $customer              = getCustomer($invoice['customer_id'], $domain_id);
                        $spc2us_pref           = str_replace(" ", "_", $invoice['index_name']);
                        $pdf_file_name_invoice = $spc2us_pref . ".pdf";

                        // email invoice
                        if (($value['email_biller'] == "1") || ($value['email_customer'] == "1")) {
                            $export = new export ();
                            $export->domain_id     = $domain_id;
                            $export->format        = "pdf";
                            $export->file_location = 'file';
                            $export->module        = 'invoice';
                            $export->id            = $invoice['id'];
                            $export->execute ();

                            // $attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);
                            $email = new email ();
                            $email->domain_id = $domain_id;
                            $email->format    = 'cron_invoice';

                            $email_body = new email_body ();
                            $email_body->email_type    = 'cron_invoice';
                            $email_body->customer_name = $customer['name'];
                            $email_body->invoice_name  = $invoice['index_name'];
                            $email_body->biller_name   = $biller['name'];

                            $email->notes              = $email_body->create ();
                            $email->from               = $biller['email'];
                            $email->from_friendly      = $biller['name'];
                            $email->to                 = $this->getEmailSendAddresses($value, $customer['email'], $biller['email']);
                            $email->invoice_name       = $invoice['index_name'];
                            $email->subject            = $email->set_subject ();
                            $email->attachment         = $pdf_file_name_invoice;
                            $result['email_message']  = $email->send ();
                        }

                        // Check that all details are OK before doing the eway payment
                        $eway_check = new eway ();
                        $eway_check->domain_id  = $domain_id;
                        $eway_check->invoice    = $invoice;
                        $eway_check->customer   = $customer;
                        $eway_check->biller     = $biller;
                        $eway_check->preference = $preference;
                        $eway_pre_check         = $eway_check->pre_check ();

                        // do eway payment
                        if ($eway_pre_check == 'true') {
                            $eway            = new eway ();
                            $eway->domain_id = $domain_id;
                            $eway->invoice   = $invoice;
                            $eway->biller    = $biller;
                            $eway->customer  = $customer;
                            $payment_done    = $eway->payment ();
                            $payment_id      = $db->lastInsertID ();

                            $pdf_file_name_receipt = 'payment' . $payment_id . '.pdf';
                            if ($payment_done == 'true') {
                                // do email of receipt to biller and customer
                                if (($value['email_biller'] == "1") || ($value['email_customer'] == "1")) {
                                     // If you want a new copy of the invoice being emailed to the customer
                                     // use this code
                                    $export_rec = new export ();
                                    $export_rec->domain_id     = $domain_id;
                                    $export_rec->format        = "pdf";
                                    $export_rec->file_location = 'file';
                                    $export_rec->module        = 'invoice';
                                    $export_rec->id            = $invoice['id'];
                                    $export_rec->execute ();

                                    $email_rec = new email ();
                                    $email_rec->domain_id = $domain_id;
                                    $email_rec->format    = 'cron_invoice';

                                    $email_body_rec = new email_body ();
                                    $email_body_rec->email_type    = 'cron_invoice_receipt';
                                    $email_body_rec->customer_name = $customer['name'];
                                    $email_body_rec->invoice_name  = $invoice['index_name'];
                                    $email_body_rec->biller_name   = $biller['name'];

                                    $email_rec->notes         = $email_body_rec->create ();
                                    $email_rec->from          = $biller['email'];
                                    $email_rec->from_friendly = $biller['name'];
                                    $email_rec->to            = $this->getEmailSendAddresses($value, $customer['email'], $biller['email']);
                                    $email_rec->invoice_name  = $invoice['index_name'];
                                    $email_rec->attachment    = $pdf_file_name_invoice;
                                    $email_rec->subject       = $email_rec->set_subject('invoice_eway_receipt');
                                    $result['email_message']  = $email_rec->send ();
                                }
                            } else {
                                // do email to biller/admin - say error
                                $email = new email ();
                                $email->domain_id     = $domain_id;
                                $email->format        = 'cron_payment';
                                $email->from          = $biller['email'];
                                $email->from_friendly = $biller['name'];
                                $email->to            = $biller['email'];
                                $email->subject       = "Payment failed for " . $invoice['index_name'];
                                $error_message        = "Invoice:  " . $invoice['index_name'] . "<br /> Amount: " . $invoice['total'] . " <br />";
                                foreach($eway->get_message () as $key => $value) {
                                    $error_message .= "\n<br>\$ewayResponseFields[\"$key\"] = $value";
                                }
                                $email->notes            = $error_message;
                                $result['email_message'] = $email->send ();
                            }
                        }
                    } else {
                        // cron not run for this cron_id
                        $result['cron_message_' . $value['cron_id']] = "Cron ID: " . $value['cron_id'] . " NOT RUN: Cron for " . $value['index_name'] . " with start date of " . $value['start_date'] . ", end date of " . $value['end_date'] . " where it runs each " . $value['recurrence'] . " " . $value['recurrence_type'] . " did not recur today :: Info diff=" . $diff;
                    }
                } else {
                    // days diff is negative - whats going on
                    $result['cron_message_' . $value['cron_id']] = "Cron ID: " . $value['cron_id'] . " NOT RUN: - Not cheduled for today - Cron for " . $value['index_name'] . " with start date of " . $value['start_date'] . ", end date of " . $value['end_date'] . " where it runs each " . $value['recurrence'] . " " . $value['recurrence_type'] . " did not recur today :: Info diff=" . $diff;
                }
            } else {
                // cron has already been run for that cron_id today
                $result['cron_message_' . $value['cron_id']] = "Cron ID: " . $value['cron_id'] . " - Cron has already been run for domain: " . $domain_id . " for the date: " . $today . " for invoice " . $value['invoice_id'];
                $result['email_message'] = "";
            }
        }

        // no crons scheduled for today
        if ($number_of_crons_run == '0') {
            $result['id']            = $i;
            $result['cron_message']  = "No invoices recurred for this cron run for domain: " . $domain_id . " for the date: " . $today;
            $result['email_message'] = "";
        }
        return $result;
    }
}
