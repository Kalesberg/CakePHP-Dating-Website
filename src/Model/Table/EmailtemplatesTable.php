<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class EmailtemplatesTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Emailtemplates');
       
    }
    public function validationEmailDefault(Validator $validator)
    {
        $validator->requirePresence('name')
                ->notEmpty('name','Please enter your page title.');
                
        $validator
                ->notEmpty('slug','Please enter slug for your tamplate.');
                
        $validator->requirePresence('subject')
                ->notEmpty('subject','Please enter subject of email.');
                
        $validator->requirePresence('description')    
                ->notEmpty('description','Please enter contant for page.');
                
        return $validator;
    }
   

}
?>