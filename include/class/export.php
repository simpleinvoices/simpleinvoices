<?php

class export
{
    public $format;
    public $file_type;
    public $file_location;
    public $file_name;
    public $module;
    public $id;
    public $start_date;
    public $end_date;
    public $biller_id;
    public $customer_id;
    public $domain_id;

    public function __construct()
    {
        $this->domain_id = domain_id::get($this->domain_id);
    }

    function showData($data)
    {
        if ($this->file_name == '' && $this->module == 'payment') {
            $this->file_name = 'payment' . $this->id;
        }

        switch ($this->format) {
            case "print":
                echo($data);
                break;

            case "pdf":
                pdfThis($data, $this->file_location, $this->file_name);
                if ($this->file_location === "download" || $this->file_location === "inline") exit();
                break;

            case "file":
                // Normalise legacy extensions to proper formats
                $file_type = strtolower($this->file_type ?? '');
                if ($file_type === 'xls')  $file_type = 'xlsx';
                if ($file_type === 'doc')  $file_type = 'docx';

                if ($file_type === 'xlsx') {
                    $this->exportXlsx($data);
                } elseif ($file_type === 'docx') {
                    $this->exportDocx($data);
                } elseif ($file_type === 'ods') {
                    $this->exportOds($data);
                } elseif ($file_type === 'odt') {
                    $this->exportOdt($data);
                } else {
                    // Fallback for any other file type: stream HTML as octet-stream
                    $invoice    = getInvoice($this->id, $this->domain_id);
                    $preference = getPreference($invoice['preference_id'], $this->domain_id);

                    header("Content-type: application/octet-stream");
                    switch ($this->module) {
                        case "statement":
                            header('Content-Disposition: attachment; filename="statement.' . addslashes($this->file_type) . '"');
                            break;
                        case "payment":
                            header('Content-Disposition: attachment; filename="payment' . addslashes($this->id . '.' . $this->file_type) . '"');
                            break;
                        default:
                            header('Content-Disposition: attachment; filename="' . addslashes($preference['pref_inv_heading'] . $this->id . '.' . $this->file_type) . '"');
                    }
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    echo($data);
                }
                break;
        }
    }

    /**
     * Send a docx/xlsx (ZIP-based OOXML) as a download without corrupting the stream.
     * Clears output buffers, disables zlib compression, and sets Content-Length so clients
     * do not treat HTML warnings or partial gzip as part of the file.
     */
    private function sendOfficeOpenXmlDownloadAndExit(string $path, string $mimeType, string $downloadName): void
    {
        if (!is_readable($path)) {
            @unlink($path);
            exit(1);
        }

        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
        @ini_set('zlib.output_compression', '0');

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $downloadName = basename(str_replace(["\0", "\r", "\n"], '', $downloadName));
        $asciiName    = preg_replace('/[^\x20-\x7E]/', '_', $downloadName);
        $size         = filesize($path);
        if ($size === false) {
            @unlink($path);
            exit(1);
        }

        header('Content-Type: ' . $mimeType);
        header(
            'Content-Disposition: attachment; filename="' . str_replace(['\\', '"'], ['\\\\', '\\"'], $asciiName)
            . '"; filename*=UTF-8\'\'' . rawurlencode($downloadName)
        );
        header('Content-Length: ' . $size);
        header('Cache-Control: private, must-revalidate');
        header('Pragma: public');

        readfile($path);
        @unlink($path);
        exit(0);
    }

    /** XLSX from the same rendered HTML as print/PDF/DOCX (PhpSpreadsheet Reader\Html). */
    private function exportXlsx($html = null): void
    {
        require_once('./vendor/autoload.php');

        $htmlStr = is_string($html) ? trim($html) : '';
        if ($htmlStr === '') {
            throw new \RuntimeException('XLSX export requires rendered HTML.');
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $reader->setAllowExternalImages(true);
        $spreadsheet = $reader->loadFromString($htmlStr);

        try {
            $spreadsheet->getActiveSheet()->setTitle(
                $this->module === 'payment' ? 'Payment' : ($this->module === 'statement' ? 'Statement' : 'Invoice')
            );
        } catch (\Throwable $e) {
            // Keep the title from the HTML if rename fails (length / invalid characters).
        }

        $filename = basename(($this->file_name ?: $this->module . $this->id) . '.xlsx');
        $tmp      = @tempnam(sys_get_temp_dir(), 'sixls_');
        if ($tmp === false) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            @ini_set('zlib.output_compression', '0');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . str_replace(['\\', '"'], ['\\\\', '\\"'], $filename) . '"');
            header('Cache-Control: private, must-revalidate');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit(0);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        try {
            $writer->save($tmp);
        } catch (\Throwable $e) {
            @unlink($tmp);
            throw $e;
        }
        $this->sendOfficeOpenXmlDownloadAndExit(
            $tmp,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            $filename
        );
    }

    /**
     * PhpWord Html::addHtml() uses DOMDocument::loadXML(), which rejects typical HTML
     * (e.g. unclosed <img>, <br>). Normalize via loadHTML + saveXML of body children.
     */
    private function htmlFragmentForPhpWord(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '<p></p>';
        }

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML(
            '<?xml encoding="utf-8">' . $html,
            LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();

        $body = $dom->getElementsByTagName('body')->item(0);
        if (!$body || !$body->hasChildNodes()) {
            $dom = new \DOMDocument();
            $dom->loadHTML(
                '<?xml encoding="utf-8"><html><body>' . $html . '</body></html>',
                LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
            );
            libxml_clear_errors();
            $body = $dom->getElementsByTagName('body')->item(0);
        }

        if (!$body || !$body->hasChildNodes()) {
            return '<p>' . htmlspecialchars(strip_tags($html), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
        }

        $inner = '';
        foreach ($body->childNodes as $child) {
            $inner .= $dom->saveXML($child);
        }

        return $inner !== '' ? $inner : '<p></p>';
    }

    /** Strip C0/C1 control bytes disallowed in XML 1.0 text (LibreOffice 24+ is strict). */
    private function stripIllegalXmlControlChars(string $s): string
    {
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $s);
    }

    /**
     * PhpWord Html::addHtml calls addTextBreak() on the current container; Table is not a container,
     * so br nodes that sit inside table but outside td/th (invalid HTML) cause a fatal error.
     */
    private function htmlFragmentSanitizeForPhpWord(string $fragment): string
    {
        $fragment = trim($fragment);
        if ($fragment === '') {
            return $fragment;
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML(
            '<?xml encoding="utf-8"><html><body><div id="si_phproot">' . $fragment . '</div></body></html>',
            LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();

        $xp    = new \DOMXPath($dom);
        $nodes = $xp->query('//br[ancestor::table and not(ancestor::td) and not(ancestor::th)]');
        if ($nodes !== false) {
            foreach (iterator_to_array($nodes, false) as $br) {
                if ($br->parentNode !== null) {
                    $br->parentNode->removeChild($br);
                }
            }
        }

        $root = $dom->getElementById('si_phproot');
        if ($root === null) {
            return $fragment;
        }

        $inner = '';
        foreach ($root->childNodes as $child) {
            $inner .= $dom->saveXML($child);
        }

        return $inner !== '' ? $inner : $fragment;
    }

    // Export invoice/payment as a real DOCX via PhpWord HTML import
    private function exportDocx($html)
    {
        require_once('./vendor/autoload.php');

        // LibreOffice 24+ rejects document.xml if text runs use writeRaw() with &, <, etc.; escaping fixes that.
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        if (class_exists(\ZipArchive::class)) {
            \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::ZIPARCHIVE);
        }

        $fragment = $this->htmlFragmentSanitizeForPhpWord(
            $this->stripIllegalXmlControlChars($this->htmlFragmentForPhpWord($html))
        );

        $buildDoc = static function (string $frag) {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $phpWord->getSettings()->setUpdateFields(true);
            $section = $phpWord->addSection([
                'marginTop'    => 720,
                'marginBottom' => 720,
                'marginLeft'   => 900,
                'marginRight'  => 900,
            ]);
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $frag, false, false);

            return $phpWord;
        };

        try {
            $phpWord = $buildDoc($fragment);
        } catch (\Throwable $e) {
            // Logos often use URLs; if the image cannot be fetched, PhpWord throws — retry without <img>.
            $stripped = preg_replace('/<img\b[^>]*\/?>/i', '', $fragment);
            $phpWord  = $buildDoc($stripped !== '' ? $stripped : '<p></p>');
        }

        $filename = basename(($this->file_name ?: $this->module . $this->id) . '.docx');
        $tmp      = @tempnam(sys_get_temp_dir(), 'sidoc_');
        $writer   = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');

        if ($tmp === false) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            @ini_set('zlib.output_compression', '0');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="' . str_replace(['\\', '"'], ['\\\\', '\\"'], $filename) . '"');
            header('Cache-Control: private, must-revalidate');
            $writer->save('php://output');
            exit(0);
        }

        try {
            $writer->save($tmp);
        } catch (\Throwable $e) {
            @unlink($tmp);
            throw $e;
        }
        $this->sendOfficeOpenXmlDownloadAndExit(
            $tmp,
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            $filename
        );
    }

    /** ODS from the same rendered HTML via PhpSpreadsheet Reader\Html → Writer\Ods. */
    private function exportOds($html = null): void
    {
        require_once('./vendor/autoload.php');

        $htmlStr = is_string($html) ? trim($html) : '';
        if ($htmlStr === '') {
            throw new \RuntimeException('ODS export requires rendered HTML.');
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $reader->setAllowExternalImages(true);
        $spreadsheet = $reader->loadFromString($htmlStr);

        try {
            $spreadsheet->getActiveSheet()->setTitle(
                $this->module === 'payment' ? 'Payment' : ($this->module === 'statement' ? 'Statement' : 'Invoice')
            );
        } catch (\Throwable $e) {
            // Keep default title if rename fails (length / invalid characters).
        }

        $filename = basename(($this->file_name ?: $this->module . $this->id) . '.ods');
        $tmp      = @tempnam(sys_get_temp_dir(), 'siods_');
        if ($tmp === false) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            @ini_set('zlib.output_compression', '0');
            header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
            header('Content-Disposition: attachment; filename="' . str_replace(['\\', '"'], ['\\\\', '\\"'], $filename) . '"');
            header('Cache-Control: private, must-revalidate');
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
            $writer->save('php://output');
            exit(0);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Ods($spreadsheet);
        try {
            $writer->save($tmp);
        } catch (\Throwable $e) {
            @unlink($tmp);
            throw $e;
        }
        $this->sendOfficeOpenXmlDownloadAndExit(
            $tmp,
            'application/vnd.oasis.opendocument.spreadsheet',
            $filename
        );
    }

    /** ODT from the same rendered HTML via PhpWord Html::addHtml → Writer\ODText. */
    private function exportOdt($html): void
    {
        require_once('./vendor/autoload.php');

        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        if (class_exists(\ZipArchive::class)) {
            \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::ZIPARCHIVE);
        }

        $fragment = $this->htmlFragmentSanitizeForPhpWord(
            $this->stripIllegalXmlControlChars($this->htmlFragmentForPhpWord($html))
        );

        $buildDoc = static function (string $frag) {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $phpWord->getSettings()->setUpdateFields(true);
            $section = $phpWord->addSection([
                'marginTop'    => 720,
                'marginBottom' => 720,
                'marginLeft'   => 900,
                'marginRight'  => 900,
            ]);
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $frag, false, false);

            return $phpWord;
        };

        try {
            $phpWord = $buildDoc($fragment);
        } catch (\Throwable $e) {
            $stripped = preg_replace('/<img\b[^>]*\/?>/i', '', $fragment);
            $phpWord  = $buildDoc($stripped !== '' ? $stripped : '<p></p>');
        }

        $filename = basename(($this->file_name ?: $this->module . $this->id) . '.odt');
        $tmp      = @tempnam(sys_get_temp_dir(), 'siodt_');
        $writer   = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');

        if ($tmp === false) {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            @ini_set('zlib.output_compression', '0');
            header('Content-Type: application/vnd.oasis.opendocument.text');
            header('Content-Disposition: attachment; filename="' . str_replace(['\\', '"'], ['\\\\', '\\"'], $filename) . '"');
            header('Cache-Control: private, must-revalidate');
            $writer->save('php://output');
            exit(0);
        }

        try {
            $writer->save($tmp);
        } catch (\Throwable $e) {
            @unlink($tmp);
            throw $e;
        }
        $this->sendOfficeOpenXmlDownloadAndExit(
            $tmp,
            'application/vnd.oasis.opendocument.text',
            $filename
        );
    }

    function getData()
    {
        global $bladeView;
        global $siUrl;

        switch ($this->module) {
            case "statement":
            {
                $invoice = new invoice();
                $invoice->domain_id = $this->domain_id;
                $invoice->biller    = $this->biller_id;
                $invoice->customer  = $this->customer_id;

                $having_count = 0;
                if ($this->filter_by_date == "yes") {
                    if (isset($this->start_date)) {
                        $invoice->start_date = $this->start_date;
                    }
                    if (isset($this->end_date)) {
                        $invoice->end_date = $this->end_date;
                    }
                    if (isset($this->start_date) && isset($this->end_date)) {
                        $invoice->having = "date_between";
                    }
                    $having_count = 1;
                }

                if ($this->show_only_unpaid == "yes") {
                    if ($having_count == 1) {
                        $invoice->having_and = "money_owed";
                    } else {
                        $invoice->having = "money_owed";
                    }
                }

                $invoice_all = $invoice->select_all('count');
                $invoices    = $invoice_all->fetchAll();

                $statement = ['total' => 0, 'owing' => 0, 'paid' => 0];
                foreach ($invoices as $i => $row) {
                    $statement['total'] += $row['invoice_total'];
                    $statement['owing'] += $row['owing'];
                    $statement['paid']  += $row['INV_PAID'];
                }

                $templatePath   = "./templates/default/statement/index.blade.php";
                $biller_details = getBiller($this->biller_id, $this->domain_id);
                $billers        = $biller_details;
                $customer_details = getCustomer($this->customer_id, $this->domain_id);

                $this->file_name = "statement_{$this->biller_id}_{$this->customer_id}_{$invoice->start_date}_{$invoice->end_date}";

                $bladeView->assign('biller_id',       $this->biller_id);
                $bladeView->assign('biller_details',  $biller_details);
                $bladeView->assign('billers',         $billers);
                $bladeView->assign('customer_id',     $this->customer_id);
                $bladeView->assign('customer_details', $customer_details);
                $bladeView->assign('show_only_unpaid', $this->show_only_unpaid);
                $bladeView->assign('filter_by_date',  $this->filter_by_date);
                $bladeView->assign('invoices',        $invoices);
                $bladeView->assign('start_date',      $this->start_date);
                $bladeView->assign('end_date',        $this->end_date);
                $bladeView->assign('statement',       $statement);
                $data = $bladeView->fetch("." . $templatePath);
                break;
            }

            case "payment":
            {
                $payment     = getPayment($this->id);
                $invoice     = getInvoice($payment['ac_inv_id'], $this->domain_id);
                $biller      = getBiller($payment['biller_id'], $this->domain_id);
                $logo        = getLogo($biller);
                $logo        = str_replace(" ", "%20", $logo);
                $customer    = getCustomer($payment['customer_id'], $this->domain_id);
                $invoiceType = getInvoiceType($invoice['type_id']);
                $customFieldLabels = getCustomFieldLabels($this->domain_id);
                $paymentType = getPaymentType($payment['ac_payment_type'], $this->domain_id);
                $preference  = getPreference($invoice['preference_id'], $this->domain_id);

                $bladeView->assign("payment",          $payment);
                $bladeView->assign("invoice",          $invoice);
                $bladeView->assign("biller",           $biller);
                $bladeView->assign("logo",             $logo);
                $bladeView->assign("customer",         $customer);
                $bladeView->assign("invoiceType",      $invoiceType);
                $bladeView->assign("paymentType",      $paymentType);
                $bladeView->assign("preference",       $preference);
                $bladeView->assign("customFieldLabels", $customFieldLabels);
                $bladeView->assign('pageActive',       'payment');
                $bladeView->assign('active_tab',       '#money');

                $css = $siUrl . "/templates/invoices/default/style.css";
                $bladeView->assign('css', $css);

                $templatePath = "./templates/default/payments/print.blade.php";
                $data = $bladeView->fetch("." . $templatePath);
                break;
            }

            case "invoice":
            {
                $invoiceobj  = new invoice();
                $invoiceobj->domain_id = $this->domain_id;
                $invoice     = $invoiceobj->select($this->id, $this->domain_id);
                $invoice_number_of_taxes = numberOfTaxesForInvoice($this->id, $this->domain_id);
                $customer    = getCustomer($invoice['customer_id'], $this->domain_id);
                $billerobj   = new biller();
                $billerobj->domain_id = $this->domain_id;
                $biller      = $billerobj->select($invoice['biller_id']);
                $preference  = getPreference($invoice['preference_id'], $this->domain_id);
                $preference  = InvoiceTokens::expandPreference($preference, $invoice, $biller, $customer);
                $defaults    = getSystemDefaults($this->domain_id);
                $logo        = getLogo($biller);
                $logo        = str_replace(" ", "%20", $logo);
                $invoiceItems = $invoiceobj->getInvoiceItems($this->id, $this->domain_id);

                $spc2us_pref    = str_replace(" ", "_", $invoice['index_name']);
                $this->file_name = $spc2us_pref;

                $customFieldLabels = getCustomFieldLabels($this->domain_id);

                // Use the dedicated export template for all office file exports (xlsx/docx/ods/odt)
                $file_type_norm = strtolower($this->file_type ?? '');
                if ($file_type_norm === 'xls') $file_type_norm = 'xlsx';
                if ($file_type_norm === 'doc') $file_type_norm = 'docx';
                $isOfficeExport = ($this->format === 'file' && in_array($file_type_norm, ['xlsx', 'docx', 'ods', 'odt']));
                $template = $isOfficeExport
                    ? ($defaults['export_template'] ?: 'export')
                    : $defaults['template'];

                // Fall back to 'export' template if configured template folder is missing
                if ($isOfficeExport && !is_dir("./templates/invoices/{$template}")) {
                    $template = 'export';
                }

                $templatePath      = "./templates/invoices/${template}/template.blade.php";
                $template_path     = "templates.invoices.{$template}";
                $css               = $siUrl . "/templates/invoices/${template}/style.css";

                $css_inline = '';
                if ($this->format === 'pdf') {
                    $cssPath = "./templates/invoices/{$template}/style.css";
                    if (is_file($cssPath) && is_readable($cssPath)) {
                        $css_inline = file_get_contents($cssPath) ?: '';
                    }
                }

                $pageActive = "invoices";
                $bladeView->assign('pageActive', $pageActive);

                if (file_exists($templatePath)) {
                    $this->assignTemplateLanguage($preference);
                    $bladeView->assign('biller',                 $biller);
                    $bladeView->assign('customer',               $customer);
                    $bladeView->assign('invoice',                $invoice);
                    $bladeView->assign('invoice_number_of_taxes', $invoice_number_of_taxes);
                    $bladeView->assign('preference',             $preference);
                    $bladeView->assign('logo',                   $logo);
                    $bladeView->assign('template',               $template);
                    $bladeView->assign('invoiceItems',           $invoiceItems);
                    $bladeView->assign('template_path',          $template_path);
                    $bladeView->assign('css',                    $css);
                    $bladeView->assign('css_inline',             $css_inline);
                    $bladeView->assign('customFieldLabels',      $customFieldLabels);
                    $data = $bladeView->fetch("." . $templatePath);
                }
                break;
            }
        }

        return $data;
    }

    function execute()
    {
        $this->showData($this->getData());
    }

    function assignTemplateLanguage($preference)
    {
        if ($pref_language = $preference['language'] and $LANG = getLanguageArray($pref_language) and is_array($LANG) and count($LANG)) {
            global $bladeView;
            $bladeView->assign('LANG', $LANG);
        }
        if ($pref_locale = $preference['locale'] and strlen($pref_locale) > 4) {
            siLocal::setLocaleOverride($pref_locale);
        } else {
            siLocal::setLocaleOverride(null);
        }
    }
}
