<?php
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
?>
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
                <li><a href="javascript:;" title="Sample page 1">Category Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Edit Category</a>
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
                                <h6>Category Details
                                    </h6>
                              
                            </div>
                           
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                     <?php echo $this->Form->Create($post,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                                        <fieldset>
                                            <div class="text-center">
                                                <?php if(isset($post[0]['category_icon'])){ 
                                                    echo $this->Html->image($post[0]['category_icon'],array('class'=>'avatar img-circle','id'=>'photo','height'=>'100','width'=>'100'));  ?>
                                                    <h6>Upload a different photo...</h6>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <span class="btn btn-primary btn-file">
                                                                Browse
                                                                <input type="file" onchange="readURL(this);" multiple="" value="<?php echo $post[0]['category_icon']; ?>" name="category_icon" id="photo">
                                                                <input type="hidden" name="photo" value="<?php echo $post[0]['category_icon']; ?>" />
                                                            </span>
                                                        </span>
                                                       
                                                        <!--<input type='submit' value='Save' class="btn btn-primary"/>-->
                                                    </div>
                                                 <?php } else {  ?>
                                                    <?php echo $this->Html->image($post[0]['category_icon'],array('class'=>'avatar img-circle','id'=>'photo','height'=>'100','width'=>'100')); ?>
                                                    <h6>Upload a different photo...</h6>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <span class="btn btn-primary btn-file">
                                                                Browse
                                                                <input type="file" onchange="readURL(this);" multiple="" value="<?php echo $post[0]['category_icon']; ?>" name="category_icon" id="photo">
                                                            </span>
                                                        </span>
                                                    </div>
                                                   
                                                    <!--<input type='submit' value='Save' class="btn btn-primary"/>-->
                                                <?php } ?>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Title*</label>
                                                <div class="controls">
                                                   
                                                    <?php echo $this->Form->input('id',['value'=>$post[0]['id'],'type'=>'hidden']); ?>
                                                    <?php echo $this->Form->input('category_name',['value'=>$post[0]['category_name'],'class'=>'form-control','required'=>true,'label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Show in Member Dashboard*</label>
                                                <div class="controls">
                                                    <?php $flag = array('1'=>'Yes','2'=>'No');echo $this->Form->select('flag', $flag, ['default'=>$post[0]['flag'],'class'=>'form-control','required'=>'required']); ?>                            
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Description</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('description',['value'=>$post[0]['description'],'class'=>'form-control','type'=>'textarea','label'=>false]); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                               
                                                <label class="control-label" for="email">Status*</label>
                                                <div class="controls">
                                                    <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['default'=>$post[0]['status'],'class'=>'form-control','required'=>'required']); ?>
                                                </div>
                                            </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                 <?php echo $this->Html->Link("Cancel",['controller'=>'Categories','action'=>'categorylist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                               
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

    <script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#photo')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
    
