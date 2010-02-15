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
				email_biller,
				email_customer
			) VALUES (
				:domain_id,
				:invoice_id,
				:start_date,
				:end_date,
				:email_biller,
				:email_customer
			)";
        	$sth  = $db->query($sql,
				':domain_id',$auth_session->domain_id, 
				':invoice_id',$this->invoice_id,
				':start_date',$this->start_date,
				':end_date',$this->end_date,
				':email_biller',$this->email_biller,
				':email_customer',$this->email_customer
			) or die(htmlspecialchars(end($dbh->errorInfo())));
        
        return $sth->fetch();
    }

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

        
	        $sql = "SELECT * FROM ".TB_PREFIX."cron WHERE domain_id = :domain_id AND invoice_id = :invoice_id";
        	$sth  = $db->query($sql,':domain_id',$auth_session->domain_id, ':id',$id) or die(htmlspecialchars(end($dbh->errorInfo())));
        
        return $sth->fetch();
    }

	}

}
