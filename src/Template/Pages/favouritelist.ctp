<?php
$user = $this->request->session()->read('Auth.User'); ?>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>My Favorite Questions</h2>
            </section>
            <section class="question_detail_content">
               
                <div class="container">
                    <div class="question_detail_head">
                    <h2>
                        
                    </h2> 
                    <div class="favorite_list_button">
                        <?php //echo $this->Html->Link("Favorite list",['controller'=>'Pages','action'=>'favouritelist']); ?>
                    </div>
                </div>
                    <?php echo $this->Flash->render(); ?>
                 <?php if(!empty($post)){
                 echo $this->Form->create($post,array('controller'=>'Pages','action'=>'addsurvey','id'=>'myform'));
                    $user= $this->Global->getUser($user['id']);
                    $membership     = $user['membership_level'];
                    $survey_type    = $user['survey_type'];
                 ?>
               
                <div class="career_finances_list">
                    <div class="alert alert-danger" style="display: none;">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <span class="entypo-thumbs-down"></span>
                        <strong></strong>&nbsp;&nbsp;Please add some questions in the survey.
                    </div>
                    <?php $i=1;
                    $uid=$user['id'];
                    foreach($post as $val){
                        $qid=$val->question_id;
                        $data=$this->Global->getQuestions($qid);
                        //pr($data);die;
                    ?>
                    <div class="career_finances_row">
                        <div class="career_finances_head">
                             <input type="hidden" value="<?php echo $membership;  ?>" name="membership_level" />
                            <input type="hidden" value="<?php echo $survey_type;  ?>" name="survey_type" />
                            <input type="hidden" value='<?php echo $val->category_id; ?>' id="category<?php echo $val->question_id; ?>" name='category_id[]'/>
                            <input type="hidden" value='Favlist' name="page" />
                             <input type="hidden" value='<?php echo $uid; ?>' name="user_id" />
                            <input type="hidden" value='<?php echo $survey_id; ?>' name="survey_id" />
                            <input type="checkbox" class="inputcheck" value="<?php echo $val->question_id; ?>" name="questions_id[]" id="question<?php echo $val->question_id; ?>"/> 
                            <h2><?php echo $i.".".$data[0]['question_text']; ?></h2>
                           <?php if($this->Global->getStar($uid,$qid)){
                             ?>
                            <span id="star<?php echo $val->question_id; ?>">
                                <?php echo $this->Html->image('red_star01.png',['onclick' => "changeImage($val->question_id)"]); ?>    
                            </span>
                            <?php }else{ ?>
                            <span id="star<?php echo $val->question_id; ?>">
                                <?php echo $this->Html->image('gray_star.png',['onclick' => "changeImage($val->question_id)"]); ?>    
                            </span>
                            <?php } ?>
                        </div>
                        <div class="finances_list">
                            <ul>
                                <li><?php echo $data[0]['option_1']; ?></li>
                                <li><?php echo $data[0]['option_2']; ?></li>
                                <?php if(!empty($data[0]['option_3'])){ ?>
                                     <li><?php echo $data[0]['option_3']; ?></li>
                                <?php } ?>
                                <?php if(!empty($data[0]['option_4'])){ ?>
                                        <li><?php echo $data[0]['option_4']; ?></li>
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
                    <a href="javascript:;" class="submitForm">Add to survey</a>
                </div>
                <?php echo $this->Form->end(); }else{ ?>
                <div class="question_detail_head">
                    <h2>
                     
                    </h2>
                </div>
               <?php } ?>
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
    //var url = site_url+"Pages/addfavorite/";
    //alert(cat_id);
     $.ajax({
            type:'POST',
            data:{question_id:ques_id,category_id:cat_id},
            url:"<?php echo SITE_URL.'Pages/addfavorite';?>",
            success:function(data) {
                if (data=='1') {
                    var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."img/red_star01.png"; ?>">';
                    $('#star'+id).html(img);
                }else{
                    var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."/img/gray_star.png"; ?>">';
                    $('#star'+id).html(img);
                }
            }
         });
    return false; // prevent the link's default behaviour
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
</script>
</body>

</html>