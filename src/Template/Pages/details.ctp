<?php
$user = $this->request->session()->read('Auth.User'); ?>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>User guide</h2>
            </section>
            <div class="services_detail_content">
            <div class="welcome_selfmatch">
                <!--<div class="container"> 
                    <h1>Welcome to Self-Match!</h1>
                    <p>Self-Match offers a unique approach to exploring compatibility 
                    <br/>with your <span>Partner</span> and a simple decision-making guide 
                    <br/>to choosing the Right partner.</p>
                </div>-->
            </div>
  
            <div class="nutshell_review_box">
                <div class="nutshell_box">
                    <h2>WHO SHOULD USE SELF-MATCH</h2>


                    <ul>
                        <li> Any person who has a prospective or current partner may find certain benefits in using Self-Match.
                            Below are the groups of people who may benefit the most:</li> 
                        <li>People who date online, receive multiple matches and find it hard to manage and prioritize potential partners.</li>
                        <li>People in early dating stages who can’t decide if they are pursuing the right relationship.</li>
                        <li>People who have past failed relationships and who want to be more careful and thoughtful about approaching new relationships.</li>
                        <li>Partners in current relationships who have trouble communicating about various issues.</li>
                        <li>Partners who want to learn more about each other.</li>
                        <li>Partners who want to boost or spice up their communication.</li>
                       
                    </ul>
                </div>

                <div class="review_box">
                    <div class="get_paid_head"> 
                        <h2>Benefits of Using Self-Match</h2>    
                        <span></span>
                    </div>
                    <div class="conditions_box">
                        <h2></h2>
                        <ul>
                            <li>Puts partners in control of the matching process.</li>
                            <li>Reduces the time partners need to learn more about each other.</li>
                            <li>Allows for objective compatibility research that can be customized in many ways.</li>
                            <li>Helps partners to spot red-flags and potential areas of conflict.</li>
                            <li>Provides an easy way for making decisions regarding partner’s suitability by calculating a TrueMatch score and comparing it to the scores of other partners.</li>
                            <li>Boosts communication between current or potential partners.</li>
                            <li>Offers convenience of managing data for multiple partners.</li>
                           
                        </ul>
                    </div>
                    
                </div>
                <div class="review_box">
                    <div class="get_paid_head"> 
                        <h2>Tips on Getting Better Results with Self-Match.</h2>    
                        <span></span>
                    </div>
                    <div class="conditions_box">
                            
                       
                        <ul>
                            <li>Choose your level of engagement – from a single short survey to in-depth compatibility research based on multiple surveys.
                            Create your surveys using questions from multiple categories.</li>
                            <li>Choose questions that
                                <ul>
                                    <li>are somewhat important to you</li>
                                    <li>you don’t know what your partner’s responses might be</li>
                                    <li>could be red-flag areas of potential conflict or incompatibility</li>   
                                </ul>
                            </li>
                            <li>Encourage your partner to create and send you his/her own surveys.</li> 
                            <li>Do not treat a low matching answers score as a verdict of incompatibility; rather use it as an encouragement for further exploration.</li>
                            <li>Follow up with TrueMatch Score Calculator.</li>
                            <li>Be honest and thoughtful in your responses and the ratings of your partner’s responses.</li>
                            <li>For best results, use our Recipe for Choosing the Right Partner</li>
                            <li>Take time to discuss the Compatibility Report(s) with your partner, focusing on your differences and if they are something you can compromise on.</li>
                        </ul>
                    </div>
                    
                  
                </div>
                <div class="nutshell_box" style="height:787px;">
                    <h2>SELF-MATCH APP IN A NUTSHELL</h2>
                   
                    <ul>
                        <li>The user compiles a custom survey from questions available at the Question Bank.</li>
                    <li>The user answers these questions and sends the same blank survey to his/her partner.</li> 
                    <li>The partner takes the survey.</li> 
                    <li>Both the user and the partner can view the Compatibility Report showing their answers side by side and the matching percentage of the same answers.</li>
                    <li>The user who chooses to use the TrueMatch Scores Calculator may analyze the partner’s responses by labeling them Positive or Negative and assigning the importance rating to each response on the scale from 1 to 10. </li>
                    <li>The program will calculate the running TrueMatch Score for the partner.</li>
                    </ul>
                </div>
            </div>
 
           
        
    </div>
    </div>
    <?php echo $this->element('home_footer'); ?>
    </div>
    <?php //echo $site_url=SITE_URL; ?>

</body>

</html>