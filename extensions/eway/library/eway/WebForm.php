<?php
require_once('EwayPaymentLive.php');
if (isset($_POST['btnProcess'])){

  $txtFirstName = $_POST['txtFirstName'];
  $txtLastName = $_POST['txtLastName'];
  $txtEmail = $_POST['txtEmail'];
  $txtAddress = $_POST['txtAddress'];
  $txtPostcode = $_POST['txtPostcode'];
  $txtTxnNumber = $_POST['txtTxnNumber'];
  $txtInvDesc = $_POST['txtInvDesc'];
  $txtInvRef = $_POST['txtInvRef'];
  $txtOption1 = $_POST['txtOption1'];
  $txtOption2 = $_POST['txtOption2'];
  $txtOption3 = $_POST['txtOption3'];
  $txtCCNumber = $_POST['txtCCNumber'];
  $ddlExpiryMonth = $_POST['ddlExpiryMonth'];
  $ddlExpiryYear = $_POST['ddlExpiryYear'];
  $txtCCName = $_POST['txtCCName'];
  $txtAmount = $_POST['txtAmount'];
  
  // Set the payment details
  $eway = new EwayPaymentLive($eWAY_CustomerID, $eWAY_PaymentMethod, $eWAY_UseLive);

  $eway->setTransactionData("TotalAmount", $txtAmount); //mandatory field
  $eway->setTransactionData("CustomerFirstName", $txtFirstName);
  $eway->setTransactionData("CustomerLastName", $txtLastName);
  $eway->setTransactionData("CustomerEmail", $txtEmail);
  $eway->setTransactionData("CustomerAddress", $txtAddress);
  $eway->setTransactionData("CustomerPostcode", $txtPostcode);
  $eway->setTransactionData("CustomerInvoiceDescription", $txtInvDesc);
  $eway->setTransactionData("CustomerInvoiceRef", $txtInvRef);
  $eway->setTransactionData("CardHoldersName", $txtCCName); //mandatory field
  $eway->setTransactionData("CardNumber", $txtCCNumber); //mandatory field
  $eway->setTransactionData("CardExpiryMonth", $ddlExpiryMonth); //mandatory field
  $eway->setTransactionData("CardExpiryYear", $ddlExpiryYear); //mandatory field
  $eway->setTransactionData("TrxnNumber", "");
  $eway->setTransactionData("Option1", $txtOption1);
  $eway->setTransactionData("Option2", $txtOption2);
  $eway->setTransactionData("Option3", $txtOption3);
  
  $eway->setCurlPreferences(CURLOPT_SSL_VERIFYPEER, 0); // Require for Windows hosting

  // Send the transaction
  $ewayResponseFields = $eway->doPayment();

  if(strtolower($ewayResponseFields["EWAYTRXNSTATUS"])=="false")
  {
      print "Transaction Error: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>\n";
      foreach($ewayResponseFields as $key => $value)
          print "\n<br>\$ewayResponseFields[\"$key\"] = $value";
  }
  else if(strtolower($ewayResponseFields["EWAYTRXNSTATUS"])=="true")
  {
       // payment succesfully sent to gateway
       // Payment succeeded get values returned
       $lblResult = " Result: " . $ewayResponseFields["EWAYTRXNSTATUS"] . "<br>";
       $lblResult .= " AuthCode: " . $ewayResponseFields["EWAYAUTHCODE"] . "<br>";
       $lblResult .= " Error: " . $ewayResponseFields["EWAYTRXNERROR"] . "<br>";
       $lblResult .= " eWAYInvoiceRef: " . $ewayResponseFields["EWAYTRXNREFERENCE"] . "<br>";
       $lblResult .= " Amount: " . $ewayResponseFields["EWAYRETURNAMOUNT"] . "<br>";
       $lblResult .= " Txn Number: " . $ewayResponseFields["EWAYTRXNNUMBER"] . "<br>";
       $lblResult .= " Option1: " . $ewayResponseFields["EWAYOPTION1"] . "<br>";
       $lblResult .= " Option2: " . $ewayResponseFields["EWAYOPTION2"] . "<br>";
       $lblResult .= " Option3: " . $ewayResponseFields["EWAYOPTION3"] . "<br>";
       echo $lblResult;
  }
  else
  {
       // invalid response recieved from server.
       $lblResult =  "Error: An invalid response was recieved from the payment gateway.";
       echo $lblResult;
  }

}
else {
?>
<HTML>
<HEAD><title>eWAY PHP Example</title></HEAD>
<body>
<form id="Form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" ENCTYPE="multipart/form-data">

<div id="pnlBeforeProcess" style="height:328px;width:488px;">&nbsp; * Fields in Red are required 
<TABLE id=Table1 style="WIDTH: 352px; HEIGHT: 264px" cellSpacing=0 cellPadding=3 
width=352 border=0>
  <TR>
    <TD bgColor=#000000 colSpan=2><FONT color=#ffffff><STRONG>Process 
      Transaction</STRONG></FONT></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label1">First Name:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtFirstName" type="text" id="txtFirstName" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label5">Last Name:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtLastName" type="text" id="txtLastName" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label7">Email Address:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtEmail" type="text" id="txtEmail" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label11">Address:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtAddress" type="text" id="txtAddress" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label12">Postcode:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtPostcode" type="text" id="txtPostcode" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label8">Invoice Description:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtInvDesc" type="text" id="txtInvDesc" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label9">Invoice Reference:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtInvRef" type="text" id="txtInvRef" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro>
<span id="Label13">Transaction Number:</span></TD>
    <TD bgColor=gainsboro>
<input name="txtTxnNumber" type="text" id="txtTxnNumber" /></TD></TR>
  <TR>
    <TD bgColor=red>
<span id="Label10">Card Holders Name:</span></TD>
    <TD 
  bgColor=red>
<input name="txtCCName" type="text" id="txtCCName" /></TD></TR>
  <TR>
    <TD bgColor=red>
<span id="Label2">Card Number:</span></TD>
    <TD bgColor=red>
<input name="txtCCNumber" type="text" maxlength="17" id="txtCCNumber" /></TD></TR>
  <TR>
    <TD bgColor=red>
<span id="Label3">Card Expiry:</span></TD>
    <TD bgColor=red>
<select name="ddlExpiryMonth" id="ddlExpiryMonth">
		<option value="01">01</option>
		<option value="02">02</option>
		<option value="03">03</option>
		<option value="04">04</option>
		<option value="05">05</option>
		<option value="06">06</option>
		<option value="07">07</option>
		<option value="08">08</option>
		<option value="09">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
	</select>
<select name="ddlExpiryYear" id="ddlExpiryYear">
		<option value="04">04</option>
		<option value="05">05</option>
		<option value="06">06</option>
		<option value="07">07</option>
		<option value="08">08</option>
		<option value="09">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
	</select></TD></TR>
  <TR>
    <TD style="HEIGHT: 24px" bgColor=red>
<span id="Label4">Card Type:</span></TD>
    <TD style="HEIGHT: 24px" bgColor=red>
<select name="ddlCardType" id="ddlCardType">
		<option value="VISA">VISA</option>
		<option value="MASTERCARD">MASTERCARD</option>
		<option value="AMEX">AMEX</option>
	</select></TD></TR>
  <TR>
    <TD bgColor=red>
<span id="Label6">Total Amount:</span></TD>
    <TD bgColor=red>
<input name="txtAmount" type="text" id="txtAmount" style="width:64px;" /></TD></TR>
  <TR>
    <TD bgColor=gainsboro colSpan=2>
<input type="submit" name="btnProcess" value="Process Transaction" id="btnProcess" /></TD></TR></TABLE>
<BR>
</form>
</body>
</HTML>
<?php }?>