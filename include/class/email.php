<?php

class email
{
	public $format;

	public $notes;

	public $file_location;
	public $attachment;
	#ublic $module;
	public $id;
	public $start_date;
	public $end_date;
	public $biller_id;
	public $customer_id;

	function send()
	{
		global $config;
		
		$mail = new PHPMailer(true);

		try {
			$mail->Host = $config->email->host;

			if($config->email->smtp_auth == true) {
				$mail->SMTPAuth = true;
				$mail->Username = $config->email->username;
				$mail->Password = $config->email->password;
				$mail->SMTPSecure = $config->email->secure;
				$mail->Port = $config->email->smtpport;
			}

			$mail->isHTML(true);
			$mail->Subject = $this->subject;
			$mail->msgHTML($this->notes);
			$mail->setFrom($this->from, $this->from_friendly);

			$to_addresses = preg_split('/\s*[,;]\s*/', $this->to);
			if (!empty($to_addresses)) {
				foreach ($to_addresses as $to) {
					$mail->addAddress($to);
				}
			}
			if (!empty($this->bcc)) {
				$bcc_addresses = preg_split('/\s*[,;]\s*/', $this->bcc);
				foreach ($bcc_addresses as $bcc) {
					$mail->addBCC($bcc);
				}
			}

			//allow self signed certs
			$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
						)
					);
			if($this->attachment)
			{
				$mail->addAttachment('./tmp/cache/'.$this->attachment);
			}
			$mail->Send();

		} catch  (phpmailerException $e) {
			echo '<strong>Mail Protocol Exception:</strong> ' .  $e->getMessage();
			exit;
		}

		// Remove temp invoice
		unlink("./tmp/cache/$this->attachment");

		switch ($this->format)
		{
			case "invoice":
				{

					// Create succes message
					$message  = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\">";
					$message .= "<br />$this->attachment has been emailed";

					break;
				}	
			case "statement":
				{

					// Create succes message
					$message  = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=statement&amp;view=index\">";
					$message .= "<br />$this->attachment has been emailed";

					break;
				}	
			case "cron":
				{

					// Create succes message
					$message .= "<br />Cron email for today has been sent";

					break;
				}
			case "cron_invoice":
				{

					// Create succes message
					$message .= "$this->attachment has been emailed";

					break;

				}	
		}	



		return $message;
	}

	public function set_subject($type='')
	{

		switch ($type)
		{
			case "invoice_eway":
				{

					$message = "$this->invoice_name ready for automatic credit card payment";

					break;
				}	
			case "invoice_eway_receipt":
				{

					$message = "$this->invoice_name secure credit card payment successful";

					break;
				}	
			case "invoice_receipt":
				{

					$message = "$this->attachment has been emailed";

					break;
				}	
			case "invoice":
			default:
				{

					$message = "$this->attachment from $this->from_friendly";

					break;
				}	
		}    

		return $message;
	}

	public function get_admin_email()
	{

		global $db;
		$domain_id = domain_id::get($this->domain_id);

		$sql = "select email from si_user where role_id = '1' and domain_id =:domain_id LIMIT 1";
		$sth  = $db->query($sql,':domain_id',$domain_id) or die(htmlsafe(end($dbh->errorInfo())));

		return $sth->fetchColumn();

	}

}
