<?php //  $$ = $this->request->session()->read('Auth.User'); ?>
<?php echo $this->element('admin_header'); ?>
<?php echo $this->element('admin_sidebar'); ?>
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
<li><a href="javascript:;" title="Sample page 1">Edit Profile</a>
</li>
<!-- <li class="pull-right">
    <div class="input-group input-widget">

        <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">
    </div>
</li>-->
</ul>
<!-- END OF BREADCRUMB -->
<?php
foreach($post as $val){
    $user_id    =$val->id; 
    $first_name =$val->first_name;
    $last_name  =$val->last_name;
    $email      =$val->email;
    $contact    =$val->phone;
    $city       =$val->city;
    $country    =$val->country;
    $role       =$val->role;
    $gender     =$val->gender;
    $age        =$val->age;
    $relationship_status=$val->relationship_status;
    $membership=$val->membership;
    $user_name=$val->username;
    $profile_photo=$val->profile_photo;
    $password=base64_decode($val->password);
}
?>
<div class="content-wrap">
<?php echo $this->Flash->render(); ?>
<div class="row">
    <div class="col-sm-12">
        <!-- BLANK PAGE-->
        <div style="margin:-20px 15px;" class="nest" id="Blank_PageClose">
            <div class="title-alt">
                <h6>
                    Edit Profile</h6>
                <!--<div class="titleClose">
                    <a class="gone" href="#Blank_PageClose">
                        <span class="entypo-cancel"></span>
                    </a>
                </div>
                <div class="titleToggle">
                    <a class="nav-toggle-alt" href="#Blank_Page_Content">
                        <span class="entypo-up-open"></span>
                    </a>
                </div>-->
            </div>
            
                <div class="body-nest" id="Blank_Page_Content">
                    <div class="row">
                    <!-- left column -->
                    <?php echo $this->Form->Create($post,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                  
                        <div class="col-md-3">
                            <div class="text-center">
                                <?php if(isset($profile_photo)){ 
                                //$filepath ="http://localhost/self-match/" . '/img/' . $profile_photo; ?>
                                <?php echo $this->Html->image("user_images/".$profile_photo,array('class'=>'img-circle img-responsive img-profile','id'=>'photo')); ?>
                                    <h6>Upload a different photo...</h6>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Browse
                                                <input type="file" onchange="readURL(this);"  value="<?php echo $profile_photo; ?>" name="profile_photo" id="photo">
                                                <input type="hidden" name="photo" value="<?php echo $profile_photo; ?>" />
                                            </span>
                                        </span>
                                       
                                        <!--<input type='submit' value='Save' class="btn btn-primary"/>-->
                                    </div>
                                 <?php } else {  ?>
                                    <?php echo $this->Html->image("user_images/".$profile_photo,array('class'=>'img-circle img-responsive img-profile','id'=>'photo')); ?>
                                    <h6>Upload a different photo...</h6>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file">
                                                Browse
                                                <input type="file" onchange="readURL(this);" value="<?php echo $profile_photo; ?>" name="profile_photo" id="photo">
                                            </span>
                                        </span>
                                    </div>
                                   
                                    <!--<input type='submit' value='Save' class="btn btn-primary"/>-->
                                <?php } ?>
                            </div>
                        </div>
                        <!-- edit form column -->
                        <div class="col-md-9 personal-info">
                            <h3>Personal info</h3>
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo $user_id; ?>" />
                                    <label class="col-lg-3 control-label">First name:</label>
                                    <div class="col-lg-8">
                                         <?php echo $this->Form->input('first_name',['class'=>'form-control','value'=>$first_name,'label'=>false, 'required'=>'required']); ?>
                                
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Last name:</label>
                                    <div class="col-lg-8">
                                        <?php echo $this->Form->input('last_name',['class'=>'form-control','value'=>$last_name,'label'=>false,'required'=>'required']); ?>
                                       
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Email:</label>
                                    <div class="col-lg-8">
                                        <?php echo $this->Form->input('email',['class'=>'form-control','value'=>$email,'label'=>false,'required'=>'required']); ?>
                                      
                                    </div>
                                </div>
								<div class="form-group">
                                    <label class="col-md-3 control-label">Country:</label>
                                    <div class="col-md-8">
                                        <?php echo $this->Form->select('country',$countries,['class'=>'form-control countryId','default'=>$country,'label'=>false,'required'=>'required']); ?>
                                        
                                    </div>
                                </div>
							 	<div class="form-group">
									 <label class="col-md-3 control-label">State/Region*</label>
								 	<div class="col-md-8">
										  <?php 
											$state = $this->Global->getStateName($post[0]['region']);
											$states=[$state['name']];
											echo $this->Form->select('region',$states,['default'=>$state['name'],'class'=>'form-control stateId','label'=>false]); ?>

									</div>
								</div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">City:</label>
                                    <div class="col-md-8">
                                          <?php 
											$city = $this->Global->getStateName($city);
											$cities=[$city['name']];
											echo $this->Form->select('city',$cities,['default'=>$city['name'],'class'=>'form-control cityId','label'=>false,'required'=>'required']); ?>
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Contact:</label>
                                    <div class="col-md-8">
                                         <?php echo $this->Form->input('phone',['class'=>'form-control','value'=>$contact,'label'=>false]); ?>
                                       
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Age:</label>
                                    <div class="col-md-8">
                                         <?php echo $this->Form->input('age',['class'=>'form-control','value'=>$age,'label'=>false,'required'=>'required']); ?>
                             
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Gender:</label></br>
                                    <div class="col-md-8">
                                         <?php
                                                $option = ['1'=>'Male','2'=>'Female'];
                                                echo $this->Form->radio('gender',$option,['default'=>$post[0]['gender'],'type'=>'radio','required']);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"></label>
                                    <div class="col-md-8">
                                        <input class="btn btn-primary" value="Save Changes" type="submit" >
                                        <span></span>
                                        <?php echo $this->Html->Link("Cancel",['controller'=>'Users','action'=>'profile'],['class'=>'btn btn-default','escape'=>false]); ?>
                                        <!--<input class="btn btn-default" value="Cancel" type="reset">-->
                                    </div>
                                </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END OF BLANK PAGE -->
    </div>
</div>
<?php echo $this->element('admin_footer'); ?>
<?php  echo $this->element('admin_footer_js');  ?>
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
</body>