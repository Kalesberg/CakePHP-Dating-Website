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
                <li><a href="javascript:;" title="Sample page 1">Membership Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Edit Membership</a>
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
                                <h6>Membership Details
                                    </h6>
                            </div>
                            
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                  <?php echo $this->Form->Create($post,[
                                        'class' =>'form-horizontal'
                                        //'id'=>'demo-form2',
                                       // 'inputDefaults' => array(
                                       // 'label' => false,
                                        //'div' => false
                                        ]);
                                  //pr($post);die;
                                  ?>
                                        <fieldset>
                                            <div class="control-group">
                                                <?php echo $this->Form->input('id',['type'=>'hidden','value'=>$post[0]['id']]); ?>
                            
                                                <label class="control-label" for="first_name">Membership Name*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('membership_name',array('class'=>'form-control','value'=>$post[0]['membership_name'],'label'=>false)); ?>
                                
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Price*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('price',array('class'=>'form-control','value'=>$post[0]['price'],'label'=>false)); ?>
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
                                               <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['default'=>$post[0]['status'],'class'=>'form-control','required'=>'required']); ?>
                                            </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                               <?php echo $this->Html->Link("Cancel",['controller'=>'Memberships','action'=>'membershiplist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                            </div>
                                        </fieldset>
                                    <?php echo $this->Form->end()?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <?php echo $this->element('admin_footer'); ?>
  
<?php  echo $this->element('admin_footer_js');  ?>
<!-- /MAIN EFFECT -->
    
