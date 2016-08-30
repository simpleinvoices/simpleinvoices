<?php
/*
 * Script: index.php
 * Main controller file for SimpleInvoices
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
require_once "./include/init_pre.php";

// @formatter:off
$module = isset($_GET['module']) ? filenameEscape($_GET['module']) : null;
$view   = isset($_GET['view'])   ? filenameEscape($_GET['view'])   : null;
$action = isset($_GET['case'])   ? filenameEscape($_GET['case'])   : null;

// globals set in the init.php logic
$databaseBuilt     = false;
$databasePopulated = false;
// @formatter:on

// Will be set in the following init.php call to extensions that are enabled.
$ext_names = array();
$help_image_path = "./images/common/";

// Note: include/functions.php and include/sql_queries.php loaded by this include.
require_once "./include/init.php";
global $smarty,
       $smarty_output,
       $menu,
       $LANG,
       $siUrl,
       $config,
       $auth_session,
       $early_exit;

foreach ($ext_names as $ext_name) {
    if (file_exists("./extensions/$ext_name/include/init.php")) {
        require_once ("./extensions/$ext_name/include/init.php");
    }
}

$smarty->assign("help_image_path", $help_image_path);

// **********************************************************
// The include configs and requirements stuff section - END
// **********************************************************
// @formatter:off
$smarty->assign("ext_names", $ext_names);
$smarty->assign("config"   , $config);
$smarty->assign("module"   , $module);
$smarty->assign("view"     , $view);
$smarty->assign("siUrl"    , $siUrl);
$smarty->assign("LANG"     , $LANG);
$smarty->assign("enabled"  , array($LANG['disabled'],$LANG['enabled']));
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
if (strstr($module, "api") || (strstr($view, "xml") || (strstr($view, "ajax")))) {
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

// Load any hooks that are defined for extensions
foreach ($ext_names as $ext_name) {
    if (file_exists("./extensions/$ext_name/templates/default/hooks.tpl")) {
        $smarty->$smarty_output("../extensions/$ext_name/templates/default/hooks.tpl");
    }
}
// Load standard hooks file. Note that any module hooks loaded will not be
// impacted by loading this file.
$smarty->$smarty_output("../custom/hooks.tpl");

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
// This change allows template files modified with necessary logic, to
// include sections defined in extentions. The benefit is that multiple
// extensions that affect the same tpl file can be written without being
// concerned that the tpl file for one extension will overwrite the tpl
// for another extension. For an example, look at the file,
// "extension/past_due_report/templates/default/index.tpl." Note the
// "data-section" attribute in the <span> tag. The value in this tag
// is what allows logic in the reports default index.tpl to know where
// to include the past_due_report extension's index.tpl file.
$extension_php_insert_files = array();
if ($extension_php_insert_files) {} // Show variable as used.

$perform_extension_php_insertions = (($module == 'system_defaults' && $view == 'edit'));
$extensionPhpFile = 0;
foreach ($ext_names as $ext_name) {
    $phpfile = "./extensions/$ext_name/modules/$module/$view.php";
    if (file_exists($phpfile)) {
        // If $perform_extension_php_insertions is true, then the extension php
        // file content is to be included in the standard php file. Otherwise,
        // the file is a replacement for the standard php file.
        if ($perform_extension_php_insertions) {
            // @formatter:off
            $vals = array("file"   => $phpfile,
                          "module" => $module,
                          "view"   => $view);
            $extension_php_insert_files[$ext_name] = $vals;
            // @formatter:on
        } else {
            include $phpfile;
            $extensionPhpFile++;
        }
    }
}
if ($extensionPhpFile == 0 && ($my_path = getCustomPath("$module/$view", 'module'))) {
    include $my_path;
}
// **********************************************************
// Include php file for the requested page section - END
// **********************************************************

if ($module == "export" || $view == "export" || $module == "api") {
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
    // <!-- BEFORE:tax_rates -->
    // <li>
    // <a {if $pageActive == "custom_flags"} class="active"{/if} href="index.php?module=custom_flags&amp;view=manage">
    // {$LANG.custom_flags_upper}
    // </a>
    // </li>
    // {if $subPageActive == "custom_flags_view"}
    // <li>
    // <a class="active active_subpage" href="#">
    // {$LANG.view}
    // </a>
    // </li>
    // {/if}
    // {if $subPageActive == "custom_flags_edit"}
    // <li>
    // <a class="active active_subpage" href="#">
    // {$LANG.edit}
    // </a>
    // </li>
    // {/if}
    //
    // This means the content of the extension's menu.tpl file will be inserted before the
    // following line in the default menu.tpl file:
    //
    // <!~- SECTION:tax_rates -->
    //
    // If no matching section is found, the file will NOT be instered.
    $my_path = getCustomPath('menu');
<<<<<<< HEAD
    $menutpl = $smarty->fetch($my_path);	// read original menu
	$menuSections = explode ("<!-- SECTION:", $menutpl);	// divide menu tpl into sections
	$newMenuHead = array_shift ($menuSections);	// remove head and store for later
	$newMenu = array();	// create new array
	foreach ($menuSections as $menuSection) {	// each section of menu tpl 
		$matches = explode (" -->", $menuSection, 2);
		if (strtoupper($matches[0]) != "END")	// ignore section:end
			$newMenu[$matches[0]] = $matches[1];	// put each section into new array
	}
    foreach ($ext_names as $ext_name) {	// each extension
		if (file_exists("./extensions/$ext_name/templates/default/menu.tpl"))
		{
			$menu_extension = $smarty->fetch("../extensions/$ext_name/templates/default/menu.tpl");	// grab ext menu.tpl
			$exploded = explode ("<!-- SECTION:", $menu_extension);	// separate this ext menu tpl into sections
			$menuPart0 = array_shift ($exploded);	// store & erase first element
			foreach ($exploded as $part) {	// each section to be inserted
				$temp = explode(':', $part);
				$menuAction = $temp[0];	// <!-- section:<THIS>:~ -->~
				$temp = explode (' -->', $temp[1]);
				$sectionName = $temp[0];	// <!-- section:<action>:<THIS> -->~
				$sectionContents = $temp[1];	// <!-- section:<action>:<name> --><THIS>
				switch (strtoupper ($menuAction)) {
					case 'AFTER':
						$newMenu[$sectionName] = $newMenu[$sectionName] . $sectionContents;	// append
						break;
					case 'NEWHEAD':
						$newMenuHead = $sectionContents;	// replace head
						break;
					case 'BEFORE':
						$newMenu[$sectionName] = $sectionContents . $newMenu[$sectionName];	// pre-pend
						break;
					case 'REPLACE':
						$newMenu[$sectionName] = $sectionContents;	// replace
						break;
					case 'END':
						break;	// ignore
				}
			}
		}
	}
	$menutpl = $newMenuHead;	// begin with new menu head
	foreach ($menuSections as $menuSection) {	// each section of menu tpl
		preg_match ("/^(\w+) /", $menuSection, $matches);
		if (!empty($newMenu[$matches[1]]))
			$menutpl.= "<!-- SECTION:" . $matches[1] . " -->" . $newMenu[$matches[1]];// . "<!-- SECTION:END -->";
		else $menutpl.= "<!-- SECTION:" . $menuSection;
	}
=======
    $menutpl = $smarty->fetch($my_path);
    $lines = array();
    $sections = array();
    Funcs::menuSections($menutpl, $lines, $sections);
    $menutpl = Funcs::mergeMenuSections($ext_names, $lines, $sections);
>>>>>>> a213cf0e2a644b6db8702effedf566b14ec250a5
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
// For extensions with a report, this logic allows them to be inserted into the
// the report menu (index.tpl) without having to replicate the content of that
// file. There two ways to insert content; either as a new menu section or as
// an appendage to an existing section. There are examples of each of these.
// Refer to the "expense" extension report index.tpl file for insertion of
// a new menu section. Note the "data-section" with the "BEFORE" entry. This
// tells the program to insert the menu before the menu section with the
// "$LANG.xxxxx" value that appears following the "BEFORE" statement. To
// append to an existing menu section, refer to the report index.tpl file
// for the "past_due_report" extension. Note the "data-section" attribute
// in the "<span ...>" tag. This tells the program to insert the report
// menu item at the end of the section with "$LANG.xxxxx" value assigned
// to the attribute.
$extension_insertion_files = array();
$perform_extension_insertions = (($module == 'reports' && $view == 'index') ||
                 ($module == 'system_defaults' && $view == 'manage'));

foreach ($ext_names as $ext_name) {
    $tpl_file = "./extensions/$ext_name/templates/default/$module/$view.tpl";
    if (file_exists($tpl_file)) {
        // If $perform_extension_insertions is true, the $path and
        // $extensionTemplates are not set/incremented intentionally.
        // The logic runs through the normal report template logic
        // with the index.tpl files for each one of the extensions
        // reports will be loaded for the section it goes in.
        if ($perform_extension_insertions) {
            $content = file_get_contents($tpl_file);
            $type = "";
            if (($pos = strpos($content, 'data-section="')) === false) {
                $section = $smarty->_tpl_vars['LANG']['other'];
            } else {
                $pos += 14;
                $str = substr($content, $pos);
                if (preg_match('/^BEFORE \{\$LANG\./', $str)) {
                    $pos += 14;
                    $type = "BEFORE ";
                } else {
                    $pos += 7;
                    $type = "";
                }
                $end = strpos($content, '}', $pos);
                $len = $end - $pos;
                $lang_element = substr($content, $pos, $len);
                $section = $smarty->_tpl_vars['LANG'][$lang_element];
            }
            // @formatter:off
            $vals = array("file"    => "." . $tpl_file,
                          "module"  => $module,
                          "section" => $type . $section);
            $extension_insertion_files[] = $vals;
            // @formatter:on
        } else {
            $path = "../extensions/$ext_name/templates/default/$module/";
            $my_tpl_path = "." . $tpl_file;
            $extensionTemplates++;
        }
    }
}

// TODO: if more than one extension has a template for the requested file, thats trouble :(
// This won't happen for reports, standard menu.tpl and system_defaults menu.tpl given
// changes implimented in this file for them. Similar changes should be implimented for
// other templates as needed.
if ($extensionTemplates == 0) {
    if ($my_tpl_path = getCustomPath("$module/$view")) {
        $path = dirname($my_tpl_path) . '/';
        $extensionTemplates++;
    }
}
// @formatter:off
$smarty->assign("extension_insertion_files"   , $extension_insertion_files);
$smarty->assign("perform_extension_insertions", $perform_extension_insertions);
$smarty->assign("path"                        , $path);
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
