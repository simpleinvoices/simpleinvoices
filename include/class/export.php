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

    // Raw structured data stored by getData() for use by xlsx exporter
    protected $raw_data = [];

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
                    $this->exportXlsx();
                } elseif ($file_type === 'docx') {
                    $this->exportDocx($data);
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

    // Export invoice/payment as a real XLSX spreadsheet via PhpSpreadsheet
    private function exportXlsx()
    {
        require_once('./vendor/autoload.php');

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        switch ($this->module) {
            case 'invoice':
                $this->buildInvoiceSheet($sheet);
                $sheet->setTitle('Invoice');
                break;
            case 'payment':
                $this->buildPaymentSheet($sheet);
                $sheet->setTitle('Payment');
                break;
            default:
                $sheet->setCellValue('A1', 'Export not supported for module: ' . $this->module);
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

    // Build a structured invoice sheet
    private function buildInvoiceSheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $d          = $this->raw_data;
        $invoice    = $d['invoice'];
        $biller     = $d['biller'];
        $customer   = $d['customer'];
        $preference = $d['preference'];
        $items      = $d['invoiceItems'] ?? [];

        $moneyFmt  = '#,##0.00';
        $boldStyle = ['font' => ['bold' => true]];
        $row       = 1;

        // ---- Title row ----
        $sheet->setCellValue('A' . $row, $invoice['index_name'] ?? ('Invoice #' . $invoice['id']));
        $sheet->getStyle('A' . $row)->applyFromArray(['font' => ['bold' => true, 'size' => 14]]);
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->setCellValue('D' . $row, 'Date:');
        $sheet->getStyle('D' . $row)->applyFromArray($boldStyle);
        $sheet->setCellValue('E' . $row, $invoice['calc_date'] ?? $invoice['date']);
        $row++;

        $sheet->setCellValue('D' . $row, 'Status:');
        $sheet->getStyle('D' . $row)->applyFromArray($boldStyle);
        $sheet->setCellValue('E' . $row, $preference['status_wording'] ?? '');
        $row += 2;

        // ---- From / To headers ----
        $sheet->setCellValue('A' . $row, 'From');
        $sheet->getStyle('A' . $row)->applyFromArray($boldStyle);
        $sheet->setCellValue('D' . $row, 'To');
        $sheet->getStyle('D' . $row)->applyFromArray($boldStyle);
        $row++;

        // Biller + customer address blocks side by side
        $addrFields = [
            ['name'],
            ['street_address'],
            ['street_address2'],
            ['city', 'state', 'zip_code'],
            ['country'],
            ['phone'],
            ['email'],
        ];
        foreach ($addrFields as $fields) {
            $billerVal   = implode(', ', array_filter(array_map(fn($f) => $biller[$f]   ?? '', $fields)));
            $customerVal = implode(', ', array_filter(array_map(fn($f) => $customer[$f] ?? '', $fields)));
            if ($billerVal !== '' || $customerVal !== '') {
                $sheet->setCellValue('A' . $row, $billerVal);
                $sheet->setCellValue('D' . $row, $customerVal);
                $row++;
            }
        }
        $row++;

        // ---- Items table header ----
        $itemHeaders = ['Qty', 'Description', 'Unit Price', 'Tax', 'Total'];
        $itemCols    = ['A', 'B', 'C', 'D', 'E'];
        foreach ($itemHeaders as $i => $hdr) {
            $sheet->setCellValue($itemCols[$i] . $row, $hdr);
            $sheet->getStyle($itemCols[$i] . $row)->applyFromArray($boldStyle);
        }
        $sheet->getStyle('A' . $row . ':E' . $row)
              ->getBorders()->getBottom()
              ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $row++;

        // ---- Line items ----
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item['quantity']);
            $sheet->setCellValue('B' . $row, $this->invoiceItemXlsxDescription($item));
            $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('C' . $row, (float)$item['unit_price']);
            $sheet->setCellValue('D' . $row, (float)$item['tax_amount']);
            $sheet->setCellValue('E' . $row, (float)$item['total']);
            $sheet->getStyle('C' . $row . ':E' . $row)->getNumberFormat()->setFormatCode($moneyFmt);
            $row++;
        }
        $row++;

        // ---- Totals ----
        $totals = [
            'Total'  => (float)$invoice['total'],
            'Paid'   => (float)$invoice['paid'],
            'Owing'  => (float)$invoice['owing'],
        ];
        foreach ($totals as $label => $amount) {
            $sheet->setCellValue('D' . $row, $label . ':');
            $sheet->getStyle('D' . $row)->applyFromArray($boldStyle);
            $sheet->setCellValue('E' . $row, $amount);
            $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode($moneyFmt);
            $row++;
        }

        // ---- Notes ----
        if (!empty($invoice['note'])) {
            $row++;
            $sheet->setCellValue('A' . $row, 'Notes:');
            $sheet->getStyle('A' . $row)->applyFromArray($boldStyle);
            $row++;
            $sheet->setCellValue('A' . $row, $this->xlsxHtmlToPlainText((string)$invoice['note'], false));
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
        }

        // ---- Invoice detail / payment footer (same fields as PDF & invoice templates) ----
        $detailHeading = trim((string)($preference['pref_inv_detail_heading'] ?? ''));
        $detailLine    = $this->xlsxHtmlToPlainText((string)($preference['pref_inv_detail_line'] ?? ''), false);
        $payMethod     = trim((string)($preference['pref_inv_payment_method'] ?? ''));
        $payLine1      = $this->xlsxPrefPaymentLine(
            $preference['pref_inv_payment_line1_name'] ?? '',
            $preference['pref_inv_payment_line1_value'] ?? ''
        );
        $payLine2      = $this->xlsxPrefPaymentLine(
            $preference['pref_inv_payment_line2_name'] ?? '',
            $preference['pref_inv_payment_line2_value'] ?? ''
        );
        $billerFooter  = $this->xlsxHtmlToPlainText((string)($biller['footer'] ?? ''), false);

        if ($detailHeading !== '' || $detailLine !== '' || $payMethod !== '' || $payLine1 !== '' || $payLine2 !== '' || $billerFooter !== '') {
            $row += 2;
            if ($detailHeading !== '') {
                $sheet->setCellValue('A' . $row, $detailHeading);
                $sheet->getStyle('A' . $row)->applyFromArray($boldStyle);
                $sheet->mergeCells('A' . $row . ':E' . $row);
                $row++;
            }
            if ($detailLine !== '') {
                $sheet->setCellValue('A' . $row, $detailLine);
                $sheet->mergeCells('A' . $row . ':E' . $row);
                $sheet->getStyle('A' . $row)->getFont()->setItalic(true);
                $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
                $row++;
            }
            if ($payMethod !== '') {
                $sheet->setCellValue('A' . $row, $payMethod);
                $sheet->mergeCells('A' . $row . ':E' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
                $row++;
            }
            if ($payLine1 !== '') {
                $sheet->setCellValue('A' . $row, $payLine1);
                $sheet->mergeCells('A' . $row . ':E' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
                $row++;
            }
            if ($payLine2 !== '') {
                $sheet->setCellValue('A' . $row, $payLine2);
                $sheet->mergeCells('A' . $row . ':E' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
                $row++;
            }
            if ($billerFooter !== '') {
                $row++;
                $sheet->setCellValue('A' . $row, $billerFooter);
                $sheet->mergeCells('A' . $row . ':E' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()
                    ->setWrapText(true)
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        // ---- Column widths ----
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(14);
    }

    /**
     * Convert stored HTML to plain text for spreadsheet cells.
     *
     * @param bool $singleLine If true, collapse all whitespace (footer snippets). If false, keep line breaks from
     *                         tags like <p>, <br> so wrap-text cells read like formatted notes.
     */
    private function xlsxHtmlToPlainText(string $html, bool $singleLine): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $normalized = preg_replace('/<\s*br\s*\/?>/i', "\n", $html);
        $normalized = preg_replace(
            '/<\/\s*(p|div|h[1-6]|tr|li|blockquote|pre|section|article)\s*>/i',
            "\n",
            $normalized
        );
        $normalized = preg_replace('/<\s*\/\s*table\s*>/i', "\n", $normalized);

        $text = strip_tags($normalized);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        if ($singleLine) {
            return trim(preg_replace('/\s+/u', ' ', $text));
        }

        $lines = preg_split('/\r\n|\r|\n/', $text);
        $lines = array_map(static function ($line) {
            return trim(preg_replace('/[ \t]+/u', ' ', $line));
        }, $lines);
        $text = implode("\n", $lines);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);

        return trim($text);
    }

    private function xlsxPrefPaymentLine($name, $value): string
    {
        $parts = array_filter(array_map(static fn ($s) => trim((string)$s), [$name ?? '', $value ?? '']));

        return implode(' ', $parts);
    }

    /**
     * Line description for XLSX: match invoice templates (product name + optional item notes).
     * Itemised/consulting rows store the product label in product.description; invoice_items.description is often empty.
     */
    private function invoiceItemXlsxDescription(array $item): string
    {
        $product = $item['product'] ?? null;
        $productName = (is_array($product) && isset($product['description']))
            ? $this->xlsxHtmlToPlainText((string)$product['description'], false)
            : '';
        $lineNote = $this->xlsxHtmlToPlainText((string)($item['description'] ?? ''), false);

        if ($productName !== '' && $lineNote !== '' && strcasecmp($productName, $lineNote) !== 0) {
            return $productName . "\n" . $lineNote;
        }
        if ($productName !== '') {
            return $productName;
        }
        return $lineNote;
    }

    // Build a payment sheet
    private function buildPaymentSheet(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        $d           = $this->raw_data;
        $payment     = $d['payment']     ?? [];
        $invoice     = $d['invoice']     ?? [];
        $biller      = $d['biller']      ?? [];
        $customer    = $d['customer']    ?? [];
        $paymentType = $d['paymentType'] ?? [];

        $boldStyle = ['font' => ['bold' => true]];
        $moneyFmt  = '#,##0.00';
        $row       = 1;

        $sheet->setCellValue('A' . $row, 'Payment Receipt');
        $sheet->getStyle('A' . $row)->applyFromArray(['font' => ['bold' => true, 'size' => 14]]);
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $row += 2;

        $fields = [
            'Payment #'      => $payment['id'] ?? '',
            'Date'           => $payment['ac_date'] ?? '',
            'Amount'         => null, // handled separately for formatting
            'Payment Type'   => $paymentType['pt_description'] ?? '',
            'Invoice'        => $invoice['index_name'] ?? '',
            'Biller'         => $biller['name'] ?? '',
            'Customer'       => $customer['name'] ?? '',
            'Notes'          => $payment['ac_notes'] ?? '',
        ];
        foreach ($fields as $label => $value) {
            $sheet->setCellValue('A' . $row, $label . ':');
            $sheet->getStyle('A' . $row)->applyFromArray($boldStyle);
            if ($label === 'Amount') {
                $sheet->setCellValue('B' . $row, (float)($payment['ac_amount'] ?? 0));
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode($moneyFmt);
            } else {
                $sheet->setCellValue('B' . $row, $value);
            }
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(18);
        $sheet->getColumnDimension('B')->setWidth(40);
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

    // Export invoice/payment as a real DOCX via PhpWord HTML import
    private function exportDocx($html)
    {
        require_once('./vendor/autoload.php');

        // LibreOffice 24+ rejects document.xml if text runs use writeRaw() with &, <, etc.; escaping fixes that.
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);
        if (class_exists(\ZipArchive::class)) {
            \PhpOffice\PhpWord\Settings::setZipClass(\PhpOffice\PhpWord\Settings::ZIPARCHIVE);
        }

        $fragment = $this->stripIllegalXmlControlChars($this->htmlFragmentForPhpWord($html));

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

    function getData()
    {
        global $smarty;
        global $siUrl;

        switch ($this->module) {
            case "statement":
            {
                $invoice = new invoice();
                $invoice->domain_id = $this->domain_id;
                $invoice->biller    = $this->biller_id;
                $invoice->customer  = $this->customer_id;

                if ($this->filter_by_date == "yes") {
                    if (isset($this->start_date)) $invoice->start_date = $this->start_date;
                    if (isset($this->end_date))   $invoice->end_date   = $this->end_date;
                    if (isset($this->start_date) && isset($this->end_date)) $invoice->having = "date_between";
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

                foreach ($invoices as $i => $row) {
                    $statement['total'] = $statement['total'] + $row['invoice_total'];
                    $statement['owing'] = $statement['owing'] + $row['owing'];
                    $statement['paid']  = $statement['paid']  + $row['INV_PAID'];
                }

                $templatePath   = "./templates/default/statement/index.blade.php";
                $biller_details = getBiller($this->biller_id, $this->domain_id);
                $billers        = $biller_details;
                $customer_details = getCustomer($this->customer_id, $this->domain_id);

                $this->file_name = "statement_{$this->biller_id}_{$this->customer_id}_{$invoice->start_date}_{$invoice->end_date}";

                $smarty->assign('biller_id',       $biller_id);
                $smarty->assign('biller_details',  $biller_details);
                $smarty->assign('billers',         $billers);
                $smarty->assign('customer_id',     $customer_id);
                $smarty->assign('customer_details', $customer_details);
                $smarty->assign('show_only_unpaid', $show_only_unpaid);
                $smarty->assign('filter_by_date',  $this->filter_by_date);
                $smarty->assign('invoices',        $invoices);
                $smarty->assign('start_date',      $this->start_date);
                $smarty->assign('end_date',        $this->end_date);
                $smarty->assign('statement',       $statement);
                $data = $smarty->fetch("." . $templatePath);
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

                // Store raw data for XLSX export
                $this->raw_data = compact('payment', 'invoice', 'biller', 'customer', 'paymentType', 'preference');

                $smarty->assign("payment",          $payment);
                $smarty->assign("invoice",          $invoice);
                $smarty->assign("biller",           $biller);
                $smarty->assign("logo",             $logo);
                $smarty->assign("customer",         $customer);
                $smarty->assign("invoiceType",      $invoiceType);
                $smarty->assign("paymentType",      $paymentType);
                $smarty->assign("preference",       $preference);
                $smarty->assign("customFieldLabels", $customFieldLabels);
                $smarty->assign('pageActive',       'payment');
                $smarty->assign('active_tab',       '#money');

                $css = $siUrl . "/templates/invoices/default/style.css";
                $smarty->assign('css', $css);

                $templatePath = "./templates/default/payments/print.blade.php";
                $data = $smarty->fetch("." . $templatePath);
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
                $defaults    = getSystemDefaults($this->domain_id);
                $logo        = getLogo($biller);
                $logo        = str_replace(" ", "%20", $logo);
                $invoiceItems = $invoiceobj->getInvoiceItems($this->id, $this->domain_id);

                $spc2us_pref    = str_replace(" ", "_", $invoice['index_name']);
                $this->file_name = $spc2us_pref;

                // Store raw data for XLSX export
                $this->raw_data = compact('invoice', 'biller', 'customer', 'preference', 'invoiceItems');

                $customFieldLabels = getCustomFieldLabels($this->domain_id);
                $template          = $defaults['template'];
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
                $smarty->assign('pageActive', $pageActive);

                if (file_exists($templatePath)) {
                    $this->assignTemplateLanguage($preference);
                    $smarty->assign('biller',                 $biller);
                    $smarty->assign('customer',               $customer);
                    $smarty->assign('invoice',                $invoice);
                    $smarty->assign('invoice_number_of_taxes', $invoice_number_of_taxes);
                    $smarty->assign('preference',             $preference);
                    $smarty->assign('logo',                   $logo);
                    $smarty->assign('template',               $template);
                    $smarty->assign('invoiceItems',           $invoiceItems);
                    $smarty->assign('template_path',          $template_path);
                    $smarty->assign('css',                    $css);
                    $smarty->assign('css_inline',             $css_inline);
                    $smarty->assign('customFieldLabels',      $customFieldLabels);
                    $data = $smarty->fetch("." . $templatePath);
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
            global $smarty;
            $smarty->assign('LANG', $LANG);
        }
        if ($pref_locale = $preference['locale'] and strlen($pref_locale) > 4) {
            global $config;
            $config->local->locale = $pref_locale;
        }
    }
}
