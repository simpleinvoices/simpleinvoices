<?php

/*
 * Script: functions.php
 * Contain all non db query functions used in SimpleInvoices
 *
 * Authors:
 * - Justin Kelly
 *
 * License:
 * GNU GPL2 or above
 *
 * Date last edited:
 * Tue Jan 19 12:55:00 PST 2016
 * Mon Oct 28 12:00:00 IST 2013
 */
function checkLogin() {
    if (!defined("BROWSE")) {
        echo "You Cannot Access This Script Directly, Have a Nice Day.";
        exit();
    }
}

/**
 * Build path for the specified file type if it exists.
 * The first attempt is to make a custom path, if that file doesn't
 * exist, the regular path is checked. The first path that is for an
 * existing file is the path returned.
 * @param $name Name or dir/name of file without an extension.
 * @param $mode Set to "template" or "module".
 * @return file path or NULL if no file path determined.
 */
function getCustomPath($name, $mode = 'template') {
    $my_custom_path = "./custom/";
    $out = NULL;
    if ($mode == 'template') {
        if (file_exists("{$my_custom_path}default_template/{$name}.tpl")) {
            $out = ".{$my_custom_path}default_template/{$name}.tpl";
        } elseif (file_exists("./templates/default/{$name}.tpl")) {
            $out = "../templates/default/{$name}.tpl";
        }
    }
    if ($mode == 'module') {
        if (file_exists("{$my_custom_path}modules/{$name}.php")) {
            $out = "{$my_custom_path}modules/{$name}.php";
        } elseif (file_exists("./modules/{$name}.php")) {
            $out = "./modules/{$name}.php";
        }
    }
    return $out;
}

/**
 * Global function to see if an extension is enabled.
 * @param $ext_name Name of the extension to check for.
 * @return true if enabled, false if not.
 */
function isExtensionEnabled($ext_name) {
    global $ext_names;
    $enabled = false;
    foreach ($ext_names as $name) {
        if ($name == $ext_name) {
            $enabled = true;
            break;
        }
    }
    return $enabled;
}

function getLogoList() {
    $dirname = "./templates/invoices/logos";
    $ext = array("jpg", "png", "jpeg", "gif");
    $files = array();
    if ($handle = opendir($dirname)) {
        while (false !== ($file = readdir($handle))) {
            for ($i = 0; $i < sizeof($ext); $i++) {
                // NOT case sensitive: OK with JpeG, JPG, ecc.
                if (stristr($file, "." . $ext[$i])) $files[] = $file;
            }
        }
        closedir($handle);
    }

    sort($files);
    return $files;
}

function getLogo($biller) {
    $url = getURL();

    if (empty($biller['logo'])) {
        return $url . "/templates/invoices/logos/_default_blank_logo.png";
    }
    return $url . "/templates/invoices/logos/$biller[logo]";
}

/**
 * Function: get_custom_field_name
 *
 * Used by manage_custom_fields to get the name of the custom field and which section it relates to (ie,
 * biller/product/customer)
 *
 * Arguments:
 * field - The custom field in question
 */
function get_custom_field_name($field) {
    global $LANG;

    // grab the first character of the field variable
    $get_cf_letter = $field[0];
    // grab the last character of the field variable
    $get_cf_number = $field[strlen($field) - 1];

    // functon to return false if invalid custom_field
    $custom_field_name = "";
    switch ($get_cf_letter) {
        case "b":
            $custom_field_name = $LANG['biller'];
            break;
        case "c":
            $custom_field_name = $LANG['customer'];
            break;
        case "i":
            $custom_field_name = $LANG['invoice'];
            break;
        case "p":
            $custom_field_name = $LANG['products'];
            break;
        default:
            $custom_field_name = false;
    }

    // Append the rest of the string
    $custom_field_name .= " :: " . $LANG["custom_field"] . " " . $get_cf_number;
    return $custom_field_name;
}

/**
 * Create a drop down list for the specified array.
 * @param array $choiceArray Array of string values to stored in drop down list.
 * @param string $defVal Default value to selected option in list for.
 * @return String containing the HTML code for the drop down list.
 */
function dropDown($choiceArray, $defVal) {
    $dropDown = '<select name="value">' . "\n";
    foreach ($choiceArray as $key => $value) {
        // @formatter:off
        $dropDown .= '<option ' .
                          'value="' . htmlsafe($key) . '" ' .
                          ($key == $defVal ? 'selected style="font-weight: bold" >' :
                                             '>') .
                          htmlsafe($value) .
                     '</option>' .
                     "\n";
        // @formatter:on
    }
    $dropDown .= "</select>\n";
    return $dropDown;
}

function simpleInvoicesError($type, $info1 = "", $info2 = "") {
    if ($type == "dbConnection" && strstr($info1, "Unknown database") !== false) {
        $type = "install";
        $parts = explode("'", $info1);
        $dbname = $parts[1];
error_log("dbname[$dbname] parts - " . print_r($parts,true));
    }
    // @formatter:off
    switch ($type) {
        case "notWriteable":
            $error = exit("
            <br />
            ===========================================
            <br />
            SimpleInvoices error
            <br />
            ===========================================
            <br />
            The " . $info1 . " <b>" . $info2 . "</b> has to be writeable");
            break;

        case "dbConnection":
            $error = exit("
            <br />
            ===========================================
            <br />
            Simple Invoices database connection problem
            <br />
            ===========================================
            <br />
            <br />
            Could not connect to the Simple Invoices database
            <br />
            <br />
            For information on how to fix this pease refer to the following database error:
            <br />
            --> <b>$info1</b>
            <br />
            <br />
            If this is an &quot;Access denied&quot; error please enter the correct database
            connection details config/config.php
            <br />
            <br />
            <b>Note:</b> If you are installing Simple Invoices please follow the below steps:
            <br />
            1. Create a blank MySQL database
            <br />
            2. Enter the correct database connection details in the config/config.php file
            <br />
            3. Refresh this page
            <br />
            <br />
            ===========================================
            <br />
            ");
            break;

        case "install":
            global $config_file_path;
            $error = exit("
              <div id='Container' class='col si_wrap'>
                <div id='si_install_logo'>
                  <img src='images/common/simple_invoices_logo.jpg' class='si_install_logo' width='300'/>
                </div>
                <table class='center' style='width:50%'>
                  <tr>
                    <th style='font-weight: bold;text-align:center;'>
                      ===========================================
                    </th>
                  </tr>
                  <tr>
                    <th style='font-weight: bold;text-align:center;'>
                      SimpleInvoices database connection problem
                    </th>
                  </tr>
                  <tr>
                    <th style='font-weight: bold;text-align:center;'>
                      ===========================================
                    </th>
                  </tr>
                  <tr>
                    <th style='font-weight:normal;'>
                      You&#39;ve reached this page because the name of the database in your
                      configuration file has not been created. Please follow the the following
                      instructions before leaving this page.
                      <ol>
                        <li>Using your database admin program, phpMyAdmin for MySQL, create a database
                            preferably with UTF-8 collation. It can be named whatever you like but the
                            name currently in the configuration file is, $dbname.</li>
                        <li>Assign an administrative user and password to the database.</li>
                        <li>Enter the database connection details in the <strong>$config_file_path</strong> file.
                            The fields that need to be set are:
                            <ul style='font-family:\"Lucida Console\", \"Courier New\"'>
                                <li>database.params.host&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;localhost</li>
                                <li>database.params.username&nbsp;=&nbsp;root</li>
                                <li>database.params.password&nbsp;=&nbsp;&#39;mypassword&#39;</li>
                                <li>database.params.dbname&nbsp;&nbsp;&nbsp;=&nbsp;simple_invoices</li>
                                <li>database.params.port&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;3306</li>
                            </ul>
                        </li>
                        <li>When you have completed these steps, simply refresh this page and follow
                            the instructions to complete installation of SimpleInvoices.</li>
                      </ol>
                    </th>
                  </tr>
                </table>
              </div>
            ");
            break;


        case "PDO":
            $error = exit("
            <br />
            ===========================================
            <br />
            SimpleInvoices - PDO problem
            <br />
            ===========================================
            <br />
            <br />
            PDO is not configured in your PHP installation.
            <br />
            This means that SimpleInvoices can't be used.
            <br />
            <br />
            To fix this please installed the pdo_mysql php extension.
            <br />
            If you are using a webhost please email them and get them to
            <br />
            install PDO for PHP with the MySQL extension
            <br />
            <br />
            ===========================================
            <br />
            ");
            break;

        case "sql":
            $error = exit("
            <br />
            ===========================================
            <br />
            SimpleInvoices - SQL problem
            <br />
            ===========================================
            <br />
            <br />
            The following sql statement:
            <br />
            $info2
            <br />
            <br />
            had the following error code: " . $info1['1'] . "
            <br />
            with the message of:" . $info1['2'] . "
            <br />
            <br />
            ===========================================
            <br />
            ");
            break;

        case "PDO_mysql_attr":
            $error = exit("
            <br />
            ===========================================
            <br />
            SimpleInvoices - PDO - MySQL problem
            <br />
            ===========================================
            <br />
            <br />
            Your SimpleInvoices installation can't use the
            <br />
            database settings 'database.utf8'.
            <br />
            <br />
            To fix this please edit config/config.php and
            <br />
            set 'database.utf8' to 'false'
            <br />
            <br />
            ===========================================
            <br />
            ");
            break;
    }
    // @formatter:off

    return $error;
}

function getLangList() {
    $startdir = './lang/';
    $ignoredDirectory[] = '.';
    $ignoredDirectory[] = '..';
    $ignoredDirectory[] = '.svn';
    if (is_dir($startdir)) {
        if ($dh = opendir($startdir)) {
            while (($folder = readdir($dh)) !== false) {
                if (!(array_search($folder, $ignoredDirectory) > -1)) {
                    if (filetype($startdir . $folder) == "dir") {
                        $folderList[] = $folder;
                    }
                }
            }
            closedir($dh);
        }
    }
    sort($folderList);
    return ($folderList);
}

function sql2xml($sth, $count) {
    // you can choose any name for the starting tag
    $xml = ("<result>");
    $xml .= "<page>1</page>";
    $xml .= "<total>" . $count . "</total>";
    foreach ($sth as $row) {
        // count the no. of columns in the table
        $fcount = count($row);

        $xml .= ("<tablerow>");
        foreach ($row as $key => $value) {
            $xml .= ("<$key>" . htmlsafe($value) . "</$key>");
        }
        $xml .= ("</tablerow>");
    }
    $xml .= ("</result>");

    return $xml;
}

/**
 * Function: si_truncate
 *
 * Trucate a given string
 *
 * Parameters:
 * string - the string to truncate
 * max - the max length in characters to truncate the string to
 * rep - characters to be added at end of truncated string
 *
 * Returns:
 * The array sorted as you want
 */
function si_truncate($string, $max = 20, $rep = '') {
    if (strlen($string) <= ($max + strlen($rep))) {
        return $string;
    }
    $leave = $max - strlen($rep);
    return substr_replace($string, $rep, $leave);
}

/* Escapes HTML stuff */
function htmlsafe($str) {
    return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

/* Makes a string to be put inside a href="" safe */
function urlsafe($str) {
    $str = preg_replace('/[^a-zA-Z0-9@;:%_\+\.~#\?\/\=\&\/\-]/', '', $str);
    if (preg_match('/^\s*javascript/i', $str)) {
        return false;  // no javascript urls
    }
    $str = htmlsafe($str);
    return $str;
}

/* Sanitises HTML for output stuff */
function outhtml($html) {
    $config = HTMLPurifier_Config::createDefault();

    // configuration goes here:
    $config->set('Core.Encoding', 'UTF-8'); // replace with your encoding
    $config->set('HTML.Doctype', 'XHTML 1.0 Strict'); // replace with your doctype

    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);
}

// Generates a token to be used on forms that change something
function siNonce($action = false, $userid = false, $tickTock = false) {
    global $config;
    global $auth_session;

    $tickTock = ($tickTock) ? $tickTock : floor(time() / $config->nonce->timelimit);

    if (!$userid) {
        $userid = $auth_session->id;
    }

    $hash = md5($tickTock . ':' . $config->nonce->key . ':' . $userid . ':' . $action);

    return $hash;
}

// Verify a nonce token
function verifySiNonce($hash, $action, $userid = false) {
    global $config;

    $tickTock = floor(time() / $config->nonce->timelimit);
    if (!isempty($hash) &&
        ($hash === siNonce($action, $userid) || $hash === siNonce($action, $userid, $tickTock - 1))) {
        return true;
    }

    return false;
}

// Put this before an action is commited make sure to put a unique $action
function requireCSRFProtection($action = 'all', $userid = false) {
    verifySiNonce($_REQUEST['csrfprotectionbysr'], $action, $userid) or die('CSRF Attack Detected');
}

function antiCSRFHiddenInput($action = 'all', $userid = false) {
    return '<input type="hidden" name="csrfprotectionbysr" value="' . htmlsafe(siNonce($action, $userid)) . '" />';
}
