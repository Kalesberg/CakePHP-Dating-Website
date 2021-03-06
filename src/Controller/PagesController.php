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
use Stripe\Subscription;
use App\Database\Expression\BetweenComparison;


/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController
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
         $this->Auth->allow(['siteMap','stripeWebhook','home','checkmembership','faq','selfmatchdemo','contactus','aboutus','details','questionbank','searchQuestion','questionsdetails','guideline','help','view','advertisement','addquestion']);
       
    }
    public function isAuthorized($user)
    { 
          if(isset($user['role']) && $user['role'] === ADMIN){ 
               if (in_array($this->request->action, ['index','profile','changepassword','compatibilityreport','userlist','globalsetting','logout','adduser'])) { 
                    return true;
               }
          }
          if(isset($user['role']) && $user['role'] === USER){ 
               if (in_array($this->request->action, ['paymentPaypal','advertisementver','cardupdate','applypromocode','upgrade','submit','create','savedsurvey','deleteReport','deletepartner','editreceiver','mycompatibilityreports','upgradepayment','choosepaymenttypeforsurvey','downgradepayment','paymentPaypal1','cancel','updatemembership','survey','usermembership','checkPromocode','choosepaymenttype','mysurvey','paymenthistory','payment','deleteSurvey','memberdashboard','manageprofile','changepassword','favouritelist','choosesurvey',
                                                     'addsurvey','addfavorite','submissionform','deleteaccount',
                                                     'editoccupation','aftersubmission','savesurvey','paymentpage','sendsurvey','compatibilityreport','feedback','randomquestions','randomquestionchallenge','addquestion','removequestion'])) { 
                    return true;
               }
          }
        return false;
    }
    public function home(){
        $this->layout='home_page';
    }
    public function contactus(){
        $this->layout='home_page';
        $table = TableRegistry::get('Contactus');
        $post=$table->newEntity();
        if($this->request->is(['post','put']))
        {
            $post=$table->patchEntity($post,$this->request->data);
            $post->created = date("Y-m-d h:i:s");
            $username=$this->request->data['first_name']." ".$this->request->data['last_name'];
            $useremail=$this->request->data['email'];
            $enquiry=$this->request->data['enquiry'];
            $usermessage =$this->request->data['message'];
            if($table->save($post))
            {
               
                $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'contact_inquiry_mail'])->toArray();
              
                if($query){
                    //$activation_link = SITE_URL.'Questions/questionlist/';
                    
                    
                    $globalsettingsTbl=TableRegistry::get("Globalsettings");
                    $GlobalsettingsData =$globalsettingsTbl->find("all")->where(['slug'=>"support_email"])->first();
                   
                   
                    $to = $GlobalsettingsData['value'];
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{from_user_name}}','{{from_user_email}}','{{from_user_message}}','{{inquiry_type}}'],[$username,$useremail,$usermessage,$enquiry],$message1);
                   
                    parent::sendEmail($to, $subject, $message);
                }
                $this->Flash->success(__('Your message has been sent.'));
                return $this->redirect(array('action' => 'contactus'));
            }
            else
            {
                foreach($post->errors() as $key => $value){
                    $messageerror = [];
                    foreach($value as $key2 => $value2){
                        $messageerror[] = $value2;
                        foreach($messageerror as $err)
                        {
                            $this->Flash->error(__($err));
                            //return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
                        }
                    }
                }
            }
        }
        $table1=TableRegistry::get('Countries');
		$query1 = $table1->find('list', [
		'keyField' => 'id',
		'valueField' => 'name'])
		->order(['name' => 'ASC']);
		$countries 	= $query1->toArray();
		$countries	=array("223"=>"United States")+ $countries ;
        $statestbl=TableRegistry::get('States');
		$Statesqery = $statestbl->find('list', [
		'keyField' => 'id',
		'valueField' => 'name'])->where(['country_id'=>'223'])
		->order(['name' => 'ASC']);
		$states 	= $Statesqery->toArray();
        $this->set(compact('post','countries','states'));
    }
    public function memberdashboard()
    {
        $this->layout='home_page';
        $id=$this->Auth->user('id');
        //pr($id);die;
        $userTable=TableRegistry::get('Users');
        $post=$userTable->find('all')->where(['id'=>$id])->first();
        $this->set('post',$post);
    }
    public function editreceiver($user_id = null,$remail=null,$survey_id= null){
        $this->layout='home_page';
        $user_id    =base64_decode($user_id);
        $remail     =base64_decode($remail);
        $survey_id  =base64_decode($survey_id);
        $table=TableRegistry::get('Receivers');
        $post=$table->find('all')->where(['email'=>$remail,'user_id'=>$user_id,'survey_id'=>$survey_id])->first();
           
        //pr($cities);die;
        if($this->request->is(['post','put'])){
            $data=$this->request->data;
            $post=$table->patchEntity($post,$data,['validate' => 'Default']);
            // pr($post);die;
            if(!empty($this->request->data['profile_photo']['name'])){
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG'){
                   $imagePath=time().$imagename;
					$filepath = getcwd() . '/img/user_images/' .$imagePath;
                    $post->profile_photo =$imagePath ;
                    $post->modified = date("Y-m-d h:i:s");
                    if($table->save($post)){
                        if(!empty($imagename)){ 
                            move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
                            chmod($filepath, 0777);
                        }
                        $this->Flash->success(__('Your profile has been updated.'));
                        return $this->redirect(array('action' => 'editreceiver',base64_encode($user_id),base64_encode($remail),base64_encode($survey_id)));
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
                           // return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
                                    
                        }
                        
                }else{
                    $this->Flash->error("Please upload only png,jpg type file.");
                }
                //return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
            }else
            {
                
                $query=$table->query();
                $query->update()
                ->set(['name'=>!empty($this->request->data['name'])?$this->request->data['name']:"",
                       'age'=>!empty($this->request->data['age'])?$this->request->data['age']:"",
                       'occupation'=>!empty($this->request->data['occupation'])?$this->request->data['occupation']:"",
                       'country'=>$this->request->data['country'],
                       'region' =>$this->request->data['region'],
                       'city'   =>!empty($this->request->data['city'])?$this->request->data['city']:"",
                       //'profile_photo' =>$this->request->data['profile_photo']['name'],
                       'modified'=>date("Y-m-d h:i:s")])
                ->where(['email'=>$remail])
                ->execute();
                if($query){
                    $this->Flash->success(__('Profile has been updated.'));
                   return $this->redirect(array('action' => 'editreceiver',base64_encode($user_id),base64_encode($remail),base64_encode($survey_id)));
                }
                else{
                    foreach($post->errors() as $key => $value){
                        $messageerror = [];
                        foreach($value as $key2 => $value2){
                            $messageerror[] = $value2;
                            foreach($messageerror as $err)
                            {
                                $this->Flash->error(__($err));
                                //return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
                            }
                        }
                    }
                }
            }
            }
        $Countriestable=TableRegistry::get('Countries');
        $query = $Countriestable->find('list', ['keyField' => 'id','valueField' => 'name'])->order(['name' => 'ASC']);
        $countries = $query->toArray();
       	$countries	=array("223"=>"United States")+ $countries ;
        
        
        if(isset($post->country) && isset($post->region) && isset($post->city)){
            $country = $post->country;
            $region = $post->region;
            $statestbl=TableRegistry::get('States');
            $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>$country])->order(['name' => 'ASC']);
            $states 	= $Statesqery->toArray();
            
            $citiestbl=TableRegistry::get('Cities');
            $citiesqery = $citiestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['state_id'=>$region])->order(['name' => 'ASC']);
            $cities 	= $citiesqery->toArray();
        }else{
             $statestbl=TableRegistry::get('States');
            $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
            $states 	= $Statesqery->toArray();
            $states 	=array("3435"=>"Alabama") + $states ;
          
            $citiestbl  =TableRegistry::get('Cities');
            $Citiesqery = $citiestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['state_id'=>'3435','con_id'=>'223'])->order(['name' => 'ASC']);
            
            $cities     = $Citiesqery->toArray();
        }
        
        
        
       
        $this->set(compact('post','countries','states','cities'));

    }
      public function paymenthistory(){
        $this->layout='home_page';
        $id =   $this->Auth->user('id');
        
        //$id		=	base64_decode($id);
        $paymentTable 	=	TableRegistry::get('payments');
        // $post	=	$paymentTable->find('all')->where(['user_id'=>$id,'amount !=' =>0])->toArray();
        $post	=	$paymentTable->find('all')->where(['user_id'=>$id])->toArray();
        
        $tableUsers =TableRegistry::get('Users');
        
        $user_membership = $tableUsers->find('all')->where(['id'=>$id])->first();
        $membership =TableRegistry::get('Memberships');
        $membershipDetails= $membership->find('all')->where(['id'=>$user_membership['membership_level']])->first();
        
        $UsersStripeBalancesTbl =TableRegistry::get('UsersStripeBalances');
        $query = $UsersStripeBalancesTbl->find('list', ['keyField' => 'id','valueField' => 'balance'])->where(['user_id'=>$id])->toArray();
        $balances = "$0";
        if($query){
            $balances = array_sum($query);
            if($balances){
                $balances = $balances/100;
                if($balances < 0){
                    // $balances = str_replace(['-','+'],[''],$balances);
                    // $balances = '-$'.$balances;
					$balances = '-$' . abs($balances);
                }else{
                    // $balances = str_replace(['-','+'],[''],$balances);
                    // $balances = '$'.$balances;
					$balances = '$' . $balances;
                }
            }
        }
        $this->set(['post'=>$post,"membershipDetails"=>$membershipDetails,"balances"=>$balances]);
    }
    public function view(){
        $this->layout='home_page';
        $table = TableRegistry::get('Cmspages');
		$path = func_get_args();
        $slug = str_replace('-','_',$path[0]);
		$query =$table->find('all')->where(['slug' => $slug]);
		$data = $query->first();
		
		$title = isset($data->title)?$data->title:'';
		$this->set('data',$data);
		$this->set('title',$title);
    }
   
    public function guideline()
    {
        $this->layout='home_page';
    }
    public function choosesurvey($id=null){
        $this->layout='home_page';
        $id=base64_decode($id);
        $table=TableRegistry::get("Users");
        if($id=='1'){
            $user_id=$this->Auth->user('id');
            $post=$table->find('all')->where(['id'=>$user_id])->toArray();
            $free_survey=$post[0]['free_survey'];
            //pr($survey_type);die;
            if($free_survey =='0'){
                $user_id =$this->Auth->user('id');
                $query = $table->query();
                $query->update()
                ->set(['survey_type' =>'1'])
                ->where(['id' => $user_id])
                ->execute();
                $this->Flash->success('Create and send your FREE survey.');
                return $this->redirect(array('controller'=>'Pages','action'=>'questionbank'));
            }else{
                    $user_id=$this->Auth->user('id');
                    $query = $table->query();
                    $query->update()
                    ->set(['survey_type' =>'1'])
                    ->where(['id' => $user_id])
                    ->execute();
                  return $this->redirect(['controller'=>'Pages','action'=>'questionbank']);
               // $this->Flash->error('Please upgarde your membership or pay for survey.');
               // return $this->redirect(['controller'=>'Pages','action'=>'paymentpage/'.base64_encode("$.99")]);
            }
        }if($id=='2'){
            $user_id=$this->Auth->user('id');
            $query = $table->query();
			$query->update()
				->set(['survey_type' =>'2'])
				->where(['id' => $user_id])
				->execute();
                return $this->redirect(['controller'=>'Pages','action'=>'questionbank']);
               // $this->Flash->error('Please upgarde your membership or pay for survey.');
                //return $this->redirect(['controller'=>'Pages','action'=>'paymentpage/'.base64_encode("$2.99")]);
        }
    }
    public function paymentpage($amount=null,$survey_id=null,$page_id=null){
        $this->layout= 'home_page';
        $user_id=$this->Auth->user('id');
        $amountOld = $amount;
        $survey_idOld = $survey_id;
        $amount =base64_decode($amount);
        $session=$this->request->session();
        $page_id=base64_decode($page_id);
        $membershipTable=TableRegistry::get("Memberships");
        $membershipAmount		=$membershipTable->find("all")->where(['price'=>$amount])->first();
        $Countriestable=TableRegistry::get('Countries');
        $query = $Countriestable->find('list', ['keyField' => 'id','valueField' => 'name'])->order(['name' => 'ASC']);
        $countries = $query->toArray();
       	$countries	=array("223"=>"United States")+ $countries ;
        
        $statestbl=TableRegistry::get('States');
        $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
       
        $states 	= $Statesqery->toArray();
	
        $cities=[];
       /* if($page_id=="upgrade" && $membershipAmount['slug']=='platinum'){
            $user_membership=$this->Auth->user('membership_level');
            $membershipAmountOld		=$membershipTable->find("all")->where(['id'=>$user_membership])->first();
            $amountold        =$membershipAmountOld['price'];
            $amountupdate     =$amount+(($amount-$amountold)/2);
            $amount=$amountupdate;
        }else if($page_id=="downgrade" && $membershipAmount['slug']=='gold'){
            $user_membership=$this->Auth->user('membership_level');
            $membershipAmountOld		=$membershipTable->find("all")->where(['id'=>$user_membership])->first();
            $amountold  =$membershipAmountOld['price'];
            $amountupdate     =$amount-(($amountold-$amount)/2);
            $amount=$amountupdate;
        }else{
             $amount=$amount;
        }*/
        $survey_id=base64_decode($survey_id);
        require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
        $test_secret_key = Configure::read('stripe_test_secret_key');
        $setApiKey = Stripe::setApiKey($test_secret_key);
        $getApiKey = Stripe::getApiKey();
        if(!empty($getApiKey)){
        if ($this->request->is(['post', 'put'])) {
            try{
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
               
            }catch (\Stripe\Error\Base $e) {
               
                $this->Flash->error($e->getMessage());
               return $this->redirect(array('controller'=>'pages','action'=>'paymentpage',$amountOld,$survey_idOld));
               
            }
            try {
                
                $charge = \Stripe\Charge::create(array(
                  //  Math.round(9.95*100); 
                  "amount" =>round($amount*100),
                    // Amount in cents
                  "currency" => "USD",
                  "source" => $getToken->id,
                  "description" => "Plan charge"
                  ));
                $savedata['customer_id']         =  $charge['id'];
                $savedata['amount']              =  $charge['amount'];
                //pr($savedata['amount'] );die;
                $savedata['balance_transaction'] =  $charge['balance_transaction'];
                $savedata['currency']            =  $charge['currency'];
                $savedata['user_id']             =  $user_id;
                $savedata['membership_level']    =  $membershipAmount['id'];
                $savedata['payment_mode']="Stripe";
                //pr($savedata);die;
                $userTable	  =TableRegistry::get("Users");
                $user_id=$this->Auth->user('id');
                   // echo $page_id;die;
                    /*if($page_id=="upgrade" || $page_id=="downgrade"){
                        if(!empty($membershipAmount)){ 
                            $query = $userTable->query();
                            $query->update()
                                    ->set(['membership_level' =>$membershipAmount['id']])
                                    ->where(['id' => $user_id])
                                    ->execute();
                                $table  =TableRegistry::get("Payments");
                                $post=$table->newEntity();
                                $post->date                 =  date("Y-m-d h:i:s");
                                $post=$table->patchEntity($post,$savedata);
                                if($table->save($post)){
                                    if($session->read("survey_id")){
                                        $EmailTemplates= TableRegistry::get('Emailtemplates');
                                        $query = $EmailTemplates->find('all')->where(['slug' => 'send_survey'])->toArray();
                                        if($query){
                                            $usertype="receiver";
                                           // $activation_link=" ";
                                            $email          =$session->read("receiverEmail"); 
                                            $name           =$session->read("receiverName");
                                            $messageReceiver=$session->read("receiverMessage");
                                            $activation_link = SITE_URL.'Receivers/Survey/'.base64_encode($session->read("survey_id"))."/".base64_encode($usertype)."/".base64_encode($email);
                                            $to = $email;
                                            $subject = $query[0]['subject'];
                                            $message1 = $query[0]['description'];
                                            $message = str_replace(['{{username}}','{{activation_link}}','{{sender}}','{{Message to the receiver}}'],[$name,
                                            $activation_link,$this->Auth->user('first_name')." ".$this->Auth->user('last_name'),$messageReceiver],$message1);
                                            parent::sendEmail($to, $subject, $message);
                                            $session->read("receiverEmail","");
                                            $session->read("receiverName","");
                                            $session->read("receiverMessage","");
                                            $session->read("survey_id","");
                                            $this->Flash->success('The payment was successful.');
                                            return $this->redirect(array('controller'=>'Pages','action' => 'memberdashboard'));
                                       }
                                    }else{
                                        $this->Flash->success('Your plan has been updated successfully.');
                                        return $this->redirect(array('controller'=>'Pages','action' => 'usermembership'));
                                    }
                                }
                                
                        }else{
                            $membershipvisitor=$membershipTable->find("all")->where(['price'=>'0'])->first();
                            $query = $userTable->query();
                            $query->update()
                                    ->set(['membership_level' =>$membershipvisitor['id']])
                                    ->where(['id' => $user_id])
                                    ->execute();
                        }
                    }else{*/
                        if($survey_id){
                            $savedata['survey_id']             =  $survey_id;
                        }
                        $table  =TableRegistry::get("Payments");
                        $post=$table->newEntity();
                        $post->date                 =  date("Y-m-d h:i:s");
                        $post=$table->patchEntity($post,$savedata);
                        if($table->save($post)){
                            $EmailTemplates= TableRegistry::get('Emailtemplates');
                            $query = $EmailTemplates->find('all')->where(['slug' => 'send_survey'])->toArray();
                            if($query){
                                $usertype="receiver";
                               // $activation_link=" ";
                                $email          =$session->read("receiverEmail");
                                $name           =$session->read("receiverName");
                                $messageReceiver=$session->read("receiverMessage");
                                $activation_link = SITE_URL.'Receivers/Survey/'.base64_encode($survey_id)."/".base64_encode($usertype)."/".base64_encode($email);
                                $to = $email;
                                $subject = $query[0]['subject'];
                                $message1 = $query[0]['description'];
                                $message = str_replace(['{{username}}','{{activation_link}}','{{sender}}','{{Message to the receiver}}'],[$name,
                                $activation_link,$this->Auth->user('first_name')." ".$this->Auth->user('last_name'),$messageReceiver],$message1);
                                
                                parent::sendEmail($to, $subject, $message);
                                $session->read("receiverEmail","");
                                $session->read("receiverName","");
                                $session->read("receiverMessage","");
                                $session->read("survey_id","");
                                $this->Flash->success('The payment was successful. Your survey is on the way. You will be notified immediately after your partner completes the survey.');
                                return $this->redirect(array('controller'=>'Pages','action' => 'questionbank'));
                            }
                        }
                   // }
                }catch(\Stripe\Error\Card $e) {
                    $error = $e->getMessage();
                    $token_id = '';
                    $this->Flash->error($error);
                }
            }
            
            $this->set(compact("countries","states","cities"));
        }
    }
    public function upgrade($membership=null,$amount=null){
        $this->layout='home_page';
        $user_id=$this->Auth->user('id');
        $session=$this->request->session();
        $userTable	  =TableRegistry::get("Users");
        $membershipTable=TableRegistry::get("Memberships");
        $amount=base64_decode($amount);
        $membership=base64_decode($membership);
        $membershipId=$membershipTable->find("all")->where(['slug'=>$membership])->first();
        $tablePayments  =TableRegistry::get("Payments");
        $filter=[];
        $filter['membership_level IN']=[8,7];
        $filter['user_id']=$user_id;
        $customer=$tablePayments->find("all")->where(['user_id'=>$user_id,$filter])->order(['id'=>'DESC'])->first();
        if($membership=='lifetime'){
            $plan='lifetime_notrial';
        }elseif($membership=='platinum'){
            $plan='platinum_pro';
        }
        require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
        $test_secret_key = Configure::read('stripe_test_secret_key');
        $setApiKey = Stripe::setApiKey($test_secret_key);
        $getApiKey = Stripe::getApiKey();
        $cop=$session->read("couponcode");
        if(!empty($getApiKey)){
            if($this->request->is(['post', 'put'])){
                try{
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
                }catch (\Stripe\Error\Base $e) {
                    $this->Flash->set($e->getMessage(),['params'=>['class' => 'alert danger']]);
                }
              
                if($customer['payment_mode']=='Stripe' && $customer['customer_id']){
                    try{
                        if($cop){
                            $subscription = \Stripe\Customer::retrieve($customer['customer_id']);
                            $subscription->plan = $plan;
							// $subscription->account_balance = $amount*100;
                            $subscription->coupon=$cop;
                            $subscription->save();
                        }else{
                            $subscription = \Stripe\Customer::retrieve($customer['customer_id']);
                            $subscription->plan = $plan;
							// $subscription->account_balance = $amount*100;
                            $subscription->save();
                        }
						if($plan=='lifetime_notrial') {
							$invoiceitem = \Stripe\InvoiceItem::create(array(
								"customer" => $subscription['id'],
								"amount" => $amount*100,
								"currency" => "usd",
								"description" => "One-time membership fee")
							);
						}
                       
                        if(!empty($subscription)){ 
                            $query = $userTable->query();
                            $query->update()
                                    ->set(['membership_level' =>$membershipId['id']])
                                    ->where(['id' => $user_id])
                                    ->execute();
                            $querypayment=$tablePayments->query();
                            //$querypayment->update()
                            //    ->set(['amount'=>($amount*100),'membership_level'=>$membershipId['id'],'date'=>date("Y-m-d h:i:s")])
                            //    ->where(['customer_id'=>$customer['customer_id'],'user_id'=>$user_id])
                            //    ->execute();
                            if($querypayment){
                                if(isset($cop)){
                                   $session->destroy("couponcode");
                                }
                                $this->Flash->success('Your plan has been updated successfully.');
                                return $this->redirect(array('controller'=>'Pages','action' => 'usermembership'));
                            }
                        }
                    }catch(\Stripe\Error\Card $e) {
                        $error = $e->getMessage();
                        $token_id = '';
                        $this->Flash->set($error,['params'=>['class' => 'alert danger']]);
                    }
                }
				else{
                    try{
                        $customer = \Stripe\Customer::create(array(
                                'source'   => $getToken,
                                'email'    => $this->Auth->user('email'),
                                'plan'     => $plan,
                                'description' => "new recurring plan",
                                'coupon'=>isset($cop)?$cop: null
                        ));
						if($plan=='lifetime_notrial') {
							$charge = \Stripe\Charge::create(array(
								"amount" => $amount*100,
								"currency" => "usd",
								"customer" => $customer['id'],
								"description" => "One-time membership fee")
							);
						}
                        if(!empty($customer)){ 
                            $query = $userTable->query();
                            $query->update()
                                    ->set(['membership_level' =>$membershipId['id']])
                                    ->where(['id' => $user_id])
                                    ->execute();
                     						
							
							$savedata['customer_id']           = $customer['id'];
							// $savedata['amount']                = round($amount*100);
							$savedata['amount']                = 0;
							$savedata['currency']              =  'usd';
							$savedata['user_id']               =  $user_id;
							$savedata['membership_level']      =  $membershipId['id'];
							$savedata['subscription_id']=isset($customer['subscriptions']['data'][0]['id'])?$customer['subscriptions']['data'][0]['id']:"";
							$savedata['payment_mode']="Stripe";
							$table  =TableRegistry::get("Payments");
							$post=$tablePayments->newEntity();
                            $post->date   =  date("Y-m-d h:i:s");
                            $post=$tablePayments->patchEntity($post,$savedata);
							
							$table1 = TableRegistry::get("UsersStripeBalances");
							$UsersStripeBalancesentity = $table1->newEntity();                    
							$UsersStripeBalancesentity->customer_id 		= $customer['id'];
							$UsersStripeBalancesentity->user_id 		        = $user_id;
							$UsersStripeBalancesentity->balance 	                = round($amount*100);
							$UsersStripeBalancesentity->created			= date("Y-m-d");
							
                            if($tablePayments->save($post) && $table1->save($UsersStripeBalancesentity)){
                                if(isset($cop)){
                                   $session->destroy("couponcode");
                                }
                                $this->Flash->success('Your plan has been updated successfully.');
                                return $this->redirect(array('controller'=>'Pages','action' => 'usermembership'));
                            }
                        }
                       // pr($customer);die;
                    }catch(\Stripe\Error\Card $e) {
                        $error = $e->getMessage();
                        $token_id = '';
                        $this->Flash->set($error,['params'=>['class' => 'alert danger']]);
                    }
                    
                }
            }
        }
        $Countriestable=TableRegistry::get('Countries');
        $query = $Countriestable->find('list', ['keyField' => 'id','valueField' => 'name'])->order(['name' => 'ASC']);
        $countries = $query->toArray();
       	$countries	=array("223"=>"United States")+ $countries ;
        
        $statestbl=TableRegistry::get('States');
        $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
        $states 	= $Statesqery->toArray();
	
        $cities=[];
        $this->set(compact("countries","states","cities"));
    }
    /*
       public function upgrade($membership=null,$amount=null){
        $this->layout='home_page';
        $user_id=$this->Auth->user('id');
        $session=$this->request->session();
        $userTable	  =TableRegistry::get("Users");
        $membershipTable=TableRegistry::get("Memberships");
      //  $amount_old=base64_decode($amount);
        $membership=base64_decode($membership);
        $membershipId=$membershipTable->find("all")->where(['slug'=>$membership])->first();
        $tablePayments  =TableRegistry::get("Payments");
        $filter=[];
        $filter['OR']=['membership_level'=>'8','membership_level'=>'7'];
        $customer=$tablePayments->find("all")->where(['user_id'=>$user_id,$filter])->order(['id'=>'DESC'])->first();
        if($membership=='gold'){
            $plan='gold';
        }if($membership=='platinum'){
            $plan='platinum_pro';
                }
                
                                        $paymentTable =TableRegistry::get('payments');
                                        $payment_data=$paymentTable->find('all',array('conditions'=>array('user_id' => $user_id),'order'=>array('id'=>'DESC')))->first();
                                        $payment_date=$payment_data->date;
                                        $i=0;
                                        foreach($payment_date as $roe)
                                        {
                                        if($i==0)
                                        {
                                        $payment_date=$roe;
                                        }
                                        $i++;
                                        }
                                        date_default_timezone_set("Asia/Kolkata");
                                        $current_date=date('Y-m-d');
                                        $explode_date=explode('-',$current_date);
                                        $day_in_month=cal_days_in_month(CAL_GREGORIAN,$explode_date[1],$explode_date[0]);
                                        $day_in_month=$day_in_month;
                                        $now_to = date('Y-m-d'); // or your date as well
                                        $now = strtotime($now_to);
                                        $payment_date_explode=explode(" ",$payment_date);
                                        $exp_dat=explode("-",$payment_date_explode[0]);
                                        $day_in_month_month=cal_days_in_month(CAL_GREGORIAN,$exp_dat[1],$exp_dat[0]);
                                        $your_date = strtotime($payment_date_explode[0]);
                                        $datediff = $now - $your_date;
                                        $date_difference=floor($datediff / (60 * 60 * 24));
                                        $date_difference=$date_difference%$day_in_month_month;
                                        $total_remaining_day=$day_in_month-$date_difference;
                                        
                                        
                                        
                                        
if($membership=='gold'){
                                        if($membership=='gold')
                                        {
                                        $membership_slug="platinum";
                                        }
                                        else if($membership=='platinum')
                                        {
                                        $membership_slug="gold"; 
                                        }
                                        $membershipAmount   =$membershipTable->find("all")->where(['slug'=>$membership_slug])->first();
                                        $price              =$membershipAmount["price"];
                                        $amount_old=$price;
                                        $amount_my=base64_decode($amount);
                                        
                                        $amt_gy=round(($amount_old/$day_in_month),"2");
                                        
                                        $payable_price=$amt_gy*$date_difference;
                                        $payable_price=round($payable_price,"2");
                                        
                                        $amt_re=round(($amount_my/$day_in_month),"3");
                                        
                                        $repayable_price=$amt_re*$total_remaining_day;
                                        $repayable_price=round($repayable_price,"3");
                                        
                                        
                                        
                                        $total_cost_to_pay=$repayable_price+$payable_price;
                                        $total_cost_to_pay=round($total_cost_to_pay,"2");
                                        
                                        
                                        if($total_cost_to_pay>0){
                                        $refund_amount=$amount_old-$total_cost_to_pay;
                                        }
                                        
                                        
                                        $amount_to_pay=$amount_my-$refund_amount;
                                        
                                        
                                        $amount=$amount_to_pay;

                      
}
                       
else if($membership=='platinum')
{
                                           
                                            $membershipAmount   =$membershipTable->find("all")->where(['slug'=>$membership])->first();
                                            $price              =$membershipAmount["price"];
                                            $paymentTable =TableRegistry::get('payments');
                                            $payment_data=$paymentTable->find('all',array('conditions'=>array('user_id' => $user_id),'order'=>array('id'=>'DESC')))->first();
                                            $payment_date=$payment_data->date;
                                            $i=0;
                                            foreach($payment_date as $roe)
                                            {
                                            if($i==0)
                                            {
                                            $payment_date=$roe;
                                            }
                                            $i++;
                                            }
                                            date_default_timezone_set("Asia/Kolkata");
                                            $current_date=date('Y-m-d');
                                            $explode_date=explode('-',$current_date);
                                            $day_in_month=cal_days_in_month(CAL_GREGORIAN,$explode_date[1],$explode_date[0]);
                                            $day_in_month=$day_in_month;
                                            $now_to = date('Y-m-d'); // or your date as well
                                            $now = strtotime($now_to);
                                            $payment_date_explode=explode(" ",$payment_date);
                                            $exp_dat=explode("-",$payment_date_explode[0]);
                                            $day_in_month_month=cal_days_in_month(CAL_GREGORIAN,$exp_dat[1],$exp_dat[0]);
                                            $your_date = strtotime($payment_date_explode[0]);
                                            $datediff = $now - $your_date;
                                            $date_difference=floor($datediff / (60 * 60 * 24));
                                            $date_difference=$date_difference%$day_in_month_month;
                                            $total_remaining_day=$day_in_month-$date_difference;
                                            
                                             if($membership=='gold')
                                        {
                                        $membership_slug="platinum";
                                        }
                                        else if($membership=='platinum')
                                        {
                                        $membership_slug="gold"; 
                                        }
                                           
                                            $membershipAmount   =$membershipTable->find("all")->where(['slug'=>$membership_slug])->first();
                                            $price              =$membershipAmount["price"];
                                            $amount_old=$price;
                                            $amount_my=base64_decode($amount);
                                            
                                            $amt_gy=round(($amount_old/$day_in_month),"2");
                                            
                                            $payable_price=$amt_gy*$date_difference;
                                            $payable_price=round($payable_price,"2");
                                            
                                            $amt_re=round(($amount_my/$day_in_month),"3");
                                            
                                            $repayable_price=$amt_re*$total_remaining_day;
                                            $repayable_price=round($repayable_price,"3");
                                            
                                            
                                            
                                            $total_cost_to_pay=$repayable_price+$payable_price;
                                            $total_cost_to_pay=round($total_cost_to_pay,"2");
                                            
                                            
                                            if($total_cost_to_pay>0){
                                            $refund_amount=$amount_old-$total_cost_to_pay;
                                            }
                                           
                                            
                                            $amount_to_pay=$amount_my-$payable_price;
                                            
                                            
                                            $amount=$amount_to_pay+$amount_my;
                                 
                                         
                   
                                            
}
                        
else{
                        $globalTable =TableRegistry::get('Globalsettings');
                        $membershipAmount =$globalTable->find("all")->where(['slug'=>$membership])->first();
                        $price=isset($membershipAmount['value'])?$membershipAmount['value']:"";
                        $amount=$price;
}
                     
                           
        require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
        $test_secret_key = Configure::read('stripe_test_secret_key');
        $setApiKey = Stripe::setApiKey($test_secret_key);
        $getApiKey = Stripe::getApiKey();
        $cop=$session->read("couponcode");
        if(!empty($getApiKey)){
             
            if($this->request->is(['post', 'put'])){
                try{
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
                }catch (\Stripe\Error\Base $e) {
                    $this->Flash->set($e->getMessage(),['params'=>['class' => 'alert danger']]);
                }
               
                if($customer['payment_mode']=='Stripe' && $customer['customer_id']){
                    try{
                      
                         $amount_dataa=($amount*100)-($amount_my*100);
                       
                    
                      
              $amount=($amount*100);
              if($amount>0)
              {
                $amount=$amount;
              }
              else
              {
                $amount=($amount_my*100);
              }
          
             
                       
                        $customer_data = \Stripe\Customer::create(array(
                                'source'   => $getToken,
                                'email'    => $this->request->data['email'],
                                'plan'     => $plan,
                               // 'account_balance' =>$amount_dataa ,
                                'description' => "new recurring plan",
                                'coupon'=>isset($cop)?$cop: null
                        ));
             
                        if($cop){
                            $subscription = \Stripe\Customer::retrieve($customer['customer_id']);
                            $subscription->plan = $plan;
                            $subscription->coupon=$cop;
                            $subscription->save();
                        }else{
                            $subscription = \Stripe\Customer::retrieve($customer['customer_id']);
                            $subscription->plan = $plan;
                            //$subscription->coupon=isset($cop)?$cop:NULL;
                            $subscription->save();
                        }
                           
                        if(!empty($subscription)){ 
                            $query = $userTable->query();
                            $query->update()
                                    ->set(['membership_level' =>$membershipId['id']])
                                    ->where(['id' => $user_id])
                                    ->execute();
                            $querypayment=$tablePayments->query();
                           
                            $querypayment->update()
                                ->set(['amount'=>$amount,'membership_level'=>$membershipId['id'],'date'=>date("Y-m-d h:i:s")])
                                ->where(['customer_id'=>$customer['customer_id'],'user_id'=>$user_id])
                                ->execute();
                            if($querypayment){
                                if(isset($cop)){
                                   $session->destroy("couponcode");
                                }
                                $this->Flash->success('Your plan has been updated successfully.');
                                return $this->redirect(array('controller'=>'Pages','action' => 'usermembership'));
                            }
                        }
                    }catch(\Stripe\Error\Card $e) {
                        $error = $e->getMessage();
                        $token_id = '';
                        $this->Flash->set($error,['params'=>['class' => 'alert danger']]);
                    }
                }else{
                         
                    try{
                       if($plan=='gold')
                       {
                        $amount_dataa=($amount*100)-($amount_my*100);
            
                        $r=-($amount_my*100);
                   
                       
                      if($amount_dataa<$r)
                      {
                        
                        $amount_dataa=0;
                      }
                      else
                      {
                        $amount_dataa=$amount_dataa;
                      }
                     
                  
                       }
                       else if($plan=='platinum')
                       {
                       $amount_dataa=($amount*100)-($amount_my*100);

                       }
                      
              $amount=($amount*100);
              if($amount>0)
              {
                $amount=$amount;
              }
              else
              {
                $amount=($amount_my*100);
              }
              
                     
                        $customer = \Stripe\Customer::create(array(
                                'source'   => $getToken,
                                'email'    => $this->request->data['email'],
                                'plan'     => $plan,
                                //'account_balance' =>$amount_dataa ,
                                'description' => "new recurring plan",
                                'coupon'=>isset($cop)?$cop: null
                        ));
                             
                        if(!empty($customer)){
                            
                            $query = $userTable->query();
                            $query->update()
                                    ->set(['membership_level' =>$membershipId['id']])
                                    ->where(['id' => $user_id])
                                    ->execute();
                            $savedata['customer_id']           = $customer['id'];
                            $savedata['amount']                = round($amount);
                            $savedata['currency']              =  'usd';
                            $savedata['user_id']               =  $user_id;
                            $savedata['membership_level']      =  $membershipId['id'];
                            $savedata['subscription_id']=isset($customer['subscriptions']['data'][0]['id'])?$customer['subscriptions']['data'][0]['id']:"";
                            $savedata['payment_mode']="Stripe";
                            $post=$tablePayments->newEntity();
                            $post->date   =  date("Y-m-d h:i:s");
                            $post=$tablePayments->patchEntity($post,$savedata);
                            if($tablePayments->save($post)){
                                if(isset($cop)){
                                   $session->destroy("couponcode");
                                }
                                $this->Flash->success('Your plan has been updated successfully.');
                                return $this->redirect(array('controller'=>'Pages','action' => 'usermembership'));
                            }
                        }
                       // pr($customer);die;
                    }catch(\Stripe\Error\Card $e) {
                        $error = $e->getMessage();
                        $token_id = '';
                        $this->Flash->set($error,['params'=>['class' => 'alert danger']]);
                    }
                    
                }
            }
        }
        $Countriestable=TableRegistry::get('Countries');
        $query = $Countriestable->find('list', ['keyField' => 'id','valueField' => 'name'])->order(['name' => 'ASC']);
        $countries = $query->toArray();
       	$countries	=array("223"=>"United States")+ $countries ;
        
        $statestbl=TableRegistry::get('States');
        $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
        $states 	= $Statesqery->toArray();
	
        $cities=[];
        $this->set(compact("countries","states","cities"));
    }
  
 
 
   */
    public function applypromocode(){
        $this->autoRender = false;
        $session=$this->request->session();
        if($this->request->is(['put','post'])){
            $promocodestable = TableRegistry::get('Promocodes');
            $usertable = TableRegistry::get('Users');
            $membershiptable = TableRegistry::get('Memberships');
            $uid        =$this->Auth->user('id');
            $userdata   =$usertable->get($uid);
            $promocode  =$this->request->data['promocode'];
            $mainPrice  =$this->request->data['mainPrice'];
           // $survey_id  =$this->request->data['survey_id'];
            $membership =$this->request->data['membership'];
            $membershipId=$membershiptable->find('all')->where(['slug'=>$membership])->first();
            $plan       =$membershipId['id'];
            $getpromocodedata = $promocodestable->find('all')->where(['promocode_title'=>$promocode,'status'=>ACTIVE,'type'=>$plan])->first();
            if($getpromocodedata){
                require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
                $test_secret_key = Configure::read('stripe_test_secret_key');
                $setApiKey = Stripe::setApiKey($test_secret_key);
                $getApiKey = Stripe::getApiKey();
                $coupon=\Stripe\Coupon::retrieve($getpromocodedata->promocode_title);
                $days=($getpromocodedata->duration)*30;
                $promoPrice  =$getpromocodedata['price'];
                if($coupon){ 
                    $responce['status'] = 1;
                    if($mainPrice > $promoPrice){
                        $responce['newprice'] = $mainPrice - $promoPrice;
                        $session->write("couponcode",$getpromocodedata->promocode_title);
                       // $responce['newpriceBase'] = SITE_URL.'pages/paymentPaypal1/'.base64_encode($mainPrice - $promoPrice);
                        $responce['newpriceBaseStrip'] = SITE_URL.'pages/upgrade/'.base64_encode($membership)."/".base64_encode($mainPrice - $promoPrice);
                        $responce['msg'] = "Promo code applied successfully";
                    }else{
                         $responce['status'] = 2;
                        $responce['msg'] = "Promo code is not applied";
                    }
                }else{
                    $responce['status'] = 2;
                    $responce['msg'] = "Promo code does not applied";
                }
            }else{
                $responce['status'] = 2;
                $responce['msg'] = "Promo code is not valid";
            }
        }
        echo  json_encode($responce);
        exit;
    }  
	public function upgradepayment(){
		$this->layout='home_page';
	}
    public function downgradepayment(){
		$this->layout='home_page'; 
	}
    public function updatemembership(){
		$this->layout='home_page';
        $user_id=$this->Auth->user('id');
        $userTable=TableRegistry::get("Users");
        $membershipTable=TableRegistry::get("Memberships");
        $membershipvisitor=$membershipTable->find("all")->where(['price'=>'0'])->first();
            $query = $userTable->query();
            $query->update()
                ->set(['membership_level' =>$membershipvisitor['id']])
                ->where(['id' => $user_id])
                ->execute();
        if($query){
            $this->Flash->success('Your plan has been updated successfully.');
            return $this->redirect(array('controller'=>'Pages','action' => 'usermembership'));
        }
	}
    public function questionbank($survey_id=null)
    {
        $this->layout='home_page';
        $table=TableRegistry::get('Categories');
        $survey_id = base64_decode($survey_id);
        $post = $table->find('all')->where(['status'=>ACTIVE])->toArray();
        //pr($post);die;
        if($this->request->is('post')){
            $searchvalue=$this->request->data['searchvalue'];
            if(!empty($searchvalue)){   
               // $post=$table->find('all')->where(['category_name Like'=>'%'.$searchvalue.'%','status'=>ACTIVE]);
                return $this->redirect(array('action' => 'searchQuestion',base64_encode($searchvalue),base64_encode($this->request->data['survey_id'])));
            }
        }
        $this->set(compact('post','survey_id'));
    }
    public function questionsdetails($id=null,$survey_id=null)
    {
        $this->layout='home_page';
        $category_id = base64_decode($id);
        $survey_id = base64_decode($survey_id);
        $table=TableRegistry::get('Questions');
        $count = $table->find('all')->where(['category_id'=>$category_id,'status'=>ACTIVE,'review'=>APPROVED])->count();
        if($count > 0){
            $post = $table->find()->where(['category_id'=>$category_id,'status'=>ACTIVE,'review'=>APPROVED])->toArray();
            $table2=TableRegistry::get('Categories');
            $category=$table2->find('all')->where(['id' =>$post[0]['category_id']])->first();
           // $session->write('questiondetails',true);
        }
        else{
                $this->Flash->error('There is no questions in this category please try another category.');
        }
      
        //$this->set('session',$session);
        $this->set(compact('post','category','survey_id'));
        
    }
    public function randomquestions($survey_id=null, $categoryId=null, $totalquestions=null)
    { 
        $this->layout='home_page';
        $survey_id = base64_decode($survey_id);
        $categoryId = base64_decode($categoryId);
        $totalquestions = base64_decode($totalquestions);
        $table=TableRegistry::get('Surveys');
        $surveyEntity=$table->newEntity();
        if($this->request->is(['post','put'])){
            $categoryId =$this->request->data['category_type'];
            $totalquestions =$this->request->data['totalqus'];
            $categoryId=explode(",",$categoryId);
            $len =count($categoryId);
            if($totalquestions && $categoryId){
                if(!is_numeric($totalquestions)){
                    $this->Flash->error(__('Please enter numeric value for total questions.'));
                }else{
                    $table1=TableRegistry::get('Questions');
                    $post=$table1->find('all', array(
                        'conditions' => array(
                                    "Questions.category_id IN" => $categoryId,
                                    'Questions.status'=>ACTIVE
                                    /*dont use array() */
                            ),
                        'order' => 'rand()',
                        "limit"=>$totalquestions
                ))->toArray();
                }
                $question_id = [];
                $categoryId = [];
                foreach($post as $val){
                    $question_id[]  =   $val->id;
                    $categoryId[]   =   $val->category_id;
                }
                //pr($categoryId);
                $data =[];
                $data['user_id']        = $this->Auth->user('id');
                $data['questions_id']   = serialize($question_id);
               // $data['created']        = date("Y-m-d h:i:s");
                $data['status']         = "1";
                $data['category_id']    =serialize($categoryId);
                $data['page']           ="pageRand";
                $surveyEntity->created  =date("Y-m-d h:i:s");
                $surveyEntity   =$table->patchEntity($surveyEntity,$data);
                if($result=$table->save($surveyEntity)){
                    $id=$result->id;
                    //$this->Flash->success(__('Questions added to your survey.'));
                    return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($id)));
                }
            }else
            {
                 $this->Flash->error(__('Please select category and enter total questions.'));
                //return $this->redirect(array('controller'=>'Users','action' => 'randomquestions'));
            }
        }
        $this->set(compact('post','categoryId','totalquestions','survey_id'));
    }
    public function randomquestionchallenge()
    {
        $this->layout='home_page';
        $table=TableRegistry::get('Categories');
        $post = $table->find('all')->where(['status'=>ACTIVE])->toArray();
        $this->set(compact('post'));
    }
    
    public function addfavorite(){
        $this->autoRender = false;
        $id=$this->Auth->user('id');
        $table=TableRegistry::get('Favourites');
        $post=$table->newEntity();
        if($this->request->is(['post','put'])) {
            $data=$this->request->data;
            $question =$this->request->data['question_id'];
            $category =$this->request->date['category_id'];
            $exists = $table->exists(['question_id' => $question, 'user_id'=>$id]);
            $post=$table->patchEntity($post,$data);
            $post->user_id=$id;
            $post->created = date("Y-m-d h:i:s");
            if(!$exists){
                if($result=$table->save($post)) {
                    echo "1";
                }
                else {
                    echo "Error: some error";
                }
            }else{
                $query = $table->query();
                $query->delete()
                        ->where(['question_id' => $question,'user_id'=>$id])
                        ->execute();
                if($query){
                     echo "2";
                }
            }
        }
    }
    public function manageprofile($id = null){
        $this->layout='home_page';
        $id=$this->Auth->user('id');
        //$id=base64_decode($id);
        //pr($id);die;
        $table=TableRegistry::get('Users');
        $post=$table->get($id);
            $country = $post->country;
            $region = $post->region;
            $statestbl=TableRegistry::get('States');
            $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>$country])->order(['name' => 'ASC']);
            $states 	= $Statesqery->toArray();
            
            $citiestbl=TableRegistry::get('Cities');
            $citiesqery = $citiestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['state_id'=>$region])->order(['name' => 'ASC']);
            $cities 	= $citiesqery->toArray();
            
        if($this->request->is(['post','put'])){
            $country = $this->request->data['country'];
            $region = $this->request->data['region'];
           
            $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>$country])->order(['name' => 'ASC']);
            $states 	= $Statesqery->toArray();
            
            $citiestbl=TableRegistry::get('Cities');
            $citiesqery = $citiestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['state_id'=>$region])->order(['name' => 'ASC']);
            $cities 	= $citiesqery->toArray();
            $data=$this->request->data;
            $table=TableRegistry::get('Users');
            $post=$table->find()->where(['`id`' => $this->request->data['id']])->first();
            $post=$table->patchEntity($post,$this->request->data,['validate' => 'ProfileDefault']);
            if(!empty($this->request->data['profile_photo']['name'])){
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG'){
                    $imagePath=time().$imagename;
					$filepath = getcwd() . '/img/user_images/' .$imagePath;
                    $post->profile_photo =$imagePath ;
                    $post->modified = date("Y-m-d h:i:s");
                    $dataing=$this->request->data['datingsites'];
                    if(in_array('0',$dataing)){
                        $post->other=$this->request->data['other'];
                    }else{
                        $post->other="";
                    }
                    $post->datingsites=serialize($this->request->data['datingsites']);
                    $post->membership_level=$this->request->data['membership_level'];
                    if($table->save($post)){
                        if(!empty($imagename)){ 
                            move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
                            chmod($filepath, 0777);
                        }
                        $this->Flash->success(__('Your profile has been updated.'));
                        //return $this->redirect(array('action' => 'profile'));
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
                           // return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
                                    
                        }
                        
                }else{
                $this->Flash->error("Please upload only png,jpg type file.");
                }
                //return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
            }else{
                    if(!empty($this->request->data['photo'])){
                        $post->profile_photo =$this->request->data['photo'];
                    }
                    $post->modified = date("Y-m-d h:i:s");
                    $dataing=$this->request->data['datingsites'];
                    $post->other="";
                    if($dataing){
                        if(in_array('0',$dataing)){
                            $post->other=$this->request->data['other'];
                        }
                    }
                    
                    $post->datingsites=serialize($this->request->data['datingsites']);
                    $post->membership_level=$this->request->data['membership_level'];
                    //  pr($post);
                 
                    if($table->save($post)){
                        $this->Flash->success(__('Your profile has been updated.'));
                       // return $this->redirect(array('action' => 'profile'));
                    }
                    else{
                        foreach($post->errors() as $key => $value){
                            $messageerror = [];
                            foreach($value as $key2 => $value2){
                                $messageerror[] = $value2;
                                foreach($messageerror as $err)
                                {
                                    $this->Flash->error(__($err));
                                    //return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
                                }
                            }
                        }
                    }
                }
            }
		$table1=TableRegistry::get('Countries');
		$query1 = $table1->find('list', [
		'keyField' => 'id',
		'valueField' => 'name'])
		->order(['name' => 'ASC']);
		$countries 	= $query1->toArray();
		$countries	=array("223"=>"United States")+ $countries ;
        
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
        $sites = $query->toArray();
		array_unshift($sites,"N/A");
        $this->set(compact('post','sites','countries','membership','states','cities','countrycode'));

    }
    public function changepassword($id = null) {
        $this->layout='home_page';
        $id=$this->Auth->user('id');
        $hasher = new DefaultPasswordHasher();
        $table = TableRegistry::get('Users');
        $newpw = $table->find('all')->where(['`id`'=>$id])->first();
        if ($this->request->is('post','put')) {
            $verify = $hasher->check($this->request->data['oldpassword'],$newpw->password );
            $newpw=$table->patchEntity($newpw,$this->request->data,[ 'validate' => 'ChangeDefault']);
            if(!$newpw->errors()){
                if($verify == 1){
                    if($this->request->data['newpassword'] == $this->request->data['cpassword']){
                        $password =$hasher->hash($this->request->data['newpassword']);
                        $query = $table->query();
                        $query->update()
                            ->set(['password' => $password])
                            ->where(['id' => $id])
                            ->execute();
                        if($query){
                            $EmailTemplates= TableRegistry::get('Emailtemplates');
                            $query = $EmailTemplates->find('all')->where(['slug' => 'change_password'])->toArray();
                            if($query){
                                    $to = $newpw->email;
                                    $subject = $query[0]['subject'];
                                    $message1 = $query[0]['description'];
                                    $message = str_replace(['{{username}}','{{email}}','{{password}}'],[$newpw->first_name,$newpw->email,$this->request->data['newpassword']],$message1);
                                    parent::sendEmail($to, $subject, $message);
                                    //$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
                                    //return $this->redirect(['controller'=>'Users','action' => 'login']);
                            }
                            
                            return $this->redirect(['controller'=>'Users','action' => 'logout',base64_encode("1")]);
                        }
                    }else{
                        $this->Flash->error(__('Passwords do not matched.'));
                    }
                }else{
                        $this->Flash->error(__('Old password is incorrect.'));
                }    
            }else{
                foreach($newpw->errors() as $key => $value){
                    $messageerror = [];
                    foreach($value as $key2 => $value2){
                        $messageerror[] = $value2;
                        foreach($messageerror as $err)
                        {
                            $this->Flash->error(__($err));
                            
                        }
                    }
                }
            }
                
        }
        $this->set('email',$newpw->email);
    }
    public function addsurvey(){
        $this->layout='home_page';
        $table=TableRegistry::get('Surveys');
        $post=$table->newEntity();
        $membership=$this->Auth->user('membership_level');
        if($this->request->is(['put','post']))
        {
            if(!empty($this->request->data['questions_id']))
            {
                $survey_type =$this->request->data['survey_type'];
                $tmp=array();
                // $cat = []; 
                // $cates=[]; 
                $membertable=TableRegistry::get('Memberships');
                $member=$membertable->find("all")->where(['id'=>$membership])->first();
                // echo $member['slug'];die;
             
                if($this->request->data['survey_id'])
                {
                    
                    $p=$table->find('all')->where(['id'=>$this->request->data['survey_id']])->first();
                    $tmp        =unserialize($p['questions_id']);
                    //$cat[]    =unserialize($p[0]['category_id']);
                    $questions  =$this->request->data['questions_id'];
                    
                    $cates    =$this->request->data['category_id'];
                    $tmp        =array_merge($tmp,$questions);
                    // pr($tmp );die;
                    $tmpunique  =array_unique($tmp);
                    //pr($tmpunique);die;
                    //$cat      =array_merge($cat,$cates);
                    //echo $member['slug'];die;
                    if(($member['slug']=='visitor') && ($survey_type=='1' || $survey_type=='0' ))
                    {
                        //ech $count=count($tmp);die;
                        $count=count($tmp);
                        if($count > 15){
                            $this->Flash->error(__('You can add up to 15 questions to this survey.'));
                            return $this->redirect(array('controller'=>'Pages','action' =>'survey',base64_encode($this->request->data['survey_id'])));
                        }
                    }
                
                        $query = $table->query();
                        $query->update()
                            ->set(['questions_id' =>serialize($tmpunique),'modified'=>date("Y-m-d h:i:s"),'status'=>'1'])
                            ->where(['id' => $this->request->data['survey_id']])
                            ->execute();
                        if($query){
                            $this->Flash->success(__('The questions below are added to your survey. Please answer ALL the questions before clicking SUBMIT or SAVE.'));
                            return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($this->request->data['survey_id'])));
                        }
                }else
                {
                    $post=$table->patchEntity($post,$this->request->data);
                    $cates  =$this->request->data['category_id'];
                   // pr($cates[0]);die;
                    if(($member['slug']=='visitor') && ($survey_type=='1' || $survey_type=='0' ))
                    {
                        $count=count($this->request->data['questions_id']);
                        if($count > 15){
                            $this->Flash->error(__('You can add up to 15 questions to this survey.'));
                            return $this->redirect(array('controller'=>'Pages','action' =>'questionsdetails',base64_encode($cates[0])));
                        }
                    }
                    $questions=serialize($this->request->data['questions_id']);
                    $post->questions_id=$questions;
                    $post->created = date("Y-m-d h:i:s");
                    $post->status = "1";
                   // $post->category_id=serialize($cates);
                    $post->page=$this->request->data['page'];
                    if($result=$table->save($post)){
                        $id=$result->id;
                        $this->Flash->success(__('The questions below are added to your survey. Please answer ALL the questions before clicking SUBMIT or SAVE.'));
                        return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($id)));
                    }
                }    
            }
        }
        $this->set(compact('post'));
    }
    public function survey($id=null,$usertype=null,$email=null)
    {
        $this->layout='home_page';
        $session=$this->request->session();
        $survey_id  = base64_decode($id);
        $usertype   = base64_decode($usertype);
        $email      = base64_decode($email);
        $table=TableRegistry::get('Surveys');
        $post = $table->find('all')->where(['id'=>$survey_id])->first();
       //pr($post);die;
        $totalquestion = $session->read('totalquestion');
        $this->set('totalquestion',$totalquestion);
        $this->set(compact('post','survey_id','usertype','email'));
    }
    public function create(){
        $this->layout='home_page';
        if($this->request->is(['post','put'])) {
            $id     = $this->request->data['id'];
            $user_id=$this->request->data['user_id'];
            $table=TableRegistry::get('Savedsurvey');
            $post = $table->find('all')->where(['id'=>$id])->first();
            $data['questions_id']   =$post['question_id'];
            $data['user_id']        =$post['user_id'];
            $data['answers']        =$post['answers'];
            $data['type']            =SAVED;
           // $data['category_id']    =$post['category_id'];
            $tableSurvey=TableRegistry::get('Surveys');
            $postSurveys            =$tableSurvey->newEntity();
            $postSurveys            =$tableSurvey->patchEntity($postSurveys,$data);
            $postSurveys->created   =date("Y-m-d h:i:s");
            $result=$tableSurvey->save($postSurveys);
            //pr($post);die;
            $id=$result->id;
            echo base64_encode($id);
            exit;
        }
        
    }
    public function submit(){
        $this->layout='home_page';
        if($this->request->is(['post','put'])) {
            $id     = $this->request->data['id'];
            $user_id=$this->request->data['user_id'];
            $table=TableRegistry::get('Savedsurvey');
            $post = $table->find('all')->where(['id'=>$id])->first();
            $data['questions_id']   =$post['question_id'];
            $data['user_id']        =$post['user_id'];
            $data['answers']        =$post['answers'];
            $data['type']            =SAVED;
           // $data['category_id']    =$post['category_id'];
            $tableSurvey=TableRegistry::get('Surveys');
            $postSurveys            =$tableSurvey->newEntity();
            $postSurveys            =$tableSurvey->patchEntity($postSurveys,$data);
            $postSurveys->created   =date("Y-m-d h:i:s");
            $result=$tableSurvey->save($postSurveys);
            //pr($post);die;
           echo $id=$result->id;
         //   echo base64_encode($id);
            exit;
        }
        
    }
    public function savedsurvey($id=null){
        $this->layout='home_page';
        $session=$this->request->session();
        $id  = base64_decode($id);
        $table=TableRegistry::get('Savedsurvey');
        $post = $table->find('all')->where(['id'=>$id])->first();
        $survey_id=$post['id'];
        $this->set(compact('post','survey_id'));
    }
    public function sendsurvey(){
        $this->layout='home_page';
        $this->autoRender=false;
        $tableSurveyAnswers=TableRegistry::get('Surveyanswers');
        $tableSurvey=TableRegistry::get('Surveys');
        $post=$tableSurveyAnswers->newEntity();
        $data=$this->request->data;
        if($this->request->is(['put','post'])){
            $post=$tableSurveyAnswers->patchEntity($post,$this->request->data);
            if(!empty($this->request->data['question_id']))
            {
                $user_id		=$this->Auth->user('id');
                $userTable		=TableRegistry::get('Users');			
                $user			=$userTable->find("all")->where(['id'=>$user_id])->first();
                $survey_type	=$user['survey_type'];
                $survey_id      =$this->request->data['survey_id'];
                //pr($survey_id);die;
                $membershipTable = TableRegistry::get('Memberships');			
                $membership	=$membershipTable->find("all")->where(['id'=>$user['membership_level']])->first();
                if($this->request->data['processType'] == 'save'){
                    $tableSavedSurvey=TableRegistry::get('Savedsurvey');
                    $isname   =$tableSavedSurvey->find("all")->where(['survey_name'=>$this->request->data['surveyname'],'user_id'=>$user_id])->first();
                    if($isname){
                        $this->Flash->error(__('Survey name must be unique.'));
                        return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($survey_id)));
                    }else{
                  
                        $saved    =$tableSavedSurvey->newEntity();
                        $isPost   =$tableSurvey->find("all")->where(['id'=>$survey_id])->first();
                        $ans=!empty($this->request->data['answers'])?$this->request->data['answers']:"";
                        if(isset($isPost['answers'])){
                            $query1 = $tableSurvey->query();
                            $query1->update()
                               ->set(['answers'=>serialize($ans),
                                        'modified'=>date("Y-m-d h:i:s"),
                                        'type'=>'2'])
                               ->where(['id'=>$survey_id,'user_id'=>$user_id])
                               ->execute();
                        }
                            $datavalue                  =$tableSavedSurvey->patchEntity($saved,$this->request->data);
                            $datavalue['survey_id']     =$this->request->data['survey_id'];
                            $datavalue['survey_name']   =$this->request->data['surveyname'];
                            $datavalue['question_id']   =serialize($this->request->data['question_id']);
                            $datavalue['user_id']       =$user_id;
                            $datavalue['type']          =SAVED;
                            //pr($this->request->data['answers']);die;
                            //$datevalue['category_id']   =$this->request->data['category_id'];
                            $datavalue['withanswer']    =$this->request->data['withanswer'];
                            $datavalue->created         =date("Y-m-d h:i:s");
                            if($this->request->data['withanswer']=='1'){
                                $datavalue['answers']   =serialize($this->request->data['answers']);
                                //pr($datavalue['answers']);die; 
                            }
                            if($tableSavedSurvey->save($datavalue)){
                                $query = $tableSurvey->query();
                                    $query->update()
                                       ->set(['status'=>'2','type'=>'2'])
                                       ->where(['id'=>$survey_id,'user_id'=>$user_id])
                                       ->execute();
                                $this->Flash->success(__('Your survey has been saved.'));
                                return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($survey_id)));
                            }
                    }
                }   
                    if(empty($this->request->data['answers'])){
                        $this->Flash->error(__('Please answer all the questions before sending this survey to your partner.'));
                        return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($this->request->data['survey_id'])));       
                    }
                    else
                    {
                     
                        if($survey_type=='1' && $membership['slug']=='visitor'){
                            $survey_for=1;
                        }
                        if($survey_type=='2'&& $membership['slug']=='visitor'){
                            $survey_for=2;
                        }
                        if($membership['slug']=='gold'){
                            $survey_for=3;
                        }
                        if($membership['slug']=='platinum'){
                            $survey_for=4;
                        }
                        //pr($survey_for);die;
                        $questions  = serialize($this->request->data['question_id']);
                        $answers    =serialize($this->request->data['answers']);
                        $exists     =$tableSurveyAnswers->find("all")->where(['survey_id'=>$this->request->data['survey_id'],'user_id'=>$user_id])->first();
                        if($exists){
                            // die("bfhjb");
                            $query = $tableSurveyAnswers->query();
                            $query->update()
                            ->set(['question_id'=>$questions,'answers'=>$answers,'survey_for'=>$survey_for,'modified'=>date('Y-m-d h:i:s')])
                            ->where(['id' => $this->request->data['survey_id'],'user_id'=>$user_id])
                            ->execute();
                            if($membership['slug']=='visitor'){
                                  $this->Flash->success(__('Your survey has been created. Upload your photo to the survey'));
                            }else{
                                 $this->Flash->success(__('Your survey has been created.'));
                            }
                            return $this->redirect(array('controller'=>'Pages','action' => 'submissionform',base64_encode($this->request->data['survey_id'])));
                        }else{
                            // die("bfhjb23");
                            $post->question_id  =$questions;
                            $post->answers      =$answers;
                            $post->survey_for   =$survey_for;
                            $post->survey_id    =$this->request->data['survey_id'];
                            $post->created      =date("Y-m-d h:i:s");
                            //pr($post);die;
                            if($tableSurveyAnswers->save($post)){
                                $query = $tableSurvey->query();
                                $query->update()
                                ->set(['modified'=>date("Y-m-d h:i:s"),'status'=>'2'])
                                ->where(['id' => $this->request->data['survey_id']])
                                ->execute();
                                if($membership['slug']=='visitor'){
                                  $this->Flash->success(__('Your survey has been created. Upload your photo to the survey'));
                                }else{
                                     $this->Flash->success(__('Your survey has been created.'));
                                }
                                return $this->redirect(array('controller'=>'Pages','action' => 'submissionform',base64_encode($this->request->data['survey_id'])));
                            }
                        }
                    }              
            }else{
                $this->Flash->error(__('Please add some questions in the survey.'));
                return $this->redirect(array('controller'=>'Pages','action' => 'questionbank'));
            }
        }
        $this->set(serialize($post));
    }
    function choosepaymenttypeforsurvey($membership=null,$survey_id=null){
        $this->layout='home_page';
        $membership     =base64_decode($membership);
 //       $page_id        =base64_decode($page_id);
       // echo  $page_id; die;
        $user_id        =   $this->Auth->user('id');
        $user_membership=   $this->Auth->user('membership_level');
        $survey_id      =   base64_decode($survey_id);
        $session = $this->request->Session();
        $membershipTable=TableRegistry::get("Memberships");
	    if($membership=='gold' || $membership=='platinum'){
            $membershipAmount   =$membershipTable->find("all")->where(['slug'=>$membership])->first();
            $price              =$membershipAmount["price"];
	    }else{
            $globalTable =TableRegistry::get('Globalsettings');
            $membershipAmount =$globalTable->find("all")->where(['slug'=>$membership])->first();
            $price=isset($membershipAmount['value'])?$membershipAmount['value']:"";
	    }
        $user =   $session->read('Config.data');  
        if($this->request->is(['put','post'])){
            $data['uid'] =$user_id;
            $data['amount']=round($this->request->data['amount'],"2");
            //pr($this->request->data['amount']);die;
            $data['return_url'] = SITE_URL."pages/paymentPaypal1/".base64_encode($user_id)."/".base64_encode($survey_id)."/".base64_encode($membership);
            $ff = $this->PaypalExpressRecurring->expresscheckout($data);
            die;
        }   
        $this->set(compact('post','price','membership','survey_id','user_id'));    
    }
    
    function choosepaymenttype($membership=null,$survey_id=null,$page_id=null){
        $this->layout='home_page';
        $membership     =base64_decode($membership);
        $page_id        =base64_decode($page_id);
        //echo  $page_id; die;
        $user_id        =   $this->Auth->user('id');
        $user_membership=   $this->Auth->user('membership_level');
        $survey_id      =   base64_decode($survey_id);
        $session = $this->request->Session();
        $membershipTable=TableRegistry::get("Memberships");
	    if($membership=='platinum' || $membership=='lifetime'){
            $membershipAmount   =$membershipTable->find("all")->where(['slug'=>$membership])->first();
            $price              =$membershipAmount["price"];
	    }else{
            $globalTable =TableRegistry::get('Globalsettings');
            $membershipAmount =$globalTable->find("all")->where(['slug'=>$membership])->first();
            $price=isset($membershipAmount['value'])?$membershipAmount['value']:"";
	    }
        $amount=$price;
        $user =   $session->read('Config.data');  
        if($this->request->is(['put','post'])){
            if($page_id=="upgrade" && $membership=='platinum'){
                $membershipAmountOld		=$membershipTable->find("all")->where(['id'=>$user_membership])->first();
                $amountold      =$membershipAmountOld['price'];
                $amountupdate   =$this->request->data['amount']+(($this->request->data['amount']-$amountold)/2);
                $amount         =$amountupdate;
            }else if($page_id=="downgrade" && $membership=='gold'){
                $membershipAmountOld		=$membershipTable->find("all")->where(['id'=>$user_membership])->first();
                $amountold  =$membershipAmountOld['price'];
                $amountupdate =$amount-(($amountold-$amount)/2);
                $amount=$amountupdate;
            }else{
               // die("dfjgkd");
                 $amount=$price;
            }
           
           // die("njgf");
          //  $this->request->data['membership_level']=$membership;
    
            $data['uid'] =$user_id;
            $data['amount']=round($amount,"2");
            $data['return_url'] = SITE_URL."pages/paymentPaypal1/".base64_encode($user_id)."/".base64_encode($survey_id)."/".base64_encode($membership)."/".base64_encode($page_id);
            $ff = $this->PaypalExpressRecurring->expresscheckout($data);
            die;
        }   
        $this->set(compact('post','price','membership','survey_id','page_id'));    
    }
    function mysurvey(){
        $this->layout='home_page';
        $id     = $this->Auth->user('id');
        $table =TableRegistry::get("Savedsurvey");
        $post  =$table->find('all')->where(['user_id'=>$id])->order(['id'=>'DESC'])->toArray();
        $this->set('post',$post);        
    }
    function mycompatibilityreports($user_id=null,$remail=null){
        $this->layout='home_page';
        $user_id=base64_decode($user_id);
        $remail=base64_decode($remail);
        $tableUsers =TableRegistry::get("Users ");
        $user  =$tableUsers->find("all")->where(['id'=>$user_id])->first();
        $filter=[];
        $filter['OR']=[['survey_for'=>3],['survey_for'=>4]];
        $tableMembership =TableRegistry::get("Memberships");
        $usermem =$tableMembership->find("all")->where(['id'=>$user['membership_level']])->first();
        $table =TableRegistry::get("Surveyanswers");
        //pr($user);die;
        /*if($usermem['slug']=='platinum'){
            $post  =$table->find('all')->where(['AND'=>['user_id'=>$user_id],['receiver_email'=>$remail],['survey_for'=>'4']])->order(['id'=>'DESC'])->toArray();
        }else{
            $post  =$table->find('all')->where(['AND'=>['user_id'=>$user_id],['receiver_email'=>$remail],['survey_for'=>'3']])->order(['id'=>'DESC'])->toArray();
        }*/
       $post  =$table->find('all')->where(['AND'=>['user_id'=>$user_id],['receiver_email'=>$remail],$filter])->order(['id'=>'DESC'])->toArray();
        $this->set('post',$post);        
    }
    function compatibilityreport($receiver_email = null,$id = null,$survey_id = null){
        $this->layout='home_page';
        $receiver_email = base64_decode($receiver_email);
      	$user_id   = base64_decode($id);
        $survey_id = base64_decode($survey_id);
        $table     = TableRegistry::get('Surveyanswers');
        $post  =$table->find('all')->where(['receiver_email'=> $receiver_email,'user_id'=>$user_id,'survey_id'=>$survey_id])->first();
        $this->set('post',$post);
    }
    function removequestion(){
        $survey_id = $this->request->data['survey_id'];
        $idss = $this->request->data['idss'];
        $question_ids=explode(",",$idss);
        $table = TableRegistry::get('Surveys');
        $post = $table->find('all')->where(['id'=>$survey_id])->toArray();
        $total= unserialize($post[0]['questions_id']);
        $new =array_diff($total,$question_ids);
        $new=serialize($new);
        //pr($new);die;
        $query =$table->query();
        $query->update()
            ->set(['questions_id'=>$new])
            ->where(['id'=>$survey_id])
            ->execute();
        if($query){
            $message['msg'] ='Questions have been removed from survey.';
            $message['id'] =$question_ids;
            echo json_encode($message);
        }else{
            echo "error";
        }
        exit;  
    }
    public function favouritelist($survey_id = null)
    {
        $this->layout='home_page';
        $id=$this->Auth->user('id');
        $survey_id= base64_decode($survey_id);
        $table=TableRegistry::get('Favourites');
        $post = $table->find('all')->where(['user_id'=>$id])->toArray();
        if(empty($post)){
             $this->Flash->error('There are no questions on your Favorite List.');
        }
        $this->set(compact('post','survey_id'));
    }
    
    public function submissionform($survey_id=null)
    {
        $this->layout='home_page';
      
        $survey_id=base64_decode($survey_id);
        $user_id=$this->Auth->user('id');
		
       
        
        $table=TableRegistry::get('Receivers');
        $tableUser=TableRegistry::get('Users');
        $post=$table->newEntity();
        $session=$this->request->session();
        $data=$this->request->data;
        //pr($data);die;
        if($this->request->is(['put','post'])){
            $session->write("receiverEmail",$this->request->data['email']);
            $session->write("receiverName",$this->request->data['name']);
            $session->write("receiverMessage",$this->request->data['message']);
            $session->write("survey_id",$survey_id);
           $p=$tableUser->find('all')->where(['id'=>$user_id])->first();
            //$postUser=$tableUser->patchEntity($p,$data);
            
            $firstname = $p['first_name'];
            if(isset($p['last_name'])){
             $lastname = $p['last_name'];
            }else {
                $lastname='';
                
            }
          
            
            
            if(!empty($this->request->data['profile_photo']['name']))
            {
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG')
                {
                    $imagePath=time().$imagename;
                    $filepath = getcwd() .'/img/user_images/'.$imagePath;
                    if(!empty($imagename)){ 
                        move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
                        chmod($filepath, 0777);
                        $query=$tableUser->query();
                        $query->update()
                        ->set(['profile_photo'=>$imagePath])
                        ->where(['id'=>$user_id])
                        ->execute();
                    }
                         //$this->Flash->success(__('Your profile picture has been updated.'));
                        //return $this->redirect(array('controller'=>'Receivers','action' =>'survey',base64_encode($survey_id),base64_encode($usertype),base64_encode($email),base64_encode('flag'),base64_encode($imagename)))
                }else{
                    $this->Flash->error("Please upload only png,jpg type file.");
                }
            }
            $exists= $table->find("all")->where(['survey_id'=>$survey_id,'user_id'=>$user_id])->first();
            if(empty($exists)){
               // pr($this->request->data);die;
                $post=$table->patchEntity($post,$this->request->data);
                $post->user_id=$this->Auth->user('id');
                $post->created = date("Y-m-d h:i:s");
                $post->survey_id=$survey_id;
                //pr($post);die;
                if($table->save($post)){
                    //$this->Flash->success(__('Details has been saved.'));
                }
                else{
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
            }else{
                $query=$table->query();
                $query->update()
                ->set(['name'=>$this->request->data['name'],
                       'email'=>$this->request->data['email'],
                        'message'=>$this->request->data['message'],
                        'user_id'=>$this->Auth->user('id'),
                        'survey_id'=>$survey_id,
                       'modified'=>date("Y-m-d h:i:s")])
                ->where(['survey_id'=>$survey_id,'user_id'=>$user_id])
                ->execute();
                //$this->Flash->success(__('Details has been saved.'));
            }
            $membershipTable = TableRegistry::get('Memberships');			
            $membership	=$membershipTable->find("all")->where(['id'=>$p['membership_level']])->first();
            if($membership['slug']=='visitor'){
                $globalTable	=TableRegistry::get('Globalsettings');	
                if($p['survey_type']=='1' && $p['free_survey'] != 0){
                   // die("1");
                    $surveyAmount	=$globalTable->find("all")->where(['slug'=>'simple_survey'])->first();
                    return $this->redirect(['controller'=>'Pages','action'=>'choosepaymenttypeforsurvey',base64_encode($surveyAmount['slug']),base64_encode($survey_id)]);
                }else if($p['survey_type']=='2'){
                    // die("2");
                    $surveyAmount	=$globalTable->find("all")->where(['slug'=>'advanced_survey'])->first();
                    return $this->redirect(['controller'=>'Pages','action'=>'choosepaymenttypeforsurvey',base64_encode($surveyAmount['slug']),base64_encode($survey_id)]);
                }else
                {
                    // die("3");
                     $user_id =$this->Auth->user('id');
                     $userTable=TableRegistry::get('Users');
                     $query = $userTable->query();
                     $query->update()
                     ->set(['free_survey' =>'1'])
                     ->where(['id' => $user_id])
                     ->execute();
                    $tableSurvey=TableRegistry::get('Surveys');
                    $query = $tableSurvey->query();
                            $query->update()
                                ->set(['modified'=>date("Y-m-d h:i:s"),'status'=>'4'])
                                ->where(['id' =>$survey_id,'user_id'=>$user_id])
                                ->execute();
                                
                   // $this->Flash->error(__('You can enjoy your first free survey.'));
                    $EmailTemplates= TableRegistry::get('Emailtemplates');
                    $query = $EmailTemplates->find('all')->where(['slug' => 'send_survey'])->toArray();
                    $query1 = $EmailTemplates->find('all')->where(['slug' => 'mobile_message'])->toArray();
                    if($query){
                        $usertype="receiver";
                       // $activation_link=" ";
                        $activation_link = SITE_URL.'Receivers/Survey/'.base64_encode($survey_id)."/".base64_encode($usertype)."/".base64_encode($this->request->data['email']);
                       
                       if(!empty($this->request->data['phone']))   
                           {
                       
                                $mobilecode = $this->request->data['countrycode'];
                                $mobileno = $this->request->data['phone'];
                                $message1 = $query1[0]['description'];
                                
                            $message = str_replace(array('{{first name}}' ,'{{second name}}','{{activation_link}}'), array($firstname,$lastname,$activation_link), $message1);  
                            
                                $msgs = strip_tags($message);
                                 $mobilenumbers = "+".$mobilecode.$mobileno;
                                 $ss = parent::sendSms($mobilenumbers,$msgs);
                          
                          if($ss!=1){      
                        $this->Flash->error(__('Something Went Wrong Please try again.'));
                        return $this->redirect(['controller'=>'Pages','action'=>'submissionform',base64_encode($survey_id)]);
                          }
                            
                           }
                           
                           
                        if(!empty($this->request->data['email'])){
                            $to = $this->request->data['email'];
                            $subject = $query[0]['subject'];
                            $message1 = $query[0]['description'];
                            $message = str_replace(['{{username}}','{{activation_link}}','{{sender}}','{{Message to the receiver}}'],[$this->request->data['name'],
                            $activation_link,$this->Auth->user('first_name')." ".$this->Auth->user('last_name'),$this->request->data['message']],$message1);
                            parent::sendEmail($to, $subject, $message);
                         }
                         
                             
                            $this->Flash->success('Your survey is on the way. You will be notified immediately after your partner completes the survey.');
                            return $this->redirect(array('controller'=>'Pages','action' => 'questionbank'));
                    }
                }
            }else
            {
                $tablePayment=TableRegistry::get('Payments');
                $filter	=[];
                $filter['OR']=[['membership_level'=>$membership['id']]];
                $filter['AND']=['user_id'=>$this->Auth->user('id')];
                $checkPay=$tablePayment->find("all")->where($filter)->order(["id"=>'DESC'])->first();
                if(isset($checkPay)){
                    $currentdate    =   date("Y-m-d");
                    $currentdate    =	date_create($currentdate);
                    $paydate        =   date("Y-m-d",strtotime($checkPay->date));
                    $paydate 	    =	date_create($paydate);
                    $days           =   date_diff($paydate,$currentdate);
                    $days           =   $days->format("%R%a days");
                    if($days >=30){			
                        if($membership['slug']=='gold')
                        {
                          //  die("jgio");
                            $this->Flash->error(__('Your plan has been expired.Please upgrade your plan.'));
                            return $this->redirect(['controller'=>'Pages','action'=>'upgradepayment',base64_encode($survey_id)]);
                        }if($membership['slug']=='platinum'){
                            $this->Flash->error(__('Your plan has been expired.Please upgrade your plan.'));
                            return $this->redirect(['controller'=>'Pages','action'=>'upgradepayment',base64_encode($survey_id)]);
                        }else
                        {
                            $tableSurvey=TableRegistry::get('Surveys');
                            $query = $tableSurvey->query();
                            $query->update()
                                ->set(['modified'=>date("Y-m-d h:i:s"),'status'=>'4'])
                                ->where(['id' => $survey_id,'user_id'=>$user_id])
                                ->execute();
                            $EmailTemplates= TableRegistry::get('Emailtemplates');
                            $query = $EmailTemplates->find('all')->where(['slug' => 'send_survey'])->toArray();
                            $query1 = $EmailTemplates->find('all')->where(['slug' => 'mobile_message'])->toArray();
                            if($query){
                                $usertype="receiver";
                               // $activation_link=" ";
                                $activation_link = SITE_URL.'Receivers/Survey/'.base64_encode($survey_id)."/".base64_encode($usertype)."/".base64_encode($this->request->data['email']);
                              
                             if(!empty($this->request->data['phone']))   
                           {
                             
                                $mobilecode = $this->request->data['countrycode'];
                                $mobileno = $this->request->data['phone'];
                                $message1 = $query1[0]['description'];
                               $message = str_replace(array('{{first name}}' ,'{{second name}}','{{activation_link}}'), array($firstname,$lastname,$activation_link), $message1);  
                                $msgs = strip_tags($message);
                                  $mobilenumbers = "+".$mobilecode.$mobileno; 
                                 $ss = parent::sendSms($mobilenumbers,$msgs);
                          
                          if($ss!=1){      
                        $this->Flash->error(__('Something Went Wrong Please try again.'));
                        return $this->redirect(['controller'=>'Pages','action'=>'submissionform',base64_encode($survey_id)]);
                          }
                            
                           }
                           
                           
                               if(!empty($this->request->data['email'])){  
                                $to = $this->request->data['email'];
                                $subject = $query[0]['subject'];
                                $message1 = $query[0]['description'];
                                $message = str_replace(['{{username}}','{{activation_link}}','{{sender}}','{{Message to the receiver}}'],[$this->request->data['name'],
                                $activation_link,$this->Auth->user('first_name')." ".$this->Auth->user('last_name'),$this->request->data['message']],$message1);
                                parent::sendEmail($to, $subject, $message);
                                
                            }
                            
                                
                                
                                $this->Flash->success('The payment was successful. Your survey is on the way. You will be notified immediately after your partner completes the survey.');
                                return $this->redirect(array('controller'=>'Pages','action' => 'questionbank'));
                            }
                    
                        }
                    }else{
                        $tableSurvey=TableRegistry::get('Surveys');
                        $query = $tableSurvey->query();
                            $query->update()
                                ->set(['modified'=>date("Y-m-d h:i:s"),'status'=>'4'])
                                ->where(['id' =>$survey_id,'user_id'=>$user_id])
                                ->execute();
                        $EmailTemplates= TableRegistry::get('Emailtemplates');
                        $query = $EmailTemplates->find('all')->where(['slug' => 'send_survey'])->toArray();
                        $query1 = $EmailTemplates->find('all')->where(['slug' => 'mobile_message'])->toArray();
                        if($query){
                            $usertype="receiver";
                          //$activation_link=SITE_URL;
                          // $activation_link);d;
                            $activation_link = SITE_URL.'Receivers/Survey/'.base64_encode($survey_id)."/".base64_encode($usertype)."/".base64_encode($this->request->data['email']);
                           
                           
                           if(!empty($this->request->data['phone']))   
                           {
                           
                                $mobilecode = $this->request->data['countrycode'];
                                $mobileno = $this->request->data['phone'];
                                $message1 = $query1[0]['description'];
                            $message = str_replace(array('{{first name}}' ,'{{second name}}','{{activation_link}}'), array($firstname,$lastname,$activation_link), $message1); 
                             $msgs = strip_tags($message);
                                
                                $mobilenumbers = "+".$mobilecode.$mobileno; 
                                 $ss = parent::sendSms($mobilenumbers,$msgs);
                          
                          if($ss!=1){      
                        $this->Flash->error(__('Something Went Wrong Please try again.'));
                        return $this->redirect(['controller'=>'Pages','action'=>'submissionform',base64_encode($survey_id)]);
                          }
                                
                            
                           }
                           
                           
                           if(!empty($this->request->data['email'])){   
                            $to = $this->request->data['email'];
                            $subject = $query[0]['subject'];
                            $message1 = $query[0]['description'];
                            $message = str_replace(['{{username}}','{{activation_link}}','{{sender}}','{{Message to the receiver}}'],[$this->request->data['name'],
                            $activation_link,$this->Auth->user('first_name')." ".$this->Auth->user('last_name'),$this->request->data['message']],$message1);
                            parent::sendEmail($to, $subject, $message);
                           }
                           
                            
                            
                            $this->Flash->success('Your survey was sent successfully. You will receive an email from Self-Match with the link to Compatibility Report after your partner completes the survey.');
                            return $this->redirect(array('controller'=>'Pages','action' => 'aftersubmission'));
                        }
                    }
                }else
                {                   
                    if($membership['slug']=='gold')
                    {
                        $this->Flash->error(__('Your plan has been expired.Please upgrade your plan.'));
                        return $this->redirect(['controller'=>'Pages','action'=>'choosepaymenttype',base64_encode($membership['slug']),base64_encode($survey_id)]);
                    }if($membership['slug']=='platinum'){
                        $this->Flash->error(__('Your plan has been expired.Please upgrade your plan.'));
                        return $this->redirect(['controller'=>'Pages','action'=>'choosepaymenttype',base64_encode($membership['slug']),base64_encode($survey_id)]);
                    }
                }
            }
        }
        
        $tablcountrycode=TableRegistry::get('Countrycode');
		$query3 = $tablcountrycode->find('list',[
           'keyField' => 'phonecode',
            'valueField' => 'phonecode'])
            ->order(['phonecode' => 'ASC']);
        $countrycode = $query3->toArray();
     
        $this->set(['post'=>$post,'countrycode'=>$countrycode]);
    }
    
    
    
    public function aftersubmission(){
        $this->layout='home_page';
    }
    public function feedback($receiver_email = null,$user_id=null,$survey_id=null){
        $this->layout='home_page';
        $receiver_email =  base64_decode($receiver_email);
        $user_id    	=  base64_decode($user_id);
        $survey_id  	=  base64_decode($survey_id);
        $tableSurveyAnswers	= TableRegistry::get('Surveyanswers');
        $tableUser	=    TableRegistry::get('Users');
        $membership    =$tableUser->find("all")->where(['id'=>$user_id])->first();
        //$membershiptype=$membership['membership_level'];
        $membershipTable = TableRegistry::get('Memberships');			
        $membershipName	=$membershipTable->find("all")->where(['id'=>$membership['membership_level']])->first();
        $filter=[];
        $filter['OR']=[['survey_for'=>3],['survey_for'=>4]];
        if($membershipName['slug']=='platinum'){
            $post	=$tableSurveyAnswers->find('all')->where(['receiver_email'=> $receiver_email,'user_id'=>$user_id,$filter])->toArray();
            //pr($post);
        }else{
            $post	=$tableSurveyAnswers->find('all')->where(['receiver_email'=> $receiver_email,'user_id'=>$user_id,'survey_id'=>$survey_id,'survey_for'=>'2'])->toArray();
        }
        $survey_for=isset($post[0]['survey_for'])?$post[0]['survey_for']:"";
		if($this->request->is(['post','put'])){
            $data = $this->request->data;
            //$dataSave = $tableSurveyAnswers->patchEntity($post,$data);
			$this->request->data['question_id'] 	= serialize($this->request->data['question_id']);
			$this->request->data['score_type'] 	    = isset($this->request->data['score_type'])?(serialize($this->request->data['score_type'])):'';
			$this->request->data['score'] 	        = serialize($this->request->data['score']);
			$this->request->data['positive_entry'] = serialize($this->request->data['positive_entry']);
			$this->request->data['negative_entry'] = serialize($this->request->data['negative_entry']);
			$this->request->data['positive_star_value'] = serialize($this->request->data['positive_star_value']);
			$this->request->data['negative_star_value'] = serialize($this->request->data['negative_star_value']);
			$query =$tableSurveyAnswers->query();
            if($survey_for=='2'){
                $query->update()
                   ->set(['score_type'=>$this->request->data['score_type'],
                          'score'=>$this->request->data['score'],
                          'positive_entry'=>$this->request->data['positive_entry'],
                          'negative_entry'=>$this->request->data['negative_entry'],
                          'positive_star_value'=>$this->request->data['positive_star_value'],
                          'negative_star_value'=>$this->request->data['negative_star_value'],
                          'total_positives'=>$this->request->data['total_positives'],
                           'total_negative'=>$this->request->data['total_negative']])
                   ->where(['receiver_email'=>$receiver_email,'user_id'=>$user_id,'survey_for'=>$survey_for,'survey_id'=>$survey_id])
                   ->execute();
            }else{
                $query->update()
                   ->set(['score_type'=>$this->request->data['score_type'],
                          'score'=>$this->request->data['score'],
                          'positive_entry'=>$this->request->data['positive_entry'],
                          'negative_entry'=>$this->request->data['negative_entry'],
                          'positive_star_value'=>$this->request->data['positive_star_value'],
                          'negative_star_value'=>$this->request->data['negative_star_value'],
                          'total_positives'=>$this->request->data['total_positives'],
                           'total_negative'=>$this->request->data['total_negative']])
                   ->where(['receiver_email'=>$receiver_email,'user_id'=>$user_id,$filter])
                   ->execute();
            }
            //return $this->redirect(array('action'=>'feedback',base64_encode($receiver_email),base64_encode($user_id),base64_encode($survey_id)));
			if($query){
               // die("");
				//$this->Flash->success(__('The score has been submited.'));
            	return $this->redirect(array('action'=>'feedback',base64_encode($receiver_email),base64_encode($user_id),base64_encode($survey_id),'#'=>'calc'));
			}
		}
		//$post	=$post->toArray();
		//pr($post);die;
		$this->set('post',$post);
        $this->set(compact('survey_id','user_id'));
    }
    public function addquestion(){
        $this->layout='home_page';
        $table=TableRegistry::get("Questions");
        $post=$table->newEntity();
        if($this->request->is(['post'])){
          
            $data=$this->request->data;
            $post=$table->patchEntity($post,$this->request->data,['validate'=>'QuestionDefault']);
            //$post->user_id=$this->Auth->user('id');
            $post->review = UNDER_REVIEW;
            $post->status =INACTIVE;
             if($table->save($post)){
                $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'question_submission'])->toArray();
                if($query){
                    $activation_link = SITE_URL.'Questions/questionlist/';
                    $tableNew=TableRegistry::get("Users");
                    $p=$tableNew->find("all")->where(['role'=>ADMIN])->first();
                    $to = $p['email'];
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{username}}','{{activation_link}}','{{name}}','{{email}}'],[$p['first_name'],
                    $activation_link,],$message1);
                    parent::sendEmail($to, $subject, $message);
                    $this->Flash->success(__('Thank you! Your question has been submitted for review. You will receive an email notifying you if your question will be added to Question Bank.'));
                    return $this->redirect(array('action' => 'questionbank'));
                }
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
        $table=TableRegistry::get('Categories');
        $query = $table->find('list', [
            'keyField' => 'id',
            'valueField' => 'category_name'])
            ->order(['category_name' => 'ASC']);
        $category = $query->all();
        $this->set(compact('post','category'));
    }
    public function deletepartner($email=null,$user_id=null){
        $email=base64_decode($email);
        $user_id=base64_decode($user_id);
        $table=TableRegistry::get('Receivers');
        $post=$table->find('all')->where(['email'=>$email,'user_id'=>$user_id])->toArray();
       // pr($post);die;
        foreach($post as $val){
            $post12=$table->get($val->id);
            $table->delete($post12);
            
        }
        $this->Flash->success(__('The Partner  has been deleted.'));
        return $this->redirect(array('action'=>'memberdashboard'));
       
    }
    public function deleteSurvey($survey_id=null){
        
        $this->request->allowMethod(['post', 'delete','delete-survey']);
        $id = $this->request->data['id'];
        $table=TableRegistry::get('Savedsurvey');
        $post=$table->get($id);
      //  pr($post);die;
        if($table->delete($post)){
            $this->Flash->success(__('The survey  has been deleted.'));
            return $this->redirect(array('action'=>'surveylist'));
        }
    }
    public function deleteReport($user_id=null,$survey_id=null,$id=null){
        $user_id    =base64_decode($user_id);
        $survey_id  =base64_decode($survey_id);
        $id         =base64_decode($id);
        $table=TableRegistry::get('Surveyanswers');
        $post=$table->find('all')->where(['user_id'=>$user_id,'survey_id'=>$survey_id,'id'=>$id])->first();
        $table->delete($post);
       /* foreach($post as $val){
            $post12=$table->get($val->id);
        }*/
        $this->Flash->success(__('The Report has been deleted.'));
        return $this->redirect(array('action'=>'memberdashboard'));
    }
    public function cancel($survey_id=null){
        $survey_id=base64_decode($survey_id);
        $table=TableRegistry::get('Surveys');
        $post=$table->get($survey_id);
        if($table->delete($post)){
            //$this->Flash->success(__('The survey  has been deleted.'));
            return $this->redirect(array('action'=>'questionbank'));
        }
    }
    public function advertisement($id = null){
        $this->layout='home_page';
        $id =base64_decode($id);
        $tableUsers =TableRegistry::get('Users');
        $user   =  $tableUsers->get($id);
        $refferal_code=$user["refferal_code"];
        $this->set("refferal_code",$refferal_code);
    }
    public function advertisementver($id = null){
        $this->layout='home_page';
        $id =base64_decode($id);
        $tableUsers =TableRegistry::get('Users');
        $user   =  $tableUsers->get($id);
        $refferal_code=$user["refferal_code"];
        $this->set("refferal_code",$refferal_code);
    }
    public function details(){
        $this->layout='home_page';
    }
    public function selfmatchdemo(){
        $this->layout='home_page';
    }
    public function usermembership(){
        $this->layout='home_page';
        $user_id    =$this->Auth->user('id');
        $tableUsers =TableRegistry::get('Users');
        $user_membership = $tableUsers->find('all')->where(['id'=>$user_id])->first();
        $membership =TableRegistry::get('Memberships');
        $membershipDetails= $membership->find('all')->where(['id'=>$user_membership['membership_level']])->first();
      //  pr($membershipDetails['slug']);die;
        $this->set("membershipDetails",$membershipDetails);
    }
    public function searchQuestion($search = null,$survey_id = null){
        $this->layout='home_page';
        $questionstable=TableRegistry::get('Questions');
        $usertable=TableRegistry::get('Users');
        $survey_id = base64_decode($survey_id);
        $search = base64_decode($search);
        $searcData = [];
        $condition='';
        if($search){
            $user_id    =$this->Auth->user('id');
            $user_membership =$usertable->find("all")->where(['id'=>$user_id])->first();
            $membership =TableRegistry::get('Memberships');
            $memberships= $membership->find('all')->where(['id'=>$user_membership['membership_level']])->first();
          
            if($memberships['slug'] =='gold' || ($memberships['slug'] =='visitor' && ($user_membership['survey_type'] =='1' || $user_membership['survey_type'] =='0')) ){  
               
                $searcData=$questionstable->find('all')->where(['OR'=>['question_text Like'=>'%'.$search.'%',
                                                         'option_1 Like'=>'%'.$search.'%',
                                                         'option_2 Like'=>'%'.$search.'%',
                                                         'option_3 Like'=>'%'.$search.'%',
                                                         'option_4 Like'=>'%'.$search.'%'],
                                                  'status'=>ACTIVE,'category_id !=' => INTIMATE]
                                                               )->toArray();
                $condition='1';
               // $this->Flash->error('Please upgrade or purchase membership to access this category.');
                //return $this->redirect(array('action'=>'questionbank'));
            }
            else{
               
                $searcData=$questionstable->find('all')->where(['OR'=>['question_text Like'=>'%'.$search.'%',
                                                         'option_1 Like'=>'%'.$search.'%',
                                                         'option_2 Like'=>'%'.$search.'%',
                                                         'option_3 Like'=>'%'.$search.'%',
                                                         'option_4 Like'=>'%'.$search.'%'],
                                                  'status'=>ACTIVE])->toArray();
            }
            
        }else{
            return $this->redirect(array('action'=>'questionbank'));
        }
        $this->set(['searcData'=>$searcData,'search'=>$search,'survey_id'=>$survey_id,'condition'=>$condition]);
    }
    public function checkPromocode(){
        $this->autoRender = false;
        if($this->request->is(['put','post'])){
            $promocodestable = TableRegistry::get('Promocodes');
            $usertable = TableRegistry::get('Users');
            $membershiptable = TableRegistry::get('Memberships');
            $uid        =$this->Auth->user('id');
            $userdata   =$usertable->get($uid);
            $promocode  =$this->request->data['promocode'];
            $mainPrice  =$this->request->data['mainPrice'];
           // $survey_id  =$this->request->data['survey_id'];
            $membership =$this->request->data['membership'];
            $membershipId=$membershiptable->find('all')->where(['slug'=>$membership])->first();
            $plan       =$membershipId['id'];
            $getpromocodedata = $promocodestable->find('all')->where(['promocode_title'=>$promocode,'status'=>ACTIVE,'type'=>$plan])->first();
            if($getpromocodedata){
                require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
                $test_secret_key = Configure::read('stripe_test_secret_key');
                $setApiKey = Stripe::setApiKey($test_secret_key);
                $getApiKey = Stripe::getApiKey();
                $coupon=\Stripe\Coupon::retrieve($getpromocodedata->promocode_title);
                $days=($getpromocodedata->duration)*30;
                if($coupon){
                    $createdDate = date('Y-m-d',strtotime($getpromocodedata['created']));
                    $currentDate = date('Y-m-d');
                    $promoPrice  = $getpromocodedata['price'];
                    $duretion = $getpromocodedata['duration'];
                    $date1=date_create($createdDate);
                    $date2=date_create($currentDate);
                    $diff=date_diff($date1,$date2);
                    if($plan == 7 || $plan == 8){
                        $dateDiff =  $diff->format("%R%m");
                        if($dateDiff >= 0 && $dateDiff <= $duretion){
                            $responce['status'] = 1;
                            if($mainPrice > $promoPrice){
                                $responce['newprice'] = $mainPrice - $promoPrice;
                                $session->write("couponcode",$getpromocodedata->promocode_title);
                               // $responce['newpriceBase'] = SITE_URL.'pages/paymentPaypal1/'.base64_encode($mainPrice - $promoPrice);
                                $responce['newpriceBaseStrip'] = SITE_URL.'pages/paymentpage/'.base64_encode($mainPrice - $promoPrice)."/".base64_encode($survey_id);
                                $responce['msg'] = "Promo code applied successfully";
                            }else{
                                 $responce['status'] = 2;
                                $responce['msg'] = "Promo code is not applied";
                            }
                        }else{
                            $responce['status'] = 2;
                            $responce['msg'] = "Promo code is not valid";
                        }
                    }else{
                        $dateDiff =  $diff->format("%R%a");
                        if($dateDiff >= 0 && $dateDiff <=$duretion){
                            $responce['status'] = 1;
                            if($mainPrice > $promoPrice){
                                $responce['newprice'] = $mainPrice - $promoPrice;
                                $session->write("couponcode",$getpromocodedata->promocode_title);
                                $responce['newpriceBase'] = SITE_URL.'pages/paymentPaypal1/'.base64_encode($mainPrice - $promoPrice);
                                $responce['newpriceBaseStrip'] = SITE_URL.'pages/paymentpage/'.base64_encode($mainPrice - $promoPrice)."/".base64_encode($survey_id);
                                $responce['msg'] = "Promo code applied successfully";
                            }else{
                                 $responce['status'] = 2;
                                $responce['msg'] = "Promo code not applied";
                            }
                        }else{
                            $responce['status'] = 2;
                            $responce['msg'] = "Promo code is not valid";
                        }
                    }
                }else{
                    $responce['status'] = 2;
                    $responce['msg'] = "Promo code is not valid"; 
                }
            }else{
                $responce['status'] = 2;
                $responce['msg'] = "Promo code is not valid";
            }
        }
        echo  json_encode($responce);
        exit;
    }
    public function paymentPaypal1($uid = null,$survey_id=null,$membership=null,$page_id=null){ 
        
        $this->layout='home_page';
        
       
        $uid=base64_decode($uid);
        $page_id=base64_decode($page_id);
        $survey_id=base64_decode($survey_id);
        $membership=base64_decode($membership);
       $session=$this->request->session();
      
        $membershipTable =TableRegistry::get("Memberships");
        $userMembership = $membershipTable->find("all")->where(['slug'=>$membership])->first();
     
        if( isset($_REQUEST['payment_status']) && $_REQUEST['payment_status'] == "Completed")
        {
            $data['amount'] = 100*($session->read('Payment_Amount'));
            $data['currency'] = $session->read('currencyCodeType');
            $data['balance_transaction'] = $_REQUEST['txn_id'];
            $data['customer_id'] = '';
            //$post->date = date('Y-m-d h:i:s',strtotime($resArray['TIMESTAMP']));
            $data['payment_mode'] = "paypal";
            $data['user_id'] =  $uid;
            $data['survey_id']= $survey_id;
            $data['membership_level']=$userMembership['id'];
              
			$table  =TableRegistry::get("Payments");
			$post=$table->newEntity();
			$post->date =  date("Y-m-d h:i:s");
			$post=$table->patchEntity($post,$data);
			if($table->save($post)){
				$EmailTemplates= TableRegistry::get('Emailtemplates');
				$query = $EmailTemplates->find('all')->where(['slug' => 'send_survey'])->toArray();
				if($query){
					$usertype="receiver";
					$email          =$session->read("receiverEmail"); 
					$name           =$session->read("receiverName");
					$messageReceiver=$session->read("receiverMessage");
					$activation_link = SITE_URL.'Receivers/Survey/'.base64_encode($survey_id)."/".base64_encode($usertype)."/".base64_encode($email);
					$to = $email;
					$subject = $query[0]['subject'];
					$message1 = $query[0]['description'];
					$message = str_replace(['{{username}}','{{activation_link}}','{{sender}}','{{Message to the receiver}}'],[$name,
					$activation_link,$this->Auth->user('first_name')." ".$this->Auth->user('last_name'),$messageReceiver],$message1);
					parent::sendEmail($to, $subject, $message);
					$session->read("receiverEmail","");
					$session->read("receiverName","");
					$session->read("receiverMessage","");
					$session->read("survey_id","");
					$this->Flash->success('The payment was successful. Your survey is on the way. You will be notified immediately after your partner completes the survey.');
					return $this->redirect(array('controller'=>'Pages','action' => 'questionbank'));
				}
			}
               
		}elseif(isset($_REQUEST['payment_status'])){
			 $this->Flash->error($_REQUEST['payer_status']);
			return $this->redirect(['controller'=>'pages','action' => 'choosepaymenttype',base64_encode($membership),base64_encode($uid)]);
		}
		else
		{
			$this->Flash->error("Please try again");
			return $this->redirect(['controller'=>'pages','action' => 'choosepaymenttype',base64_encode($membership),base64_encode($uid)]);
		   
		}
    }
    public function deleteaccount(){
        $user_id=$this->Auth->user('id');
        $table  =TableRegistry::get('Users');
        $user   =$table->get($user_id);
        $memTable=TableRegistry::get('Memberships');
        $membership=$memTable->find("all")->where(['id'=>$user['membership_level']])->first();
       
        $paymentTable  =TableRegistry::get('Payments');
        //$paymnetData =$paymentsTable->find("all",['fields'=>['sum(Payments.amount) AS ctotal']])->where(['user_id'=>$user_id])->toArray();
        // $query = $paymentTable->find()->where(['user_id'=>204245]);
        // $query->select(['sum' => $query->func()->sum('amount')])->group('user_id');
        // $data = $query->toArray();
        
       
        /*$deletedUserTable=TableRegistry::get('Deletedusers');
        $new=$deletedUserTable->newEntity();
        $data['email']= $user['email'];
        $data['name'] = $user['first_name']." ".$user['last_name'];
        $post=$deletedUserTable->patchEntity($new,$data);
        $post->created = date("Y-m-d h:i:s");
        $deletedUserTable->save($post);*/
        if($membership['slug']=='lifetime' || $membership['slug']=='platinum'){
            $query=$table->query();
            $query->update()
            ->set(['cancel_membership'=>'2'])
            ->where(['id'=>$user_id])
            ->execute();
           
            $customer=$paymentTable->find("all")->where(['user_id'=>$user_id])->order(['id'=>'DESC'])->limit(1)->first();
            /*if($customer['payment_mode']=='paypal'){
                $session = $this->request->session();  
                $profile_id = $customer['customer_id'];
                $action = 'Cancel'; //Cancel Suspend Reactivate
                $resArray = $this->PaypalExpressRecurring->ManageRecurringPaymentsProfileStatus($profile_id,$action);
                $ack = strtoupper($resArray["ACK"]);
               // pr($resArray); die;
                if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
                {
                    $PROFILEID = $resArray['PROFILEID'];
                    $PROFILESTATUS = $resArray['PROFILESTATUS'];
                    $TIMESTAMP = $resArray['TIMESTAMP'];
                    $CORRELATIONID = $resArray['CORRELATIONID'];
                    $VERSION = $resArray['VERSION'];
                    $BUILD = $resArray['BUILD'];
                   /* if($table->delete($user)){
                        $this->Flash->success(__('Your account has been deleted.'));
                        return $this->redirect($this->Auth->logout());
                    }
                        //$plan_id = $session->read('plan_id');
                        
                        //$query=$this->Users->query();
                        //$query->update()->set(['plan_id'=>$plan_id,'tx_id'=>$PROFILEID])->where(['id'=>$id])->execute();
                       // $this->Flash->success('Thank you for your payment.');
                    return $this->redirect(['controller'=>'pages','action'=>'memberdashboard']);
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
                    return $this->redirect(['controller'=>'Pages','action'=>'memberdashboard']);
                }
            }*/
            if($customer['payment_mode']=='Stripe'){
                require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
                $test_secret_key = Configure::read('stripe_test_secret_key');
                $setApiKey = Stripe::setApiKey($test_secret_key);
                $getApiKey = Stripe::getApiKey();
                $customer_id=$customer['customer_id'];
                try{
                     // $subscription = $this->Stripe->subscriptionCancel($customer_id);
                    $customernew = \Stripe\Customer::retrieve($customer_id);
                    //pr($customer_id
                    $customernew->cancelSubscription(array('at_period_end' => true));
                    $EmailTemplates= TableRegistry::get('Emailtemplates');
                    $query = $EmailTemplates->find('all')->where(['slug' => 'status_inactive'])->toArray();
                    if($query){
                        $to = $user['email'];
                        $subject = $query[0]['subject'];
                        $message1 = $query[0]['description'];
                        $message = str_replace(['{{first_name}}'],[$user['first_name'],
                        ],$message1);
                        parent::sendEmail($to, $subject, $message);
                        if($membership['slug']=='lifetime') {
							$to = 'support@self-match.com';
							$subject = 'Lifetime Membership cancelled';
							$message = "<p>Dear Admin, <br />&nbsp;</p><p>A member of your website has recently cancelled lifetime membership.</p><p>User :<b>{$user['first_name']} {$user['last_name']}</b></p><p>Best regards, <br />&nbsp;<br />Self-Match Team</p><p><strong>(***&nbsp; Please do not reply to this email ***&nbsp; )</strong></p>";
							parent::sendEmail($to, $subject, $message);
						}
                        if(isset($data[0]['sum'])  && $data[0]['sum'] > 0){
                            $this->Flash->success(__('Your plan subscription has been canceled. Your membership benefits will continue until the end of the billing cycle. After that you can continue using Self-Match.com as a Visitor.'));
                            return $this->redirect(['controller'=>'pages','action'=>'memberdashboard']);
                        }else{
                             return $this->redirect(['controller'=>'users','action'=>'logout/deleteaccount']);
                        }
                        //return $this->redirect(array('action' => 'questionbank'));
                    }
                }catch (\Stripe\Error\Base $e) {
                    //echo $e->getMessage(); die;
                    $this->Flash->error($e->getMessage());
                    $this->redirect(array('controller'=>'Pages','action'=>'memberdashboard'));
                }
                // cancelSubscription() is a function in Stripe_Customer Class,
                // So without getting subscription detail direct cancel the subscription n pa
            }else{
                $this->Flash->success(__('Invalid customer details.'));
                return $this->redirect(['controller'=>'pages','action'=>'memberdashboard']);
            }
           
        }   
    }
    public function cardupdate(){
        $this->layout='home_page';
        $user_id=$this->Auth->user('id');
        $paymentTable  =TableRegistry::get('Payments');
        $Countriestable=TableRegistry::get('Countries');
        $query = $Countriestable->find('list', ['keyField' => 'id','valueField' => 'name'])->order(['name' => 'ASC']);
        $countries = $query->toArray();
       	$countries	=array("223"=>"United States")+ $countries ;
        
        $statestbl=TableRegistry::get('States');
        $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
        $states 	= $Statesqery->toArray();
        $cities=[];
        if($this->request->is(['put','post'])){
            $customer    =$paymentTable->find('all')->where(['user_id'=>$user_id])->first();
            if($customer['payment_mode']=='Stripe'){
                require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
                $test_secret_key = Configure::read('stripe_test_secret_key');
                $setApiKey = Stripe::setApiKey($test_secret_key);
                $getApiKey = Stripe::getApiKey();
                $customer_id=$customer['customer_id'];
               // pr($this->request->data['stripeToken']);die;
                if (isset($this->request->data['stripeToken'])){
                    try{
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
                        //pr($getToken);die;
                    }catch (\Stripe\Error\Base $e) {
                        echo $e->getMessage();die;
                        $this->Flash->error($e->getMessage());
                        $this->redirect(array('controller'=>'Pages','action'=>'cardupdate'));
                    }
                      try {
                                $cu = \Stripe\Customer::retrieve($customer_id); // stored in your application
                                $cu->source = $getToken->id; // obtained with Checkout
                                $cu->save();
                                $success = "Your card details have been updated!";
                                $this->Flash->success($success);
                                $this->redirect(array('controller'=>'Pages','action'=>'memberdashboard'));
                            }
                            catch(\Stripe\Error\Card $e) {
                                $body = $e->getJsonBody();
                                $err  = $body['error'];
                                $error = $err['message'];
                                pr($error);die;
                                $this->Flash->error($error);
                                $this->redirect(array('controller'=>'Pages','action'=>'cardupdate'));
                            }
                // Add additional error handling here as needed
                }
            }
        }
        $this->set(compact("countries","states","cities"));
    }
    public function checkmembership(){
        $this->autoRender=false;
        $table  =TableRegistry::get('Users');
        $from = date("Y-m-d");
        $filters['cancel_membership'] = 2;
        $filters['user_expire <'] = $from;
        $expireusers = $table->find('all')->where($filters)->toArray();
        if($expireusers){
            $memTable=TableRegistry::get('Memberships');
            $membership=$memTable->find("all")->where(['slug'=>'visitor'])->first();
            foreach($expireusers as $val){
                $query=$table->query();
                $query->update()
                ->set([
                       'membership_level'=>$membership['id'],
                       'cancel_membership'=>0,
                       ])
                ->where(['id'=>$val->id])
                ->execute();
            
            }
        }
    }
    
    
    public function stripeWebhook(){
        
        $this->autoRender=false;

        require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
        $test_secret_key = Configure::read('stripe_test_secret_key');
        $setApiKey = Stripe::setApiKey($test_secret_key);
        $getApiKey = Stripe::getApiKey();
        \Stripe\Stripe::setApiKey($getApiKey);
        
        $body = @file_get_contents('php://input');
        $event_json = json_decode($body);
        $event_id = $event_json->data;
       
        if($event_json){
           
            $type =  $event_json->type;
            if($type == "charge.succeeded"){
                $created = date('Y-m-d',$event_json->created);
                $balance_transaction =  $event_json->data->object->balance_transaction;
                $customer = $event_json->data->object->customer;
                $amount =  $event_json->data->object->amount;
                $paymentsTbl = TableRegistry::get('Payments');
                $paymentsUserdata = $paymentsTbl->find('all')->where(['customer_id'=>$customer])->order(['id'=>'DESC'])->toArray();
                if($paymentsUserdata){
                    $user_id = $paymentsUserdata[0]['user_id'];
                    $membership_level = $paymentsUserdata[0]['membership_level'];
					
                    $paymentsentity = $paymentsTbl->newEntity();
                    $paymentsentity->customer_id 		= $customer;
                    $paymentsentity->subscription_id 		=$paymentsUserdata[0]['subscription_id'];
                    $paymentsentity->amount 	                = $amount;
                    $paymentsentity->balance_transaction 	= $balance_transaction;
                    $paymentsentity->payment_mode 	        = "Stripe";
                    $paymentsentity->currency 	                = "usd";
                    $paymentsentity->user_id 	                = $user_id;
                    $paymentsentity->membership_level 	        = $membership_level;
                    $paymentsentity->date			= $created;
                    $result = $paymentsTbl->save($paymentsentity);
                    
                    $userNextExpireDate = date('Y-m-d', strtotime('+1 month'));
					if($membership_level==7)
						$userNextExpireDate = null;
                    $UsersTbl = TableRegistry::get('Users');
                    $query = $UsersTbl->query();
                    $query->update()
                            ->set([
                                   'user_expire' => $userNextExpireDate,
                                   ])
                        ->where(['id' => $user_id])
                        ->execute();
						
					$UsersStripeBalances = TableRegistry::get('UsersStripeBalances');
                    $UsersStripeBalancesentity = $UsersStripeBalances->newEntity();
                    
                    $UsersStripeBalancesentity->customer_id 		= $customer;
                    $UsersStripeBalancesentity->user_id 		        = $user_id;
                    $UsersStripeBalancesentity->balance 	                = -$amount;
                    $UsersStripeBalancesentity->created			= $created;
                    $result = $UsersStripeBalances->save($UsersStripeBalancesentity);
                }
            }
			elseif($type == "invoiceitem.created"){
                // $created = date('Y-m-d',$event_json->created);
                // $amount =  $event_json->data->object->amount;
                // $customer =  $event_json->data->object->customer;
                // if($amount){
                    // $paymentsTbl = TableRegistry::get('Payments');
                    // $paymentsUserdata = $paymentsTbl->find('all')->where(['customer_id'=>$customer])->order(['id'=>'DESC'])->first();
                    // $user_id = $paymentsUserdata['user_id'];
                    // $UsersStripeBalances = TableRegistry::get('UsersStripeBalances');
                    // $UsersStripeBalancesentity = $UsersStripeBalances->newEntity();
                    
                    // $UsersStripeBalancesentity->customer_id 		= $customer;
                    // $UsersStripeBalancesentity->user_id 		        = $user_id;
                    // $UsersStripeBalancesentity->balance 	                = $amount;
                    // $UsersStripeBalancesentity->created			= $created;
                    // $result = $UsersStripeBalances->save($UsersStripeBalancesentity);
                // }               
                
            }
			elseif($type == "customer.updated"){
                // $created = date('Y-m-d',$event_json->created);
                // $amount =  $event_json->data->object->account_balance;
                // $customer =  $event_json->data->object->id;
                // if($amount){
                    // $paymentsTbl = TableRegistry::get('Payments');
                    // $paymentsUserdata = $paymentsTbl->find('all')->where(['customer_id'=>$customer])->order(['id'=>'DESC'])->first();
                    // $user_id = $paymentsUserdata['user_id'];
                    
                    // $UsersStripeBalances = TableRegistry::get('UsersStripeBalances');
                    // $UsersStripeBalancesentity = $UsersStripeBalances->newEntity();
                    
                    // $UsersStripeBalancesentity->customer_id 		= $customer;
                    // $UsersStripeBalancesentity->user_id 		        = $user_id;
                    // $UsersStripeBalancesentity->balance 	                = $amount;
                    // $UsersStripeBalancesentity->created			= $created;
                    // $result = $UsersStripeBalances->save($UsersStripeBalancesentity);
                // }
            }
        }
        
        $dir = explode('/',WWW_ROOT);
        unset($dir[(count($dir))-2]);
        $newdir = implode('/',$dir);
       
        $filename = time()."newfile.txt";
        $fp = fopen( $newdir."/src/Controller/webhookFile/".$filename,"wb");
        fwrite($fp,$body);
        fclose($fp);
        http_response_code(200); // PHP 5.4 or greater
        exit;
    }
     public function siteMap(){
        $this->layout='home_page';
    }
    
    
}
