
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
                <li><a href="javascript:;" title="Sample page 1">Question Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Add Question</a>
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
                                <h6>Question Details
                                    </h6>
                               
                            </div>
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                    <?php echo $this->Form->create($post,
                                        ['class' =>'form-horizontal'
                                        //'id'=>'demo-form2',
                                       // 'inputDefaults' => array(
                                       // 'label' => false,
                                        //'div' => false
                                       ] ); ?>
                                        <fieldset>
                                           <div class="control-group">
                                                <label class="control-label" for="email">Category*</label>
                                                <div class="controls">
                                                    <?php
                                                    echo $this->Form->select('category_id', $category,['class'=>'form-control','label'=>false]);  ?>
                                                       
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Question*</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('question_text',array('class'=>'form-control','label'=>false)); ?>
                                                    
                                                </div>
                                            </div>
                                             <div class="control-group">
                                                <label class="control-label" for="first_name">Option A*</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('option_1',array('class'=>'form-control','label'=>false)); ?>
                                                    
                                                </div>
                                            </div>
                                              <div class="control-group">
                                                <label class="control-label" for="first_name">Option B*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('option_2',array('class'=>'form-control','label'=>false)); ?>
                                                
                                                </div>
                                            </div>
                                               <div class="control-group">
                                                <label class="control-label" for="first_name">Option C</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('option_3',array('class'=>'form-control','label'=>false)); ?>
                                                    
                                                </div>
                                            </div>
                                                <div class="control-group">
                                                <label class="control-label" for="first_name">Option D</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('option_4',array('class'=>'form-control','label'=>false)); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Description</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('description',array('class'=>'form-control','type'=>'textarea','label'=>false)); ?>
                                                </div>
                                            </div>
                                             <div class="control-group">
                                                <label class="control-label" for="email">Status*</label>
                                                <div class="controls">
                                                    <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['class'=>'form-control','required'=>'required']); ?>
                                                </div>
                                            </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <?php echo $this->Html->Link("Cancel",['controller'=>'Questions','action'=>'questionlist'],['class'=>'btn btn-default','escape'=>false]); ?>
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

