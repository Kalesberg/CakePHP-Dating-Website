<body>
    <div class="site_content">
        <!--header-content-here-->
        <?php echo $this->element('home_header'); ?>
        <!--header-content-end-->
    
        <div class="warper">
            <section class="inner_page_header">
            <h2>Manage profile</h2>
            </section>
    
            <section class="forgot_password_content"s>
                <!--<div class="dashboard_left_box">
                <?php // echo $this->element('dashboard_menu'); ?>
                </div>-->
            <?php echo $this->Form->create($post,array('enctype'=>'multipart/form-data')); ?>
                
                    <div class="dashboard_form_box">
                        <div class="manage_profile_user">
                            <div class="manage_profile_inner">
                                <div class="manage_profile_image">
                                    <?php if(!empty($post->profile_photo)){ ?>
                                        <a href="javascript:;"><?php echo $this->Html->image("user_images/".$post->profile_photo,array('id'=>'photo')); ?>
                                        <?php echo $this->Form->input('photo',['type'=>'hidden','value'=>$post->profile_photo]); ?></a>
                                    <?php  }else{ ?>
                                            <a href="javascript:;"><?php echo $this->Html->image('user_default.jpeg',array('id'=>'photo'));
                                        ?></a>
                                    <?php  } ?>
                            </div> 
                            <div class="manage_profile_edit">
                                <a href="javascript:;"> <label for="photo1"><?php echo $this->html->image('edit_icon.png'); ?></label></a>
                            </div>
                        </div>
                    </div>
                    <div class="dashboard_form_inner">
                        <div class="dashboard_form_inner_sub">
                            <?php echo $this->Flash->render(); ?>
                                <div class="contact_info_head">
                                    <h2>My Profile</h2>
                                </div>
                                <div class="form_fild_box">
                                     <label>First Name*</label>
                                    <input type="hidden" name="id"  value="<?php echo $post->id; ?>"/>
                                    <input type='file' id="photo1" onchange="readURL(this);" value="<?php echo $post->profile_photo; ?>" name="profile_photo" style="display: none;"/>
                                    <?php echo $this->Form->input('first_name',['value'=>$post->first_name,'placeholder'=>'First Name','label'=>false]); ?>
                                </div>
                                <div class="form_fild_box">
                                     <label>Last Name*</label>
                                    <?php echo $this->Form->input('last_name',['value'=>$post->last_name,'placeholder'=>'Last Name','label'=>false]); ?>
                                </div>
                                <div class="form_fild_box">
                                    <label>Company*</label>
                                    <?php echo $this->Form->input('company',['value'=>$post->company,'placeholder'=>'Company Name','required'=>'required','label'=>false,'type'=>'text']); ?>
                                </div>
                                <div class="form_fild_box">
                                    <label>Website*</label>
                                    <?php echo $this->Form->input('website',['value'=>$post->website,'placeholder'=>'Website Name','required'=>'required','label'=>false,'type'=>'text']); ?>
                                </div>
                                <div class="form_fild_box">
                                     <label>Phone</label>
                                    <?php echo $this->Form->input('phone',['value'=>$post->phone,'placeholder'=>'Phone','label'=>false,'minlength'=>10,'maxlength'=>12]); ?>
                                </div>
								<div class="form_fild_box">
                                     <label>Email*</label>
                                     <?php echo $this->Form->input('email',['value'=>$post->email,'placeholder'=>'Email','label'=>false]); ?>
                                </div>
                                <div class="form_fild_box">
                                     <label>Username*</label>
                                     <?php echo $this->Form->input('username',['value'=>$post->username,'placeholder'=>'User Name','required'=>'required','label'=>false]); ?>
                                </div>
                                <div class="form_fild_box">
                                     <label>Paypal Email ID*</label>
                                     <?php echo $this->Form->input('paypalemail',['value'=>$post->paypalemail,'placeholder'=>'Paypal Email ID','required'=>'required','label'=>false]); ?>
                                </div>
                                <div class="form_fild_box">
                                    <label>Address 1*</label>
                                    <?php echo $this->Form->input('address1',['value'=>$post->address1,'placeholder'=>'Address 1','required'=>'required','label'=>false,'type'=>'text']); ?>
                                </div>
                                <div class="form_fild_box">
                                    <label>Address 2</label>
                                    <?php echo $this->Form->input('address2',['value'=>$post->address2,'placeholder'=>'Address 2','label'=>false,'type'=>'text']); ?>
                                </div>    
                                <div class="form_fild_box">
                                    <label>Country*</label>
                                    <?php echo $this->Form->select('country',$countries,['default'=>$post->country,'placeholder'=>'Country','required'=>'required','label'=>false,'class'=>'countryId']); ?>
                                </div>
								<div class="form_fild_box">
                                    <label>State/Region*</label>	
                                    <?php
                                     echo  $this->Form->select('region',$states,['default'=>$post->region,'placeholder'=>'Region','required'=>'required','label'=>false,'class'=>'stateId']);  ?>
                                </div>
                                <div class="form_fild_box">
                                    <label>City*</label>
                                    <?php
                                      // pr($post->city);
                                      // pr($cities);
                                       
                                        echo $this->Form->select('city',$cities,['default'=>$post->city,'placeholder'=>'City','required'=>'required','label'=>false,'class'=>'cityId' ]);  ?>
                                </div>
                                <div class="form_fild_box">
                                     <label>Zip/Postal code*</label>
                                    <?php echo $this->Form->input('zip_code',['value'=>$post->zip_code,'placeholder'=>'Zip/Postal code','required'=>'required','label'=>false ]); ?>
                                </div>
                                <div class="form_fild_box">
                                    <label>Type of Business</label>
                                    <?php
                                    $type=['1'=>'online dating site','2'=>'online dating agency/services','3'=>'blog','4'=>'other'];
                                    echo $this->Form->select('business_type',$type,['default'=>$post->business_type,'id'=>'datingsite']);  ?>
                                </div>
                               
                                <div class="form_submit_button">
                                    <input type="submit" value="Update Profile"/>
                                </div>
                            <?php echo $this->Form->end(); ?>
                        </div>	
                    </div>
                
            </section>
        </div>
    </div>
    <?php echo $this->element('home_footer'); ?>
    <script type="text/javascript">
     function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#photo')
                            .attr('src', e.target.result)
                            .width('100%')
                            .height('100%');
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
</div>
</body>
</html>