<?php
class cron {
	
 	public $start_date;
	public function insert()
	{
        	global $db;
        	global $auth_session;

		$domain_id = domain_id::get($this->domain_id);
		$today = date('Y-m-d');

        
	        $sql = "INSERT INTO ".TB_PREFIX."cron (
				domain_id,
				invoice_id,
				start_date,
				end_date,
				recurrence,
				recurrence_type,
				email_biller,
				email_customer
			) VALUES (
				:domain_id,
				:invoice_id,
				:start_date,
				:end_date,
				:recurrence,
				:recurrence_type,
				:email_biller,
				:email_customer
			)";
        	$sth = $db->query($sql,
				':domain_id',$domain_id, 
				':invoice_id',$this->invoice_id,
				':start_date',$this->start_date,
				':end_date',$this->end_date,
				':recurrence',$this->recurrence,
				':recurrence_type',$this->recurrence_type,
				':email_biller',$this->email_biller,
				':email_customer',$this->email_customer
			) or die(htmlspecialchars(end($dbh->errorInfo())));
        
 	       return $sth;

	}

	public function edit()
	{

	}

	public function delete()
	{

	}

    	public function select_all($type='', $dir='DESC', $rp='25', $page='1')
	{
		global $LANG;
		global $db;
		/*SQL Limit - start*/
		$start = (($page-1) * $rp);
		$limit = "LIMIT ".$start.", ".$rp;
		/*SQL Limit - end*/

		/*SQL where - start*/
		$query = (isset($_POST['query'])) ? $_POST['query'] : "" ;
		$qtype = (isset($_POST['qtype'])) ? $_POST['qtype'] : "" ;

		$where = (isset($_POST['query'])) ? "  AND $qtype LIKE '%$query%' " : "";
		/*SQL where - end*/
		

		/*Check that the sort field is OK*/
		if (!empty($this->sort)) {
		    $sort = $this->sort;
		} else {
		    $sort = "id";
		}

		if($type =="count")
		{
		    //unset($limit);
		    $limit="";
		}


		$sql = "SELECT
				cron.* ,
                       		(SELECT CONCAT(pf.pref_description,' ',iv.index_id)) as index_name
			FROM 
				".TB_PREFIX."cron cron,
				".TB_PREFIX."invoices iv,
				".TB_PREFIX."preferences pf
			 WHERE 
				cron.domain_id = :domain_id
				and
				cron.invoice_id = iv.id
				and 
				iv.preference_id = pf.pref_id 
			GROUP BY
			    cron.id
			ORDER BY
			$sort $dir
			$limit";

		$sth = $db->query($sql,':domain_id',domain_id::get($this->domain_id)) or die(htmlspecialchars(end($dbh->errorInfo())));
		if($type =="count")
		{
			return $sth->rowCount();
		} else {
			return $sth->fetchAll();
		}
	}

	public function select()
	{
		global $LANG;
		global $db;

		$sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id AND id = :id";
		$sth = $db->query($sql,':domain_id',domain_id::get($this->domain_id), ':id',$this->id) or die(htmlspecialchars(end($dbh->errorInfo())));

		return $sth->fetch();
	}

	public function run()
	{
        	global $db;
        	global $auth_session;

		$today = date('Y-m-d');
		$domain_id = domain_id::get($this->domain_id);
		$return ="";

		$cron_log = new cronlog();
		$cron_log->run_date = empty($this->run_date) ? $today : $this->run_date;
		$check_cron_log = $cron_log->check();        	

		//only proceed if cron has not been run for today
		if ($check_cron_log == 0)
		{
			$sql = "SELECT * FROM ".TB_PREFIX."cron WHERE domain_id = :domain_id";
			$sth  = $db->query($sql,':domain_id',$domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
			$cron = new cron();
			$data = $cron->select_all('');
		        $data = $sth->fetchAll();
			#print_r($data);
			foreach ($data as $key=>$value)
			{
				$run_cron ='false';
				$start_date = date('Y-m-d', strtotime( $data[$key]['start_date'] ) );
				$end_date = $data[$key]['end_date'] ;

				$diff = number_format((strtotime($today) - strtotime($start_date)) / (60 * 60 * 24),0);
				
		
				//only check if diff is positive
				if (($diff >= 0) AND ($end_date =="" OR $end_date >= $today))
				{

					if($data[$key]['recurrence_type'] == 'day')
					{
						$modulus = $diff % $data[$key]['recurrence'] ;
						if($modulus == 0)
						{ 
							$run_cron ='true';
						} else {
							#$return .= "cron does not runs TODAY-days";

						}

					}
					if($data[$key]['recurrence_type'] == 'week')
					{
						$period = 7 * $data[$key]['recurrence'];
						$modulus = $diff % $period ;
						if($modulus == 0)
						{ 
							$run_cron ='true';
						} else {
							#$return .= "cron is not runs TODAY-week";
						}

					}
					if($data[$key]['recurrence_type'] == 'month')
					{
						$start_day = date('d', strtotime( $data[$key]['start_date'] ) );
						$start_month = date('m', strtotime( $data[$key]['start_date'] ) );
						$start_year = date('Y', strtotime( $data[$key]['start_date'] ) );
						$today_day = date('d');	
						$today_month = date('m');	
						$today_year = date('Y'); 	

						$months = ($today_month-$start_month)+12*($today_year-$start_year);
						$modulus =  $months % $data[$key]['recurrence']  ;
						if( ($modulus == 0) AND ( $start_day == $today_day ) )
						{ 
							$run_cron ='true';
						} else {
							#$return .= "cron is not runs TODAY-month";
						}

					}
					if($data[$key]['recurrence_type'] == 'year')
					{
						$start_day = date('d', strtotime( $data[$key]['start_date'] ) );
						$start_month = date('m', strtotime( $data[$key]['start_date'] ) );
						$start_year = date('Y', strtotime( $data[$key]['start_date'] ) );
						$today_day = date('d');	
						$today_month = date('m');	
						$today_year = date('Y'); 	

						$years = $today_year-$start_year;
						$modulus =  $years % $data[$key]['recurrence']  ;
						if( ($modulus == 0) AND ( $start_day == $today_day ) AND  ( $start_month == $today_month ) )
						{ 
							$run_cron ='true';
						} else {
							#$return .= "cron is not runs TODAY-year";
						}


					}
					//run the recurrence for this invoice
					if ($run_cron == 'true')
					{
						$return .= "<br />Cron for ".$data[$key]['index_name']." with start date of ".$data[$key]['start_date'].", end date of '".$data[$key]['end_date']."' where it runs each ".$data[$key]['recurrence']." ".$data[$key]['recurrence_type']." was run today :: Info diff=".$diff."<br />";

						$ni = new invoice();
						$ni->id = $data[$key]['invoice_id'];
						$ni->recur();

						$invoice = getInvoice($data[$key]['invoice_id']);
						$preference = getPreference($invoice['preference_id']);
						$biller = getBiller($invoice['biller_id']);
						$customer = getCustomer($invoice['customer_id']);
						#print_r($customer);
						#create PDF nameVj
						$spc2us_pref = str_replace(" ", "_", $data[$key]['index_name']);
						$pdf_file_name = $spc2us_pref . '.pdf';
							
						// Create invoice
						$export = new export();
						$export -> format = "pdf";
						$export -> file_location = 'file';
						$export -> module = 'invoice';
						$export -> id = $invoice_id;
						$export -> execute();

						#$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);

						$email = new email();
						$email -> format = 'cron_invoice';
						$email -> notes = "Hi ".$customer['name'].",<br /><br /> Attached is your PDF copy of ".$data[$key]['index_name']." from ".$biller['name'];
						$email -> from = $biller['email'];
						$email -> from_friendly = $biller['name'];
						$email -> to = $customer['email'];
						#$email -> bcc = $_POST['email_bcc'];
						$email -> subject = $data[$key]['start_date']." from ".$biller['name'];
						$email -> attachment = $pdf_file_name;
						$return .= $email -> send ();

					} else {

						#$return .= "<br />NOT RUN: Cron for ".$data[$key]['index_name']." with start date of ".$data[$key]['start_date'].", end date of ".$data[$key]['end_date']." where it runs each ".$data[$key]['recurrence']." ".$data[$key]['recurrence_type']." did not recur today :: Info diff=".$diff."<br />";
	
					}

				} else {		
						#$return .= "<br />NOT RUN: Cron for ".$data[$key]['index_name']." with start date of ".$data[$key]['start_date'].", end date of ".$data[$key]['end_date']." where it runs each ".$data[$key]['recurrence']." ".$data[$key]['recurrence_type']." did not recur today :: Info diff=".$diff."<br />";

				}
							
				
			}

			//insert into cron_log date of run
			$cron_log = new cronlog();
			$cron_log->run_date = $today;
			$cron_log->domain_id = $domain_id;
			$cron_log->insert();

			$email = new email();
			$email -> format = 'cron';
			$email -> notes = $return;
			$email -> from = "simpleinvoices@127.0.0.1";
			$email -> from_friendly = "Simple Invoices - Cron";
			$email -> to = "justin@127.0.0.2";
			#$email -> bcc = $_POST['email_bcc'];
			$email -> subject = "Cron for Simple Invoices has been run for today:";
			#$return .= $email -> send ();

		} else {
	
			$return .= "Cron has already been run for domain: ".$domain_id." for the date: ".$today;
		}

		return $return;
	}

}
