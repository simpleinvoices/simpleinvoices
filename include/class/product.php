<?php

class product
{
    public static function count()
    {

        global $db;
         global $auth_session;
 
         $sql = "SELECT count(id) as count FROM ".TB_PREFIX."products WHERE domain_id = :domain_id ORDER BY id";
         $sth  = $db->query($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
 
         return $sth->fetch();

    }

    public static function get_all()
    {

         global $auth_session;
         global $db;
 
         $sql = "SELECT * FROM ".TB_PREFIX."products WHERE domain_id = :domain_id and visible = 1 ORDER BY id";
         $sth  = $db->query($sql,':domain_id',$auth_session->domain_id) or die(htmlspecialchars(end($dbh->errorInfo())));
 
         return $sth->fetchAll();

    }

    public static function get($id)
    {

         global $auth_session;
         global $db;
 
         $sql = "SELECT * FROM ".TB_PREFIX."products WHERE domain_id = :domain_id and id = :id";
         $sth  = $db->query($sql,':domain_id',$auth_session->domain_id, ':id',$id) or die(htmlspecialchars(end($dbh->errorInfo())));
 
         return $sth->fetch();

    }
}
