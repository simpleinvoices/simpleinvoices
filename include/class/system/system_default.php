<?php
class system_default {

	function __construct()
	{

		$this->extension_name = "core";

	}

	public function update()
	{

		global $db;
		global $db_server;
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

		if ($db_server == 'mysql') {
			$sql = "INSERT INTO `".TB_PREFIX."system_defaults`
				(`name`, `value`, domain_id, extension_id)
				VALUES (:name, :value, :domain_id, :extension_id)
				ON DUPLICATE KEY UPDATE `value` = :value";
		} else {
			// PostgreSQL 9.5+ / SQLite 3.24+ upsert syntax
			$sql = "INSERT INTO ".TB_PREFIX."system_defaults
				(name, value, domain_id, extension_id)
				VALUES (:name, :value, :domain_id, :extension_id)
				ON CONFLICT (domain_id, name) DO UPDATE SET value = EXCLUDED.value";
		}

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
