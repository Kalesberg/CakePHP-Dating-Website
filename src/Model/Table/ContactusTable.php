<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ContactusTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Contactus');
       
    }
    public function validationDefault(Validator $validator)
    {
        $validator
                ->notEmpty('title','Please enter title for page.');
            /* ->add('title', 'unique',[
                    'rule' => 'validateUnique',
                    'message' => 'This title is already Exist.',
                    'provider' => 'table'

                ]);*/
        $validator
            ->notEmpty('first_name','Please enter your fist name.')
               ->add('first_name', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z0-9 ]*$|'),
                'message' => 'First Name must contain letters and spaces only.'
			]);
        $validator
            ->notEmpty('last_name','Please enter your last name.')
               ->add('last_name', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z0-9 ]*$|'),
                'message' => 'Last Name must contain letters and spaces only.'
			]);
        $validator
        ->notEmpty('email', 'Please enter E-mail')
        ->add('email', [
            'length' => [
                'rule' => 'email',
                'message' => 'You must enter a valid email.',
            ]
            /*'unique'=>[
                'rule' => 'validateUnique',
                'message' => 'This email is already registered.',
                'provider' => 'table'

            ],*/
      
        ]);
        $validator
            ->notEmpty('country', 'You must enter your country.');
        $validator
            ->notEmpty('enquiry', 'You must enter your inquiry type.');
      return $validator;
    }
  

}
?>