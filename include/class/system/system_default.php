<?php
class system_default {

	function __construct()
	{

		$this->extension_name = "core";

	}
	
	public function update()
	{

		global $db;
		global $auth_session;
		$domain_id = $auth_session->domain_id;

		//dont worry about checking db if were using the core extension
		if (  $this->extension_name != "core" )
		{
			$extension_id = getExtensionID($extension_name);
		} else {
			$extension_id = 0;
		}

		if (!($extension_id >= 0))
		{
			die(htmlsafe("Invalid extension name: ".$extension));
		}

		$sql = "INSERT INTO 
			`".TB_PREFIX."system_defaults`
			(
				`name`, `value`, domain_id, extension_id
			)
			VALUES
			(
				:name, :value, :domain_id, :extension_id
			)
			ON DUPLICATE KEY UPDATE
				`value` =  :value";

		if ($db->query($sql,
			':value', $value,
			':domain_id', $domain_id,
			':name', $name,
			':extension_id', $extension_id
			)
		) return true;

		return false;

	}

}
