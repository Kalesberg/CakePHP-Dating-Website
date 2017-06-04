<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class SurveysTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Surveys');
       
    }
    
   /* public function validationChangeDefault(Validator $validator){
        $validator ->notEmpty('password','Please enter Password');    
        $validator->requirePresence('cpassword')
        ->notEmpty('cpassword','Please enter Confirm Password')
        ->add('cpassword', [
                        'compare' => [
                        'rule' => ['compareWith','password'],
                        'message' => 'Confirm Password does not match with Password.'
                        ]]);
        return $validator;
    }
    
    
    
    */
    

}
?>