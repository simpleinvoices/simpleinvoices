<?php
class categories {
	
 	public $domain_id;
	
	public function select()
	{
		global $LANG;
        global $auth_session;		
		global $db;

        $sql = "SELECT * FROM ".TB_PREFIX."categories ORDER BY category_id ASC";
		$sth = $db->query($sq,':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));

		return $sth->fetchAll();
	}
	
	public static function get_cats()
    {

         global $auth_session;
         global $db;
 
         $sql = "SELECT a.category_id, a.name, a.slug, a.referencia, a.enabled, b.parent 
         		FROM ".TB_PREFIX."categories as a INNER JOIN ".TB_PREFIX."categories_taxonomy as b
         		ON a.category_id = b.category_id ORDER BY a.category_id ASC";
         $sth  = $db->query($sql, ':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
 
         return $sth->fetchAll();

    }
	
	public static function get_last_inserted_id()
	{
		global $auth_session;
		global $db;
		
		$sql = "SELECT max(id) FROM ".TB_PREFIX."categories";
		$sth = $db->query($sql, ':domain_id',$auth_session->domain_id) or die(htmlsafe(end($dbh->errorInfo())));
		
		return $sth->fecth();
	}

}
