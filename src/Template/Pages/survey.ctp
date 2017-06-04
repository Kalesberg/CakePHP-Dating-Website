<?php 
   $user = $this->request->session()->read('Auth.User');
   
   ?>
<style>
.add_remove_button ul li:last-child a {
    background: #333 none repeat scroll 0 0;
}
.add_remove_button ul li a{
    
     background: #3bbbeb none repeat scroll 0 0;
     text-transform:none;
}
</style>
<body>
    <div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
           <h2>Survey</h2>
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
                        <?php
                    
                           $status=isset($post['type'])?$post['type']:"";
                           if($post['page'] !="pageRand"){ ?>
                        <li>
                           <?php
                              echo $this->Html->link("Add Questions from Question Bank",['controller'=>'Pages','action'=>'questionbank',base64_encode($survey_id)],['class'=>'add']); ?>
                        </li>
                        <li>
                           <?php
                           $users=$this->Global->getUser($user['id']);
                            $memberhip=$this->Global->getMembership($users['membership_level']);
                            if($memberhip['slug']!='visitor'){
                            echo $this->Html->link("Add Questions from Favorites",['controller'=>'Pages','action'=>'favouritelist',base64_encode($survey_id)],['class'=>'addFav']);
                              
                            }  ?>
                        </li>
                        <li>
                        <li><?php if(empty($usertype) && ($post['page'] !="pageRand") ){ ?><a class="remove removequestions" data-id='<?php echo $survey_id; ?>'  >Remove Questions</a><?php } ?>
                           <?php } ?>
                        </li>
                    </ul>
                </div>
                <?php echo $this->Form->create($post,array('controller'=>'Pages','action'=>'sendsurvey','class'=>'mainForm')); ?>
                <input type="hidden" name="withanswer" value="2" class="withanswer"/>
                <input type="hidden" name="surveyname" value="" class="surveyname"/>
                <input type="hidden" name="processType" value="save" class="processType"/>
                <div class="career_finances_list">
                   <?php echo $this->Flash->render(); ?> 
                    <div class="msg">
                    </div>
                        
                    <div class="alert alert-danger error"  style="display: none">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <span class="entypo-thumbs-down"></span>
                        <strong></strong>&nbsp;&nbsp;Please answer all the questions before saving this survey.
                    </div>
                    <?php $i=1;
                        $questions=$post['questions_id'];
                        $questions=unserialize($questions);
                        $answers=unserialize($post['answers']);
                        if($questions){
                        foreach($questions as $q){
                           $quest= $this->Global->getQuestionsNew($q);
                    ?>
                    <div class ="career_finances_row quest<?php echo $q; ?>">
                        <div class="career_finances_head">
                           <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>"/>
                           <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>" />
                           <input type="hidden" name="usertype" value="<?php echo $usertype; ?>" />
                           <input type="hidden" name="receiver_email" value="<?php echo $email; ?>" />
                           <input type="hidden" name="category_id" value="<?php echo $post['category_id']; ?>" />
                           <input type="hidden" name="question_id[]" id="qid"  value="<?php echo $q; ?>" >
                            <div class="alert alert-danger error<?php echo $q; ?>" style="display: none;">
                                <button data-dismiss="alert" class="close" type="button">×</button>
                                <span class="entypo-thumbs-down"></span>
                                <strong></strong>&nbsp;&nbsp;Please upgrade your membership to add question in favorite.
                            </div>
                           <?php  if(($usertype == "receiver") || ($post['page'] =="pageRand")){ ?>
                              <input class="inputcheck"  type="checkbox" value="<?php echo $q; ?>" id="question<?php echo $q; ?>" style="display: none; "/>
                           <?php }else{ ?>
                           <input class="inputcheck"  type="checkbox" value="<?php echo $q; ?>"  id="question<?php echo $q; ?>"/>
                           <?php }  ?>
                           <input type="hidden" class="checkVal" value="">
                           <h2><?php echo $i.". ".$quest['question_text']; ?></h2>
                            <?php
                            if($post['page']=="pageRand"){
                                $users=$this->Global->getUser($user['id']);
                                $membership=$this->Global->getMembership($users['membership_level']);
                                if(isset($user) && $membership['slug']!='visitor'){
                                if($this->Global->getStar($users['id'],$q)){
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
                          //  $withanswer =   $this->Global->getSavedSurvey($survey_id);
                          // echo $withanswer['type'];die;
                            if(($post['type'] == SAVED)){
                                if(empty($answers)){
                                    $answers=$this->Global->getAnswer($survey_id,$post['type']);
                                }
                            //   pr($answers);die;
                               
                               
                                ?>
                                <ul style="list-style: none;">
                                <li><input type="radio" value="A" <?php if(!empty($answers[$q]) && $answers[$q]=='A'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;A.&nbsp;<?php echo $quest['option_1']; ?></li>
                                <li><input type="radio" value="B" <?php if(!empty($answers[$q]) && $answers[$q]=='B'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;B.&nbsp;<?php echo $quest['option_2']; ?></li>
                                <?php if(!empty($quest['option_3'])){ ?>
                                <li><input type="radio" value="C" <?php if(!empty($answers[$q]) && $answers[$q]=='C'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;C.&nbsp;<?php echo $quest['option_3']; ?></li>
                                <?php }if(!empty($quest['option_4'])){ ?>
                                <li><input type="radio" value="D" <?php if(!empty($answers[$q]) && $answers[$q]=='D'){ ?> checked <?php ; }?> class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;D.&nbsp;<?php echo $quest['option_4']; ?></li>
                                <?php } ?>
                             </ul>
                               
                            <?php }else{ ?>
                                <ul style="list-style: none;">
                                    <li><input type="radio" value="A" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;A.&nbsp;&nbsp;<?php echo $quest['option_1']; ?></li>
                                    <li><input type="radio" value="B" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;B.&nbsp;&nbsp;<?php echo $quest['option_2']; ?></li>
                                    <?php if(!empty($quest['option_3'])){ ?>
                                    <li><input type="radio" value="C" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;C.&nbsp;&nbsp;<?php echo $quest['option_3']; ?></li>
                                    <?php }if(!empty($quest['option_4'])){ ?>
                                    <li><input type="radio" value="D" class="qustionAns" name="answers[<?php echo $q; ?>]" required/>&nbsp;&nbsp;D.&nbsp;&nbsp;<?php echo $quest['option_4']; ?></li>
                                    <?php } ?>
                                </ul>
                            <?php }        
                            ?>
                        </div>
                    </div>
                   <?php $i++; } } ?>
                </div>
              
                <div class="add_survey_button">
                    <input type="submit" name="submitSurvey" class="formSubmit" data-submitType="send" value="Submit Survey" />
                    <?php
                    $memberhip=$this->Global->getMembership($users['membership_level']);
                    if($memberhip['slug']!='visitor' && $post['page'] !="pageRand"){ ?>
                        <a type="button" style="text-decoration: none;" class="formSubmit" data-submitType="save" data-toggle="modal" data-target="#myModal">Save Survey</a> 
                    <?php } 
                        echo $this->Html->Link('Cancel',['controller'=>'Pages','action'=>'cancel',base64_encode($survey_id)],['style'=>'text-decoration: none;background-color:black;']);
                      
                   ?>
                    <?php //echo $this->Html->link("Save Survey",['controller'=>'Pages','action'=>'savesurvey',base64_encode($survey_id)]); ?>
                </div>
               
                <?php echo $this->Form->end(); ?>
            </div>
        </section>
      <!-- Small modal -->
      <div class="modal fade" id="myModal" role="dialog" style="display:none;">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" style="text-align: center !important">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Save Survey</h4>
               </div>
               <div class="modal-body">
                 <div class="form_fild_box">
                    <input type="text" name="surveyname" class="nameSurvey" placeholder="Survey Name" class="form-control" required/>
                 </div>
                  <p>Do you want to save your answers?</p>
               </div>
               <div class="modal-footer" style="text-align: center !important">
                  <button type="button" class="btn btn-default RadioWithAnswer" data-val="1">Yes</button>
                  <button type="button" class="btn btn-default RadioWithAnswer" data-val="2">No</button>
               </div>
            </div>
         </div>
      </div>
      <?php echo $this->element('home_footer'); ?>
   </div>
   <script type="text/javascript">
    function changeImage(id) {
        var cat_id ="";
        var ques_id = document.getElementById('question'+id).value;
        
         $.ajax({
                type:'POST',
                data:{question_id:ques_id,category_id:cat_id},
                url:"<?php echo SITE_URL.'Pages/addfavorite';?>",
                success:function(data) {
                    if (data=='1') {
                        alert("Add question to Favorites?");
                         var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."img/red_star01.png"; ?>">';
                        $('#star'+id).html(img);
                    }else{
                        alert("Question removed form favorite.");
                        var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."/img/gray_star.png"; ?>">';
                        $('#star'+id).html(img);
                    }
                }
             });
        return false; 
    }
	$('.RadioWithAnswer').click(function(){
	    $('.error').hide();
	    var thisVal = $(this).data('val');
        var surveyName =$('.nameSurvey').val();
        if (surveyName) {
             $('.nameSurvey').css('border','1px solid #cacaca');
            $('.surveyname').val(surveyName);
            $('.withanswer').val(thisVal);
            $('.modal-header .close').trigger('click');
            if(thisVal == 2){
            $('.mainForm').submit();
            }else{
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
                //$('.add').hide();
                //$('.addFav').hide();
                //$('.remove').hide();
                return false;
            }
            }
        }else{
            $('.nameSurvey').css('border','1px solid red');
        }
	});
	
	$('.formSubmit').click(function(){
      
	 var processType = $(this).attr('data-submitType');
	 $('.processType').val(processType);
	});
      var ids = [];
      $('.inputcheck').change(function(){
         var ids = [];
          $(".inputcheck").each(function() {
              if ($(this).is(':checked')) {
                  var val = $(this).val();
                  ids.push(val);
              }
             
          });
          $('.checkVal').val(ids);
      });
	   $('.removequestions').click(function(){
		   var survey_id = $(this).data('id');
      		
          var idss = $('.checkVal').val();
          if (idss) {
			  $(this).removeClass('removequestions');
               $.ajax({
                 type:'POST',
                 data:{idss:idss,survey_id:survey_id},
                 url:"<?php echo SITE_URL.'Pages/removequestion' ; ?>",
                 success:function(data) {
                  	var message = JSON.parse(data);
                  	var a =message['id'];
                  	a.forEach(function(id){
                      	$(".quest"+id).remove();
                 	});
					var msg ='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><span class="entypo-thumbs-up"></span><strong></strong>&nbsp;&nbsp;'+message['msg']+'</div>'
                  	//alert(message['msg']);
				//	$('.msg').html(msg);
				//	$('.remove').addClass('removequestions');
                //    $('.checkVal').val('');
                    location.reload();
				 }
              	});
          	}else{
                var msg1 ='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><span class="entypo-thumbs-down"></span><strong></strong>&nbsp;&nbsp;Please select question to remove.</div>'
                $('.msg').html(msg1);
            }
               
          	});
      //	});
  /*  function changeImage(id) {
   // var cat_id = document.getElementById('category'+id).value;
    var ques_id = document.getElementById('question'+id).value;
   // alet(id);
     $.ajax({
            type:'POST',
            data:{question_id:ques_id},
            url:"<?php // echo SITE_URL.'Pages/addfavorite';?>",
            success:function(data) {
                if (data=='1') {
                    alert("Add question to Favorites? OK");
                     var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."img/red_star01.png"; ?>">';
                    $('#star'+id).html(img);
                }else{
                    alert("Question removed form favorite.");
                    var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."/img/gray_star.png"; ?>">';
                    $('#star'+id).html(img);
                }
            }
         });
    return false; 
}*/
function showpopup(id) {
    $('.error'+id).css('display','block');
    return false;
}

$('.submitForm').click(function(){
   var nextProcess = 0;
   $(".inputcheck").each(function() {
      if ($(this).is(':checked')) {
         nextProcess = parseInt(nextProcess) + parseInt(1);
      }
   });
   if(nextProcess == 0){
      $(".alert-danger").css("display","block");
      $('html, body').animate({
        scrollTop: $("body").offset().top
      },0);
   }else{
       $('#myform').submit();
       return false;
   }
});
   </script>
   <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>

