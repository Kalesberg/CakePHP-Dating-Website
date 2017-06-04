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
class CmspagesController extends AppController
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
              if (in_array($this->request->action, ['cmspagelist','addcmspage','editcmspage','delete','editstatus'])){ 
                   return true;
              }
         }
       
       return false;
    }
    public function cmspagelist(){
       $this->layout='admin';
       $table=TableRegistry::get("Cmspages");
       $post=$table->find('all');
       $this->set(compact('post'));
    }
    public function addcmspage(){
       $this->layout='admin';
       $post=$this->Cmspages->newEntity();
       if($this->request->is('post')){
          //die('ngfjk');
          //pr($this->request->data);die;
            $post=$this->Cmspages->patchEntity($post,$this->request->data,['validate'=>'Default']);
            $post->show_on  =serialize($this->request->data['show_on']);
            $post->created = date("Y-m-d h:i:s");
           if($this->Cmspages->save($post)){
               $this->Flash->success(__('This Page has been saved'));
               return $this->redirect(array('action' => 'cmspagelist'));
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
       $this->set('post',$post);
    }
    public function editcmspage($id=null){
        $this->layout="admin";
        $id=base64_decode($id);
        $post=$this->Cmspages->findById($id)->toArray();
        if($this->request->is(['post','put'])){
            //pr($data=$this->request->data);die;
            $table=TableRegistry::get('Cmspages');
            $p=$table->find()->where(['`id`' => $this->request->data['id']])->first();
            $post=$this->Cmspages->patchEntity($p,$this->request->data);
            $post->show_on  =serialize($this->request->data['show_on']);
            $post->modified = date("Y-m-d h:i:s");
            if($this->Cmspages->save($post)){
                $this->Flash->success(__('your Page has been updated'));
                return $this->redirect(array('action' => 'cmspagelist'));
            }
            else{
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
              //  return $this->redirect(array('action' => 'editcmspage',base64_encode($this->request->data['id'])));
           }
        }
        $this->set(compact('post'));
        $this->set('_serialize', ['post']);
    }
    public function editstatus()
    {
        $userTable = TableRegistry::get('Cmspages');
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
        $id 				= $this->request->data['id'];
        $post=$this->Cmspages->get($id);
        if($this->Cmspages->delete($post)){
            $this->Flash->success(__('The Page has been deleted.'));
            return $this->redirect(array('action'=>'cmspagelist'));
        }
    }
}
?>