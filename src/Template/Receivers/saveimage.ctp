<body>
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
            <h2>Upload Image</h2>
        </section>
        <section class="forgot_password_content">
            <div class="dashboard_form_box">
		<div class="dashboard_form_box">
		    <div class="manage_profile_user">
			<div class="manage_profile_inner">
			    <div class="manage_profile_image">
				<?php echo $this->Form->create('Receiver',['enctype'=>'multipart/form-data']); ?>
				<?php 
					$inUser = $this->Global->findReceiverInUser($email);
					if($inUser){
						$profile=$inUser['profile_photo'];
					}else{
						$inReceiver = $this->Global->getReceiverDetails($email);
						$profile=$inReceiver['profile_photo'];
					} ?>	
					<a href="javascript:;"><?php echo $this->Html->image('user_default.jpeg',array('id'=>'photo')); ?>
						</a>
					<?php // } ?>
			    </div> 
						<!--<div class="manage_profile_edit">
							<a href="javascript:;"> <label for="photo1"><?php //echo $this->html->image('edit_icon.png'); ?></label></a>
						</div>-->
			</div>
		    </div>
                <div class="dashboard_form_inner">
                    <div class="dashboard_form_inner_sub">
                        <?php //echo $this->Flash->render() ?>
                       

                        <div class="form_fild_box">
                            <label>Insert your photo in the survey</label>
                            <input type="hidden" value="<?php echo $survey_id; ?>" name="survey_id">
							
                            <input type="file" id="photo1" onchange="readURL(this);" style="padding: 0px;" value="<?php echo $profile; ?>" name="profile_photo" required/>
                            <?php //echo $this->Form->input('image',['type'=>'file'],['placeholder'=>'Choose image','required'=>'required','label'=>false]); ?>
                            
                        </div>
						<div class="form_submit_button terms_check">
                            <input type="submit" value="Save Image"/>
                            <label>
                            <?php echo $this->html->link("Skip",['controller'=>'Receivers','action' =>'survey',base64_encode($survey_id),base64_encode($usertype),base64_encode($email),base64_encode('flag')]); ?></label>
                        </div>
                        <?php echo $this->Form->end(); ?>

                    </div>	
                </div>
            </div>
        </section>
    </div>
<?php echo $this->element('home_footer'); ?>
</div>
 <script>
   $(function(){
      $('.simple').click(function(){
        $('#surveytype').val('1');
    });
    $('.advanced').click(function(){
        $('#surveytype').val('2');
    }); 
	   
});
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
 </script>
   </div>
</body>
</html> 
