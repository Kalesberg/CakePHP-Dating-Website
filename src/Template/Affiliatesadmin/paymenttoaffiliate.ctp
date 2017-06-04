 <?php echo $this->element('admin_header'); ?>
        <?php echo $this->element('admin_sidebar'); ?>
    <!--  PAPER WRAP -->
    <div class="wrap-fluid">
        <div class="container-fluid paper-wrap bevel tlbr">
            <!-- CONTENT -->
            <!-- BREADCRUMB -->
            <ul id="breadcrumb">
                <li>
                    <span class="entypo-home"></span>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="#" title="Sample page 1">Payment</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="#" title="Sample page 1">Payment to Affiliate</a>
                </li>
                <li class="pull-right">
                    <div class="input-group input-widget">
                     <!--   <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">-->
                    </div>
                </li>
            </ul>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <?php echo $this->Flash->render(); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="nest" id="validationClose">
                            <div class="title-alt">
                                <h6>
                                    Payment to Affiliate</h6>
                                <!--<div class="titleClose">
                                    <a class="gone" href="#validationClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#validation">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>-->
                            </div>
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                    <?php echo $this->Form->Create($post,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                                  <!--<form action="adduser" id="contact-form" class="form-horizontal" method="post" enctype="multipart/form-data"> -->
                                        <fieldset>
											<div class="control-group">
												<label class="control-label" for="first_name">Amount*</label>
												<p>Amount should be in $(AUD).</p>
												<div class="controls">
													<?php echo $this->Form->input('amount',['class'=>'form-control','required'=>'required','label'=>false,'type'=>'text']); ?>
												</div>
											</div>
                                            <div class="control-group">
                                               <label class="control-label" for="first_name">Payment Mode</label>
                                                <div class="controls">
                                                    <?php
                                                    $type=['0'=>'Select','1'=>'Manually','2'=>'Paypal'];
                                                    echo $this->Form->select('payment_mode',$type,['default'=>$post->payment_mode,'class'=>'form-control payment_mode','label'=>false]);  ?>
                                                </div>
                                            </div>
                                            <div id="mode">
                                             
                                            </div>
											
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Date</label>
                                                <div id="datetimepicker1" class="input-group date control-group  col-md-6">
                                                    <input class="form-control" data-format="yyyy-MM-dd hh:mm:ss" name="date" type="text" placeholder="yyyy/MM/dd hh:mm:ss">
    
                                                    <span class="input-group-addon add-on">
                                                        <i style="font-style:normal;" data-time-icon="entypo-clock"   data-date-icon="entypo-calendar">
                                                        </i>
                                                    </span>
                                                </div>
                                            </div>
											<div class="form-actions" style="margin:20px 0 0 0;">
												<button type="submit" class="btn btn-primary">Submit</button>
												  <?php echo $this->Html->Link("Cancel",['controller'=>'Affiliatesadmin','action'=>'paymenttoaffiliate'],['class'=>'btn btn-default','escape'=>false]); ?>
											</div>
                                        </fieldset>
                                   <?php echo $this->Form->end(); ?>
                                            <div class="control-group"  id="check" style="display: none">
												<label class="control-label" for="last_name">Check Number</label>
												<div class="controls">
													<?php echo $this->Form->input('balance_transaction',['class'=>'form-control checknumber','label'=>false]); ?>
												</div>
											</div>
                                            <div class="control-group" id="paypal" style="display: none">
												<label class="control-label" for="last_name">Paypal Transaction Number</label>
												<div class="controls">
													<?php echo $this->Form->input('balance_transaction',['class'=>'form-control paypalnumber','label'=>false]); ?>
												</div>
											</div>
                                </div>
                            </div>
                        </div>
                    </div>
               
             <?php echo $this->element('admin_footer'); ?>
  
<?php  echo $this->element('admin_footer_js');  ?>
<!-- /MAIN EFFECT -->
 
    
    
<script type="text/javascript">
    
    $('#datetimepicker1').datetimepicker({
        language: 'pt-BR'
    });
    $('#dp1').datepicker()
    $('#dpYears').datepicker();
    $('#timepicker1').timepicker();
    $('#t1').clockface();
    $('#t2').clockface({
        format: 'HH:mm',
        trigger: 'manual'
    });

    $('#toggle-btn').click(function(e) {
        e.stopPropagation();
        $('#t2').clockface('toggle');
    });
		$('#datetimepicker2').datetimepicker({
        language: 'pt-BR'
    });
    $('#dp1').datepicker()
    $('#dpYears').datepicker();
    $('#timepicker1').timepicker();
    $('#t1').clockface();
    $('#t2').clockface({
        format: 'HH:mm',
        trigger: 'manual'
    });

    $('#toggle-btn').click(function(e) {
        e.stopPropagation();
        $('#t2').clockface('toggle');
    });
    
    
    $(function(){
        $('input').attr('required',false);
        })
    

    $('.payment_mode').change(function(){
        var v =$('.payment_mode').val();
        //alert(v);
        if (v=='1') {
            var html =$('#check').html();
            $('#mode').html(html);
            $('.checknumber').attr('required',true);
            $('.paypalnumber').attr('required',false);
        }else{
            var html =  $('#paypal').html();
            $('#mode').html(html);
            $('.paypalnumber').attr('required',true);
            $('.checknumber').attr('required',false);
        }
    });

</script>
    


   