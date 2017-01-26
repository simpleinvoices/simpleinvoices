<?php

class decode
{

    public static function xml($xml) {
        $xmlary = array();
            
        $reels = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
        $reattrs = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';

        $elements = array();
        preg_match_all($reels, $xml, $elements);

        foreach ($elements[1] as $ie => $xx) {
            if ($xx) {} // eliminated unused warning
            $xmlary[$ie]["name"] = $elements[1][$ie];
            
            if ($attributes = trim($elements[2][$ie])) {
                $att = array();
                preg_match_all($reattrs, $attributes, $att);
                foreach ($att[1] as $ia => $xx)
                    $xmlary[$ie]["attributes"][$att[1][$ia]] = $att[2][$ia];
            }

            $cdend = strpos($elements[3][$ie], "<");
            if ($cdend > 0) {
                $xmlary[$ie]["text"] = substr($elements[3][$ie], 0, $cdend - 1);
            }

            if (preg_match($reels, $elements[3][$ie]))
                $xmlary[$ie]["elements"] = xml2array($elements[3][$ie]);
            else if ($elements[3][$ie]) {
                $xmlary[$ie]["text"] = $elements[3][$ie];
            }
        }

        return $xmlary;
    }

}
