<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;
use App\Controller\AppController;
use App\Controller\ConnectionManager;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\View\Helper\SessionHelper;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Network\Request;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Security;
use Cake\View\Helper;
use App\Controller\DateTime;
use Cake\I18n\Time;
use Cake\Controller\Component\AuthComponent;
use Cake\Controller\Component\CookieComponent;
use Stripe\Stripe;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PaypalController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        //$this->loadComponent('Session');
        $this->loadComponent('Flash');
        $this->loadComponent('PaypalExpressRecurring');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Cookie', ['expiry' => '1 day']);
        
    }
    
    public function beforeFilter(Event $event)
    {
         parent::beforeFilter($event);
         $this->Auth->allow(['ipnresponse','updatesubscriptionplan','changesubscriptionstatus','review','home','faq','selfmatchdemo','contactus','aboutus','details','questionbank','searchQuestion','questionsdetails','guideline','help','view','advertisement','addquestion']);
       
    }
    public function isAuthorized($user)
    { 
          if(isset($user['role']) && $user['role'] === ADMIN){ 
               if (in_array($this->request->action, ['index','profile','changepassword','compatibilityreport','userlist','globalsetting','logout','adduser'])) { 
                    return true;
               }
          }
          if(isset($user['role']) && $user['role'] === USER){ 
               if (in_array($this->request->action, ['ipnresponse','updatesubscriptionplan','changesubscriptionstatus','review','paymentPaypal','submit','create','savedsurvey','deleteReport','deletepartner','editreceiver','mycompatibilityreports','upgradepayment','choosepaymenttypeforsurvey','downgradepayment','paymentPaypal1','cancel','updatemembership','survey','usermembership','checkPromocode','choosepaymenttype','mysurvey','paymenthistory','payment','deleteSurvey','memberdashboard','manageprofile','changepassword','favouritelist','choosesurvey',
                                                     'addsurvey','addfavorite','submissionform','deleteaccount',
                                                     'editoccupation','aftersubmission','savesurvey','paymentpage','sendsurvey','compatibilityreport','feedback','randomquestions','randomquestionchallenge','addquestion','removequestion'])) { 
                    return true;
               }
          }
        return false;
    }
    
    
    public function ipnresponse()
    {
	$this->log($_REQUEST, 'debug');
	die('IPN Response');
    }

    public function review()
    {
        $this->loadModel('Users');
        $id = $this->Auth->user('id');
        $session = $this->request->session();
        $FinalPaymentAmt =  $_SESSION["Payment_Amount"];
        $description =  $_SESSION["Payment_Description"];
        
        if (isset($_REQUEST['token']))
        {
        $token = $_REQUEST['token'];
        $payerId = $_REQUEST['PayerID'];
        }
	 
        $resArray = $this->PaypalExpressRecurring->CreateRecurringPaymentsProfile($FinalPaymentAmt,$token,$payerId,$description);
        $ack = strtoupper($resArray["ACK"]);
   pr($resArray); die;
	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
	{
            $PROFILEID = $resArray['PROFILEID'];

            $transactionId		= isset($resArray["TRANSACTIONID"]) ? $resArray["TRANSACTIONID"] : ''; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
            $transactionType 	= isset($resArray["TRANSACTIONTYPE"]) ? $resArray["TRANSACTIONTYPE"] : '' ; //' The type of transaction Possible values: l  cart l  express-checkout 
            $paymentType		= isset($resArray["PAYMENTTYPE"]) ? $resArray["PAYMENTTYPE"] : '' ;  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
            $orderTime 			= isset($resArray["ORDERTIME"]) ? $resArray["ORDERTIME"] : '';  //' Time/date stamp of payment
            $amt				= isset($resArray["AMT"]) ? $resArray["AMT"] : '';  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
            $currencyCode		= isset($resArray["CURRENCYCODE"]) ? $resArray["CURRENCYCODE"] : '';  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
            $feeAmt				= isset($resArray["FEEAMT"]) ? $resArray["FEEAMT"] : '';  //' PayPal fee amount charged for the transaction
            $settleAmt			= isset($resArray["SETTLEAMT"]) ? $resArray["SETTLEAMT"] : '';  //' Amount deposited in your PayPal account after a currency conversion.
            $taxAmt				= isset($resArray["TAXAMT"]) ? $resArray["TAXAMT"] : '';  //' Tax charged on the transaction.
            $exchangeRate		= isset($resArray["EXCHANGERATE"]) ? $resArray["EXCHANGERATE"] : '';  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their 
            
            $paymentStatus	= isset($resArray["PAYMENTSTATUS"]) ? $resArray["PAYMENTSTATUS"] : ''; 
            $pendingReason	= isset($resArray["PENDINGREASON"]) ? $resArray["PENDINGREASON"] : '';  
            $reasonCode		= isset($resArray["REASONCODE"]) ? $resArray["REASONCODE"] : ''; 
            
            $plan_id = $session->read('plan_id');
            
            $query=$this->Users->query();
            //$query->update()->set(['plan_id'=>$plan_id,'tx_id'=>$PROFILEID])->where(['id'=>$id])->execute();
            $this->Flash->success('Thank you for your payment.');
            return $this->redirect(['controller'=>'pages','action'=>'home']);
	}
	else  
	{
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		
		echo "GetExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;
		$this->Flash->success('Something error !.');
		 return $this->redirect(['action'=>'index']);
	}
    }
    
    public function changesubscriptionstatus()
    { 
        $this->loadModel('Users');
        $id = $this->Auth->user('id');
        $session = $this->request->session();  
        $profile_id = 'I-CTVSVE0W56MB';
        $action = 'Cancel'; //Cancel Suspend Reactivate
        $resArray = $this->PaypalExpressRecurring->ManageRecurringPaymentsProfileStatus($profile_id,$action);
        $ack = strtoupper($resArray["ACK"]);
        pr($resArray); die;
        if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
        {
                $PROFILEID = $resArray['PROFILEID'];
            $PROFILESTATUS = $resArray['PROFILESTATUS'];
            $TIMESTAMP = $resArray['TIMESTAMP'];
            $CORRELATIONID = $resArray['CORRELATIONID'];
            $VERSION = $resArray['VERSION'];
            $BUILD = $resArray['BUILD']; 
                //$plan_id = $session->read('plan_id');
                
                //$query=$this->Users->query();
                //$query->update()->set(['plan_id'=>$plan_id,'tx_id'=>$PROFILEID])->where(['id'=>$id])->execute();
                $this->Flash->success('Thank you for your payment.');
                return $this->redirect(['controller'=>'pages','action'=>'home']);
        }
        else  
        {
            //Display a user friendly Error on the page using any of the following error information returned by PayPal
            $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
            $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
            $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
            $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
            
            echo "GetExpressCheckoutDetails API call failed. ";
            echo "Detailed Error Message: " . $ErrorLongMsg;
            echo "Short Error Message: " . $ErrorShortMsg;
            echo "Error Code: " . $ErrorCode;
            echo "Error Severity Code: " . $ErrorSeverityCode;
            $this->Flash->success('Something error !.');
             return $this->redirect(['action'=>'index']);
        }
    }
    public function updatesubscriptionplan()
    {  
        $this->loadModel('Users');
        $id = $this->Auth->user('id');
        $session = $this->request->session();  
        $profile_id = 'I-K77PXVARBLXB';
        $paymentAmount = 5; //Cancel Suspend Reactivate
        $resArray = $this->PaypalExpressRecurring->UpdateRecurringPaymentsProfile($profile_id,$paymentAmount);
        $ack = strtoupper($resArray["ACK"]);
        pr($resArray); die;
        if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
        {
            $PROFILEID = $resArray['PROFILEID'];
            $PROFILESTATUS = $resArray['PROFILESTATUS'];
            $TIMESTAMP = $resArray['TIMESTAMP'];
            $CORRELATIONID = $resArray['CORRELATIONID'];
            $VERSION = $resArray['VERSION'];
            $BUILD = $resArray['BUILD']; 
            //$plan_id = $session->read('plan_id');
            
            //$query=$this->Users->query();
            //$query->update()->set(['plan_id'=>$plan_id,'tx_id'=>$PROFILEID])->where(['id'=>$id])->execute();
            $this->Flash->success('Thank you for your payment.');
            return $this->redirect(['controller'=>'pages','action'=>'home']);
        }
        else  
        {
            //Display a user friendly Error on the page using any of the following error information returned by PayPal
            $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
            $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
            $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
            $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
            
            echo "GetExpressCheckoutDetails API call failed. ";
            echo "Detailed Error Message: " . $ErrorLongMsg;
            echo "Short Error Message: " . $ErrorShortMsg;
            echo "Error Code: " . $ErrorCode;
            echo "Error Severity Code: " . $ErrorSeverityCode;
            $this->Flash->success('Something error !.');
             return $this->redirect(['action'=>'index']);
        }
    }
      
}
