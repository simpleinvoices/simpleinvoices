<?php
include('./include/menu.php');
include('./config/config.php'); 
include("./lang/$language.inc.php");


?>

<html>
<head>

                <title>Simple Invoices</title>

                <script type="text/javascript" src="./include/jquery.js"></script>
    <script type="text/javascript" src="./include/greybox.js"></script>
     <script type="text/javascript">
      var GB_ANIMATION = true;
      $(document).ready(function(){
        $("a.greybox").click(function(){
          var t = this.title || $(this).text() || this.href;
          GB_show(t,this.href,470,600);
          return false;
        });
      });
    </script>

                <script type="text/javascript" src="./include/jquery-accordian.js"></script>


                <style type="text/css">
	/* Ajax Alter popup Greybox - start */
	#GB_overlay {
	  background-image: url(./images/overlay.png);
	  position: absolute;
	  margin: auto;
	  top: 0;
	  left: 0;
	  z-index: 100;
	  width:  100%;
	  height: 100%;
	}
	
	* html #GB_overlay {
	  background-color: #000;
	  background-color: transparent;
	  background-image: url(./images/blank.gif);
	  filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src="./images/overlay.png", sizingMethod="scale");
	}
	
	#GB_window {
	  top: 10px;
	  left: 0px;
	  position: absolute;
	  background: #fff;
	  border: 5px solid #aaa;
	  overflow: auto;
	  width: 400px;
	  height: 400px;
	  z-index: 150;
	}
	
	#GB_frame {
	  border: 0;
	  overflow: auto;
	  width: 100%;
	  height: 378px;
	}
	
	#GB_caption {
	  font: 12px bold helvetica, verdana, sans-serif;
	  color: #fff;
	  background: #888;
	  padding: 2px 0 2px 5px;
	  margin: 0;
	  text-align: left;
	}
	
	#GB_window img {
	  position: absolute;
	  top: 2px;
	  right: 5px;
	  cursor: pointer;
	  cursor: hand;
	}
	/*Greybox - alert popup - end */


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
                                        <a href="./inline_instructions.php#faqs-what">What is Simple Invoices?</a><br/>
                                        <a href="./inline_instructions.php#faqs-need">What do I need to start invoicing?</a><br/>
                                        <a href="inline_instructions.php#faqs-how">How do I create invoices?</a><br/>
                                        <a href="inline_instructions.php#faqs-types">What are the different types of invoices?</a>
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
                                        <a href="manage_system_defaults.php">System Defaults</a><br/>
                                        <a href="manage_tax_rates.php">Tax Rates</a><br/>
                                        <a href="manage_preferences.php">Invoice Preferencest</a><br/>
                                        <a href="manage_payment_types.php">Payment Types</a><br/>
                                        <a href="database_sqlpatches.php">Database Upgrade Manager</a> <br/>
                                        <a href="backup_database.php">Backup Database</a>
                                </div>
                        </div>
                        <div id="item26">
                                <div class="mytitle">Help!!</div>
                                <div class="mycontent">
                                        <a href="inline_instructions.php#installation">Installation<br/></a>
                                        <a href="inline_instructions.php#upgrading">Upgrading Simple Invoices<br/></a>
                                        <a href="inline_instructions.php#use">Prepare Simple Invoices for use<br/></a>
                                </div>
                        </div>
                </div>
 




</BODY>
</HTML>







