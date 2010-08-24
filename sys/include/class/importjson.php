<?php

class importjson extends import
{
	public $pattern_find;
	public $pattern_replace;

	public function decode($json)
	{
		$a = json_decode($json,true);	
		return $a;
	}

	public function process($a)
	{
		
	    $sql ="";

		foreach ($a as $k => $v) 
		{
			$table = $k;

			if($this->debug) echo "<br>";
			if($this->debug) echo "<b>Table: ".$table."</b>";
			
			
			$columns ="";
			$values="";
			    foreach ($a[$k] as $v2) 
				{
					if($this->debug) echo "<br>";
						$i = "1";
						foreach ($v2 as $k3 => $v3) 
						{
						
							//TODO: IF NULL don't ''
							$i == "1" ? $columns .= $k3 : $columns .= ", ".$k3;
							$i == "1" ? $values .= "'".$v3."'" : $values .= ", '".$v3."'";

							$i++;
						}
						
					
					$sql_print = "INSERT into ".$table." (".$columns.") VALUES (".$values.");";
                    $sql .= $sql_print;
					if($this->debug) echo "SQL: ".$sql_print;
					$columns ="";
					$values ="";
				}
			if($this->debug) echo "<br>";    
			
		}

        return $sql;
    }
	public function collate()
	{
		$json = $this->getFile();
        $replace = $this->replace($json);
		$decode = $this->decode($replace);
		return $this->process($decode);

	}

}
