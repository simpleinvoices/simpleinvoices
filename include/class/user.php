
<?php

class user
{

	function getUserRoles()
	{
	
		$sql = "select id, name from si_user_role where name != 'biller' AND name != 'customer' order by id";
		$result = dbQuery($sql);

		return $result->fetchAll();

	}

}
