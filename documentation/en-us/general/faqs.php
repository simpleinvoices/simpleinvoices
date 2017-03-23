<!DOCTYPE html>
<html>
<head>
  <title>SimpleInvoices - FAQs</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="../../../templates/default/css/main.css">
  <style>
    li, dl {margin-top:5px;margin-bottom:5px;}
    p {margin-top:5px;margin-bottom:5px;margin-left:0;}
  </style>
</head>
<body>
  <a id="top"></a>
  <h1 class="si_center">Frequently Asked Questions</h1>
  <div class="si_toolbar">
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Return To Previous Screen</a>
  </div>
  <div class="si_toolbar_top_left">
    <dl>
      <dt><a href="#faqs-what">What is SimpleInvoices?</a></dt>
      <dt><a href="#faqs-need">What do i need to start invoicings?</a></dt>
      <dt><a href="#faqs-how">How do I create invoices?</a></dt>
      <dt><a href="#faqs-types">What are the different invoice types?</a></dt>
      <dt><a href="#cron">Recurrence (aka cron)</a></dt>
    </dl>
    <br />
    <a id="faqs-what"><b>What is SimpleInvoices?</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
      <p>SimpleInvoices is a basic invoicing system designed with
      simplicity and functionality in mind. It caters to the needs of small organizations and home users. For more information please
      refer to the SimpleInvoices website <a href="http://www.simpleinvoices.org">http://www.simpleinvoices.org</a>.</p>
    <a id="faqs-need"><b>What do I need to start invoicing?</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
      <p>Once you've installed SimpleInvoices (refer to Installation
        documentation for more info), all you need to do is enter a <b>Biller</b> (normally your organization) and a <b>Customer</b>
        (the person your are invoicing). Once this set up is complete, you can create an invoice. However, if you are invoicing products
        sold, you also need to enter a <b>Product</b> record.</p>
    <a id="faqs-how"><b>How do I create invoices?</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
      <p>Creating invoices is easy. Once a <b>Biller</b> and <b>Customer</b>
        have been set up, select the <b>New Invoice</b> option in the <b>Money</b> tab menu. Fill in the fields in the new invoice
        form that will be displayed and click <b>Save Invoice</b>.</p>
    <a id="faqs-types"><b>What are the different invoice types?</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
      <p>In SimpleInvoices there are three types of invoices available:</p>
      <ul>
        <li><b>Total Invoice</b>: Think an invoice from a plumber that lists the actions and then has one price, associated
          taxes and a total.</li>
        <li><b>Itemized Invoice</b>: An invoice that list many different items. For example a grocery store invoice.</li>
        <li><b>Consulting Invoice</b>: An invoice that is similar to an <b>Intemised Invoice</b> except that with each line
          item there is a detailed description of the work performed/product sold. For example an invoice from an
          accounting or legal firm.</li>
      </ul>
    <a id="cron"><b>Recurrence</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <ul>
      <li>
        To use cron to generate recurrent invoices, prepare a file <b>si_cron</b> and place it in the <b>/etc/cron.d</b>
        folder in linux with the following contents:
        <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;<b>#SimpleInvoices recurrence - run each day at 1 AM</b>
        <br/>
        &nbsp;&nbsp;&nbsp;&nbsp;<b>0 1 * * * &#47;usr&#47;bin&#47;wget -q -O - http:&#47;&#47;localhost&#47;api-cron &gt;/dev/null 2&gt;&#38;1</b>
      </li>
      <li>
        Now run the command: <b>crontab&nbsp;/etc/cron.d/si_cron</b>
      </li>
    </ul>
    <ul>
      <li>Sample Apache configuration file in debian (/etc/apache2/sites-available/simpleinvoices)
<pre style="font-family:courier;">
ServerAdmin webmaster@localhost
ServerSignature Off
ServerTokens Prod

&lt;IfModule mpm_prefork_module&gt;
    StartServers          2
    MinSpareServers       1 
    MaxSpareServers       2
    MaxClients           50
    MaxRequestsPerChild 100
&lt;/IfModule&gt;

&lt;VirtualHost *:80&gt;

DocumentRoot /var/www/simpleinvoices

&lt;Directory /&gt;
    Options FollowSymLinks
    AllowOverride None
&lt;/Directory&gt;

&lt;Directory /var/www/simpleinvoices&gt;
    Options FollowSymLinks MultiViews
    AllowOverride None
    Order allow,deny
    allow from all
    DirectoryIndex index.php index.html

    RewriteBase /
    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^/?([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)?$ index.php?module=$1&amp;view=$2&amp;id=$3
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^/?([a-zA-Z0-9_]+)-([a-zA-Z0-9_]+)$ index.php?module=$1&amp;view=$2 [L]
&lt;/Directory&gt;

&lt;Directory /var/www/simpleinvoices/tmp&gt;
    Order Deny,Allow
    Deny from All
&lt;/Directory&gt;

&lt;Directory /var/www/simpleinvoices/config&gt;
    Order Deny,Allow
    Deny from All
&lt;/Directory&gt;

# Protect sensitive files.
&lt;FilesMatch "\.(htaccess|htpasswd|ini|phps|fla|psd|inc|po|sh|.*sql|log|tpl)$"&gt;
    Order allow,deny
    Deny from All
    Satisfy All
&lt;/FilesMatch&gt;

&lt;FilesMatch "\.(htm|html|css|js|php)$"&gt;
    AddDefaultCharset UTF-8
&lt;/FilesMatch&gt;

# Disable directory listings.
Options -Indexes

ErrorLog /var/log/apache2/error.log

# Possible values include: debug, info, notice, warn, error, crit,
# alert, emerg.
LogLevel warn

CustomLog /var/log/apache2/access.log combined

&lt;/VirtualHost&gt;
</pre>
      </li>
    </ul>
  </div>
</body>
</html>
