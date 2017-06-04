<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CmspagesTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Cmspages');
       
    }
    public function validationDefault(Validator $validator)
    {
       //die("jgkj");
        $validator
                ->notEmpty('title','Please enter title for page.');
            /* ->add('title', 'unique',[
                    'rule' => 'validateUnique',
                    'message' => 'This title is already Exist.',
                    'provider' => 'table'

                ]);*/
        $validator
                ->notEmpty('slug','Please enter slug for page.');
              /*   ->add('slug', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z0-9 ]*$|'),
                'message' => 'Slug must contain letters,digits and spaces only.'
			])*/
            /* ->add('slug', 'unique',[
                    'rule' => 'validateUnique',
                    'message' => 'This title is already Exist.',
                    'provider' => 'table'

                ]);*/
        $validator
                ->notEmpty('meta_title','Please enter meta title for page.');
        $validator
                ->notEmpty('meta_keyword','Please enter meta keyword  for page.');
        $validator
                ->notEmpty('meta_description','Please enter meta description for page.');
        /*$validator
                ->notEmpty('content','Please enter content for page.');*/
       // $validator
              //  ->notEmpty('template','Please select template for page.');
    
        return $validator; 
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
    }
   

}
?>