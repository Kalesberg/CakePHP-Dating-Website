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
                <h2>Paypal Payment</h2>
				
            </section>
            <section class="forgot_password_content">
									
                <div class="dashboard_form_box">
					<?php echo $this->Flash->render(); ?>
                    <div class="dashboard_form_inner">
                        <div class="payment-errors"></div>
                        <div class="dashboard_form_inner_sub">
                            <?php echo $this->Form->create('Payment',['id'=>'paymentid']);
			    echo $this->Form->hidden('amount',['value'=>$amount]);
			    ?> 
                            <div class="form_fild_box">
                                <label>Card Number :</label>
                                <?php echo $this->Form->input('creditcard_number',['class'=>'card-number','placeholder'=>'Card Number','label'=>false]); ?>
                            </div>
                            
                            <div class="two_fild_box">
                                <div class="left_fild_box">
                                    <div class="form_fild_box">
                                        <label>Year:</label>
                                        <select class="card-expiry-year" name="creditcard_year">
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
                                        <select class="card-expiry-month" name="creditcard_month">
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
                                <?php echo $this->Form->input('creditcard_code',['class'=>'card-cvc','placeholder'=>'CVV','label'=>false]); ?>
                                
                            </div>
                            <div class="form_fild_box">
                                <label>Name :</label>
                                <?php echo $this->Form->input('full_name',['class'=>'card-holder-name','placeholder'=>'Full ame','label'=>false]); ?>
                        
                            </div>
                        
                
                            <div class="form_submit_button">
                                <?php //echo $this->Html->Link("Submit",[],['id'=>'sendcreditcard','class'=>'form_submit_button','type'=>'button']); ?>

                                <input type="submit" value="submit"/>
                            </div>
                        </div>	
                    </div>
                </div>
            </section>
         
        </div>

    <?php echo $this->element('home_footer'); ?>
    </div>
  
</body>
</html>