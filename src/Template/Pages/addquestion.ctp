<body>
    <div class="site_content">
    <!--header-content-here-->
    <?php echo $this->element('home_header'); ?>
    <?php  $user = $this->request->session()->read('Auth.User'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>Contribute a Question to Question Bank</h2>
            </section>
            <section class="forgot_password_content">
                <div class="dashboard_form_box">
                    <div class="dashboard_form_inner">
                        <div class="dashboard_form_inner_sub">
                        <?php echo $this->Form->Create($post); ?>
                        <?php //echo $this->Flash->render(); ?>
                           <!-- <div class="form_fild_box">
                                <?php  $users=$this->Global->getUser($user['id']); ?>
                                <label>User Name:</label>
                                <?php //echo $this->Form->input("username",['value'=>$users['username'],'placeholder'=>'User Name','label'=>false,'disabled'=>true]); ?>
                            </div>-->
                            
                            <div class="form_fild_box">
                                <label>Name:</label>
                                <?php $name=ucwords($users['first_name'])." ".ucwords($users['last_name']);
                                echo $this->Form->input("name",['value'=>!empty($name)?trim($name):"",'placeholder'=>'Name','label'=>false]); ?>
                            </div>
                            
                            <div class="form_fild_box">
                                <label>Email Address:</label>
                                <?php echo $this->Form->input("email",['value'=>$users['email'],'placeholder'=>'Email Address','label'=>false]); ?>
                            </div>
                            
                            <!--<div class="form_fild_box">
                                <label>Would you like us to display your name ?</label>
                                <?php
                                  //  $options=array(['value'=>'1','text'=>'Yes'],['value'=>'2','text'=>'No']);
                                 //echo $this->Form->select('displayname', $options);  ?>
                            </div>-->
                            <div class="form_fild_box">
                                 <label>Question Category</label>
                                <?php
                                echo $this->Form->select('category_id', $category,['label'=>false]);  ?>
                               
                            </div>
                            
                            <div class="form_fild_box">
                                <label>Body of the question</label>
                                <?php echo $this->Form->input('question_text',array('placeholder'=>'Body of the question','label'=>false)); ?>
                            </div>
                            
                            <div class="form_fild_box">
                                <?php echo $this->Form->input('option_1',array('label'=>false,'placeholder'=>'Answer choice A' )); ?>
                           
                            </div>
                            
                            <div class="form_fild_box">
                                 <?php echo $this->Form->input('option_2',array('label'=>false,'placeholder'=>'Answer choice B' )); ?>
                                    
                            </div>
                            
                            <div class="form_fild_box">
                                 <?php echo $this->Form->input('option_3',array('label'=>false,'placeholder'=>'Answer choice C' )); ?>
                           
                            </div>
                            <div class="form_fild_box">
                                 <?php echo $this->Form->input('option_4',array('label'=>false,'placeholder'=>'Answer choice D' )); ?>
                            </div>
                            
                            <div class="form_submit_button terms_check ">
                                <input type="submit" value="submit"/>
								<label><?php echo $this->Html->link("General Guidelines for Question Submission",['controller'=>'Pages','action'=>'guideline']); ?></label>
                            </div> 
                        <?php echo $this->Form->end(); ?>
                        </div>	
                    </div>
                </div>
            </section>
        </div>
        <?php echo $this->element('home_footer'); ?>
    </div>
</body>
</html>