
<body>
<div class="site_content">
<!--header-content-here-->
<?php echo $this->element('home_header'); ?>
 
<div class="warper">
 <section class="inner_page_header">
  <h2>My Saved Survey</h2>
 </section>
 
 <section class="dashboard_content">
  <div class="dashboard_left_box">
     <?php echo $this->element('dashboard_menu'); ?>
  </div>
  
  <div class="dashboard_right_box">
   <div class="payment_history_table">
    <table>
    <?php if($post){ ?>
	 <tr>
        <th>S.No.</th>
        <th>Survey Name</th>
         <!--<th>Receiver Name</th>-->
		  <th>Date</th>
        
        <th>Action</th>
	 </tr>
     <?php
     $i=1;
     
     foreach($post as $val){
        //hb$partner=$this->Global->getSurveyAnswers($val->id);
            
        ?>
        <tr>
            <td><?php echo $i; //echo $this->html->link("Survey".$i,['controller'=>'Pages','action'=>'survey',base64_encode($val->id),base64_encode($val->status)]); ?></td>
            <td><?php echo ucfirst($val->survey_name); ?></td>
            <?php //$receiver=$this->Global->getReceiverEmail($val->user_id,$val->id); echo $receiver['email']; ?>
            <td><?php echo date('m-d-Y',strtotime($val->created)); ?></td>
	
            <td>
                <span style="margin-right:3px;">
                    <?php
                    echo $this->Html->link(($this->html->image("eye.png")),['controller'=>'Pages','action'=>'savedsurvey',base64_encode($val->id)],['class'=>'del_button','escape'=>false])
                    ?>
                </span>
				<!--<a href="javascript:;" class="del_button" data-id="<?php echo $val->id; ?>"><?php //echo $this->html->image("edit.png"); ?></a>-->
				<span style="margin-right:3px;">
				<a href="javascript:;" class="del_button btnDelete" data-id="<?php echo $val->id; ?>"><?php echo $this->html->image("del.png"); ?></a>
                </span>
               
            </td>
        </tr>
    <?php $i++; } }else{
	?>
	<tr>
	    <td>You do not have any saved surveys yet.</td>
	</tr>
	<?php    
    } ?>
	
	</table>
   </div>
  </div>
 </section>
 
</div>

<!--footer-content-here-->
<?php echo $this->element('home_footer'); ?>
<script>
    $('.btnDelete').click(function(){
         var id = $(this).data('id');
         if(confirm('Do you want to delete?')){
            $.ajax({
               type:'POST',
               data:{id:id},
               url:"<?php echo SITE_URL.'Pages/deleteSurvey';?>",
               success:function(data) {
               }
            });
            $(this).closest('tr').remove();
         }
         
      });
</script>
</div>
</body>
</html>