
<?php

class user
{


	function getUserRoles()
	{
	
		$sql = "select id, name from ".TB_PREFIX."user_role where name != 'biller' AND name != 'customer' order by id";
		$result = dbQuery($sql);

		return $result->fetchAll();

	}

	function getUser($id)
	{
	
		global $auth_session;

		$sql = "select u.*, ur.name as role_name from ".TB_PREFIX."user u, ".TB_PREFIX."user_role ur where u.id = :id and u.domain_id = :domain_id and u.role_id = ur.id";
		$result = dbQuery($sql,':id',$id,':domain_id',$auth_session->domain_id);

		return $result->fetch();

	}
}
