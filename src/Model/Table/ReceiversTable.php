<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ReceiversTable extends Table
{
	
    public function initialize(array $config)
    {
		//$this->belongsTo('Receivers');
        $this->hasMany('Surveyanswers',[
           'className' => 'Surveyanswers',
            'foreignKey' => 'receiver_email',
            'dependent' =>true
        ]);
    }
    
    public function validationDefault(Validator $validator)
    {
        //die('hvdh');
        $validator
            ->notEmpty('name', 'You must enter your name.');
          /*  ->add('name', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'Name must contain letters and spaces only.'
			]);*/
    // ...$validator
        $validator->notEmpty('email', 'Please enter E-mail')
            ->add('email', [
                'length' => [
                    'rule' => 'email',
                    'message' => 'You must enter a valid email.',
                ],
                //'unique'=>[
                 //   'rule' => 'validateUnique',
                  //  'message' => 'This email is already registered.',
                  //  'provider' => 'table'

               // ],
		  
            ]);
        $validator
            ->notEmpty('age', 'You must enter your age.')
		 	->add('age', 'validFormat',[
                'rule'       => array('custom', '/^[1-9]?[0-9]{1}$|^100$/'), 
                'message' => 'age must be positive integer.'
			]); 
          return $validator;  
    // ...
    }
}
?>