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
                <li><a href="#" title="Sample page 1">Edit </a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="#" title="Sample page 1">User</a>
                </li>
                <li class="pull-right">
                    <div class="input-group input-widget">
                     <!--   <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">-->
                    </div>
                </li>
            </ul>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <?php  echo $this->Flash->render(); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="nest" id="validationClose">
                            <div class="title-alt">
                                <h6>
                                    Edit User</h6>
                            </div>
                            
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                    <?php echo $this->Form->Create($post,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                                        <fieldset>
                                            <div class="text-center">
                                                <?php if(!empty($post['profile_photo'])){
                                                    $profile=$post['profile_photo'];
                                                    echo $this->Html->image("user_images/".$profile,array('class'=>'avatar img-circle','id'=>'photo','height'=>'100','width'=>'100'));  ?>
                                                    <h6>Upload a different photo...</h6>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <span class="btn btn-primary btn-file">
                                                                Browse
                                                                <input type="file" onchange="readURL(this);" value="<?php if(!empty($post['profile_photo'])){ echo $post['profile_photo']; } ?>" name="profile_photo" id="photo">
                                                                <input type="hidden" name="photo" value="<?php if(!empty($post['profile_photo'])){ echo $post['profile_photo']; }; ?>" />
                                                            </span>
                                                      </span>
                                                       
                                                        <!--<input type='submit' value='Save' class="btn btn-primary"/>-->
                                                    </div>
                                                 <?php } else {
                                                    $profile=$post['profile_photo'];
                                                    ?>
                                                    <?php echo $this->Html->image("user_default.jpeg",array('class'=>'avatar img-circle','id'=>'photo','height'=>'100','width'=>'100')); ?>
                                                    <h6>Upload a different photo...</h6>
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <span class="btn btn-primary btn-file">
                                                                Browse
                                                                  <input type="hidden" name="photo" value="user_default.jpeg" />
                                                                <input type="file" onchange="readURL(this);" value="<?php echo $profile; ?>" name="profile_photo" id="photo">
                                                            </span>
                                                        </span>
                                                    </div>
                                                   
                                                    <!--<input type='submit' value='Save' class="btn btn-primary"/>-->
                                                <?php } ?>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">First Name*</label>
                                                <div class="controls">
                                            
                                                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>" />
                                                     <?php echo $this->Form->input('first_name',['class'=>'form-control','value'=>$post['first_name'],'label'=>false]); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Last Name*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('last_name',['class'=>'form-control','value'=>$post['last_name'],'label'=>false]); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Email Address*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('email',['class'=>'form-control','value'=>$post['email'],'label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Country Code</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->select('countrycode',$countrycode,['value'=>$post['countrycode'],'placeholder'=>'Country Code','class'=>'form-control','label'=>false]); ?>
                                                </div>
                                                <label class="control-label" for="email">Phone</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('phone',['class'=>'form-control','value'=>$post['phone'],'label'=>false]); ?>
                                                    
                                                </div>
                                            </div>
                                            
											<div class="control-group">
                                                <label class="control-label" for="email">Country*</label>
                                                <div class="controls">
                                                      <?php echo $this->Form->select('country',$countries,['class'=>'form-control countryId','default'=>$post['country'],'label'=>false]); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">State/Region*</label>
                                                <div class="controls">
                                                      <?php
														
														echo $this->Form->select('region',$states,['default'=>$post['region'],'class'=>'form-control stateId','label'=>false]); ?>
                                                  
                                                </div>
                                            </div>
                                             <div class="control-group">
                                                <label class="control-label" for="email">City*</label>
                                                <div class="controls">
                                                    <?php
														
														echo $this->Form->select('city',$cities,['default'=>$post['city'],'class'=>'form-control cityId','label'=>false]); 
													?>
                                                
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Age*</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('age',['class'=>'form-control','value'=>$post['age'],'label'=>false,'min'=>'0','max'=>'100']); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Gender*</label></br>
                                                <div class="controls">
                                                 <?php
                                                    $option = ['1'=>'Male','2'=>'Female'];
                                                    echo $this->Form->radio('gender',$option,['default'=>$post['gender'],'type'=>'radio']); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Relationship Status*</label>
                                                <div class="controls">
                                                    <ul class="radio-buttons">
                                                        <li>
                                                             <?php $rel=array('1'=>'I have a prospective partner.','2'=>'I have a current partner.');
                                                            echo $this->Form->radio('relationship_status',$rel,['default'=>$post['relationship_status'],'type'=>'radio','separator'=>'</li><li>']); ?>
                                                        </li>  
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Membership Level*</label>
                                                <div class="controls">
                                                    <ul class="radio-buttons">
                                                        <li>
                                                            <?php
                                                    		echo $this->Form->radio('membership_level',$membership,['default'=>$post['membership_level'],'type'=>'radio','separator'=>'</li><li>']); ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Are you a member of a dating site? If 'yes', please check all that apply :</label>
                                                <div class="controls">
                                                   <?php
                                                 
                                                    $d=unserialize($post['datingsites']);
                                                    //pr($d);
                                                    echo  $this->Form->select('datingsites',$sites,['value'=>$d,'id'=>'datingsite','style'=>'width:100%;','multiple'=>'multiple']);  ?>
                                                <label for="flat-checkbox-1"></label>
                                                </div>
                                            </div>
                                            <?php if(!empty($post['other'])){ ?>
                                                <div class="control-group"  id="other">
                                                   <label class="control-label">Other</label>
                                                   <div class="controls" >
                                                       <?php echo $this->Form->input('other',['value'=>!empty($post['other'])?$post['other']:"",'class'=>'form-control','label'=>false]); ?>
                                                   </div>
                                               </div>
                                            <?php }else { ?>
                                                <div class="control-group"  id="other" style="display: none;">
                                                    <label class="control-label">Other</label>
                                                    <div class="controls" >
                                                        <?php echo $this->Form->input('other',['class'=>'form-control','label'=>false]); ?>
                                                    </div>
                                                </div>
                                             <?php } ?>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Username*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('username',['value'=>$post['username'],'class'=>'form-control','label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
                                            <!--<div class="control-group">
                                                <label class="control-label" for="email">Password*</label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('password',['value'=>base64_decode($post['password']),'class'=>'form-control','type'=>'password','label'=>false]); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Confirm Password*</label>
                                                <div class="controls">
                                                    <?php //echo $this->Form->input('cpassword',['class'=>'form-control','type'=>'password','label'=>false]); ?>
                                                </div>
                                            </div>-->
                                            <!--<div class="control-group">
                                                
                                                <div class="controls">
                                                       <input type="checkbox" name="tc" value="1" required/>
                                                </div>
                                                <label class="control-label">I accept and agree to Self-Match Terms of Use and Privacy Policy. </label>
                                    -->
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <?php echo $this->Html->Link("Cancel",['controller'=>'Users','action'=>'userlist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                            </div>
                                        </fieldset>
                                    <?php echo $this->Form->end(); ?>
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
$('#datingsite').change(function(){
            //alert();
           var r = $("#datingsite").val();
           var i=0;
           var nextProcess = 0;
            for(i=0;i<r.length;i++)
            {
                console.log(r[i]);
                if (r[i]=='0') {
                  nextProcess = parseInt(nextProcess) + parseInt(1);
                }
            }
            if (nextProcess > 0) {
                $('#other').show();
            }else{
                $('#other').hide();
            }
      });
 //datingsite code end
	$(".countryId").change(function(){
    var countryId = $('.countryId').val();
    //alert(countryId);
        $.ajax({
           type:'POST',
           data:{countryId:countryId},
           url:"<?php echo SITE_URL.'Users/states';?>",
           success:function(data) {
            var datas = [];
             datas = jQuery.parseJSON(data);
            var selectoption = "<option value=''>Choose option</option>";
            $('.stateId').html(selectoption);
            for (var i = 0; i < datas.length; i++) {
               var option = '<option value="' + datas[i]['id'] + '">' + datas[i]['name'] + '</option>';
               $('.stateId').append(option);
            }
           }
        });
    });
  $(".stateId").change(function(){
    var stateId = $('.stateId').val();
    //alert(stateId);
        $.ajax({
           type:'POST',
           data:{stateId:stateId},
           url:"<?php echo SITE_URL.'Users/cities';?>",
           success:function(data) {
            var datas = [];
             datas = jQuery.parseJSON(data);
            var selectoption = "<option value=''>Choose option</option>";
            $('.cityId').html(selectoption);
            for (var i = 0; i < datas.length; i++) {
               var option = '<option value="' + datas[i]['id'] + '">' + datas[i]['name'] + '</option>';
               $('.cityId').append(option);
            }
           }
        });
    });
</script>
    


   