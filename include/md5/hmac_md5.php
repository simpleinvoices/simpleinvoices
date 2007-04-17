<?php 
/**
 * Creates a HMAC digest that can be used for auth purposes.
 * See RFCs 2104, 2617, 2831
 * Uses mhash() extension if available
 *
 * Squirrelmail has this function in functions/auth.php, and it might have been
 * included already. However, it helps remove the dependancy on mhash.so PHP
 * extension, for some sites. If mhash.so _is_ available, it is used for its
 * speed.
 *
 * This function is Copyright (c) 1999-2003 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * @param string $data Data to apply hash function to.
 * @param string $key Optional key, which, if supplied, will be used to
 * calculate data's HMAC.
 * @return string HMAC Digest string
 */

function hmac_md5($data, $key='') {
    // See RFCs 2104, 2617, 2831
    // Uses mhash() extension if available
    if (extension_loaded('mhash')) {
      if ($key== '') {
        $mhash=mhash(MHASH_MD5,$data);
      } else {
        $mhash=mhash(MHASH_MD5,$data,$key);
      }
      return $mhash;
    }
    if (!$key) {
         return pack('H*',md5($data));
    }
    $key = str_pad($key,64,chr(0x00));
    if (strlen($key) > 64) {
        $key = pack("H*",md5($key));
    }
    $k_ipad =  $key ^ str_repeat(chr(0x36), 64) ;
    $k_opad =  $key ^ str_repeat(chr(0x5c), 64) ;
    /* Heh, let's get recursive. */
    $hmac=hmac_md5($k_opad . pack("H*",md5($k_ipad . $data)) );
    return $hmac;
}
?>
