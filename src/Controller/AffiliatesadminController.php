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
class AffiliatesadminController extends AppController
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
       $this->Auth->allow(['logout','login']);
       
    }
    public function isAuthorized($user)
    { 
         if(isset($user['role']) && $user['role'] === ADMIN){ 
              if (in_array($this->request->action,['history','approve','affiliatelist','paymenttoaffiliate','delete','refferallist',
                                                   'affiliateprofile','addaffiliate','editstatus','editaffiliate'])) { 
                   return true;
              }
         }
       return false;
    }

    public function affiliatelist(){
        $this->layout='admin';
        $userTable=TableRegistry::get("Users");
        $post=$userTable->find('all')->where(['role'=>AFFILIATE])->toArray();
		$this->set(compact('post','comm','pending','totalRefferals'));
    }
    public function refferallist(){
        $this->layout='admin';
        $table=TableRegistry::get("Users");
        $post=$table->find('all')->where(["AND"=>['role'=>USER],['reference_id !='=>0]])->toArray();
        $this->set('post',$post);
    }
    public function delete(){
        $table=TableRegistry::get("Users");
        $this->request->allowMethod(['post', 'delete']);
        $id = $this->request->data['id'];
        $post=$table->get($id);
        if($table->delete($post)){
			$EmailTemplates= TableRegistry::get('Emailtemplates');
           	$query = $EmailTemplates->find('all')->where(['slug' => 'account_delete'])->toArray();
			if($query){
				$to = $post->email;
				$subject = $query[0]['subject'];
				$message1 = $query[0]['description'];
				$message = str_replace(['{{username}}'],[$post->first_name],$message1);
				parent::sendEmail($to, $subject, $message);
				//$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
				//return $this->redirect(['controller'=>'Users','action' => 'login']);
			}
            $this->Flash->success(__('The user has been deleted.'));
            return $this->redirect(array('action'=>'affiliatelist'));
        }
    }
    public function affiliateprofile($id=null){
        $this->layout='admin';
        $id=base64_decode($id);
        $userTable=TableRegistry::get("Users");
        $post=$userTable->get($id);
       	$refferedusers	=$userTable->find("all")->where(['reference_id'=>$id,'status'=>ACTIVE])->toArray();
		$paymentTable	=TableRegistry::get('payments');
		$globalTable 	=TableRegistry::get('Globalsettings');
        $findBenifit	=$globalTable->find('all')->where(['slug'=>'RefferalBenifit'])->first();
		$findBenifit 	=$findBenifit->toArray();
		$commPercent 	=$findBenifit['value'];
		if($refferedusers){
			//$totalcommision=[];
            $totalcomm=[];
            $totalpaidamount=[];
			$ActiveRefferalsCount = 0;
			foreach($refferedusers as $val){
				$reffered 		=	$paymentTable->find("all")->where(['user_id'=>$val->id,'customer_id !='=>''])->toArray();
				$ActiveRefferalsCount = $ActiveRefferalsCount + count($reffered);
				if(!empty($reffered)){
					$amount		   	=	$reffered[0]['amount']/100;
					$comm			=	($amount)*($commPercent/100);
					$totalcomm[]	=	$comm;	
				}
				
			}
			$manuallypaid 	=	$paymentTable->find("all")->where(['user_id'=>$id])->toArray();
			if(!empty($manuallypaid)){
				
				foreach($manuallypaid as $val){
					$totalpaidamount[]		=	$val->amount;
				}
				
			}
			$totalcomm		=array_sum($totalcomm);
			$totalpaidamount=array_sum($totalpaidamount)/100;
			$duecommission	=($totalcomm-$totalpaidamount);
		}
        $this->set(compact('post','totalcomm','totalpaidamount','duecommission','ActiveRefferalsCount'));
    }
    public function addaffiliate()
    {
        $this->layout='admin';
        $table=TableRegistry::get("Users");
        $post=$table->newEntity();
        if($this->request->is('post')){
            $email=$this->request->data['email'];
            $user_name=$this->request->data['username'];
            $password=$this->request->data['password'];
            $post=$table->patchEntity($post,$this->request->data,['validate'=>'AffiliateDefault']);
			$imagename=!empty($this->request->data['profile_photo']['name'])?$this->request->data['profile_photo']['name']:"user_default.jpeg";
            if($imagename)
            {
				$ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG')
                {
						$filepath = getcwd() . '/img/user_images/' . $imagename;
                    	$post->profile_photo =  $imagename ;
						$post->created = date("Y-m-d h:i:s");
						$post->status ='2';
						$post->role ='3';
						$first_name =$this->request->data['first_name'];
						$last_name  =$this->request->data['last_name'];
						$letter_one     =substr($first_name,'0','1');
						$letter_two     =substr($last_name,'0','1');
						$code           =rand('100','999');
						$refferal_code  ="SM".strtoupper($letter_one).strtoupper($letter_two).$code;
						$post->refferal_code =$refferal_code;
						if($result=$table->save($post)){
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
							$this->Flash->success('Affiliate has been added');
							return $this->redirect(['controller'=>'Affiliatesadmin','action' => 'affiliatelist']);
					
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
				else{
                     $this->Flash->error("Please upload any gif,png,jpg type file.");
                    return $this->redirect(array('controller'=>'Users','action' => 'adduser'));
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
	
	
	
	
		
        //rsort($sites);
        $this->set(compact('post','countries','states','cities'));
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
            $post->modified = date("Y-m-d h:i:s");
			$userTable->save($data);
			if($mainStatus[$status]==ACTIVE){
				$EmailTemplates= TableRegistry::get('Emailtemplates');
				$query = $EmailTemplates->find('all')->where(['slug' => 'status_active'])->toArray();
				if($query){
					$to = $data->email;
					$subject = $query[0]['subject'];
					$message1 = $query[0]['description'];
					$message = str_replace(['{{username}}'],[$data->first_name],$message1);
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
					$message = str_replace(['{{username}}'],[$data->first_name],$message1);
					parent::sendEmail($to, $subject, $message);

				}
			}
			echo "Record updated successfully.";
			exit;
		}
    }
     public function approve(){
        $this->autoRender=false;
        $this->request->allowMethod(['post', 'approve']);
        $id = $this->request->data['id'];
        $userTable = TableRegistry::get('Users');
        $post=$userTable->get($id);
        if($this->request->data['value']=="5"){
            $post->review=REJECTED;
            $userTable->save($post);
               $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'affiliate_denied'])->toArray();
                if($query){
                    //$activation_link = SITE_URL.'Users/Verify/'.base64_encode($post->category_id);
                    $to = $post->email;
                   // pr($to);die;
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{first_name}}'],[$post->first_name,
                    $activation_link],$message1);
                    parent::sendEmail($to, $subject, $message); 
                    return $this->redirect(array('action' => 'affiliatelist'));
                }
        }else{
            $post->review=APPROVED;
            $password=$post->temppass;
            $post->temppass="";
            //$post->status=ACTIVE;
            $userTable->save($post);
                $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'affiliate_approved'])->toArray();
                if($query){
                    $activation_link = "<a href='".SITE_URL.'Affiliates/verify/'.base64_encode($id)."'>here</a>";
                    $to = $post->email;
                   // pr($to);die;
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{first_name}}','{{activation_link}}','{{email}}','{{username}}','{{password}}'],[$post->first_name,
                    $activation_link,$post->email,$post->username,$password],$message1);
                    parent::sendEmail($to, $subject, $message);
                    return $this->redirect(array('action' => 'affiliatelist'));
                }
        }
         exit;
    }
    public function editaffiliate($id=null){
        $this->layout="admin";
        $id=base64_decode($id);
        $userTable = TableRegistry::get('Users');
        $post=$userTable->get($id);
            $country = $post->country;
            $region = $post->region;
            $statestbl=TableRegistry::get('States');
            $Statesqery = $statestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['country_id'=>$country])->order(['name' =>'ASC']);
            $states 	= $Statesqery->toArray();

            $citiestbl=TableRegistry::get('Cities');
            $citiesqery = $citiestbl->find('list', ['keyField' => 'id','valueField' => 'name'])->where(['state_id'=>$region])->order(['name' => 'ASC']);
            $cities 	= $citiesqery->toArray();
        if($this->request->is(['post','put'])){
           
            $data=$this->request->data;
            //$p=$userTable->find('all')->where(['id' => $this->request->data['id']])->first();
            $post=$userTable->patchEntity($post,$this->request->data,[ 'validate' => 'AffiliateProfileDefault']);
            if(!empty($this->request->data['profile_photo']['name'])){
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG'){
                    $filepath = getcwd() . '/img/user_images' . $imagename;
                    $post->profile_photo =$imagename ;
                    $post->modified = date("Y-m-d h:i:s");
                    if($userTable->save($post)){
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
                        return $this->redirect(array('action' => 'affiliatelist'));
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
                return $this->redirect(array('controller'=>'Affiliateadmin','action' => 'editaffiliate',base64_encode($this->request->data['id'])));
            }else{
                    $post->profile_photo =$this->request->data['photo'];
                    $post->modified = date("Y-m-d h:i:s");
                    if($userTable->save($post)){
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
                        return $this->redirect(array('action' => 'affiliatelist'));
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
        $this->set(compact('post','countries','states','cities'));
          
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
	public function paymenttoaffiliate($id = null){
		$this->layout="admin";
        $id		    =	base64_decode($id);
		$userTable 	=	TableRegistry::get('Users');
		$user       =   $userTable->get($id);
		$paymentTable 	=	TableRegistry::get('payments');
		$post	=	$paymentTable->newEntity();
		if($this->request->is('post')){
           // pr($this->request->data);die;
			$post	=	$paymentTable->patchEntity($post,$this->request->data);
			$post->amount	=($this->request->data['amount'])*100;
			$post->user_id	=$id;
			$post->currency	="aud";
			$post->date 	=$this->request->data['date'];
			if($paymentTable->save($post)){
				$EmailTemplates= TableRegistry::get('Emailtemplates');
				$query = $EmailTemplates->find('all')->where(['slug' => 'payment_to_affiliate'])->toArray();
				if($query){
					$to = $user->email;
					$subject = $query[0]['subject'];
					$message1 = $query[0]['description'];
					$message = str_replace(['{{username}}'],[$user->first_name],$message1);
					parent::sendEmail($to, $subject, $message);
					//$this->Flash->success('Thank you for registering with us. A mail has been sent to your email address with all details. Please verify your email address by clicking on available link to access your account.');
					//return $this->redirect(['controller'=>'Users','action' => 'login']);
				}
				$this->Flash->success('Details has been saved');
                return $this->redirect(['controller'=>'Affiliatesadmin','action' => 'affiliateprofile',base64_encode($id)]);
			}
		}
		$this->set('post',$post);
	}
	public function history($id = null){
		$this->layout="admin";
        $id		=	base64_decode($id);
		$paymentTable 	=	TableRegistry::get('payments');
		$post	=	$paymentTable->find('all')->where(['user_id'=>$id])->toArray();
		$this->set('post',$post);
	}
    public function logout()
    {
        //$this->Cookie->delete('remember_me');
        $this->Flash->success(__('Logout successful'));
        return $this->redirect($this->Auth->logout());
    }
}
?>