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
                    <div class="alert alert-danger" style="display: none;">
                        <button data-dismiss="alert" class="close" type="button">x</button>
                        <span class="entypo-thumbs-down"></span>
                        <strong></strong>&nbsp;&nbsp;Please add some questions in the survey.
                    </div>
                   
                </div>
                 <?php
                    echo $this->Form->create("survey",array('controller'=>'Pages','action'=>'addsurvey','id'=>'myform'));
                    $user= $this->Global->getUser($user['id']);
                    $membership     = $user['membership_level'];
                    $survey_type    = $user['survey_type'];
                    $memberships    =$this->Global->getMembership($user['membership_level']);
                ?>
                <div class="career_finances_list">
                    <?php $i=1;
            
                    if(!empty($searcData)){
                    $uid=$user['id'];
                    foreach($searcData as $val){
                       /* $cate    =   $val->category_id;
                        if($cate==INTIMATE ){
                            if($memberships['slug']=='gold' || ($memberships['slug'] =='visitor' && $survey_type =='1')){
                               ?>
                                        
                            <?php
                            }
                        }
                        else{*/
                            $qid=$val->id;
                    ?>
                            <div class="career_finances_row">
                                <div class="career_finances_head">
                                    <div class="alert alert-danger error<?php echo $val->id; ?>" style="display: none;">
                                        <button data-dismiss="alert" class="close" type="button"></button>
                                        <span class="entypo-thumbs-down"></span>
                                        <strong></strong>&nbsp;&nbsp;Please become a member to add questions to Favorites.
                                    </div>
                                    <input type="hidden" value="<?php echo $membership;  ?>" name="membership_level" />
                                    <input type="hidden" value="<?php echo $survey_type;  ?>" name="survey_type" />
                                    <input type="hidden" value='<?php echo $uid; ?>' name="user_id" />
                                    <input type="hidden" value='<?php echo $val->category_id; ?>' id="category<?php echo $val->id; ?>" name='category_id[]'/>
                                    <input type="hidden" value='<?php echo $survey_id; ?>' name='survey_id'/>
                                    <input type="hidden" value='pageQuest' name="page" />
                                    <? if(!empty($user)){ ?><input type="checkbox" class="inputcheck" value="<?php echo $val->id; ?>" name="questions_id[]" id="question<?php echo $val->id; ?>"/> <? } ?>
                                    <h2><?php
                                    $replc = '<markmsg>'.$search.'</markmsg>';
                                    $question_text = str_replace($search,$replc,$val->question_text);
                                    echo $i.".".$question_text; ?></h2>
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
                                                if($memberships['slug'] =='gold' && $val->category_id == INTIMATE){
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
                                        <li>
                                            <?php
                                                $option_1 = str_replace($search,$replc,$val->option_1);
                                                echo $option_1;
                                            ?>
                                        </li>
                                        <li>
                                            <?php
                                                $option_2 = str_replace($search,$replc,$val->option_2);
                                                echo $option_2;
                                            ?>
                                        </li>
                                        <?php if(!empty($val->option_3)){ ?>
                                             <li><?php $option_3 = str_replace($search,$replc,$val->option_3);
                                                echo $option_3; ?></li>
                                        <?php } ?>
                                        <?php if(!empty($val->option_4)){ ?>
                                                <li><?php $option_4 = str_replace($search,$replc,$val->option_4);
                                                echo $option_4; ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                <?php  $i++; } ?>
                </div>
                 <div class="add_survey_button">
                    <!--<a href="javascript:;">Add to survey</a>-->
                    <?php
                    if(!empty($survey_id)){
                            echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'survey/',base64_encode($survey_id)],['style'=>'background-color:black;']); 
                    }else{
                    echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'questionbank']); } ?>
                    <?php if(isset($user)){ ?>
                        <a href="javascript:;" class="submitForm">Add to survey</a>
                    <?php } ?>
                 
                    
                </div>
                <?php }/*elseif($condition ==1 && !empty($searcData)){ ?>
                    <div class="add_survey_button">
                  
                    <?php
                    if(!empty($survey_id)){
                            echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'survey/',base64_encode($survey_id)],['style'=>'background-color:black;']); 
                    }else{
                    echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'questionbank']); } ?>
                   
                    </div>
                    <div class="alert alert-danger error" >
                        <button data-dismiss="alert" class="close" type="button">X</button>
                        <span class="entypo-thumbs-down"></span>
                        <strong></strong>&nbsp;&nbsp;Please purchase or upgrade your membership to access this category.
                    </div>
                   <?php } */else{ ?>
                        <div class="add_survey_button">
                         <?php
                            if(!empty($survey_id)){
                                    echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'survey/',base64_encode($survey_id)],['style'=>'background-color:black;']); 
                            }else{
                            echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'questionbank']); } ?>
                        </div>
                        <div class="alert alert-danger error" >
                            <button data-dismiss="alert" class="close" type="button">X</button>
                            <span class="entypo-thumbs-down"></span>
                            <strong></strong>Your search produced no results.
                        </div>
                        
                <?php } ?>
            </div>
        </section>
    </div>
    <?php echo $this->element('home_footer'); ?>
    </div>
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
                            alert("Question removed form favorite ?");
                            var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."/img/gray_star.png"; ?>">';
                            $('#star'+id).html(img);
                        }
                    }
                 });
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
           }
        });
        function showpopup(id) {
            $('.error'+id).css('display','block');
            return false;
        }
        function showpop(id) {
            $('.errors'+id).css('display','block');
            return false;
        }

    </script>
<style>
    markmsg {
    background: yellow;
}
</style>
</body>

</html>