<?php

class biller
{    
    public static function get_all()
    {
        global $LANG;
        global $dbh;
        global $auth_session;
        
        $sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id ORDER BY name";
        $sth  = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
        
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

    public static function select($id)
    {
        global $LANG;
        global $db;
        global $auth_session;
        
        $sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id AND id = :id";
        $sth  = $db->query($sql,':domain_id',$auth_session->domain_id, ':id',$id) or die(htmlsafe(end($dbh->errorInfo())));
        
	$biller = $sth->fetch();
	$biller['wording_for_enabled'] = $biller['enabled']==1?$LANG['enabled']:$LANG['disabled'];
	return $biller;
        #return $sth->fetch();
    }
}
