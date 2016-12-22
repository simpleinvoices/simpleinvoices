<?php
<<<<<<< HEAD
/**
 * siLocal class for value formatting. 
 */
class siLocal {
    const DATE_FORMAT_PARAMETER = "/(full|long|date_short|short|month|month_short|medium)/";
=======
/* Class: wrapper class for zend locale*/
class siLocal 
{
	/*Function: wrapper function for zend_locale_format::toNumber*/
	public static function number($number,$precision="",$locale="")
	{
		global $config;
		
		$locale = ($locale == "") ? new Zend_Locale($config->local->locale) : $locale;
		$load_precision = $config->local->precision; 
		
		$precision = ($precision == "") ? $load_precision : $precision;
		$formatted_number = Zend_Locale_Format::toNumber($number, array('precision' => $precision, 'locale' => $locale));
		
		//trim zeros from decimal point if enabled
		//if ($config->local->trim_zeros == "y") { $formatted_number = rtrim(trim($formatted_number, '0'), '.'); }
		
		return $formatted_number;
	}
	
    /*
    * Function: number_clean
    * Purpose: Remove trailing and leading zeros - just to return cleaner number in invoice creation from ajax product change
    */
    public function number_clean($num){

        //remove zeros from end of number ie. 140.00000 becomes 140.
        $clean = rtrim($num, '0');
        //remove zeros from front of number ie. 0.33 becomes .33
        $clean = ltrim($clean, '0');
        //remove decimal point if an integer ie. 140. becomes 140
        $clean = rtrim($clean, '.');
>>>>>>> refs/remotes/simpleinvoices/master

    /**
     * Format numbers.
     * Note: This is a wrapper for the <b>Zend_Locale_Format::toNumber</b> function.
     * @param string $number Number to be formatted
     * @param string $precision Decimal precision.
     * @param string $locale Locale the number is to be formatted for. 
     * @param string $symbol Currency symbol. Defaults to no symbol used.
     * @return string Formatted number.
     */
    public static function number($number, $precision = "", $locale = "", $symbol = "") {
        global $config;

        if (empty($locale)) $locale = new Zend_Locale($config->local->locale);

        if (empty($precision)) $precision = $config->local->precision;

        $formatted_number = Zend_Locale_Format::toNumber($number, array ('precision' => $precision, 'locale' => $locale));

        if (!empty($symbol)) $formatted_number = $symbol . $formatted_number;

        return $formatted_number;
    }

    /**
     * Clean specified numeric value.
     * Note: Cleaned value will have leading and trailing zeros removed and if there
     *       are not non-zero decial digits, the decimal point will be remoted.
     * @param string $number Value to clean.
     * @return string Cleaned value.
     */
    public static function number_clean($number) {
        $clean = rtrim ( $number, '0'); // remove zeros from end of number ie. 140.00000 becomes 140.
        $clean = ltrim ( $clean, '0' ); // remove zeros from front of number ie. 0.33 becomes .33
        $clean = rtrim ( $clean, '.' ); // remove decimal point if an integer ie. 140. becomes 140
        return $clean;
    }

    /**
     * Format number in default form.
     * Note: Default form is without trailing zeros, decimal point and comma.
     * @param string $number Numeric value to be formatted.
     * @return string Formatted string.
     */
    public static function number_trim($number) {
        global $config;

        $formatted_number = self::number( $number );

        // Calculate the decimal point right offset.
        $position = ($config->local->precision + 1) * - 1;

        // Trim any zeros trailing the decimal point.
        if (substr($formatted_number, $position, 1) == ".") {
            $formatted_number = rtrim (trim($formatted_number, '0'), '.');
        }

        // Trim any trailing zeros and the comma.
        if (substr($formatted_number, $position, 1) == ",") {
            $formatted_number = rtrim(trim($formatted_number, '0'), ',');
        }

        return $formatted_number;
<<<<<<< HEAD
    }

    /**
     * Format a date value.
     * Note: This is a wrapper for the <b>Zend_Date</b> function.
     * @param strin $date Date value to be forematted.
     * @param string $date_format (Optional) Date format. Values are:
     *        <ul>
     *          <li><b>day</b>        : Zend_Date constant DAY              - Ex: 06</li>
     *          <li><b>day_short</b>  : Zend_Date constant DAY_SHORT        - Ex: 6</li>
     *          <li><b>date_short</b> : Zend_Date constant DATE_SHORT       - Ex: 5/6/2017</li>
     *          <li><b>full</b>       : Zend_Date constant DATE_FULL        - Ex: Friday, May 6, 2017</li>
     *          <li><b>long</b>       : Zend_Date constant DATE_LONG        - Ex: May 6, 2017</li>
     *          <li><b>medium</b>     : Zend_Date constant DATE_MEDIUM      - Ex: 05/06/2017</li> 
     *          <li><b>month</b>      : Zend_Date constant MONTH_NAME       - Ex: 05</li>
     *          <li><b>month_short</b>: Zend_Date constant MONTH_NAME_SHORT - Ex: 5</li>
     *          <li><b>short</b>      : Zend_Date constant DATE_SHORT       - Ex: 5/6/2017</li>
     *        </ul>
     *        Defaults to <b>medium</b>.
     * @param string $locale (Optional) <i>locale</i> setting to format the date for.
     *        Defaults to <b>local.locale</b> setting in the <i>config.php</i> setting.
     *        Ex: en_US.
     * @return string <b>$date</b> formatted per option settings.
     * @throws Exception if an undefined <b>$date_format</b> is specified.
     */
    public static function date($date, $date_format = "medium", $locale = "") {
        global $config;

        if (!preg_match(self::DATE_FORMAT_PARAMETER, $date_format)) {
            $str = "siLocal - date(): Invalid date format, $date_format, specified.";
            error_log($str);
            throw new Exception($str);
        }
        if (!empty($locale)) $locale = new Zend_Locale($config->local->locale);

        $temp_date = new Zend_Date($date, 'yyyy-MM-dd');

        // @formatter:off
        switch ($date_format) {
            case "full"        : return $temp_date->get(Zend_Date::DATE_FULL       , $locale);
            case "long"        : return $temp_date->get(Zend_Date::DATE_LONG       , $locale);
            case "date_short"  : // Same as "short".
            case "short"       : return $temp_date->get(Zend_Date::DATE_SHORT      , $locale);
            case "month"       : return $temp_date->get(Zend_Date::MONTH_NAME      , $locale);
            case "month_short" : return $temp_date->get(Zend_Date::MONTH_NAME_SHORT, $locale);
            case "medium"      : // Same as default for any undefined parameter setting.
            default            :
                break;
        }
        // @formatter:on
        return $temp_date->get(Zend_Date::DATE_MEDIUM, $locale);
    }

    /**
     * Format a numbers for data entry fields.
     * Note: Example invoice edit/ajax where data is in 6 decimial places
     *       but only need 2 places in edit view.
     * $param string $number Number to be formatted.
     * @return string Formatted number.
     */
    
    public static function number_formatted($number) {
        global $config;

        $number_formatted = number_format($number, $config->local->precision, '.', '');
        return $number_formatted;
    }
=======
	}
	
	/*Function: wrapper function for zend_date*/
	public static function date($date,$length="",$locale="")
	{
		global $config;
		
		$locale = ($locale == "") ? new Zend_Locale($config->local->locale) : $locale;
		$length == "" ? $length = "medium" : $length = $length;
		/*
		 * Length can be any of the Zend_Date lengths - FULL, LONG, MEDIUM, SHORT
		 */

		$formatted_date = new Zend_Date($date,'yyyy-MM-dd');
		
		switch ($length) {
			case "full":
			    return $formatted_date->get(Zend_Date::DATE_FULL,$locale);
			    break;
			case "long":
			    return $formatted_date->get(Zend_Date::DATE_LONG,$locale);
			    break;
			case "medium":
			    return $formatted_date->get(Zend_Date::DATE_MEDIUM,$locale);
			    break;
			case "short":
			    return $formatted_date->get(Zend_Date::DATE_SHORT,$locale);
			    break;
			case "month":
			    return $formatted_date->get(Zend_Date::MONTH_NAME,$locale);
			    break;
			case "month_short":
			    return $formatted_date->get(Zend_Date::MONTH_NAME_SHORT,$locale);
			    break;
			default:
				return $formatted_date->get(Zend_Date::DATE_SHORT,$locale);
		}
		
	}


	/*
	 * Function: number_formatted
	 * Description: wrapper for php number_format
	 * Purpose: to format numbers for data entry fields - ie invoice edit/ajax where data is in 6 decimial places but only neex x places in edit view
	 */
	public static function number_formatted($number)
	{
		global $config;
	
		$number_formatted = number_format($number, $config->local->precision, '.', '');
		return $number_formatted;
	}
>>>>>>> refs/remotes/simpleinvoices/master
}
