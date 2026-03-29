<?php

class encode
{

    public static function xml($array, $level=1) {

        $xml = '';
        if ($level==1) {
            $xml .= '<?xml version="1.0" encoding="ISO-8859-1"?>'.
                    "\n<array>\n";
        }
        foreach ($array as $key=>$value) {
            $key = strtolower($key);
            if (is_array($value)) {
                $multi_tags = false;
                foreach($value as $key2=>$value2) {
                    if (is_array($value2)) {
                        $xml .= str_repeat("\t",$level)."<$key>\n";
                        $xml .= array_to_xml($value2, $level+1);
                        $xml .= str_repeat("\t",$level)."</$key>\n";
                        $multi_tags = true;
                    } else {
                        if (trim($value2)!='') {
                            if (htmlsafe($value2)!=$value2) {
                                $xml .= str_repeat("\t",$level).
                                        "<$key><![CDATA[$value2]]>".
                                        "</$key>\n";
                            } else {
                                $xml .= str_repeat("\t",$level).
                                        "<$key>$value2</$key>\n";
                            }
                        }
                        $multi_tags = true;
                    }
                }
                if (!$multi_tags and count($value)>0) {
                    $xml .= str_repeat("\t",$level)."<$key>\n";
                    $xml .= array_to_xml($value, $level+1);
                    $xml .= str_repeat("\t",$level)."</$key>\n";
                }
            } else {
                if (trim($value)!='') {
                    if (htmlsafe($value)!=$value) {
                        $xml .= str_repeat("\t",$level)."<$key>".
                                "<![CDATA[$value]]></$key>\n";
                    } else {
                        $xml .= str_repeat("\t",$level).
                                "<$key>$value</$key>\n";
                    }
                }
            }
        }
        if ($level==1) {
            $xml .= "</array>\n";
        }
        return $xml;
    }

	public static function json($data, $format='plain')
	{
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		if ($json === false) {
			return '';
		}

		if ($format === 'pretty') {
			$pretty = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
			$safe = htmlspecialchars($pretty, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
			return "<pre class=\"json-output\">{$safe}</pre>";
		}

		return $json;
	}

} // end of class
