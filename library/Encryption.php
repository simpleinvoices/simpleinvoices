<?php
class Encryption {
    const SCRAMBLE1 = '! "#%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
    const SCRAMBLE2 = 'f^jAE]okI\OzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!"#\'~(;Lt-R}Ma,NvW+Ynb*0X';

    private $errors; // array of error messages
    private $adj;    // 1st adjustment value (optional)
    private $mod;

    public function __construct() {
        $this->errors = array();
        $this->adj = 1.75; // this value is added to the rolling fudgefactors
        $this->mod = 3; // if divisible by this the adjustment is made negative
    }

    public function decrypt($key, $source) {
        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for decryption';
            return;
        }

        $target = null;
        $factor2 = 0;

        $key .= "" . strlen($source);
        $key = md5($key);

        // convert $key into a sequence of numbers
        $fudgefactor = $this->_convertKey($key);
        if ($this->errors) return;

        for ($i = 0; $i < strlen($source); $i++) {
            // extract a character from $source
            $char2 = substr($source, $i, 1);
            // identify its position in $scramble2
            $num2 = strpos(self::SCRAMBLE2, $char2);
            if ($num2 === false) {
                $this->errors[] = "Source string contains an invalid character ($char2)";
                return;
            }

            // get an adjustment value using $fudgefactor
            // @formatter:off
            $adj     = $this->_applyFudgeFactor($fudgefactor);
            $factor1 = $factor2 + $adj; // accumulate in $factor1
            $num1    = round($factor1 * -1) + ($num2); // generate offset for $scramble1
            $num1    = $this->_checkRange($num1); // check range
            $factor2 = $factor1 + $num2; // accumulate in $factor2
            // @formatter:on

            // Extract character from SCRAMBLE1 and append to $target string
            $char1 = substr(self::SCRAMBLE1, $num1, 1);
            $target .= $char1;
        }

        return rtrim($target);
    }

    public function encrypt($key, $source, $sourcelen = 0) {
        if (empty($source)) {
            $this->errors[] = 'No value has been supplied for Encryption';
            return;
        }

        str_pad($source, $sourcelen, " ");

        $key .= "" . strlen($source);
        $key = md5($key);

        // convert $key into a sequence of numbers
        $fudgefactor = $this->_convertKey($key);
        if (!empty($this->errors)) return;

        $target = null;
        $factor2 = 0;
        for ($i = 0; $i < strlen($source); $i++) {
            $char1 = substr($source, $i, 1);         // extract a character from $source
            $num1 = strpos(self::SCRAMBLE1, $char1); // identify its position in $scramble1
            if ($num1 === false) {
                $this->errors[] = "Source string contains an invalid character ($char1)";
                return;
            }

            // get an adjustment value using $fudgefactor
            // @formatter:off
            $adj     = $this->_applyFudgeFactor($fudgefactor);
            $factor1 = $factor2 + $adj;           // accumulate in $factor1
            $num2    = round($factor1) + ($num1); // generate offset for $scramble2
            $num2    = $this->_checkRange($num2); // check range
            $factor2 = $factor1 + $num2;          // accumulate in $factor2
            // @formatter:on

            // extract character from $scramble2
            $char2 = substr(self::SCRAMBLE2, $num2, 1);
            $target .= $char2;
        }

        return $target;
    }

    public function getAdjustment() {
        return $this->adj;
    }

    public function getModulus() {
        return $this->mod;
    }

    public function setAdjustment($adj) {
        $this->adj = (float)$adj;
    }

    public function setModulus($mod) {
        $this->mod = (int)abs($mod); // must be a positive whole number
    }

    // return an adjustment value based on the contents of $fudgefactor
    // NOTE: $fudgefactor is passed by reference so that it can be modified
    private function _applyFudgeFactor(&$fudgefactor) {
        $fudge = array_shift($fudgefactor); // extract 1st number from array
        $fudge += $this->adj;               // add in adjustment value
        $fudgefactor[] = $fudge;            // put it back at end of array

        // If the modifier has been supplied and the fudge value is evenly
        // divisible by it, negate the fudge value.
        if (!empty($this->mod)) {
            // if evenly divisible by modifier, negate it.
            if ($fudge % $this->mod == 0) {
                $fudge *= -1;
            }
        }

        return $fudge;
    }

    private function _checkRange($num) {
        $num = round($num); // round up to nearest whole number

        // indexing starts at 0, not 1, so subtract 1 from string length
        $limit = strlen(self::SCRAMBLE1) - 1;
        while ($num > $limit) $num -= $limit; // value too high, so reduce it
        while ($num < 0) $num += $limit; // value too low, so increase it
        return $num;
    }

    private function _convertKey($key) {
        if (empty($key)) {
            $this->errors[] = 'No value has been supplied for the Encryption key';
            return;
        }

        $lcl_array = array();
        $lcl_array[] = strlen($key); // first entry in array is length of $key
        $tot = 0;

        for ($i = 0; $i < strlen($key); $i++) {
            // extract a character from $key
            $char = substr($key, $i, 1);
            // identify its position in $scramble1
            if (($num = strpos(self::SCRAMBLE1, $char)) === false) {
                $this->errors[] = "Key contains an invalid character ($char)";
                return;
            }

            $lcl_array[] = $num; // store in output array
            $tot += $num; // accumulate total for later
        }

        $lcl_array[] = $tot; // insert total as last entry in array
        return $lcl_array;
    }
}

