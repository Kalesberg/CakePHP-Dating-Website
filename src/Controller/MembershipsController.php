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

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class MembershipsController extends AppController
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
            if (in_array($this->request->action, ['addmembership','editmembership','editstatus','delete','membershiplist'])) { 
                   return true;
              }
         }
       return false;
    }
    public function membershiplist()
    {
        $this->layout='admin';
        $table=TableRegistry::get("Memberships");
        $post=$table->find('all')->order(['id'=>'DESC'])->toArray();
        $this->set(compact('post'));
    }
    public function addmembership(){
        $this->layout='admin';
        $post=$this->Memberships->newEntity();
        if($this->request->is(['post'])){
            $data=$this->request->data;
            $post=$this->Memberships->patchEntity($post,$this->request->data, [ 'validate' => 'MemberDefault']);
            $post->created = date("Y-m-d h:i:s");
            if($this->Memberships->save($post)){
                $this->Flash->success(__('This Membership has been saved'));
                return $this->redirect(array('action' => 'membershiplist'));
            }else{
                //pr($post->errors());die;
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
        $this->set('post',$post);
    }
    public function editmembership($id=null){
        $this->layout="admin";
        $id=base64_decode($id);
        $post=$this->Memberships->findById($id)->toArray();
        if($this->request->is(['post','put'])){
            $data=$this->request->data;
            //pr($data);die;
            $table=TableRegistry::get('Memberships');
            $p=$table->find()->where(['`id`' => $this->request->data['id']])->first();
            $post=$table->patchEntity($p,$this->request->data,[ 'validate' => 'MemberDefault']);
            $post->modified = date("Y-m-d h:i:s");
            if($table->save($post)){
                $this->Flash->success(__('Membership has been updated'));
                return $this->redirect(array('action' => 'membershiplist'));
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
        $this->set(compact('post'));
        //$this->set('_serialize', ['post']);
    }
     public function editstatus()
    {
        $userTable = TableRegistry::get('Memberships');
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
			echo "Record updated successfully.";
			exit;
		}
    }
    public function delete($id){
        $this->request->allowMethod(['post', 'delete']);
        $id = $this->request->data['id'];
        $post=$this->Memberships->get($id);
        if($this->Memberships->delete($post)){
            $this->Flash->success(__('The Membership with has been deleted.'));
            return $this->redirect(array('action'=>'membershiplist'));
        }
    }
}
?>