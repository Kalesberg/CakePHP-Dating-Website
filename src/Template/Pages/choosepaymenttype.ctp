<body>
<div class="site_content">
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
            <h2>Payment Method</h2>
        </section>
        <section class="forgot_password_content">
            <div class="dashboard_form_box">
                <div class="dashboard_form_inner">
                    <div class="dashboard_form_inner_sub">
                        <?php echo $this->Flash->render() ?>
                        <?php echo $this->Form->create('User',['id'=>'payment']);
                        //echo $price;
                        //echo $survey_id;
                        //echo $membership
                        ?>
                        <h2><?php echo ucwords(str_replace("_"," ",$membership))." Member"; ?></h2>
                        <div class="form_fild_box terms_check">
                            <label>Amount($)</label>
			    
                        <?php echo $this->Form->input('amount',['value'=>$price,'placeholder'=>'Amount','class'=>'mainamount','required'=>'required','label'=>false,'disabled'=>'disabled']); ?>
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
                        <?php echo $this->Form->end(); ?>
                        <div class="simple_advanced_button">
                            <ul>
                             
                                <li style="width:100%" ><a href="<?php echo SITE_URL.'pages/upgrade/'.base64_encode($membership)."/".base64_encode($price); ?>" class="newpriceBaseStrip">Pay WITH CREDIT CARD</a>
                                </li>
                            </ul>
						</div>
                      
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
      var mainPrice = '<?php echo $price; ?>';
       var membership = '<?php echo $membership; ?>';
      if(promocode){
	 $('.showpromomsg').remove();
	 $.ajax({
	    type:'POST',
	    data:{promocode:promocode,mainPrice:mainPrice,membership:membership},
	    url:"<?php echo SITE_URL.'Pages/applypromocode';?>",
	    success:function(data) {
	       var datas = jQuery.parseJSON(data);
	       if(datas['status'] == 1){
		  var newprice = datas['newprice'];
		  var msg = datas['msg'];
		  var newpriceBase = datas['newpriceBase'];
		  var newpriceBaseStrip = datas['newpriceBaseStrip'];
		  var showmsg = "<label class='showpromomsg' style='color:green'>"+msg+"</label>";
		  $('.terms_check').append(showmsg);
		  $('.mainamount').val(newprice);
		   $('.showpromo').remove();
		   $('.form_fild_box.promocodebox').remove();
		 // $('.paypalpayment').attr('href',newpriceBase); 
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
