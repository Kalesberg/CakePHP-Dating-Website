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

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Auth', [
			'authorize' => ['Controller'],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'loginRedirect' => [
                'controller' => 'Users',
                'action' => 'indexnext'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'authError' => 'Did you really think you are allowed to see that?',
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password'],
                    //'scope' => ['Users.status' => ACTIVE]
                ]
            ]
        
        ]);
        
       // Configure::write('stripe_test_secret_key','sk_test_QVNlCarHhjZbAfK9XdJrKvxW ');
        Configure::write('stripe_test_secret_key','sk_live_BJTpu52aSd1GfjAuhtPQBkS1');
        //$this->loadComponent('RequestHandler');
        //$this->loadComponent('Flash');
        
	$this->loadModel('MetaTitleDescriptions');
		$query = $this->MetaTitleDescriptions->find('all');
		$meta_title_description = $query->toArray();
		//pr($meta_title_description); die;
        
        $this->set('meta_title_description',$meta_title_description);
	
          
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    //public function beforeRender(Event $event)
    //{
    //    if (!array_key_exists('_serialize', $this->viewVars) &&
    //        in_array($this->response->type(), ['application/json', 'application/xml'])
    //    ) {
    //        $this->set('_serialize', true);
    //    }
    //}
    public function isAuthorized($user){
        return true;
    }
    
    /**
      *@Send Mail
      * Send Mail
     */
    public function sendSms($toNumber, $message){
	    
		$this->autoRender = false;
		 include_once(ROOT . DS  . 'vendor' . DS  . 'twillio-sms' . DS . 'sendsms.php'); 
		$sms = new \sendsms();
		if(!empty($toNumber) && !empty($message)){
			 $msg = $sms->sms($toNumber, $message);
		}
		return $msg; 
	}
	
	
	
	public function sendEmail($to, $subject, $message, $from = null){
		$email = new Email();
		$email->transport('default');
		$result = $email->from(['support@webfullcircle.com' => 'Self-Match'])
			->to($to)
			->emailFormat('html')
			->subject($subject)
			->send($message);
			
		return $result;
	}
    
   
}
