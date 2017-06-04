<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Network\Http\Client;

use Cake\Event\Event;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\ORM\RulesChecker;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
class PaypalExpressRecurringComponent extends Component {
	  
	
	  	
	//$USE_PROXY = false;
	//$version="64";
	  public function expresscheckout($data) {
		
		
		 // ==================================
		     // PayPal Express Checkout Module
		     // ==================================
		     
		     //'------------------------------------
		     //' The paymentAmount is the total value of 
		     //' the shopping cart, that was set 
		     //' earlier in a session variable 
		     //' by the shopping cart page
		     //'------------------------------------
		     
		     //$paymentAmount = $_POST["Payment_Amount"];
		     //$paymentAmount = $_SESSION["Payment_Amount"];
		    
		      
			$paymentAmount = $data['amount'];
		        $description = "Your amount is $".$data['amount'];
			
			$_SESSION["Payment_Amount"] =  $paymentAmount;
			$_SESSION["Payment_Description"] =  $description;
		     //echo $data['amount']; die;
		     //'------------------------------------
		     //' The currencyCodeType and paymentType 
		     //' are set to the selections made on the Integration Assistant 
		     //'------------------------------------
		     $currencyCodeType = "USD";
		     $paymentType = "SALE";
		     #$paymentType = "Authorization";
		     #$paymentType = "Order";
		    
		     //'------------------------------------
		     //' The returnURL is the location where buyers return to when a
		     //' payment has been succesfully authorized.
		     //'
		     //' This is set to the value entered on the Integration Assistant 
		     //'------------------------------------
		     $returnURL = $data['return_url'];
		     //$returnURL = SITE_URL."paypal/review";
		     //'------------------------------------
			 
			 
		     //' The cancelURL is the location buyers are sent to when they hit the
		     //' cancel button during authorization of payment during the PayPal flow
		     //'
		     //' This is set to the value entered on the Integration Assistant 
		     //'------------------------------------
		     $cancelURL = SITE_URL."Users/index.php";
		    
		     
		     //'------------------------------------
		     //' Calls the SetExpressCheckout API call
		     //'
		     //' The CallShortcutExpressCheckout function is defined in the file PayPalFunctions.php,
		     //' it is included at the top of this file.
		     //'------------------------------------------------- 
		     $resArray = $this->CallShortcutExpressCheckout ($paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL,$description);
			 
		     $ack = strtoupper($resArray["ACK"]);
		     if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		     {
			    
				 $this->RedirectToPayPal($resArray["TOKEN"]);
		     } 
		     else  
		     {
		
			     //Display a user friendly Error on the page using any of the following error information returned by PayPal
			     $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			     $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			     $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			     $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		     
			     echo "SetExpressCheckout API call failed. ";
			     echo "Detailed Error Message: " . $ErrorLongMsg;
			     echo "Short Error Message: " . $ErrorShortMsg;
			     echo "Error Code: " . $ErrorCode;
			     echo "Error Severity Code: " . $ErrorSeverityCode;
				
				return $resArray;
		     }
		 
					
				 
		
	}
		
	public function order_save($FinalPaymentAmt,$token,$PayerID,$email,$shipToName,$shipToStreet,$shipToCity,$shipToState,$shipToZip,$shipToCountry,$payment_amount,$description,$paymentType,$currencyCodeType,$serverName)
	{
	
		
				$PaymentOption = "PayPal";
			if ( $PaymentOption == "PayPal" )
			{
				/*
				'------------------------------------
				' The paymentAmount is the total value of 
				' the shopping cart, that was set 
				' earlier in a session variable 
				' by the shopping cart page
				'------------------------------------
				*/
				
				$finalPaymentAmount = $payment_amount;
				
				/*
				'------------------------------------
				' Calls the DoExpressCheckoutPayment API call
				'
				' The ConfirmPayment function is defined in the file PayPalFunctions.jsp,
				' that is included at the top of this file.
				'-------------------------------------------------
				*/
				
				//$resArray = ConfirmPayment ( $finalPaymentAmount ); Remove comment with ontime payment.
				
				return $resArray = $this->CreateRecurringPaymentsProfile($FinalPaymentAmt,$token,$PayerID,$email,$shipToName,$shipToStreet,$shipToCity,$shipToState,$shipToZip,$shipToCountry,$payment_amount,$description,$paymentType,$currencyCodeType,$serverName);
				die;
				//$ack = strtoupper($resArray["ACK"]);

			}
			
	 }
	 
		public function get_shipping_detail($token){
		
			$resArray = $this->GetShippingDetails( $token );
			 return $resArray;
		
		}
	

////////////////////////////////////////////////////////////




/*------------------------------------------Paypal Functions --------------------------------------------*/


public function CallShortcutExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, $cancelURL,$description) 
	{
		
		 
		//------------------------------------------------------------------------------------------------------------------------------------
		// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation
 
				$_SESSION["currencyCodeType"] = $currencyCodeType;	  
				$_SESSION["PaymentType"] = $paymentType;	
				
				/*$nvpstr  = "&AMT=".urlencode($paymentAmount);
				$nvpstr .= "&PAYMENTACTION=".urlencode($paymentType);
				$nvpstr .= "&BILLINGAGREEMENTDESCRIPTION=".urlencode("Test Recurring Payment($1 monthly)");
				$nvpstr .= "&BILLINGTYPE= RecurringPayments";
				$nvpstr .= "&solutiontype=".urlencode("sole");
				$nvpstr .= "&RETURNURL=".urlencode($returnURL);
				$nvpstr .= "&CANCELURL=".urlencode($cancelURL);
				$nvpstr .= "&CURRENCYCODE=".urlencode($currencyCodeType);*/
				
				$nvpstr="&AMT=". $paymentAmount;
		$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&BILLINGAGREEMENTDESCRIPTION=".urlencode($description);
		$nvpstr = $nvpstr . "&BILLINGTYPE=RecurringPayments";
		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
		$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCodeType;
				
		 
		//'--------------------------------------------------------------------------------------------------------------- 
		//' Make the API call to PayPal
		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
		//' If an error occured, show the resulting errors
		//'---------------------------------------------------------------------------------------------------------------
	 
		$resArray= $this->hash_call("SetExpressCheckout", $nvpstr);
		 
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			$token = urldecode($resArray["TOKEN"]);
			$_SESSION['TOKEN']=$token;
		}

	    return $resArray;
	}

	/*   
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the SetExpressCheckout API Call.
	' Inputs:  
	'		paymentAmount:  	Total value of the shopping cart
	'		currencyCodeType: 	Currency code value the PayPal API
	'		paymentType: 		paymentType has to be one of the following values: Sale or Order or Authorization
	'		returnURL:			the page where buyers return to after they are done with the payment review on PayPal
	'		cancelURL:			the page where buyers return to when they cancel the payment review on PayPal
	'		shipToName:		the Ship to name entered on the merchant's site
	'		shipToStreet:		the Ship to Street entered on the merchant's site
	'		shipToCity:			the Ship to City entered on the merchant's site
	'		shipToState:		the Ship to State entered on the merchant's site
	'		shipToCountryCode:	the Code for Ship to Country entered on the merchant's site
	'		shipToZip:			the Ship to ZipCode entered on the merchant's site
	'		shipToStreet2:		the Ship to Street2 entered on the merchant's site
	'		phoneNum:			the phoneNum  entered on the merchant's site
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
	public function CallMarkExpressCheckout( $paymentAmount, $currencyCodeType, $paymentType, $returnURL, 
									  $cancelURL, $shipToName, $shipToStreet, $shipToCity, $shipToState,
									  $shipToCountryCode, $shipToZip, $shipToStreet2, $phoneNum
									) 
	{
		//------------------------------------------------------------------------------------------------------------------------------------
		// Construct the parameter string that describes the SetExpressCheckout API call in the shortcut implementation
		
		$nvpstr="&PAYMENTREQUEST_0_AMT=". $paymentAmount;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&RETURNURL=" . $returnURL;
		$nvpstr = $nvpstr . "&CANCELURL=" . $cancelURL;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_CURRENCYCODE=" . $currencyCodeType;
		$nvpstr = $nvpstr . "&ADDROVERRIDE=1";
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTONAME=" . $shipToName;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET=" . $shipToStreet;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTREET2=" . $shipToStreet2;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCITY=" . $shipToCity;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOSTATE=" . $shipToState;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE=" . $shipToCountryCode;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOZIP=" . $shipToZip;
		$nvpstr = $nvpstr . "&PAYMENTREQUEST_0_SHIPTOPHONENUM=" . $phoneNum;
		
		$_SESSION["currencyCodeType"] = $currencyCodeType;	  
		$_SESSION["PaymentType"] = $paymentType;

		//'--------------------------------------------------------------------------------------------------------------- 
		//' Make the API call to PayPal
		//' If the API call succeded, then redirect the buyer to PayPal to begin to authorize payment.  
		//' If an error occured, show the resulting errors
		//'---------------------------------------------------------------------------------------------------------------
	    $resArray= $this->hash_call("SetExpressCheckout", $nvpstr);
		$ack = strtoupper($resArray["ACK"]);
		pr($ack); die;
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			$token = urldecode($resArray["TOKEN"]);
			$_SESSION['TOKEN']=$token;
		}
		   
	    return $resArray;
	}
	
	/*
	'-------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		None
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'-------------------------------------------------------------------------------------------
	*/
	public function GetShippingDetails( $token )
	{
		//'--------------------------------------------------------------
		//' At this point, the buyer has completed authorizing the payment
		//' at PayPal.  The function will call PayPal to obtain the details
		//' of the authorization, incuding any shipping information of the
		//' buyer.  Remember, the authorization is not a completed transaction
		//' at this state - the buyer still needs an additional step to finalize
		//' the transaction
		//'--------------------------------------------------------------
	   
	    //'---------------------------------------------------------------------------
		//' Build a second API request to PayPal, using the token as the
		//'  ID to get the details on the payment authorization
		//'---------------------------------------------------------------------------
	    $nvpstr="&TOKEN=" . $token;

		//'---------------------------------------------------------------------------
		//' Make the API call and store the results in an array.  
		//'	If the call was a success, show the authorization details, aGetExpressnd provide
		//' 	an action to complete the payment.  
		//'	If failed, show the error
		//'---------------------------------------------------------------------------
	    $resArray= $this->hash_call("GetExpressCheckoutDetails",$nvpstr);
	    $ack = strtoupper($resArray["ACK"]);

		if($ack == "SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{	
			$_SESSION['payer_id'] =	$resArray['PAYERID'];
			$_SESSION['email'] =	$resArray['EMAIL'];
			$_SESSION['firstName'] = $resArray["FIRSTNAME"]; 
			$_SESSION['lastName'] = $resArray["LASTNAME"]; 
			$_SESSION['shipToName'] = $resArray["SHIPTONAME"]; 
			$_SESSION['shipToStreet'] = $resArray["SHIPTOSTREET"]; 
			$_SESSION['shipToCity'] = $resArray["SHIPTOCITY"];
			$_SESSION['shipToState'] = $resArray["SHIPTOSTATE"];
			$_SESSION['shipToZip'] = $resArray["SHIPTOZIP"];
			$_SESSION['shipToCountry'] = $resArray["SHIPTOCOUNTRYCODE"];
		} 
		return $resArray;
	}
	
	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	Prepares the parameters for the GetExpressCheckoutDetails API Call.
	'
	' Inputs:  
	'		sBNCode:	The BN code used by PayPal to track the transactions from a given shopping cart.
	' Returns: 
	'		The NVP Collection object of the GetExpressCheckoutDetails Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/
		
	public function ConfirmPayment( $FinalPaymentAmt,$token,$payerID,$paymentType,$currencyCodeType,$serverName)
	{
		/* Gather the information to make the final call to
		   finalize the PayPal payment.  The variable nvpstr
		   holds the name value pairs
		   */
	 
	 
 
		//Format the other parameters that were stored in the session from the previous calls	
		/*$token 				= urlencode($_SESSION['TOKEN']);
		$paymentType 		= urlencode($_SESSION['PaymentType']);
		$currencyCodeType 	= urlencode($_SESSION['currencyCodeType']);
		$payerID 			= urlencode($_SESSION['payer_id']);

		$serverName 		= urlencode($_SERVER['SERVER_NAME']);*/

		$nvpstr  = '&TOKEN=' . $token . '&PAYERID=' . $payerID . '&PAYMENTACTION=' . $paymentType . '&AMT=' . $FinalPaymentAmt;
		$nvpstr .= '&CURRENCYCODE=' . $currencyCodeType . '&IPADDRESS=' . $serverName; 

		 /* Make the call to PayPal to finalize payment
		    If an error occured, show the resulting errors
		    */
		$resArray= $this->hash_call("DoExpressCheckoutPayment",$nvpstr);
		//$_SESSION['billing_agreemenet_id']	= $resArray["BILLINGAGREEMENTID"];

		/* Display the API response back to the browser.
		   If the response from PayPal was a success, display the response parameters'
		   If the response was an error, display the errors received using APIError.php.
		   */
		$ack = strtoupper($resArray["ACK"]);

		return $resArray;
	die;
	}
	
	public function CreateRecurringPaymentsProfile($FinalPaymentAmt,$token,$payerId,$description)
	{
		
		
		/*$payment_data = $this->ConfirmPayment( $FinalPaymentAmt,$token,$PayerID,$paymentType,$currencyCodeType,$serverName );
		if($payment_data['ACK'] == 'Success' || $payment_data['ACK'] == 'SuccessWithWarning'){
		 
		$TRANSACTIONID = $payment_data['TRANSACTIONID'];*/
		 
		$profile_start_date =  date('Y-m-d',strtotime("+1 days"));
		 
		//'--------------------------------------------------------------
		//' At this point, the buyer has completed authorizing the payment
		//' at PayPal.  The function will call PayPal to obtain the details
		//' of the authorization, incuding any shipping information of the
		//' buyer.  Remember, the authorization is not a completed transaction
		//' at this state - the buyer still needs an additional step to finalize
		//' the transaction
		//'--------------------------------------------------------------
		/*$token 			= urlencode($_SESSION['TOKEN']);
		$email 			= urlencode($_SESSION['email']);
		$shipToName		= urlencode($_SESSION['shipToName']);
		$shipToStreet	= urlencode($_SESSION['shipToStreet']);
		$shipToCity		= urlencode($_SESSION['shipToCity']);
		$shipToState	= urlencode($_SESSION['shipToState']);
		$shipToZip		= urlencode($_SESSION['shipToZip']);
		$shipToCountry	= urlencode($_SESSION['shipToCountry']);*/
	   
	    //'---------------------------------------------------------------------------
		//' Build a second API request to PayPal, using the token as the
		//'  ID to get the details on the payment authorization
		//'---------------------------------------------------------------------------
		//'--------------------------------------------------------------
		//' At this point, the buyer has completed authorizing the payment
		//' at PayPal.  The function will call PayPal to obtain the details
		//' of the authorization, incuding any shipping information of the
		//' buyer.  Remember, the authorization is not a completed transaction
		//' at this state - the buyer still needs an additional step to finalize
		//' the transaction
		//'--------------------------------------------------------------
		$token 		= urlencode($token);
		$email 		= urlencode('tkoles@gmail.com');
		$shipToName		= urlencode('Tatiana');
		$shipToStreet		= urlencode('1914 Riverlawn Dr');
		$shipToCity		= urlencode('Kingwood');
		$shipToState		= urlencode('Kingwood');
		$shipToZip		= urlencode('TX 77339');
		//$shipToCountry	= 'IN';
		$shipToCountry = urlencode('US');
	
	    //'---------------------------------------------------------------------------
		//' Build a second API request to PayPal, using the token as the
		//'  ID to get the details on the payment authorization
		//'---------------------------------------------------------------------------
		$nvpstr ="&TOKEN=".$token;
		$nvpstr .="&EMAIL=tkoles@gmail.com ";
		$nvpstr .="&SHIPTONAME=".$shipToName;
		$nvpstr .="&SHIPTOSTREET=".$shipToStreet;
		$nvpstr .="&SHIPTOCITY=".$shipToCity;
		$nvpstr .="&SHIPTOSTATE=".$shipToState;
		$nvpstr .="&SHIPTOZIP=".$shipToZip;
		$nvpstr .="&SHIPTOCOUNTRY=".$shipToCountry;
		$nvpstr .="&PROFILESTARTDATE=".urlencode($profile_start_date.'T0:0:0');
		$nvpstr .="&DESC=".urlencode($description);
		$nvpstr .="&BILLINGPERIOD=Month";
		$nvpstr .="&BILLINGFREQUENCY=1";
		$nvpstr .="&AMT=".$FinalPaymentAmt;
		$nvpstr .="&CURRENCYCODE=USD";
		$nvpstr .="&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];
			
			//pr($nvpstr); die;
		//'---------------------------------------------------------------------------
		//' Make the API call and store the results in an array.  
		//'	If the call was a success, show the authorization details, and provide
		//' 	an action to complete the payment.  
		//'	If failed, show the error
		//'---------------------------------------------------------------------------
		$resArray=$this->hash_call("CreateRecurringPaymentsProfile",$nvpstr);
		
		$ack = strtoupper($resArray["ACK"]);
		return $resArray;
	/*}else{
		
		return $payment_data;
		
	}*/
	
	die;
	
	}
	/*
	'-------------------------------------------------------------------------------------------------------------------------------------------
	' Purpose: 	This function makes a DoDirectPayment API call
	'
	' Inputs:  
	'		paymentType:		paymentType has to be one of the following values: Sale or Order or Authorization
	'		paymentAmount:  	total value of the shopping cart
	'		currencyCode:	 	currency code value the PayPal API
	'		firstName:			first name as it appears on credit card
	'		lastName:			last name as it appears on credit card
	'		street:				buyer's street address line as it appears on credit card
	'		city:				buyer's city
	'		state:				buyer's state
	'		countryCode:		buyer's country code
	'		zip:				buyer's zip
	'		creditCardType:		buyer's credit card type (i.e. Visa, MasterCard ... )
	'		creditCardNumber:	buyers credit card number without any spaces, dashes or any other characters
	'		expDate:			credit card expiration date
	'		cvv2:				Card Verification Value 
	'		
	'-------------------------------------------------------------------------------------------
	'		
	' Returns: 
	'		The NVP Collection object of the DoDirectPayment Call Response.
	'--------------------------------------------------------------------------------------------------------------------------------------------	
	*/


	public function DirectPayment( $paymentType, $paymentAmount, $creditCardType, $creditCardNumber,
							$expDate, $cvv2, $firstName, $lastName, $street, $city, $state, $zip, 
							$countryCode, $currencyCode )
	{
		//Construct the parameter string that describes DoDirectPayment
		$nvpstr = "&AMT=" . $paymentAmount;
		$nvpstr = $nvpstr . "&CURRENCYCODE=" . $currencyCode;
		$nvpstr = $nvpstr . "&PAYMENTACTION=" . $paymentType;
		$nvpstr = $nvpstr . "&CREDITCARDTYPE=" . $creditCardType;
		$nvpstr = $nvpstr . "&ACCT=" . $creditCardNumber;
		$nvpstr = $nvpstr . "&EXPDATE=" . $expDate;
		$nvpstr = $nvpstr . "&CVV2=" . $cvv2;
		$nvpstr = $nvpstr . "&FIRSTNAME=" . $firstName;
		$nvpstr = $nvpstr . "&LASTNAME=" . $lastName;
		$nvpstr = $nvpstr . "&STREET=" . $street;
		$nvpstr = $nvpstr . "&CITY=" . $city;
		$nvpstr = $nvpstr . "&STATE=" . $state;
		$nvpstr = $nvpstr . "&COUNTRYCODE=" . $countryCode;
		$nvpstr = $nvpstr . "&IPADDRESS=" . $_SERVER['REMOTE_ADDR'];

		$resArray= $this->hash_call("DoDirectPayment", $nvpstr);

		return $resArray;
	}
	
	public function ManageRecurringPaymentsProfileStatus( $profile_id, $action)
	{
		//Construct the parameter string that describes DoDirectPayment
		$nvpstr = "&PROFILEID=" . urlencode( $profile_id );
		$nvpstr = $nvpstr . "&ACTION=" . urlencode( $action );
		$nvpstr = $nvpstr . "&NOTE=" . urlencode( 'Profile cancelled at store' );

		$resArray= $this->hash_call("ManageRecurringPaymentsProfileStatus", $nvpstr);

		return $resArray;
	}
	
	
	public function UpdateRecurringPaymentsProfile( $profile_id, $paymentAmount)
	{
		//Construct the parameter string that describes DoDirectPayment
		$nvpstr = "&AMT=" . $paymentAmount;
		$nvpstr = $nvpstr . "&PROFILEID=" . urlencode( $profile_id );
		$nvpstr = $nvpstr . "&NOTE=" . urlencode( 'Update recurring payment profile' );
		$nvpstr .="&CURRENCYCODE=USD";

		$resArray= $this->hash_call("UpdateRecurringPaymentsProfile", $nvpstr);

		return $resArray;
	}


	/**
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	  * hash_call: Function to perform the API call to PayPal using API signature
	  * @methodName is name of API  method.
	  * @nvpStr is nvp string.
	  * returns an associtive array containing the response from the server.
	  '-------------------------------------------------------------------------------------------------------------------------------------------
	*/
	public function hash_call($methodName,$nvpStr)
	{
		
		
	//declaring of global variables
	 
		if(defined('API_UserName')){
			
		} else{
			
		define("API_UserName","admin_api1.self-match.com");
		define("API_Password","7266X58ELM5LXAJN");
		define("API_Signature",'AFcWxV21C7fd0v3bYYYRCpSSRl31AcZZr3cbrmTEA6qzNZsIeZbDhwFw');
		define("sBNCode","PP-ECWizard");
		define("SandboxFlag",true);
		 
		if (SandboxFlag == false) 
	{
		 
		define("API_Endpoint","https://api-3t.sandbox.paypal.com/nvp");
		define("PAYPAL_URL","https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=");
	}
	else
	{
		define("API_Endpoint","https://api-3t.paypal.com/nvp");
		define("PAYPAL_URL","https://www.paypal.com/webscr?cmd=_express-checkout&token=");
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		$PAYPAL_URL = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=";
	}
		define("USE_PROXY",false);
		define("version","64");	
	}	 
	 
		// BN Code 	is only applicable for partners
		
			
		/**global $API_Endpoint, $version, $API_UserName, $API_Password, $API_Signature;
		global $USE_PROXY, $PROXY_HOST, $PROXY_PORT;
		global $gv_ApiErrorURL;
		global $sBNCode;**/
		
 
		//setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
	    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
	   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
		if(USE_PROXY)
			curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST. ":" . PROXY_PORT); 

		//NVPRequest for submitting to server
		$nvpreq="METHOD=" . urlencode($methodName) . "&VERSION=" . urlencode(version) . "&PWD=" . urlencode(API_Password) . "&USER=" . urlencode(API_UserName) . "&SIGNATURE=" . urlencode(API_Signature) . $nvpStr . "&BUTTONSOURCE=" . urlencode(sBNCode);

		//var_dump($nvpreq);
		//setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		//getting response from server
		$response = curl_exec($ch);

		//convrting NVPResponse to an Associative Array
		$nvpResArray= $this->deformatNVP($response);
		$nvpReqArray= $this->deformatNVP($nvpreq);
		$_SESSION['nvpReqArray']=$nvpReqArray;

		if (curl_errno($ch)) 
		{
			// moving to display page to display curl errors
			  $_SESSION['curl_error_no']=curl_errno($ch) ;
			  $_SESSION['curl_error_msg']=curl_error($ch);

			  //Execute the Error handling module to display errors. 
		} 
		else 
		{
			 //closing the curl
		  	curl_close($ch);
		}

		return $nvpResArray;
	}

	/*'----------------------------------------------------------------------------------
	 Purpose: Redirects to PayPal.com site.
	 Inputs:  NVP string.
	 Returns: 
	----------------------------------------------------------------------------------
	*/
	public function RedirectToPayPal ( $token )
	{
		global $PAYPAL_URL;
	 
		// Redirect to paypal.com here
		 $payPalURL = PAYPAL_URL . $token;
	
		header("Location: ".$payPalURL);
	}

	
	/*'----------------------------------------------------------------------------------
	 * This function will take NVPString and convert it to an Associative Array and it will decode the response.
	  * It is usefull to search for a particular key and displaying arrays.
	  * @nvpstr is NVPString.
	  * @nvpArray is Associative Array.
	   ----------------------------------------------------------------------------------
	  */
	public function deformatNVP($nvpstr)
	{
		$intial=0;
	 	$nvpArray = array();

		while(strlen($nvpstr))
		{
			//postion of Key
			$keypos= strpos($nvpstr,'=');
			//position of value
			$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

			/*getting the Key and Value values and storing in a Associative Array*/
			$keyval=substr($nvpstr,$intial,$keypos);
			$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
			//decoding the respose
			$nvpArray[urldecode($keyval)] =urldecode( $valval);
			$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
	     }
		return $nvpArray;
	}

}