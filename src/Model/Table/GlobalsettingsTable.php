<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class GlobalsettingsTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Globalsettings');
       
    }
    

    public function validationDefault(Validator $validator)
    {
       
      $validator
                ->notEmpty('value','You must Enter details regarding to this field.');
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
		return $validator;
    }
   

}
?>