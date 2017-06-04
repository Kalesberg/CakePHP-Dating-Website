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
class PromocodesController extends AppController
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
            if (in_array($this->request->action, ['promocodelist','addpromocode','editpromocode','delete','editstatus'])) { 
                   return true;
              }
         }
       return false;
    }
     public function promocodelist(){
        $this->layout='admin';
        $table=TableRegistry::get("Promocodes");
        $post=$table->find('all')->order(['id'=>'DESC'])->toArray();
        $this->set(compact('post'));
     }
     public function addpromocode(){
        $this->layout='admin';
        $table=TableRegistry::get('Memberships');
        $post=$this->Promocodes->newEntity();
        if($this->request->is('post')){
           // pr($this->request->data);die;
            $post=$this->Promocodes->patchEntity($post,$this->request->data,['validate'=>'Default']);
            $post->created = date("Y-m-d h:i:s");
            $membership=$table->find("all")->where(['id'=>$this->request->data['type']])->first();
            if($membership['slug']=='gold' || $membership['slug']=='platinum'){
                $duration='repeating';
                $durationType = "duration_in_months";
                
            }if($membership['slug']=='visitor'){
                $duration='once';
                $durationType = "duration_in_days";
            }
            
            if($this->Promocodes->save($post)){
                    require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
                    $test_secret_key = Configure::read('stripe_test_secret_key');
                    $setApiKey = Stripe::setApiKey($test_secret_key);
                    $getApiKey = Stripe::getApiKey();
                    $date = date('Y-m-d');
                    if(!empty($getApiKey)){
                        try{
                            $coupon= \Stripe\Coupon::create(array(
                                "amount_off"    => round(($post->price)*100),
                                "currency"      => "usd",
                                "duration"      => $duration,
                                $durationType   => $post->duration,
                                "id"            => $post->promocode_title,
                                //"max_redemptions" => 2,
                                //"redeem_by" =>  strtotime($date),
                            ));
                            //pr($coupon);die;
                            //die("gfkolk");
                        }catch (\Stripe\Error\Base $e){
                            pr($e->getMessage());
                            $this->Flash->set($e->getMessage(),['params'=>['class' => 'alert danger']]);
                           // $this->redirect(array('controller'=>'promocodescontroller','action'=>'addpromocode'));
                        }
                    }
             
                $this->Flash->success(__('This Coupon has been saved'));
                return $this->redirect(array('action' => 'promocodelist'));
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
      
        $query = $table->find('list', [
            'keyField' => 'id',
            'valueField' => 'membership_name'])
            ->order(['membership_name' => 'ASC']);
        $membership = $query->all();
        $this->set(compact('post','membership'));
    
        $this->set('post',$post);
     }
    public function editpromocode($id=null){
        $this->layout="admin";
        $id=base64_decode($id);
        $post=$this->Promocodes->findById($id)->toArray();
        if($this->request->is(['post','put'])){
            $data=$this->request->data;
            $table=TableRegistry::get('Promocodes');
            $p=$table->find()->where(['`id`' => $this->request->data['id']])->first();
            $post=$this->Promocodes->patchEntity($p,$this->request->data,['validate'=>'Default']);
             $post->modified = date("Y-m-d h:i:s");
            if($this->Promocodes->save($post)){
                $this->Flash->success(__('your Code has been updated'));
                return $this->redirect(array('action' => 'promocodelist'));
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
                   // return $this->redirect(array('controller'=>'Promocodes','action' => 'editpromocode',base64_encode($this->request->data['id'])));
                
           }
        }
        $table=TableRegistry::get('Memberships');
        $query = $table->find('list', [
            'keyField' => 'id',
            'valueField' => 'membership_name'])
            ->order(['membership_name' => 'ASC']);
        $membership = $query->all();
        $this->set(compact('post','membership'));
    }
    public function editstatus()
    {
        $userTable = TableRegistry::get('Promocodes');
		if ($this->request->is(['post', 'put'])) { 
			$id 				= $this->request->data['id'];
			$status 			= $this->request->data['status'];
            $mainStatus = [ACTIVE=>INACTIVE,INACTIVE=>ACTIVE];
			$data = $userTable->get($id);
			if(empty($data)){
				throw new NotFoundException;
			}
			$data->status = $mainStatus[$status];
			$userTable->save($data);
            $post->modified = date("Y-m-d h:i:s");
			echo "Record updated successfully.";
			exit;
		}
    }
    public function delete($id){
        $this->request->allowMethod(['post', 'delete']);
        $id= $this->request->data['id'];
        $post=$this->Promocodes->get($id);
        require_once(ROOT . DS  . 'vendor' . DS  . 'stripe' . DS . 'autoload.php');
        $test_secret_key = Configure::read('stripe_test_secret_key');
        $setApiKey = Stripe::setApiKey($test_secret_key);
        $getApiKey = Stripe::getApiKey();
        //pr($post->promocode_title);die;
        try{
            $cpn = \Stripe\Coupon::retrieve($post->promocode_title);
            if($cpn){
                $cpn->delete();
            }
        }catch(\Stripe\Error\Base $e)
        {
            $this->Flash->error($e->getMessage());
            $this->redirect(array('controller'=>'promocodescontroller','action'=>'promocodelist'));
        }
       
        
        if($this->Promocodes->delete($post)){
            $this->Flash->success(__('The Coupon has been deleted.'));
            return $this->redirect(array('action'=>'promocodelist'));
        }
    }
}
?>