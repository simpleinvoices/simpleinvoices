<?php
include('./include/menu.php');
include('./config/config.php'); 
include("./lang/$language.inc.php");


?>

<html>
<head>

                <title>Simple Invoices</title>

                <script type="text/javascript" src="./include/jquery.js"></script>
                <script type="text/javascript" src="./include/jquery-accordian.js"></script>


                <style type="text/css">
			/*The CSS code for the mina body of Simple Invoices - start*/
			body{background:#F5F5F5 url('./themes/<?php echo $theme; ?>/images/gb_top.gif') repeat-x; color: #222; margin: 0;      padding: 0;}

                        #list1 { width:48%;  position:absolute; top:15%; right:1em; }
                        .title { cursor:pointer; border:1px solid #CCCCCC; margin-top:0.5em; padding:0.1em; }
                        .on1  .title { background-color:#E4EFC7; }
                        .off1 .title { background-color:#E0E0E0; }
                        .content    { background-color:#F5F5F5; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }

                        #list2 { width:48%; position:absolute; top:15%; left:1em; }
                        .mytitle { cursor:pointer; border:1px solid #CCCCCC; margin-top:0.5em; padding:0.1em; }
                        .on  .mytitle { background-color:#E4EFC7; }
                        .off .mytitle { background-color:#E0E0E0; }
                        .mycontent    { background-color:#F5F5F5; padding:0.1em; border:1px solid #C0C0C0; border-top-width:0; }

			a:link {  text-decoration: none; }
			a:visited { text-decoration: none; }
			a:active { text-decoration: none; }
			a:hover {text-decoration: underline; color:  #ff0000; }

                </style>

</head>
<BODY>
<?php
$mid->printMenu('hormenu1');
$mid->printFooter();
?>
<!-- <link rel="stylesheet" type="text/css" href="themes/<?php echo $theme; ?>/tables.css"> -->
<br>


                <h1 align=center>Welcome to Simple Invoices</h1>
                <div id="list1">
                <h2><img src="./images/reports.png"></img> Quick stats</h2>
                        <div id="item11">

                                <div class="title">Lorem ipsum dolor sit amet</div>

                                <div class="content">

                                        consectetuer adipiscing elit<br/>

                                        Sed lorem leo<br/>

                                        lorem leo consectetuer adipiscing elit<br/>

                                        Sed lorem leo<br/>

                                        rhoncus sit amet

                                </div>
                        </div>

                        <div id="item12">

                                <div class="title">elementum at</div>

                                <div class="content">

                                        bibendum at, eros<br/>

                                        Cras at mi et tortor egestas vestibulum<br/>

                                        sed Cras at mi vestibulum<br/>

                                        Phasellus sed felis sit amet

                                </div>

                        </div>

                        <div id="item13">

                                <div class="title">orci dapibus semper.</div>

                                <div class="content">

                                        Morbi eros massa<br/>

                                        interdum et, vestibulum id, rutrum nec<br/>

                                        bibendum at, eros<br/>

                                        Cras at mi et tortor egestas vestibulum<br/>

                                        Phasellus sed felis sit amet<br/>

                                        Morbi eros massa<br/>

                                        interdum et, vestibulum id, rutrum nec<br/>

                                        Phasellus sem leo

                                </div>

                        </div>
                </div>


               <div id="list2">

                <h2><img src="./images/menu.png"> Shortcut menu</h2>

                        <div id="item21">
                                <div class="mytitle">Getting Started</div>
                                <div class="mycontent">
                                        consectetuer adipiscing elit<br/>
                                        Sed lorem leo<br/>
                                        lorem leo consectetuer adipiscing elit<br/>
                                        Sed lorem leo<br/>
                                </div>
                        </div>

                        <div id="item22">
                                <div class="mytitle">Create an invoice</div>
                                <div class="mycontent">
                                        <a href="invoice_total.php">Total</a><br/>
                                        <a href="invoice_itemised.php">Itemised</a><br/>
                                        <a href="invoice_consulting.php">Consulting</a><br/>
                                </div>
                        </div>
                        <div id="item23">
                                <div class="mytitle">Manage your existing invoices</div>
                                <div class="mycontent">
                                        <a href="manage_invoices.php">Manage Invoices</a><br/>
                                </div>
                        </div>

                        <div id="item24">
                                <div class="mytitle">Manage your data</div>
                                <div class="mycontent">
                                        <a href="insert_biller.php">Add Biller</a><br/>
                                        <a href="insert_customer.php">Add Customer</a><br/>
                                        <a href="insert_product.php">Add Product</a><br/>
                                </div>
                        </div>
                        <div id="item25">
                                <div class="mytitle">Options</div>
                                <div class="mycontent">
                                        <a href="manage_invoices.php">System Defaults</a><br/>
                                        <a href="manage_invoices.php">Tax Rates</a><br/>
                                        <a href="manage_invoices.php">Invoice Preferencest</a><br/>
                                        <a href="manage_invoices.php">Payment Types</a><br/>
                                        <a href="manage_invoices.php">Database Upgrade Manager</a> <br/>
                                        <a href="manage_invoices.php">Backup Database</a>
                                </div>
                        </div>
                        <div id="item26">
                                <div class="mytitle">Help!!</div>
                                <div class="mycontent">
                                        Installation<br/>
                                        Upgrading Simple Invoices<br/>
                                        Prepare Simple Invoices for uset<br/>
                                </div>
                        </div>
                </div>
 




</BODY>
</HTML>







