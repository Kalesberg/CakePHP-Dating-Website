 <?php use Cake\Core\Configure;?>
<?php echo $this->Html->script('fckeditor');?>
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
                <li><a href="javascript:;" title="Sample page 1">Email Template Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Edit Email Template</a>
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
                                    Template Details</h6>
                            </div>
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                    <?php echo $this->Form->Create($post,['class'=>'form-horizontal']); ?>
                                        <fieldset>
                                            <div class="control-group">
                                                <label class="control-label" for="title">Title*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('id',['type'=>'hidden','value'=>$post[0]['id']]); ?>
                                                    
                                                    <?php echo $this->Form->input('name',array('class'=>'form-control','value'=>$post[0]['name'],'label'=>false)); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="slug">Slug*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('slug',array('class'=>'form-control','value'=>$post[0]['slug'],'label'=>false,'readonly'=>true)); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="subject">Subject*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('subject',array('class'=>'form-control','value'=>$post[0]['subject'],'label'=>false)); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="description">Contant*</label>
                                                <div class="controls">
                                                   
                                                     <?php
                                                        echo $this->Form->textarea("description", array('id'=>'EmailtemplatesDescription','value'=>$post[0]['description'])); 
                                                        echo $this->FCK->load("Emailtemplates/description","Default",400,"100%");
                                                    ?>
                                                </div>
                                            </div>
                                
                                            <div class="control-group">
                                                <label class="control-label" for="status">Status*</label>
                                                <div class="controls">
                                                     <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['default'=>$post[0]['status'],'class'=>'form-control','required'=>'required']); ?>
                                                </div>
                                            </div>
                                            
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                               <?php echo $this->Html->Link("Cancel",['controller'=>'Emailtemplates','action'=>'emailtemplatelist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                            </div>
                                        </fieldset>
                                  <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <?php echo $this->element('admin_footer'); ?>
<?php  echo $this->element('admin_footer_js');  ?>
<!-- /MAIN EFFECT -->
    
