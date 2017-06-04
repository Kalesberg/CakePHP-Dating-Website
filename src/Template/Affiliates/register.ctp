<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
             <h2>Register As Affiliate</h2>
            </section>
            <section class="forgot_password_content">
                <div class="dashboard_form_box">
                    <div class="dashboard_form_inner">
                        <div class="dashboard_form_inner_sub">
                            <?php
			    if($showmsg == 2){
				  echo $this->Flash->render();
			    }
			     echo $this->Form->create($post); ?>
                            <div class="form_fild_box">
                                <label>First Name*</label>
                                <?php echo $this->Form->input('first_name',['placeholder'=>'First Name','required'=>'required','label'=>false]); ?>
                            </div>
                            <div class="form_fild_box">
                                 <label>Last Name*</label>
                                <?php echo $this->Form->input('last_name',['placeholder'=>'Last Name','required'=>'required','label'=>false]); ?>
                            </div>
                            <div class="form_fild_box">
                                 <label>Company</label>
                                <?php echo $this->Form->input('company',['placeholder'=>'Company Name','label'=>false,'type'=>'text']); ?>
                            </div>
                            <div class="form_fild_box">
                                <label>Website*</label>
                                <?php echo $this->Form->input('website',['placeholder'=>'Website Name','required'=>'required','label'=>false,'type'=>'text']); ?>
                            </div>
                            <div class="form_fild_box">
                                <div class="form-new">
                                    <label>Country Code</label>
                                    <?php echo $this->Form->select('countrycode',$countrycode,['placeholder'=>'Country Code']); ?>
                                </div>
                                <div class="form-new2">
                                    <label>Phone</label>
                                    <?php echo $this->Form->input('phone',['placeholder'=>'XXXXXXXXXX','label'=>false,'minlength'=>4,]); ?>
                                </div>
                            </div>
							<div class="form_fild_box">
                                 <label>Email*</label>
                                <?php echo $this->Form->input('email',['placeholder'=>'Email','type'=>'email','required'=>'required','label'=>false]); ?>
                            
							<!-- <div class="email_note_box">
							  <p>Please enter the correct email address. Self-Match is not responsible if the survey is sent to a wrong or inactive email address, if the partner does not open the Self-Match notification email, or if the partner refuses to take the survey. NO REFUNDS WILL BE ISSUED.</p>
							 </div>-->
							 
							</div>
                            <div class="form_fild_box">
                                <label>Paypal Email ID</label>
                                <?php echo $this->Form->input('paypalemail',['placeholder'=>'Paypal Email ID','type'=>'email','label'=>false]); ?>
                            </div>
                            <div class="form_fild_box">
                                <label>Address 1*</label>
                                <?php echo $this->Form->input('address1',['placeholder'=>'Address 1','required'=>'required','label'=>false,'type'=>'text']); ?>
                            </div>
                            <div class="form_fild_box">
                                <label>Address 2</label>
                                <?php echo $this->Form->input('address2',['placeholder'=>'Address 2','label'=>false,'type'=>'text']); ?>
                            </div>
							<div class="form_fild_box">
                                <label>Country*</label>
                                <?php echo $this->Form->select('country',$countries,['required'=>'required','label'=>false,'class'=>'countryId']); ?>
                            </div>
                            <div class="form_fild_box">
                                 <label>State/Region*</label>
                                <?php
                                //$states=[];
                                echo $this->Form->select('region',$states,['empty'=>'choose option','required'=>'required','label'=>false,'class'=>'stateId']); ?>
                            </div>
                            <div class="form_fild_box">
                                 <label>City*</label>
                                <?php
                                
                                echo $this->Form->select('city',$cities,['empty'=>'choose option','required'=>'required','label'=>false,'class'=>'cityId']); ?>
                            </div>
							 <div class="form_fild_box">
                                 <label>Zip/Postal code*</label>
                                <?php echo $this->Form->input('zip_code',['placeholder'=>'Zip/Postal code','required'=>'required','label'=>false ]); ?>
                            </div>
                            <div class="form_fild_box">
                                <label>Type of Business</label>
                                <?php
                                $type=['0'=>'Select','1'=>'online dating site','2'=>'online dating agency/services','3'=>'blog','4'=>'other'];
                                echo $this->Form->select('business_type',$type,['id'=>'datingsite']);  ?>
                            </div>
                            <div class="form_fild_box">
                                 <label>Username*</label>
                                 <?php echo $this->Form->input('username',['placeholder'=>'User Name','required'=>'required','label'=>false]); ?>
                            </div>
                            <div class="form_fild_box">
                                 <label>Password*</label>
                                <?php echo $this->Form->input('password',['placeholder'=>'Password','required'=>'required','type'=>'password','label'=>false]); ?>
                               
                            </div>
                            <div class="form_fild_box">
                                 <label>Confirm Password*</label>
                                 <?php echo $this->Form->input('cpassword',['value'=>'','placeholder'=>'Confirm Password','required'=>'required','type'=>'password','label'=>false]); ?>
                                
                            </div>
                           <!-- <div class="form_fild_box">
                                 <label>Promo Code</label>
                                 <?php //echo $this->Form->input('promocode',['placeholder'=>'promocode','label'=>false]); ?>
                                
                            </div>-->
                            <div class="terms_check">
                                <input type="checkbox" name="tc" value="1" required/>
                                <label>I accept the terms of the <a href="<?php echo SITE_URL.'affiliate_agreement.html'; ?>">Affiliates Agreement</a>.
                                <a href="<?php echo SITE_URL.'term_of_use.html'; ?>">Terms of Use</a> and <a href="<?php echo SITE_URL.'privacy_policy.html'; ?>">Privacy Policy</a></label>
                            </div>
                            <div class="confirm_password_captch">
                                 <div class="g-recaptcha" data-sitekey="6LcAoxUUAAAAAHLenDYy2SXqbR7LxREaZfr9qCMD"></div>
                            </div>
                            <div class="form_submit_button">
                                <input type="submit" value="Register"/>
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
    $('.captcha_reload').click(function(){
	newCaptch();
      });
    
    newCaptch();
     function newCaptch() {
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	 var text = '';
	 for( var i=0; i < 6; i++ ){
	   text += possible.charAt(Math.floor(Math.random() * possible.length));
	 }
     //alert(text);
     var img = "<img src='' alt='"+text+"'>";
     $('.captcha').html('');
     $('.captcha').append(img);
	 $('.createcaptcha').val(text);
     }
     $('.captchavalue').change(function(){
        var v = $('.captchavalue').val();
        var i=$('.createcaptcha').val();
      //  alert(i);
       // d alert(v);
        if (v!=i) {
            alert("Please enter correct captcha code");
        }
        });
   //dating site code start
        $('#datingsite').change(function(){
           var r = $("#datingsite").val();
           var i=0;
           var nextProcess = 0;
            for(i=0;i<r.length;i++)
            {
                console.log(r[i]);
                if (r[i]=='0') {
                  nextProcess = parseInt(nextProcess) + parseInt(1);
                }
            }
            if (nextProcess > 0) {
                $('#other').show();
            }else{
                $('#other').hide();
            }
      });
   //captcha code end
   $(".countryId").change(function(){
    var countryId = $('.countryId').val();
    //alert(countryId);
        $.ajax({
           type:'POST',
           data:{countryId:countryId},
           url:"<?php echo SITE_URL.'Affiliates/states';?>",
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
           url:"<?php echo SITE_URL.'Affiliates/cities';?>",
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
     <script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>