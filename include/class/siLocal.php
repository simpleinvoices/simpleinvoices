<?php
/**
 * siLocal class for value formatting.
 */
class siLocal {
    const DATE_FORMAT_PARAMETER = "/(full|long|date_short|short|month|month_short|medium)/";

    /**
     * Format numbers.
     * Note: This is a wrapper for the <b>Zend_Locale_Format::toNumber</b> function.
     * @param string $number Number to be formatted
     * @param string $precision Decimal precision.
     * @param string $locale Locale the number is to be formatted for.
     * @param string $symbol Currency symbol. Defaults to no symbol used.
     * @return string Formatted number.
     * @throws Zend_Locale_Exception
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
     * Format number in default form.
     * Note: Default form is without leading & trailing zeros, and locale decimal point (period or comma).
     * @param string $number Numeric value to be formatted.
     * @return string Formatted string.
     * @throws Zend_Locale_Exception
     */
    public static function number_trim($number, $precision = "", $locale = "", $symbol = "") {
        global $config;

        if (empty($locale)) $locale = new Zend_Locale($config->local->locale);

        if (empty($precision)) $precision = $config->local->precision;

        $formatted_number = self::number( $number, $precision, $locale, $symbol );

        // Calculate the decimal point right offset.
        $position = ($precision + 1) * (-1);

        // Get character in the decimal point position. Check if it is a
        // decimal point. If so, remove it if it is followed only by zeros.
        // Note this differs in that it won't trim trailing zeroes if there
        // are non-zero characters following the decimal point. (ex: 1.10 won't trim).
        $chr = substr($formatted_number, $position, 1);
        if ($chr == '.' || $chr == ',') {
            $formatted_number = rtrim (trim($formatted_number, '0'), '.,');
        }

        return $formatted_number;
    }

    /**
     * Convert a localized number back to the format stored in the database.
     * @param string $number
     * @return string Number formatted for database storage (ex: 12.345,67 converts to 12345.67)
     * @throws Zend_Locale_Exception
     */
    public static function dbStd($number) {
        global $config;

        $locale = new Zend_Locale($config->local->locale);
        $new_number = (empty($number) ? "0" : $number);
        $new_number = Zend_Locale_Format::getNumber($new_number, ['locale' => $locale, 'precision' => $config->local->precision]);
        return $new_number;
    }

    /**
     * Format a date value.
     * Note: This is a wrapper for the <b>Zend_Date</b> function.
     * @param string $date Date value to be forematted.
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
}
