
<body>
<div class="site_content">
<!--header-content-here-->
<?php echo $this->element('home_header'); ?>
 
<div class="warper">
 <section class="inner_page_header">
  <h2>My Compatibility Reports</h2>
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
        <th>Receiver Name</th>
        <th>Receiver Email</th>
		<th>Date</th>
        <th>Action</th>
	 </tr>
     <?php
     $i=1;
     
     foreach($post as $val){
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php $receiver=$this->Global->getReceiverNew($val->receiver_email,$val->user_id,$val->survey_id); echo ucfirst($receiver['name']); ?></td>
            <td><?php echo $val->receiver_email; ?></td>
            <td><?php echo date('m-d-Y',strtotime($val->created)); ?></td>
            <td>
				
				<span style="margin-right:3px;">
                    <?php
                    echo $this->Html->link(($this->html->image("eye.png")),['controller'=>'Pages','action'=>'compatibilityreport',base64_encode($val->receiver_email),base64_encode($val->user_id),base64_encode($val->survey_id)],['class'=>'del_button','escape'=>false]);
                      ?>
                </span>
                <span style="margin-left:3px;">
                    <a href="<?php echo SITE_URL."Pages/deleteReport/".base64_encode($val->user_id)."/".base64_encode($val->survey_id)."/".base64_encode($val->id); ?>" class="del_button btnDelete" ><?php echo $this->html->image("del.png"); ?></a>
                </span>
            </td>
        </tr>
    <?php $i++; } }else{
	?>
	<tr>
	    <td>You do not have any reports yet.</td>
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
         if(confirm('Do you want to delete?')){
            return true;
         }else{
            return false;
         }
         
      });
</script>
</div>
</body>
</html>