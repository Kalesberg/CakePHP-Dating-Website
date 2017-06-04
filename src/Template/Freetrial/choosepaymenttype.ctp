<body>
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
            <h2>Payment Method</h2>
        </section>
        <section class="forgot_password_content">
            <div class="dashboard_form_box">
                  <h1>Make Your Selection</h1><br/>
                <div class="dashboard_form_inner" style="width:900px !important;">
                     
                    <div class="dashboard_form_inner_sub">
                        <?php //echo $this->Flash->render() ?>
                        <?php echo $this->Form->create('User',['id'=>'payment']);
                        //echo $price;
                        //echo $survey_id;
                        //echo $membership; die;
                        ?>
                        <p>Your card will be charged $9.99 for Platinum Membership or $29.99 for Lifetime Membership<br/> AFTER YOUR FREE TRIAL EXPIRES<br /> unless you cancel your subscription.</p>
                     
                        <div class="form_fild_box terms_check">
                            
                            <?php echo $this->Form->input('amount',['value'=>$price,'class'=>'mainamount','type'=>'hidden']); ?>

                           <label><a href="javascript:;" class="showpromo">I have a promo code.</a></label> 
                        </div>
                        <div class="form_fild_box promocodebox" style="display: none;">
                            <label>Promocode*</label>
                            <div >
                                 <input type="text" class="promocodeValue" placeholder="Enter Promocode"/>
                            </div>
                            <div class="col-md-6 add_survey_button">
                                <a href="javascript:;" class="promocodeApply">Apply</a>
                            </div>
                        </div>
						
                        <div class="form_fild_box">
                          
                        </div>
                       
                        <div class="simple_advanced_button">
                            <ul>
                                <li>
                                    <a href="<?php echo SITE_URL.'freetrial/remainvisitor/' ?>">I Choose to Remain Visitor</a></li>
                                <li><a href="<?php echo SITE_URL.'freetrial/paymentpage/?plan=platinum' ?>" class="newpriceBaseStrip">PLATINUM MEMBERSHIP</a>
                                    </li>
								<li><a href="<?php echo SITE_URL.'freetrial/paymentpage/?plan=lifetime' ?>" class="newpriceBaseStrip">LIFETIME MEMBERSHIP</a>
                                    </li>
                            </ul>
						</div>
                        <?php echo $this->Form->end(); ?>
                      
                    </div>	
                </div>
            </div>
        </section>
    </div>
<?php echo $this->element('home_footer'); ?>
</div>
<script>
   $(function(){
    
    $('.paypalpayment').click(function(){
	$('#payment').submit();
    });
    
      $('.simple').click(function(){
        $('#surveytype').val('1');
    });
    $('.advanced').click(function(){
        $('#surveytype').val('2');
    });
    $(".showpromo").click(function(){
        $(".promocodebox").css("display",'block');
        });
   $('.promocodeApply').click(function(){
      $('.showpromo').hide();
	var promocode = $('.promocodeValue').val();

      if(promocode){
	 $('.showpromomsg').remove();
	 $.ajax({
	    type:'POST',
	    data:{promocode:promocode},
	    url:"<?php echo SITE_URL.'freetrial/checkPromocode';?>",
	    success:function(data) {
	       var datas = jQuery.parseJSON(data);
	       if(datas['status'] == 1){
		
		  var msg = datas['msg'];
		
		 var newpriceBaseStrip = datas['newpriceBaseStrip'];
		  var showmsg = "<label class='showpromomsg' style='color:green'>"+msg+"</label>";
		  $('.terms_check').append(showmsg);
	
		   $('.showpromo').remove();
		   $('.form_fild_box.promocodebox').remove();
	
		  $('.newpriceBaseStrip').attr('href',newpriceBaseStrip); 
	       }else{
		  var msg = datas['msg'];
		  var showmsg = "<label class='showpromomsg' style='color:red'>"+msg+"</label>";
		  $('.terms_check').append(showmsg);
		  
	       }
	    }
	 });
      }
   });
   // $(".formSubmit").click();
});
</script>
</div>
</body>
</html> 
