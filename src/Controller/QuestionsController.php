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
class QuestionsController extends AppController
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
            if (in_array($this->request->action, ['questionlist','deleteall','addquestion','editquestion','delete','editstatus','approve'])) { 
                   return true;
              }
         }
       return false;
    }
     public function questionlist($id=null){
        $this->layout='admin';
        $id=base64_decode($id);
        $table=TableRegistry::get('Questions');
        if($id){
            $quest=$table->find("all")->where(['category_id'=>$id,'status !='=>DELETED])->order(['id'=>'DESC']);
        }else{
            $quest=$table->find('all')->where(['status !='=>DELETED])->order(['id'=>'DESC'])->toArray();
        }
        
       // $questions=$table->find('all')->order(['id'=>'DESC','status =!'])->toArray();
       // pr($category);die;
       // $table2=TableRegistry::get('Categories');
       // $category=$table2->find()->where(['`category_id`' => $this->request->$category])->first();
        $this->set(compact('quest','id'));
     }
    
    public function addquestion(){
        $this->layout='admin';
        $post=$this->Questions->newEntity();
        if($this->request->is(['post'])){
            $data=$this->request->data;
            $table=TableRegistry::get('Questions');
            $post=$this->Questions->patchEntity($post,$this->request->data,['validate'=>'QuestionDefault']);
            $post->user_id=$this->Auth->user('id');
             $post->created = date("Y-m-d h:i:s");
             if($this->Questions->save($post)){
                
                $this->Flash->success(__('This Question has been saved'));
                return $this->redirect(array('action' => 'questionlist'));
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
    public function editquestion($id=null){
        $this->layout="admin";
        $id=base64_decode($id);
        $post=$this->Questions->findById($id)->toArray();
        if($this->request->is(['post','put'])){
            $data=$this->request->data;
            $table=TableRegistry::get('Questions');
            $p=$table->find()->where(['`id`' => $this->request->data['id']])->first();
            $post=$this->Questions->patchEntity($p,$this->request->data,['validate'=>'QuestionDefault']);
            $post->modified = date("Y-m-d h:i:s");
            if($this->Questions->save($post)){
                $this->Flash->success(__('your Question has been updated'));
                //return $this->redirect(array('action' => 'questionlist'));
            }else{
               // pr($post->errors());die;
                foreach ($post->errors() as $key => $value) {
                        $messageerror = [];
                        foreach ($value as $key2 => $value2) {
                            $messageerror[] = $value2;
                        }
                        $errorInputs[$key] = implode(",", $messageerror);
                    }
                    $err=implode(',',$errorInputs);
                    $this->Flash->error($err);
                   // return $this->redirect(array('controller'=>'Questions','action' => 'editquestion',base64_encode($this->request->data['id'])));
                    
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
     public function editstatus()
    {
        $userTable = TableRegistry::get('Questions');
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
            
			echo "Record updated successfully.";
			exit;
		}
    }
    public function delete($id){
        $this->request->allowMethod(['post', 'delete']);
        $id = $this->request->data['id'];
        $table=TableRegistry::get('Questions');
        $query = $table->query();
                $query->update()
                ->set(['status'=>DELETED])
                ->where(['id' => $id])
                ->execute();
        if($query){
            $this->Flash->success(__('The question  has been deleted.'));
            return $this->redirect(array('action'=>'questionlist'));
        }
    }
    public function deleteall(){
        $table=TableRegistry::get('Questions');
        $query = $table->query();
                $query->update()
                ->set(['status'=>DELETED])
                ->execute();
        if($query){
            $this->Flash->success(__('All questions  has been deleted.'));
            return $this->redirect(array('action'=>'questionlist'));
        }        
    }
    public function approve(){
        $this->autoRender=false;
        $this->request->allowMethod(['post', 'approve']);
        $id = $this->request->data['id'];
        //pr($this->request->data['value']);die;
        $post=$this->Questions->get($id);
        if($this->request->data['value']=="5"){
            $post->review=REJECTED;
          //  $cat=base64_encode($post->category_id);
            $this->Questions->save($post);
               $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'question_not_approved'])->toArray();
                if($query){
                    $activation_link = SITE_URL.'Pages/questionsdetails/'.base64_encode($post->category_id);
                    $to = $post->email;
                   // pr($to);die;
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{username}}','{{activation_link}}'],[$post->name,
                    $activation_link],$message1);
                    parent::sendEmail($to, $subject, $message); 
                    $this->Flash->success(__('Thank you! Your question has been approved.'));
                    return $this->redirect(array('action' => 'questionbank'));
                }
        }else{
            $post->review=APPROVED;
            $post->status=ACTIVE;
            //$cat=base64_encode($post->category_id);
            $this->Questions->save($post);
                $EmailTemplates= TableRegistry::get('Emailtemplates');
                $query = $EmailTemplates->find('all')->where(['slug' => 'question_approved'])->toArray();
                if($query){
                    $activation_link = SITE_URL.'Pages/questionsdetails/'.base64_encode($post->category_id);
                    $to = $post->email;
                   // pr($to);die;
                    $subject = $query[0]['subject'];
                    $message1 = $query[0]['description'];
                    $message = str_replace(['{{username}}','{{activation_link}}'],[$post->name,
                    $activation_link],$message1);
                    parent::sendEmail($to, $subject, $message);
                }
                $this->Flash->error(__('The question has been not approved.'));
                return $this->redirect(array('action'=>'questionbank'));
        }
         exit;
    }
   public function addsurvey(){
        $this->layout='home_page';
        $table=TableRegistry::get('Surveys');
        $post=$table->newEntity();
        if($this->request->is(['put','post']))
        {
            if(!empty($this->request->data['questions_id']))
            {
                $survey_type =$this->request->data['survey_type'];
                $membership_level =$this->request->data['membership_level'];
                $tmp=array();
                $cat=array();
                if($this->request->data['survey_id'])
                {
                    $p=$table->find('all')->where(['id'=>$this->request->data['survey_id']])->toArray();
                    $tmp        =unserialize($p[0]['questions_id']);
                    $cat        =unserialize($p[0]['category_id']);
                    $questions  =$this->request->data['questions_id'];
                    $cates      =$this->request->data['category_id'];
                    $tmp        =array_merge($tmp,$questions);
                    $tmp        =array_unique($tmp);
                    $cat        =array_merge($cat,$cates);
                    if(($membership_level=='visitor') && ($survey_type=='1'))
                    {
                        $count=count($tmp);
                        if($count > 15){
                            $this->Flash->error(__('You can add up to 15 questions in this survey.'));
                            return $this->redirect(array('controller'=>'Pages','action' =>'survey',base64_encode($this->request->data['survey_id'])));
                        }
                    }
                    $query = $table->query();
                    $query->update()
                        ->set(['questions_id' =>serialize($tmp),'category_id'=>serialize($cat),'modified'=>date("Y-m-d h:i:s"),'status'=>'1'])
                        ->where(['id' => $this->request->data['survey_id']])
                        ->execute();
                    if($query){
                        $this->Flash->success(__('Questions added to your survey.'));
                        return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($this->request->data['survey_id'])));
                    }
                }else
                {  
                    $post=$table->patchEntity($post,$this->request->data);
                    $cates  =$this->request->data['category_id'];
                    if(($membership_level=='visitor') && ($survey_type=='1'))
                    {
                        $count=count($this->request->data['questions_id']);
                        if($count > 15){
                            $this->Flash->error(__('You can add up to 15 questions in this survey.'));
                            return $this->redirect(array('controller'=>'Pages','action' =>'questionsdetails',base64_encode($cates[0])));
                        }
                    }
                    $questions=serialize($this->request->data['questions_id']);
                    $post->questions_id=$questions;
                    $post->created = date("Y-m-d h:i:s");
                    $post->status = "1";
                    $post->category_id=serialize($cates);
                    $post->page=$this->request->data['page'];
                    if($result=$table->save($post)){
                        $id=$result->id;
                        $this->Flash->success(__('Questions added to your survey.'));
                        return $this->redirect(array('controller'=>'Pages','action' => 'survey',base64_encode($id)));
                    }
                }    
            }
        }
        $this->set(compact('post'));
    } 
    
}
?>