<?php use Cake\ORM\TableRegistry;
  // $user = $this->request->session()->read('Auth.User');
   ?> 
<body>
   <div class="site_content">
      <?php echo $this->element('home_header'); ?>
      <div class="warper">
         <section class="inner_page_header">
            <h2>Survey</h2>
         </section>
         <section class="question_detail_content">
            <div class="container">     
                <div class="sender_receiver_user" style="margin-top: 53px;">
                <?php
              //  echo $email;die;
               // $inUser = $this->Global->findReceiverInUser($email);
               // if($inUser){
                //    $profile=$inUser['profile_photo'];
               // }else{
                    $inReceiver = $this->Global->getUserofReceiver($email,$survey_id);
                    $profile=$inReceiver['profile_photo'];
                //}
                //pr($inReceiver);die;
                if(!empty($profile)){
                    ?>
                   <a href="javascript:;"> <?php echo $this->Html->image("user_images/".$profile,['style'=>'width:100%;height:100%;']); ?></a>
                 <?php }else{ ?>
                     <a href="javascript:;"> <?php echo $this->Html->image('user_default.jpeg'); ?></a>
                 <?php } ?>
                 
                </div>
                   
               <div class="question_detail_head">
               </div>
               <?php echo $this->Form->create($post,array('controller'=>'Receivers','action'=>'sendsurvey','class'=>'mainForm')); ?>
               <div class="career_finances_list">
                  <?php echo $this->Flash->render(); ?>
                    <div class="alert alert-danger error"  style="display: none">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <span class="entypo-thumbs-down"></span>
                        <strong></strong>&nbsp;&nbsp;Please answer all the questions before save this survey.
                    </div>
                  <?php $i=1;
                     $questions=$post['questions_id'];
                     $questions=unserialize($questions);
                     
                     foreach($questions as $q){
                         $quest= $this->Global->getQuestionsNew($q);
                     ?>
                  <div class ="career_finances_row quest<?php echo $q; ?>">
                     <div class="career_finances_head">
                      
                        <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>" />
                        <input type="hidden" name="usertype" value="<?php echo $usertype; ?>" />
                        <input type="hidden" name="receiver_email" value="<?php echo $email; ?>" />
                        <input type="hidden" name="category_id" value="<?php echo $post['category_id']; ?>" />
                        <input type="hidden" name="question_id[]" id="qid" value="<?php echo $q; ?>" >
                        <?php if(empty($usertype)){ ?>
                        <input class="inputcheck"  type="checkbox" value="<?php echo $q; ?>" name="question_id[]" id="question<?php echo $q; ?>"/>
                        <?php  } ?>
                        <input type="hidden" class="checkVal" value="">
                        <h2><?php echo $i.".".$quest['question_text']; ?></h2>
                        <?php //echo $this->Html->image('red_star01.png'); ?>
                     </div>
                     <div class="finances_list">
                        <ul style="list-style: none;">
                           <li><input type="radio" value="A" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;A.&nbsp;&nbsp;<?php echo $quest['option_1']; ?></li>
                           <li><input type="radio" value="B" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;B.&nbsp;&nbsp;<?php echo $quest['option_2']; ?></li>
                           <?php if(!empty($quest['option_3'])){ ?>
                           <li><input type="radio" value="C" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;C.&nbsp;&nbsp;<?php echo $quest['option_3']; ?></li>
                           <?php }if(!empty($quest['option_4'])){ ?>
                           <li><input type="radio" value="D" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;D.&nbsp;&nbsp;<?php echo $quest['option_4']; ?></li>
                           <?php } ?>
                        </ul>
                     </div>
                  </div>
                  <?php $i++; } ?>
               </div>
               <div class="add_survey_button">
                   <!--<a type="button" style="text-decoration: none;" class="formSubmit">Submit survey</a>-->
                  <input type="submit"  value="Submit survey" />
               </div>
               <?php echo $this->Form->end(); ?>
            </div>
         </section>
      </div>
      <?php echo $this->element('home_footer'); ?>
   </div>
   <script type="text/javascript">

	$('.formSubmit').click(function(){
        //alert();
        var nextProcess  = 0;
     $(".qustionAns").each(function() {
		    if ($(this).is(':checked')) {
			nextProcess = parseInt(nextProcess) + parseInt(1);
		    }
		});
		var totalQus = '<?php echo count($questions); ?>';
		if(nextProcess ==  totalQus){
		    $('.mainForm').submit();
		}else{
		    $('.error').show();
            return false;
            //$('.add').hide();
            //$('.addFav').hide();
           // $('.remove').hide();
        }
        
      });
	   
   </script>
</body>
</html>

