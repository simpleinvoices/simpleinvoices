<?php

class email_body
{
    public $email_type;

    public function create() {
        switch ($this->email_type) {
            case "cron_payment":
                $email_body = <<<EOT
$this->customer_name, A PDF copy of your payment receipt is attached.
<br />
<br />
Thank you for using our service,
<br />
$this->biller_name
EOT;
                break;

            case "cron_invoice_reprint":
                $email_body = <<<EOT
$this->customer_name, A PDF copy of your invoice is attached.
<br />
<br />
Thank you for using our service,
<br />
$this->biller_name
EOT;
                break;

            case "cron_invoice":
            default:
                $email_body = <<<EOT
$this->customer_name, A PDF copy of your invoice is attached.
<br />
<br />
Thank you for using our service,
<br />
$this->biller_name
EOT;
                break;
        }

        return $email_body;
    }
}
