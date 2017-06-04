   
        <?php echo $this->element('admin_header'); ?>
        <?php echo $this->element('admin_sidebar'); ?>
       <?php //$AdminData = $this->request->session()->read('Auth.User'); ?>
        <!--  PAPER WRAP -->
        <div class="wrap-fluid">
            <div class="container-fluid paper-wrap bevel tlbr">
               
                <?php echo $this->element('admin_title'); ?>
    
                <!-- BREADCRUMB -->
                <ul id="breadcrumb">
                    <li>
                        <span class="entypo-home"></span>
                    </li>
                    <li><i class="fa fa-lg fa-angle-right"></i>
                    </li>
                    <li><a href="javascript:;" title="Sample page 1">Admin Management</a>
                    </li>
                    <li><i class="fa fa-lg fa-angle-right"></i>
                    </li>
                    <li><a href="javascript:;" title="Sample page 1">Change Password</a>
                    </li>
                   <!-- <li class="pull-right">
                        <div class="input-group input-widget">
    
                            <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">
                        </div>
                    </li>-->
                </ul>
    
                <!-- END OF BREADCRUMB -->
                <div class="content-wrap">
                    <div class="row">
                         <?php echo $this->Flash->render() ?>
                        <div class="col-sm-12">
                            <div class="nest" id="validationClose">
                                <div class="title-alt">
                                    <h6>
                                        Change Password</h6>
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
                                        <?php echo $this->Form->Create($post,['class'=>'form-horizontal']); ?>
                                            <fieldset>
                                                <div class="control-group">
                                                    <label class="control-label">Old Password*</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('oldpassword',['class'=>'form-control','label'=>false,'type'=>'password','required']); ?>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">New Password*</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('newpassword',['class'=>'form-control','label'=>false,'type'=>'password','required']); ?>
                                                        
                                                        <?php echo $this->Form->input('id',['class'=>'form-control','type'=>'hidden','value'=>$post->id]); ?>
                                                        
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">Confirm Password*</label>
                                                    <div class="controls">
                                                        <?php echo $this->Form->input('cpassword',['class'=>'form-control','label'=>false,'type'=>'password','required']); ?>
                                                     
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label"></label>
                                                    <div class="controls">
                                                        <input class="btn btn-primary" value="Save Changes" type="submit" required>
                                                        <span></span>
                                                       <?php echo $this->Html->Link("Cancel",['controller'=>'Affiliates','action'=>'affiliateDetails'],['class'=>'btn btn-default','escape'=>false]); ?>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>     
        <!-- END OF PROFILE --><?php echo $this->element('admin_footer'); ?>
                  
        <?php  echo $this->element('admin_footer_js');  ?>