<!DOCTYPE html>
<html>
<head>
  <title>SimpleInvoices - Read Me</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link rel="stylesheet" href="../../../templates/default/css/main.css">
  <style>
    li, dl {margin-top:5px;margin-bottom:5px;}
    p {margin-top:5px;margin-bottom:5px;margin-left:25px;}
  </style>
</head>
<body>
  <a id="top"></a>
  <h1 class="si_center">ReadMe</h1>
  <div class="si_toolbar">
    <a href="<?php echo $_SERVER['HTTP_REFERER']; ?>">Return To Previous Screen</a>
  </div>
  <div class="si_toolbar_top_left">
    <dl>
      <dt><a href="#installation">Installation</a></dt>
      <dd><a href="#requirements">Requirements</a></dd>
      <dd><a href="#recommended">Recommended</a></dd>
      <dd><a href="#notinstalled">Above Not Installed</a></dd>
      <dd><a href="#siinstallation">SimpleInvoices Installation</a></dd>
      <dd><a href="#sowhatnow">Installation completed. So what now?</a></dd>
      <dt><a href="#backup">Database backup</a></dt>
      <dt><a href="#login">Enabdtng the login system</a></dt>
      <dt><a href="#upgrading">Upgrading</a></dt>
      <dt><a href="#prepare">Preparing SimpleInvoices for use</a></dt>
      <dd><a href="#addbiller">Add Biller</a></dd>
      <dd><a href="#addcustomerr">Add Customer</a></dd>
      <dd><a href="#addproduct">Add Product</a></dd>
      <dd><a href="#settaxrate">Set Tax Rate</a></dd>
      <dd><a href="#setpreferences">Set Invoice Preferences</a></dd>
      <dt><a href="#use">Using SimpleInvoices</a></dt>
      <dd><a href="#logos">Biller Logos</a></dd>
      <dd><a href="#templates">Invoice Templates</a></dd>
      <dd><a href="#defaults">System Defaults</a></dd>
      <dd><a href="#export">Export to PDF, Spreedshet or Document</a></dd>
      <dt><a href="#faqs">Frequently Asked Questions (FAQs)</a></dt>
      <dd><a href="#faqs-what">What is SimpleInvoices?</a></dd>
      <dd><a href="#faqs-need">What do i need to start invoicings?</a></dd>
      <dd><a href="#faqs-how">How do I create invoices?</a></dd>
      <dd><a href="#faqs-types">What are the different invoice types?</a></dd>
    </dl>
    <br />
    <a id="installation"><b>Installation</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <ul>
      <li><a id="requirements"><b>Requirements</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>Apache 2.4x</li>
          <li>MySQL 5.6x or compatible DBMS (ie: MariaDB).</li>
          <li>PHP 5.6x
            <ul>
              <li>For PDF export to work your PHP needs:
                <ul>
                  <li>GD2 support</li>
                  <li>php.ini needs to be edited with a max memory of <b>24M</b> or higher.</li>
                </ul>
              </li>
              <li>For Reports to work your PHP needs:
                <ul><li>xsl support (in PHP5)</ul>
              </li>
            </ul>
          </li>
          <li>The following directories must be setup and writeable by the webserver user:
<pre style="margin-left:10px;font-family:courier;font-weight:bold;font-size:1em;">
  tmp
  tmp/cache
  tmp/database_backups
  tmp/log
  tmp/template_c
</pre>
          </li>
        </ul>
      </li>
      <li><a id="recommended"><b>Recommended</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>phpMyAdmin <a href="http://phpmyadmin.sf.net">http://phpmyadmin.sf.net</a></li>
        </ul>
      </li>
      <li><a id="notinstalled"><b>Above not installed?</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>Windows: Install WampServer from <a
            href="http://www.wampserver.com/en">http://www.wampserver.com/en</a></li>
          <li>Mac: Install MAMP5 from <a
            href="http://www.mamp.info/">http://www.mamp.info/</a>.
          </li>
          <li>Linux: Use the distribution provided with your OS.</li>
        </ul>
      </li>
      <li><a id="siinstallation"><b>SimpleInvoices Installation</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>Download the <b>.zip</b> file for the current version of SimpleInvoices from GitHub.</li>
          <li>In the <i>document root</i> directory of your webserver, extract the content of the <b>.zip</b> file. <b>Note</b>:
            If you want to use your own directory name for SimpleInvoices in your <i>document root</i> directory, create
            the directory before extracting the files from the <b>.zip</b> file. Drill down one level in the <b>.zip</b>
            file, select all the directories and files and extract them into the directory you made.</li>
          <li>Create a database on your database server. You will probably use <i>phpMyAdmin</i> to do this or a
            <i>cPanel</i> utility. Be sure to assign a user and password with full access to your database.
          </li>
          <li>In the <i>config/</i> directory, copy the <b>config.php</b> file to makea file named,
            <b>custom.config.php</b>. Use this for your configuation so that future updates will not load
            over your setup.</li>
          <li>Modify the folloing parameters in your <b>custom.config.php</b>:
<pre style="margin-left:10px;font-family:courier;font-weight:bold;font-size:1em;">
  database.adapter         = pdo_mysql
  database.utf8            = true
  database.params.host     = localhost
  database.params.username = <i>yourdbusername</i>
  database.params.password = '<i>yourdbpassword</i>'
  database.params.dbname   = <i>yourdbname</i>
  database.params.port     = 3306 ; Default is 3306
</pre>
          </li>
          <li>Now access SimpleInvoices instance from your web browser. For example, if you stored your files in a
            directory named <b>simpleinvoices</b> and the <b>database.host</b> setting is <b>localhost</b> enter
            the address <b>localhost/simpleinvoices</b> to start the database setup process. Follow the instructions
            displayed to populate the database with the necessary information.
          </li>
        </ul>
      </li>
      <li><a id="sowhatnow"><b>Installation completed. So what now?</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>To generate and export PDF files, the PHP <i>gd2</i> extension must be enabled so PHP can create and
            manipulate image files in a variety of different image formats. The extension is enabled by un-commenting
            (removing the semi-colon) from the front of the <i>extension=php_gd2.dll</i> line in the <b>php.ini</b> file.
            <p>
            To verify that <i>gd2</i> has been enabled, use the <b>phpinfo.php</b> file in the SimpleInvoices root
            directory. Change the <b>$secure</b> setting to <b>false</b> and access it from your web browser.
            Example: <b>localhost/simpleinvoices/phpinfo.php</b>.
            </p>
            <p>
            Scroll down the list that prints in your browser and look for the <b>gd</b> extension. Extensions are listed
            alphabetically so it should be easy to find. If it isn't there and you have removed the comment character from the
            extension line in the php.ini file, you probably need to restart your web server. Do this and check again.
            </p>
            <p>
            Remember to change the <b>$secure</b> line in the <b>phpinfo.php</b> file back to <b>true</b> after verifying
            that the extension is enabled. This secures the file from being accessed by others to view your set up.
            </p>
          </li>
          <li>Next, test your ability to generate <b>PDF</b> files by using the pdf test page. Type the address,
            <b>localhost/simpleinvoices/library/pdf/demo</b>, in your browser.
            <br/>
            The <i>Source</i> area the 'Single URL' is set to www.google.com by default. If you are connected to the
            internet just leave this as is but if your currently not connected to the internet change this to a
            valid webpage on your system ie. <i>http://localhost/simpleinvoices</i> and scroll to the bottom of the
            page and click the 'Convert&nbsp;File' button. If all goes well a pdf of www.google.com homepage
            (or the url you entered) will be created and displayed in your pdf viewer.
            <br/>
            If a pdf wasn't created and an error similar to the following occurred, you will have to alter your
            <b>php.ini</b> file configuration.
            <br />
<pre style="margin-left:10px;font-family:courier;font-weight:bold;font-size:1em;">
  Fatal error: Allowed memory size of 8388608 bytes exhausted (tried to allocate 4864 bytes)
  in /var/www/simpleinvoices/pdf/filter.data.html2xhtml.class.php on line 8
</pre>
            In the <b>php.ini</b> file, local the <b>memory_limit</b> line and change to the setting to <b>24M</b>
            or greater. Restart your webserver and test again.
            <br />
          </li>
        </ul>
      </li>
      <li>Everything has been setup and configured now, Simple Invoice is ready to be used</li>
      <li>Open your Internet browser and go to http://localhost/simpleinvoices and user SimpleInvoices as you wish</li>
      <li>Installation is finished</li>
    </ul>
    <a id="backup"><b>SimpleInvoices database backup</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <p>SimpleInvoices provides a database backup feature to preserve current content for protection from
      corruption and to preserve data prior to update installations.</p>
    <a id="login"><b>Enabling the login/authentication system</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <ul>
      <li>SimpleInvoices has a login system which can allow you to protect your SimpleInvoices with a username and password login
        system. By default this is turned off.</li>
      <li>To turn on the login system in SimpleInvoices all you have to do is:
        <ul>
          <li>Open the <b>config/custom.config.php</b> file and change the <b>$authentication.enabled</b>
            setting to <b>true</b>.</li>
          <li>Done :-)  Now just open up SimpleInvoices, login and make invoices.</li>
        </ul>
      </li>
    </ul>
    <a id="upgrading"><b>Upgrading SimpleInvoices from one version to another</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <ul>
      <li>To upgrade SimpleInvoices from one version to another, the first step is to download the updated version of
        SimpleInvoices from <i>GitHub</i></li>
      <li>If you are using <i>config/config.php</i>, copy it and make a file named, <i>config/custom.config.php</i>. This will be
        used automatically and preserves your settings when the new SimpleInvoices version is installed.</li>
      <li>Next, extract the contents of the downloaded <b>.zip</b> file into the <i>simpleinvoices</i> directory. Be sure to
        drill down to the <b>.zip</b> file to the directory that contains the <b>config</b> directory and select all the content
        to extract into your <i>simpleinvoices</i> directory.</li>
      <li>Test your setup in the web browser (usually by entering <i>http://localhost/simpleinvoices</i>). Modifications to the
        database are automatically applied the first time you access the updated implementation.</li> 
    </ul>
    <a id="prepare"><b>Preparing SimpleInvoices for use</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <p style="margin-left:0;">The first steps in using SimpleInvoices is to setup the base information, that is billers, customers, products, tax
      rates, and invoice preferences</p>
    <ul>
      <li><a id="addbiller"><b>Add Biller</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>A biller is the name and details of the person creating the invoice, ie you or your company</li>
          <li>Open up SimpleInvoices in your browser (normally http://localhost/simpleinvoice)</li>
          <li>In the main page click on the Insert Biller button</li>
          <li>Once in the Insert Biller screen fill in the required fileds and click the Insert Biller button</li>
          <li>Now when you create an Invoice you will be able to select this biller</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li><a id="addcustomerr"><b>Add Customer</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>In the main page click on the Insert Customerr button</li>
          <li>Once in the Insert Customer screen fill in the required fileds and click the Insert Customer button</li>
          <li>Now when you create an Invoice you will be able to select this customer</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li><a id="addproduct"><b>Add Product</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>A product can be any item that you wish to appear in the Itemised Invoice. It can be anything you wish to sell
            and invoice - physical item ie. light bulbs or services such as an accounting service charge per hour/PC repairs/etc...</li>
          <li>Note that products are only available when you create an Itemised Invoice</li>
          <li>In the main page click on the Insert Product button</li>
          <li>Once in the Insert Product screen fill in the required fileds and click the Insert Product button</li>
          <li>Now when you create an Itemised Invoice you will be able to select this product</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li><a id="settaxrate"><b>Set Tax Rate</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>In some countries (USA, Australia, England, New Zealand, etc.), there are taxes on sales; normally called
            Sales Tax, GST(goods and services tax), or VAT (value added tax). SimpleInvoices has the ability to setup
            and define tax rates any and all of these various tax types.</li>
          <li>To view the default tax rates select Manage Tax Rates from the Option menu.</li>
          <li>This will now display all the available tax rates in SimpleInvoices</li>
          <li>To edit an existing tax rate select the edit button next to the tax rate and in the edit screen update it with
            the new information and click the Sav Tax Rate button</li>
          <li>To add a new tax rate select from the Option menu Insert New Tax Rate</li>
          <li>Fill in the required fields and click the Insert Tax Rate button</li>
          <li>The new tax rate will now be available when creating a new invoice</li>
        </ul>
      </li>
    </ul>
    <ul>
      <li><a id="setpreferences"><b>Set Invoice Preferences</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <p style="margin-left:0;">The Invoice Preferences is where you can set the various preferences of your invoices. Available preferences are:</p>
        <ul>
          <li><b>Description</b>: This is the name of the set of preferneces</li>
          <li><b>Currency sign</b>: This is the curreny symbol that will be used</li>
          <li><b>Invoice heading</b>: This is the heading of the invoice</li>
          <li><b>Invoice wording</b>: This is the invoice wording - ie if you enter Quote - in the Manage Invoices screen
            it'll say Quote in the invoice type field and through that invoice it'll say quote instead of invoice ie. Quote
            ID, Quote Date, etc..</li>
          <li><b>Invoice detail heading</b>: This is what will appear as the heading of the footer/details section of the
            invoice</li>
          <li><b>Invoice detail line</b>: This is the text that appear under the details/footer heading. Normally used to
            define payment termns etc.</li>
          <li><b>Invoice payment method</b>: This is the where you specify how you would like the customer to pay you,
            ie Cheque/money order/electronic funds transfer/etc.</li>
          <li><b>Invoice payment line1 name</b>: This is where you can specify the payment line 1 name ie. Account name</li>
          <li><b>Invoice payment line1 value</b>: This is where you can specify the payment line 1 value ie. The name of
            your back account</li>
          <li><b>Invoice payment line2 name</b>: This is where you can specify the payment line 2 name ie. Account number</li>
          <li><b>Invoice payment line2 value</b>: This is where you can specify the payment line 2 value ie. The name of
            your back account</li>
        </ul>
      </li>
    </ul>
    <a id="use"><b>Using SimpleInvoices</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <ul>
      <li><b>The basics</b>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>Now that SimpleInvoices has been installed and setup will all the required information, you can start to do some
            invoices :)</li>
          <li>Open SimpleInvoices in your browser (http://localhost/simpleinvoices)</li>
          <li>To create an invoice, in the main page, click on the style of invoice you wish to create, <b>Invoice - Total</b> or
            <b>Invoice - Itemised</b>.
            <ul>
              <li><b>Total Invoice</b>: An invoice that lists the actions and then displays a single price and any associated
                tax. For example, an invoice from a plumber.</li>
              <li><b>Itemised Invoice</b>: An invoice that lists many different items in the same invoice - think a grocery
                store invoice</li>
              <li><b>Consulting Invoice</b>: An invoice that is similar to the <b>Intemised Invoice</b> except that each line item
                has a detail description of the work performed, product sold, etc. For example an accounting or legal firm&#39;s invoice.</li>
            </ul>
          </li>
          <li>Once in the create invoice screen, select a biller and a customer.</li>
          <li>If creating a <b>Total Invoice</b>, enter the description of the invoice, the total of the invoice, the tax rate, the
            invoice preference and click the <b>Submit</b> button.</li>
          <li>If creating an <b>Itemised Invoice</b>, enter the quantity of the item, select the product, the tax rate, the
            invoice preference and click the <b>Submit</b> button.</li>
          <li>Your invoice will now be created and you'll be presented with a <b>Quick View</b> of the invoice.</li>
          <li><b>Quick View</b>: A view of the invoice while your still in SimpleInvoices. It allows you to see the invoice
            and select actions to generate <b>Email</b> with the invoice attached, <b>Print View</b> to print the formatted invoice
            or to generate formatted output to a file of types <b>PDF</b>, <b>Spreedsheet</b> or <b>Document</b>.</li>
          <li>If you select the <b>Print View</b>, it brings up a the invoice formatted for printing. Use the <i>right click</i> to
            print it and use the browser&#39;s previous screen (back arrow) button to return to the previous screen.</li>
          <li>Enjoy :-)</li>
        </ul>
      </li>
      <li><a id="logos"><b>Biller Logos</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>SimpleInvoices allows for each biller to have their own logo displayed in the print formatted invoices.</li>
          <li>To add a logo to a biller the first step is to upload your billers logo into the logo directory into SimpleInvoices
            directory on your system</li>
          <li>Once the logo has been uploaded the last step is to go into the <b>Manage Billers</b> page, edit the biller in question,
            select the logo file to use from the <b>Logo file</b> drop down list and <b>Save</b> the change.</li>
          <li>Now when you generate a print image (Print view, PDF, spreedsheet, etc.), the logo assigned to the biller will be used.</li>
        </ul>
      </li>
      <li><a id="templates"><b>Invoice Templates</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>An Invoice Template is the template that SimpleInvoices will use to create the Print Preview of the invoice</li>
          <li>To choose which Invoice Template your SimpleInvoices will use go to the Options menu and select System Defaults</li>
          <li>In the System Default page to tell which Invoice Template your system is using look at the 'Default invoice template:'
            field and that will be the default thats currently used</li>
          <li>If you wish to change what Invoice Template is the default for your SimpleInvoices click the edit button next to
            'Default invoice template:' and from the drop down menu select another template to use and click save</li>
          <li>Now when you select <b>Print View</b> of an invoice it will use this new Invoice Template</li>
        </ul>
      </li>
      <li><a id="defaults"><b>System Defaults</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <p style="margin-left:0;">In SimpleInvoices it&#39;s possible to setup defaults for various values. The following default values are maintained
           in the <i>System Defaults</i> page; accessed from the <i>System Prefernces</i> menu in the <i>Settings</i> tab:</p>
        <ul>
          <li><b>Default Biller</b>: Defines biller to use when creating a new invoice. It can be changed before saving the invoice.</li>
          <li><b>Default Customer</b>: Defines customer to use when creating a new invoice. It can be changed before saving the invoice.</li>
          <li><b>Default Tax Rate</b>: Defines tax rate to use when creating a new invoice. It can be changed before saving the invoice.</li>
          <li><b>Default Invoice Preferences</b>: Defines invoice preference (type) to use when creating a new invoice. It can be
            changed before saving the invoice.</li>
          <li><b>Default Number of Line Items</b>: Defines the number of line items displayed in an <i>Itemised</i> and <i>Consulting</i>
            invoice</li>
          <li><b>Default Invoice Template</b>: Defines the invoice template used to create the output displayed in <b>Print</b>, <b>Email</b>,
            <b>PDF</b>, <b>Spreedsheet</b> and <b>Document</b> view modes.</li>
        </ul>
      </li>
      <li><a id="export"><b>Export to PDF, Spreedsheet or Document</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
        <ul>
          <li>SimpleInvoices allows to the export of an invoice from the Quick View to a <b>PDF</b>, <b>Spreedsheet</b>
            or <b>Document</b> format.</li>
          <li>Default export formats are set to:
            <ol>
              <li><b>PDF</b>: Standard PDF form file downloaded to your computer or in attached to an <b>Email</b> in <b>Email View</b>.</li>
              <li><b>Excel (.xls)</b>: Spreedsheet file downloaded to your system. Can be changed to <b>Open Spreedsheet (.od)</b> format
                for access by <i>Open Office</i>.</li>
              <li><b>Word (.doc)</b>: Document file downloaded to your system. Can be changed to <b>Open Document (.ods)</b> format for
                access by <i>Open Office</i>.</li>
            </ol>
          </li>
          <li>The default formats and dimensions can be changed in the <i>config/custom.config.php</i> file. The following lines
            specify the configuration:
<pre style="margin-left:10px;font-family:courier;font-weight:bold;font-size:1em;">
  export.spreadsheet      = xls
  export.wordprocessor    = doc
  export.pdf.screensize   = 510
  export.pdf.papersize    = Letter
  export.pdf.leftmargin   = 10
  export.pdf.rightmargin  = 10
  export.pdf.topmargin    = 10
  export.pdf.bottommargin = 10
</pre>
          </li>
          <li><b>Note</b>: The <i>config/custom.config.php</i> settings for spreadsheet and wordprocess can be set to any value provided
            their associated program can read a HTML document.</li>
        </ul>
      </li>
    </ul>
    <a id="faqs"><b>Frequently Asked Questions (FAQs)</b></a>&nbsp;<a href="#top"><i>(top)</i></a>
    <ul>
      <li><a id="faqs-what"><b>What is SimpleInvoices?</b></a> SimpleInvoices is a basic invoicing system designed with
        simplicity and functionality in mind. It caters to the needs of small organizations and home users. For more information please
        refer to the SimpleInvoices website <a href="http://www.simpleinvoices.org">http://www.simpleinvoices.org</a>.</li>
      <li><a id="faqs-need"><b>What do I need to start invoicing?</b></a> Once you've installed SimpleInvoices (refer to Installation
        documentation for more info), all you need to do is enter a <b>Biller</b> (normally your organization) and a <b>Customer</b>
        (the person your are invoicing). Once this set up is complete, you can create an invoice. However, if you are invoicing products
        sold, you also need to enter a <b>Product</b> record.</li>
      <li><a id="faqs-how"><b>How do I create invoices?</b></a> Creating invoices is easy. Once a <b>Biller</b> and <b>Customer</b>
        have been set up, select the <b>New Invoice</b> option in the <b>Money</b> tab menu. Fill in the fields in the new invoice
        form that will be displayed and click <b>Save Invoice</b>.</li>
      <li><a id="faqs-types"><b>What are the different invoice types?</b></a> In SimpleInvoices there are three types of
        invoices available:
        <ul>
          <li><b>Total Invoice</b>: Think an invoice from a plumber that lists the actions and then has one price, associated
            taxes and a total.</li>
          <li><b>Itemised Invoice</b>: An invoice that list many different items. For example a grocery store invoice.</li>
          <li><b>Consulting Invoice</b>: An invoice that is similar to an <b>Intemised Invoice</b> except that with each line
            item there is a detailed description of the work performed/product sold. For example an invoice from an
            accounting or legal firm.</li>
        </ul>
      </li>
    </ul>
  </div>
</body>
</html>
