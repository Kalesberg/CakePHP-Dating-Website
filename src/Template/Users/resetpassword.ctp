<body>
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
    <section class="inner_page_header">
        <h2>Reset password</h2>
    </section>
    <section class="dashboard_content">
       <!-- <div class="dashboard_left_box">
   <div class="dashboard_menu">
    <ul>
	 <li><a href="javascript:;">Dashboard</a></li>
	 <li><a href="javascript:;">Manage Profile</a></li>
	 <li><a href="javascript:;">THINGS TO DO</a>
	  <ul>
	   <li><a href="javascript:;">Send New Custom Survey</a></li>
	   <li><a href="javascript:;">Send Ice-Breaker Survey</a></li>
	   <li><a href="javascript:;">Send Easy-Match Survey</a></li>
	   <li><a href="javascript:;">Random Challenge Survey</a></li>
	  </ul>
	 </li>
	 <li><a href="javascript:;">MY STORAGE</a></li>
	 <li><a href="javascript:;">My Account</a></li>
	 <li><a href="javascript:;">payment history</a></li>
	 <li class="active"><a href="javascript:;">Change password</a></li>
	 <li><a href="javascript:;">Membership Level </a></li>
	 <li><a href="javascript:;">Log Out</a></li>
	</ul>
   </div>
  </div>-->
  
  <!--<div class="dashboard_right_box">-->
        <div class="dashboard_form_box">
            <div class="dashboard_form_inner">
                <div class="dashboard_form_inner_sub">
                   <?php //echo $this->Flash->render() ?>
                   <?php echo $this->Form->create($post); ?>
                    <div class="form_fild_box">
                        <?php echo $this->Form->input('id',['type'=>'hidden','value'=>$post->id]); ?>                            
                        <?php //echo $this->Form->input('oldpassword',['type'=>'password','placeholder'=>'Old Password','required'=>'required','label'=>false]); ?>           
                    </div>
                    <div class="form_fild_box">
                          <label>New Password</label>
                        <?php echo $this->Form->input('newpassword',['type'=>'password','placeholder'=>'New Password','required'=>'required','label'=>false]); ?>           
                    </div>
                    <div class="form_fild_box">
                        <label>Confirm Password</label>
                        <?php echo $this->Form->input('cpassword',['type'=>'password','placeholder'=>'Confirm New Password','required'=>'required','label'=>false]); ?>           
                    </div>
                    <div class="form_submit_button">
                        <input type="submit" value="Submit"/>
                    </div>
                <?php  echo $this->Form->end(); ?>
                </div>	
            </div>
   <!--</div>-->
  </div>
 </section>
 
</div>
    <?php echo $this->element('home_footer'); ?>
</div>
</div>
</body>
</html> 