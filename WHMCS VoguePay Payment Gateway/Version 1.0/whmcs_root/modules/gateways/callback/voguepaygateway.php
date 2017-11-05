<?php
//////////////////////////////////////////////////////
//***************************************************/
//* Do Not Run This File In Directly on ur Browser  */
//* Please see the ReadMe.txt file for instruction  */
//* This File is Written For Voguepay Gateway       */
//* For Any Help, Contact me                        */
//***************************************************/
//* Email: oluwasayo12@gmail.com                    */
//////////////////////////////////////////////////////


# Required File Includes
//include("../../../dbconnect.php");
//include("../../../includes/functions.php");
//include("../../../includes/gatewayfunctions.php");
//include("../../../includes/invoicefunctions.php");
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/functions.php'; 
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

//$gatewaymodule = "voguepaygateway.php"; # Enter your gateway module name here replacing template

$gatewayModuleName = basename(__FILE__, '.php');

$GATEWAY = getGatewayVariables($gatewayModuleName);
if (!$GATEWAY["type"]) die("Module Not Activated"); # Checks gateway module is active before accepting callback



$transaction_id = $_POST['transaction_id'];



$data = file_get_contents("https://voguepay.com/?v_transaction_id=".$transaction_id."&type=xml&demo=true");

//$result = json_decode($data, true);

$xml_elements = new SimpleXMLElement($data);
$transaction = array();
$t = array();

foreach($xml_elements as $key => $value) $transaction[$key]=$value;
# Get Returned Variables - Adjust for Post Variable Names from your Gateway's Documentation
$invoice_id = $transaction['merchant_ref'];
$status = $transaction['status'];
$transid = $transaction['transaction_id'];
$v_merchant = $transaction['merchant_id'];

$invoiceid = checkCbInvoiceID($invoice_id,$GATEWAY["name"]); 
checkCbTransID($transid); 
$merchant_id = $GATEWAY['merchant_id'];

if ($status =="Approved" ) {
     //Successful
    addInvoicePayment($invoice_id,$transid,$amount,$fee,$gatewayModuleName); 
    logTransaction($gatewayModuleName["name"],$_POST,"Transaction Was Successful");
}
else
{
logTransaction($gatewayModuleName["name"],$_POST,"Transaction Not Approved OR Unrecognised Merchant ID");
}

if ($status =="Pending") {
    logTransaction($gatewayModuleName["name"],$_POST,"Pending");
}

if ($status =="Failed") {
    logTransaction($gatewayModuleName["name"],$_POST,"Failed");
}

if ($status =="Disputed") {
    logTransaction($gatewayModuleName["name"],$_POST,"Disputed");
}

if ($status =="Cancelled") {
   logTransaction($gatewayModuleName["name"],$_POST,"Payment Cancelled By Customer");
}

?>
