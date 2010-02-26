<?php
class email_body {
	
	public function create()
	{

		$email_body = "Hi ".$this->customer_name.",
<br />
<br />
Attached is your PDF copy of ".$this->invoice_name." from ".$this->biller_name;


		return $email_body;
	}

}
