<body>
    <div class="site_content">
        <!--header-content-here-->
          <?php echo $this->element('home_header'); ?>
        <!--header-content-end-->
        <?php echo $this->Html->css(['compatibilityprint'],['media' => 'print']); ?>
        <div class="warper">
            <section class="inner_page_header">
             <h2>Compatibility Report</h2>
            </section>
         
            <section class="compatibility_report_content">
                <div class="compatility_survey_head">
                    <div class="container">
						
                        <?php
                        $sender = [];
                        $sender_name = $receiver_name = '';
                        //pr($post);die;
                        if($post){
                            $user_id        =   $post['user_id'];
                            $receiver_email =   $post['receiver_email'];
                            $post['survey_id'];
                            $modified       =   $post['modified'];
                            $sender         =   $this->Global->getUser($user_id);
                            $receiver       =   $this->Global->getReceiverNew($receiver_email,$user_id,$post['survey_id']);
							// pr($receiver);die;
                            $sender_name    =   $sender->first_name." ".$sender->last_name;
                            $receiver_name  =   isset($receiver['name'])?$receiver['name']:'';
                            echo '<h2>'.ucwords($sender_name).' and '.ucwords($receiver_name).' Compatibility Report</h2>';
                        }else{
                            echo '<h2></h2>';
                        }
                           
                        ?>
                        <span><div class="add_survey_button" style="margin-top: -24px;">
							<a onclick="reportprint(); return false;">Print</a>
                    	</div> </span>
						
                    </div>
                </div>
             
                <div class="sender_receiver_content">
                    <div class="sender_receiver_box sender_box">
                        <div class="sender_receiver_head">
                            <h2>sender</h2>
                            <div class="sender_receiver_user">
                            <?php
                            if(!empty($sender->profile_photo)){ ?>
                                <a href="javascript:;"> <?php echo $this->Html->image("user_images/".$sender->profile_photo,['style'=>'height: 100%;width: 100%;']); ?></a>
                            <?php }else{ ?>
                                <a href="javascript:;"> <?php echo $this->Html->image('user_default.jpeg'); ?></a>
                             <?php } ?>
                            </div>
                            <div class="sender_receiver_title">
                             <a href="javascript:;"><?php echo $sender_name; ?></a>
                            </div>
                        </div>
                    
                        <div class="sender_receiver_list">
                            <?php
                                $i=1;
                                $sender_answer = [];
                                $questions      =$post['question_id'];
                                $sender_answer  = !empty($post['answers'])?(unserialize($post['answers'])):[];
                                $questions  = !empty($questions)?(unserialize($questions)):[];
                                
                                if($questions){
                                    foreach($questions as $q){
                                        $quest= $this->Global->getQuestionsNew($q);
                                 ?>
                                    <div class="sender_receiver_row">
                                        <h3><?php echo $i.".". $quest['question_text']; ?></h3> 
                                        <ul>
                                            <li>
                                                <input type="radio" name="answers<?php echo $q ?>" value="A" <?php if($sender_answer[$q]=='A'){ ?> checked <?php ; } ?>/>
                                                <span><?php echo isset($quest['option_1'])?$quest['option_1']:''; ?></span>
                                            </li>
                                            <li>
                                                <input type="radio" name="answers<?php echo $q ?>" value="B" <?php if($sender_answer[$q]=='B'){  ?> checked <?php ; } ?>/>
                                                <span><?php echo isset($quest['option_2'])?$quest['option_2']:''; ?></span>
                                            </li>
                                            <?php if(!empty($quest['option_3'])){ ?>
                                            <li>
                                                <input type="radio" name="answers<?php echo $q ?>" value="C" <?php if($sender_answer[$q]=='C'){ ?> checked <?php ; } ?>/>
                                                <span><?php echo $quest['option_3']; ?></span>
                                            </li>
                                            <?php }if(!empty($quest['option_4'])){ ?>
                                            <li>
                                                <input type="radio" name="answers<?php echo $q ?>" value="D" <?php if($sender_answer[$q]=='D'){  ?> checked <?php ; } ?>/>
                                                <span><?php echo $quest['option_4']; ?></span>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                            <?php $i++; }
                            } ?>
                        </div>
                    </div>
                    <div class="sender_receiver_box receiver_box">
                        <div class="sender_receiver_head">
                            <h2>Receiver</h2>
                            <div class="sender_receiver_user">
                                <?php
                                //$inUser = $this->Global->findReceiverInUser($receiver_email);
                              //  pr($inUser);die;
                                //if($inUser){
                               //     $profile=$inUser['profile_photo'];
                               // }else{
                                    $inReceiver = $this->Global->getUserofReceiver($receiver_email,$post['survey_id']);
                                    $profile=$inReceiver['profile_photo'];
                              //  }//echo $receiver_email;die;
                                //$receiver_details   = $this->Global->getReceiver($receiver_email);
                                    if(!empty($profile))
                                    {
                                ?>
                                        <a href="javascript:;"><?php echo $this->Html->image("user_images/".$profile,['style'=>'height: 100%;width: 100%;']); ?></a>
                                <?php }
                                else{ ?>
                                    <a href="javascript:;"><?php echo $this->Html->image("user_default.jpeg"); ?></a>
                                <?php } ?>
                            </div>
                            <div class="sender_receiver_title">
                                <a href="javascript:;"><?php echo $receiver_name; ?></a>
                            </div>
                        </div>
                        <div class="sender_receiver_list">
                            <?php
                                $i=1;
                                $receiver_answer =[];
                                $receiver_answer  = !empty($post['receiver_answers'])?(unserialize($post['receiver_answers'])):[];
                                
                                $questions = $post['question_id'];
                                $questions  = !empty($questions)?(unserialize($questions)):[];
                                if($questions){
                                foreach($questions as $q){
                                    $quest= $this->Global->getQuestionsNew($q);
                                 ?>
                                    <div class="sender_receiver_row">
                                        <h3><?php echo $i.".".$quest['question_text']; ?></h3> 
                                        <ul>
                                            <li>
                                                <input type="radio" name="recever_answers[<?php echo $q ?>]" value="A" <?php if($receiver_answer[$q]=='A'){ ?> checked <?php ; } ?>/>
                                                <span><?php echo isset($quest['option_1'])?$quest['option_1']:''; ?></span>
                                            </li>
                                            <li>
                                                <input type="radio" name="recever_answers[<?php echo $q ?>]" value="B" <?php if($receiver_answer[$q]=='B'){  ?> checked <?php ; } ?>/>
                                                <span><?php echo isset($quest['option_2'])?$quest['option_2']:''; ?></span>
                                            </li>
                                            <?php if(!empty($quest['option_3'])){ ?>
                                            <li>
                                                <input type="radio" name="recever_answers[<?php echo $q ?>]" value="C" <?php if($receiver_answer[$q]=='C'){ ?> checked <?php ; } ?>/>
                                                <span><?php echo $quest['option_3']; ?></span>
                                            </li>
                                            <?php }if(!empty($quest['option_4'])){ ?>
                                            <li>
                                                <input type="radio" name="recever_answers[<?php echo $q ?>]" value="D" <?php if($receiver_answer[$q]=='D'){  ?> checked <?php ; } ?>/>
                                                <span><?php echo $quest['option_4']; ?></span>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                            <?php $i++; } }?>
                        </div>
                    </div>
                </div>
                   
                <div class="answer_questions">
                    <div class="container">
                        <?php $comman_ans = $this->Global->getComplibilityscore($sender_answer,$receiver_answer);
                        //pr($comman_ans);die;
                        $len = count($comman_ans);
                        ?>
                        <h1>You chose the same answers to <?php echo $len; ?> out of <?php echo count($sender_answer); ?> questions.</h1>
                        <p>Your Matching Score is</p>
                        <?php $score = ($len/count($sender_answer))*100;
                        ?>
                        <span><?php echo round($score, 2); ?>%</span>
                    </div>
                   <!--<div class="add_survey_button">
                        <?php //echo $this->Html->Link("Pros & Cons",['controller'=>'Pages','action'=>'feedback']); ?>
                        <!--<input type="submit" value="Pros & Cons" />
                    </div>-->
                </div>
               
            </section>
        </div>
        <?php echo $this->element('home_footer'); ?>
    </div>
	<script>
function reportprint() {
        window.print();
    }s
</script>
</body>
</html>