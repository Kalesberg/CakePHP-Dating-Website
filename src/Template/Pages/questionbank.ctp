<?php
$user = $this->request->session()->read('Auth.User'); ?>
<body>
    <div class="site_content">
        <!--header-content-here-->
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>Question Bank</h2>
            </section>
            <section class="question_bank_content">
                <div class="container">
                    <div class="question_bank_head">
                        <div class="question_bank_head_title">
                            <h2>Question Categories</h2>
                        </div>   
                        <div class="search_categories_box">
                            <form action="questionbank" method="post">
                                <div class="search_categories_input">
                                    <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>"/>
                                    <input type="text" name="searchvalue" value="" placeholder="Enter Keyword/Phrase"/> 
                                </div>
                                <div class="search_button">
                                    <input type="submit" value="search"/>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="search_categories_list">
                        <ul>
                            <?php
                            echo $this->Flash->render();
							$user= $this->Global->getUser($user['id']);
							$membership     = $user['membership_level'];
							$survey_type    = $user['survey_type'];
                            $memberships= $this->Global->getMembership($membership);
                            //pr($memberships);die;
                            foreach($post as $val){
                             ?>   
                            <li>
                                <div class="categories_box">
                                    <div class="categories_box_inner">
                                        <div class="categories_box_image">
                                            <a href="javascript:;"><?php if(!empty($val->category_icon)){ echo $this->Html->image($val->category_icon); }else{ echo $this->Html->image("small-bg10.jpg"); } ?></a>
                                        </div>
                                        <div class="categories_over_box">
                                            <div class="categories_over_inner">
                                                <h4><?php echo ucfirst($val->category_name); ?></h4>
                                                <div class="view_questions">
						   	
                                                   <?php
                                                        echo $this->Html->Link('View questions',
														['controller'=>'Pages',
														'action'=>'questionsdetails',base64_encode($val->id),base64_encode($survey_id)]); 
                                                        
                                                     ?>
                                                    <!--<a href="javascript:;">View questions</a>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>		
                                </div>
				 
                            </li>
                            <?php } ?>
			    
                        </ul>
                    </div>
					<div class="alert alert-danger" id="error"  style="display: none">
						<button data-dismiss="alert" class="close" type="button">x</button>
						<span class="entypo-thumbs-down"></span>
						<strong></strong>&nbsp;&nbsp;Please purchase a membership to view the questions of this category. 
					</div>
                    <div class="alert alert-danger" id="errorRandom"  style="display: none">
						<button data-dismiss="alert" class="close" type="button">x</button>
						<span class="entypo-thumbs-down"></span>
						<strong></strong>&nbsp;&nbsp;Please become a Member to use this feature. 
					</div>
				    <div class="random_question_button">
                    <?php if(isset($user)){
                        $memberhip=$this->Global->getMembership($user['membership_level']);
                        if($memberhip['slug']!='visitor'){
                            echo $this->Html->Link("Favorite Questions",['controller'=>'Pages','action'=>'favouritelist'],['style'=>'margin-right:15px;']);
                            echo $this->Html->Link("Random Question Challenge",['controller'=>'Pages','action'=>'randomquestionchallenge']);
                        }else{
                            echo $this->Html->Link("Create New Survey",['controller'=>'Pages','action'=>'choosesurvey'],['style'=>'margin-right:15px;']);
                            echo $this->Html->Link("Random Question Challenge",['controller'=>'Pages','action'=>'randomquestionchallenge'],['class'=>'random']);
                        } } ?>
					</div> 
				</div>
            </section>
        </div>
        <?php echo $this->element('home_footer'); ?>
	<script>
	    $('.viewquestion').click(function(){
		$("#error").css('display','block');
		
		});
        $('.random').click(function(){
		$("#errorRandom").css('display','block');
		return false;
		});
	</script>
    </div>
</body>
</html>