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
class ReceiversController extends AppController
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
       $this->Auth->allow(['survey','sendsurvey','compatibilityreport','saveimage']);
       
    }
    
    public function survey($id=null,$usertype=null,$email=null,$flag=null,$imagename = null)
    {
        $this->layout='home_page';
        $survey_id  = base64_decode($id);
        $usertype   = base64_decode($usertype);
        $email      = base64_decode($email);
        $flag       = base64_decode($flag);
        $imagename  = base64_decode($imagename);
        //pr($imagename);die;
        $table=TableRegistry::get('Surveys');
        $post = $table->find('all')->where(['id'=>$survey_id])->first();
        //pr($post);die;
        if(empty($flag) && ($post['status'] != '3')){
            return $this->redirect(array('controller'=>'Receivers','action' =>'saveimage',
                                         base64_encode($email),
                                         base64_encode($survey_id),
                                         base64_encode($usertype)));
        }
        if(($usertype == "receiver") && ($post['status'] == '3')){
            return $this->redirect(array('controller'=>'Receivers','action' =>'compatibilityreport',
                                     base64_encode($email),
									 base64_encode($post['user_id']),
                                     base64_encode($survey_id)));
        }
        $this->set(compact('post','survey_id','usertype','email','imagename'));
    }
    function saveimage($email=null,$survey_id=null,$usertype=null){
        $email = base64_decode($email);
        //pr($email);die;
        $survey_id = base64_decode($survey_id);
        $usertype = base64_decode($usertype);
        $this->layout='home_page';
        $tableReceiver=TableRegistry::get('Receivers');
        if($this->request->is(['put','post']))
        {
            //echo $this->request->data['survey_id'];die;
            $p=$tableReceiver->find('all')->where(['survey_id' => $this->request->data['survey_id']])->first();
            //pr($p);die;
            $post=$tableReceiver->patchEntity($p,$this->request->data);
            if(!empty($this->request->data['profile_photo']['name']))
            {
                $imagename=$this->request->data['profile_photo']['name'];
                $ext = pathinfo($imagename, PATHINFO_EXTENSION);
                if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'|| $ext =='JPEG' || $ext == 'bmp'|| $ext =='JPG'|| $ext =='PNG')
                {
                    $imagePath=time().$imagename;
                    $filepath = getcwd() .'/img/user_images/'.$imagePath;
                    $post->profile_photo =$imagePath ;
                    if($tableReceiver->save($post))
                    {
                        if(!empty($imagename)){ 
                            move_uploaded_file($this->request->data['profile_photo']['tmp_name'], $filepath);
                            chmod($filepath, 0777);
                        }
                        $this->Flash->success(__('Please answer ALL the questions before clicking SUBMIT.'));
                        return $this->redirect(array('controller'=>'Receivers','action' =>'survey',base64_encode($survey_id),base64_encode($usertype),base64_encode($email),base64_encode('flag'),base64_encode($imagename)));
                    }
                }else{
                    $this->Flash->error("Please upload only png,jpg type file.");
                }
            }else{
				if(!empty($this->request->data['photo'])){
                    $post->profile_photo =$this->request->data['photo'];
                }
				$this->Flash->success(__('Please answer ALL the questions before clicking SUBMIT.'));
                return $this->redirect(array('controller'=>'Receivers','action' =>'survey',base64_encode($survey_id),base64_encode($usertype),base64_encode($email),base64_encode('flag'),base64_encode($this->request->data['photo'])));
			}
        }
        $this->set(compact('post','survey_id','usertype','email'));
    }
    public function sendsurvey(){
        $this->layout='home_page';
        $this->autoRender=false;
        $tableSurveyAnswers=TableRegistry::get('Surveyanswers');
        $tableReceivers=TableRegistry::get('Receivers');
        $data=$this->request->data;
        if($this->request->is(['put','post'])){
            $usertype           = $this->request->data["usertype"];
            $receiver_email     = $this->request->data["receiver_email"];
            $questions          = serialize($this->request->data['question_id']);
            $ques=$this->request->data['question_id'];
            foreach($ques as $q){
                if(!empty($this->request->data["answers"][$q])){
                    $answers=$this->request->data["answers"];
                    $answers=serialize($answers);
                    $tableSurvey=TableRegistry::get('Surveys');
                    $query2=$tableSurvey->query();
                    $query2->update()
                        ->set(['status'=>'3'])
                        ->where(['id' => $this->request->data['survey_id']])
                        ->execute();
                        
                    $query=$tableSurveyAnswers->query();
                    $query->update()
                    ->set(['receiver_answers' =>$answers,'receiver_email'=>$receiver_email,'modified'=>date("Y-m-d h:i:s")])
                    ->where(['survey_id' => $this->request->data['survey_id']])
                    ->execute();
                    $sender=$tableSurveyAnswers->find("all")->where(['survey_id'=>$this->request->data['survey_id']])->first();
                    $senderId =$sender['user_id'];
                    
                        $User=TableRegistry::get('Users');
                        $sendermail=$User->find("all")->where(['id'=>$senderId])->first();
                        $senderEmail =$sendermail['email'];
                        // pr($senderEmail);die;
                        $receiver=$tableReceivers->find("all")->where(['email'=>$receiver_email,'user_id'=>$sender['user_id'],'survey_id'=>$this->request->data['survey_id']])->first();
                        $receiver_name=$receiver['name'];
                        if($query){
                            $EmailTemplates= TableRegistry::get('Emailtemplates');
                            $query2 = $EmailTemplates->find('all')->where(['slug' => 'notification_invitation'])->toArray();
                            if($query2){
                                    
                                $email = $senderEmail;
                               // pr($this->request->data['user_id']);die;
                                $activation_link = SITE_URL.'Pages/compatibilityreport/'.base64_encode($receiver_email)."/".base64_encode($senderId)."/".base64_encode($this->request->data['survey_id']);
                                $to =$email;
                                $subject = $query2[0]['subject'];
                                $message1 = $query2[0]['description'];
                                $message = str_replace(['{{firstname}}','{{receiverName}}','{{activation_link}}'],[$sendermail['first_name'],$receiver_name,
                                $activation_link],$message1);
                                parent::sendEmail($to, $subject, $message);
                               // $this->Flash->success(__('Your survey has been submitted'));
                                return $this->redirect(array('controller'=>'Receivers','action' =>'compatibilityreport',base64_encode($receiver_email),base64_encode($senderId),base64_encode($this->request->data['survey_id'])));
                            }
                            
                        }
                }else{
                    $this->Flash->error("Please add some questions in the survey before submit.");
                    return $this->redirect(array('controller'=>'Receivers','action' =>'survey',base64_encode($this->request->data['survey_id']),base64_encode($usertype),base64_encode($receiver_email))); 
                }
            }
        }
                
     $this->set(serialize($post));
    }
    function compatibilityreport($receiver_email = null,$id=null,$survey_id=null){
        $this->layout='home_page';
       $receiver_email = base64_decode($receiver_email);
       $user_id   = base64_decode($id);
       $survey_id = base64_decode($survey_id);
        $table     = TableRegistry::get('Surveyanswers');
        $post  =$table->find('all')->where(['receiver_email'=> $receiver_email,'user_id'=>$user_id,'survey_id'=>$survey_id])->first();
        $this->set('post',$post);
    }
}
?>