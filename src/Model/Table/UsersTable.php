<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{
	
    public function initialize(array $config)
    {
		//$this->belongsTo('Users');
        $this->hasMany('Surveys', [
           'className' => 'Surveys',
            'foreignKey' => 'user_id',
            'dependent' =>true
        ]);
        $this->hasMany('Payments', [
           'className' => 'Payments',
            'foreignKey' => 'user_id',
            'dependent' =>true
        ]);
        $this->hasMany('Surveyanswers', [
           'className' => 'Surveyanswers',
            'foreignKey' => 'user_id',
            'dependent' =>true
        ]);
        $this->hasMany('Receivers', [
           'className' => 'Receivers',
            'foreignKey' => 'user_id',
            'dependent' =>true
        ]);
        $this->hasMany('Favourites', [
           'className' => 'Favourites',
            'foreignKey' => 'user_id',
            'dependent' =>true
        ]);

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
        $validator
            ->notEmpty('first_name', 'You must enter your first name.');
//            ->add('first_name', 'validFormat',[
//                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
//                'message' => 'First name must contain letters and spaces only.'
			//]
			//);
		
		$validator
            ->notEmpty('last_name', 'You must enter your last name.');
			//->add('last_name', 'validFormat',[
			//	'rule' => array('custom', '|^[a-zA-Z ]*$|'),
			//	'message' => 'Last name must contain letters and spaces only.'
			//]);
            
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
            ->notEmpty('city', 'You must enter your city.');
         
       /* $validator
           // ->notEmpty('phone', 'You must enter your contact number.')
            ->add('phone', 'validFormat',[
                'rule'       => array('custom', '/^[0-9]( ?[0-9]){8} ?[0-9]$/'), 
                'message' => 'Contact must be numeric and contain atleast 10 digits.'
			]); */
            
        $validator
            ->notEmpty('region', 'You must enter your Region.');
          
        $validator
            ->notEmpty('country', 'You must enter your country.');
           
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
            ->notEmpty('age', 'You must enter your age.')
		 	->add('age', 'validFormat',[
                'rule'       => array('custom', '/^[1-9]?[0-9]{1}$|^100$/'), 
                'message' => 'age must be positive integer.'
			]); 
        $validator
            ->notEmpty('gender', 'You must enter your gender.');
        $validator
            ->notEmpty('membership_level', 'You must enter your membership.');
        $validator
            ->notEmpty('relationship_status', 'You must enter your relationship status.');
        
       
   $validator
            ->notEmpty('password', 'please enter password.')
			->add('password',[
                    'matches'=> [
                        'rule' => function($value, $stuff) {
                               return $value != $stuff['data']['email'];
                         },
                      'message' => 'password should not your email.'
                    ],
                   'validFormat' => [
                       'rule'       => array('custom', '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$'),
                        'message' => 'Password should be between 8-24 characters with uppercase and lowercase letters, numbers, and special characters.'
                    ]
                ]
            );
   /*

        $validator ->notEmpty('password','Please enter Password')
                    ->add('password','validFormat',[
                        'rule'       => array('custom', '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$'),
                        'message' => 'Password should be between 8-24 characters with uppercase,lowercase,numbers,letters and special characters.']);
       */
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
        $validator
            ->notEmpty('newpassword', 'Please enter Password.')
			->add('newpassword',[
                    'matches'=> [
                        'rule' => function($value, $stuff) {
                               return $value != $stuff['data']['email'];
                         },
                      'message' => 'password should not your email.'
                    ],
                   'validFormat' => [
                       'rule'       => array('custom', '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$'),
                        'message' => 'Password should be between 8-24 characters with uppercase and lowercase letters, numbers, and special characters.'
                    ]
                ]
            );
       // $validator ->notEmpty('newpassword','Please enter Password');    
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
            ->notEmpty('first_name', 'You must enter your first name.');
//            ->add('first_name', 'validFormat',[
//                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
//                'message' => 'First name must contain letters and spaces only.'
//			]);
		
		$validator->requirePresence('last_name')
            ->notEmpty('last_name', 'You must enter your last name.');
			//->add('last_name', 'validFormat',[
			//	'rule' => array('custom', '|^[a-zA-Z ]*$|'),
			//	'message' => 'Last name must contain letters and spaces only.'
			//]);
            
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
            ->notEmpty('city', 'You must enter your city.');
            
        $validator->requirePresence('region')
            ->notEmpty('region', 'You must enter your state/region.');
            
            
        $validator->requirePresence('country')
            ->notEmpty('country', 'You must enter your country.');
           
        $validator
            ->notEmpty('age', 'You must enter your age.')
		 	->add('age', 'validFormat',[
                'rule'       => array('custom', '/^[1-9]?[0-9]{1}$|^100$/'), 
                'message' => 'age must be positive integer.'
			]); 
        
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
	public function validationAffiliateDefault(Validator $validator){
	    $validator
            ->notEmpty('first_name', 'You must enter your first name.');
          //  ->add('first_name', 'validFormat',[
//                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
//                'message' => 'First name must contain letters and spaces only.'
//			]);
		
		$validator
            ->notEmpty('last_name', 'You must enter your last name.');
			//->add('last_name', 'validFormat',[
			//	'rule' => array('custom', '|^[a-zA-Z ]*$|'),
			//	'message' => 'Last name must contain letters and spaces only.'
			//]);
       
		
        $validator
            ->notEmpty('website', 'You must enter your website.');
		$validator
            ->notEmpty('address1', 'You must enter your address1.');
		
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
            ->notEmpty('city', 'You must enter your city.');
		$validator
            ->notEmpty('zip_code', 'You must enter your zip/postalcode.');
        $validator->requirePresence('country')
            ->notEmpty('country', 'You must select your country.');
		$validator->requirePresence('region')
            ->notEmpty('region', 'You must select your state/region.');
		$validator->requirePresence('city')
            ->notEmpty('city', 'You must select your city.');
		$validator->requirePresence('city')
            ->notEmpty('business_type', 'You must select your business type.');
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
           ->notEmpty('password', 'please enter password.')
           ->add('password',[
                   'matches'=> [
                       'rule' => function($value, $stuff) {
                              return $value != $stuff['data']['email'];
                        },
                     'message' => 'Password should not your email.'
                   ],
                  'validFormat' => [
                      'rule'       => array('custom', '$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$'),
                       'message' => 'Password should be between 8-24 characters with uppercase and lowercase letters, numbers, and special characters.'
                   ]
               ]
           );   
        $validator
        ->notEmpty('cpassword','Please enter confirm password')
        ->add('cpassword', [
                        'compare' => [
                        'rule' => ['compareWith','password'],
                        'message' => 'Password does not match.'
                        ]]);
	
	
        return $validator;
    
    
	}
	public function validationAffiliateProfileDefault(Validator $validator){
		 $validator
            ->notEmpty('first_name', 'You must enter your first name.');
//            ->add('first_name', 'validFormat',[
//                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
//                'message' => 'First name must contain letters and spaces only.'
//			]);
		
		$validator
            ->notEmpty('last_name', 'You must enter your last name.');
			//->add('last_name', 'validFormat',[
			//	'rule' => array('custom', '|^[a-zA-Z ]*$|'),
			//	'message' => 'Last name must contain letters and spaces only.'
			//]);
        $validator
            ->notEmpty('company', 'You must enter your company.');
		
        $validator
            ->notEmpty('website', 'You must enter your website.');
		$validator
            ->notEmpty('address1', 'You must enter your address1.');
		/*$validator
            ->notEmpty('address2', 'You must enter your address2.');*/
       /* $validator
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
            ->notEmpty('paypalemail', 'You must enter your paypal email ID.')
            ->add('paypalemail', [
                 'unique'=>[
                    'rule' => 'validateUnique',
                    'message' => 'This paypal email ID is already registered.',
                    'provider' => 'table'

                ],
			]);*/
        $validator
            ->notEmpty('city', 'You must enter your city.');
		$validator
            ->notEmpty('zip_code', 'You must enter your zip/postalcode.');
        $validator->requirePresence('country')
            ->notEmpty('country', 'You must select your country.');
		$validator->requirePresence('region')
            ->notEmpty('region', 'You must select your state/region.');
		$validator->requirePresence('city')
            ->notEmpty('city', 'You must select your city.');
		$validator->requirePresence('city')
            ->notEmpty('business_type', 'You must select your business type.');
        $validator
            ->notEmpty('username', 'You must enter your user name.')
            ->add('username', [
                 'unique'=>[
                    'rule' => 'validateUnique',
                    'message' => 'This user name is already registered.',
                    'provider' => 'table'

                ],
			]);
		
        return $validator;
	}
	
}
?>