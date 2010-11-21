<?php
class cronlog {
	
	public $domain_id;
	public $cron_id;

	public function insert()
	{
        	global $db;
        	global $auth_session;

		$domain_id = domain_id::get($this->domain_id);

		$today = date('Y-m-d');
		$run_date = empty($this->run_date) ? $today : $this->run_date;
		
		$sql = "INSERT into ".TB_PREFIX."cron_log (
				domain_id,
                cron_id,
				run_date
			) VALUES (
				:domain_id,
				:cron_id,
				:run_date
			);";

        	$sth  = $db->query($sql,
				':domain_id',$domain_id, 
				':cron_id',$this->cron_id, 
				':run_date',$run_date
			) or die(htmlsafe(end($dbh->errorInfo())));
        
 	       return $sth;
	}

	public function check()
	{
        	global $db;
		
		$domain_id = domain_id::get($this->domain_id);

		$run_date = empty($this->run_date) ? $today : $this->run_date;
		$sql = "SELECT 
                    count(*) as count 
                FROM 
                    ".TB_PREFIX."cron_log 
                WHERE 
                    domain_id = :domain_id 
                AND 
                    cron_id = :cron_id 
                AND
                    run_date = :run_date";
        	$sth = $db->query($sql,
				':domain_id',$domain_id, 
				':cron_id',$this->cron_id, 
				':run_date',$run_date
			) or die(htmlsafe(end($dbh->errorInfo())));
        
 	       return $sth->fetchColumn();
	}

}
