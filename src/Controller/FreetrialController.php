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
 * @license   http://www.opensource.org/licenses/mit-license.updatephp MIT License
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
use Cake\View\Helper\FlashHelper;
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
use Stripe\Subscription;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class FreetrialController extends AppController
{
     /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
     //public $components = array('PaypalExpressRecurring');
     
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('PaypalExpressRecurring');
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie', ['expiry' => '1 day']);
         $this->Cookie->config('path', '/');
    }
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['logout','login','freeregister','remainvisitor','choosepaymenttype','applypromocode','checkPromocode','paymentpage','states','cities','sendmail','verify','indexnext']);
       
    }
    public function isAuthorized($user)
    { 
         if(isset($user['role']) && $user['role'] === ADMIN){ 
              if (in_array($this->request->action, ['index','deleteduser','suspend','history','compatibility','profile','adduser','editprofile','searchuser','editstatus','changepassword','userlist','userprofile','globalsetting','logout','adduser','delete','edituser'])) { 
                   return true;
              }
         }
         if(isset($user['role']) && $user['role'] === USER){ 
              if (in_array($this->request->action, ['deleteaccount'])) { 
                   return true;
              }
         }
       return false;
    }
    public function freeregister($refferal_codee=null)
    {
        $this->layout='home_page';
        $Userstable=TableRegistry::get('Users');
        $post=$Userstable->newEntity();
        if($refferal_codee){
            setcookie('refferal_code', $refferal_codee, -1, '/');
            $refferal_code = $refferal_codee;
        }else{
            $refferal_code = isset($_COOKIE['refferal_code'])?$_COOKIE['refferal_code']:'';
        }
        $refferal_code  =base64_decode($refferal_code);
        
        $user           =$Userstable->find('all')->where(['refferal_code'=>$refferal_code])->first();
        $uid = $this->Auth->user('id');
        if($uid){
             return $this->redirect($this->Auth->redirectUrl());
        }
        $session = $this->request->Session();
        $showmsg = 1;
        
        $Countriestable=TableRegistry::get('Countries');
        $query = $Countriestable->find('list', ['keyField' => 'id','valueField' => 'name'])->order(['name' => 'ASC']);
        $countries = $query->toArray();
       	$countries	=array("223"=>"United States")+ $countries ;
        $statestbl=TableRegistry::get('States');
        $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
        $states 	= $Statesqery->toArray();
        $cities=[];
        
        if($this->request->is('post')){
            
            $country = $this->request->data['country'];
            $region = $this->request->data['region'];
           
            $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>$country])->order(['name' => 'ASC']);
            $states 	= $Statesqery->toArray();
            
            $citiestbl=TableRegistry::get('Cities');
            $citiesqery = $citiestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['state_id'=>$region])->order(['name' => 'ASC']);
            $cities 	= $citiesqery->toArray();
            if(empty($this->request->data['g-recaptcha-response'])){
                 $this->Flash->error('Please select the captcha.');
                 $showmsg = 2;
            }else
            {
                $email=$this->request->data['email'];
                $user_name=$this->request->data['username'];
                $password=$this->request->data['password'];
                $membershipTable=TableRegistry::get("Memberships");
                $member =$membershipTable->find("all")->where(['slug'=>'platinum'])->first();
                $this->request->data['membership_level']=$member['id'];
                $post=$Userstable->patchEntity($post,$this->request->data,['validate'=>'AddDefault']);
                if(!$post->errors()){
                    $post->created = date("Y-m-d h:i:s");
                    $post->status =INACTIVE;
                    if(!empty($refferal_code)){
                       // die("jhjto");
                        $post->reference_id =$user['id'];
                    }
                    $post->datingsites=serialize($this->request->data['datingsites']);
                    $this->request->data['reference_id']=$user['id'];
                    $session->write("userTempData",$this->request->data);
                    setcookie('refferal_code', null, -1, '/');
                    return $this->redirect(['controller'=>'freetrial','action' => 'choosepaymenttype/',base64_encode('platinum')]);
                }else{
                    foreach ($post->errors() as $key => $value) {
                        $messageerror = [];
                        foreach ($value as $key2 => $value2) {
                            $messageerror[] = $value2;
                        }
                        $errorInputs[$key] = implode(",", $messageerror);
                    }
                    $err=implode(',',$errorInputs);
                    $this->Flash->error($err);
                }
            }
        }
		$tablemembership=TableRegistry::get('Memberships');
		$query2 = $tablemembership->find('list',[
           'keyField' => 'id',
            'valueField' => 'membership_name'])
			 ->where(['status'=>ACTIVE])
            ->order(['id' => 'ASC']);
        $membership = $query2->toArray();
            
        $tablcountrycode=TableRegistry::get('Countrycode');
		$query3 = $tablcountrycode->find('list',[
           'keyField' => 'phonecode',
            'valueField' => 'phonecode'])
            ->order(['phonecode' => 'ASC']);
        $countrycode = $query3->toArray();

      	$table=TableRegistry::get('Datingsites');
        $query = $table->find('list', [
            'keyField' => 'id',
            'valueField' => 'site_name'])
            ->order(['site_name' => 'ASC']);
       	$sites 	= 	$query->toArray();
		array_unshift($sites,"N/A");
        //,,$sites[0] = "N/A";
        //rsort($sites);
        $this->set(compact('post','sites','countries','membership','states','showmsg','cities','countrycode'));
    }
	public function states(){
        $this->autoRender=false;
        $table =TableRegistry::get('states');
        if($this->request->is('post')){
            $countryId = $this->request->data['countryId'];
            $query = $table->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'])
            ->where(['country_id'=>$countryId])
            ->order(['name' => 'ASC']);
            $states = $query->all();
            $states = $states->toArray();
            $i=0;
            $newArray = array();
            foreach($states  as $key=>$states){
                $newArray[$i]['id'] =$key;
                $newArray[$i]['name'] =$states; 	
                    
                $i++;
            }
            $states = json_encode($newArray);
            echo $states;
        }
     exit;
    }
    public function cities(){
        $this->autoRender=false;
        $table =TableRegistry::get('cities');
        if($this->request->is('post')){
            $stateId = $this->request->data['stateId'];
            $query = $table->find('list', [
            'keyField' => 'id',
            'valueField' => 'name'])
            ->where(['state_id'=>$stateId])
            ->order(['name' => 'ASC']);
            $cities = $query->all();
            $cities = $cities->toArray();
            $i=0;
            $newArray = array();
            foreach($cities  as $key=>$city){
                $newArray[$i]['id'] =$key;
                $newArray[$i]['name'] =$city; 	
                $i++;
            }
            $newArray = json_encode($newArray);
            echo $newArray;
        }
     exit;
    }
    function choosepaymenttype($membership=null){
        $this->layout='home_page';
        $membership     =   base64_decode($membership);
        $membershipTable=TableRegistry::get("Memberships");
        $price = 0;
        if($membership == "platinum" || $membership == "lifetime"){
                $membershipAmount   =$membershipTable->find("all")->where(['slug'=>$membership])->first();
                $price              =$membershipAmount["price"];
        }
        $this->set(compact('post','price','membership','user_id','password'));    
    }
    public function remainvisitor(){
        $session=$this->request->session();
        $user_data=$session->read("userTempData");
        $membershipTable=TableRegistry::get("Memberships");
        $userTable=TableRegistry::get("Users");
        $membership   =$membershipTable->find("all")->where(['slug'=>'visitor'])->first();
        $user_data['membership_level']=$membership['id'];
        $user_data['free_survey'] = 0;
                        //save in db
        $post1=$userTable->newEntity();
       // pr($user_data);die;
        $post1=$userTable->patchEntity($post1,$user_data);
        $post1->created = date("Y-m-d h:i:s");
        $post1->status =INACTIVE;
        $post1->datingsites=serialize($user_data['datingsites']);
        $result=$userTable->save($post1);
            $this->Flash->success(__('This User has been saved'));
            $EmailTemplates= TableRegistry::get('Emailtemplates');
            $query = $EmailTemplates->find('all')->where(['slug' => 'user_registration'])->toArray();
            if($query){
             //echo $password;die;
                $activation_link = SITE_URL.'Users/verify/'.base64_encode($result->id);
                $to = $result->email;
                $subject = $query[0]['subject'];
                $message1 = $query[0]['description'];
                $message = str_replace(['{{first_name}}','{{username}}','{{activation_link}}','{{email}}','{{password}}'],[$user_data['first_name'],$user_data['username'],$activation_link,$user_data['email'],$user_data['password']],$message1);
                parent::sendEmail($to, $subject, $message);
                $session->destroy("userTempData");
                $this->Flash->success('Thank you for registering with Self-Match. A mail has been sent to your email address with all the details. Please verify your email address by clicking on available link to access your account.');
                return $this->redirect(['controller'=>'Users','action' => 'login']);
            }
        
    }
    /*  24 march 2017
    public function paymentpage($amount=null){
        $this->layout= 'home_page';
        $session=$this->request->session();
        $user_data=$session->read("userTempData");
        //pr($user_data);die;
        $membership_level = $user_data['membership_level'];
        $amount =base64_decode($amount);
        $Dpassword=$user_data['password'];
        $tableUser=TableRegistry::get("Users");
        $tableMember=TableRegistry::get("Memberships");
        $find=$tableMember->find("all")->where(['id'=>$membership_level])->first();
        require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
            $test_secret_key = Configure::read('stripe_test_secret_key');
            $setApiKey = Stripe::setApiKey($test_secret_key);
            $getApiKey = Stripe::getApiKey();
            if(!empty($getApiKey)){
        if ($this->request->is(['post', 'put'])) {
                try {
                    $getToken = \Stripe\Token::create(
                        array(
                            "card" => array(
                            "number" => $this->request->data['card_number'],
                            "exp_month" => (int)$this->request->data['expiry_month'],
                            "exp_year" => (int)$this->request->data['expiry_year'],
                            "cvc" => $this->request->data['cvv'],
                            "name" => $this->request->data['name'],
                            "address_line1" => $this->request->data['address'],
                            "address_line2" => '',
                            "address_city" => $this->request->data['city'],
                            "address_zip" => $this->request->data['zip'],
                            "address_state" => $this->request->data['state']
                        )));
                    
                    $cop=$session->read("couponcode");
                    $tableCode=TableRegistry::get("Promocodes");
                    $coupons=$tableCode->find("all")->where(['promocode_title'=>$cop])->first();
                    $days=($coupons['duration'])*30;
                    if($cop && ($days==90)){
                        $user_expire = date("Y-m-d",strtotime("+3 month"));
                        $plan      ='platinum_three_month';
                    }else if($cop && ($days==30)){
                        $user_expire = date("Y-m-d",strtotime("+1 month"));
                        $plan    ='platinum_one_month';
                    }else{
                        $user_expire = date("Y-m-d",strtotime("+7 day"));
                        $plan    = 'Platinum';
                    }
					$customer = \Stripe\Customer::create(array(
                        'source'   => $getToken->id,
                        'email'    => 'testingbydev@gmail.com',
                        'plan'     => $plan,
                        'account_balance' => '1000',
                        'description' => "new recurring plan",
                    ));
                    $post1=$tableUser->newEntity();
                    $post1=$tableUser->patchEntity($post1,$user_data);
                    $post1->created = date("Y-m-d h:i:s");
                    $post1->user_expire = $user_expire;
                    $post1->status =INACTIVE;
                    $post1->datingsites=serialize($user_data['datingsites']);
                    $result=$tableUser->save($post1);
                    $savedata['customer_id']           = $customer['id'];
                    $savedata['amount']                = round($amount*100);
					//pr($savedata['amount'] );die;
                    //$savedata['balance_transaction']   =  $charge['balance_transaction'];
                    $savedata['currency']              =  'usd';
                    $savedata['user_id']               =  $result->id;
                    $savedata['membership_level']      =  $membership_level;
                    $savedata['subscription_id']=isset($customer['subscriptions']['data'][0]['id'])?$customer['subscriptions']['data'][0]['id']:"";
                    $savedata['payment_mode']="Stripe";
                    $table  =TableRegistry::get("Payments");
                    $post=$table->newEntity();
                    $post->date   =  date("Y-m-d h:i:s");
                    $post=$table->patchEntity($post,$savedata);
                    if($table->save($post)){
                        $EmailTemplates= TableRegistry::get('Emailtemplates');
                        $query = $EmailTemplates->find('all')->where(['slug' => 'user_registration'])->toArray();
                        if($query){
                            $activation_link = SITE_URL.'Users/verify/'.base64_encode($savedata['user_id']);
                            $to = $result->email;
                            $subject = $query[0]['subject'];
                            $message1 = $query[0]['description'];
                            $message = str_replace(['{{first_name}}','{{username}}','{{activation_link}}','{{email}}','{{password}}'],[$result->first_name,$result->username,$activation_link,$result->email,$Dpassword],$message1);
                            parent::sendEmail($to, $subject, $message);
                            $session->destroy("userTempData");
                            $session->destroy("couponcode");
                            $this->Flash->success('Thank you for registering with Self-Match. A mail has been sent to your email address with all the details. Please verify your email address by clicking on available link to access your account.');
                           return $this->redirect(['controller'=>'Users','action' => 'login']);
                        }
                    }
                }
                catch (\Stripe\Error\Base $e) {
                    //echo $e->getMessage(); die;
                    $this->Flash->error($e->getMessage());
                    $this->redirect(array('controller'=>'Freetrial','action'=>'paymentpage'));
                }
               
                 
               
            }
			
        }
    }

   */
    
      public function paymentpage($amount=null){
       
        $this->layout= 'home_page';
        $session=$this->request->session();
        $user_data=$session->read("userTempData");
        // var_dump($user_data);die;
        $membership_level = $user_data['membership_level'];
        $amount =base64_decode($amount);
        $Dpassword=$user_data['password'];
        $tableUser=TableRegistry::get("Users");
        $tableMember=TableRegistry::get("Memberships");
        // $find=$tableMember->find("all")->where(['id'=>$membership_level])->first();
        require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
            $test_secret_key = Configure::read('stripe_test_secret_key');
            $setApiKey = Stripe::setApiKey($test_secret_key);
            $getApiKey = Stripe::getApiKey();
        if(!empty($getApiKey)){
			if(!empty($amount))
			{
				$strip_amount=base64_decode($amount);
			}
			else
			{
			$strip_amount=9.99;
			}
			if ($this->request->is(['post', 'put'])) {
                try {
                    $getToken = \Stripe\Token::create(
                        array(
                            "card" => array(
                            "number" => $this->request->data['card_number'],
                            "exp_month" => (int)$this->request->data['expiry_month'],
                            "exp_year" => (int)$this->request->data['expiry_year'],
                            "cvc" => $this->request->data['cvv'],
                            "name" => $this->request->data['name'],
                            "address_line1" => $this->request->data['address'],
                            "address_line2" => '',
                            "address_city" => $this->request->data['city'],
                            "address_zip" => $this->request->data['zip'],
                            "address_state" => $this->request->data['state']
                        )));
                    
					$plan = $this->request->query['plan'];
					if($plan=='platinum') {
						$user_data['membership_level'] = $membership_level = 8;
						$amount = $account_balance = 9.99*100;
						$cop=$session->read("couponcode");
						$tableCode=TableRegistry::get("Promocodes");
						$coupons=$tableCode->find("all")->where(['promocode_title'=>$cop])->first();
						$days=($coupons['duration'])*30;
						if($cop && ($days==90)){
							$user_expire = date("Y-m-d",strtotime("+3 month"));
							$plan      ='platinum_three_month';
						}else if($cop && ($days==30)){
							$user_expire = date("Y-m-d",strtotime("+1 month"));
							$plan    ='platinum_one_month';
						}else{
							$user_expire = date("Y-m-d",strtotime("+7 day"));
							$plan    = 'Platinum';
						}
					}
					else {
						$user_data['membership_level'] = $membership_level = 7;
						$amount = $account_balance = 29.99*100;
						$user_expire = date("Y-m-d",strtotime("+7 day"));
					}
					$customer = \Stripe\Customer::create(array(
						'source'   => $getToken->id,
				 
						'email'    => $this->request->data['email'],
						'plan'     => $plan,
						// 'account_balance' => $account_balance,
						'description' => "new recurring plan",
					));
					if($plan=='lifetime') {
						$invoiceitem = \Stripe\InvoiceItem::create(array(
							"customer" => $customer['id'],
							"amount" => $amount,
							"currency" => "usd",
							"description" => "One-time membership fee")
						);
					}
                    $post1=$tableUser->newEntity();
                    $post1=$tableUser->patchEntity($post1,$user_data);
                    $post1->created = date("Y-m-d h:i:s");
                    $post1->user_expire = $user_expire;
                    $post1->status =INACTIVE;
                    $post1->datingsites=serialize($user_data['datingsites']);
                    $result=$tableUser->save($post1);
					
                    $savedata['customer_id']           = $customer['id'];
                    $savedata['amount']                = 0;
                    // $savedata['amount']                = $amount;
					//pr($savedata['amount'] );die;
                    //$savedata['balance_transaction']   =  $charge['balance_transaction'];
                    $savedata['currency']              =  'usd';
                    $savedata['user_id']               =  $result->id;
                    $savedata['membership_level']      =  $membership_level;
                    $savedata['subscription_id']=isset($customer['subscriptions']['data'][0]['id'])?$customer['subscriptions']['data'][0]['id']:"";
                    $savedata['payment_mode']="Stripe";
                    $table  =TableRegistry::get("Payments");
                    $post=$table->newEntity();
                    $post->date   =  date("Y-m-d h:i:s");
                    $post=$table->patchEntity($post,$savedata);
					
					$table1 = TableRegistry::get("UsersStripeBalances");
					$UsersStripeBalancesentity = $table1->newEntity();                    
                    $UsersStripeBalancesentity->customer_id 		= $customer['id'];
                    $UsersStripeBalancesentity->user_id 		        = $result->id;
                    $UsersStripeBalancesentity->balance 	                = $amount;
                    $UsersStripeBalancesentity->created			= date("Y-m-d");

                    if($table->save($post) && $table1->save($UsersStripeBalancesentity)){
                        $EmailTemplates= TableRegistry::get('Emailtemplates');
                        $query = $EmailTemplates->find('all')->where(['slug' => 'user_registration'])->toArray();
                        if($query){
                            $activation_link = SITE_URL.'Users/verify/'.base64_encode($savedata['user_id']);
                            $to = $result->email;
                            $subject = $query[0]['subject'];
                            $message1 = $query[0]['description'];
                            $message = str_replace(['{{first_name}}','{{username}}','{{activation_link}}','{{email}}','{{password}}'],[$result->first_name,$result->username,$activation_link,$result->email,$Dpassword],$message1);
                            parent::sendEmail($to, $subject, $message);
                            $session->destroy("userTempData");
                            $session->destroy("couponcode");
                            $this->Flash->success('Thank you for registering with Self-Match. A mail has been sent to your email address with all the details. Please verify your email address by clicking on available link to access your account.');
                           return $this->redirect(['controller'=>'Users','action' => 'login']);
                        }
                    }
                }
                catch (\Stripe\Error\Base $e) {
                    //echo $e->getMessage(); die;
                    $this->Flash->error($e->getMessage());
                    $this->redirect(array('controller'=>'Freetrial','action'=>'paymentpage'));
                }
               
                 
               
            }
			
        }
    }

  
    
    public function checkPromocode(){
        $this->autoRender = false;
        if($this->request->is(['put','post'])){
            $promocodestable = TableRegistry::get('Promocodes');
            $usertable = TableRegistry::get('Users');
            $promocode  = $this->request->data['promocode'];
            $session = $this->request->session(); 
            $userdata = $session->read('userTempData');
            $plan       = $userdata['membership_level'];
            $getpromocodedata = $promocodestable->find('all')->where(['promocode_title'=>$promocode,'status'=>ACTIVE,'type'=>$plan])->first();
            if($getpromocodedata){
               /* require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
                $test_secret_key = Configure::read('stripe_test_secret_key');
                $setApiKey = Stripe::setApiKey($test_secret_key);
                $getApiKey = Stripe::getApiKey();
                $coupon=\Stripe\Coupon::retrieve($getpromocodedata->promocode_title);*/
                $days=($getpromocodedata->duration)*30;
                //if($coupon){ 
                    $responce['status'] = 1;
                    $session->write("couponcode",$getpromocodedata->promocode_title);
                    $responce['newpriceBaseStrip'] = SITE_URL.'freetrial/paymentpage/';
                    $responce['msg'] = "Promo code applied successfully for free trial of ".$days." days";
                    //return $this->redirect(['controller'=>'freetrial','action' => 'paymentpage']);
                    //die("sjdfh");
                    /*try{
                        $custmor=\Stripe\Subscription::create(array(
                        "customer" => "cus_4fdAW5ftNQow1a",
                        "plan" => "pro-monthly",
                        "coupon" => "free-period",
                      ));
                    }catch(){
                        
                    }*/
                    
               /* }else{
                    $responce['status'] = 2;
                    $responce['msg'] = "Promo code not applied";
                }*/
            }else{
                $responce['status'] = 2;
                $responce['msg'] = "Promocode not valid";
                
            }
        }
        echo  json_encode($responce);
        exit;
    }  
    
    public function verify($id=null){
        $id=base64_decode($id);
		//pr($id);die;
        $userTable = TableRegistry::get('Users');
        $post   = $userTable->find('all')->where(['id'=>$id])->first();	
		if($post->status == ACTIVE){
			$this->Flash->success('Your account is already activated.');
          	return $this->redirect(['controller'=>'Users','action' => 'login']);
		}else{
			$reference_id       = $post->reference_id;
			$membership_level   = $post->membership_level;
			if(!empty($reference_id))
			{
				$affiliate_benifits = TableRegistry::get('Affiliatebenifits');
				$exists =$affiliate_benifits->find("all")->where(['user_id'=>$id,['reference_id'=>$reference_id]])->first();
				if(!$exists){
					$post   =$affiliate_benifits->newEntity();
					$data[] ="";
					$data['user_id']        =$id;
					$data['reference_id']   =$reference_id;
					$data['membership']     =$membership_level;
					$data['status']         =PENDING;
					$post->created	        =date("Y-m-d h:i:s");
					$post=$affiliate_benifits->patchEntity($post,$data);
					$affiliate_benifits->save($post);
				}
			}
			$post->status =ACTIVE;
			if($userTable->save($post)){
				$this->Flash->success('Your account has been activated.');
			  return $this->redirect(['controller'=>'Users','action' => 'login']);
			}
		}
    }
    public function history($id = null){
            $this->layout="admin";
    $id		=	base64_decode($id);
            $paymentTable 	=	TableRegistry::get('payments');
            $post	=	$paymentTable->find('all')->where(['user_id'=>$id])->toArray();
            $this->set('post',$post);
    }
    public function logout($page_id=null)
    {
        $page_id    =   base64_decode("$page_id");
        if($page_id){
             $this->Flash->success(__('Your password has been successfully updated. Please login with the new password.'));
        }
       else{
        //$this->Cookie->delete('remember_me');
        $this->Flash->success(__('Logout successful'));
       }
        return $this->redirect($this->Auth->logout());
    }
  /* public function sendmail(){
    $EmailTemplates= TableRegistry::get('Emailtemplates');
	$query = $EmailTemplates->find('all')->where(['slug' => 'forgot_password'])->toArray();
    if($query){
        $to = 'mohit@mailinator.com';
        $subject = $query[0]['subject'];
        $message1 = $query[0]['description'];
        $message = str_replace(['{{username}}','{{activation_link}}'],['mohit','selmatch'],$message1);
        parent::sendEmail($to, $subject, $message);
    }
     
     die;
   }*/
    
   
}
