<?php
$user = $this->request->session()->read('Auth.User'); ?>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>Question Bank</h2>
            </section>
            <section class="question_detail_content">
                <div class="container">
                   
                    <div class="question_detail_head">
                         <?php echo $this->Flash->render(); ?>
                    <h2>
                        <?php if(!empty($category)){ echo $category->category_name; } ?>
                       
                    </h2> 
                    <div class="favorite_list_button">
                        <?php //if(isset($user)){ echo $this->Html->Link("Favorite list",['controller'=>'Pages','action'=>'favouritelist']); } ?>
                    </div>
                </div>
                 <?php if(!empty($category)){
                 echo $this->Form->create($post,array('controller'=>'Pages','action'=>'addsurvey','id'=>'myform')); ?>
                  <?php
                    $user= $this->Global->getUser($user['id']);
                    $membership     = $user['membership_level'];
                    $survey_type    = $user['survey_type'];
                    $memberships=$this->Global->getMembership($user['membership_level']);
                ?>
                <div class="career_finances_list">
                    <div class="alert alert-danger errornew" style="display: none;">
                      <!--  <button data-dismiss="alert" class="close" type="button">×</button>-->
                        <span class="entypo-thumbs-down"></span>
                        <strong></strong>&nbsp;&nbsp;Please add some Questions to the survey.
                    </div>
                   
                    <?php $i=1;
                    $uid=$user['id'];
                    //pr($survey_id);
                    foreach($post as $val){
                        $qid=$val->id;
                    ?>
                    <div class="career_finances_row">
                        <div class="career_finances_head">
                            <div class="alert alert-danger error<?php echo $val->id; ?>" style="display: none;">
                                <button data-dismiss="alert" class="close" type="button"></button>
                                <span class="entypo-thumbs-down"></span>
                                <strong></strong>&nbsp;&nbsp;Please become a member to add questions to Favorites.
                            </div>
                            <div class="alert alert-danger errors<?php echo $val->id; ?>" style="display: none;">
                                <button data-dismiss="alert" class="close" type="button"></button>
                                <span class="entypo-thumbs-down"></span>
                                <strong></strong>&nbsp;&nbsp;Please purchase Advanced Survey or upgrade to Platinum Membership to use this category.
                            </div>
                            <input type="hidden" value="<?php echo $membership;  ?>" name="membership_level" />
                            <input type="hidden" value="<?php echo $survey_type;  ?>" name="survey_type" />
                            <input type="hidden" value='<?php echo $uid; ?>' name="user_id" />
                            <input type="hidden" value='<?php echo $category->id; ?>' id="category<?php echo $val->id; ?>" name='category_id[]'/>
                            <input type="hidden" value='<?php echo $survey_id; ?>' name='survey_id'/>
                            <input type="hidden" value='pageQuest' name="page" />
                            <?php  if(isset($user)){
                                if($category->id == INTIMATE){
                                   
                                    if($memberships['slug'] =='gold' || ($memberships['slug'] =='visitor' && $survey_type =='1') ){      
                                    ?>
                                        <input type="checkbox" onchange="showpop(<?php echo $val->id; ?>);" /> 
                                    <?php
                                    }else{ ?>
                                          <input type="checkbox" class="inputcheck" value="<?php echo $val->id; ?>" name="questions_id[]" id="question<?php echo $val->id; ?>"/> 
                                    <?php }
                                }else{ ?>
                                      <input type="checkbox" class="inputcheck" value="<?php echo $val->id; ?>" name="questions_id[]" id="question<?php echo $val->id; ?>"/> 
                                <?php  }
                            } ?>
                            <h2><?php echo $i.". ".$val->question_text; ?></h2>
                           
                           <?php
                          
                            if(isset($user)){
                                if($memberships['slug'] !='visitor' )
                                {
                                   
                                    if($this->Global->getStar($uid,$qid))
                                    {  
                                    ?>
                                        <span id="star<?php echo $val->id; ?>">
                                            <?php echo $this->Html->image('red_star01.png',['onclick' => "changeImage($val->id)"]); ?>    
                                        </span>
                                    <?php }
                                    else
                                    {
                                        if($memberships['slug'] =='gold' && $category->id == INTIMATE){
                                            ?>
                                             <span class="star">
                                            <?php
                                             //echo $this->Html->image('gray_star.png',['onclick' => "showpopup($val->id)"]);     
                                        ?>
                                         </span>
                                        <?php 
                                        }else{
                                        ?>
                                        <span id="star<?php echo $val->id; ?>">
                                            <?php  echo $this->Html->image('gray_star.png',['onclick' => "changeImage($val->id)"]); ?>    
                                        </span>
                                        <?php
                                        }
                                    }
                                }else if($memberships['slug'] =='gold' || $memberships['slug'] =='visitor' ){ ?>
                                    <span class="star">
                                        <?php echo $this->Html->image('gray_star.png',['onclick' => "showpopup($val->id)"]); ?>    
                                    </span>
                                <?php }
                                else{
                                ?>
                                <span class="star<?php echo $val->id; ?>">
                                    <?php echo $this->Html->image('gray_star.png',['onclick' => "showpopup($val->id)"]); ?>    
                                </span>
                           <?php } }?>
                        </div>
                        <div class="finances_list">
                            <ul>
                                <li><?php echo $val->option_1; ?></li>
                                <li><?php echo $val->option_2; ?></li>
                                <?php if(!empty($val->option_3)){ ?>
                                     <li><?php echo $val->option_3; ?></li>
                                <?php } ?>
                                <?php if(!empty($val->option_4)){ ?>
                                        <li><?php echo $val->option_4; ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php $i++; } ?>
                </div>
                <div class="add_survey_button">
                    <!--<a href="javascript:;">Add to survey</a>-->
                    <?php
                    if(!empty($survey_id)){
                            echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'survey/',base64_encode($survey_id)],['style'=>'background-color:black;']); 
                    }else{
                          echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'questionbank'],['style'=>'background-color:black;']);
                    }
                    ?>
                    <?php if(isset($user)){
                        if($category->id == INTIMATE){
                            if($memberships['slug'] =='gold' || ($memberships['slug'] =='visitor' &&  $survey_type =='1') ){
                            }else{ ?>
                                <a href="javascript:;" class="submitForm">Add to survey</a>
                            <?php }
                        }else{ ?>
                                <a href="javascript:;" class="submitForm">Add to survey</a>
                        <?php } } ?>
                 
                    
                </div>
                
                <?php echo $this->Form->end(); } ?>
                
             
            </div>
        </section>
    </div>
    <?php echo $this->element('home_footer'); ?>
    </div>
<?php //echo $site_url=SITE_URL; ?>
<script type="text/javascript">
function changeImage(id) {
    var cat_id = document.getElementById('category'+id).value;
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
function showpopup(id) {
    $('.error'+id).css('display','block');
    return false;
}
function showpop(id) {
    $('.errors'+id).css('display','block');
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
      $(".errornew").css("display","block");
      $('html, body').animate({
        scrollTop: $("body").offset().top
      },0);
   }else{
       $('#myform').submit();
   }
});
</script>
</body>

</html>