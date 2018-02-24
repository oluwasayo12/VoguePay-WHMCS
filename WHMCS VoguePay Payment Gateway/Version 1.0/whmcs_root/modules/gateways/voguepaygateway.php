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


function voguepaygateway_config() {
$pay_color  = 'blue'; //Default Favourite Color is blue
    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"Voguepay Payment Processor"),
     "merchant_id" => array("FriendlyName" => "Voguepay Merchant ID", "Type" => "text", "Size" => "20", ),
     "pay_color" => array("FriendlyName" => "Make Payment ICon Color", "Type" => "dropdown", "Options" => "green,blue,red,grey", "Description" => "Example of Submit Image <img src=https://voguepay.com/images/buttons/make_payment_$pay_color.png border=0 alt=We Accept Voguepay />", ),
     "cur" => array("FriendlyName" => "Currency", "Type" => "dropdown", "Options" => "NGN,USD", "Description" => "Select Currency", ),   
     "developer_code" => array("FriendlyName" => "Your Voguepay Developer Code", "Type" => "text", "Size" => "20", ),
    );
    return $configarray;
}

function voguepaygateway_link($params) {
    
    $pay_color  = $params['pay_color'];
    $cur  = $params['cur'];
    # Gateway Specific Variables
    $developer_code = $params['developer_code'];
    $gatewaytestmode = $params['testmode'];
        
    if($gatewaytestmode)
    {
    $merchant_id = 'demo';
    }
    else
    {
    $merchant_id = $params['merchant_id'];
    }
    
    
    $notify_url = $params['notification_url'];
    $fail_url = $params['fail_url'];
    $success_url = $params['success_url'];

    # Invoice Variables
    $invoiceid = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount']; # Format: ##.##
    $currency = $params['currency']; # Currency Code

    # Client Variables
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    # System Variables
    $companyname = $params['companyname'];
    $systemurl = $params['systemurl'];
    $currency = $params['currency'];


    # Enter your code submit to the gateway...
 $isSSL = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443);
$callbackUrl = 'http' . ($isSSL ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] .
        substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')) .
        '/modules/gateways/callback/voguepaygateway.php';
 $redirect_url = "//".$_SERVER['SERVER_NAME']."/billing/viewinvoice.php?id=".$invoiceid;
       $developer_code = '57aeee5803b0d';
    $code = '<form method="post" action="https://voguepay.com/pay">
<input type="hidden" name="v_merchant_id" value="'.$merchant_id.'" />
<input type="hidden" name="item_1" value="Invoice Payment" />
<input type="hidden" name="developer_code" value="'.$developer_code.'" />
<input type="hidden" name="price_1" value="'.$amount.'" />
<input type="hidden" name="description_1" value="'.$description.'" />
<input type="hidden" name="memo" value="'.$description.'" />
<input type="hidden" name="merchant_ref" value="'.$invoiceid.'" />
<input type="hidden" name="cur" value="'.$cur.'" />
<input type="hidden" name="notify_url" value="'.$callbackUrl.'" />
<input type="hidden" name="success_url" value="'.$redirect_url.'" />
<input type="hidden" name="fail_url" value="'.$redirect_url.'" />
<input type="image" src="https://voguepay.com/images/buttons/make_payment_'.$pay_color.'.png" border="0" alt="We Accept Voguepay" />


</form>';

    return $code;
}


?>
