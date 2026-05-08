<?php

class LocaleHelper
{
    private static ?array $localeMap = null;

    /**
     * Return a map of locale_code => display_label sorted by display label.
     * Label format: "Language (Region) (xx_XX)" e.g. "English (Australia) (en_AU)"
     */
    public static function getLocaleList(): array
    {
        if (self::$localeMap !== null) {
            return self::$localeMap;
        }

        $codes = [];

        if (class_exists('\ResourceBundle')) {
            try {
                $all = ResourceBundle::getLocales('');
            } catch (\Throwable $e) {
                try {
                    $all = ResourceBundle::getLocales('root');
                } catch (\Throwable $e2) {
                    $all = [];
                }
            }
            if (!empty($all)) {
                foreach ($all as $l) {
                    if (preg_match('/^[a-z]{2}_[A-Z]{2}$/', $l)) {
                        $codes[] = $l;
                    }
                }
            }
        }

        if (empty($codes) && extension_loaded('intl')) {
            foreach (\IntlCalendar::getAvailableLocales() as $l) {
                if (preg_match('/^[a-z]{2}_[A-Z]{2}$/', $l)) {
                    $codes[] = $l;
                }
            }
        }

        if (empty($codes)) {
            $codes = array_keys(self::hardcodedMap());
        }

        $map = [];
        foreach ($codes as $code) {
            $label = self::displayLabel($code);
            $map[$code] = $label;
        }

        // Sort by display label, case-insensitive
        uasort($map, function ($a, $b) {
            return strcasecmp($a, $b);
        });

        return self::$localeMap = $map;
    }

    private static function displayLabel(string $code): string
    {
        if (extension_loaded('intl')) {
            try {
                $language = \Locale::getDisplayLanguage($code, $code);
                $region   = \Locale::getDisplayRegion($code, $code);
                if ($language !== $code && $region !== '') {
                    return "$language ($region) ($code)";
                }
                if ($language !== $code) {
                    return "$language ($code)";
                }
            } catch (\Throwable $e) {
                // Fall through to hardcoded
            }
        }

        return self::hardcodedMap()[$code] ?? $code;
    }

    private static function hardcodedMap(): array
    {
        return [
            'af_ZA' => 'Afrikaans (South Africa) (af_ZA)',
            'ar_AE' => 'Arabic (UAE) (ar_AE)',
            'ar_BH' => 'Arabic (Bahrain) (ar_BH)',
            'ar_DZ' => 'Arabic (Algeria) (ar_DZ)',
            'ar_EG' => 'Arabic (Egypt) (ar_EG)',
            'ar_IQ' => 'Arabic (Iraq) (ar_IQ)',
            'ar_JO' => 'Arabic (Jordan) (ar_JO)',
            'ar_KW' => 'Arabic (Kuwait) (ar_KW)',
            'ar_LB' => 'Arabic (Lebanon) (ar_LB)',
            'ar_LY' => 'Arabic (Libya) (ar_LY)',
            'ar_MA' => 'Arabic (Morocco) (ar_MA)',
            'ar_OM' => 'Arabic (Oman) (ar_OM)',
            'ar_QA' => 'Arabic (Qatar) (ar_QA)',
            'ar_SA' => 'Arabic (Saudi Arabia) (ar_SA)',
            'ar_SY' => 'Arabic (Syria) (ar_SY)',
            'ar_TN' => 'Arabic (Tunisia) (ar_TN)',
            'ar_YE' => 'Arabic (Yemen) (ar_YE)',
            'be_BY' => 'Belarusian (Belarus) (be_BY)',
            'bg_BG' => 'Bulgarian (Bulgaria) (bg_BG)',
            'bn_BD' => 'Bengali (Bangladesh) (bn_BD)',
            'bn_IN' => 'Bengali (India) (bn_IN)',
            'ca_ES' => 'Catalan (Spain) (ca_ES)',
            'cs_CZ' => 'Czech (Czechia) (cs_CZ)',
            'cy_GB' => 'Welsh (UK) (cy_GB)',
            'da_DK' => 'Danish (Denmark) (da_DK)',
            'de_AT' => 'German (Austria) (de_AT)',
            'de_BE' => 'German (Belgium) (de_BE)',
            'de_CH' => 'German (Switzerland) (de_CH)',
            'de_DE' => 'German (Germany) (de_DE)',
            'de_LU' => 'German (Luxembourg) (de_LU)',
            'el_CY' => 'Greek (Cyprus) (el_CY)',
            'el_GR' => 'Greek (Greece) (el_GR)',
            'en_AS' => 'English (American Samoa) (en_AS)',
            'en_AU' => 'English (Australia) (en_AU)',
            'en_BE' => 'English (Belgium) (en_BE)',
            'en_BW' => 'English (Botswana) (en_BW)',
            'en_BZ' => 'English (Belize) (en_BZ)',
            'en_CA' => 'English (Canada) (en_CA)',
            'en_GB' => 'English (UK) (en_GB)',
            'en_GU' => 'English (Guam) (en_GU)',
            'en_HK' => 'English (Hong Kong) (en_HK)',
            'en_IE' => 'English (Ireland) (en_IE)',
            'en_IN' => 'English (India) (en_IN)',
            'en_JM' => 'English (Jamaica) (en_JM)',
            'en_MH' => 'English (Marshall Islands) (en_MH)',
            'en_MP' => 'English (N. Mariana Islands) (en_MP)',
            'en_MT' => 'English (Malta) (en_MT)',
            'en_NA' => 'English (Namibia) (en_NA)',
            'en_NZ' => 'English (New Zealand) (en_NZ)',
            'en_PH' => 'English (Philippines) (en_PH)',
            'en_PK' => 'English (Pakistan) (en_PK)',
            'en_SG' => 'English (Singapore) (en_SG)',
            'en_TT' => 'English (Trinidad & Tobago) (en_TT)',
            'en_UM' => 'English (US Minor Outlying Is.) (en_UM)',
            'en_US' => 'English (US) (en_US)',
            'en_VI' => 'English (US Virgin Islands) (en_VI)',
            'en_ZA' => 'English (South Africa) (en_ZA)',
            'en_ZW' => 'English (Zimbabwe) (en_ZW)',
            'es_AR' => 'Spanish (Argentina) (es_AR)',
            'es_BO' => 'Spanish (Bolivia) (es_BO)',
            'es_CL' => 'Spanish (Chile) (es_CL)',
            'es_CO' => 'Spanish (Colombia) (es_CO)',
            'es_CR' => 'Spanish (Costa Rica) (es_CR)',
            'es_CU' => 'Spanish (Cuba) (es_CU)',
            'es_DO' => 'Spanish (Dominican Republic) (es_DO)',
            'es_EC' => 'Spanish (Ecuador) (es_EC)',
            'es_ES' => 'Spanish (Spain) (es_ES)',
            'es_GQ' => 'Spanish (Equatorial Guinea) (es_GQ)',
            'es_GT' => 'Spanish (Guatemala) (es_GT)',
            'es_HN' => 'Spanish (Honduras) (es_HN)',
            'es_MX' => 'Spanish (Mexico) (es_MX)',
            'es_NI' => 'Spanish (Nicaragua) (es_NI)',
            'es_PA' => 'Spanish (Panama) (es_PA)',
            'es_PE' => 'Spanish (Peru) (es_PE)',
            'es_PR' => 'Spanish (Puerto Rico) (es_PR)',
            'es_PY' => 'Spanish (Paraguay) (es_PY)',
            'es_SV' => 'Spanish (El Salvador) (es_SV)',
            'es_US' => 'Spanish (US) (es_US)',
            'es_UY' => 'Spanish (Uruguay) (es_UY)',
            'es_VE' => 'Spanish (Venezuela) (es_VE)',
            'et_EE' => 'Estonian (Estonia) (et_EE)',
            'eu_ES' => 'Basque (Spain) (eu_ES)',
            'fa_AF' => 'Persian (Afghanistan) (fa_AF)',
            'fa_IR' => 'Persian (Iran) (fa_IR)',
            'fi_FI' => 'Finnish (Finland) (fi_FI)',
            'fo_FO' => 'Faroese (Faroe Islands) (fo_FO)',
            'fr_BE' => 'French (Belgium) (fr_BE)',
            'fr_BF' => 'French (Burkina Faso) (fr_BF)',
            'fr_BI' => 'French (Burundi) (fr_BI)',
            'fr_BJ' => 'French (Benin) (fr_BJ)',
            'fr_CA' => 'French (Canada) (fr_CA)',
            'fr_CD' => 'French (Congo - Kinshasa) (fr_CD)',
            'fr_CF' => 'French (Central African Republic) (fr_CF)',
            'fr_CG' => 'French (Congo - Brazzaville) (fr_CG)',
            'fr_CH' => 'French (Switzerland) (fr_CH)',
            'fr_CI' => 'French (Côte d\'Ivoire) (fr_CI)',
            'fr_CM' => 'French (Cameroon) (fr_CM)',
            'fr_DJ' => 'French (Djibouti) (fr_DJ)',
            'fr_FR' => 'French (France) (fr_FR)',
            'fr_GA' => 'French (Gabon) (fr_GA)',
            'fr_GF' => 'French (French Guiana) (fr_GF)',
            'fr_GN' => 'French (Guinea) (fr_GN)',
            'fr_GP' => 'French (Guadeloupe) (fr_GP)',
            'fr_KM' => 'French (Comoros) (fr_KM)',
            'fr_LU' => 'French (Luxembourg) (fr_LU)',
            'fr_MC' => 'French (Monaco) (fr_MC)',
            'fr_MG' => 'French (Madagascar) (fr_MG)',
            'fr_ML' => 'French (Mali) (fr_ML)',
            'fr_MQ' => 'French (Martinique) (fr_MQ)',
            'fr_MR' => 'French (Mauritania) (fr_MR)',
            'fr_MU' => 'French (Mauritius) (fr_MU)',
            'fr_NC' => 'French (New Caledonia) (fr_NC)',
            'fr_NE' => 'French (Niger) (fr_NE)',
            'fr_PF' => 'French (French Polynesia) (fr_PF)',
            'fr_RE' => 'French (Réunion) (fr_RE)',
            'fr_RW' => 'French (Rwanda) (fr_RW)',
            'fr_SC' => 'French (Seychelles) (fr_SC)',
            'fr_SN' => 'French (Senegal) (fr_SN)',
            'fr_TD' => 'French (Chad) (fr_TD)',
            'fr_TG' => 'French (Togo) (fr_TG)',
            'fr_TN' => 'French (Tunisia) (fr_TN)',
            'fr_VU' => 'French (Vanuatu) (fr_VU)',
            'fy_NL' => 'Western Frisian (Netherlands) (fy_NL)',
            'ga_IE' => 'Irish (Ireland) (ga_IE)',
            'gd_GB' => 'Scottish Gaelic (UK) (gd_GB)',
            'gl_ES' => 'Galician (Spain) (gl_ES)',
            'gu_IN' => 'Gujarati (India) (gu_IN)',
            'he_IL' => 'Hebrew (Israel) (he_IL)',
            'hi_IN' => 'Hindi (India) (hi_IN)',
            'hr_BA' => 'Croatian (Bosnia) (hr_BA)',
            'hr_HR' => 'Croatian (Croatia) (hr_HR)',
            'hu_HU' => 'Hungarian (Hungary) (hu_HU)',
            'hy_AM' => 'Armenian (Armenia) (hy_AM)',
            'id_ID' => 'Indonesian (Indonesia) (id_ID)',
            'is_IS' => 'Icelandic (Iceland) (is_IS)',
            'it_CH' => 'Italian (Switzerland) (it_CH)',
            'it_IT' => 'Italian (Italy) (it_IT)',
            'ja_JP' => 'Japanese (Japan) (ja_JP)',
            'ka_GE' => 'Georgian (Georgia) (ka_GE)',
            'kk_KZ' => 'Kazakh (Kazakhstan) (kk_KZ)',
            'km_KH' => 'Khmer (Cambodia) (km_KH)',
            'kn_IN' => 'Kannada (India) (kn_IN)',
            'ko_KR' => 'Korean (South Korea) (ko_KR)',
            'ky_KG' => 'Kyrgyz (Kyrgyzstan) (ky_KG)',
            'lb_LU' => 'Luxembourgish (Luxembourg) (lb_LU)',
            'lo_LA' => 'Lao (Laos) (lo_LA)',
            'lt_LT' => 'Lithuanian (Lithuania) (lt_LT)',
            'lv_LV' => 'Latvian (Latvia) (lv_LV)',
            'mi_NZ' => 'Maori (New Zealand) (mi_NZ)',
            'mk_MK' => 'Macedonian (North Macedonia) (mk_MK)',
            'ml_IN' => 'Malayalam (India) (ml_IN)',
            'mn_MN' => 'Mongolian (Mongolia) (mn_MN)',
            'mr_IN' => 'Marathi (India) (mr_IN)',
            'ms_BN' => 'Malay (Brunei) (ms_BN)',
            'ms_MY' => 'Malay (Malaysia) (ms_MY)',
            'mt_MT' => 'Maltese (Malta) (mt_MT)',
            'my_MM' => 'Burmese (Myanmar) (my_MM)',
            'nb_NO' => 'Norwegian Bokmål (Norway) (nb_NO)',
            'ne_IN' => 'Nepali (India) (ne_IN)',
            'ne_NP' => 'Nepali (Nepal) (ne_NP)',
            'nl_BE' => 'Dutch (Belgium) (nl_BE)',
            'nl_NL' => 'Dutch (Netherlands) (nl_NL)',
            'nn_NO' => 'Norwegian Nynorsk (Norway) (nn_NO)',
            'or_IN' => 'Odia (India) (or_IN)',
            'pa_Guru_IN' => 'Punjabi (Gurmukhi, India) (pa_Guru_IN)',
            'pl_PL' => 'Polish (Poland) (pl_PL)',
            'ps_AF' => 'Pashto (Afghanistan) (ps_AF)',
            'pt_AO' => 'Portuguese (Angola) (pt_AO)',
            'pt_BR' => 'Portuguese (Brazil) (pt_BR)',
            'pt_CH' => 'Portuguese (Switzerland) (pt_CH)',
            'pt_CV' => 'Portuguese (Cape Verde) (pt_CV)',
            'pt_GQ' => 'Portuguese (Equatorial Guinea) (pt_GQ)',
            'pt_GW' => 'Portuguese (Guinea-Bissau) (pt_GW)',
            'pt_LU' => 'Portuguese (Luxembourg) (pt_LU)',
            'pt_MO' => 'Portuguese (Macao) (pt_MO)',
            'pt_MZ' => 'Portuguese (Mozambique) (pt_MZ)',
            'pt_PT' => 'Portuguese (Portugal) (pt_PT)',
            'pt_ST' => 'Portuguese (São Tomé) (pt_ST)',
            'pt_TL' => 'Portuguese (Timor-Leste) (pt_TL)',
            'ro_MD' => 'Romanian (Moldova) (ro_MD)',
            'ro_RO' => 'Romanian (Romania) (ro_RO)',
            'ru_BY' => 'Russian (Belarus) (ru_BY)',
            'ru_KG' => 'Russian (Kyrgyzstan) (ru_KG)',
            'ru_KZ' => 'Russian (Kazakhstan) (ru_KZ)',
            'ru_MD' => 'Russian (Moldova) (ru_MD)',
            'ru_RU' => 'Russian (Russia) (ru_RU)',
            'ru_UA' => 'Russian (Ukraine) (ru_UA)',
            'rw_RW' => 'Kinyarwanda (Rwanda) (rw_RW)',
            'si_LK' => 'Sinhala (Sri Lanka) (si_LK)',
            'sk_SK' => 'Slovak (Slovakia) (sk_SK)',
            'sl_SI' => 'Slovenian (Slovenia) (sl_SI)',
            'sq_AL' => 'Albanian (Albania) (sq_AL)',
            'sq_MK' => 'Albanian (North Macedonia) (sq_MK)',
            'sq_XK' => 'Albanian (Kosovo) (sq_XK)',
            'sr_BA' => 'Serbian (Bosnia) (sr_BA)',
            'sr_Cyrl_BA' => 'Serbian (Cyrillic, Bosnia) (sr_Cyrl_BA)',
            'sr_Cyrl_ME' => 'Serbian (Cyrillic, Montenegro) (sr_Cyrl_ME)',
            'sr_Cyrl_RS' => 'Serbian (Cyrillic, Serbia) (sr_Cyrl_RS)',
            'sr_Cyrl_XK' => 'Serbian (Cyrillic, Kosovo) (sr_Cyrl_XK)',
            'sr_Latn_BA' => 'Serbian (Latin, Bosnia) (sr_Latn_BA)',
            'sr_Latn_ME' => 'Serbian (Latin, Montenegro) (sr_Latn_ME)',
            'sr_Latn_RS' => 'Serbian (Latin, Serbia) (sr_Latn_RS)',
            'sr_Latn_XK' => 'Serbian (Latin, Kosovo) (sr_Latn_XK)',
            'sv_FI' => 'Swedish (Finland) (sv_FI)',
            'sv_SE' => 'Swedish (Sweden) (sv_SE)',
            'sw_CD' => 'Swahili (Congo - Kinshasa) (sw_CD)',
            'sw_KE' => 'Swahili (Kenya) (sw_KE)',
            'sw_TZ' => 'Swahili (Tanzania) (sw_TZ)',
            'sw_UG' => 'Swahili (Uganda) (sw_UG)',
            'ta_IN' => 'Tamil (India) (ta_IN)',
            'ta_LK' => 'Tamil (Sri Lanka) (ta_LK)',
            'ta_MY' => 'Tamil (Malaysia) (ta_MY)',
            'ta_SG' => 'Tamil (Singapore) (ta_SG)',
            'te_IN' => 'Telugu (India) (te_IN)',
            'th_TH' => 'Thai (Thailand) (th_TH)',
            'ti_ER' => 'Tigrinya (Eritrea) (ti_ER)',
            'ti_ET' => 'Tigrinya (Ethiopia) (ti_ET)',
            'tk_TM' => 'Turkmen (Turkmenistan) (tk_TM)',
            'tn_ZA' => 'Tswana (South Africa) (tn_ZA)',
            'tr_CY' => 'Turkish (Cyprus) (tr_CY)',
            'tr_TR' => 'Turkish (Turkey) (tr_TR)',
            'tt_RU' => 'Tatar (Russia) (tt_RU)',
            'ug_CN' => 'Uyghur (China) (ug_CN)',
            'uk_UA' => 'Ukrainian (Ukraine) (uk_UA)',
            'ur_IN' => 'Urdu (India) (ur_IN)',
            'ur_PK' => 'Urdu (Pakistan) (ur_PK)',
            'uz_Arab_AF' => 'Uzbek (Arabic, Afghanistan) (uz_Arab_AF)',
            'uz_Cyrl_UZ' => 'Uzbek (Cyrillic, Uzbekistan) (uz_Cyrl_UZ)',
            'uz_Latn_UZ' => 'Uzbek (Latin, Uzbekistan) (uz_Latn_UZ)',
            'vi_VN' => 'Vietnamese (Vietnam) (vi_VN)',
            'wo_SN' => 'Wolof (Senegal) (wo_SN)',
            'yo_NG' => 'Yoruba (Nigeria) (yo_NG)',
            'zh_CN' => 'Chinese (China) (zh_CN)',
            'zh_HK' => 'Chinese (Hong Kong) (zh_HK)',
            'zh_Hans_CN' => 'Chinese (Simplified, China) (zh_Hans_CN)',
            'zh_Hans_HK' => 'Chinese (Simplified, Hong Kong) (zh_Hans_HK)',
            'zh_Hans_MO' => 'Chinese (Simplified, Macao) (zh_Hans_MO)',
            'zh_Hans_SG' => 'Chinese (Simplified, Singapore) (zh_Hans_SG)',
            'zh_Hant_HK' => 'Chinese (Traditional, Hong Kong) (zh_Hant_HK)',
            'zh_Hant_MO' => 'Chinese (Traditional, Macao) (zh_Hant_MO)',
            'zh_Hant_TW' => 'Chinese (Traditional, Taiwan) (zh_Hant_TW)',
            'zh_MO' => 'Chinese (Macao) (zh_MO)',
            'zh_SG' => 'Chinese (Singapore) (zh_SG)',
            'zh_TW' => 'Chinese (Taiwan) (zh_TW)',
            'zu_ZA' => 'Zulu (South Africa) (zu_ZA)',
        ];
    }
}
