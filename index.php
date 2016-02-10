<?php
/*
 * Script: index.php
 * Main controller file for Simple Invoices
 *
 * License:
 * GPL v3 or above
 */
// stop browsing to files directly - all viewing to be handled by index.php
// if browse not defined then the page will exit
if (!defined("BROWSE")) define("BROWSE", "browse");

// **********************************************************
// The include configs and requirements stuff section - START
// **********************************************************

// Load stuff required before init.php
require_once ("./include/init_pre.php");

// @formatter:off
$module = isset($_GET['module']) ? filenameEscape($_GET['module']) : null;
$view   = isset($_GET['view'])   ? filenameEscape($_GET['view'])   : null;
$action = isset($_GET['case'])   ? filenameEscape($_GET['case'])   : null;

// globals set in the init.php logic
$databaseBuilt     = false;
$databasePopulated = false;
$patchCount        = false;
// @formatter:on

// Note: include/functions.php and include/sql_queries.php loaded by this include.
require_once ("./include/init.php");

// Remove disabled extensions from the array
$ext_names = array();
foreach ($config->extension as $extension) {
    if ($extension->enabled == "1") {
        $ext_names[] = $extension->name;
    }
}

foreach ($ext_names as $ext_name) {
    if (file_exists("./extensions/$ext_name/include/init.php")) {
        require_once ("./extensions/$ext_name/include/init.php");
    }
}

// **********************************************************
// The include configs and requirements stuff section - END
// **********************************************************
// @formatter:off
$smarty->assign("ext_names", $ext_names);
$smarty->assign("config"   , $config);
$smarty->assign("module"   ,$module);
$smarty->assign("view"     ,$view);
$smarty->assign("siUrl"    , $siUrl);
$smarty->assign("LANG"     ,$LANG);
$smarty->assign("enabled"  ,array($LANG['disabled'],$LANG['enabled']));
// @formatter:on

// Menu - hide or show menu
$menu = (isset($menu) ? $menu : true);

// Check for any unapplied SQL patches when going home
// TODO - redo this code
if (($module == "options") && ($view == "database_sqlpatches")) {
    include_once ('./include/sql_patches.php');
    donePatches();
} else {
    // Check that database structure has been built and populated.
    $skip_db_patches = false;
    if (!$databaseBuilt) {
        $module = "install";
        $view == "structure" ? $view = "structure" : $view = "index";
        $skip_db_patches = true; // do installer
    } else if (!$databasePopulated) {
        $module = "install";
        $view == "essential" ? $view = "essential" : $view = "structure";
        $skip_db_patches = true; // do installer
    }

    // See if we need to verify patches have been loaded.
    if ($skip_db_patches == false) {
        // If default user or an active session exists, proceed with check.
        if ($config->authentication->enabled == 0 || isset($auth_session->id)) {
            include_once ('./include/sql_patches.php');
            // Check if there are patches to process
            if (getNumberOfPatches() > 0) {
                $view = "database_sqlpatches";
                $module = "options";
                if ($action == "run") {
                    runPatches();
                } else {
                    listPatches();
                }
                $menu = false;
            } else {
                // There aren't patches to apply. So check to see if there are invoices in db.
                // If so, show the home page as default. Otherwise show Manage Invoices page
                if ($module == null) {
                    $invoiceobj = new invoice();
                    if ($invoiceobj->are_there_any() > "0") {
                        $module = "invoices";
                        $view = "manage";
                    } else {
                        $module = "index";
                        $view = "index";
                    }
                    unset($invoiceobj);
                }
            }
        }
    }
}

// Don't include the header if requested file is an invoice template.
// For print preview etc.. header is not needed
if (($module == "invoices") && (strstr($view, "template"))) {
    // Loop through the extensions. Load the module path php file for it if one exists.
    // TODO: Make this more efficient.
    $extensionInvoiceTemplateFile = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/modules/invoices/template.php")) {
            include_once ("./extensions/$ext_name/modules/invoices/template.php");
            $extensionInvoiceTemplateFile++;
        }
    }

    // Get the default module path php if their aren't any for enabled extensions.
    if (($extensionInvoiceTemplateFile == 0) && ($my_path = getCustomPath("invoices/template", 'module'))) {
        include_once ($my_path);
    }
    exit(0);
}

// Check for "api" module or a "xml" or "ajax" "page requeset" (aka view)
if (strstr($module, "api") or (strstr($view, "xml") or (strstr($view, "ajax")))) {
    $extensionXml = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/modules/$module/$view.php")) {
            include ("./extensions/$ext_name/modules/$module/$view.php");
            $extensionXml++;
        }
    }

    // Load default if none found for enabled extensions.
    if ($extensionXml == 0 && $my_path = getCustomPath("$module/$view", 'module')) {
        include ($my_path);
    }
    exit(0);
}

// **********************************************************
// Prep the page - load the header stuff - START
// **********************************************************

// To remove the js error due to multiple document.ready.function()
// in jquery.datePicker.js, jquery.autocomplete.conf.js and jquery.accordian.js
// without instances in manage pages - Ap.Muthu
// TODO: fix the javascript or move datapicker to extjs to fix this hack - not nice
$extension_jquery_files = "";
foreach ($ext_names as $ext_name) {
    if (file_exists("./extensions/$ext_name/include/jquery/$ext_name.jquery.ext.js")) {
        // @formatter:off
        $extension_jquery_files .= 
            '<script type="text/javascript" src="./extensions/' .
                     $ext_name . '/include/jquery/' .
                     $ext_name . '.jquery.ext.js">' .
            '</script>';
        // @formatter:on
    }
}

$smarty->assign("extension_jquery_files", $extension_jquery_files);

if (!in_array($module . "_" . $view, $early_exit)) {
    $extensionHeader = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/templates/default/header.tpl")) {
            $smarty->$smarty_output("../extensions/$ext_name/templates/default/header.tpl");
            $extensionHeader++;
        }
    }

    if ($extensionHeader == 0) {
        $my_path = getCustomPath('header');
        $smarty->$smarty_output($my_path);
    }
}
// **********************************************************
// Prep the page - load the header stuff - END
// **********************************************************

// **********************************************************
// Include php file for the requested page section - START
// **********************************************************
$extensionPHPFile = 0;
foreach ($ext_names as $ext_name) {
    if (file_exists("./extensions/$ext_name/modules/$module/$view.php")) {
        include_once ("./extensions/$ext_name/modules/$module/$view.php");
        $extensionPHPFile++;
    }
}

if (($extensionPHPFile == 0) && $my_path = getCustomPath("$module/$view", 'module')) {
    include ($my_path);
}
// **********************************************************
// Include php file for the requested page section - END
// **********************************************************

if ($module == "export" or $view == "export" or $module == "api") {
    exit(0);
}

// **********************************************************
// Post load javascript files - START
// NOTE: This is loaded after the .php file so that it can
// use script load for the .php file.
// **********************************************************
foreach ($ext_names as $ext_name) {
    if (file_exists("./extensions/$ext_name/include/jquery/$ext_name.post_load.jquery.ext.js.tpl")) {
        $smarty->$smarty_output("../extensions/$ext_name/include/jquery/$ext_name.post_load.jquery.ext.js.tpl");
    }
}

// NOTE: Don't load the default file if we are processing an authentication "auth" request.
// if ($extensionPostLoadJquery == 0 && $module != 'auth') {
if ($module != 'auth') {
    $smarty->$smarty_output("../include/jquery/post_load.jquery.ext.js.tpl");
}
// **********************************************************
// Post load javascript files - END
// **********************************************************

// **********************************************************
// Main: Custom menu - START
// **********************************************************
if ($menu == "true") {
    // Check for menu.tpl files for extensions. The content of these files is:
    //
    //      <!-- BEFORE:tax_rates -->
    //      <li>
    //        <a {if $pageActive == "custom_flags"} class="active"{/if} href="index.php?module=custom_flags&amp;view=manage">
    //          {$LANG.custom_flags_upper}
    //        </a>
    //      </li>
    //      {if $subPageActive == "custom_flags_view"}
    //        <li>
    //          <a class="active active_subpage" href="#">
    //            {$LANG.view}
    //          </a>
    //        </li>
    //      {/if}
    //      {if $subPageActive == "custom_flags_edit"}
    //        <li>
    //          <a class="active active_subpage" href="#">
    //            {$LANG.edit}
    //          </a>
    //        </li>
    //      {/if}
    //
    // This means the content of the extension's menu.tpl file will be inserted before the
    // following line in the default menu.tpl file:
    //
    // <!~- SECTION:tax_rates -->
    //
    // If no matching section is found, the file will NOT be instered.
    $my_path = getCustomPath('menu');
    $menutpl = $smarty->fetch($my_path);
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/templates/default/menu.tpl")) {
            $menu_extension = $smarty->fetch("../extensions/$ext_name/templates/default/menu.tpl");
            if (($pos = stripos($menu_extension, '<!-- BEFORE:')) !== false) {
                $pos += 12;
                $end = stripos($menu_extension, ' ', $pos);
                $len = $end - $pos;
                $section = substr($menu_extension, $pos, $len);
                $pattern = "<!-- SECTION:" . $section . " -->";
                if (($pos = stripos($menutpl, $pattern)) !== false) {
                    $menutpl = substr($menutpl, 0, $pos - 1) . $menu_extension . substr($menutpl, $pos);
                }
            }
        }
    }
    echo $menutpl;
}
// **********************************************************
// Main: Custom menu - END
// **********************************************************

// **********************************************************
// Main: Custom layout - START
// **********************************************************
if (!in_array($module . "_" . $view, $early_exit)) {
    $extensionMain = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/templates/default/main.tpl")) {
            $smarty->$smarty_output("../extensions/$ext_name/templates/default/main.tpl");
            $extensionMain++;
        }
    }

    if ($extensionMain == "0") {
        $smarty->$smarty_output(getCustomPath('main'));
    }
}
// **********************************************************
// Main: Custom layout - END
// **********************************************************

// **********************************************************
// Smarty template load - START
// **********************************************************
$extensionTemplates = 0;
$my_tpl_path = '';

$path = '';
// This change allows the index.tpl set up for report in an extension, to
// contain just the few lines needed for that report to show in the
// templates/default/reports/index.tpl file. For an example, look at the
// extension/past_due_report/templates/default/index.tpl file. Note the
// "data-section" attribute in the \<span\> tag. The value in this tag
// is what allows logic in the reports default index.tpl to know where
// to include the extension's index.tpl file.
$report_extensions = array();
$report_index = ($module == 'reports' && $view == 'index');

foreach ($ext_names as $ext_name) {
    if (file_exists("./extensions/$ext_name/templates/default/$module/$view.tpl")) {
        // If $report_index is true, the $path and $extensionTemplates are not set/incremented intentionally.
        // The logic runs through the normal report template logic with the index.tpl files for each one
        // of the extensions reports will be loaded for the section it goes in.
        if ($report_index) {
            $tpl_file = "./extensions/$ext_name/templates/default/$module/$view.tpl";
            $content = file_get_contents($tpl_file);
            if (($pos = strpos($content, 'data-section="{$LANG')) === false) {
                $section = $smarty->_tpl_vars['LANG']['other'];
            } else {
                $pos += 21;
                if (($end = strpos($content, '}"', $pos)) === false) {
                    $section = $smarty->_tpl_vars['LANG']['other'];
                } else {
                    $len = $end - $pos;
                    $lang_element = substr($content, $pos, $len);
                    $section = $smarty->_tpl_vars['LANG'][$lang_element];
                }
            }
            $section = strtolower($section);
            $vals = array("file" => "." . $tpl_file, "section" => $section, "added" => "0");
            $report_extensions[] = $vals;
        } else {
            $path = "../extensions/$ext_name/templates/default/$module/";
            $my_tpl_path = "../extensions/{$ext_name}/templates/default/$module/$view.tpl";
            $extensionTemplates++;
        }
    }
}

// TODO: if more than one extension has a template for the requested file, thats trouble :(
// This won't happen for reports given the changes with the addition of logic for
// "report_extensions" and "report_index".
if ($extensionTemplates == 0) {
    if ($my_tpl_path = getCustomPath("$module/$view")) {
        $path = dirname($my_tpl_path) . '/';
        $extensionTemplates++;
    }
}

// @formatter:off
$smarty->assign("report_extensions", $report_extensions);
$smarty->assign("report_index"     , $report_index);
$smarty->assign("path"             ,$path);
$smarty->$smarty_output($my_tpl_path);
// @formatter:on

// If no smarty template - add message
if ($extensionTemplates == 0) {
    error_log("NO TEMPLATE!!! for module[$module] view[$view]");
}
// **********************************************************
// Smarty template load - END
// **********************************************************

// **********************************************************
// Footer - START
// **********************************************************
if (!in_array($module . "_" . $view, $early_exit)) {
    $extensionFooter = 0;
    foreach ($ext_names as $ext_name) {
        if (file_exists("./extensions/$ext_name/templates/default/footer.tpl")) {
            $smarty->$smarty_output("../extensions/$ext_name/templates/default/footer.tpl");
            $extensionFooter++;
        }
    }

    if ($extensionFooter == 0) {
        $smarty->$smarty_output(getCustomPath('footer'));
    }
}
// **********************************************************
// Footer - END
// **********************************************************
