<?php
class cron {
	
    public $start_date;
	public function insert()
	{
        	global $db;
        	global $auth_session;

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
				':domain_id',$this->domain_id, 
				':invoice_id',$this->invoice_id,
				':start_date',$this->start_date,
				':end_date',$this->end_date,
				':recurrence',$this->recurrence,
				':recurrence_type',$this->recurrence_type,
				':email_biller',$this->email_biller,
				':email_customer',$this->email_customer
			) or die(htmlspecialchars(end($dbh->errorInfo())));
        
 	       return $sth->fetch();

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

        
	        $sql = "SELECT * FROM ".TB_PREFIX."cron WHERE domain_id = :domain_id";
        	$sth  = $db->query($sql,':domain_id',$this->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
        
 	       $data = $sth->fetchAll();
		
		foreach ($data as $key=>$value)
		{
			$start_date = date('Y-m-d', strtotime( $data[$key]['start_date'] ) );

    			$diff = number_format((strtotime($today) - strtotime($start_date)) / (60 * 60 * 24),0);
			echo '<br />Today: '.$today." Start date: ".$start_date. " ID: ".$data[$key]['id']." Diff: ".$diff;
			
			//only check if diff is positive
			if ($diff >= 0)
			{
				if($data[$key]['recurrence_type'] == 'day')
				{
					$modulus = $diff % $data[$key]['recurrence'] ;
					if($modulus == 0)
					{ 
						echo "cron runs TODAY-days";
					} else {
						echo "cron does not runs TODAY-days";

					}

				}
				if($data[$key]['recurrence_type'] == 'week')
				{
					$period = 7 * $data[$key]['recurrence'];
					$modulus = $diff % $period ;
					if($modulus == 0)
					{ 
						echo "cron runs TODAY-week";
					} else {
						echo "cron is not runs TODAY-week";
					}

				}
				if($data[$key]['recurrence_type'] == 'month')
				{
					$start_month = date('m', strtotime( $data[$key]['start_date'] ) );
					$start_year = date('Y', strtotime( $data[$key]['start_date'] ) );
					$today_month = date('m');	
					$today_year = date('Y'); 	

					$months = ($today_month-$start_month)+12*($today_year-$start_year);
					$modulus =  $months % $data[$key]['recurrence']  ;
					if($modulus == 0)
					{ 
						echo "cron runs TODAY-month";
					} else {
						echo "cron is not runs TODAY-month";
					}

				}
				if($data[$key]['recurrence_type'] == 'year')
				{

				}
			} else {		
				echo "cron is not run today as date is in future";
			}

			
		}
	}

}
