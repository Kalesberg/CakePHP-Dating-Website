<?php 
   $user = $this->request->session()->read('Auth.User');
   
   ?>
<style>
.add_remove_button ul li:last-child a {
    background: #333 none repeat scroll 0 0;
}
.add_remove_button ul li a{
    
     background: #3bbbeb none repeat scroll 0 0;
}
</style>
<body>
    <div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
           <h2><?php echo $post['survey_name']; ?></h2>
        </section>
        <section class="question_detail_content">
            <div class="container">
                <div class="question_detail_head">
                   <h2>
                   </h2>
                   <div class="favorite_list_button">
                      <!--<a href="javascript:;">Favorite list</a>-->
                   </div>
                </div>
                <div class="add_remove_button">
                    <ul>
                       
                        <li>
                              <a class='add' data-id='<?php echo $survey_id; ?>'>Modify</a>
                        </li>
                       <!-- <li>
                          
                            <a class='add' data-id='<?php echo $survey_id; ?>'>Add questions from Favorite List</a>
                              
                          
                        </li>
                        <li>
                        <li><a class="add" data-id='<?php echo $survey_id; ?>'>Remove Questions</a>-->
   
                        </li>
                    </ul>
                </div>
                <?php echo $this->Form->create($post,array('controller'=>'Pages','action'=>'sendsurvey','class'=>'mainForm')); ?>
                <div class="career_finances_list">
                  
                    <?php $i=1;
                        $questions=$post['question_id'];
                        $questions=unserialize($questions);
                        $answers=unserialize($post['answers']);
                        //pr($answers);die;
                        foreach($questions as $q){
                           $quest= $this->Global->getQuestionsNew($q);
                    ?>
                    <div class ="career_finances_row quest<?php echo $q; ?>">
                        <div class="career_finances_head">
                           <input type="hidden" id="user_id" name="user_id" value="<?php echo $post['user_id']; ?>"/>
                           <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>" class="survey"/>
                           <input type="hidden" name="processType" value="send" />
                          <!--<input type="hidden" name="usertype" value="<?php //echo $usertype; ?>" />
                           <input type="hidden" name="receiver_email" value="<?php //echo $email; ?>" />-->
                           <input type="hidden" name="category_id" value="<?php echo $post['category_id']; ?>" />
                           <input type="hidden" name="question_id[]" id="qid"  value="<?php echo $q; ?>" >
                            <div class="alert alert-danger error<?php echo $q; ?>" style="display: none;">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <span class="entypo-thumbs-down"></span>
                                <strong></strong>&nbsp;&nbsp;Please upgrade your membership to add question in favorite.
                            </div>
                           <input class="inputcheck"  type="checkbox" value="<?php echo $q; ?>" name="question_id[]" id="question<?php echo $q; ?>"/>
                           <input type="hidden" class="checkVal" value="">
                           <h2><?php echo $i.".".$quest['question_text']; ?></h2>
                            <?php
                            if($post['page']=="pageRand"){
                                $membership=$this->Global->getMembership($user['membership_level']);
                                if(isset($user) && $membership['slug']!='visitor'){
                                if($this->Global->getStar($user['id'],$q)){
                             ?>
                            <span id="star<?php echo $q; ?>">
                                <?php echo $this->Html->image('red_star01.png',['onclick' => "changeImage($q)"]); ?>    
                            </span>
                            <?php }else{ ?>
                            <span id="star<?php echo $q; ?>">
                                <?php echo $this->Html->image('gray_star.png',['onclick' => "changeImage($q)"]); ?>    
                            </span>
                            <?php } }else{ ?>
                            <span class="star<?php echo $q; ?>">
                                <?php echo $this->Html->image('gray_star.png',['onclick' => "showpopup($q)"]); ?>    
                            </span>
                           <?php }
                          
                           } ?>
                        </div>
                        <div class="finances_list">
                            <?php
                            if(($post['type']!=SAVED && $post['withanswer']=='2')){ ?>
                                <ul style="list-style: none;">
                                    <li><input type="radio" value="A" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;A.&nbsp;&nbsp;<?php echo $quest['option_1']; ?></li>
                                    <li><input type="radio" value="B" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;B.&nbsp;&nbsp;<?php echo $quest['option_2']; ?></li>
                                    <?php if(!empty($quest['option_3'])){ ?>
                                    <li><input type="radio" value="C" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;C.&nbsp;&nbsp;<?php echo $quest['option_3']; ?></li>
                                    <?php }if(!empty($quest['option_4'])){ ?>
                                    <li><input type="radio" value="D" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;D.&nbsp;&nbsp;<?php echo $quest['option_4']; ?></li>
                                    <?php } ?>
                                </ul>
                            <?php }else{
                               //pr($survey_id);die;
                                $answers=unserialize($post['answers']);
                                ?>
                                <ul style="list-style: none;">
                                <li><input type="radio" value="A" <?php if(!empty($answers[$q]) && $answers[$q]=='A'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;A.&nbsp;&nbsp;<?php echo $quest['option_1']; ?></li>
                                <li><input type="radio" value="B" <?php if(!empty($answers[$q]) && $answers[$q]=='B'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;B.&nbsp;&nbsp;<?php echo $quest['option_2']; ?></li>
                                <?php if(!empty($quest['option_3'])){ ?>
                                <li><input type="radio" value="C" <?php if(!empty($answers[$q]) && $answers[$q]=='C'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;C.&nbsp;&nbsp;<?php echo $quest['option_3']; ?></li>
                                <?php }if(!empty($quest['option_4'])){ ?>
                                <li><input type="radio" value="D" <?php if(!empty($answers[$q]) && $answers[$q]=='D'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;D.&nbsp;&nbsp;<?php echo $quest['option_4']; ?></li>
                                <?php } ?>
                             </ul>
                            <?php }        
                            ?>
                        </div>
                    </div>
                   <?php $i++; } ?>
                </div>
                <div class="add_survey_button">
                    <input type="submit" name="submitSurvey" class="formSubmit" data-submitType="send" value="Submit Survey" />
                </div>
                <?php echo $this->Form->end(); ?>
            </div>
        </section>
      <!-- Small modal -->
     
      <?php echo $this->element('home_footer'); ?>
   </div>
   <script type="text/javascript">
   $(".add").click(function(){
    var user_id= $('#user_id').val();
    //alert(user_id);
    var id= $(this).data('id');
     $.ajax({
            type:'POST',
            data:{id:id,user_id:user_id},
            url:"<?php echo SITE_URL.'Pages/create';?>",
            success:function(data) {
            ///console.log(data);
            var newURL = '<?php echo SITE_URL.'Pages/survey/'; ?>'+data;
          ///  console.log(newURL);
            window.location.href = '<?php echo SITE_URL.'Pages/survey/'; ?>'+data;
            }
         });
        //return false; 
    })
    

   </script>
   <script type="text/javascript">
   $(".formSubmit").click(function(e){
    e.preventDefault();
    var user_id= $('#user_id').val();
   
    var id= $(".add").data('id');
     //alert(id);
     $.ajax({
            type:'POST',
            data:{id:id,user_id:user_id},
            url:"<?php echo SITE_URL.'Pages/submit';?>",
            success:function(data) {
            console.log(data);
           $(".survey").val(data);
           $(".mainForm").submit();
            //var newURL = '<?php echo SITE_URL.'Pages/sendsurvey/'; ?>'+data;
          ///  console.log(newURL);
          //  window.location.href = '<?php echo SITE_URL.'Pages/sendsurvey/'; ?>'+data;
            }
         });
        //return false; 
    })
    

   </script>
   <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

