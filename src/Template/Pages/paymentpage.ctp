<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
     
        <div class="warper">
            <section class="inner_page_header">
				<?php /* if($amount){ ?>
					<h2>payment For gold Membership</h2>
				<?php }else if($amount==2){ ?>
				<h2>payment For gold Membership</h2>
				<?php }else{ */?>
                <h2>payment information</h2>
				
            </section>
            <section class="forgot_password_content">
									
                <div class="dashboard_form_box">
					<?php echo $this->Flash->render(); ?>
                    <div class="dashboard_form_inner">
                        <div class="payment-errors"></div>
                        <div class="dashboard_form_inner_sub">
                            <?php echo $this->Form->create('Payment',['id'=>'paymentid']); ?> 
                            <div class="cards_accepted_box">
                                <h2>Cards Accepted</h2>
                                <div class="cards_accepted_list">
                                    <ul>
                                        <li>
                                            <a href="javascript:;">
                                             <input type="radio" name="card_type" required="required"/>
                                             <span><?php echo $this->Html->image('card01.png'); ?></span>
                                            </a>  
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                             <input type="radio" name="card_type" required="required"/>
                                             <span><?php echo $this->Html->image('card02.png'); ?></span>
                                            </a>  
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                             <input type="radio" name="card_type" required="required" checked="checked"/>
                                             <span><?php echo $this->Html->image('card03.png'); ?></span>
                                            </a>  
                                        </li>
                                        <li>
                                            <a href="javascript:;">
                                             <input type="radio" name="card_type" required="required"/>
                                             <span><?php echo $this->Html->image('card04.png'); ?></span>
                                            </a>  
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form_fild_box">
                                <label>Card Number :</label>
                                <?php echo $this->Form->input('card_number',['class'=>'card-number','placeholder'=>'Card Number','label'=>false]); ?>
                            </div>
                            
                            <div class="two_fild_box">
                                <div class="left_fild_box">
                                    <div class="form_fild_box">
                                        <label>Year:</label>
                                        <select class="card-expiry-year" name="expiry_year">
                                            <?php
                                            $i=2010;
                                            while($i <= 2080){ ?>
                                                 <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                         <?php $i++;  }
                                            ?>
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="right_fild_box">
                                    <div class="form_fild_box">
                                        <label>Month :</label>
                                        <select class="card-expiry-month" name="expiry_month">
                                            <?php
                                            $i=1;
                                            while($i <= 12){ ?>
                                                 <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php $i++;  }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form_fild_box">
                                <label>CVV :</label>
                                <?php echo $this->Form->input('cvv',['class'=>'card-cvc','placeholder'=>'CVV','label'=>false]); ?>
                                
                            </div>
                            <div class="form_fild_box">
                                <label>Name :</label>
                                <?php echo $this->Form->input('name',['class'=>'card-holder-name','placeholder'=>'Name','label'=>false]); ?>
                        
                            </div>
                            <div class="form_fild_box">
                                <label>Country :</label>
                                <?php
                             //   $countries=$this->Global->getCountry();
                                
                                echo $this->Form->select('country',$countries,['class'=>'countryId','label'=>false]);  ?>
                                
                            </div>
                            <div class="form_fild_box">
                                <label>State :</label>
                                <?php echo $this->Form->select('state',$states,['class'=>'stateId','empty'=>'Choose State','label'=>false]); ?>
                             
                            </div>
                            
                            <div class="form_fild_box">
                                <label>City :</label>
                                <?php echo $this->Form->select('city',$cities,['class'=>'cityId','empty'=>'Choose City','label'=>false]); ?>

                            </div>
                            <div class="form_fild_box">
                                 <label>Address :</label>
                                  <?php echo $this->Form->input('address',['class'=>'address','placeholder'=>'Address','label'=>false]); ?>
                        
                            </div>
                            
                            
                            
                           
                            <div class="form_fild_box">
                                <label>Zip Code :</label>
                                <?php echo $this->Form->input('zip',['class'=>'zip','placeholder'=>'Zip Code','label'=>false]); ?>
                            
                            </div>
                      
                            <div class="form_fild_box">
                                <label>Phone Number :</label>
                                <?php echo $this->Form->input('phone',['class'=>'phone','placeholder'=>'Phone Number','minlength'=>'10','maxlength'=>'12','label'=>false]); ?>
                            
                            </div>
                            
                            <div class="form_fild_box">
                                <label>Email Address :</label>
                                <?php echo $this->Form->input('email',['class'=>'email','placeholder'=>'Email Address','label'=>false]); ?>
                            
                            </div>
                            <div class="form_submit_button">
                                <?php //echo $this->Html->Link("Submit",[],['id'=>'sendcreditcard','class'=>'form_submit_button','type'=>'button']); ?>

                                <input type="button" id="sendcreditcard" value="submit"/>
                            </div>
                        </div>	
                    </div>
                </div>
            </section>
         
        </div>

    <?php echo $this->element('home_footer'); ?>
    </div>
 <script type="text/javascript">
	    
$(document).ready(function() {
   
   $('#sendcreditcard').click(function(){
 
   $('#sendcreditcard').attr("disabled",true);
    Stripe.card.createToken({
                        number: $('.card-number').val(),
                        cvc: $('.card-cvc').val(),
                        exp_month: $('.card-expiry-month').val(),
                        exp_year: $('.card-expiry-year').val(),
			name: $('.card-holder-name').val(),
			address_line1: $('.address').val(),
			address_city: $('.city').val(),
			address_zip: $('.zip').val(),
			address_state: $('.state').val(),
			address_country: $('.country').val(),
           // email: $('.email').val(),
           // phone: $('.phone').val()
            
                    }, stripeResponseHandler);
    
      return false;
      
      
   });
});
 

</script>
<script type="text/javascript">
            // this identifies your website in the createToken call below
            Stripe.setPublishableKey('pk_test_orHFrFWMVJ3UEpAot0egQp40');
 
            function stripeResponseHandler(status, response)
	    {
	       if (response.error)
	       {
		  $('#sendcreditcard').removeAttr("disabled");
		  $(".payment-errors").html(response.error.message);
		  $(".payment-errors").show();
	       }
	       else
	       {
		  
		  var form$ = $("#paymentid");
		  // token contains id, last4, and card type
		  var token = response['id'];
		  //alert(token);
		  // insert the token into the form so it gets submitted to the server
		  form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
		  // and submit
		  form$.get(0).submit();
               }
            }
 
$(".countryId").change(function(){
    var countryId = $('.countryId').val();
    //alert(countryId);
        $.ajax({
           type:'POST',
           data:{countryId:countryId},
           url:"<?php echo SITE_URL.'Users/states';?>",
           success:function(data) {
            var datas = [];
             datas = jQuery.parseJSON(data);
            var selectoption = "<option value=''>Choose option</option>";
            $('.stateId').html(selectoption);
            for (var i = 0; i < datas.length; i++) {
               var option = '<option value="' + datas[i]['id'] + '">' + datas[i]['name'] + '</option>';
               $('.stateId').append(option);
            }
           }
        });
    });
  $(".stateId").change(function(){
    var stateId = $('.stateId').val();
    //alert(stateId);
        $.ajax({
           type:'POST',
           data:{stateId:stateId},
           url:"<?php echo SITE_URL.'Users/cities';?>",
           success:function(data) {
            var datas = [];
             datas = jQuery.parseJSON(data);
            var selectoption = "<option value=''>Choose option</option>";
            $('.cityId').html(selectoption);
            for (var i = 0; i < datas.length; i++) {
               var option = '<option value="' + datas[i]['id'] + '">' + datas[i]['name'] + '</option>';
               $('.cityId').append(option);
            }
           }
        });
    });
</script>
   
</body>
</html>