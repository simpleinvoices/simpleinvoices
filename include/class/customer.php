<?php

class customer
{

    public static function get_all()
    {
        
        global $dbh;
        global $LANG;
        global $auth_session;
        
        $customer = null;
        
        $sql = "SELECT * FROM ".TB_PREFIX."customers WHERE domain_id = :domain_id";
        $sth = dbQuery($sql,':domain_id', $auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));

        $customers = null;

        for($i=0; $customer = $sth->fetch(); $i++) {
            if ($customer['enabled'] == 1) {
                $customer['enabled'] = $LANG['enabled'];
            } else {
                $customer['enabled'] = $LANG['disabled'];
            }

            #invoice total calc - start
            $customer['total'] = calc_customer_total($customer['id']);
            #invoice total calc - end

            #amount paid calc - start
            $customer['paid'] = calc_customer_paid($customer['id']);
            #amount paid calc - end

            #amount owing calc - start
            $customer['owing'] = $customer['total'] - $customer['paid'];
            
            #amount owing calc - end
            $customers[$i] = $customer;

        }
        
        return $customers;

    }
    
}
