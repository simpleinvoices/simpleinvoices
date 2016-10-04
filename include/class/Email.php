<?php
class Email {
    public $attachment;
    public $biller_id;
    public $customer_id;
    public $domain_id;
    public $end_date;
    public $file_location;
    public $format;
    public $id;
    public $notes;
    public $start_date;

    function send() {
        global $config;

        // Create authentication with SMTP server
        $authentication = array();
        if ($config->email->smtp_auth) {
            // @formatter:off
            $authentication = array('auth'     => 'login',
                                    'username' => $config->email->username,
                                    'password' => $config->email->password,
                                    'ssl'      => $config->email->secure,
                                    'port'     => $config->email->smtpport);
            // @formatter:on
        }

        if ($config->email->use_local_sendmail == false) {
            $transport = new Zend_Mail_Transport_Smtp($config->email->host, $authentication);
        }

        // Create e-mail message
        $mail = new Zend_Mail('utf-8');
        $mail->setType(Zend_Mime::MULTIPART_MIXED);
        $mail->setBodyText($this->notes);
        $mail->setBodyHTML($this->notes);
        $mail->setFrom($this->from, $this->from_friendly);

        $to_addresses = preg_split('/\s*[,;]\s*/', $this->to);
        if (!empty($to_addresses)) {
            foreach ($to_addresses as $to) {
                $mail->addTo($to);
            }
        }

        if (!empty($this->bcc)) {
            $bcc_addresses = preg_split('/\s*[,;]\s*/', $this->bcc);
            foreach ($bcc_addresses as $bcc) {
                $mail->addBcc($bcc);
            }
        }
        $mail->setSubject($this->subject);

        if (!empty($this->attachment)) {
            // Create attachment
            $content = file_get_contents('./tmp/cache/' . $this->attachment);
            $at = $mail->createAttachment($content);
            $at->type = 'application/pdf';
            $at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
            $at->filename = $this->attachment;
        }

        // Send e-mail through SMTP
        try {
            if ($config->email->use_local_sendmail) {
                $mail->send();
            } else {
                $mail->send($transport);
            }
        } catch (Zend_Mail_Protocol_Exception $e) {
            echo '<strong>Zend Mail Protocol Exception:</strong> ' . $e->getMessage();
            error_log("Email.php mail error - " . print_r($e,true));
            exit();
        }

        // Remove temp invoice if present
        if (!empty($this->attachment)) unlink("./tmp/cache/$this->attachment");

        switch ($this->format) {
            case "cron":
                // Create succes message
                $message .= "<br />Cron email for today has been sent";
                break;
            
            case "cron_invoice":
                // Create succes message
                $message .= "$this->attachment has been emailed";
                break;

            case "invoice":
                // Create succes message
                $message = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\">";
                $message .= "<br />$this->attachment has been emailed";
                break;

            case "payment":
                // Create succes message
                $message = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=payment&amp;view=manage\">";
                $message .= "<br />$this->attachment has been emailed";
                break;

            case "statement":
                // Create succes message
                $message = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=statement&amp;view=index\">";
                $message .= "<br />$this->attachment has been emailed";
                break;

            default:
                error_log("include/class/Email.php - Undefined format, " . $this->format);
                echo '<strong>Undefined format (' . $this->format . ')</strong>';
                exit();
        }

        return $message;
    }

    public function set_subject($type = '') {
        switch ($type) {
            case "invoice_eway":
                $message = "$this->invoice_name ready for automatic credit card payment";
                break;

            case "invoice_eway_receipt":
                $message = "$this->invoice_name secure credit card payment successful";
                break;

            case "invoice_receipt":
                $message = "$this->attachment has been emailed";
                break;

            case "invoice":
            default:
                $message = "$this->attachment from $this->from_friendly";
                break;

        }

        return $message;
    }

    public function get_admin_email() {
        global $pdoDb;

        $jn = new Join("LEFT", "user_role", "r");
        $jn->addSimpleItem("u.role_id", new DbField("r.id"));
        $pdoDb->addToJoins($jn);

        $pdoDb->addSimpleWhere("r.name", "administrator", "AND");
        $pdoDb->addSimpleWhere("domain_id", domain_id::get());

        $pdoDb->setSelectList("u.email");
        return $pdoDb->request("SELECT", "user", "u");
    }

    /**
     * Verify the $_FILES[] values for upload files.
     * @param $varname The variable name for the upload entry.
     */
    private function verifyFiles($varname, $validtypes, $max_size, &$lines, &$files) {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat as invalid.
        if (!isset($_FILES[$varname]['error'])) {
            $lines[] = '<p class="warnMsg">Invalid value found. Verify all fields set and re-submit.</p>';
            return false;
        }
    
        if (is_array($_FILES[$varname]['error'])) {
            $files = array();
            for ($i = 0; $i < count($_FILES[$varname]['error']); $i++) {
                $files[] = $_FILES[$varname]['name'][$i];
                if (!verifyOneFile($varname, $_FILES[$varname]['error'][$i], $validtypes, $_FILES[$varname]['size'][$i],
                        $max_size, $_FILES[$varname]['tmp_name'][$i], $_FILES[$varname]['name'][$i], $lines)) {
                            return false;
                        }
            }
        } else {
            $files = $_FILES[$varname]['name'];
            if (!verifyOneFile($varname, $_FILES[$varname]['error'], $validtypes, $_FILES[$varname]['size'], $max_size,
                    $_FILES[$varname]['tmp_name'], $_FILES[$varname]['name'], $lines)) {
                        return false;
                    }
        }
        return true;
    }
    
    private function verifyOneFile($varname, $error, $validtypes, $size, $max_size, $tmp_name, $name, &$lines) {
        // Check $error value.
        if (strstr($varname, 'pic')) {
            $desc = 'Picture';
        } else {
            $desc = 'Media';
        }
    
        switch ($error) {
            case UPLOAD_ERR_OK:
                break;
    
            case UPLOAD_ERR_NO_FILE:
                $lines[] = '<p class="warnMsg">No ' . $desc . ' file transmitted. Verify file specified and re-submit.</p>';
                return false;
    
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $lines[] = '<p class="warnMsg">' . $desc . ' file,' . $name . ', is too large.</p>';
                return false;
    
            default:
                $lines[] = '<p class="warnMsg">Undefined error reported for ' . $desc . '.</p>';
                return false;
        }
    
        if ($max_size < 1024) {
            $maxsiz = 1024 * 1024 * 1024;
            $maxsizstr = '100Mb';
        } else if ($max_size < (1024 * 1024)) {
            $maxsiz = $max_size;
            $maxsizstr = sprintf('%uKb', ($max_size / 1024));
        } else {
            $maxsiz = $max_size;
            $maxsizstr = sprintf('%uMb', ($max_size / (1024 * 1024)));
        }
    
        // You should also check file size here.
        if ($size > $maxsiz) {
            $lines[] = '<p class="warnMsg">' . $desc . ' file,' . $name . ' exceeds specified file size limit, ' . $maxsizstr .
            '.</p>';
            return false;
        }
    
        $typs = $_FILES[$varname]['type'];
        if (is_string($typs)) $typs = array($typs);
        foreach($typs as $typ) {
            $error=false;
            if (($key = array_search($typ, $validtypes)) === false) {
                $error = true;
            } else if (strlen($key) > 3 && substr($key,3) != "-any") {
                $browser = strtolower(getBrowser());
                $typ_browser = strtolower(substr($key,4));
                if ($browser != $typ_browser) $error = true;
            }
    
            if ($error) {
                error_log("media_form_functions verifyOneFile(): File type, $typ, not allowed. Validtypes are: " .
                        print_r($validtypes,true));
                $lines[] = '<p class="warnMsg">' . $desc . ' file, ' . $name . ' is not an allowed file type.</p>';
                return false;
            }
        }
    
        return true;
    }
    
}
