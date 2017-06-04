<?php                    
 $user = $this->request->session()->read('Auth.User'); ?>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>Random Challenge Survey</h2>
            </section>
            <section class="question_detail_content">
                <div class="container">
                    <div class="question_detail_head">
                    <h2>
                    </h2> 
                    <div class="favorite_list_button">
                        <?php echo $this->html->link("Favourite list",['controller'=>'Pages','action'=>'favouritelist']);?>
                    </div>
                </div>
                 <?php
                 echo $this->Form->create('Random',array('controller'=>'Pages','action'=>'addrandomsurvey','id'=>'myform')); ?>
                <div class="career_finances_list">
                    <?php echo $this->Flash->render(); ?>
                    <div class="alert alert-danger" style="display: none;">
                        <button data-dismiss="alert" class="close" type="button">×</button>
                        <span class="entypo-thumbs-down"></span>
                        <strong></strong>&nbsp;&nbsp;Please add some questions in the survey.
                    </div>
                    <?php $i=1;
                    $uid=$user['id'];
                    if(!empty($post)){
                    $user= $this->Global->getUser($user['id']);
                    $membership     = $user['membership_level'];
                    $survey_type    = $user['survey_type'];
                
                    foreach($post as $val){
                        $qid=$val->id;
                    ?>
                    <div class="career_finances_row">
                        <div class="career_finances_head">
                            <input type="hidden" value='<?php echo $uid; ?>' name="user_id" />
                            <input type="hidden" value="<?php echo $membership;  ?>" name="membership_level" />
                            <input type="hidden" value="<?php echo $survey_type;  ?>" name="survey_type" />
                            <input type="hidden" value='<?php echo serialize($categoryId); ?>' name='category_id'/>
                            <input type="hidden" value='<?php echo $val->category_id; ?>' id="category<?php echo $val->id; ?>" />
                            <input type="hidden" value='<?php echo $survey_id; ?>' name='survey_id'/>
                            <input type="hidden" value='<?php echo $totalquestions; ?>' name='totalquestions'/>
                            <input type="hidden" value='pageRand' name="page" />
                            <input type="checkbox" class="inputcheck" value="<?php echo $val->id; ?>" name="questions_id[]" id="question<?php echo $val->id; ?>"/> 
                            <h2><?php echo $i.".".$val->question_text; ?></h2>
                            <?php if($this->Global->getStar($uid,$qid)){
                             ?>
                            <span id="star<?php echo $val->id; ?>">
                                <?php echo $this->Html->image('red_star01.png',['onclick' => "changeImage($val->id)"]); ?>    
                            </span>
                            <?php }else{ ?>
                            <span id="star<?php echo $val->id; ?>">
                                <?php echo $this->Html->image('gray_star.png',['onclick' => "changeImage($val->id)"]); ?>    
                            </span>
                            <?php } ?>
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
                <?php $i++; }  ?>
                </div>
                <div class="add_survey_button">
                   <a href="javascript:;" class="submitForm">Add to survey</a>
                    <!--<input type="submit" value="Add to survey" />-->
                </div>
                
                <?php } echo $this->Form->end(); ?>
            </div>
        </section>
    </div>
        <?php echo $this->element('home_footer'); ?>
    </div>
    <script type="text/javascript">
function changeImage(id) {
    var cat_id = document.getElementById('category'+id).value;
    var ques_id = document.getElementById('question'+id).value;
    //var url = site_url+"Pages/addfavorite/";
   // alert(cat_id);
     $.ajax({
            type:'POST',
            data:{question_id:ques_id,category_id:cat_id},
            url:"<?php echo SITE_URL.'Pages/addfavorite';?>",
            success:function(data) {
                if (data=='1') {
                     alert("Question added to your favourite.");
                     var img = '<img alt="" onclick="changeImage('+id+')" src="<?php echo SITE_URL."img/red_star01.png"; ?>">';
                    $('#star'+id).html(img);
                }else{
                    alert("Question removed form favourite.");
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