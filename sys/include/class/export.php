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

	function showData($data)
	{

        if($this->file_name =='')
        {
            switch ($this->module)
            {
                case "payment":
                {
                    $this->file_name = 'payment'.$this->id;
                    break;
                }
            }
        }


		//echo "export show data";
		switch ($this->format)
		{
			case "print":
			{
				echo($data);
				break;
			}

			case "pdf":
			{
				pdfThis($data, $this->file_location, $this->file_name);
				$this->file_location == "download" ? exit():"" ;
				break;
			}
			case "file":
			{
				$invoice = getInvoice($this->id);
				$preference = getPreference($invoice['preference_id']);

				//xls/doc export no longer uses the export template
				//$template = "export";

				header("Content-type: application/octet-stream");
				//header("Content-type: application/x-msdownload");
				switch ($this->module)
				{
					case "statement":
					{
						header('Content-Disposition: attachment; filename="statement.'.addslashes($this->file_type).'"');
						break;
					}
					case "payment":
					{
						header('Content-Disposition: attachment; filename="payment'.addslashes($this->id.'.'.$this->file_type).'"');
						break;
					}
					case "database":
					{
						$today = date("YmdGisa");
						header('Content-Disposition: attachment; filename="simple_invoices_backup_'.$today.'.'.$this->file_type.'"');
						break;
					}
					default:
					{
						header('Content-Disposition: attachment; filename="'.addslashes($preference[pref_inv_heading].$this->id.'.'.$this->file_type).'"');
						break;
					}
				}

				header("Pragma: no-cache");
				header("Expires: 0");

				echo($data);

				break;
			}
		}
	}

	function getData()
	{
		//echo "export - get data";
		global $smarty;
		global $siUrl;
		global $include_dir;

		switch ($this->module)
		{
			case "database":
			{
				$oBack = new backup_db;
				$oBack->start_backup();
				$data = $oBack->output;

				break;
			}
			case "statement":
			{
				$invoice = new invoice();
				$invoice->biller = $this->biller_id;
				$invoice->customer = $this->customer_id;

				if ( $this->filter_by_date =="yes" )
				{
					if ( isset($this->start_date) )
					{
						$invoice->start_date = $this->start_date;
					}
					if ( isset($this->end_date) )
					{
						$invoice->end_date = $this->end_date;
					}

					if ( isset($this->start_date) AND isset($this->end_date) )
					{
						$invoice->having = "date_between";
					}
					$having_count = '1';
				}

				if ( $this->show_only_unpaid == "yes")
				{
					if ($having_count == '1')
					{

						$invoice->having_and = "money_owed";
					    $having_count = '2';

					} else {

						$invoice->having = "money_owed";
					    $having_count = '1';

					}
				}

				if ( $this->show_only_real == "yes")
				{
					if ($having_count == '2')
					{

						$invoice->having_and2 = "real";

					} elseif ($having_count == '1') {

						$invoice->having_and = "real";

					} else {

						$invoice->having = "real";

					}
				}

				$invoice_all = $invoice->select_all('count');

				$invoices = $invoice_all->fetchAll();

				foreach ($invoices as $i => $row) {
					$statement['total'] = $statement['total'] + $row['invoice_total'];
					$statement['owing'] = $statement['owing'] + $row['owing'] ;
					$statement['paid'] = $statement['paid'] + $row['INV_PAID'];

				}

                // ToDo: THIS FILE IS MISSING!!
				$templatePath = $include_dir . "sys/templates/default/statement/index.tpl";

				$biller_details = getBiller($this->biller_id);
				$customer_details = customer::get($this->customer_id);

				$this->file_name = "statement_".$this->biller_id."_".$this->customer_id."_".$invoice->start_date."_".$invoice->end_date;

				$smarty -> assign('biller_id', $biller_id);
				$smarty -> assign('biller_details', $biller_details);
				$smarty -> assign('customer_id', $customer_id);
				$smarty -> assign('customer_details', $customer_details);

				$smarty -> assign('show_only_unpaid', $show_only_unpaid);
				$smarty -> assign('show_only_real', $show_only_real);
				$smarty -> assign('filter_by_date', $this->filter_by_date);

				$smarty -> assign('invoices', $invoices);
				$smarty -> assign('start_date', $this->start_date);
				$smarty -> assign('end_date', $this->end_date);

				$smarty -> assign('invoices',$invoices);
				$smarty -> assign('statement',$statement);
				$data = $smarty -> fetch(".".$templatePath);

				break;
			}
            case "payment":
            {
                $SI_PAYMENT_TYPES = new SimpleInvoices_Db_Table_PaymentTypes();
                
                $payment = getPayment($this->id);

                /*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
                $invoice = getInvoice($payment['ac_inv_id']);
                $biller = getBiller($payment['biller_id']);
                $logo = getLogo($biller);
                $logo = str_replace(" ", "%20", $logo);
                $customer = customer::get($payment['customer_id']);
                $invoiceType = getInvoiceType($invoice['type_id']);
                $customFieldLabels = getCustomFieldLabels();
                $paymentType = $SI_PAYMENT_TYPES->($payment['ac_payment_type']);
                $preference = getPreference($invoice['preference_id']);

                $smarty -> assign("payment",$payment);
                $smarty -> assign("invoice",$invoice);
                $smarty -> assign("biller",$biller);
                $smarty -> assign("logo",$logo);
                $smarty -> assign("customer",$customer);
                $smarty -> assign("invoiceType",$invoiceType);
                $smarty -> assign("paymentType",$paymentType);
                $smarty -> assign("preference",$preference);
                $smarty -> assign("customFieldLabels",$customFieldLabels);

                $smarty -> assign('pageActive', 'payment');
                $smarty -> assign('active_tab', '#money');

				$css = $siUrl."/sys/templates/invoices/default/style.css";
				$smarty -> assign('css',$css);

                // ToDo: THIS FILE IS MISSING!!
                $templatePath = $include_dir . "sys/templates/default/payments/print.tpl";
				$data = $smarty -> fetch(".".$templatePath);
                break;
            }
			case "invoice":
			{
                $SI_SYSTEM_DEFAULTS = new SimpleInvoices_Db_Table_SystemDefaults();
                
				$invoice = invoice::select($this->id);
 			    $invoice_number_of_taxes = numberOfTaxesForInvoice($this->id);
				$customer = customer::get($invoice['customer_id']);
				$biller = biller::select($invoice['biller_id']);
				$preference = getPreference($invoice['preference_id']);
				$defaults = $SI_SYSTEM_DEFAULTS->fetchAll();
				$logo = getLogo($biller);
				$logo = str_replace(" ", "%20", $logo);
				$invoiceItems = invoice::getInvoiceItems($this->id);

				$spc2us_pref = str_replace(" ", "_", $invoice['index_name']);
				$this->file_name = $spc2us_pref;

				$customFieldLabels = getCustomFieldLabels();
				$customFieldDisplay = getCustomFieldDisplay();

				/*Set the template to the default*/
				$template = $defaults['template'];

                // Instead of appending the CSS we are going to inject it allowing
                // a cleaner hierarchy tree while allowing public directories
				$css_file = $include_dir ."/sys/templates/invoices/${template}/style.css";
                
                if (file_exists($css_file)) {
                    $css = file_get_contents($css_file);
                    if ($css) {
                        // Create the tags
                        $css = '<style type="text/css" media="all">' . $css . '</style>';
                    }
                } else {
                    $css = '';
                }

                $smarty->addTemplateDir($include_dir . "sys/templates/invoices/${template}/", 'Invoice_' . $template);
				$smarty->addPluginsDir($include_dir ."sys/templates/invoices/${template}/plugins/");

				$pageActive = "invoices";
				$smarty->assign('pageActive', $pageActive);

                if ($smarty->templateExists('file:[Invoice_' . $template . ']template.tpl')) {
					$smarty -> assign('biller',$biller);
					$smarty -> assign('customer',$customer);
					$smarty -> assign('invoice',$invoice);
					$smarty -> assign('invoice_number_of_taxes',$invoice_number_of_taxes);
					$smarty -> assign('preference',$preference);
					$smarty -> assign('logo',$logo);
					$smarty -> assign('template',$template);
					$smarty -> assign('invoiceItems',$invoiceItems);
					$smarty -> assign('css',$css);
					$smarty -> assign('customFieldLabels',$customFieldLabels);
					$smarty -> assign('customFieldDisplay',$customFieldDisplay);

					$data = $smarty->fetch('file:[Invoice_' . $template . ']template.tpl');
				}

				break;
			}

		}

		return $data;

	}

	function execute()
	{
		$this->showData( $this->getData() );
	}

}
