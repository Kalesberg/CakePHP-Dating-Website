<body>
    <div class="site_content">
        <!--header-content-here-->
        <?php echo $this->element('home_header'); ?>
        <!--header-content-end-->
    
        <div class="warper">
            <section class="inner_page_header">
            <h2>Manage Partner's Profile</h2>
            </section>
    
            <section class="dashboard_content">
                <div class="dashboard_left_box">
                <?php echo $this->element('dashboard_menu'); ?>
                </div>
            <?php echo $this->Form->create($post,array('enctype'=>'multipart/form-data')); ?>
                <div class="dashboard_right_box">
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
                                    <h2>Profile</h2>
                                </div>
                                <div class="form_fild_box">
                                    <label>Name*</label>
                                   <!-- <input type="hidden" name="id"  value="<?php //echo $post->id; ?>"/>-->
                                    <input type='file' id="photo1" onchange="readURL(this);" value="<?php echo "user_images/".$post->profile_photo; ?>" name="profile_photo" style="display: none;" />
                                   <input type="text" value='<?php echo $post->name; ?>' name="name" placeholder='Name' required="required"/>
                                 
                                </div>
                                <div class="form_fild_box">
                                     <label>Email*</label>
                                     <input type="email" value="<?php echo $post->email ?>" disabled/>
                                     <?php //echo $this->Form->input('email',['value'=>$post->email,'placeholder'=>'Email','label'=>false]); ?>
                                </div>
                                <div class="form_fild_box">
                                     <label>Age</label>
                                     <?php echo $this->Form->input('age',['value'=>$post->age,'placeholder'=>'Age','label'=>false,'min'=>'1','max'=>'100']); ?>
                                </div>
                                <div class="form_fild_box">
                                     <label>Occupation</label>
                                     <?php echo $this->Form->input('occupation',['value'=>$post->occupation,'placeholder'=>'Occupation','label'=>false,'type'=>'text']); ?>
                                </div>
							 	<div class="form_fild_box">
                                    <label>Country</label>
                                    <?php echo $this->Form->select('country',$countries,['default'=>$post->country,'placeholder'=>'Country','label'=>false,'class'=>'countryId']); ?>
                                </div>
								<div class="form_fild_box">
                                    <label>State/Region</label>
									<?php
                                  
                                       echo  $this->Form->select('region',$states,['default'=>$post->state,'placeholder'=>'Region','label'=>false,'class'=>'stateId']);  ?>
                                  
                                </div>
                                <div class="form_fild_box">
                                     <label>City</label>
                                    <?php
                                    //pr($cities);die;
                                    if(!empty($post->city)) {
										echo $this->Form->select('city',$cities,['default'=>$post->city,'placeholder'=>'City','label'=>false,'class'=>'cityId' ]);  
                                    }else{
                                        	echo $this->Form->select('city',$cities,['default'=>$cities,'placeholder'=>'City','label'=>false,'class'=>'cityId' ]);  
                                    } ?>

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
	    $('.cityId').html(selectoption);
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