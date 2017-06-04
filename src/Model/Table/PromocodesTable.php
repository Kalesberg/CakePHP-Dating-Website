<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PromocodesTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Promocodes');
       
    }
    public function validationDefault(Validator $validator)
    {
        $validator->notEmpty('promocode_title', 'please enter code title.')
                 ->add('promocode_title', [
                 'unique'=>[
                    'rule' => 'validateUnique',
                    'message' => 'This promocode is already exists.',
                    'provider' => 'table'
                ],
			]);
                //->add('promocode_title', 'validFormat',[
                //'rule' => array('custom', '|^[a-zA-Z0-9]*$|'),
                //'message' => 'promocode title must contain letters and spaces only.'
			//]);
        $validator->notEmpty('price', 'please enter price.')
        ->add('price','numeric',array('rule' => 'numeric' ,'message'=> 'Please provide a valid price'));
        $validator->notEmpty('type', 'please enter type.');
       
        return $validator;
    }
   

}
?>