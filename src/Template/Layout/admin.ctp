<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

//$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>
        <?= "Self-Match" ?>
    </title>
	<?= $this->Html->meta('icon') ?>
   <?php echo $this->Html->css(['style','loader-style','bootstrap','signin','profile']);
  // echo $this->Html->css('../js/colorPicker/bootstrap-colorpicker');
  // echo $this->Html->css('../js/validate/validate');
  // echo $this->Html->css('../js/idealform/css/jquery.idealforms');
	echo $this->Html->css('../js/timepicker/bootstrap-timepicker');
    echo $this->Html->css('../js/datepicker/datepicker');
   // echo $this->Html->css('../js/datepicker/clockface');
    echo $this->Html->css('../js/datepicker/bootstrap-datetimepicker');
	//echo $this->Html->css('../js/tag/jquery.tagsinput');
   //echo $this->Html->css('../js/progress-bar/number-pb');
 // echo $this->Html->css('../js/footable/css/footable.core?v=2-0-1');
  // echo $this->Html->css('../js/footable/css/footable.standalone');
  // echo $this->Html->css('../js/footable/css/footable-demos');
  // echo $this->Html->css('../js/dataTable/lib/jquery.dataTables/css/DT_bootstrap');
  // echo $this->Html->css('../js/dataTable/css/datatables.responsive');
   ?>
	<?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.js"></script>
	</head>
	<?php echo $this->fetch('content'); ?>
</html>

