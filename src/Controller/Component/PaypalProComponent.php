<?php

namespace App\Controller\Component;
use Cake\Network\Http\Client;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Mailer\Email;
use Cake\Core\Configure;
use Cake\View\Helper;
use Cake\ORM\Table;
use Cake\I18n\Time;
use Cake\Controller\Controller;
use Cake\Controller\Component\CookieComponent;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Utility\Security;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;

class PaypalProComponent extends Component {

////////////////////////////////////////////////////////////
	
	public $paypal_username = null;
	public $paypal_password = null;
	public $paypal_signature = null;

	private $paypal_endpoint = 'https://api-3t.paypal.com/nvp';
	private $paypal_endpoint_test = 'https://api-3t.sandbox.paypal.com/nvp';

	public $amount = null;
	public $ipAddress = '';
	public $creditCardType = '';
	public $creditCardNumber = '';
	public $creditCardExpires = '';
	public $creditCardCvv = '';
	
	public $customerFirstName = '';
	public $customerLastName = '';
	public $customerEmail = '';

	public $billingAddress1 = '';
	public $billingAddress2 = '';
	public $billingCity = '';
	public $billingState = '';
	public $billingCountryCode = '';
	public $billingZip = '';

	protected $_controller = null;

////////////////////////////////////////////////////////////

	public function __construct() {
		$this->ipAddress = $_SERVER['REMOTE_ADDR'];
	}
	
	// Change TTD to USD 
	function convertCurrency($amount, $from, $to){
		$url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
		$data = file_get_contents($url);
		preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
		$converted = preg_replace("/[^0-9.]/", "", $converted[1]);
		return round($converted);
		//return number_format(round($converted, 3),2);
	}

	
	
	
	
	public function doDirectPaymentNew($data) {
		//pr($user);pr($data);die;
		//$testmode=false ;
		//$data['year'];
		if( Configure::read('App.PaypalAccountMode') == Configure::read('Paypal.mode.live') ){   
		  $testmode = true ;       
		}
		 
		 //echo $testmode; die;
		if($testmode) {
			
			//$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			//$this->paypal_username = 'denail.seller_api1.gmail.com';
			//$this->paypal_password = '1406786079';
			//$this->paypal_signature = 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AXjOhpXqcrBtZUWzZDP0AqA5j7D3';
			
			
			//$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			//$this->paypal_username = 'noto_api1.mailinator.com';
			//$this->paypal_password = 'APKB8HJM222MWVAF';
			//$this->paypal_signature = 'AQU0e5vuZCvSg-XJploSa.sGUDlpA.Pe0gS3UMxG2T4.tABaNyYQfywd';
			/*$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$this->paypal_username = 'ssrock78-facilitator_api1.gmail.com';
			$this->paypal_password = 'J5HZH7G73U9YJK8L';
			$this->paypal_signature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AOHgrSEui2AbMbQBV8I3uqvVRUXK';*/
			
			$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$this->paypal_username = 'testingbydev-developer_api1.gmail.com';
			$this->paypal_password = '1401176928';
			$this->paypal_signature = 'AdVLAZiQe0jRM6jFpMrpsB2oBdMxAUG-WFf4Z5DIX0mwUkZgQ0YA9IjM';
			
		}
		else
		{  
			$this->paypal_endpoint = 'https://api-3t.paypal.com/nvp';
			$this->paypal_username = 'info_api1.yink.com';
			$this->paypal_password = '6JVCUP4ZSXJDK54PA';
			$this->paypal_signature = 'AFcWxV21C7fd0vA3bYYYRCpSSRl31ABnSk-jS9EhksjEJS10ivO1hz6Mv';
		}
		

// Convert timezone
//$this->initialize();
		$doDirectPaymentNvp = array(
			'METHOD'           => 'DoDirectPayment',
			'VERSION'          => '53.0',
			'PAYMENTACTION'    => 'Sale',
			'IPADDRESS'        => $this->ipAddress,
			'RETURNFMFDETAILS' => 1,

			'ACCT'             => $data['creditcard_number'],
			'EXPDATE'          => $data['creditcard_month'].$data['creditcard_year'],
			'CVV2'             => $data['creditcard_code'],
			'CREDITCARDTYPE'   => 'Visa',

			'FIRSTNAME' => 'hello',
			'LASTNAME' => 'hi',
			'EMAIL' => 'abc@gmail.com',

			'STREET' => 'jaipur',
			
			'CITY' => 'Jaipur',//$user['city'],
			'STATE' => 'Rajasthan',//$user['state'],
			'COUNTRYCODE' => 'IN',//$user['country'],
			'ZIP' => '302018',//$user['zip'],
			'AMT' => 20,//$user['User']['order_total'],
			 
			'CURRENCYCODE' => 'USD',
			'USER' => $this->paypal_username,
			'PWD' => $this->paypal_password,
			'SIGNATURE' => $this->paypal_signature,
			 //$date = date_default_timezone_set('America/New_York'),
			
		);
		
		/*$nvp_string = '';
                foreach($doDirectPaymentNvp as $var=>$val)
		{
		    $nvp_string .= '&'.$var.'='.urlencode($val);   
		}
		
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_URL, $this->paypal_endpoint);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $nvp_string);
 
$result = curl_exec($curl);    
curl_close($curl);
pr($result); die;
return $nvp_response_array = parse_str($result);*/
		/*echo $data['creditcard_number'];
		echo $data['mnth'].$data['year'];
		echo $data['creditcard_code'];
		echo $user['first_name'];
		//echo $user['last_name'];
		echo $user['email'];
		echo $user['address'];*/
		
		//echo $this->paypal_endpoint;
		//pr($doDirectPaymentNvp);die;
		
		
		$http = new Client();
		$response = $http->post($this->paypal_endpoint, $doDirectPaymentNvp);
		
		
		$body = $response->body();
	     // return $body;
		 //die;

		parse_str($body,$parsed);
			// pr($parsed);die;
			return $parsed;
			 	
			
		
	}

////////////////////////////////////////////////////////////

}
