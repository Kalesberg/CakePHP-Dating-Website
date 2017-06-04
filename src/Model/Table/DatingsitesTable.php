<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class DatingsitesTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Cmspages');
       
    }
    public function validationDefault(Validator $validator)
    {
       /* $validator->notEmpty('title','Please enter your page title.');
        $validator->notEmpty('slug','Please enter slug for your page.');
        $validator->notEmpty('meta_title','Please enter meta title for page.');
        $validator->notEmpty('meta_keyword','Please enter meta keyword for page.');
        $validator->notEmpty('meta_description','Please enter meta description for page.');
        $validator->notEmpty('contant','Please enter contant for page.');
        $validator->notEmpty('template','Please enter template for page.');
        return $validator; 
		->add('image',[
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