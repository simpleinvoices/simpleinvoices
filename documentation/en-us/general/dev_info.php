<!DOCTYPE html>
<html lang="en">
<head>
  <title>SimpleInvoices - Code Documents</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../../../templates/default/css/main.css">
  <link rel="stylesheet" href="../../../templates/default/css/info.css">
</head>
<body>
  <h1 class="si_center">Code Documents</h1>
  <div class="si_toolbar">
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Return To Previous Screen</a>
  </div>
  <br />
  <br />
  <div id="left" style="margin-left:10px;">
    <p><b>New one:</b></p>
<pre>
    ./NaturalDocs -i /var/www/simpleinvoices -o HTML /var/www/simpleinvoices-dev/branches/codedocs/
                  -p /var/www/simpleinvoices-dev/branches/codedocs/ -xi /var/www/simpleinvoices/pdf/
                  -xi /var/www/simpleinvoices/cache/ -xi /var/www/simpleinvoices/modules/reports/tmp/
                  -xi /var/www/simpleinvoices/modules/include/js/lgplus/ -img /var/www/simpleinvoices/images/
                  -s Default
</pre>
   <p><b>Old one:</b></p>
<pre>
    ./NaturalDocs -i /var/www/simpleinvoices -o HTML project/ -p project/ -xi /var/www/simpleinvoices/pdf/
                  -img /var/www/simpleinvoices/images/
                  -s Default
</pre>
    <br />
    <p><b>Lang check:</b></p>
<pre>
    cd lang
    perl lang_check.pl &gt; lang_check.html
</pre>
 	<p><b>Remove MS Dos line break:</b></p>
<pre>
    vi
    :1,$s/^M//
</pre>
    <br />
    <p><b>Perl:</b></p>
<pre>
    perl -pi -e '/s/OLD/NEW/g'

    Example: perl -pi -e '/s/\/src\//\/modules\//g'
</pre>
  </div>
</body>
</html>
<!--
<script type="text/javascript" src="modules/include/js/ibox.js"></script>
<link rel="stylesheet" href="modules/include/css/ibox.css" type="text/css"  media="screen"/>

rel="ibox&height=400"
rel='ibox&height=400'
-->
