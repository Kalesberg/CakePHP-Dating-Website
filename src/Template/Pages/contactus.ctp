<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <div class="contact_us_content">
                <div class="contact_map_box">
                    <div id="map"  style="width:100%;height:500px"></div>
                    <?php //echo $this->Html->image('contact_map.png'); ?>
                </div>
                <div class="contact_detail_box" id="myform">
                    <div class="container">
                        <div class="contact_inner"> 
                            <div class="contact_left_box">
                                <!--<div class="contact_social_box">-->
                                <!--    <h2>Connect with us</h2>-->
                                    <!--<div class="contact_social">-->
                                    <!--    <ul>-->
                                    <!--        <li><a href="javascript:;"><?php echo $this->Html->image('social_icon01.png'); ?></a></li>-->
                                    <!--        <li><a href="javascript:;"><?php echo $this->Html->image('social_icon02.png'); ?></a></li>-->
                                    <!--        <!--<li><a href="javascript:;"><?php echo $this->Html->image('social_icon03.png'); ?></a></li>-->
                                    <!--        <li><a href="javascript:;"><?php echo $this->Html->image('social_icon04.png'); ?></a></li>-->
                                    <!--        <li><a href="javascript:;"><?php echo $this->Html->image('social_icon05.png'); ?></a></li>-->
                                    <!--    </ul>-->
                                    <!--</div>-->
                                <!--</div>-->
                                <div class="contact_info">
                                  
                                    <h2>Contact Info</h2>
                                    <h2>Depending on the nature of your email, please use one of the following addresses:</h2>
                                    <div class="contact_address">
                                        <ul>
                                           <!-- <li>
                                                <i><?php //echo $this->Html->image('contact_icon01.png'); ?></i>
                                                <span>1914 Riverlawn Dr.</span>
                                            </li>-->
                                           
                                            <li>
                                                <i><?php echo $this->Html->image('contact_icon03.png'); ?></i> 
                                                <span><a href="mailto:admin@self-match.com" target="_top" style="color: #fff;">admin@self-match.com</a></span>
                                            </li>
                                            <li>
                                                <i><?php echo $this->Html->image('contact_icon03.png'); ?></i>
                                                <span><a href="mailto:support@self-match.com" target="_top" style="color: #fff;">support@self-match.com</a></span>
                                            </li>
                                            <li>
                                                <i><?php echo $this->Html->image('contact_icon03.png'); ?></i>
                                                <span><a href="mailto:affiliate@self-match.com" target="_top" style="color: #fff;">affiliate@self-match.com</a></span>
                                            </li>
                                             <li>
                                                <i><?php echo $this->Html->image('contact_icon02.png'); ?></i>
                                                <span>877-600-8164</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>	 
                            <div class="contact_right_box">
                                <div class="contact_form_box">
                                      <?php echo $this->Flash->render(); ?>
                                    <div class="contact_form_head">
                                        <h2>Contact Us</h2> 
                                    </div>
                                    <?php echo $this->Form->Create($post); ?>
                                    <div class="contact_form_fild_bg">
                                        <div class="form_fild_box">
                                            <?php echo $this->Form->input("first_name",['placeholder'=>'First Name','label'=>false]); ?>
                                          
                                        </div>
                                        <div class="form_fild_box">
                                             <?php echo $this->Form->input("last_name",['placeholder'=>'Last Name','label'=>false]); ?>
                                           
                                        </div>
                                        <div class="form_fild_box">
                                             <?php echo $this->Form->input("email",['placeholder'=>'Email','label'=>false]); ?>
                                          
                                        </div>
                                       
                                        <div class="form_fild_box">
                                            <?php echo $this->Form->select('country',$countries,['placeholder'=>'Country','required'=>'required','label'=>false,'class'=>'countryId']); ?>
                                           
                                        </div>
                                         
                                        <!--<div class="form_fild_box">
                                          
                                           <?php // $states=[];
                                          // echo $this->Form->select('region',$states,['empty'=>'choose option','required'=>'required','label'=>false,'class'=>'stateId']); ?>
                                        </div>
                                        <div class="form_fild_box">
                                          
                                           <?php   //$cities=[]; echo $this->Form->select('city',$cities,['placeholder'=>'City','required'=>'required','label'=>false,'class'=>'cityId' ]); ?>
                                        </div>-->
                                        <div class="form_fild_box">
                                            <?php
                                            $arr=['General Inquiry'=>'General Inquiry','Suggest A Feature'=>'Suggest A Feature','Report A Problem'=>'Report A Problem'];
                                            echo $this->Form->select("enquiry",$arr,['placeholder'=>'Enquiry','empty'=>'Select Inquiry','label'=>false]); ?>
                                            
                                        </div>
                                        <div class="form_fild_box">
                                             <?php echo $this->Form->input("message",['placeholder'=>'Message','label'=>false,'type'=>'textarea']); ?>

                                        </div>
                                        <div class="form_submit_button">
                                            <input type="submit" value="Send"/>
                                        </div>
                                        <?php echo $this->Form->end(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
        <?php echo $this->element('home_footer'); ?>
      
    </div>
    <script>
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
    <script>
    function myMap() {
        
      var myCenter = new google.maps.LatLng(29.7604267,-95.3698028);
      var mapCanvas = document.getElementById("map");
      var mapOptions = {center: myCenter,
        scrollwheel: false,
        navigationControl: false,
        mapTypeControl: false,
        scaleControl: false,
        draggable: false,
      zoom: 10};
      var map = new google.maps.Map(mapCanvas, mapOptions);
      var marker = new google.maps.Marker({position:myCenter});
      marker.setMap(map);
    }
    </script>
   

<script src="https://maps.googleapis.com/maps/api/js?callback=myMap"></script>
</body>
</html>