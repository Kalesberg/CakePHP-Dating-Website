<body>
    <div class="site_content">
    <!--header-content-here-->
 <style>
    .manage_profile_edit {
    bottom: 0;
    left: 132px;
    position: absolute;
    top: 149px;
    width: 50px;
    z-index: 99;
}
 </style>   
    <?php $user = $this->request->session()->read('Auth.User');
    echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
            <h2>Submission Form</h2>
            </section>
            <section class="forgot_password_content">
                    <div class="dashboard_form_box">
                        <?php
                        $users=$this->Global->getUser($user['id']);
                        $membership=$this->Global->getMembership($users['membership_level']);
                         //pr($membership['slug']);die;
                        if($membership['slug']=='visitor'){
                           
                            //pr($users['survey_type']);die;
                        ?>
                            <div class="manage_profile_user">
                               <div class="manage_profile_inner">
                                   <div class="manage_profile_image"> 
                                       <a href="javascript:;"><?php echo $this->Html->image('user_default.jpeg',array('id'=>'photo')); ?></a>
                                   </div>
                                   <div class="manage_profile_edit">
                                       <a href="javascript:;"><label for="photo1"><?php echo $this->html->image('edit_icon.png'); ?></label></a>
                                   </div>
                               </div>
                           </div>
                        <?php   } ?>
                    <div class="dashboard_form_inner">
                        <div class="dashboard_form_inner_sub">
                            <?php echo $this->Form->create($post,['enctype'=>'multipart/form-data']); ?>
                            <?php echo $this->Flash->render(); ?>
                            <div class="form_fild_box">
                               
                                <input type='file' id="photo1" onchange="readURL(this);" value="" name="profile_photo" style="display: none;"/>
                                <?php echo $this->Form->input('name',['placeholder'=>"Receiver’s Name",'label'=>false]); ?>
                            </div>
                            <div class="form_fild_box">
                                <?php echo $this->Form->input('email',['placeholder'=>"Receiver’s Email Address",'label'=>false,'class'=>'emailbox tin']); ?>
                                <div class="email_note_box" style="display: none">
                                  <p>Please enter the correct email address. Self-Match is not responsible if the survey is sent to a wrong or inactive email address, if the partner does not open the Self-Match notification email, or if the partner refuses to take the survey. NO REFUNDS WILL BE ISSUED.</p>
                                </div>
                            </div>
                            
                            <span style="bottom: 11px;position: relative;">OR</span>
                            
                          <div class="form_fild_box">
                                <div class="form-new">
                                    <?php echo $this->Form->select('countrycode',$countrycode,['label'=>false]); ?>
                                </div>
                                <div class="form-new2">
                                    <?php echo $this->Form->input('phone',['placeholder'=>'Phone Number','label'=>false,'minlength'=>10,'maxlength'=>12,'class'=>'tin']); ?>
                                </div>
                            </div>
                           
                           <!-- <div class="form_fild_box">
                                <?php //echo $this->Form->input('occupation',['placeholder'=>"Occupation",'label'=>false,'type'=>'text']); ?>
                            </div>-->
                            <div class="form_fild_box">
                                <?php echo $this->Form->input('message',['type'=>'textarea','label'=>false,'placeholder'=>'Message to the Receiver']); ?>
                            </div>
                            <div class="form_submit_button">
                                <input type="submit" value="Send survey to your partner"/>
                            </div>
                        </div>	
                    </div>
            </section>
        </div>
        <?php echo $this->element('home_footer'); ?>
    </div>
        <script>
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
            
        
            $(document).ready(function(){
                $(".emailbox").focus(function(){
                    $(".email_note_box").css("display", "block");
                });
                $(".emailbox").focusout(function(){
                    $(".email_note_box").css("display", "none");
                });
            });
        </script>
        
        
        <?php //echo $this->Html->script(['minjsforvalidate']); ?>
<?php //echo $this->Html->script(['additi']); ?>

<?php //echo $this->Html->css(['addscriptcss']); ?>
<!--<script>
// just for the demos, avoids form submit




$(document).ready(function () {

    $('#myform').validate({ // initialize the plugin
        groups: {
            names: "email phone"
        },
        rules: {
            phone: {
                require_from_group: [1, ".tin"]
            },
            email: {
                require_from_group: [1, ".tin"]
            }
        },
       
    });

    jQuery.extend(jQuery.validator.messages, {
        require_from_group: jQuery.format("'Please enter either phone/ email address")
    });

});




</script>-->
        
     
</body>
</html>