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
                <li><a href="javascript:;" title="Sample page 1">Edit Promo Code</a>
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
                            </div>
                            
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                     <?php echo $this->Form->Create($post,['class'=>'form-horizontal']); ?>
                                        <fieldset>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Title*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('promocode_title',array('class'=>'form-control','value'=>$post[0]['promocode_title'],'label'=>false)); ?>
                                                 <?php echo $this->Form->input('id',['type'=>'hidden','value'=>$post[0]['id']]); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Price*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('price',array('class'=>'form-control','value'=>$post[0]['price'],'label'=>false)); ?>
                                
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Type*</label>
                                                <div class="controls">
                                                    <?php
                                                    //$membership=array('1'=>'Simple Survey','2'=>'Advanced Survey with Pros/Cons Calculator','3'=>'Gold Membership','4'=>'Platinum Membership');
                                                    echo $this->Form->select('type', $membership,['default'=>$post[0]['type'],'class'=>'form-control membership','label'=>false]);  ?>
                                                </div>
                                            </div>
                                            <div id="duration">
                                                <div class="control-group"  >
                                                    <?php if($post[0]['type']=='1' || $post[0]['type']=='2'){ ?>
                                                       <div class="control-group"  >
                                                            <label class="control-label" for="last_name">Times*</label>
                                                            <div class="controls">
                                                                <?php echo $this->Form->input('duration',['value'=>$post[0]['duration'],'class'=>'form-control','label'=>false,'required'=>'required','type'=>'number']); ?>
                                                            </div>
                                                        </div>
                                                    <?php }else{ ?>
                                                    <div class="control-group">
                                                        <label class="control-label" for="last_name">Months*</label>
                                                        <div class="controls">
                                                           <?php
                                                            $val =array('1 Month'=>'1 Month','3 Months'=>'3 Months');
                                                           echo $this->Form->select('duration',$val,['default'=>$post[0]['duration'],'class'=>'form-control','label'=>false,'required'=>'required']); ?>
                                                        </div>
                                                    </div>
                                                    <?php  } ?>
                                                   
                                                  
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Description</label>
                                                <div class="controls">
                                                   <?php echo $this->Form->input('description',array('class'=>'form-control','value'=>$post[0]['description'],'type'=>'textarea','label'=>false)); ?>
                                                </div>
                                            </div>
                                           <div class="control-group">
                                                <label class="control-label" for="email">Status*</label>
                                                <div class="controls">
                                                     <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['default'=>$post[0]['status'],'class'=>'form-control','required'=>'required']); ?>
                                                </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                 <?php echo $this->Html->Link("Cancel",['controller'=>'Promocodes','action'=>'promocodelist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                            </div>
                                        </fieldset>
                                        <?php echo $this->Form->end(); ?>
                                        <div id='time' style="display: none;">
                                            <div class="control-group"  >
                                                  <label class="control-label" for="last_name">Times*</label>
                                                  <div class="controls">
                                                      <?php echo $this->Form->input('duration',['value'=>$post[0]['duration'],'class'=>'form-control durationTime','id'=>'durationval','label'=>false,'type'=>'number']); ?>
                                                  </div>
                                              </div>
                                        </div>
                                         <div id='month' style="display: none;">
                                            <div class="control-group">
                                                  <label class="control-label" for="last_name">Months*</label>
                                                  <div class="controls">
                                                     <?php
                                                      $val =array('1'=>'1 Month','3'=>'3 Months');
                                                     echo $this->Form->select('duration',$val,['default'=>$post[0]['duration'],'class'=>'form-control durationMonth','label'=>false]); ?>
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