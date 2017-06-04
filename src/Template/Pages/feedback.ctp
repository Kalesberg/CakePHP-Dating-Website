
<style>
    .proscons_score {
    background: #000000 none repeat scroll 0 0;
    border-radius: 4px;
    display: inline-block;
    font-family: "Roboto-Bold";
    margin-top: 15px;
    padding: 10px;
    text-align: center;
    width: 313px;
}
</style>
<body>
   <div class="site_content">
      <!--header-content-here-->
      
      <?php echo $this->element('home_header'); ?>
   
      <div class="warper">
         <section class="inner_page_header">
            <h2>TrueMatch Score Calculator</h2>
         </section>
         <?php if(!empty($post)){ ?>
         <section class="question_detail_content">
            <div class="container">
               <div class="question_detail_head">
                  <h2>TrueMatch Score Calculator</h2>
                  <div class="favorite_list_button">
                     <?php
                        $newArry = [];

                        if($post){
                           $user_id        =   $post[0]['user_id'];
                           $receiver_email =   $post[0]['receiver_email'];
                           $modified       =   $post[0]['modified'];
                           $sender         =   $this->Global->getUser($user_id);
                           $survey_for=isset($post[0]['survey_for'])?$post[0]['survey_for']:"";
                           if($post[0]['survey_for']=='4')
                           {
                               $receiver       =   $this->Global->getReceiverDetails($receiver_email);
                           }else{
                              // echo $receiver_email."  ".$user_id."  ".$post[0]['survey_id'];
                                $receiver       =   $this->Global->getReceiverNew($receiver_email,$user_id,$post[0]['survey_id']);
                           }
                           //pr($receiver);die;
                           $sender_name    =   $sender->first_name." ".$sender->last_name;
                           $receiver_name  =   isset($receiver['name'])?$receiver['name']:'';
                           echo '<h2 align="center">Partner: '.ucwords($receiver_name).'</h2>';
                           }else{
                               echo '<h2></h2>';
                           } ?>
                  </div>
               </div>
               <?php echo $this->Flash->render(); ?>
               <div class="career_finances_list">
                  <?php
                  $unique=[];
                  $positive_entry=[];
                  $positive_stars=[];
                  $negative_entry=[];
                  $negative_stars=[];
                    foreach($post as $val){
                        
                    $unique[]=unserialize($val->question_id);
                    $j=1;
                    $questions      =$val->question_id;
                    $questions      = !empty($questions)?(unserialize($questions)):[];
                           
                   //  $receiver_answers  = [];
                     $receiver_answers  = !empty($val->receiver_answers)?(unserialize($val->receiver_answers)):[];
                     $score_type		=!empty($val->score_type)?(unserialize($val->score_type)):[];
                     $score			=!empty($val->score)?(unserialize($val->score)):[];
                     if($questions){		 
                        foreach($questions as $q){
                            $quest= $this->Global->getQuestionsNew($q);
                            if(!in_array($q,$newArry)){
                            
                                        
                                       
                                   ?>	                    		
                  <?php echo $this->Form->create('post'); ?>
                  <div class="career_finances_row row<?php echo $q; ?>">
                     <div class="career_finances_head">
                        <h2><?php echo $j.".". $quest['question_text']; ?></h2>
                        <!--<img src="images/red_star01.png"/>-->
                     </div>
                     <div class="finances_list">
                        <ul>
                           <li> <span><input type="radio" name="answers<?php echo $val->id.$q; ?>" value="A" <?php if($receiver_answers[$q]=='A'){ ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                              </span><?php echo isset($quest['option_1'])?$quest['option_1']:''; ?>
                           </li>
                           <li> <span> <input type="radio" name="answers<?php echo $val->id.$q; ?>" value="B" <?php if($receiver_answers[$q]=='B'){  ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                              </span><?php echo isset($quest['option_2'])?$quest['option_2']:''; ?>
                           </li>
                           <?php if(!empty($quest['option_3'])){ ?>
                           <li>
                              <span><input type="radio" name="answers<?php echo $val->id.$q; ?>" value="C" <?php if($receiver_answers[$q]=='C'){ ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                              </span><?php echo $quest['option_3']; ?>
                           </li>
                           <?php }if(!empty($quest['option_4'])){ ?>
                           <li> <span><input type="radio" name="answers<?php echo $val->id.$q; ?>" value="D" <?php if($receiver_answers[$q]=='D'){ ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                              </span><?php echo $quest['option_4']; ?>
                           </li>
                           <?php } ?>
                        </ul>
                     </div>
                     <div class="positive_content">
                        <?php
                       if(!isset($score_type[$q])){
                        $score_type[$q] = 0;
                       }
                        ?>
                        <div class="positive_text">
                           <input type="radio" class="inputcheck"  name="score_type[<?php echo isset($q)?$q:""; ?>]" data-id="<?php echo isset($q)?$q:""; ?>" value="1" <?php  if(!empty($score_type) && $score_type[$q] == '1'){ ?> checked="checked" <?php ; } ?>  class="positive<?php echo $q; ?>"/><label>Positive</label>
                        </div>
                        <div class="positive_text">
                           <input type="radio" class="inputcheck" name="score_type[<?php echo $q; ?>]" data-id="<?php echo $q; ?>" value="2" <?php if(!empty($score_type) && $score_type[$q]=='2'){ ?>checked="checked" <?php  ; } ?> class="negative<?php echo $q; ?>"/><label>Negative</label>
                        </div>
                     </div>
                     <?php
                     // pr(unserialize($val->positive_entry));
                    $positive_entry[]       =unserialize($val->positive_entry);
                    $positive_stars[]       =unserialize($val->positive_star_value);
                    $negative_entry[]       =unserialize($val->negative_entry);
                    $negative_stars[]        =unserialize($val->negative_star_value);
                   // pr($score);
                        if(!empty($score) && isset($score[$q])){ ?>
                     <div class="reviews changeImg<?php echo $q; ?>" data-atrid="<?php echo $q;?>">
                        <?php
                        for($i=1;$i<=$score[$q];$i++){
                           if($score_type[$q]=='1'){
                               echo $this->Html->image('red_star01.png',['data-number'=>$i]);
                           }else{
                               echo $this->Html->image('red_star01_old.png',['data-number'=>$i]);
                           }
                           } 
                           for($i=$i;$i<=10;$i++){
                           echo $this->Html->image('gray_star.png',['data-number'=>$i]);
                           } ?>
                     </div>
                     <?php }else{?>
                     <div class="reviews changeImg<?php echo $q; ?>" data-atrid="<?php echo $q;?>">
                        <?php 
                           for($i=1;$i<=10;$i++){ ?>
                        <?php echo $this->Html->image('gray_star.png',['data-number'=>$i]); ?>
                        <?php }
                           ?>
                     </div>
                     <?php } ?>
                     <input type="hidden" value="<?php echo isset($score[$q])?$score[$q]:0; ?>" name="score[<?php echo $q; ?>]" class="score<?php echo $q; ?> scoreall"/>
                     <input type="hidden" value="<?php  echo isset($score_type[$q])?$score_type[$q]:''; ?>" name="radioCheck" class="radioCheck"/>
                     <input type="hidden" value="<?php echo $user_id; ?>" name="user_id"/>
                     <input type="hidden" value="<?php echo $survey_id; ?>" name="survey_id"/>
                     <input type="hidden" value="<?php echo $q; ?>" name="question_id[]" class="quest"/>
                  </div>
                  <?php 
                    }else{    
                        ?>   
                    <div class="career_finances_row row">
                        <h3 style="text-align: center; color: red">You have already rated this response.</h3>  
                        <div class="career_finances_head">
                           <h2><?php echo $j.".". $quest['question_text']; ?></h2>
                           <!--<img src="images/red_star01.png"/>-->
                        </div>
                        <div class="finances_list">
                            <ul>
                                <li> <span><input type="radio" value="A" <?php if($receiver_answers[$q]=='A'){ ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                                    </span><?php echo isset($quest['option_1'])?$quest['option_1']:''; ?>
                                </li>
                                <li> <span> <input type="radio" value="B" <?php if($receiver_answers[$q]=='B'){  ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                                    </span><?php echo isset($quest['option_2'])?$quest['option_2']:''; ?>
                                </li>
                                <?php if(!empty($quest['option_3'])){ ?>
                                <li>
                                   <span><input type="radio" value="C" <?php if($receiver_answers[$q]=='C'){ ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                                   </span><?php echo $quest['option_3']; ?>
                                </li>
                                <?php }if(!empty($quest['option_4'])){ ?>
                                <li> <span><input type="radio" value="D" <?php if($receiver_answers[$q]=='D'){ ?> checked="checked" <?php ; } ?> disabled="disabled"/>
                                   </span><?php echo $quest['option_4']; ?>
                                </li>
                                <?php } ?>
                            </ul>
                    </div>
                   
                  </div>
                    
                  <?php
                 
                  }
                        $j++;
                        $newArry[] = $q;
                  }  } }  //pr($positive_entry[0]); pr($positive_stars[0]); pr($negative_entry[0]);
                 // pr($negative_stars[0]); ?>
                  
                  
                  <div class="positive_list_content">
                     <div class="positive_list_inner">
                        <div class="positive_left_box">
                           <ul class="repeat">
                              <label>Any Positive Entry</label>
                              <?php 
                                 if(!empty($positive_entry[0])){
                                 	$scorep = isset($positive_stars[0])?$positive_stars[0]:"";	
                                 	//pr($scorep);
                                 	//pr($positive_entry);
                                 	$j =0;
                                 	foreach($positive_entry[0] as $p){ 
                                 
                                 ?>
                              <li>
                                 <span><input type="text" name="positive_entry[]" value="<?php echo $p ?>" class="NewScoreText" data-type="1" placeholder="Enter text"/></span>
                                 <input type="hidden" value="<?php echo $scorep[$j]; ?>" class="NewScore" name="positive_star_value[]"/>
                                 <div class="star_reveiw" data-type="1">
                                    <?php 
                                       for($i=1;$i<=$scorep[$j];$i++){
                                       	echo $this->Html->image('red_star01.png',['data-number'=>$i]);
                                       } 
                                       for($i=$i;$i<=10;$i++){
                                       	echo $this->Html->image('gray_star.png',['data-number'=>$i]);
                                       } 
                                       ?>
                                 </div>
                              </li>
                              <?php $j++;	}
                                }else{  ?>
                              <li>
                                 <span> <input type="text" name="positive_entry[]" value="<?php echo !empty($p)?$p:""; ?>"  class="NewScoreText" data-type="1" placeholder="Enter text"/></span>
                                 <input type="hidden" value="0" class="NewScore" name="positive_star_value[]"/>
                                 <div class="star_reveiw" data-type="1">
                                    <?php 
                                       for($i=1;$i<=10;$i++){ 
                                       echo $this->Html->image('gray_star.png',['data-number'=>$i]); 
                                       }
                                       
                                       ?>
                                 </div>
                              </li>
                              <li>
                                 <span><input type="text" name="positive_entry[]"  class="NewScoreText" data-type="1" placeholder="Enter text" /></span>
                                 <input type="hidden" value="0" class="NewScore" name="positive_star_value[]"/>
                                 <div class="star_reveiw" data-type="1">
                                    <?php 
                                       for($i=1;$i<=10;$i++){ ?>
                                    <?php echo $this->Html->image('gray_star.png',['data-number'=>$i]); ?>
                                    <?php }
                                       ?>
                                 </div>
                              </li>
                            
                              <?php  } ?>
                              
                              
                            
                           </ul>
                           <li><a href="javascript:;" class="newEntryPos"> Click to add new entry</a></li>
                        </div>
                        <div class="positive_right_box">
                           <ul class="repeatCons"  >
                              <label>Any Negative Entry</label>
                              <?php
                                 if(!empty($negative_entry[0])){
                                 //$negative_entry=unserialize($val->negative_entry);
                                 $scoren =isset($negative_stars[0])?$negative_stars[0]:"";
                                 $j =0;
                                 foreach($negative_entry[0] as $n){ ?>
                              <li>
                                 <span><input type="text"  name="negative_entry[]" value="<?php echo $n; ?>" class="NewScoreText" data-type="2" placeholder="Enter text"  /></span>
                                 <input type="hidden" value="<?php echo $scoren[$j]; ?>" class="NewScore" name="negative_star_value[]"/>
                                 <div class="star_reveiw" data-type="2">
                                    <?php 
                                       for($i=1;$i<=$scoren[$j];$i++){
                                       	echo $this->Html->image('red_star01_old.png',['data-number'=>$i]);
                                       } 
                                       
                                       
                                       for($i=$i;$i<=10;$i++){
                                       	echo $this->Html->image('gray_star.png',['data-number'=>$i]);
                                       } 
                                       ?>
                                 </div>
                              </li>
                              <?php $j++;	 
                                 }
                                                      }else{ ?>
                              <li>
                                 <span><input type="text"  name="negative_entry[]" class="NewScoreText" data-type="2" placeholder="Enter text" /></span>
                                 <input type="hidden" value="0" class="NewScore" name="negative_star_value[]"/>
                                 <div class="star_reveiw" data-type="2">
                                    <?php 
                                       for($i=1;$i<=10;$i++){ ?>
                                    <?php echo $this->Html->image('gray_star.png',['data-number'=>$i]); ?>
                                    <?php }
                                       ?>
                                 </div>
                              </li>
                              <li>
                                 <span><input type="text" name="negative_entry[]" class="NewScoreText" data-type="2" placeholder="Enter text"/> </span>
                                 <input type="hidden" value="0" class="NewScore" name="negative_star_value[]"/>
                                 <div class="star_reveiw" data-type="2">
                                    <?php 
                                       for($i=1;$i<=10;$i++){ ?>
                                    <?php echo $this->Html->image('gray_star.png',['data-number'=>$i]); ?>
                                    <?php }
                                       ?>
                                 </div>
                                 <?php } ?>
                              </li>
                             
                              
                           </ul>
                           <li><a href="javascript:;" class="newEntryCons" > Click to add new entry</a></li>
                        </div>
                     </div>
                  </div>
                  <div class="positive_score">
                     <ul>
                        <li>
                           <label>Positive score</label>
                           <span>
                           <input type="text"  placeholder="<?php echo !empty($val->total_positives)?$val->total_positives:0;  ?>" name="total_positives" value="" class="PositiveScore" />
                           </span>
                        </li>
                        <li>
                           <label>Negative score</label>
                           <span>
                           <input type="text" placeholder="<?php echo !empty($val->total_negative)?$val->total_negative:0;  ?>"  name="total_negative" value="" class="NegativeScore" />
                           </span>
                        </li>
                     </ul>
                  </div>
                  <div class="add_survey_button"  id="calc">
                     <input type="submit" value="Submit"/>
							
                            <a onclick="reportprint(); return false;">Print</a>
                        <br>
                     <?php //  if(isset($post) && ($survey_for=='2' || $survey_for=='4')){?>
                    <div class="proscons_score">
                    
                <?php
               
                    $survey_id=isset($post[0]['survey_id'])?$post[0]['survey_id']:"";
                    $proscons = $this->Global->getproscons($user_id,$survey_id); 
                    $pros = !empty($proscons['total_positives'])?$proscons['total_positives']:"0";
                    $cons = !empty($proscons['total_negative'])?$proscons['total_negative']:"0";
                    if(!empty($proscons )){
                            if($pros=='0'){ ?>
                                <span>TrueMatch score</span> 	   
                                <p><?php echo "0"; ?></p>
                            <?php }else if($cons=='0'){ ?>
                                <span>TrueMatch score</span> 	   
                                <p><?php echo round($pros/1,'2'); ?></p>
                            <?php }else{  ?>
                                <span>TrueMatch score</span> 	   
                                <p><?php echo round($pros/$cons,'2'); ?></p>
                            <?php }
                    }	
                    else{ ?>
                            <span>Not Updated</span> 	   
                            <p>0</p>
                       <?php } 
                    ?>
                
                </div>
                    <?php // } ?>
                </div>
                <?php echo $this->Form->end(); ?>
               </div>
            </div>
         </section>
       
         <?php }else{ ?>
        <section class="question_detail_head">
             <h2 align="center">This Partner does not have any Feedback yet.</h2>
         </section>
        <?php } ?>
      </div>
      <?php echo $this->element('home_footer'); ?>
   </div>
   <div class="NegativeCopy" style="display: none;">
      <li>
         <span><input type="text"  name="negative_entry[]"  class="NewScoreText" data-type="2" placeholder="Enter text"/> </span>
         <input type="hidden" value="0" class="NewScore" name="negative_star_value[]"/>
         <div class="star_reveiw"  data-type="2">
            <?php 
               for($i=1;$i<=10;$i++){
                  echo $this->Html->image('gray_star.png',['data-number'=>$i]);
               }
               ?>
         </div>
      </li>
   </div>
   <div class="PositiveCopy" style="display: none;">
      <li>
         <span><input type="text" name="positive_entry[]" class="NewScoreText"  data-type="1" placeholder="Enter text"/> </span>
         <input type="hidden" value="0" class="NewScore"  name="positive_star_value[]"/>
         <div class="star_reveiw" data-type="1">
            <?php 
               for($i=1;$i<=10;$i++){
                  echo $this->Html->image('gray_star.png',['data-number'=>$i]);
               }
               ?>
         </div>
      </li>
   </div>
   <script>
      var a =[];
      $('.reviews').on('click','img',function(){
        var checkval = 1;
        $(this).parent().parent().find('.positive_text .inputcheck').each(function(){
            if ($(this).is(':checked')) {
                 checkval = $(this).val();
            }
        });
        
          var newClass  = $(this).parent('.reviews').data('atrid');
          $(this).parent('.reviews').html('');
          var dataNumber = $(this).data('number');
          //console.log(a);
          
          $('.score'+newClass).val(dataNumber);
          for(var i = 1; i < 11; i++){
            if(checkval == 1){
                  var src ="<?php echo SITE_URL.'img/red_star01.png'; ?>";
            }else{
                var src ="<?php echo SITE_URL.'img/red_star01_old.png'; ?>";
            }
          
            if(dataNumber < i){ src =  "<?php echo SITE_URL.'img/gray_star.png'; ?>"; }
            var img = '<img data-number="'+i+'" src="'+src+'">';
            $('.changeImg'+newClass).append(img);
          }
          
           score();
        });
      
        $('.positive_list_inner').on('click','.star_reveiw img',function(){
        var datatype = $(this).parent().attr('data-type');
       // alert(datatype);
         var dataNumber = $(this).data('number');
         var  star_reveiw  = $(this).parent();
          $(this).parent().parent().find('.NewScore').val(dataNumber);
         $(this).parent().html('');
          for(var i = 1; i < 11; i++){
           if(datatype == 1){
                  var src ="<?php echo SITE_URL.'img/red_star01.png'; ?>";
            }else{
                var src ="<?php echo SITE_URL.'img/red_star01_old.png'; ?>";
            }
           // var src ="<?php echo SITE_URL.'img/red_star01.png'; ?>";
            if(dataNumber < i){ src =  "<?php echo SITE_URL.'img/gray_star.png'; ?>"; }
            var img = '<img data-number="'+i+'" src="'+src+'">';
            star_reveiw.append(img);
          }
         score();
      });
      
      $('.positive_list_inner').on('keyup','.NewScoreText',function(){
         score();
      });
      
      score();
      $(".newEntryPos").click(function(){
          var d = $(".PositiveCopy").html();
          $(".repeat").append(d);
      });
      $(".newEntryCons").click(function(){
          var d = $(".NegativeCopy").html();
          $(".repeatCons").append(d);
      });
      
      $('.inputcheck').click(function(){
         $(this).parent().parent().parent().find('.radioCheck').val($(this).val());
         score();
      });
      
      function score(){
         var  positive  = 0;
         var  negative  = 0;
         $('.scoreall').each(function(){
            var score = $(this).val()
            if(score){
               var radioVal = $(this).parent().find('.radioCheck').val();
	       //console.log(radioVal);
               if(radioVal == 1){
                  positive = parseInt(positive) + parseInt($(this).val());
                }else if(radioVal == 2){
                  negative = parseInt(negative) + parseInt($(this).val());
               }
            }
         });
         
         $('.positive_list_inner .NewScoreText').each(function(){
            var thisVal = $(this).val();
            var thisStarval = $(this).parent().parent().find('.NewScore').val()
            var thisType = $(this).data('type');
            if(thisVal && thisStarval){
               if(thisType == 1){
                  positive = parseInt(positive) + parseInt(thisStarval);
               }else if(thisType == 2){
                  negative = parseInt(negative) + parseInt(thisStarval);
               }
            }
         });
         
        $('.PositiveScore').val(positive);
        $('.NegativeScore').val(negative);
      }
      
      $('.career_finances_list').on('click','.inputcheck',function(){
        var radiaCheckVal = $(this).val();
        var starVal = $(this).parent().parent().parent().find('.scoreall').val();
        var  star_reveiw  = $(this).parent().parent().parent().find('.reviews');
        $(this).parent().parent().parent().find('.reviews').html("");
	 if(starVal){
	    for(var i = 1; i < 11; i++){
	    if(radiaCheckVal == 1){
		   var src ="<?php echo SITE_URL.'img/red_star01.png'; ?>";
	     }else{
		 var src ="<?php echo SITE_URL.'img/red_star01_old.png'; ?>";
	     }
	    // var src ="<?php echo SITE_URL.'img/red_star01.png'; ?>";
	     if(starVal < i){ src =  "<?php echo SITE_URL.'img/gray_star.png'; ?>"; }
	     var img = '<img data-number="'+i+'" src="'+src+'">';
	     star_reveiw.append(img);
	   }
	}
    });
    function reportprint() {
        window.print();
    }
   
   </script>
   <style>
      .positive_rating img{
      cursor: pointer;
      }
   </style>
</body>
</html>

