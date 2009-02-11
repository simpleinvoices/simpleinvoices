<?php

class export
{
	public $format;
	public $module;
	public $id;

	function showData($data)
	{
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
				pdfThis($data);
				exit();	
				break;
			}		
			case "file":
			{
				/* The Export code - supports any file extensions - excel/word/open office - what reads html*/
				if (isset($_GET['export'])) {
					$template = "export";
					$file_extension = $_GET['export'];
					header("Content-type: application/octet-stream");
					//header("Content-type: application/x-msdownload");
					header("Content-Disposition: attachment; filename=$preference[pref_inv_heading]$invoice[id].$file_extension");
					header("Pragma: no-cache");
					header("Expires: 0");
				}
				
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
			case "invoice":
			{
			
				$invoice = getInvoice($this->id);
				$customer = getCustomer($invoice['customer_id']);
				$biller = getBiller($invoice['biller_id']);
				$preference = getPreference($invoice['preference_id']);
				$defaults = getSystemDefaults();
				$logo = getLogo($biller);
				$logo = str_replace(" ", "%20", $logo);
				$invoiceItems = invoice::getInvoiceItems($this->id);
				
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