<?php
use Cake\View\Helper\SessionHelper;
?>
<body>
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
            <h2>User login</h2>
        </section>
        <section class="forgot_password_content">
            <div class="dashboard_form_box">
                <div class="dashboard_form_inner">
                    <div class="dashboard_form_inner_sub">
                        <?php echo $this->Flash->render() ?>
                        <?php
                            if( $session->read('blocked')){ ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <span class="entypo-thumbs-down"></span>
                                    <strong></strong>This email id has been blocked.Please <?php echo $this->Html->Link("Reset Password",
                                    array("controller"=>"Users","action"=>"forgotpassword")
                                    ); ?> your password.
                                </div>
                            <?php } ?>
                        <?php
			echo $this->Form->create('User');
			if($process){ $this->Global->process($process); }
			?>
                        <div class="form_fild_box">
                            <label>Username or Email</label>
                            <?php //echo $this->Form->input("email",['value'=>isset($cookieData['email'])?$cookieData['email']:'','placeholder'=>'User Name','label'=>false]); ?>
                            <input type="text" id="inputUsernameEmail"  placeholder="Username or Email" name="email" value='<?php  echo isset($cookieData['email'])?$cookieData['email']:''; ?>' required>
                            
                        </div>
                        <div class="form_fild_box">
                            <label>Password</label>
                            <?php //echo $this->Form->input("password",['value'=>isset($cookieData['password'])?$cookieData['password']:'','placeholder'=>'Password','label'=>false]); ?>
                            <input type="password" placeholder="Password" name="password" id="inputPassword" value="<?php  echo isset($cookieData['password'])?base64_decode($cookieData['password']):''; ?>" required>
                        </div>
                        <div class="forgot_box">
                            <?php
                           
                                echo $this->Html->Link("Forgot password?",
                                array("controller"=>"Users","action"=>"forgotpassword")
                               ); 
                         ?>
                           <input type="checkbox" name="remember_me" <?php echo isset($cookieData['email'])?'checked':''; ?>>&nbsp;Remember me
                           <!-- <a href="javascript:;">Forgot Password</a>-->
                           <input type="hidden" value="0" id="surveytype" name="surveytype"/>
                        </div>
                        

                        <div class="form_submit_button">
                            <input type="submit" value="log in"/>
                          
                        </div>
                        <div class="form_foot">
                            <p>Not registered? <?php echo $this->Html->link("Register now!",['controller'=>'freetrial','action'=>'freeregister']); ?></p>
                        </div>
						<!--<div class="simple_advanced_button">
						 <ul>
						  <li><input type="button" value="As simple survey" class="simple"/></li>
						  <li><input type="button" value="As advanced survey" class="advanced"/></li>
						 </ul>
						</div>-->
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
 </script>
   </div>
</body>
</html> 
