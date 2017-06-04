<body>
    
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="slider_bg_section">
         <div class="flexslider">
          <ul class="slides"> 
           <li><?php echo $this->Html->image('slider_banner.jpg'); ?></li>
           <div class="slider_content animatedParent">
            <div class="container">
             <div class="now_free_button animated bounceInUp">
              <?php echo $this->Html->Link('Try it now <span>FREE</span>',['controller'=>'Freetrial','action'=>'freeregister'],['escape'=>false]); ?>
             </div>
            </div>
           </div>
          </ul>
         </div>
         
        </section> 
        
        <section class="welcome_company_section">
         <div class="container">
          <div class="welcome_company_head animatedParent">
           <h2 class="animated growIn slow">Welcome to <span> Self-Match</span></h2>
           <h1 class="animated growIn slow">Matchmaking done YOUR way </h1><br/>
           <p> Self-Match is a unique tool for exploring compatibility between partners. It offers unprecedented flexibility and total user control over the matching process. The self-matching approach is delivered through the comparison of partners’ independent responses to custom-built surveys. <br/> 
            Self-Match appeals to all kinds of people looking for successful relationships, seeking to improve their current relationships, or those trying to decide if their current relationship is worth holding on to. </p>
          </div>
          
          <div class="welcome_company_des animatedParent">
           <p class="animated bounceInLeft slow"><strong>Shy</strong> and <strong>fun-loving</strong> people may use Ice-Breaker and Easy-Match questions for a light-weight approach.
            <strong>Thoughtful</strong> people may compile custom surveys from questions that matter most to them.
            <strong>Systematic</strong> people may create their own matchmaking approach starting with fun questions and diving into more serious research later.
           </p>  
           <p class="animated bounceInRight slow">
                <strong>Bold </strong>people may choose to start with Deal-Breaker questions, quickly eliminating unsuitable partners.
                <strong>Risk-taking</strong> people may use the Random Question Challenge allowing the app to generate a survey based on the categories and the number of questions they choose.
                Regardless of what kind of person you are, you can mold the resources available at Self-Match to meet your own unique needs.
           </p>
          </div>
          
          <div class="welcome_button_box animatedParent">
           <ul>
            <li class="animated bounceInLeft slow">
                  <?php echo $this->Html->link("Browse Question Bank",['controller'=>'Pages','action'=>'questionbank']); ?>
            
            </li>
            <li class="animated growIn slow">
                <?php echo $this->Html->link("See Self-Match Demo",['controller'=>'Pages','action'=>'selfmatchdemo']); ?>
            
            </li>
            <li class="animated bounceInRight slow">
                <?php echo $this->Html->link("Contribute a Question",['controller'=>'Pages','action'=>'addquestion']); ?>
            
            </li>
           </ul>
          </div>
         </div>
        </section>
        
        <section class="self_match_section">
         <div class="container">
          <div class="self_match_inner">
           <div class="self_match_head animatedParent">
            <h2 class="animated growIn slow">Self-Match offers a unique approach to exploring compatibility 
            with your <span>Partner</span> and a simple decision-making guide 
            to choosing the <span>Right</span> partner.
            </h2>
           </div>
          
        <div class="self_match_list animatedParent">
            <ul>
                <?php $menu = $this->Global->getMenu(HOME);
                foreach($menu as $val){
                    ?>
                    <li class="animated bounceInLeft slow">
                        <h4><?php
                        if($val->slug=='tips_on_using_self_match'){
                            $id="tips";
                            echo $val->title;
                            //echo $this->Html->link($val->title,SITE_URL."welcome_visitor.html#".$id);
                        }
                        else if($val->slug=='self_match_in_ a_nut_shell'){
                            $id="nutshell";
                             echo $val->title;
                            //echo $this->Html->link($val->title,SITE_URL."welcome_visitor.html#".$id);
                        }else{
                             echo $val->title;
                           // echo $this->Html->link($val->title,SITE_URL."welcome_visitor.html");
                        }
                        //echo $this->html->link();
                        ?></h4>
                        <p><?php
                        $description = explode('.',$val->meta_description);
                        echo $description[0]."."; ?><br/>
                        <?php echo  $this->Html->link("Read More..",SITE_URL."welcome_visitor.html",['style'=>'color:#3bbbeb;','escape'=>false]); ?>
                      
                        </p>
                    </li>
                    
                <?php }
                ?>
             <!--<li class="animated bounceInLeft slow">
              <h4>1.Who Should Use Self-Match</h4>
              <p>Any person who has a prospective or current partner may find certain benefits in using Self-Match.<br/>
            
                </p>
             </li>
             
            <li class="animated bounceInRight slow">
             <h4>2.Benefits of Using Self-Match</h4>
             <p>Puts partners in control of the matching process.
                Reduces the time partners need to learn more about each other. </p>
            </li>
            
            <li class="animated bounceInLeft slow">
             <h4>3.Tips on Using Self-Match</h4>
             <p>Choose your level of engagement – from a single short survey to in-depth compatibility research based on multiple surveys.</p>
            </li>
            
            <li class="animated bounceInRight slow">
             <h4>4.Self- Match app in a Nutshell</h4>
             <p>The user compiles a custom survey from questions available at the Question Bank. </p>
            </li>-->
            </ul> 
        </div>
          
          </div>
         </div>
        </section>
        
        <section class="self_match_works_section">
         <div class="container">
          <div class="self_match_work_head animatedParent">
           <h2 class="animated growIn slow">How Self-Match <span>Works</span></h2>
          </div>
         
          <div class="self_stap_contet">
           <div class="self_box1 animatedParent">
            <h6 class="animated bounceInDown slow">The user compiles <br/> the survey</h6>
            <div class="self_icon_box animated bounceInUp slow">
              <a href="javascript:;"><?php echo $this->Html->image('self_icon01.png'); ?></a>
            </div>
           </div>
           
           <div class="self_box2 animatedParent">
            <div class="self_icon_box animated bounceInDown slow">
             <a href="javascript:;"><?php echo $this->Html->image('self_icon02.png'); ?></a>
            </div>
            <h6 class="animated bounceInUp slow">The user takes<br/> the survey</h6>
           </div>
           
           <div class="self_box3 animatedParent">
            <h6 class="animated bounceInDown slow">The user sends the blank<br/> survey to the partner</h6>
            <div class="self_icon_box animated bounceInUp slow">
            <a href="javascript:;"><?php echo $this->Html->image('self_icon03.png'); ?></a>
            </div>
           </div>
           
           <div class="self_box4 animatedParent">
            <div class="self_icon_box animated bounceInDown slow">
             <a href="javascript:;"><?php echo $this->Html->image('self_icon04.png'); ?></a>
            </div>
            <h6 class="animated bounceInUp slow">The partner takes<br/> the survey</h6>
           </div>
           
           <div class="self_box5 animatedParent">
            <h6 class="animated bounceInDown slow">Both partners can see <br/>the Compatibility Report</h6>
            <div class="self_icon_box animated bounceInUp slow ">
            <?php echo $this->Html->image('self_icon05.png'); ?>
           </div>
          </div>
         </div>
        </section>
      
        <section class="clients_video_section animatedParent">
         <div class="testimonial_box animated bounceInLeft slow">
          <div class="testimonial_head animated growIn slowest">
           <h2>Our<span> Clients </span> Says</h2>
          </div> 
          
          <div class="testimonial_client_box animated growIn slowest">
		   
		   <div class="flexslider">
		   <ul class="slides">
		   <li>
           <div class="testimonial_pic">
            <a href="<?php echo SITE_URL."Jessica.html"; ?>"><?php echo $this->Html->image('clients/Jessica.png'); ?></a>
           </div>
           <div class="testimonial_des">
            <p>When I found out about Self-Match.com,
             <br/> I thought it might be interesting to try it with my new boyfriend...
              <br/>to find out if he is the right guy for me. 
            </p>
           </div>
           <div class="tes_username">
            <a href="<?php echo SITE_URL."Jessica.html"; ?>">Jessica B., Bronston, Kentucky, USA</a>
           </div> 
           </li>
		   
		   <li>
           <div class="testimonial_pic">
            <a href="<?php echo SITE_URL."Michael.html"; ?>"><?php echo $this->Html->image('clients/Michael.png'); ?></a>
           </div>
           <div class="testimonial_des">
            <p>Use the self-match today and save yourself some future troubles. 
            </div>
           <div class="tes_username">
            <a href="<?php echo SITE_URL."Michael.html"; ?>">Michael M, California, USA</a>
           </div> 
           </li>
		   
		   <li>
           <div class="testimonial_pic">
            <a href="<?php echo SITE_URL."Nicky.html"; ?>"><?php echo $this->Html->image('clients/Nicky.png'); ?></a>
           </div>
           <div class="testimonial_des">
            <p>My 'prospective' partner is a sweet guy I met a couple of months ago while traveling in Indonesia.
           <br/> He is also from the Netherlands and we connected so well in that country I call paradise. 
            
            
           </div>
           <div class="tes_username">
            <a href="<?php echo SITE_URL."Nicky.html"; ?>">Nicky W.B., Netherlands </a>
           </div> 
           </li>
		   
		   </ul>
		  </div>
		  
		  </div>
          </div>
         <div class="video_box">
            <div >
            <ul >
                <li>
				    <div class="testimonial_head animated growIn slowest">
				     <h2>Nothing says<span> better </span> then a video</h2>
				    </div> 
                    
                    <div class="video_inner animated growIn slowest">
                     <a href="javascript:;">
                        <video width="500" height="282" controls>
                        <source src="<?php echo SITE_URL.'video/MeetFred.mp4'; ?>" type="video/mp4">
                        </video>
                     </a>
					 <br/>
					 <br/>
					 <a href="javascript:;">
                        <video width="500" height="282" controls>
                        <source src="<?php echo SITE_URL.'video/FMLtkoles1.mp4'; ?>" type="video/mp4">
                        </video>
                     </a>
					 
                    </div>          
                </li>
               <!-- <li>

                    <h2 class="animated growIn slowest">Nothing says better,<br/> then a video</h2>
                    <div class="video_inner animated growIn slowest">
                   <a><video width="320" height="240" controls>
                        <source src="<?php echo SITE_URL.'video/SMVideo1.mp4'; ?>" type="video/mp4">
                      </video></a> 
                    </div>          
                </li>-->
            </ul>
            </div>
          
         </div>
        </section>
    <?php echo $this->element('home_footer'); ?>
</div>
   </div>
</body>
</html> 
