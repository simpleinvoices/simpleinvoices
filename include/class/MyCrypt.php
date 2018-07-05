<?php

class MyCrypt {
    const HEXCHRS = "0123456789abcdef";
    /**
     * Static function to encrypt a string using a specified key.
     * @param string $encrypt String to encrypt
     * @param string $key String to use as the encryption key.
     * @return string Encrypted string.
     */
    public static function encrypt($encrypt, $key) {
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt . $mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt) . '|' . base64_encode($iv);
        return $encoded;
    }

    /**
     * Static function to decrypt a string using a specified key.
     * @param string $decrypt String to decrypt.
     * @param string $key String to use as the encryption key.
     * @return string Decrypted string.
     * @throws Exception if the string cannot be decrypted.
     */
    public static function decrypt($decrypt, $key) {
        $decrypt = explode('|', $decrypt . '|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if (strlen($iv) !== mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)) {
            throw new PdoDbException("MyCrypt decrypt(): Invalid size of decode string.");
        }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if ($calcmac !== $mac) {
            throw new PdoDbException("MyCrypt decrypt(): Calculated mac is not equal to store mac.");
        }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }

    /**
     * Join the two parts of the line together separated by an equal sign.
     * @param string $itemname Name of this item.
     * @param string $encrypt Encrypted value associated with the <b>$itemname</b>.
     * @return string Line formed by joining <b>$itemname = $encrypt</b>.
     */
    public static function join($itemname, $encrypt) {
        $line = $itemname . " = " . $encrypt;
        return $line;
    }

    /**
     * Unjoin line parts separated by an equal sign.
     * @param string $line Line to be broken apart.
     * @param string $prefix Value that is at the first part of the field separated from the
     *        rest of the parameter name by a period. Ex: <i>database.adapter</i> is the <i>adapter</i>
     *        field with a prefix of <i>database</i>.
     * @return array $pieces The two parts of the line previously joined by the equal sign.
     */
    public static function unjoin($line, $prefix) {
        $line = preg_replace('/^(.*);.*$/', '$1', $line);
        $pieces = explode("=", $line);
        if (count($pieces) != 2) return array("","");

        $parts = explode(".", $pieces[0]);
        $ndx = count($parts) - 1;
        if (!empty($prefix) && ($ndx < 1 || trim($parts[0] != $prefix))) return array("","");

        $pieces[0] = trim($parts[$ndx]);
        $pieces[1] = trim($pieces[1]);
        return $pieces;
    }

    /**
     * Static function to generate a 64 character key based on a specified value.
     * @param String $id Character string to base key on.
     * @return String Generated hex key.
     * @throws Exception if no <b>$id</b> is specified.
     */
    public static function keygen($id=null) {
        if (!isset($id)) {
            throw new PdoDbException("MyCrypt keygen(): Required parameter not provided.");
        }

        $len = strlen($id);
        $key = '';
        for($i=0; $i<$len; $i++) {
            $chr = substr($id,$i,1);
            $val = ord(substr($id,$i,1));
            $lft = 0;
            do {
                $ndx = $val % 16;
                $chr = substr(self::HEXCHRS, $ndx, 1);
                $key = ($lft++ % 2 == 0 ? $chr . $key : $key . $chr);
                $val = ($val - $ndx) / 16;
            } while($val > 0);
        }

        while (strlen($key) < 64) $key .= $key;

        $key = substr($key, 0, 64);

        return $key;
    }
}

