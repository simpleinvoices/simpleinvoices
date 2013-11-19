<?php

class biller
{    
	public $domain_id;
    
	public function __construct()
	{
		$this->domain_id = domain_id::get($this->domain_id);
	}

    public function get_all()
    {
        global $LANG;

        $sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id ORDER BY name";
        $sth  = dbQuery($sql,':domain_id',$this->domain_id);
        
        $billers = null;
        
        for($i=0;$biller = $sth->fetch();$i++) {
            
            if ($biller['enabled'] == 1) {
                $biller['enabled'] = $LANG['enabled'];
            } else {
                $biller['enabled'] = $LANG['disabled'];
            }
            $billers[$i] = $biller;
        }
        
        return $billers;
    }

    public function select($id)
    {
        global $LANG;
        
        $sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id AND id = :id";
        $sth  = dbQuery($sql,':domain_id',$this->domain_id, ':id',$id);
        
		$biller = $sth->fetch();
		$biller['wording_for_enabled'] = $biller['enabled']==1?$LANG['enabled']:$LANG['disabled'];

		return $biller;
    }
}
