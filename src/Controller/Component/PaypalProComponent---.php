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

class PaypalProComponent extends Component {

////////////////////////////////////////////////////////////
	
	public $paypal_username = null;
	public $paypal_password = null;
	public $paypal_signature = null;

	private $paypal_endpoint = 'https://api-3t.paypal.com/nvp';
	private $paypal_endpoint_test = 'https://api-3t.sandbox.paypal.com/nvp';

	public $amount 			= null;
	public $ipAddress 		= '';
	public $creditCardType 		= '';
	public $creditCardNumber 	= '';
	public $creditCardExpires 	= '';
	public $creditCardCvv 		= '';
	
	public $customerFirstName 	= '';
	public $customerLastName 	= '';
	public $customerEmail 		= '';

	public $billingAddress1 	= '';
	public $billingAddress2 	= '';
	public $billingCity 		= '';
	public $billingState 		= '';
	public $billingCountryCode 	= '';
	public $billingZip 		= '';

	protected $_controller 		= null;

/////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function __construct() {
		 
		$this->ipAddress = $_SERVER['REMOTE_ADDR'];
	}
	
	// Change TTD to USD 
	function convertCurrency($amount, $from, $to){
		$url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
		$data = file_get_contents($url);
		preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
		$converted = preg_replace("/[^0-9.]/", "", $converted[1]);
		//return round($converted);
		return number_format(round($converted, 3),2);
	}

	public function doDirectPayment($data) {
		 
		
		// echo $data['TblContestDetail']['creditcard_number']; die;
		//pr($data);die;
		//echo $data['TblContestDetail']['creditcard_month'];
		//echo '<br />';
		// echo $data['TblContestDetail']['creditcard_year']['year'];
		//$testmode=false ;
		
		//$model = ClassRegistry::init('TblPaymentMethod');
		//$configcredit_card = $model->find('first',array('conditions'=>array('slug'=>'credit_card')));
		//
		// if(!empty($configcredit_card)) {		
		//		
		//	Configure::write('App.paypal.username',$configcredit_card['TblPaymentMethod']['username']);
		//	Configure::write('App.paypal.password',$configcredit_card['TblPaymentMethod']['password']);
		//	Configure::write('App.paypal.signature',$configcredit_card['TblPaymentMethod']['secrete_key']);
		//	Configure::write('App.credit_card_paypal.is_test_mode',$configcredit_card['TblPaymentMethod']['is_test_mode']);	
		//}
		
		// $username = trim(Configure::read('App.paypal.username'));
	//	 $password = trim(Configure::read('App.paypal.password'));
	//		  $signature = trim(Configure::read('App.paypal.signature'));
		
		//if( Configure::read('App.credit_card_paypal.is_test_mode') == '1' ){   
		   $testmode = true ;       
		//}
		//ec Configure::read('App.credit_card_paypal.is_test_mode');
		//die;
		//if($testmode) {
			//die;
			//$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			//$this->paypal_username = 'sloganSellers_api1.gmail.com';
			//$this->paypal_password = 'VLFJCZX4EBY3QUU7';
			//$this->paypal_signature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AQJNWgOd90.ig35XwxOGbRpve.lG';
			//$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			//$this->paypal_username = 'Civetta_seller0096_api1.gmail.com';
			//$this->paypal_password = '83P335563GJBPLH2';
			//$this->paypal_signature = 'AynhQMYujlpMv2uje13eUceDVO7IAV1MnXvd5RXuMbaJi05beuM-TePw';
	//	}
	//	else
	//	{
	//		$this->paypal_endpoint = 'https://api-3t.paypal.com/nvp';			
	//		$this->paypal_username = $username;
	//		$this->paypal_password = $password;
	//		$this->paypal_signature = $signature;
			//die;
	//	}
		//$this->initialize();
		
		
		
		if($testmode) {
			//$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			//$this->paypal_username = 'denail.seller_api1.gmail.com';
			//$this->paypal_password = '1406786079';
			//$this->paypal_signature = 'An5ns1Kso7MWUdW4ErQKJJJ4qi4-AXjOhpXqcrBtZUWzZDP0AqA5j7D3';
			
			
			//$this->paypal_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			//$this->paypal_username = 'noto_api1.mailinator.com';
			//$this->paypal_password = 'APKB8HJM222MWVAF';
			//$this->paypal_signature = 'AQU0e5vuZCvSg-XJploSa.sGUDlpA.Pe0gS3UMxG2T4.tABaNyYQfywd';
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
		$doDirectPaymentNvp = array(
			'METHOD' => 'DoDirectPayment',
			'VERSION' => '53.0',
			'PAYMENTACTION' => 'Sale',
			'IPADDRESS' => $this->ipAddress,
			'RETURNFMFDETAILS' => 1,

			'ACCT' => $data['creditcard_number'],
			'EXPDATE' => $data['creditcard_month'].$data['creditcard_year'],
			'CVV2' => $data['creditcard_code'],
			'CREDITCARDTYPE' => 'Visa',
                        
			'FIRSTNAME' => $data['full_name'],
			'EMAIL' => 'dharm@gmail.com',

			'STREET' => 'STREET',
			'STREET2' => '',
			'CITY' => 'jaipur',
			'STATE' => 'Rajasthan',
			'COUNTRYCODE' =>'IN',
			'ZIP' => '302020',
			'AMT' => $data['amount'],
			/* 'ITEMAMT' => $shop['Order']['order_subtotal'], */
			'CURRENCYCODE' => 'USD',

			'USER' => $this->paypal_username,
			'PWD' => $this->paypal_password,
			'SIGNATURE' => $this->paypal_signature,
			'SHIPTONAME' => 'Dharm',
			'SHIPTOSTREET' => 'STREET',
			'SHIPTOCITY' => 'jaipur',
			'SHIPTOCOUNTRY' => 'IN',
			'SHIPTOPHONENUM' => '9529946565'
		);
		
		// pr($shop);die;
		
		        $doDirectPaymentNvp['L_NAME0'] = 'testerrr';
			$doDirectPaymentNvp['L_NUMBER0'] = $data['id'];
			$doDirectPaymentNvp['L_AMT0'] = $data['amount'];
			$doDirectPaymentNvp['L_QTY0'] = 1;
			 
		 
		
		
		//App::uses('HttpSocket', 'Network/Http');
		 
		//$httpSocket = new HttpSocket();
		$httpSocket = new Client();
		$response = $httpSocket->post($this->paypal_endpoint, $doDirectPaymentNvp, ['type' => 'xml']);
		//pr($response->body);
		//die;
		 parse_str($response->body , $parsed);
		 
		   
			  $result = array();
			 // pr($parsed);
			//  die;
			  
		  return json_encode($parsed);
		  
		//	return $parsed;
		////App::uses('HttpSocket', 'Network/Http');
		//$http = new Client();
		////$httpSocket = new HttpSocket();
		//$response = $http->post($this->paypal_endpoint, $doDirectPaymentNvp);
		//
		//parse_str($response , $parsed);
		//	  pr($parsed);die;
		 	//return $parsed;
			
		
	}

////////////////////////////////////////////////////////////

}
