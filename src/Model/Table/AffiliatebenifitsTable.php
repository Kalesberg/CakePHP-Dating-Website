<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class AffiliatebenifitsTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Affiliatebenifits');
       
    }
    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $query
            ->select(['id', 'email', 'password'])
            ->where(['Users.email' => 'email','Users.password'=>'password']);
        return $query;
    }
    
    public function validationAddDefault(Validator $validator)
    {
	/*	$validator->requirePresence('profile_photo')
            ->notEmpty('profile_photo','Please select image')
            ->add('profile_photo',[
                'rule' => 'processImageUpload',                
                'message' => 'image extension not matched,please try again.',
                'provider' => 'table'
            ]);*/
        $validator->notEmpty('profile_photo.name', 'Please select image');
		/*  ->add('profile_photo',['mycapitalrule' => [
                        'rule' => [$this,'processImageUpload'],
                        'message' => 'image extension not matched,please try again.'
                ]
            ]);*/
        $validator
            ->notEmpty('first_name', 'You must enter your first name.')
            ->add('first_name', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'First name must contain letters and spaces only.'
			]);
		
		$validator
            ->notEmpty('last_name', 'You must enter your last name.')
			->add('last_name', 'validFormat',[
				'rule' => array('custom', '|^[a-zA-Z ]*$|'),
				'message' => 'Last name must contain letters and spaces only.'
			]);
            
        $validator
            ->notEmpty('email', 'Please enter E-mail')
            ->add('email', [
                'length' => [
                    'rule' => 'email',
                    'message' => 'You must enter a valid email.',
                ],
                'unique'=>[
                    'rule' => 'validateUnique',
                    'message' => 'This email is already registered.',
                    'provider' => 'table'

                ],
		  
            ]);
        $validator
            ->notEmpty('city', 'You must enter your city.')
            ->add('city', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'City must contain letters and spaces only.'
			]);
        /*$validator
            ->notEmpty('contact', 'You must enter your contact number.')
            ->add('contact', 'validFormat',[
                'rule'       => array('custom', '/^[0-9]( ?[0-9]){8} ?[0-9]$/'), 
                'message' => 'Contact must be numeric and contain atleast 10 digits.'
			]);*/
            
            
        $validator
            ->notEmpty('username', 'You must enter your user name.')
            ->add('username', [
                 'unique'=>[
                    'rule' => 'validateUnique',
                    'message' => 'This user name is already registered.',
                    'provider' => 'table'

                ],
			]);
            
        $validator
            ->notEmpty('region', 'You must enter your Region.')
            ->add('region', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'Region must contain letters and spaces only.'
			]);
        $validator
            ->notEmpty('country', 'You must enter your country.')
            ->add('country', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'Country must contain letters and spaces only.'
			]);
        $validator
            ->notEmpty('age', 'You must enter your age.');
        $validator
            ->notEmpty('gender', 'You must enter your gender.');
       // $validator
         //   ->notEmpty('membership_level', 'You must enter your membership.');
       // $validator
           // ->notEmpty('relationship_status', 'You must enter your relationship status.');
        
       /* $validator->requirePresence('agreed')
               ->notEmpty('agreed', 'please check term and conditions')
//            ->add('agreed', [
//                'length' => [
//                    'rule' => ['comparison', '!=', 0],
//                    'required'=>true,
//                    'message'=>'you must agree to term and conditions.'
//                ]
//
//            ]);*/
        $validator ->notEmpty('password','Please enter Password');    
        $validator
        ->notEmpty('cpassword','Please enter confirm password')
        ->add('cpassword', [
                        'compare' => [
                        'rule' => ['compareWith','password'],
                        'message' => 'Password does not match.'
                        ]]);
        return $validator;
    }
    public function validationChangeDefault(Validator $validator){
        //die('gjkdf');
        $validator ->notEmpty('newpassword','Please enter Password');    
        $validator
        ->notEmpty('cpassword','Please enter confirm password')
        ->add('cpassword', [
                        'compare' => [
                        'rule' => ['compareWith','newpassword'],
                        'message' => 'Password does not match.'
                        ]]);
        return $validator;
    }
    public function validationProfileDefault(Validator $validator)
    {
        //$validator->add('id','valid','numeric');
       /* $validator
            ->add('profile_photo', [

                'uploadError' => [
                        'rule' => 'uploadError',
                        'message' => 'The cover image upload failed.',
                        'allowEmpty' => TRUE,
                ],

                'mimeType' => [
                        'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
                        'message' => 'Please only upload images (gif, png, jpg).',
                        'allowEmpty' => TRUE,
                ],

                'fileSize' => [
                        'rule' => array('fileSize', '<=', '1MB'),
                        'message' => 'Cover image must be less than 1MB.',
                        'allowEmpty' => TRUE,
                ],

                'processCoverUpload' => [
                'provider' => 'table', // <<<< there you go
                'rule' => 'processCoverUpload',
                'message' => 'Unable to process cover image upload.',
                'allowEmpty' => TRUE,
                ],

            ]);*/
        $validator->requirePresence('first_name')
            ->notEmpty('first_name', 'You must enter your first name.')
            ->add('first_name', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'First name must contain letters and spaces only.'
			]);
		
		$validator->requirePresence('last_name')
            ->notEmpty('last_name', 'You must enter your last name.')
			->add('last_name', 'validFormat',[
				'rule' => array('custom', '|^[a-zA-Z ]*$|'),
				'message' => 'Last name must contain letters and spaces only.'
			]);
            
        $validator->requirePresence('email')
            ->notEmpty('email', 'Please enter E-mail')
            ->add('email', [
                'length' => [
                    'rule' => 'email',
                    'message' => 'You must enter a valid email.',
                ],
                'unique'=>[
                    'rule' => 'validateUnique',
                    'message' => 'This email is already registered.',
                    'provider' => 'table'

                ],
		  
            ]);
        $validator->requirePresence('city')
            ->notEmpty('city', 'You must enter your city.')
            ->add('city', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'City must contain letters and spaces only.'
			]);
        $validator->requirePresence('region')
            ->notEmpty('region', 'You must enter your state/region.')
            
            ->add('region', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'State/Region must contain letters and spaces only.'
			]);
            
        $validator->requirePresence('country')
            ->notEmpty('country', 'You must enter your country.')
            ->add('country', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'Country must contain letters and spaces only.'
			]);
        $validator->requirePresence('age')
            ->notEmpty('age', 'You must enter your age.')
            ;
            
        
       /* ->add('profile_photo',[
			'validType' =>[
			    'rule' => function($value){
				    $ext = explode('.',$value['name']);
				    $ext = end( $ext );
				    $ext = strtolower($ext);
				    return in_array($ext,['jpg','jpeg','png','gif']);
				},
			    'message' => 'Please upload only gif,png,jpg type file'
			]])*/
		//->add('link', 'valid', ['rule' => 'url','message' => 'The link must be a valid url address'])
		//->allowEmpty('profile_photo',false);
		return $validator; 
    }

}
?>