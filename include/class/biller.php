<?php

class biller
{    
    public static function get_all()
    {
        global $LANG;
        global $dbh;
        global $auth_session;
        
        $sql = "SELECT * FROM ".TB_PREFIX."biller WHERE domain_id = :domain_id ORDER BY name";
        $sth  = dbQuery($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
        
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
}
