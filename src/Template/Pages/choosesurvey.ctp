<body>
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
            <h2>Choose Survey</h2>
        </section>
        <section class="forgot_password_content">
            <div class="dashboard_form_box">
                <div class="dashboard_form_inner">
                    <div class="dashboard_form_inner_sub">
                        <?php echo $this->Flash->render() ?>
                        <?php echo $this->Form->create('User'); ?>
						<div class="simple_advanced_button">
						 <ul>
						  <li><?php echo $this->Html->link('A simple survey',['action'=>'choosesurvey',$id=base64_encode('1')],['class'=>'simple']); ?></li>
						  <li style="width:40%"><?php echo $this->Html->link('An advanced survey',['action'=>'choosesurvey',$id=base64_encode('2')],['class'=>'advanced']); ?></li>
						 </ul>
                         <div style="margin-top: 90px;" >
                            <h2> Upgrade Your Plan </h2>
                            <ul style="margin-top: 10px; width: 100%;">
                              <li><?php echo $this->Html->link('Platinum',['controller'=>'Pages','action'=>'choosepaymenttype',base64_encode("platinum"),base64_encode("0"),base64_encode("upgrade")],['class'=>'simple','escape'=>false]); ?></li>
                              <li><?php echo $this->Html->link('Lifetime',['controller'=>'Pages','action'=>'choosepaymenttype',base64_encode("lifetime"),base64_encode("0"),base64_encode("upgrade")],['class'=>'advanced','escape'=>false]); ?></li>
                            </ul>   
                         </div>
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
 </script>
   </div>
</body>
</html> 
