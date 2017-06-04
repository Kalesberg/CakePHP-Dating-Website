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
use App\Database\Expression\BetweenComparison;
/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class AffiliatesController extends AppController
{
    
     /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie', ['expiry' => '1 day']);
    }
    public function beforeFilter(Event $event)
    {
       parent::beforeFilter($event);
       $this->Auth->allow(['logout','login','register','invite','verify','forgotpassword','resetpassword','states','cities']);
       
    }
    public function isAuthorized($user)
    { 
         if(isset($user['role']) && $user['role'] === AFFILIATE){
            if (in_array($this->request->action, ['affiliateDetail','manageprofile'])) { 
                    return true;
            }
         }
         return false;
    }
    public function affiliateDetail(){
        $this->layout='home_page';
        $userTable =TableRegistry::get('Users');
        $from = date('Y-m-01'); 
        $to  = date('Y-m-t');
        $uid = $this->Auth->user('id');
        $post=$userTable->find("all")->where(['id'=>$uid])->toArray();
        $filters[] = array("DATE_FORMAT(`created`, '%Y-%m-%d') between '$from' AND '$to' ");
        $filters['reference_id'] =  $uid;
        $filters['status'] =  ACTIVE;
        $userdata=$userTable->find('all')->where($filters)->toArray();
        $thisMonthUser	= count($userdata);
		$refferedusers	=$userTable->find("all")->where(['reference_id'=>$uid,'status'=>ACTIVE])->toArray();
		$paymentTable	=TableRegistry::get('payments');
		$globalTable 	=TableRegistry::get('Globalsettings');
        $findBenifit	=$globalTable->find('all')->where(['slug'=>'RefferalBenifit'])->first();
		$findBenifit 	=$findBenifit->toArray();
		$commPercent 	=$findBenifit['value'];
        //pr($commPercent);die;
		if($refferedusers){
            $totalcomm=[];
			$totalpaidamount=[];
			
			foreach($refferedusers as $val){
				$reffered 		=	$paymentTable->find("all")->where(['user_id'=>$val->id,'customer_id !='=>''])->toArray();
				
				if(!empty($reffered)){
					$amount		   	=	$reffered[0]['amount']/100;
					$comm			=	($amount)*($commPercent/100);
					$totalcomm[]	=	$comm;	
				}
			}
			$manuallypaid 	=	$paymentTable->find("all")->where(['user_id'=>$uid ])->toArray();
			if(!empty($manuallypaid)){
				//$totalpaidamount=[];
				foreach($manuallypaid as $val){
					$totalpaidamount[]		=	$val->amount;
				}
			}
			$totalcomm		=array_sum($totalcomm);
          //  pr($totalcomm);die;
			$totalpaidamount=array_sum($totalpaidamount)/100;
			$duecommission	=($totalcomm-$totalpaidamount);
		}
        $this->set(compact('post','thisMonthUser','totalpaidamount','totalcomm','duecommission'));
    }
    
   public function login(){
        $this->layout='home_page';
        $uid = $this->Auth->user('id');
        if($uid){
             return $this->redirect(array('controller'=>'Affiliates','action'=>'affiliateDetail'));
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
                        if($user['role'] == AFFILIATE){
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
                           
                            return $this->redirect(array('controller'=>'Affiliates','action'=>'affiliateDetail'));
                        }
                        else if(isset($user['status']) && $user['status']==INACTIVE)
                        { 
                            $this->Flash->error(__('Please verify your account.'));
                            return $this->redirect(array('controller'=>'Affiliates','action'=>'login'));
                        }
                        }else{
                             $this->Flash->error(__('This email address is invalid.'));
                            return $this->redirect(array('controller'=>'Affiliates','action'=>'login'));
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
                        return $this->redirect(array('controller'=>'Affiliates','action'=>'login'));
                    }
                }
                else
                {
                    $session->write('blocked',true);
                   // $this->Flash->error('This email id has been blocked');
                    return $this->redirect(array('controller'=>'Affiliates','action'=>'login'));
                }
            }
            else
            {
                $this->Flash->error(__('This email address is invalid.'));
                return $this->redirect(array('controller'=>'Affiliates','action'=>'login'));
            }
        }
        $this->set('session',$session);
        $this->set('cookieData',$cookie);
    }
    public function register()
    {
        $uid = $this->Auth->user('id');
        if($uid){
             return $this->redirect($this->Auth->redirectUrl());
        }
        $this->layout='home_page';
        $table=TableRegistry::get("Users");
        $post=$table->newEntity();
        $session=$this->request->session();
        $showmsg = 1;
	
        $Countriestable=TableRegistry::get('Countries');
        $query = $Countriestable->find('list', ['keyField' => 'id','valueField' => 'name'])->order(['name' => 'ASC']);
        $countries = $query->toArray();
       	$countries	=array("231"=>"United States")+ $countries ;
        
	
        $statestbl=TableRegistry::get('States');
        $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>'223'])->order(['name' => 'ASC']);
        $states 	= $Statesqery->toArray();
	
        $cities=[];
        if($this->request->is('post')){
	    
			//pr($this->request->data);die;
            if(empty($this->request->data['g-recaptcha-response'])){
                $this->Flash->error('Please select the captcha.');
                $showmsg = 2;
            }else{ 
                $email=$this->request->data['email'];
                $user_name=$this->request->data['username'];
                $password=$this->request->data['password'];
                $post=$table->patchEntity($post,$this->request->data,['validate'=>'AffiliateDefault']);
                $post->created = date("Y-m-d h:i:s");
                $post->status ='2';
                $post->role ='3';
                $post->temppass=$password;
                $first_name =$this->request->data['first_name'];
                $last_name  =$this->request->data['last_name'];
                $letter_one     =substr($first_name,'0','1');
                $letter_two     =substr($last_name,'0','1');
                $code           =rand('100','999');
                $refferal_code  ="SM".strtoupper($letter_one).strtoupper($letter_two).$code;
                $post->refferal_code =$refferal_code;
               // pr($post);die;
                if($result=$table->save($post)){
                    $EmailTemplates= TableRegistry::get('Emailtemplates');
                    $query = $EmailTemplates->find('all')->where(['slug' => 'affiliate_registration'])->toArray();
                    if($query){
                       // $activation_link = "<a href='".SITE_URL.'Affiliates/verify/'.base64_encode($result->id)."'>here</a>";
                        $to = $email;
                        $subject = $query[0]['subject'];
                        $message1 = $query[0]['description'];
                        $message = str_replace(['{{first name}}'],[$first_name],$message1);
                        parent::sendEmail($to,$subject, $message);
                        $this->Flash->success('Thank you for applying to Self-Match Affiliate Program. We will notify you of the status of your application shortly.');
                        return $this->redirect(['controller'=>'Affiliates','action' => 'login']);
                    }
                }else{
				//	die("gnfj");
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
        $tablcountrycode=TableRegistry::get('Countrycode');
		$query3 = $tablcountrycode->find('list',[
           'keyField' => 'phonecode',
            'valueField' => 'phonecode'])
            ->order(['phonecode' => 'ASC']);
        $countrycode = $query3->toArray();
        $this->set(compact('post','countries','states','showmsg','cities','countrycode'));
    
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
            ->order(['country_id' => 'ASC']);
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
    public function verify($id=null){
        $id=base64_decode($id);
        $userTable = TableRegistry::get('Users');
        $query = $userTable->query();
        $query->update() ->set(['status' => ACTIVE ])->where(['id' => $id])->execute();
        $this->Flash->success('Your account has been activated.');
        return $this->redirect(['controller'=>'Affiliates','action' => 'login']);

    }
    public function invite(){
        $this->autoRender=false;
        $this->layout='home_page';
        $id =$this->Auth->user('id');
        $userTable = TableRegistry::get('Users');
        $post=$userTable->get($id);
        if($this->request->is('post')){
            $email = $this->request->data['email'];
            //pr($email);die;
            $EmailTemplates= TableRegistry::get('Emailtemplates');
            $query = $EmailTemplates->find('all')->where(['slug' => 'referral_invitation'])->toArray();
            if($query){
                $activation_link = SITE_URL.'freetrial/freeregister/'.base64_encode($post->refferal_code);
                $to = $email;
                $subject = $query[0]['subject'];
                $message1 = $query[0]['description'];
                $message = str_replace(['{{username}}','{{activation_link}}'],[ucfirst($post->first_name)." ".ucfirst($post->last_name),$activation_link],$message1);
                parent::sendEmail($to, $subject, $message);
                $this->Flash->success('Your invitation has been sent.');
                return $this->redirect(['controller'=>'Affiliates','action' => 'affiliateDetail']);
            }
        }
    }
     public function manageprofile(){
        $this->layout='home_page';
        $id=$this->Auth->user('id');
       // $id=base64_decode($id);
        //pr($id);die;
        $table=TableRegistry::get('Users');
        $post=$table->get($id);
            $country = $post->country;
            $region = $post->region;
            //echo $region;die; 
            $statestbl=TableRegistry::get('States');
            $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>$country])->order(['name' => 'ASC']);
            $states 	= $Statesqery->toArray();
            
            $citiestbl=TableRegistry::get('Cities');
            $citiesqery = $citiestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['state_id'=>$region])->order(['name' => 'ASC']);
            $cities 	= $citiesqery->toArray();
           // pr($cities);die;
        if($this->request->is(['post','put'])){
            $data=$this->request->data;
            $table=TableRegistry::get('Users');
            $post=$table->find()->where(['`id`' => $this->request->data['id']])->first();
            $post=$table->patchEntity($post,$this->request->data,[ 'validate' => 'AffiliateProfileDefault']);
            if(!empty($this->request->data['profile_photo']['name'])){
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG'){
                    $filepath = getcwd() . '/img/user_images/' . $imagename;
                    $post->profile_photo =$imagename ;
                    $post->modified = date("Y-m-d h:i:s");
                    if($table->save($post)){
                        if(!empty($imagename)){ 
                            move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
                            chmod($filepath, 0777);
                        }
                       // die("ghfntg");
                        $this->Flash->success(__('Your profile has been updated.'));
                        return $this->redirect(array('controller'=>'Affiliates','action' => 'affiliateDetail'));
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
                    if($table->save($post)){
                        $this->Flash->success(__('Your profile has been updated.'));
                        return $this->redirect(array('action' => 'affiliateDetail'));
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
        //rsort($sites);
        $this->set(compact('post','countries','cities','states'));
        
    }
    function forgotpassword() {
        $this->layout='home_page';
        if (!empty($this->request->data)) {
            $email=$this->request->data['email'];
            $Users= TableRegistry::get('Users');
            $post = $Users->find('all')->where(['email' =>$email,"role"=>AFFILIATE])->toArray();
            if (empty($post)) {
                $this->Flash->error('Sorry, the username entered was not found.');
               return $this->redirect(['controller'=>'Affiliates','action' => 'forgotpassword']);
            }else {
                $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'forgot_password'])->toArray();
                if($query){
                    $activation_link = SITE_URL.'Affiliates/resetpassword/'.base64_encode($post[0]['id']);
                    $to = $email;
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{username}}','{{activation_link}}'],[$post[0]['username'],$activation_link],$message1);
                    parent::sendEmail($to, $subject, $message);
                    $this->Flash->success('Password reset instructions have been sent to your email address.
						You have 24 hours to complete the request.');
                   return $this->redirect(['controller'=>'Affiliates','action' => 'login']);
                }
            }
        }
    }
    public function resetpassword($id= null){
        $this->layout='home_page';
        $ids = $id;
        $id = base64_decode($id);
        $table = TableRegistry::get('Users');
        $post =$table->findById($id)->toArray();
        if ($this->request->is(['post','put'])) {
           // $hasher = new DefaultPasswordHasher();
            $post = $table->find('all')->where(['id'=>$this->request->data['id']])->first();
            $post = $table->patchEntity($post,$this->request->data);
            if($this->request->data['newpassword']==$this->request->data['cpassword']){
                $post->password=$this->request->data['newpassword'];
                $post->modified = date("Y-m-d h:i:s");
                $post->logincount='0';
                if($table->save($post)){
                    $session =$this->request->session();
                    $session->destroy("blocked");
                    $this->Flash->success(__('Your password has been successfully updated.'));
                    return $this->redirect(['controller'=>'Affiliates','action' => 'login']);
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
                           return $this->redirect(['controller'=>'Affiliates','action' => 'resetpassword/'.$ids]);
                }
            }else{
               $this->Flash->error("Passwords do not match.");
              
            }
        }
    $this->set(compact('id','post'));
    }
    public function logout()
    {
        //$this->Cookie->delete('remember_me');
        $this->Flash->success(__('Logout successful'));
        return $this->redirect($this->Auth->logout());
    }
}
?>