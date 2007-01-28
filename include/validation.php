<?php

function jsBegin(){
  echo "<script Language=\"JavaScript\">\n";
  echo "<!--\n";
}

function jsEnd(){
  echo "-->\n";
  echo "</script>\n";
}

function jsFormValidationBegin($sformname) {
  echo "function ".$sformname."_Validator(theForm)\n";
  echo "{\n";
}

function jsFormValidationEnd(){
  echo "  return (true);\n";
  echo "}\n";
}

function jsTextValidation($sName, $sLabel, $iMin, $iMax){
/*
  echo "  if (theForm.".$sName.".value != \"\")\n";
  echo "  {\n";
*/ 
 echo "  if (theForm.".$sName.".value.length < ".$iMin.")\n";
  echo "  {\n";
  echo "    alert(\"Please select a ".$sLabel."\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
  echo "  }\n";
  echo "  if (theForm.".$sName.".value.length > ".$iMax.")\n";
  echo "  {\n";
  echo "    alert(\"The ".$sLabel." field can only contain a maximum of ".$iMax." characters.\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
  echo "  }\n";
/*

 echo "  }\n";
*/
/*
  echo "  else if (theForm.".$sName.".value = \"\")\n";
  echo "    alert(\"Please select a ".$sLabel."\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
*/
}




function jsPaymentValidation($sName, $sLabel, $iMin, $iMax){
/*
  echo "  if (theForm.".$sName.".value != \"\")\n";^M
  echo "  {\n";
*/
 echo "  if (theForm.".$sName.".value < ".$iMin.")\n";
  echo "  {\n";
  echo "    alert(\"Please enter a valid ".$sLabel."\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
  echo "  }\n";
  echo "  if (theForm.".$sName.".value > ".$iMax.")\n";
  echo "  {\n";
  echo "    alert(\"The ".$sLabel." is not a valid.  Please make sure that there is in an actual invoice with this ".$sLabel.".\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
  echo "  }\n";
  echo "  if(!(/^\d+$/).test(theForm.".$sName.".value))\n";
  echo "  {\n";
  echo "    alert(\"Please Enter a valid ".$sLabel.". Decimal places or letters are not accepted in the ".$sLabel." field.\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
  echo "  }\n";
}


/*
^([1-9]|[1-9]\d|100)$
function jsValidateifNum($sName,$sLabel){^M
        echo "if(!(/[-+]?[0-9]*\.?[0-9]+/).test(theForm.".$sName.".value))\n";^M
        echo "{\n";^M
        echo "    alert(\"Please Enter a valid Number in the ".$sLabel." field.\");\n";^M
    echo "    theForm.".$sName.".focus();\n";^M
    echo "    return (false);\n";^M
        echo "}\n";^M
}^M
*/

function jsPreferenceValidation($sName, $sLabel, $iMin, $iMax){
/*
  echo "  if (theForm.".$sName.".value != \"\")\n";
  echo "  {\n";
*/ 
 echo "  if (theForm.".$sName.".value.length < ".$iMin.")\n";
  echo "  {\n";
  echo "    alert(\"Please select an ".$sLabel."\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
  echo "  }\n";
  echo "  if (theForm.".$sName.".value.length > ".$iMax.")\n";
  echo "  {\n";
  echo "    alert(\"The ".$sLabel." field can only contain a maximum of ".$iMax." characters.\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
  echo "  }\n";
/*
 echo "  }\n";
*/
/*
  echo "  else if (theForm.".$sName.".value = \"\")\n";
  echo "    alert(\"Please select a ".$sLabel."\");\n";
  echo "    theForm.".$sName.".focus();\n";
  echo "    return (false);\n";
*/
}





function jsValidateRequired($sName,$sLabel) {
    echo "  if (theForm.".$sName.".value == \"\")\n";
    echo "  {\n";
    echo "    alert(\"Please Enter A Value for the ".$sLabel." field.\");\n";
    echo "    theForm.".$sName.".focus();\n";
    echo "    return (false);\n";
    echo "  }\n";
}

function jsValidateifNumZero($sName,$sLabel){
	echo "if(theForm.".$sName.".value == '0')\n";
	echo "{\n";
	echo "    alert(\"".$sLabel." can't be zero.\");\n";
    echo "    theForm.".$sName.".focus();\n";
    echo "    return (false);\n";
	echo "}\n";
}

function jsValidateifNum($sName,$sLabel){
	echo "if(!(/[-+]?[0-9]*\.?[0-9]+/).test(theForm.".$sName.".value))\n";
	echo "{\n";
	echo "    alert(\"Please Enter a valid Number in the ".$sLabel." field.\");\n";
    echo "    theForm.".$sName.".focus();\n";
    echo "    return (false);\n";
	echo "}\n";
}

function jsValidateifEmail($sName){
	echo "if(!(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/).test(theForm.".$sName.".value))\n";
	echo "{\n";
	echo "    alert(\"Must Enter a valid Email here.\");\n";
    echo "    theForm.".$sName.".focus();\n";
    echo "    return (false);\n";
	echo "}\n";
}

function jsValidateRegEx($sName,$regex){
	echo "if(!".$regex.".test(theForm.".$sName.".value))\n";
	echo "{\n";
	echo "    alert(\"Invalid Input.\");\n";
    echo "    theForm.".$sName.".focus();\n";
    echo "    return (false);\n";
	echo "}\n";
}

?>
