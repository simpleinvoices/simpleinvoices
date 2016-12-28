<!DOCTYPE html>
<html lang="en">
<head>
<title>SimpleInvoices - Changelog</title>
<meta charset="UTF-8" />
<link rel="stylesheet" href="../../../templates/default/css/main.css">
<style>
li, dl {
  margin-top: 5px;
  margin-bottom: 5px;
}

p {
  margin-top: 5px;
  margin-bottom: 5px;
  margin-left: 25px;
}
</style>
</head>
<body>
  <h1 class="si_center">Code Documents</h1>
  <div class="si_toolbar">
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Return To Previous
      Screen</a>
  </div>
  <br />
  <br />
  <div id="left">
    New one ./NaturalDocs -i /var/www/simpleinvoices -o HTML
    /var/www/simpleinvoices-dev/branches/codedocs/ -p
    /var/www/simpleinvoices-dev/branches/codedocs/ -xi
    /var/www/simpleinvoices/pdf/ -xi /var/www/simpleinvoices/cache/ -xi
    /var/www/simpleinvoices/modules/reports/tmp/ -xi
    /var/www/simpleinvoices/modules/include/js/lgplus/ -img
    /var/www/simpleinvoices/images/ -s Default old one ./NaturalDocs -i
    /var/www/simpleinvoices -o HTML project/ -p project/ -xi
    /var/www/simpleinvoices/pdf/ -img /var/www/simpleinvoices/images/ -s
    Default
    <br />
    <br />
    <b>Lang check:</b>
    <br />
    <br />
    cd lang
    <br />
    <br />
    perl lang_check.pl &gt; lang_check.html <b>Stuff</b> Vi: remove MS Dos line break
    <br />
    :1,$s/^M//
    <br />
    <br />
    Perl:
    <br />
    perl -pi -e '/s/OLD/NEW/g'
    <br />
    ie. perl -pi -e '/s/\/src\//\/modules\//g'
    <br />
    <br />
    <b>SVN:</b>
  </div>
</body>
</html>
<!--
<script type="text/javascript" src="modules/include/js/ibox.js"></script>
<link rel="stylesheet" href="modules/include/css/ibox.css" type="text/css"  media="screen"/>

rel="ibox&height=400"
rel='ibox&height=400'
-->
