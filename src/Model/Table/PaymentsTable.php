<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PaymentsTable extends Table
{
	
    public function initialize(array $config)
    {
		$this->belongsTo('Payments');
       
    }
   

}
?>