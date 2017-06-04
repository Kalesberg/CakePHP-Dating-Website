
<body>
<div class="site_content">
<!--header-content-here-->
<?php echo $this->element('home_header'); ?>
 
<div class="warper">
 <section class="inner_page_header">
  <h2>Payment History</h2>
 </section>
 
 <section class="dashboard_content">
  <div class="dashboard_left_box">
     <?php echo $this->element('dashboard_menu'); ?>
  </div>
  
  <div class="dashboard_right_box">
   <div class="payment_history_table">
       <div class="rightbox">
	    <span>Your current plan : <?php echo $membershipDetails['membership_name']; ?></span>.   
	    <span>Your current balance: <?php echo $balances; ?></span> 
      </div>
    <table>
<?php	if($post){ ?>
	 <tr>
	     <th style="width: 5%">S.No.</th>
	     <th style="width: 10%">Date</th>
	     <th style="width: 10%">Amount</th>
	     <th style="width: 10%">Check NO.</th>
	 </tr>
     <?php
	
		$i=1;
		foreach($post as $val){ ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo date('m-d-Y',strtotime($val->date)); ?></td>
				<td><?php echo "$".($val->amount)/100; ?></td>
				<td><?php echo $val->balance_transaction; ?></td>
			</tr>
			
	<?php $i++; }
	}
	    else{
                                ?>
                                <tr>
	    <td>You do not have any history yet.</td>
	</tr>
                                <?php    
                            }
	 ?>
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