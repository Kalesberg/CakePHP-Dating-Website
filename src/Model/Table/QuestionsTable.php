<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class QuestionsTable extends Table
{
	
    public function initialize(array $config)
    {
	//	$this->belongsTo('Questions');
       
    }
    public function validationQuestionDefault(Validator $validator)
    {
         $validator->requirePresence('category_id')    
                ->notEmpty('category_id','Please enter Question category.');
                
        $validator->requirePresence('question_text')
                ->notEmpty('question_text','Please enter Question.');
                
        $validator->requirePresence('option_1')
                ->notEmpty('option_1','Please enter option A.');
                
        $validator->requirePresence('option_2')
                ->notEmpty('option_2','Please enter option B.');
                
        //$validator->requirePresence('option_3')    
               // ->notEmpty('option_3','Please enter option C.');
        //$validator->requirePresence('option_4')    
                //->notEmpty('option_4','Please enter option 4.');
           
        return $validator;
    }

}
?>