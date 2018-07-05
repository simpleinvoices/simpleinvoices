<?php
class Cron {

    public static function insert() {
        global $pdoDb;
        try {
            $pdoDb->setExcludedFields("id");
            $pdoDb->request("INSERT", "cron");
        } catch (PDOException $pde) {
            error_log("Cron insert error - " . $pde->getMessage());
            return false;
        }
        return true;
    }

    public static function update($id) {
        global $pdoDb;
        try {
            $pdoDb->setExcludedFields(array("id", "domain_id"));
            $pdoDb->addSimpleWhere("id", $id, "AND");
            $pdoDb->addSimpleWhere("domain_id", domain_id::get());
            $result = $pdoDb->request("UPDATE", "cron");
        } catch (PDOException $pde) {
            error_log("Cron update error - " . $pde->getMessage());
            return false;
        }
        return $result;
    }

    public static function delete() {
    }

    public static function select_all($type, $sort, $dir, $rp, $page) {
        global $pdoDb;

        $query = isset ( $_POST ['query'] ) ? $_POST ['query'] : null;
        $qtype = isset ( $_POST ['qtype'] ) ? $_POST ['qtype'] : null;
        if (!empty($qtype) && !empty($query)) {
            if (in_array($qtype, array('iv.id', 'b.name', 'cron.id', 'aging'))) {
                $pdoDb->addToWhere(new WhereItem(false, $qtype, "LIKE", "%$query%", false, "AND"));
            }
        }
        $pdoDb->addSimpleWhere("cron.domain_id", domain_id::get());

        $oc = new OnClause();
        $oc->addSimpleItem("cron.invoice_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("cron.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("INNER", "invoices", "iv", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.customer_id", new DbField("cust.id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("cust.domain_id"));
        $pdoDb->addToJoins(array("INNER", "customers", "cust", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.preference_id", new DbField("pf.pref_id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("pf.domain_id"));
        $pdoDb->addToJoins(array("INNER", "preferences", "pf", $oc));

        if ($type == "count") {
            $pdoDb->addToFunctions("COUNT(*) AS count");
            $rows = $pdoDb->request("SELECT", "cron", "cron");
            return $rows[0]['count'];
        }

        if (empty($sort)) {
            $pdoDb->setOrderBy("cron.id");
        } else {
            $pdoDb->setOrderBy($sort);
        }

        $start = (($page - 1) * $rp);
        $pdoDb->setLimit($rp, $start);

        $expr_list = array(
            "cron.id",
            "cron.domain_id",
            "cron.email_biller",
            "cron.email_customer",
            "cron.start_date",
            "cron.end_date",
            "cron.invoice_id",
            "cron.recurrence",
            "cron.recurrence_type",
            "cust.name"
        );

        $pdoDb->setSelectList($expr_list);

        $pdoDb->setGroupBy($expr_list);

        $fn = new FunctionStmt("CONCAT", "pf.pref_description, ' ', iv.index_id");
        $se = new Select($fn, null, null, "index_name");
        $pdoDb->addToSelectStmts($se);

        $result = $pdoDb->request("SELECT", "cron", "cron");
        return $result;
    }

    public static function select() {
        global $pdoDb;
        // Use this function to select crons that need to run each day across all domain_id values
        $oc = new OnClause();
        $oc->addSimpleItem("cron.invoice_id", new DbField("iv.id"), "AND");
        $oc->addSimpleItem("cron.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins(array("INNER", "invoices", "iv", $oc));

        $oc = new OnClause();
        $oc->addSimpleItem("iv.preference_id", new DbField("pf.pref_id"), "AND");
        $oc->addSimpleItem("iv.domain_id", new DbField("pf.domain_id"));
        $pdoDb->addToJoins(array("INNER", "preferences", "pf", $oc));

        $fn = new FunctionStmt("CONCAT", "pf.pref_description, ' ', iv.index_id");
        $se = new Select($fn, null, null, "index_name");
        $pdoDb->addToSelectStmts($se);

        $pdoDb->addSimpleWhere("cron.id", $_GET['id'], "AND");
        $pdoDb->addSimpleWhere("cron.domain_id", domain_id::get());

        $pdoDb->setSelectList(array("cron.*", "cron.id", "cron.domain_id"));

        $result = $pdoDb->request("SELECT", "cron", "cron");
        return $result[0];
    }

    private static function getEmailSendAddresses($src_array, $customer_email, $biller_email) {
        $email_to_addresses = Array ();
        if ($src_array['email_customer'] == ENABLED) $email_to_addresses[] = $customer_email;
        if ($src_array['email_biller']   == ENABLED) $email_to_addresses[] = $biller_email;
        return implode(";", $email_to_addresses);
    }

    public static function run() {
        global $pdoDb;
        $result = array();

        $today = date('Y-m-d');
        $rows  = self::select_crons_to_run();
        $result['cron_message'] = "Cron started";
        $number_of_crons_run = "0";
        $i = 0; // set here so accessable outside of the loop

        foreach($rows as $value) {
            // @formatter:off
            $cron_id   = $value['id'];
            $domain_id = $value['domain_id'];

            $check_cron_log = CronLog::check($pdoDb, $domain_id, $cron_id, $today);

            $i = "0";
            if ($check_cron_log == 0) {
                // only proceed if Cron has not been run for today
                $run_cron   = false;
                $start_date = date('Y-m-d', strtotime($value['start_date']));
                $end_date   = $value['end_date'];

                // Seconds in a day = 60 * 60 * 24 = 86400
                $diff = number_format((strtotime($today) - strtotime($start_date)) / 86400, 0);

                // only check if diff is positive
                if (($diff >= 0) && ($end_date == "" || $end_date >= $today)) {
                    $month = false;

                    switch($value['recurrence_type']) {
                        case 'day':
                            // Calculate number of days passed.
                            $modulus = $diff % $value['recurrence'];
                            $run_cron = ($modulus == 0);
                            break;

                        case 'week':
                            // Calculate number of weeks passed.
                            $period   = $value['recurrence'] * 7;
                            $modulus  = $diff % $period;
                            $run_cron = ($modulus == 0);
                            break;

                        case 'month':
                            $month = true;
                        case 'year' :
                            $start_day   = date('d', strtotime($value['start_date']));
                            $start_month = date('m', strtotime($value['start_date']));
                            $start_year  = date('Y', strtotime($value['start_date']));
                            $today_day   = date('d');
                            $today_month = date('m');
                            $today_year  = date('Y');

                            if ($month) {
                                // Calculate number of month passed.
                                $val  = ($today_month - $start_month) + (($today_year - $start_year) * 12);
                            } else {
                                // Calculate number of years passed.
                                $val = $today_year - $start_year;
                            }

                            $modulus = $val % $value['recurrence'];
                            $run_cron = ($modulus == 0 && $start_day == $today_day);
                            if (!$month) {
                                $run_cron = ($run_cron && $start_month == $today_month);
                            }
                            break;

                        default:
                            $run_cron = false;
                            break;
                    }

                    // run the recurrence for this invoice
                    if ($run_cron) {
                        $number_of_crons_run++;
                        // @formatter:off
                        $cron_msg = "Cron ID: $value[id] - Cron for $value[index_name] with ";
                        $cron_msg += (empty($value['start_date']) ? "no start date" : "start date of $value[start_date] ") . "and ";
                        $cron_msg += (empty($value['end_date']  ) ? "no end date"   : "an end date of $value[end_date] ");
                        $cron_msg += " that runs each $value[recurrence] $value[recurrence_type], was run today :: Info diff=$diff";
                        $result["cron_message_{$value['id']}"] = $cron_msg;
                        // @formatter:on
                        $i++;

                        // $domain_id gets propagated from invoice to be copied from
                        $new_invoice_id = Invoice::recur ($value['invoice_id']);

                        CronLog::insert($pdoDb, $domain_id, $cron_id, $today);
                        // @formatter:off
                        $invoice               = Invoice::select($new_invoice_id);
                        $preference            = Preferences::getPreference($invoice['preference_id'], $domain_id);
                        $biller                = Biller::select($invoice['biller_id']);
                        $customer              = Customer::get($invoice['customer_id']);
                        $spc2us_pref           = str_replace(" ", "_", $invoice['index_name']);
                        $pdf_file_name_invoice = $spc2us_pref . ".pdf";
                        // @formatter:on

                        // email invoice
                        if (($value['email_biller'] == ENABLED) || ($value['email_customer'] == ENABLED)) {
                            $export = new export ();
                            // @formatter:off
                            $export->domain_id = $domain_id;
                            $export->format    = "pdf";
                            $export->module    = 'invoice';
                            $export->id        = $invoice['id'];
                            $export->setDownload(false);
                            $export->execute ();

                            // $attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);
                            $email = new Email ();
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
                            $email->to                 = self::getEmailSendAddresses($value, $customer['email'], $biller['email']);
                            $email->invoice_name       = $invoice['index_name'];
                            $email->subject            = $email->set_subject ();
                            $email->attachment         = $pdf_file_name_invoice;
                            $result['email_message']   = $email->send ();
                            // @formatter:on
                        }

                        // Check that all details are OK before doing the eway payment
                        $eway_check = new eway ();
                        // @formatter:off
                        $eway_check->domain_id  = $domain_id;
                        $eway_check->invoice    = $invoice;
                        $eway_check->customer   = $customer;
                        $eway_check->biller     = $biller;
                        $eway_check->preference = $preference;
                        $eway_pre_check         = $eway_check->pre_check ();
                        // @formatter:on

                        // do eway payment
                        if ($eway_pre_check == 'true') {
                            $eway = new eway ();
                            // @formatter:off
                            $eway->domain_id = $domain_id;
                            $eway->invoice   = $invoice;
                            $eway->biller    = $biller;
                            $eway->customer  = $customer;
                            $payment_id      = $eway->payment ();
                            $payment_done    = ($payment_id !== false);
                            // @formatter:on

                            $pdf_file_name_receipt = 'payment' . $payment_id . '.pdf';
                            if ($pdf_file_name_receipt) {}; // To eliminate unused variable warning.
                            if ($payment_done == 'true') {
                                // Email receipt to biller and customer
                                if (($value['email_biller']   == ENABLED) ||
                                    ($value['email_customer'] == ENABLED)) {
                                     // Code to email a new copy of the invoice to the customer
                                    $export_rec = new export ();
                                    // @formatter:off
                                    $export_rec->domain_id = $domain_id;
                                    $export_rec->format    = "pdf";
                                    $export_rec->module    = 'invoice';
                                    $export_rec->id        = $invoice['id'];
                                    $export_rec->setDownload(false);
                                    $export_rec->execute ();

                                    $email_rec = new Email ();
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
                                    $email_rec->to            = self::getEmailSendAddresses($value, $customer['email'], $biller['email']);
                                    $email_rec->invoice_name  = $invoice['index_name'];
                                    $email_rec->attachment    = $pdf_file_name_invoice;
                                    $email_rec->subject       = $email_rec->set_subject('invoice_eway_receipt');
                                    $result['email_message']  = $email_rec->send ();
                                    // @formatter:on
                                }
                            } else {
                                // do email to biller/admin - say error
                                $email = new Email ();
                                // @formatter:off
                                $email->domain_id     = $domain_id;
                                $email->format        = 'cron_payment';
                                $email->from          = $biller['email'];
                                $email->from_friendly = $biller['name'];
                                $email->to            = $biller['email'];
                                $email->subject       = "Payment failed for $invoice[index_name]";
                                $error_message        = "Invoice: $invoice[index_name]<br />Amount: $invoice[total]<br />";
                                foreach($eway->get_message () as $key2 => $value2) {
                                    $error_message .= "\n<br>\$ewayResponseFields[\"$key2\"] = $value2";
                                }
                                $email->notes            = $error_message;
                                $result['email_message'] = $email->send ();
                            }
                        }
                    } else {
                        // Cron not run for this cron_id
                        $cron_msg = "Cron ID: $value[id] - Cron for $value[index_name] with ";
                        $cron_msg += (empty($value['start_date']) ? "no start date" : "start date of $value[start_date] ") . "and ";
                        $cron_msg += (empty($value['end_date']  ) ? "no end date"   : "an end date of $value[end_date] ");
                        $cron_msg += " that runs each $value[recurrence] $value[recurrence_type], did not recur today :: Info diff=$diff";
                        $result["cron_message_$value[id]"] = $cron_msg;
                    }
                } else {
                    // days diff is negative - whats going on
                    $cron_msg = "Cron ID: $value[id] - NOTE RUN: Not cheduled for today. Cron for $value[index_name] with ";
                    $cron_msg += (empty($value['start_date']) ? "no start date" : "start date of $value[start_date] ") . "and ";
                    $cron_msg += (empty($value['end_date']  ) ? "no end date"   : "an end date of $value[end_date] ");
                    $cron_msg += " that runs each $value[recurrence] $value[recurrence_type], did not recur today :: Info diff=$diff";
                    $result["cron_message_$value[id]"] = $cron_msg;
                }
            } else {
                // Cron has already been run for that id today
                $result["cron_message_$value[id]"] = "Cron ID: $value[id] - Cron has already been run for domain: $value[domain_id] " .
                                                     "for the date: $today for invoice $value[invoice_id]";
                $result['email_message'] = "";
            }
        }

        // no crons scheduled for today
        if ($number_of_crons_run == '0') {
            $result['id'           ] = $i;
            $result['cron_message' ] = "No invoices recurred for this Cron run for domain: " . domain_id::get() . " for the date: $today";
            $result['email_message'] = "";
        }
        return $result;
    }

    public static function select_crons_to_run() {
        global $config, $pdoDb;

        $timezone = $config->phpSettings->date->timezone;

        // Use this function to select crons that need to run each day across all domain_id values
        // @formatter:off
        $jn = new Join("INNER", "invoices", "iv");
        $jn->addSimpleItem("cron.invoice_id", new DbField("iv.id"), "AND");
        $jn->addSimpleItem("cron.domain_id", new DbField("iv.domain_id"));
        $pdoDb->addToJoins($jn);

        $jn = new Join("INNER", "preferences", "pf");
        $jn->addSimpleItem("iv.preference_id", new DbField("pf.pref_id"), "AND");
        $jn->addSimpleItem("iv.domain_id", new DbField("pf.domain_id"));
        $pdoDb->addToJoins($jn);

        $fn = new FunctionStmt("CONCAT", "pf.pref_description, ' ', iv.index_id");
        $se = new Select($fn, null, null, "index_name");
        $pdoDb->addToSelectStmts($se);

        $dtm = new DateTime(null,new DateTimeZone($timezone));
        $dt = $dtm->format("Y-m-d");
        $pdoDb->addToWhere(new WhereItem(true , "cron.start_date", "=" , "" , false, "OR"));
        $pdoDb->addToWhere(new WhereItem(false, "cron.start_date", "<=", $dt, true , "AND"));
        $pdoDb->addToWhere(new WhereItem(true , "cron.end_date"  , "=" , "" , false, "OR"));
        $pdoDb->addToWhere(new WhereItem(false, "cron.end_date"  , ">=", $dt, true , "AND"));

        $pdoDb->addSimpleWhere("cron.domain_id", domain_id::get());

        $pdoDb->setSelectList("cron.*");

        $pdoDb->setGroupBy(array("cron.id", "cron.domain_id"));
        $result = $pdoDb->request("SELECT", "cron", "cron");
        // @formatter:on
        return $result;
    }

}
