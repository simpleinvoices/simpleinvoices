<?php

class import {

	public $file;
	public $debug;
	public $pattern_find;
	public $pattern_replace;
	
	public function getFile()
	{
		$json = file_get_contents($this->file, true);
		return $json;
	}
	
    public function replace($string)
    {
/*        if(is_array($this->pattern_find))
        {
            $string_replaced ="";
            foreach($this->pattern_find as $key=>$value)
            {
                echo $this->pattern_find[$key] . $this->pattern_replace[$key];
                $string_replaced .= str_replace($this->pattern_find[$key], $this->pattern_replace[$key], $string);
            }
            
        } else {
*/
            $string_replaced = str_replace($this->pattern_find, $this->pattern_replace, $string);

   #     }
        return $string_replaced;
    }
	public function collate()
	{
		$json = $this->getFile();
		$replace = $this->replace($json);
		return $replace;
	}
	public function execute()
	{
		dbQuery($this->collate());
	}

}




?>
