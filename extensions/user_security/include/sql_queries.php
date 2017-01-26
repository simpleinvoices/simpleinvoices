<?php
/**
 * Get "password_lower" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultPasswordLower() {
    return getDefaultGeneric('password_lower');
}

/**
 * Get "password_min_length" entry from the system_defaults table.
 * @return string number setting.
 */
function getDefaultPasswordMinLength() {
    return getDefaultGeneric('password_min_length', false);
}

/**
 * Get "password_number" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultPasswordNumber() {
    return getDefaultGeneric('password_number');
}

/**
 * Get "password_special" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultPasswordSpecial() {
    return getDefaultGeneric('password_special');
}

/**
 * Get "password_upper" entry from the system_defaults table.
 * @return string "Enabled" or "Disabled"
 */
function getDefaultPasswordUpper() {
    return getDefaultGeneric('password_upper');
}
