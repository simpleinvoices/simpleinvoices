<?php
class export {
    public $biller;
    public $biller_id;
    public $customer;
    public $customer_id;
    public $do_not_filter_by_date;
    public $domain_id;
    public $end_date;
    public $file_name;
    public $file_type;
    public $format;
    public $id;
    public $invoice;
    public $module;
    public $preference;
    public $show_only_unpaid;
    public $start_date;

    private $download;

    public function __construct() {
        // @formatter:off
        $this->domain_id             = domain_id::get();
        $this->biller                = null;
        $this->biller_id             = 0;
        $this->customer              = null;
        $this->customer_id           = 0;
        $this->do_not_filter_by_date = "no";
        $this->download              = false;
        $this->end_date              = "";
        $this->file_name             = "";
        $this->file_type             = "";
        $this->format                = "";
        $this->id                    = 0;
        $this->invoice               = null;
        $this->module                = "";
        $this->preference            = null;
        $this->show_only_unpaid      = "no";
        $this->start_date            = "";
        // @formatter:on
    }

    public function setDownload($download) {
        $this->download = $download;
    }

    public function showData($data) {
        if ($this->file_name == '' && $this->module == 'payment') {
            $this->file_name = 'payment' . $this->id;
        }

        // @formatter:off
        switch ($this->format) {
            case "print":
                echo ($data);
                break;

            case "pdf":
                pdfThis($data, $this->file_name, $this->download);
                if ($this->download) exit(); // stop script execution after download
                break; // continue script execution

            case "file":
                $invoice    = Invoice::getInvoice($this->id);
                $preference = Preferences::getPreference($invoice['preference_id'], $this->domain_id);

                // xls/doc export no longer uses the export template $template = "export";
                header("Content-type: application/octet-stream");

                // header("Content-type: application/x-msdownload");
                switch ($this->module) {
                    case "statement":
                        header('Content-Disposition: attachment; filename="statement.' .
                               addslashes($this->file_type) . '"');
                        break;

                    case "payment":
                        header('Content-Disposition: attachment; filename="payment' .
                               addslashes($this->id . '.' . $this->file_type) . '"');
                        break;

                    default:
                        header('Content-Disposition: attachment; filename="' .
                               addslashes($preference['pref_inv_heading'] . $this->id . '.' .
                               $this->file_type) . '"');
                        break;
                }

                header("Pragma: no-cache");
                header("Expires: 0");
                echo ($data);
                break;
        }
        // @formatter:on
    }

    public function getData() {
        global $api_request, $smarty, $pdoDb, $siUrl;

        // @formatter:off
        //$data = null;
        switch ($this->module) {
            case "statement":
                if ($this->do_not_filter_by_date != "yes" && !empty($this->start_date) && !empty($this->end_date)) {
                    $pdoDb->setHavings(Invoice::buildHavings("date_between", array($this->start_date, $this->end_date)));
                }
                
                if ($this->show_only_unpaid == "yes") {
                    $pdoDb->setHavings(Invoice::buildHavings("money_owed"));
                }

                if (!empty($this->biller_id)  ) $pdoDb->addSimpleWhere("biller_id"  , $this->biller_id  , "AND");
                if (!empty($this->customer_id)) $pdoDb->addSimpleWhere("customer_id", $this->customer_id, "AND");

                $invoices  = Invoice::select_all("", "date", "D");
                $statement = array("total" => 0, "owing" => 0, "paid" => 0);
                foreach ( $invoices as $row ) {
                    if ($row ['status'] > 0) {
                        $statement ['total'] += $row ['invoice_total'];
                        $statement ['owing'] += $row ['owing'];
                        $statement ['paid']  += $row ['INV_PAID'];
                    }
                }

                $templatePath     = "templates/default/statement/index.tpl";
                $biller_details   = Biller::select($this->biller_id);
                $billers          = $biller_details;
                $customer_details = Customer::get($this->customer_id);
                if (empty($this->file_name)) {
                    $pdf_file_name = 'statement';
                    if (!empty($this->biller_id)  ) $pdf_file_name .= '_' . $this->biller_id;
                    if (!empty($this->customer_id)) $pdf_file_name .= '_' . $this->customer_id;
                    if ($this->do_not_filter_by_date != "yes") {
                        if (!empty($this->start_date) && !empty($this->end_date)) {
                            $pdf_file_name .= '_' . $this->start_date;
                            $pdf_file_name .= '_' . $this->end_date;
                        }
                    }
                    $this->file_name = $pdf_file_name;
                }

                $smarty->assign('biller_id'            , $this->biller_id);
                $smarty->assign('biller_details'       , $biller_details);
                $smarty->assign('billers'              , $billers);
                $smarty->assign('customer_id'          , $this->customer_id);
                $smarty->assign('customer_details'     , $customer_details);
                $smarty->assign('show_only_unpaid'     , $this->show_only_unpaid);
                $smarty->assign('do_not_filter_by_date', $this->do_not_filter_by_date);
                $smarty->assign('invoices'             , $invoices);
                $smarty->assign('start_date'           , $this->start_date);
                $smarty->assign('end_date'             , $this->end_date);
                $smarty->assign('statement'            , $statement);
                $smarty->assign('menu'                 , false);
                $data = $smarty->fetch($templatePath);
                break;

            case "payment":
                $payment = Payment::select($this->id);

                // Get Invoice preference to link from this screen back to the invoice
                $invoice = Invoice::getInvoice($payment['ac_inv_id']);
                $biller  = Biller::select($payment['biller_id']);

                $logo = getLogo($biller);
                $logo = str_replace(" ", "%20", trim($logo));

                $customer          = Customer::get($payment['customer_id']);
                $invoiceType       = Invoice::getInvoiceType($invoice['type_id']);
                $customFieldLabels = getCustomFieldLabels($this->domain_id, true);
                $paymentType       = PaymentType::select($payment['ac_payment_type']);
                $preference        = Preferences::getPreference($invoice['preference_id'], $this->domain_id);

                $smarty->assign("payment"          , $payment);
                $smarty->assign("invoice"          , $invoice);
                $smarty->assign("biller"           , $biller);
                $smarty->assign("logo"             , $logo);
                $smarty->assign("customer"         , $customer);
                $smarty->assign("invoiceType"      , $invoiceType);
                $smarty->assign("paymentType"      , $paymentType);
                $smarty->assign("preference"       , $preference);
                $smarty->assign("customFieldLabels", $customFieldLabels);
                $smarty->assign('pageActive'       , 'payment');
                $smarty->assign('active_tab'       , '#money');

                $css = $siUrl . "templates/invoices/default/style.css";
                $smarty->assign('css', $css);

                $templatePath = "templates/default/payments/print.tpl";
                $data = $smarty->fetch($templatePath);
                break;

            case "invoice":
                if (!isset($this->invoice)) $this->invoice = Invoice::select($this->id);
                $this->id = $this->invoice['id'];
                $this->file_name = str_replace(" ", "_", $this->invoice['index_name']);

                $invoice_number_of_taxes = Invoice::numberOfTaxesForInvoice($this->id, $this->domain_id);
                $invoiceItems = Invoice::getInvoiceItems($this->id);
                
                if (!isset($this->customer)) $this->customer = Customer::get($this->invoice['customer_id']);
                if (!isset($this->biller)) $this->biller = Biller::select($this->invoice['biller_id']);
                if (!isset($this->preference)) $this->preference = Preferences::getPreference($this->invoice['preference_id'], $this->domain_id);

                $defaults  = getSystemDefaults($this->domain_id);

                $logo = getLogo($this->biller);
                $logo = str_replace(" ", "%20", trim($logo));

                $customFieldLabels = getCustomFieldLabels($this->domain_id, true);

                // Set the template to the default
                $template = $defaults['template'];

                $templatePath  = "templates/invoices/{$template}/template.tpl";
                $template_path = "templates/invoices/{$template}";
                $css           = $siUrl . "templates/invoices/{$template}/style.css";

                $pageActive = "invoices";
                $smarty->assign('pageActive', $pageActive);

                $this->assignTemplateLanguage($this->preference);

                $smarty->assign('biller'                 , $this->biller);
                $smarty->assign('customer'               , $this->customer);
                $smarty->assign('invoice'                , $this->invoice);
                $smarty->assign('invoice_number_of_taxes', $invoice_number_of_taxes);
                $smarty->assign('preference'             , $this->preference);
                $smarty->assign('logo'                   , $logo);
                $smarty->assign('template'               , $template);
                $smarty->assign('invoiceItems'           , $invoiceItems);
                $smarty->assign('template_path'          , $template_path);
                $smarty->assign('css'                    , $css);
                $smarty->assign('customFieldLabels'      , $customFieldLabels);

                // Plugins specifically associated with your invoice template.
                $template_plugins_dir = "templates/invoices/${template}/plugins/";
                if (is_dir($template_plugins_dir)) {
                    $plugins_dirs = $smarty->getPluginsDir();
                    if (!is_array($plugins_dirs)) $plugins_dirs = array($plugins_dirs);
                    $plugins_dirs[] = $template_plugins_dir;
                    $smarty->setPluginsDir($plugins_dirs);
                }

                $data = $smarty->fetch($templatePath);
                break;
        }
        // @formatter:on

        return $data;
    }

    public function execute() {
        $this->showData($this->getData());
    }

    // assign the language and set the locale from the preference
    public function assignTemplateLanguage($preference) {
        // get and assign the language file from the preference table
        $pref_language = $preference['language'];
        if (isset($pref_language)) {
            $LANG = getLanguageArray($pref_language);
            if (isset($LANG) && is_array($LANG) && count($LANG) > 0) {
                global $smarty;
                $smarty->assign('LANG', $LANG);
            }
        }

        // Overide config's locale with the one assigned from the preference table
        $pref_locale = $preference['locale'];
        if (isset($pref_language) && strlen($pref_locale) > 4) {
            global $config;
            $config->local->locale = $pref_locale;
        }
    }
}
