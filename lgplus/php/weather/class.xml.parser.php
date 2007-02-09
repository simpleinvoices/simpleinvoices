<?
//============================================================================
//============================================================================
// Script:    	PHP Class "xmlParser"
//============================================================================
// From:	http://ch2.php.net/xml
// Autor:	monte at NOT-SP-AM dot ohrt dot com
// Date:	14-Sep-2005 06:48 
// License/
// Usage:	Open Source / for free	
//============================================================================
// DESCRIPTION:
// This is a class for XML parsing with an URL input. It does:
// -  Get File from URL (XML/RSS-File)
// -  Parsing the file into array
// -  Return Array
//============================================================================
//============================================================================


class xmlParser{

// *** ----------------------------------------------------------------
// DECLARATION
var $xml_obj = null;
var $output = array();


// *** ----------------------------------------------------------------
// CONSTRUCTOR
function xmlParser(){

$this->xml_obj = xml_parser_create();
xml_set_object($this->xml_obj,$this);
xml_set_character_data_handler($this->xml_obj, 'dataHandler'); 
xml_set_element_handler($this->xml_obj, "startHandler", "endHandler");

} 


// *** ----------------------------------------------------------------
function parse($path){

if (!($fp = fopen($path, "r"))) {
die("Cannot open XML data file: $path");
return false;
}

while ($data = fread($fp, 4096)) {
if (!xml_parse($this->xml_obj, $data, feof($fp))) {
die(sprintf("XML error: %s at line %d",
xml_error_string(xml_get_error_code($this->xml_obj)),
xml_get_current_line_number($this->xml_obj)));
xml_parser_free($this->xml_obj);
}
}

return true;
}


// *** ----------------------------------------------------------------
function startHandler($parser, $name, $attribs){
$_content = array('name' => $name);
if(!empty($attribs))
$_content['attrs'] = $attribs;
array_push($this->output, $_content);
}

// *** ----------------------------------------------------------------
function dataHandler($parser, $data){
if(!empty($data)) {
$_output_idx = count($this->output) - 1;
$this->output[$_output_idx]['content'] = $data;
}
}

// *** ----------------------------------------------------------------
function endHandler($parser, $name){
if(count($this->output) > 1) {
$_data = array_pop($this->output);
$_output_idx = count($this->output) - 1;
$this->output[$_output_idx]['child'][] = $_data;
} 
}

// *** ----------------------------------------------------------------
function GetNodeByPath($path,$tree = false) {
if ($tree) {
$tree_to_search = $tree;
}
else {
$tree_to_search = $this->output;
}

if ($path == "") {
return null; 
}

$arrPath = explode('/',$path);

foreach($tree_to_search as $key => $val) {
if (gettype($val) == "array") {
$nodename = $val[name];

if ($nodename == $arrPath[0]) { 

if (count($arrPath) == 1) { 
return $val;
} 

array_shift($arrPath);

$new_path = implode($arrPath,"/");

return $this->GetNodeByPath($new_path,$val[child]);
}
}
}
}
} // class : end
?> 
