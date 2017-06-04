<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CategoriesTable extends Table
{
	
    public function initialize(array $config)
    {
		//$this->belongsTo('Categories');
        $this->hasMany('Questions', [
           'className' => 'Questions',
            'foreignKey' => 'category_id',
            'dependent' =>true
        ]);
    }
    public function validationAddDefault(Validator $validator)
    {
      
        $validator
            ->notEmpty('category_name', 'You must enter your category name.')
            ->add('category_name', 'validFormat',[
                'rule' => array('custom', '|^[a-zA-Z ]*$|'),
                'message' => 'Category name must contain letters and spaces only.'
			])
             ->add('category_name', 'unique',[
                    'rule' => 'validateUnique',
                    'message' => 'This Category is already Exist.',
                    'provider' => 'table'

                ]
                  );
             
       $validator
       ->notEmpty('category_icon', 'You must enter your category name.')
       ->add('category_icon',[
			'validType' =>[
			    'rule' => function($value){
				    $ext = explode('.',$value['name']);
				    $ext = end( $ext );
				    $ext = strtolower($ext);
				    return in_array($ext,['jpg','jpeg','png','gif']);
				},
			    'message' => 'Please upload only gif,png,jpg type file'
			]]);
        
		return $validator;
    }
   

}
?>