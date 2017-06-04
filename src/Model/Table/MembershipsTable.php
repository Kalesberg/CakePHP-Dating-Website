<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
class MembershipsTable extends Table
{
    public function initialize(array $config)
    {
		$this->belongsTo('Memberships');
       
    }
    public function validationMemberDefault(Validator $validator)
    {
    
        $validator->requirePresence('price','create')
                ->notEmpty('price','Enter price');
        $validator->requirePresence('membership_name','create')
                ->notEmpty('membership_name','Enter membership name')
               /*  ->add('membership_name', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'Membership name must contain letters and spaces only.'
			])*/
             ->add('membership_name', 'unique',[
                    'rule' => 'validateUnique',
                    'message' => 'This Memebership is already Exist.',
                    'provider' => 'table'

                ]);
        return $validator;
    }
   // public function validationDefault(Validator $validator)
   // {
           // $validator->add('id','valid',['rule' => 'numeric'])
			//		->allowEmpty('id','create');
			//		$validator->notEmpty('legal_name','Please provide legal name');
				//	$validator->add('first_name','validFormat',[
				//	'rule' => array('custom', '|^[a-zA-Z ]*$|'),
				//	'message' => 'First name must contain letters and spaces only.'
				//	])
		/*->add('image',[
			'validType' =>[
			    'rule' => function($value){
				    $ext = explode('.',$value['name']);
				    $ext = end( $ext );
				    $ext = strtolower($ext);
				    return in_array($ext,['jpg','jpeg','png','gif']);
				    
				},
			    'message' => 'Please upload only gif,png,jpg type file'
			]])
		//->add('link', 'valid', ['rule' => 'url','message' => 'The link must be a valid url address'])
		//->allowEmpty('image',true)*/
        
		//return $validator;
   // }
   /* public function validationEmail(Validator $validator){
					$validator->add('email',[
					'length' => [
					'rule' => 'email',
					'message' => 'you must enter a valid email.',
					],
					'unique'=>[
					'rule' => 'validateUnique',
					'message' => 'email already exist',
					'provider' => 'table'
					]
					])
					->requirePresence('email','create')
					->notEmpty('email','Please enter email id.');				
        return $validator;
    }*/
}
?>