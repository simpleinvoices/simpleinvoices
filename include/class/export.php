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
		
		switch ($this->module)
		{
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
					$having_count = 1;
				}

				if ( $this->show_only_unpaid == "yes") 
				{
					if ($having_count == 1) 
					{
						$invoice->having_and = "money_owed";
					} else {
						$invoice->having = "money_owed";
	
					}
				}

				$invoice_all = $invoice->select_all('count');

				$invoices = $invoice_all->fetchAll();

				foreach ($invoices as $i => $row) {
					$statement['total'] = $statement['total'] + $row['invoice_total'];
					$statement['owing'] = $statement['owing'] + $row['owing'] ;
					$statement['paid'] = $statement['paid'] + $row['INV_PAID'];
					
				}

				$templatePath = "./templates/default/statement/index.tpl";
			
				$biller_details = getBiller($this->biller_id);
				$customer_details = getCustomer($this->customer_id);

				$this->file_name = "statement_".$this->biller_id."_".$this->customer_id."_".$invoice->start_date."_".$invoice->end_date;

				$smarty -> assign('biller_id', $biller_id);
				$smarty -> assign('biller_details', $biller_details);
				$smarty -> assign('customer_id', $customer_id);
				$smarty -> assign('customer_details', $customer_details);

				$smarty -> assign('show_only_unpaid', $show_only_unpaid);
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
                $payment = getPayment($this->id);

                /*Code to get the Invoice preference - so can link from this screen back to the invoice - START */
                $invoice = getInvoice($payment['ac_inv_id']);
                $biller = getBiller($payment['biller_id']);
                $logo = getLogo($biller);
                $logo = str_replace(" ", "%20", $logo);
                $customer = getCustomer($payment['customer_id']);
                $invoiceType = getInvoiceType($invoice['type_id']);
                $customFieldLabels = getCustomFieldLabels();
                $paymentType = getPaymentType($payment['ac_payment_type']);
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

				$css = $siUrl."/templates/invoices/default/style.css";
				$smarty -> assign('css',$css);
				
                $templatePath = "./templates/default/payments/print.tpl";
				$data = $smarty -> fetch(".".$templatePath);
                break;
            }
			case "invoice":
			{
			
				$invoice = invoice::select($this->id);
 			        $invoice_number_of_taxes = numberOfTaxesForInvoice($this->id);
				$customer = getCustomer($invoice['customer_id']);
				$biller = biller::select($invoice['biller_id']);
				$preference = getPreference($invoice['preference_id']);
				$defaults = getSystemDefaults();
				$logo = getLogo($biller);
				$logo = str_replace(" ", "%20", $logo);
				$invoiceItems = invoice::getInvoiceItems($this->id);
				
				$spc2us_pref = str_replace(" ", "_", $invoice['index_name']);
				$this->file_name = $spc2us_pref;
				
				$customFieldLabels = getCustomFieldLabels();
	
				/*Set the template to the default*/
				$template = $defaults['template'];
			
				$templatePath = "./templates/invoices/${template}/template.tpl";
				$template_path = "../templates/invoices/${template}";
				$css = $siUrl."/templates/invoices/${template}/style.css";
				$pluginsdir = "./templates/invoices/${template}/plugins/";

				//$smarty = new Smarty();
				
				$smarty -> plugins_dir = $pluginsdir;
				 
				$pageActive = "invoices";
				$smarty->assign('pageActive', $pageActive);
				
				if(file_exists($templatePath)) {
					//echo "test";
					$smarty -> assign('biller',$biller);
					$smarty -> assign('customer',$customer);
					$smarty -> assign('invoice',$invoice);
					$smarty -> assign('invoice_number_of_taxes',$invoice_number_of_taxes);
					$smarty -> assign('preference',$preference);
					$smarty -> assign('logo',$logo);
					$smarty -> assign('template',$template);
					$smarty -> assign('invoiceItems',$invoiceItems);
					$smarty -> assign('template_path',$template_path);
					$smarty -> assign('css',$css);
					$smarty -> assign('customFieldLabels',$customFieldLabels);
					
					$data = $smarty -> fetch(".".$templatePath);
				
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
?>
