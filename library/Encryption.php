<?php
class Encryption {
    const SCRAMBLE1 = '! "#%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
    const SCRAMBLE2 = 'f^jAE]okI\OzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!"#\'~(;Lt-R}Ma,NvW+Ynb*0X';

    private $adj;    // 1st adjustment value (optional)
    private $mod;

    /**
     * class constructor
     */
    public function __construct() {
        $this->adj = 1.75; // this value is added to the rolling fudgefactors
        $this->mod = 3; // if divisible by this the adjustment is made negative
    }

    /**
     * Decrypt previously encrypted key.
     * @param string $key Encroption key.
     * @param string $source Value to be encrypted.
     * @return boolean|string Encrypted value.
     * @throws new Exception if an error occurs.
     */
    public function decrypt($key, $source) {
        if (empty($source)) {
             throw new Exception('No value has been supplied for decryption');
        }

        $target = null;
        $factor2 = 0;

        $key .= "" . strlen($source);
        $key = md5($key);

        // Convert $key into a sequence of numbers. Note that if an error
        // is thrown, it will be pass on.
        $fudgefactor = $this->_convertKey($key);

        for ($i = 0; $i < strlen($source); $i++) {
            // extract a character from $source
            $char2 = substr($source, $i, 1);
            // identify its position in $scramble2
            $num2 = strpos(self::SCRAMBLE2, $char2);
            if ($num2 === false) {
                throw new Exception("Source string contains an invalid character ($char2)");
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

    /**
     * Encrypt specified value.
     * @param string $key Value prepended to source to produce a more
     *        secure Encryption/
     * @param string $source Value to encrypt.
     * @param number $sourcelen Lenght of resulting encrypted value.
     * @return string Encrypted value.
     * @throws Exception if an error occurs.
     */
    public function encrypt($key, $source, $sourcelen = 0) {
        if (empty($source)) {
            throw new Exception("No value has been supplied for Encryption");
        }

        str_pad($source, $sourcelen, " ");

        $key .= "" . strlen($source);
        $key = md5($key);

        // convert $key into a sequence of numbers. If error thrown
        // it will be passed on.
        $fudgefactor = $this->_convertKey($key);

        $target = null;
        $factor2 = 0;
        for ($i = 0; $i < strlen($source); $i++) {
            $char1 = substr($source, $i, 1);         // extract a character from $source
            $num1 = strpos(self::SCRAMBLE1, $char1); // identify its position in $scramble1
            if ($num1 === false) {
                throw new Exception("Source string contains an invalid character ($char1)");
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

    /**
     * getter of class property.
     * @return number Adjustment setting.
     */
    public function getAdjustment() {
        return $this->adj;
    }

    /**
     * getter of class property
     * @return number Modulus setting.
     */
    public function getModulus() {
        return $this->mod;
    }

    /**
     * setter for class property
     * @param number $adj New adjustment setting
     */
    public function setAdjustment($adj) {
        $this->adj = (float)$adj;
    }

    /**
     * Set modulus value to use in encryption
     * @param number $mod New modulus value. Note that it will be
     *        made a positive integer.
     */
    public function setModulus($mod) {
        $this->mod = (int)abs($mod); // must be a positive whole number
    }

    /**
     * Apply a specified fudge factor to the <b>adj</b> value.
     * @param array $fudgefactor Array of fudge factor values.
     * @return number Adjustment after fudge facktor applied.
     */
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

    /**
     * Adjust number to be within calculated limit.
     * @param number $num Value to adjust as necessary.
     * @return number Adjusted value.
     */
    private function _checkRange($num) {
        $num = round($num); // round up to nearest whole number

        // indexing starts at 0, not 1, so subtract 1 from string length
        $limit = strlen(self::SCRAMBLE1) - 1;
        while ($num > $limit) $num -= $limit; // value too high, so reduce it
        while ($num < 0) $num += $limit; // value too low, so increase it
        return $num;
    }

    /**
     * Convert the encryption key into the required form.
     * @param string $key Key value
     * @return string Converted key.
     * @throws Exception if error occurs.
     */
    private function _convertKey($key) {
        if (empty($key)) {
            throw new Exception('No value has been supplied for the Encryption key');
        }

        $lcl_array = array();
        $lcl_array[] = strlen($key); // first entry in array is length of $key
        $tot = 0;

        for ($i = 0; $i < strlen($key); $i++) {
            // extract a character from $key
            $char = substr($key, $i, 1);
            // identify its position in $scramble1
            if (($num = strpos(self::SCRAMBLE1, $char)) === false) {
                throw new Exception("Key contains an invalid character ($char)");
            }

            $lcl_array[] = $num; // store in output array
            $tot += $num; // accumulate total for later
        }

        $lcl_array[] = $tot; // insert total as last entry in array
        return $lcl_array;
    }
}
