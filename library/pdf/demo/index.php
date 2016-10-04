<?php
// $Header: /cvsroot/html2ps/demo/index.php,v 1.5 2007/05/06 18:49:30 Konstantin Exp $
/* remarked out for testing at halfadot
   unknown if these included elements are needed
  require_once('config.inc.php');
  require_once('media.layout.inc.php');
  require_once('treebuilder.class.php');
  parse_config_file('./.html2ps.config');
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>

<script type="text/javascript">

String.prototype.trim = function() {
        var x=this;
        x=x.replace( /^\s*/, "" );
        x=x.replace( /\s*$/, "" );
        return x;
}

function validate() {
        var formobj = document.forms[0];
        var urlval = formobj.URL.value.trim();

        if ( !isValidURL( urlval ) ) {
                alert( 'Please input a valid URL.' );
                return false;
        }

        return true;
}

function isValidURL(url) {

        if ( url == null )
                return false;

// space extr
        var reg='^ *';
//protocol
        reg = reg+'(?:([Hh][Tt][Tt][Pp](?:[Ss]?))(?:\:\\/\\/))?';
//usrpwd
        reg = reg+'(?:(\\w+\\:\\w+)(?:\\@))?';
//domain
        reg = reg+'([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}|localhost|([Ww][Ww][Ww].|[a-zA-Z0-9].)?[a-zA-Z0-9\\-\\.]+\\.[a-zA-Z]{2,6})';
//port
        reg = reg+'(\\:\\d+)?';
//path
        reg = reg+'((?:\\/.*)*\\/?)?';
//filename
        reg = reg+'(.*?\\.(\\w{2,4}))?';
//qrystr
        reg = reg+'(\\?(?:[^\\#\\?]+)*)?';
//bkmrk
        reg = reg+'(\\#.*)?';
// space extr
        reg = reg+' *$';

        return url.match(new RegExp(reg, 'i'));
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>HTML2PS/PDF</title>

<!--CSS file may be preferred as external file-->

<style type="text/css">
/* standard tag styles */
body {
        color:#000;
        background-color:#fff;
        margin:10px;
        font-family:arial, helvetica, sans-serif;
        color:#000;
        font-size:12px;
        line-height:18px;
}
p {
  color:#000;
  font-size:12px;
  line-height:18px;
  margin-top:3px;
 }
 h1 {
        font-family:arial, helvetica, sans-serif;
        color:#669;
        font-size:27px;
        letter-spacing:-1px;
        margin-top:12px;
        margin-bottom:12px;
}
input,textarea,select {
        background-color:#eeeeee;
        border: 1px solid #045564;
}
img {
        border:0px;
}
fieldset {
        border: #26a solid 1px;
        margin-left:10px;
        padding-bottom:0px;
        padding-top:0px;
        margin-top:10px;
}
legend {
        background: #eee;
        border: #26a solid 1px;
        padding: 1px 10px;
        font-weight:bold;
}
/* special class/styles */
.submit {
        background-color:#669;
        color:#fff;
}
.nulinp {
        border:0px;
        background-color:#fff;
}
.hand {
        cursor: pointer;
}
/* forms formatting */
div.form-row {
        clear: both;
        padding-top: 5px;
}
div.form-row span.labl {
        float: left;
        width: 160px;
        text-align: right;
}
div.form-row span.formw {
        float: right;
        width: 300px;
        text-align: left;
}
div.spacer {
        clear: both;
}
div.comment {
  line-height: 1.1em;
}
</style>
</head>
<body>
<h1>html2ps/pdf demo</h1>

<p><a target="_blank" href="http://www.cs.wisc.edu/~ghost/" title="More about GhostView - [new window]">GhostView</a> can be used to read PostScript files, and <a target="_blank"  href="http://www.adobe.com/products/acrobat/readstep2.html" title="Download Adobe Acrobat - [new window]">Adobe Acrobat Reader</a> can be used to read PDF files.</p>
<p>See also:
<ul>
<li><a target="_blank" href="../help/index.html" title="Table of contents - [new window]">html2ps/pdf documentation</a></li>
<li><a href="systemcheck.php">System requirements checking script</a></li>
</ul>
</p>

<div style="width:500px;">
<form action="html2ps.php" method="get" style="margin-top:12px">
<fieldset>
<legend>&nbsp;Source&nbsp;</legend>

<div class="form-row">
<label class="hand" for="ur"><span class="labl">Single URL <input type="radio" class="nulinp" name="process_mode" value="single" checked="checked"/>: </span></label>
<span class="formw">
<input type="text" tabindex="1" id="ur" name="URL" size="30" value="www.google.com"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="ur"><span class="labl">Batch mode  <input type="radio" class="nulinp" name="process_mode" value="batch"/>: </span></label>
<span class="formw">
<input type="text" tabindex="1" name="batch[]" size="30" value="www.google.com"/><br/>
<input type="text" tabindex="1" name="batch[]" size="30" value="www.altavista.com"/><br/>
<input type="text" tabindex="1" name="batch[]" size="30" value="www.msn.com"/><br/>
<input type="text" tabindex="1" name="batch[]" size="30" value="www.msn.com"/><br/>
</span>
</div>

<div class="form-row">
<label class="hand" for="ur"><span class="labl">Use proxy: </span></label>
<span class="formw">
<input type="text" tabindex="1" name="proxy" size="30" value=""/><br/>
</span>
</div>

<div class="spacer"></div><br />
</fieldset>

<fieldset>
<legend>&nbsp;Format Requirements&nbsp;</legend>
<div class="form-row">
<label class="hand" for="pixel">
<span class="labl"><a href="../help/calling.html#pixels" title="Read description of 'pixels' parameter">Page width [pixels]</a></span></label>
<span class="formw">
<select name="pixels" id="pixel">
<option value="640">640</option>
<option value="800">800</option>
<option value="1024" selected="selected">1024</option>
<option value="1280">1280</option>
</select>
</span>
</div>

<div class="form-row">
<label class="hand" for="scalepoint"><span class="labl"><a href="../help/calling.html#scalepoints" title="Read description of 'scalepoints' parameter">Keep screen pixel/point ratio</a></span></label>
<span class="formw">
<input class="nulinp" type="checkbox" name="scalepoints" value="1" checked="checked" id="scalepoint"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="renderi"><span class="labl"><a href="../help/calling.html#renderimages" title="Read description of 'renderimages' parameter">Render images</a></span></label>
<span class="formw">
 <input class="nulinp" type="checkbox" name="renderimages" value="1" checked="checked" id="renderi"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="renderi"><span class="labl"><a href="../help/calling.html#renderlinks" title="Read description of 'renderlinks' parameter">Render hyperlinks</a></span></label>
<span class="formw">
 <input class="nulinp" type="checkbox" name="renderlinks" value="1" checked="checked" id="renderl"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="renderf"><span class="labl"><a href="../help/calling.html#renderforms" title="Read description of 'renderforms' parameter">Interactive forms</a></span></label>
<span class="formw">
<input class="nulinp" type="checkbox" name="renderforms" value="1" id="renderl"/><sup style="color: red">FPDF/PDFLIB <em>1.6</em> output only!</sup>
</span>
</div>

<div class="form-row">
<label class="hand" for="renderi"><span class="labl"><a href="../help/calling.html#renderfields" title="Read description of 'renderfields' parameter">Substitute special fields</a></span></label>
<span class="formw">
 <input class="nulinp" type="checkbox" name="renderfields" value="1" checked="checked" id="renderl"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="medi"><span class="labl"><a href="../help/calling.html#media" title="Read description of 'renderforms' parameter">Media</a></span></label>
<span class="formw">
<select name="media" id="medi">
<!--Can use php here to obtain predefined media types OR leave as is-->
<option value="Letter" selected="selected">Letter</option>
<option value="Legal">Legal</option>
<option value="Executive">Executive</option>
<option value="A0Oversize">A0Oversize</option>
<option value="A0">A0</option>
<option value="A1">A1</option>
<option value="A2">A2</option>
<option value="A3">A3</option>
<option value="A4">A4</option>
<option value="A5">A5</option>
<option value="B5">B5</option>
<option value="Folio">Folio</option>
<option value="A6">A6</option>
<option value="A7">A7</option>
<option value="A8">A8</option>
<option value="A9">A9</option>
<option value="A10">A10</option>
<option value="Screenshot640">Image 640&times;480</option>
<option value="Screenshot800">Image 800&times;600</option>
<option value="Screenshot1024">Image 1024&times;768</option>
<!--end php predefined media options if used-->
</select>
</span>
</div>

<div class="form-row">
<label class="hand" for="cssmedia"><span class="labl"><a href="../help/calling.html#renderforms" title="Read description of 'cssmedia' parameter">CSS Media</a></span></label>
<span class="formw">
<select name="cssmedia" id="cssmedia">
<option value="handheld">Handheld</option>
<option value="print">Print</option>
<option value="projection">Projection</option>
<option value="Screen" selected="selected">Screen</option>
<option value="tty">TTY</option>
<option value="tv">TV</option>
</select>
</span>
</div>

<div class="form-row">
<label class="hand" for="lm"><span class="labl"><a href="../help/calling.html#margins" title="Read description of 'leftmargin' parameter">Left margin:mm</a></span></label>
<span class="formw">
<input id="lm" type="text" size="3" name="leftmargin" value="30"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="rm"><span class="labl"><a href="../help/calling.html#margins" title="Read description of 'rightmargin' parameter">Right margin:mm</a></span></label>
<span class="formw">
<input id="rm" type="text" size="3" name="rightmargin" value="15"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="tm"><span class="labl"><a href="../help/calling.html#margins" title="Read description of 'topmargin' parameter">Top margin:mm</a></span></label>
<span class="formw">
<input id="tm" type="text" size="3" name="topmargin" value="15"/>
</span>
</div>
<div class="form-row">
<label class="hand" for="bm"><span class="labl"><a href="../help/calling.html#margins" title="Read description of 'bottommargin' parameter">Bottom margin:mm</a></span></label>
<span class="formw">
<input id="bm" type="text" size="3" name="bottommargin" value="15"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="automargins"><span class="labl">Auto-size vertical margins</span></label>
<span class="formw">
<input id="automargins" class="nulinp" type="checkbox" name="automargins" value="1"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="landsc"><span class="labl"><a href="../help/calling.html#landscape" title="Read description of 'landscape' parameter">Landscape</a></span></label>
<span class="formw">
<input id="landsc" class="nulinp" type="checkbox" name="landscape" value="1"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="encod"><span class="labl"><a href="../help/calling.html#encoding" title="Read description of 'encoding' parameter">Encoding</a></span></label>
<span class="formw">
<select id="encod" name="encoding">
<option value="" selected="selected">Autodetect</option>
<option value="utf-8">utf-8</option>
<option value="iso-8859-1">iso-8859-1</option>
<option value="iso-8859-2">iso-8859-2</option>
<option value="iso-8859-3">iso-8859-3</option>
<option value="iso-8859-4">iso-8859-4</option>
<option value="iso-8859-5">iso-8859-5</option>
<option value="iso-8859-6">iso-8859-6</option>
<option value="iso-8859-7">iso-8859-7</option>
<option value="iso-8859-9">iso-8859-9</option>
<option value="iso-8859-10">iso-8859-10</option>
<option value="iso-8859-11">iso-8859-11</option>
<option value="iso-8859-13">iso-8859-13</option>
<option value="iso-8859-14">iso-8859-14</option>
<option value="iso-8859-15">iso-8859-15</option>
<option value="windows-1250">windows-1250</option>
<option value="windows-1251">windows-1251</option>
<option value="windows-1252">windows-1252</option>
<option value="koi8-r">koi8-r</option>
</select>
</span>
</div>
<div class="spacer"></div><br />
</fieldset>

<fieldset>
<legend>&nbsp;Content generation&nbsp;</legend>
<div class="form-row">
<label class="hand" for="header">
<span class="labl"><a href="../help/calling.html#headerhtml">Header</a></span></label>
<span class="formw">
<textarea name="headerhtml" id="header">
</textarea>
</span>
</div>

<div class="form-row">
<label class="hand" for="footer">
<span class="labl"><a href="../help/calling.html#footerhtml">Footer</a></span></label>
<span class="formw">
<textarea name="footerhtml" id="footer">
</textarea>
</span>
</div>

<div class="form-row">
<label class="hand" for="watermark">
<span class="labl"><a href="../help/calling.html#watermarkhtml" title="Read description of 'watermarkhtml' parameter">Watermark</a></span></label>
<span class="formw">
<textarea name="watermarkhtml" id="watermark">
</textarea>
<div class="comment">
Note that watermarking is not supported by some output drivers; currently you may place &quot;watermarks&quot;
using FPDF and PDFLIB output only.
</div>
</span>
</div>

<div class="form-row">
<label class="hand" for="toc">
<span class="labl">Table of contents</span></label>
<span class="formw">
<input type="checkbox" value="1" name="toc"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="toc-location">
<span class="labl">Place TOC at:</span></label>
<span class="formw">
<select id="toc-location" name="toc-location">
<option value="before">first page</option>
<option value="after">last page</option>
<option value="placeholder">placeholder</option>
</select>
</span>
</div>

<div class="spacer"></div><br />
</fieldset>

<fieldset>
<legend>&nbsp;Debugging&nbsp;</legend>
<div class="form-row">
<label class="hand" for="pageborder"><span class="labl"><a href="../help/calling.html#pageborder" title="Read description of 'pageborder' parameter">Show page border</a></span></label>
<span class="formw">
<input id="pageborder" class="nulinp" type="checkbox" name="pageborder" value="1"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="debugbox"><span class="labl"><a href="../help/calling.html#debugbox" title="Read description of 'debugbox' parameter">Show content boxes</a></span></label>
<span class="formw">
<input id="debugbox" class="nulinp" type="checkbox" name="debugbox" value="1"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="debugnoclip"><span class="labl">Disable clipping</span></label>
<span class="formw">
<input id="debugnoclip" class="nulinp" type="checkbox" name="debugnoclip" value="1"/>
</span>
</div>

<div class="form-row">
<label class="hand" for="debugbox"><span class="labl">Use "smart" pagebreaking algorithm</span></label>
<span class="formw">
<input id="debugbox" class="nulinp" type="checkbox" name="smartpagebreak" checked="checked" value="1"/>
</span>
</div>

<div class="spacer"></div><br />
</fieldset>

<fieldset>
<legend>&nbsp;File Requirements&nbsp;</legend>
<div class="form-row">
<label class="hand" for="ps"><span class="labl">Output</span></label>
<span class="formw">
<input class="nulinp" type="radio" id="ps" name="method" value="fastps"/>PostScript&nbsp;&nbsp;
<select name="pslevel">
<option value="2">Level 2</option>
<option value="3" selected="selected">Level 3</option>
</select>
<label for="pdf">&nbsp;</label>
<br /><input class="nulinp" type="radio" id="pdf" name="method" value="pdflib" />PDF (PDFLIB)
<br /><input class="nulinp" type="radio" id="pdf" name="method" value="fpdf" checked="checked"/>PDF (FPDF)
<br /><input class="nulinp" type="radio" id="png" name="method" value="png"/>Image (PNG) <span style="color: red; vertical-align: super; font-size: smaller;">beta</span>
<!--<br /><input class="nulinp" type="radio" id="png" name="method" value="pcl"/>PCL <span style="color: red; vertical-align: super; font-size: smaller;">alpha</span>-->
</span>
</div>

<div class="form-row">
<label class="hand" for="ps"><span class="labl">PDF compatilbility level:</span></label>
<span class="formw">
<select name="pdfversion">
<option value="1.2">PDF 1.2 (NOT supported by PDFLIB!)</b></option>
<option value="1.3" selected="selected">PDF 1.3 (Acrobat Reader 4)</option>
<option value="1.4">PDF 1.4 (Acrobat Reader 5)</option>
<option value="1.5">PDF 1.5 (Acrobat Reader 6)</option>
</select>
<br/>
Note: not all output methods support all PDF compatibility levels! 
</span>
</div>

<div class="form-row">
<label class="hand" for="towher"><span class="labl">Destination</span></label>
<span class="formw">
<input class="nulinp" type="radio" id="towher" name="output" value="0" checked="checked" />Browser (PDF will be opened in browser, Postsript will be downloaded)&nbsp;<label for="towher1">&nbsp;&nbsp;&nbsp;</label>
<br /><input class="nulinp" type="radio" id="towher1" name="output" value="1" />Browser (download as file)
<br /><input class="nulinp" type="radio" id="towher2" name="output" value="2" />File on server
</span>
</div>

<div class="form-row">
<label class="hand" for="compr"><span class="labl">Filters</span></label>
<span class="formw">
<input class="nulinp" type="checkbox" id="compr" name="ps2pdf"   value="1"/>Convert Postscript to PDF<label for="compr1">&nbsp;&nbsp;</label><br />
<input class="nulinp" type="checkbox" id="compr" name="compress" value="1"/>
Compress output file using GZIP<label for="compr1">&nbsp;&nbsp;</label>
<div class="comment">
<span style="color: red;">Don't use this option with PDF output</span>, 
as Acrobat Reader will treat compressed file as damaged. 
</div>
<br />
</span>
</div>

<div class="form-row">
<label class="hand" for="transparency_workaround"><span class="labl">Hacks &amp; Workarounds</span></label>
<span class="formw">
<input class="nulinp" type="checkbox" id="transparency_workaround" name="transparency_workaround" value="1" />Use PS2PDF transparency problem workaround <br/>
<input class="nulinp" type="checkbox" id="imagequality_workaround" name="imagequality_workaround" value="1" />Use PS2PDF image quality problem workaround<br/>
(leave these options disabled if you have no problems with generated files)
</span>
</div>

<div class="form-row">
&nbsp;
<span class="formw">
<!-- <input class="submit" type="submit" value="Download File (debugging only)" /> -->
<input class="submit" type="reset"  name="reset"  value="Reset to defaults" />
&nbsp;
<input class="submit" type="submit" name="convert" onClick="javascript: return validate();" value="Convert File" />
</span>
</div>
<div class="spacer"></div><br />
</fieldset>
</form>
</div>

<p>html2ps is free and open-source for commercial and non-commercial use. <a target=_blank href="http://www.tufat.com/html2ps.php" title="More about html2ps">Read more about html2ps</a>.</p>

<p><a target=_blank href="https://www.paypal.com/xclick/business=g8z@yahoo.com&item_name=html2ps+donation&no_shipping=1&currency_code=USD">Donate to the html2ps project</a></p>

<hr/>
&copy; 2005&ndash;2006 Darren Gates, Konstantin Bournayev 

</body>
</html>