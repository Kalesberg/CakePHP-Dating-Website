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
class GlobalsettingsController extends AppController
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
            if (in_array($this->request->action, ['globalsetting','globalsettingupdate'])) { 
                   return true;
              }
         }
       return false;
    }
     public function globalsetting($id=null){
        $this->layout='admin';
        $table=TableRegistry::get('Globalsettings');
        $post=$table->Globalsettings->find('all')->toArray();
        if($this->request->is('post','put')){
            $id=$this->request->data['id'];
            $p=$table->find()->where(['`id`' =>$id])->first();
            $post=$table->patchEntity($p,$this->request->data,['validate'=>'Default']);
            if($table->save($post)){
                 $this->Flash->success(__('Settins has been updated'));
                 return $this->redirect(array('action' => 'globalsetting'));
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
                    return $this->redirect(array('action' => 'globalsetting'));
               
            }
        }
        $this->set('post',$post);
        
    }
    public function globalsettingupdate(){
        $this->autoRander = false;
        $table=TableRegistry::get('Globalsettings');
		if ($this->request->is(['post', 'put'])) {
            //pr($this->request->data);
			$id 				= $this->request->data['id'];
			$value  			= $this->request->data['value'];
			$data = $table->get($id);
			$data->value = $value;
            $data->modified = date("Y-m-d h:i:s");
			$table->save($data);
		}
        exit;
     }
}
