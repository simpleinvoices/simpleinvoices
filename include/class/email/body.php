<?php
class email_body {
	
	public function create()
	{

		$email_body = <<<EOT
Hi $this->customer_name,
<br />
<br />
Attached is your PDF copy of $this->invoice_name
<br />
<br />
Cheers
<br />
<br />
$this->biller_name
EOT;

		return $email_body;
	}

}
