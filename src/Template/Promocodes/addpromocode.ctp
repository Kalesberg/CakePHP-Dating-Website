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
                <li><a href="javascript:;" title="Sample page 1">Promo Code Management </a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Add Promo Code</a>
                </li>
                <li class="pull-right">
                    <div class="input-group input-widget">
                     <!--   <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">-->
                    </div>
                </li>
            </ul>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <div class="row">
                    <?php //echo $this->Flash->render() ?>
                    <div class="col-sm-12">
                        <div class="nest" id="validationClose">
                            <div class="title-alt">
                                <h6>
                                    Promo Code Details
                                    </h6>
                                 <div class="titleClose">
                                    <a class="gone" href="#dateClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#date_1">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="body-nest" id="#date_1">
                                <div class="form_center">
                                   <?php echo $this->Form->Create($post,['class'=>'form-horizontal']); ?>
                                  
                                        <fieldset>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Promocode Title*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('promocode_title',array('class'=>'form-control','label'=>false)); ?>
                                                  
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Price*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('price',array('class'=>'form-control','label'=>false)); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Type*</label>
                                                <div class="controls">
                                                    <?php
                                                    //$membership=array('1'=>'Simple Survey','2'=>'Advanced Survey with Pros/Cons Calculator','3'=>'Gold Membership','4'=>'Platinum Membership');
                                                    echo $this->Form->select('type', $membership,['class'=>'form-control membership','label'=>false,'empty'=>'Please Select type' ]);  ?>
                                                </div>
                                            </div>
                                           <div id="duration">
                                             
                                           </div>
                                            <!--<div class="control-group">
                                                 <label class="control-label" for="last_name">Expiry Date</label>
                                                <div id="datetimepicker1" class="input-group date">
                                                    <input class="form-control" data-format="yyyy-MM-dd" name="expirydate" type="text" placeholder="yyyy/MM/dd">
    
                                                    <span class="input-group-addon add-on">
                                                        <i style="font-style:normal;" data-time-icon="entypo-clock" data-date-icon="entypo-calendar">
                                                        </i>
                                                    </span>
                                                </div>
                                            </div>-->
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Description</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('description',array('class'=>'form-control','type'=>'textarea','label'=>false)); ?>
                                                  
                                                </div>
                                            </div>
                                           <div class="control-group">
                                                <label class="control-label" for="email">Status</label>
                                                <div class="controls">
                                                   
                                                    <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['class'=>'form-control','required'=>'required']); ?>
                                                </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                               <?php echo $this->Html->Link("Cancel",['controller'=>'Promocodes','action'=>'promocodelist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                            </div>
                                            
                                        </fieldset>
                                        <?php //echo $this->Form->end(); ?>
                                     <?php echo $this->Form->end(); ?>
                                     
                                        <div id='time' style="display: none;">
                                          <div class="control-group"  >
                                                <label class="control-label" for="last_name">Times*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('duration',['Placeholder'=>'Days','class'=>'form-control durationTime','label'=>false,'id'=>'durationval','type'=>'number']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div id='month' style="display: none;">
                                          <div class="control-group">
                                                <label class="control-label" for="last_name">Months*</label>
                                                <div class="controls">
                                                    
                                                   <?php
                                                   $val =array('1'=>'1 Month','3'=>'3 Month');
                                                   echo $this->Form->select('duration',$val,['class'=>'form-control durationMonth','label'=>false,'empty'=>'Please select month']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    
                                           
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $this->element('admin_footer'); ?>
            <?php  echo $this->element('admin_footer_js');  ?>
<!-- /MAIN EFFECT -->
<script>
    $('.membership').change(function(){
        var v =$('.membership').val();
        //alert(v);
        if (v=='9') {
           var html =$('#time').html();
            $('#duration').html(html);
            $('.durationTime').attr('required',true);
            $('.durationMonth').attr('required',false);
        }else if (v=='7' || v=='8') {
            var html =  $('#month').html();
            $('#duration').html(html);
            $('.durationMonth').attr('required',true);
            $('.durationTime').attr('required',false);
        }
    });
</script>
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
    </script>