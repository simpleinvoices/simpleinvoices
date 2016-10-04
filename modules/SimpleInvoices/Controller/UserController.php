<?php
/**
 * Simple Invoices (http://www.simpleinvoices.org/)
 *
 * @link      http://github.com/simpleinvoices/simpleinvoices for the source repository
 * @copyright Copyright (c) 2005-2016 Simple Invoices Community (http://www.simpleinvoices.org)
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL v3
 */

namespace SimpleInvoices\Controller;

class UserController
{
    protected $smarty;

    /**
     * TODO: Don't use globals!
     */
    public function __construct()
    {
        global $smarty;

        $this->smarty = $smarty;
    }
    
    protected function xmlSql($type='', $dir, $sort, $rp, $page )
    {
    	global $config;
    	global $LANG;
    	global $auth_session;
    
    	$valid_search_fields = array('email', 'ur.name');
    	
    	//SC: Safety checking values that will be directly subbed in
    	if (intval($start) != $start) {
    		$start = 0;
    	}
    	if (intval($rp) != $rp) {
    		$rp = 25;
    	}
    	
    	/*SQL Limit - start*/
    	$start = (($page-1) * $rp);
    	$limit = "LIMIT $start, $rp";
    
    	if($type =="count")
    	{
    		unset($limit);
    	}
    	/*SQL Limit - end*/	
    	
    	if (!preg_match('/^(asc|desc)$/iD', $dir)) {
    		$dir = 'ASC';
    	}
    
    	$where = "";
    	$query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;
    	$qtype = isset($_REQUEST['qtype']) ? $_REQUEST['qtype'] : null;
    	if ( ! (empty($qtype) || empty($query)) ) {
    		if ( in_array($qtype, $valid_search_fields) ) {
    			$where = " AND $qtype LIKE :query ";
    		} else {
    			$qtype = null;
    			$query = null;
    		}
    	}
    
    	/*Check that the sort field is OK*/
    	$validFields = array('id', 'role', 'email');
    	
    	if (in_array($sort, $validFields)) {
    		$sort = $sort;
    	} else {
    		$sort = "email";
    	}
    	
    	//$sql = "SELECT * FROM ".TB_PREFIX."customers ORDER BY $sort $dir LIMIT $start, $limit";
    	$sql = "SELECT 
    				u.id, 
    				u.email, 
    				ur.name as role,
    				(SELECT (CASE WHEN u.enabled = ".ENABLED." THEN '".$LANG['enabled']."' ELSE '".$LANG['disabled']."' END )) AS enabled,
    				user_id
    			FROM 
    				".TB_PREFIX."user u LEFT JOIN
    				".TB_PREFIX."user_role ur ON (u.role_id = ur.id)
    			WHERE u.domain_id = :domain_id 
    				$where
    			ORDER BY 
    				$sort $dir 
    			$limit";
    
    	if (empty($query)) {
    		$result = dbQuery($sql,':domain_id', $auth_session->domain_id);
    	} else {
    		$result = dbQuery($sql,':domain_id', $auth_session->domain_id, ':query', "%$query%");
    	}
    
    	return $result;
    }
    
    public function addAction()
    {
        //get user roles
        $roles = \user::getUserRoles();
        
        if ($_POST['email'] != "") {
            $this->saveAction();
        }
        
        $this->smarty->assign('roles', $roles);
        
        $this->smarty->assign('pageActive', 'user');
        $this->smarty->assign('subPageActive', 'user_add');
        $this->smarty->assign('active_tab', '#people');
    }
    
    public function detailsAction()
    {
        $id            = $_GET['id'];
        $user          = \user::getUser($id);
        $roles         = \user::getUserRoles();
        $subPageActive = $_GET['action'] =="view"  ? "user_view" : "user_edit" ;
        
        $this->smarty->assign('user', $user);
        $this->smarty->assign('roles', $roles);
        $this->smarty->assign('pageActive', 'user');
        $this->smarty->assign('subPageActive', $subPageActive);
        $this->smarty->assign('active_tab', '#people');
    }
    
    public function manageAction()
    {
        global $dbh;
        
        $sql            = "SELECT count(*) as count FROM ".TB_PREFIX."user";
        $sth            = dbQuery($sql) or die(htmlsafe(end($dbh->errorInfo())));
        $number_of_rows = $sth->fetch(\PDO::FETCH_ASSOC);
        
        $this->smarty->assign("number_of_rows", $number_of_rows);
        $this->smarty->assign('pageActive', 'user');
        $this->smarty->assign('active_tab', '#people');
    }
    
    public function saveAction()
    {
        global $auth_session;
        
        $op = !empty( $_POST['op'] ) ? addslashes( $_POST['op'] ) : NULL;
        
        #insert biller
        $saved = false;
        
        if ( $op === 'insert_user') {
            $sql = "INSERT INTO ".TB_PREFIX."user
                    (
                        email,
                        password,
                        role_id,
                        domain_id,
                        enabled,
						user_id
                    )
                    VALUES
                    (
                        :email,
                        MD5(:password),
                        :role,
						:domain_id,
						:enabled,
						:user_id
                    )
            ";
        
            if (dbQuery($sql, ':email',$_POST['email'],':password',$_POST['password_field'],':role',$_POST['role'],':domain_id',$auth_session->domain_id,':enabled',$_POST['enabled'],':user_id',$_POST['user_id'])) {
                $saved = true;
            }
        }
        
        if ($op === 'edit_user' ) {
            empty($_POST[password_field]) ? $password = "" : $password = "password = '".md5($_POST[password_field])."',"  ;
        
            $sql = "UPDATE ".TB_PREFIX."user
                SET
                email = :email,
                $password
                role_id = :role,
                enabled = :enabled,
                user_id = :user_id
                WHERE
                id = :id
            ";
        
            if (dbQuery($sql, ':email',$_POST['email'], ':role',$_POST['role'], ':enabled',$_POST['enabled'], ':user_id',$_POST['user_id'], ':id',$_POST['id'])) {
                $saved = true;
            }
        }
        
        $this->smarty->assign('saved', $saved);
        $this->smarty->assign('pageActive', 'user');
        $this->smarty->assign('active_tab', '#people');
    }
    
    public function xmlAction()
    {
        global $LANG;
        
        header("Content-type: text/xml");
        
        $start          = (isset($_POST['start']))     ? $_POST['start']     : "0" ;
        $dir            = (isset($_POST['sortorder'])) ? $_POST['sortorder'] : "ASC" ;
        $sort           = (isset($_POST['sortname']))  ? $_POST['sortname']  : "email" ;
        $rp             = (isset($_POST['rp']))        ? $_POST['rp']        : "25" ;
        $page           = (isset($_POST['page']))      ? $_POST['page']      : "1" ;
        $sth            = $this->xmlSql('', $dir, $sort, $rp, $page);
        $sth_count_rows = $this->xmlSql('count',$dir, $sort, $rp, $page);
        $user           = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $count          = $sth_count_rows->rowCount();
        
        //echo sql2xml($customers, $count);
        $xml .= "<rows>";
        $xml .= "<page>$page</page>";
        $xml .= "<total>$count</total>";
        
        foreach ($user as $row) {
            $xml .= "<row id='".$row['iso']."'>";
            $xml .= "<cell><![CDATA[
            <a class='index_table' title='$LANG[view] ".$row['name']."' href='index.php?module=user&view=details&id=$row[id]&action=view'><img src='images/common/view.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            <a class='index_table' title='$LANG[edit] ".$row['name']."' href='index.php?module=user&view=details&id=$row[id]&action=edit'><img src='images/common/edit.png' height='16' border='-5px' padding='-4px' valign='bottom' /></a>
            ]]></cell>";
            $xml .= "<cell><![CDATA[".$row['email']."]]></cell>";
            $xml .= "<cell><![CDATA[".$row['role']."]]></cell>";
            if ($row['enabled']==$LANG['enabled']) {
                $xml .= "<cell><![CDATA[<img src='images/common/tick.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
            else {
                $xml .= "<cell><![CDATA[<img src='images/common/cross.png' alt='".$row['enabled']."' title='".$row['enabled']."' />]]></cell>";
            }
            $xml .= "<cell><![CDATA[".$row['user_id']."]]></cell>";
            $xml .= "</row>";
        }
        
        $xml .= "</rows>";
        echo $xml;
    }
}