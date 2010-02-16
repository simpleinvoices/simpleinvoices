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
        	$sth  = $db->query($sql,
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

	public function check()
	{
        	global $db;
        	global $auth_session;

		$today = date('Y-m-d');
		$domain_id = domain_id::get($this->domain_id);
		$return ="";

		$cron_log = new cronlog();
		$cron_log->run_date = empty($this->run_date) ? $today : $this->run_date;
		$check_cron_log = $cron_log->check();        	

		//only proceed if cron has not been rum for today
		if ($check_cron_log == 0)
		{
			$sql = "SELECT * FROM ".TB_PREFIX."cron WHERE domain_id = :domain_id";
			$sth  = $db->query($sql,':domain_id',$domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
		
		       $data = $sth->fetchAll();
			
			foreach ($data as $key=>$value)
			{
				$start_date = date('Y-m-d', strtotime( $data[$key]['start_date'] ) );
				$end_date = $data[$key]['end_date'] ;

				$diff = number_format((strtotime($today) - strtotime($start_date)) / (60 * 60 * 24),0);
				$return .= '<br />Today: '.$today." Start date: ".$start_date. " End date: ".$end_date." ID: ".$data[$key]['id']." Diff: ".$diff;
				
		
				//only check if diff is positive
				if (($diff >= 0) AND ($end_date =="" OR $end_date >= $today))
				{
					if($data[$key]['recurrence_type'] == 'day')
					{
						$modulus = $diff % $data[$key]['recurrence'] ;
						if($modulus == 0)
						{ 
							$return .= "cron runs TODAY-days";
						} else {
							$return .= "cron does not runs TODAY-days";

						}

					}
					if($data[$key]['recurrence_type'] == 'week')
					{
						$period = 7 * $data[$key]['recurrence'];
						$modulus = $diff % $period ;
						if($modulus == 0)
						{ 
							$return .= "cron runs TODAY-week";
						} else {
							$return .= "cron is not runs TODAY-week";
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
							$return .= "cron runs TODAY-month";
						} else {
							$return .= "cron is not runs TODAY-month";
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
							$return .= "cron runs TODAY-year";
						} else {
							$return .= "cron is not runs TODAY-year";
						}


					}


				} else {		
					$return .= "cron is not run today as date is in future";
				}
							
				
			}

			//insert into cron_log date of run
			$cron_log = new cronlog();
			$cron_log->run_date = $today;
			$cron_log->domain_id = $domain_id;
			$cron_log->insert();


		} else {
	
			$return .= "Cron has already been run for domain: ".$domain_id." for the date: ".$today;
		}

		return $return;
	}

}
