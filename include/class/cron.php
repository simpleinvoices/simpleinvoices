<?php

class cron {
	
 	public $start_date;
 	public $domain_id;

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
			) or die(htmlsafe(end($dbh->errorInfo())));
        
 	       return $sth;

	}

	public function update()
	{
        	global $db;

		$domain_id = domain_id::get($this->domain_id);
        
	        $sql = "UPDATE 
				".TB_PREFIX."cron 
			SET 
				invoice_id = :invoice_id,
				start_date = :start_date,
				end_date = :end_date,
				recurrence = :recurrence,
				recurrence_type = :recurrence_type,
				email_biller = :email_biller,
				email_customer = :email_customer
			WHERE 
				id = :id 
				AND 
				domain_id = :domain_id
			";
        	$sth = $db->query($sql,
				':id',$this->id, 
				':domain_id',$domain_id, 
				':invoice_id',$this->invoice_id,
				':start_date',$this->start_date,
				':end_date',$this->end_date,
				':recurrence',$this->recurrence,
				':recurrence_type',$this->recurrence_type,
				':email_biller',$this->email_biller,
				':email_customer',$this->email_customer
			) or die(htmlsafe(end($dbh->errorInfo())));
        
 	       return $sth;
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

		if($type =="count" OR $type =="no_limit)
		{
		    //unset($limit);
		    $limit="";
		}


		$sql = "SELECT
				cron.* ,
                cron.id as cron_id,
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

		$sth = $db->query($sql,':domain_id',domain_id::get($this->domain_id)) or die(htmlsafe(end($dbh->errorInfo())));
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
				and
				cron.id = :id;";
		$sth = $db->query($sql,':domain_id',domain_id::get($this->domain_id), ':id',$this->id) or die(htmlsafe(end($dbh->errorInfo())));

		return $sth->fetch();
	}

	public function run()
	{
        global $db;
        global $auth_session;

        $today = date('Y-m-d');
        $domain_id = domain_id::get($this->domain_id);

        $cron_log = new cronlog();
        $cron_log->run_date = empty($this->run_date) ? $today : $this->run_date;
        $check_cron_log = $cron_log->check();        	

        //only proceed if cron has not been run for today
        $cron = new cron();
        $data = $cron->select_all('no_limit');

        $return['cron_message'] ="Cron started";
        $number_of_crons_run = "0";	
        foreach ($data as $key=>$value)
        {

            $cron_log = new cronlog();
            $cron_log->run_date = empty($this->run_date) ? $today : $this->run_date;
            $cron_log->cron_id = $data[$key]['cron_id'];
            $check_cron_log = $cron_log->check();        	

            $i="0";
            if ($check_cron_log == 0)
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
                        $number_of_crons_run++;	
                        $return['cron_message_'.$data[$key]['cron_id']] = "Cron ID: ". $data[$key]['cron_id'] ." - Cron for ".$data[$key]['index_name']." with start date of ".$data[$key]['start_date'].", end date of ".$data[$key]['end_date']." where it runs each ".$data[$key]['recurrence']." ".$data[$key]['recurrence_type']." was run today :: Info diff=".$diff;
                        $i++;

                        $ni = new invoice();
                        $ni->id = $data[$key]['invoice_id'];
                        $new_invoice_id = $ni->recur();

                        //insert into cron_log date of run
                        $cron_log = new cronlog();
                        $cron_log->run_date = $today;
                        $cron_log->domain_id = $domain_id;
                        $cron_log->cron_id = $data[$key]['cron_id'];
                        $cron_log->insert();

                        ## email the people
                        
                        $invoice= invoice::select($new_invoice_id);
                        $preference = getPreference($invoice['preference_id']);
                        $biller = getBiller($invoice['biller_id']);
                        $customer = getCustomer($invoice['customer_id']);
                        #print_r($customer);
                        #create PDF nameVj
                        $spc2us_pref = str_replace(" ", "_", $invoice['index_name']);
                        $pdf_file_name_invoice = $spc2us_pref.".pdf";
                            
                            
                        // email invoice
                        if( ($data[$key]['email_biller'] == "1") OR ($data[$key]['email_customer'] == "1") )
                        {
                            $export = new export();
                            $export -> format = "pdf";
                            $export -> file_location = 'file';
                            $export -> module = 'invoice';
                            $export -> id = $invoice['id'];
                            $export -> execute();

                            #$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);
                            $email = new email();
                            $email -> format = 'cron_invoice';

                                $email_body = new email_body();
                                $email_body->email_type = 'cron_invoice';
                                $email_body->customer_name = $customer['name'];
                                $email_body->invoice_name = $invoice['index_name'];
                                $email_body->biller_name = $biller['name'];
                            
                            $email -> notes = $email_body->create();
                            $email -> from = $biller['email'];
                            $email -> from_friendly = $biller['name'];
                            if($data[$key]['email_customer'] == "1")
                            {
                                $email -> to = $customer['email'];
                            }
                            if($data[$key]['email_biller'] == "1" AND $data[$key]['email_customer'] == "1")
                            {
                                $email -> to = $customer['email'].";".$biller['email'];
                            }
                            if($data[$key]['email_biller'] == "1" AND $data[$key]['email_customer'] == "0")
                            {
                                $email -> to = $biller['email'];
                            }
                            $email -> invoice_name = $invoice['index_name'];
                            $email -> subject = $email->set_subject();
                            $email -> attachment = $pdf_file_name_invoice;
                            $return['email_message'] = $email -> send ();

                        }

                        //Check that all details are OK before doing the eway payment
                        $eway_check = new eway();
                        $eway_check->invoice = $invoice;
                        $eway_check->customer = $customer;
                        $eway_check->biller = $biller;
                        $eway_check->preference = $preference;
                        $eway_pre_check = $eway_check->pre_check();

                        //do eway payment
                        if ($eway_pre_check == 'true')         
                        {
                            
                            // input customerID,  method (REAL_TIME, REAL_TIME_CVN, GEO_IP_ANTI_FRAUD) and liveGateway or not
                            $eway = new eway();
                            $eway->invoice = $invoice;
                            $eway->biller = $biller ;
                            $eway->customer = $customer;
                            $payment_done = $eway->payment();  
                            
                            $payment_id = $db->lastInsertID();

                            $pdf_file_name_receipt = 'payment'.$payment_id.'.pdf';
                            if ($payment_done =='true')
                            {
                                //do email of receipt to biller and customer
                                if( ($data[$key]['email_biller'] == "1") OR ($data[$key]['email_customer'] == "1") )
                                {

                                    /*
                                    * If you want a new copy of the invoice being emailed to the customer 
                                    * use this code
                                    */
                                    $export_rec = new export();
                                    $export_rec -> format = "pdf";
                                    $export_rec -> file_location = 'file';
                                    $export_rec -> module = 'invoice';
                                    $export_rec -> id = $invoice['id'];
                                    $export_rec -> execute();

                                    #$attachment = file_get_contents('./tmp/cache/' . $pdf_file_name);
                                    $email_rec = new email();
                                    $email_rec -> format = 'cron_invoice';

                                        $email_body_rec = new email_body();
                                        $email_body_rec->email_type = 'cron_invoice_receipt';
                                        $email_body_rec->customer_name = $customer['name'];
                                        $email_body_rec->invoice_name = $invoice['index_name'];
                                        $email_body_rec->biller_name = $biller['name'];
                                    
                                    $email_rec -> notes = $email_body_rec->create();
                                    $email_rec -> from = $biller['email'];
                                    $email_rec -> from_friendly = $biller['name'];
                                    if($data[$key]['email_customer'] == "1")
                                    {
                                        $email_rec -> to = $customer['email'];
                                    }
                                    if($data[$key]['email_biller'] == "1" AND $data[$key]['email_customer'] == "1")
                                    {
                                        $email_rec -> to = $customer['email'].";".$biller['email'];
                                    }
                                    if($data[$key]['email_biller'] == "1" AND $data[$key]['email_customer'] == "0")
                                    {
                                        $email_rec -> to = $customer['email'];
                                    }
                                    $email_rec -> invoice_name = $invoice['index_name'];
                                    $email_rec -> attachment = $pdf_file_name_invoice;
                                    $email_rec -> subject = $email_rec->set_subject('invoice_eway_receipt');
                                    $return['email_message'] = $email_rec -> send ();


                                    /*
                                    * If you want a receipt as PDF being emailed to the customer uncomment
                                    * the below code
                                    */
                                    /*
                                    $export = new export();
                                    $export -> format = "pdf";
                                    $export -> file_location = 'file';
                                    $export -> module = 'payment';
                                    $export -> id = $payment_id;
                                    $export -> execute();

                                    $email = new email();
                                    $email -> format = 'cron_payment';

                                        $email_body = new email_body();
                                        $email_body->email_type = 'cron_payment';
                                        $email_body->customer_name = $customer['name'];
                                        $email_body->invoice_name = 'payment'.$payment_id;
                                        $email_body->biller_name = $biller['name'];
                                    
                                    $email -> notes = $email_body->create();
                                    $email -> from = $biller['email'];
                                    $email -> from_friendly = $biller['name'];
                                    if($data[$key]['email_customer'] == "1")
                                    {
                                        $email -> to = $customer['email'];
                                    }
                                    if($data[$key]['email_biller'] == "1" AND $data[$key]['email_customer'] == "1")
                                    {
                                        $email -> to = $customer['email'].";".$biller['email'];
                                    }
                                    if($data[$key]['email_biller'] == "1" AND $data[$key]['email_customer'] == "0")
                                    {
                                        $email -> to = $customer['email'];
                                    }
                                    $email -> subject = $pdf_file_name_receipt." from ".$biller['name'];
                                    $email -> attachment = $pdf_file_name_receipt;
                                    $return['email_message'] = $email->send();
                                    */
                                }
                            } else {
                                //do email to biller/admin - say error
                                
                                $email = new email();
                                $email -> format = 'cron_payment';
                                $email -> from = $biller['email'];
                                $email -> from_friendly = $biller['name'];
                                $email -> to = $biller['email'];
                                $email -> subject = "Payment failed for ".$invoice['index_name'];
                                $error_message ="Invoice:  ".$invoice['index_name']."<br /> Amount: ".$invoice['total']." <br />";
                                foreach($eway->get_message() as $key => $value)
                                    $error_message .= "\n<br>\$ewayResponseFields[\"$key\"] = $value";
                                $email -> notes = $error_message;
                                $return['email_message'] = $email->send();

                            }

                        }



                    } else {

                        //cron not run for this cron_id
                        $return['cron_message_'.$data[$key]['cron_id']] = "Cron ID: ". $data[$key]['cron_id'] ." NOT RUN: Cron for ".$data[$key]['index_name']." with start date of ".$data[$key]['start_date'].", end date of ".$data[$key]['end_date']." where it runs each ".$data[$key]['recurrence']." ".$data[$key]['recurrence_type']." did not recur today :: Info diff=".$diff;

                    }
            
                
                } else {		

                        //days diff is negaqtive - whats going on
                        $return['cron_message_'.$data[$key]['cron_id']] = "Cron ID: ". $data[$key]['cron_id'] ." NOT RUN: - Not cheduled for today - Cron for ".$data[$key]['index_name']." with start date of ".$data[$key]['start_date'].", end date of ".$data[$key]['end_date']." where it runs each ".$data[$key]['recurrence']." ".$data[$key]['recurrence_type']." did not recur today :: Info diff=".$diff;
                }
            } else {
                // cron has already been run for that cron_id toda
                   $return['cron_message_'.$data[$key]['cron_id']] = "Cron ID: ".$data[$key]['cron_id']." - Cron has already been run for domain: ".$domain_id." for the date: ".$today." for invoice ".$data[$key]['invoice_id'];
                   $return['email_message'] = "";
                   
            }
        }

        // no crons scheduled for today	
        if ($number_of_crons_run  == '0')
        {
            $return['id'] = $i;
            $return['cron_message'] = "No invoices recurred for this cron run for domain: ".$domain_id." for the date: ".$today;
            $return['email_message'] = "";
        }
        //insert into cron_log date of run
       /* $cron_log = new cronlog();
        $cron_log->run_date = $today;
        $cron_log->domain_id = $domain_id;
        $cron_log->insert();*/

    /*
    * If you want to get an email once cron has been run edit the below details
    *
    */
    /*
        $email = new email();
        $email -> format = 'cron';
        #$email -> notes = $return;
        $email -> from = "simpleinvoices@localhost";
        $email -> from_friendly = "Simple Invoices - Cron";
        $email -> to = "simpleinvoices@localhost";
        #$email -> bcc = $_POST['email_bcc'];
        $email -> subject = "Cron for Simple Invoices has been run for today:";
        $email -> send ();
    */
            return $return;
        
    }

}
