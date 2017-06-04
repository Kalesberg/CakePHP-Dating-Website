<body>
<div class="site_content">
<?php echo $this->element('home_header'); ?>
<div class="warper">
 <section class="inner_page_header">
  <h2>Change password</h2>
 </section>
 
 <section class="dashboard_content">
  <div class="dashboard_left_box">
   <?php echo $this->element('dashboard_menu'); ?>
  </div>
  
  <div class="dashboard_right_box">
   <div class="dashboard_form_box">
    <div class="dashboard_form_inner">
     <div class="dashboard_form_inner_sub">
        <?php echo $this->Flash->render(); ?>
        <?php echo $this->Form->create('User',array('contoller'=>'Pages','action'=>'changepassword')); ?>
        <div class="form_fild_box">
         <input type="password" name="oldpassword" value="" placeholder="Old Password"/>
        </div>
        
        <div class="form_fild_box">
          <?php echo $this->Form->input('email',['class'=>'form-control','type'=>'hidden','value'=>$email]); ?>
          <input type="password" name="newpassword" value="" placeholder="New Password"/>
        </div>
        
        <div class="form_fild_box">
         <input type="password" name="cpassword" value="" placeholder="Confirm New Password"/>
        </div>
        
        <div class="form_submit_button">
         <input type="submit" value="Submit"/>
        </div>
	 
	 </div>	
	</div>
   </div>
  </div>
 </section>
 
</div>

<?php echo $this->element('home_footer'); ?>
</div>
</body>
</html>