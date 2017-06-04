
<body>
<div class="site_content">
<?php echo $this->element('home_header'); ?>
<div class="warper">
 <section class="inner_page_header">
  <h2>General Guidelines for Question Submission</h2>
 </section>
 
 <section class="help_content">
  <div class="container">
   <div class="help_list">
    <ul>
	 <li>
	  <h2></h2>
	  <p>1. Submit original questions.</p>
	 </li>
	 
	 <li>
	  <h2></h2>
	  <p>2. Base your questions on common sense or personal relational experiences. However, you may choose to add a silly or funny question to our Ice-Breaker Questions category.</p>
	 </li>
	 
	 <li>
	  <h2></h2>
	  <p>3. Questions should be gender-neutral.</p>
	 </li>
	
	 <li>
	  <h2></h2>
	  <p>4. Questions should not contain any racial, ethnic, or religious bias. Socioeconomic bias may be acceptable when questions are geared toward people with limited resources or people with substantial income. We expect questions to have cultural bias reflecting values of the western world and the US.</p>
	 </li>
     
	 <li>
	  <h2></h2>
	  <p>5. Allow your questions to have from two to four answer choices.</p>
	 </li>
     
	 <li>
	  <h2></h2>
	  <p> 6. Make sure that no answer choice sounds as obviously right or wrong. Each of the answer choices should appeal to a certain group of people.</p>
	 </li>
     <li>
	  <h2></h2>
	  <p>7. Your question will be reviewed, and if found in compliance with the guidelines, it will be added to Question Bank.</p>
	 </li>
     </ul>
   </div>
   <div class="add_survey_button">
                   <!--<a href="javascript:;">Add to survey</a>-->
                   <?php echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'addquestion']); ?>

                </div>
  </div>
 </section>
</div>

<?php echo $this->element('home_footer'); ?>

</div>
</body>
</html>