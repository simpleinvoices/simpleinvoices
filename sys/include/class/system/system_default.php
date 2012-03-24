<?php
class system_default {
	
	function __construct()
	{

		$this->extension_name = "core";

	}
	
	public function update()
	{

		global $db;
		
		$sql = "UPDATE ".TB_PREFIX."system_defaults SET value =  :value WHERE name = :name"; 

		//dont worry about checking db if were using the core extension
		if (  $this->extension_name != "core" )
		{
            $extensions = new SimpleInvoices_Extensions();
            $extension_id = $extensions->findByName($extension_name);
		} else {
			$extension_id = 0;
		}

		if ($extension_id >= 0) { 
			$sql .= " AND extension_id = :extension_id"; 
		} else { 
			die(htmlsafe("Invalid extension name: ".$extension)); 
		}
		if ($db->query($sql, ':value', $this->value, ':name', $this->name, ':extension_id', $extension_id)) { 
			return true; 
		}
		return false;

	}

}

