<body>
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
         <h2>Forgot Password</h2>
        </section>
        <section class="forgot_password_content">
            <div class="dashboard_form_box">
                <div class="dashboard_form_inner">
                    <div class="dashboard_form_inner_sub">
                        <?php echo $this->Flash->render(); ?>
                        <?php echo $this->Form->create('Affiliates', array('action' => 'forgotpassword')); ?>
                        <div class="form_fild_box">
                            <?php echo $this->Form->input('email',['placeholder'=>'Email ID','required'=>'required','label'=>false]); ?>     
                        </div>
                        <div class="form_submit_button">
                            <input type="submit" value="Submit"/>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>	
                </div>
            </div>
        </section>
    </div>
    <?php echo $this->element('home_footer'); ?>
</div>
</div>
</body>
</html> 