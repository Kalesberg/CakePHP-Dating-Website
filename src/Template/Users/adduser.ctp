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
                <li><a href="#" title="Sample page 1">Add </a>
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
                <?php echo $this->Flash->render(); ?>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="nest" id="validationClose">
                            <div class="title-alt">
                                <h6>
                                    Add User</h6>
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
                                    <?php echo $this->Form->Create($post,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                                  <!--<form action="adduser" id="contact-form" class="form-horizontal" method="post" enctype="multipart/form-data"> -->
                                        <fieldset>
                                            <div class="text-center">
                                                <?php echo $this->html->image('user_default.jpeg',['id'=>'photo','class'=>'avatar img-circle']); ?>
                                                <!--<img src="#" class="avatar img-circle" alt="avatar" id="photo">-->
                                                <h6>Upload a different photo...</h6>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <span class="btn btn-primary btn-file">
                                                            Browse
                                                            <input type="file" name="profile_photo" onchange="readURL(this);" value="" id="photo" />
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">First Name*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('first_name',['class'=>'form-control','required'=>'required','label'=>false]); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Last Name*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('last_name',['class'=>'form-control','required'=>'required','label'=>false]); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Email Address*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('email',['class'=>'form-control','type'=>'email','required'=>'required','label'=>false]); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Country Code</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->select('countrycode',$countrycode,['placeholder'=>'Country Code','class'=>'form-control','label'=>false]); ?>
                                                </div>
                                                <label class="control-label" for="email">Phone</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('phone',['class'=>'form-control','label'=>false,'minlength'=>4]); ?>
                                                    
                                                </div>
                                            </div>
											<div class="control-group">
                                                <label class="control-label" for="email">Country*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->select('country',$countries,['class'=>'form-control countryId','required'=>'required','label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
											 <div class="control-group">
                                                <label class="control-label" for="email">State/Region*</label>
                                                <div class="controls">
                                                      <?php  echo $this->Form->select('region',$states,['class'=>'form-control stateId','required'=>'required','label'=>false]); ?>
                                                  
                                                </div>
                                            </div>
											   <div class="control-group">
													<label class="control-label" for="email">City*</label>
													<div class="controls">
														<?php $cities=[]; echo $this->Form->select('city',$cities,['class'=>'form-control cityId','required'=>'required','label'=>false]); ?>

													</div>
												</div>

                                            <div class="control-group">
                                                <label class="control-label" for="email">Age*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('age',['class'=>'form-control','required'=>'required','label'=>false,'min'=>'0','max'=>'100']); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Gender*</label></br>
                                                <div class="controls">
                                                <?php
                                                $option = ['1'=>'Male','2'=>'Female'];
                                                echo $this->Form->radio('gender',$option,['class'=>'form-control','type'=>'radio','required'=>'required']); ?>
                                                        
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Relationship Status*</label>
                                                <div class="controls">
                                                    <ul class="radio-buttons">
                                                        <li>
                                                            <?php $option=array(['value'=>'1','text'=>'I have a prospective partner.'],['value'=>'2','text'=>'I have a current partner.']);
                                                        echo $this->Form->radio('relationship_status',$option,
                                                        ['class'=>'form-control','required'=>'required','legend'=>false,'separator'=>'</li><li>']); ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Membership Level*</label>
                                                <div class="controls">
                                                    <ul class="radio-buttons">
                                                        <li>
                                                             <?php echo $this->Form->radio('membership_level',$membership,['legend'=>false,'separator'=>'</li><li>','required'=>'required']); ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">Are you a member of a dating site? If, 'yes' please check all that apply :</label>
                                                <div class="controls">
                                                <?php
                                                    echo $this->Form->select('datingsites', $sites,['style'=>'width:100%;','id'=>'datingsite','multiple'=>'multiple','required'=>'required']);
                                                ?>
                                                </div>
                                            </div>
                                            <div class="control-group"  id="other" style="display: none;">
                                                <label class="control-label">Other</label>
                                                <div class="controls" >
                                                    <?php echo $this->Form->input('other',['class'=>'form-control','label'=>false]); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Username*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('username',['class'=>'form-control','required'=>'required','label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Password*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('password',['class'=>'form-control','required'=>'required','type'=>'password','label'=>false]); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Confirm Password*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('cpassword',['value'=>'','class'=>'form-control','required'=>'required','type'=>'password','label'=>false]); ?>
                                                </div>
                                            </div>
                                           <!-- <div class="control-group">
                                                
                                                <div class="controls">
                                                       <input type="checkbox" name="tc" value="1" required/>
                                                </div>
                                                <label class="control-label">I accept and agree to Self-Match Terms of Use and Privacy Policy.</label>
                                            </div>-->
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Submit</button>
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
    $(function(){
        $('input').attr('required',false);
        })
    
function readURL(input) {
   // alert(input.files[0]);
   
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
//dating site code start
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
    


   