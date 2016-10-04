<?php

// Cronlog runs outside of sessions and triggered by Cron
// Manually set the domain_id class member before using class methods
class cronlog {

	public $domain_id;
	public $cron_id;
	public $run_date;

	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

	public function insert()
	{
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

        	$sth  = dbQuery($sql,
				  ':domain_id',$this->domain_id, 
				  ':cron_id',  $this->cron_id, 
				  ':run_date', $this->run_date
			);

 	       return $sth;
	}

	public function check()
	{

		$run_date = empty($this->run_date) ? $today : $this->run_date;
		$sql = "SELECT count(*) AS count 
                FROM ".TB_PREFIX."cron_log 
                WHERE cron_id   = :cron_id
                  AND run_date  = :run_date
				  AND domain_id = :domain_id 
                ";
        	$sth = dbQuery($sql,
				 ':cron_id',  $this->cron_id, 
				 ':run_date', $this->run_date, 
				 ':domain_id',$this->domain_id
			);

 	       return $sth->fetchColumn();
	}

	public function select()
	{

		$sql = "SELECT * FROM ".TB_PREFIX."cron_log 
			WHERE domain_id = :domain_id
			ORDER BY run_date DESC, id DESC;
		";

        	$sth  = dbQuery($sql, ':domain_id', $this->domain_id);

 	       return $sth;
	}

}
