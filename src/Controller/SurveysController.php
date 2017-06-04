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
class SurveysController extends AppController
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
            if (in_array($this->request->action, ['surveylist','delete','surveydetails'])) { 
                   return true;
            }
        }
       return false;
    }
    public function surveylist($user_id =null){
        $this->layout='admin';
		$user_id	=	base64_decode($user_id);
        $table=TableRegistry::get('Surveys');
		if($user_id){
            $filter=[];
            $filter['OR']=[['status' => PENDING] ,['status' => COMPLETED]];
            $filter['user_id']=$user_id;
			$post=$table->find("all")->where($filter)->order(['id'=>'DESC'])->toArray();
           // pr($post);die;
		}else{
			 $post=$table->find("all")->order(['id'=>'DESC'])->toArray();
		}
		$this->set(compact('post'));
    }
    public function surveydetails($id=null)
    {
        $this->layout='admin';
        $session=$this->request->session();
        $survey_id  = base64_decode($id);
    //    $usertype   = base64_decode($usertype);
    //    $email      = base64_decode($email);
        $table=TableRegistry::get('Surveys');
        $post = $table->find('all')->where(['id'=>$survey_id])->first();
		
       // pr($post);die;
       // $totalquestion = $session->read('totalquestion');
      //  $this->set('totalquestion',$totalquestion);
        $this->set(compact('post','survey_id'));
    }
  
    public function delete($id){
       $this->request->allowMethod(['post', 'delete']);
       $id = $this->request->data['id'];
        $post=$this->Surveys->get($id);
        if($this->Surveys->delete($post)){
            $this->Flash->success(__('The survey  has been deleted.'));
            return $this->redirect(array('action'=>'surveylist'));
        }
    }
}
?>