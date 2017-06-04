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
                <li><a href="javascript:;" title="Sample page 1">Cms Page Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Add Cms Page</a>
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
                    <?php // echo $this->Flash->render() ?>
                    <div class="col-sm-12">
                        <div class="nest" id="validationClose">
                            <div class="title-alt">
                                <h6>
                                    Page Details</h6>
                               <!-- <div class="titleClose">
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
                            <div class="body-nest">
                                <div class="form_center">
                                      <?php echo $this->Form->create($post,array('class'=>'form-horizontal')); ?>
                                   <!-- <form action="addcmspage"  method="post">-->
                                        <fieldset>
                                            <div class="control-group">
                                                <label class="control-label" for="title">Title*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('title',['class'=>'form-control','label'=>false]); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="slug">Slug*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('slug',['class'=>'form-control','label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="meta_title">Meta Title*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('meta_title',['class'=>'form-control','label'=>false]); ?>
                                                   
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="meta_keyword">Meta Keyword*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('meta_keyword',['class'=>'form-control','label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="meta_description">Meta Description*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('meta_description',['class'=>'form-control','type'=>'textarea','label'=>false]); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="contant">Content*</label>
                                                <div class="controls">
                                                    <?php
                                                   echo $this->Form->textarea("content", array('id'=>'CmspagesContent')); 
                                                  echo $this->FCK->load("Cmspages/content","Default",400,"100%");
                                                    ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="template">Show On</label>
                                                <div class="controls">
                                                    <?php $options =array('0'=>'None','1'=>'header_menu','2'=>'footer_menu','3'=>'home');
                                                    echo $this->Form->select('show_on', $options, ['class'=>'form-control','multiple'=>'multiple']);
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                             <div class="control-group">
                                                <label class="control-label" for="template" >Template*</label>
                                                <div class="controls">
                                                    <?php $options =array('0'=>'Default','1'=>'Home','2'=>'Contact');
                                                    echo $this->Form->select('template', $options, ['class'=>'form-control']);
                                                    ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="status">Status</label>
                                                <div class="controls">
                                                    <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['class'=>'form-control']); ?>
                                                </div>
                                            </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                 <?php echo $this->Html->Link("Cancel",['controller'=>'cmspages','action'=>'cmspagelist'],['class'=>'btn btn-default','escape'=>false]); ?>
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
   
   
