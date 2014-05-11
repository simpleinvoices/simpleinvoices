<?php

class user
{


	function getUserRoles()
	{
            global $db;
//		$sql = "select id, name from ".TB_PREFIX."user_role where name != 'biller' AND name != 'customer' order by id";
		$sql = "SELECT id, name FROM ".TB_PREFIX."user_role ORDER BY id";
		$result = $db->query($sql);

		return $result->fetchAll();

	}

	function getUser($id)
	{
	
		global $auth_session;
		global $LANG;
                global $db;
		$sql = "SELECT 
					u.*, 
					ur.name AS role_name,
					(SELECT (CASE WHEN u.enabled = ".ENABLED." THEN '".$LANG['enabled']."' ELSE '".$LANG['disabled']."' END )) AS lang_enabled,
					user_id
				FROM 
					".TB_PREFIX."user u LEFT JOIN 
					".TB_PREFIX."user_role ur ON (u.role_id = ur.id)
				WHERE u.domain_id = :domain_id
				  AND u.id = :id 
				";
		$result = $db->query($sql,':id', $id, ':domain_id', $auth_session->domain_id);

		return $result->fetch();

	}
}
