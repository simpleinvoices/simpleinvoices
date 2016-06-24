<?php
// @formatter:off
$LCL_LANG = array(
    'company_logo'              => "Company Logo File Name",
    'company_name_item'         => "Company Name",
    'confirm_password'          => "Confirm Password",
    'help_confirm_password'     => "Re-enter the new password to confirm it.",
    'help_company_logo'         => "Enter the file name of your company logo to display on the SI login screen. It will
                                    appear to the left of the company name. The logo must be placed in the
                                    <b>extensions/user_security/images</b> folder.<br/><br/>You will need to size
                                    the logo so that it will display properly on the login screen.",
    'help_company_name_item'    => "Enter the name of your company to brand your implementation of SimpleInvoices.
                                    You can use <i>HTML</i> tags such as <b><i>&lt;br/&gt;</i></b> to force a line
                                    break for long names.",
    'help_email_address'        => "Enter the email address for this user. This value is required and must conform to
                                    standard email name format requirements.",
    'help_new_password'         => "",
    'help_password_lower'       => "<b>ENABLE</b> if passwords must contain at least one lowercase character.
                                    <b>DISABLE</b> if not.",
    'help_password_min_length'  => "Enter a number between <b>6</b> and <b>16</b> for the minimum length a password must be.",
    'help_password_number'      => "<b>ENABLE</b> if passwords must contain at least one numeric character.
                                    <b>DISABLE</b> if not.",
    'help_password_special'     => "<b>ENABLE</b> if passwords must contain at least one special character.
                                    <b>DISABLE</b> if not.",
    'help_password_upper'       => "<b>ENABLE</b> if passwords must contain at least one uppercase character.
                                    <b>DISABLE</b> if not.",
    'help_session_timeout'      => "This field, specified in minutes, is the inactive session timeout period. The minimum
                                    setting is 15 (minutes) and the maximum is 999 (minutes) which is effectively no
                                    timeout. The period is renewed each time the user submits an entry.",
    'help_user_enabled'         => "Select <b>Enabled</b> or <b>Disabled</b> to allow or disallow access by this user.",
    'help_username'             => "Enter a unique <b><i>User Name</i></b> to be assigned to this user. It must be at least
                                    6-characters long and begin with an alpha character. The remaining characters can be
                                    upper or lower case alpha or numberic characters as well as any of the following
                                    special characters: <b>@</b>, <b>_</b>, <b>-</b>, <b>.</b>, <b>#</b> and <b>$</b>.
                                    No blanks or other characters are allowed.",
    'password_lower'            => "Password Lowercase Required",
    'password_min_length'       => "Mininum Password Length",
    'password_number'           => "Password Number Required",
    'password_special'          => "Password Special Required",
    'password_upper'            => "Password Upper Required",
    'session_timeout'           => "Session Timeout",
    'username'                  => "User Name"
);
// @formatter:on
$pwd_msg = "Passwords must:
            <ul>
              <li>Begin with an alpha character</li>
              <li>Be at least " . $defaults['password_min_length'] . "-characters long</li>
              <li>Contain no blanks</li>";

if ($defaults['password_upper'] == 1) {
    $pwd_msg .= "<li>Contain at least one upper case character</li>";
}

if ($defaults['password_lower'] == 1) {
    $pwd_msg .= "<li>Contain at least one lower case character</li>";
}

if ($defaults['password_number'] == 1) {
    $pwd_msg .= "<li>Contain at least one numeric character</li>";
}

if ($defaults['password_special'] == 1) {
    $pwd_msg .= "<li>Contain at least one special character</li>";
}

$LCL_LANG['help_new_password'] = $pwd_msg . "</ul>";

$LANG = array_merge($LANG, $LCL_LANG);
