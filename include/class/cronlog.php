<?php
class cronlog {
	
	public $domain_id;
	public $cron_id;
	public $run_date;

	public function insert()
	{
        	global $db;
//        	global $auth_session;

//		$domain_id = domain_id::get($this->domain_id);

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
				  ':domain_id',$this->domain_id, 
				  ':cron_id',  $this->cron_id, 
				  ':run_date', $this->run_date
			) or die(htmlsafe(end($dbh->errorInfo())));
        
 	       return $sth;
	}

	public function check()
	{
        global $db;
		$domain_id = domain_id::get($this->domain_id);
		
		$run_date = empty($this->run_date) ? $today : $this->run_date;
		$sql = "SELECT count(*) AS count 
                FROM ".TB_PREFIX."cron_log 
                WHERE cron_id   = :cron_id
                  AND run_date  = :run_date
				  AND domain_id = :domain_id 
                ";
        	$sth = $db->query($sql,
				 ':cron_id',  $this->cron_id, 
				 ':run_date', $this->run_date, 
				 ':domain_id',$domain_id 
			) or die(htmlsafe(end($dbh->errorInfo())));
        
 	       return $sth->fetchColumn();
	}

	public function select()
	{
        	global $db;
        	global $auth_session;

		$domain_id = domain_id::get($this->domain_id);

		$sql = "SELECT * FROM ".TB_PREFIX."cron_log 
			WHERE domain_id = :domain_id
			ORDER BY run_date DESC, id DESC;
		";

        	$sth  = $db->query($sql, ':domain_id',$domain_id)
						or die(htmlsafe(end($dbh->errorInfo())));
        
 	       return $sth;
	}

}
