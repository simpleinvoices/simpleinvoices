<?php

class encode
{
	public static function json($data)
	{
		return json_encode($data);
	}

	public static function xml($data)
	{

		$xml = new XmlWriter();
		$xml->openMemory();
		$xml->startDocument('1.0', 'UTF-8');
		$xml->startElement('root');
		    foreach($data as $key => $value){
			if(is_array($value)){
			    $xml->startElement($key);
			    write($xml, $value);
			    $xml->endElement();
			    continue;
			}
			$xml->writeElement($key, $value);
		    }


		$xml->endElement();
		return $xml->outputMemory(true);

	}
}
