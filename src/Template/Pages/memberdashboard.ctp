<?php  use Cake\Core\Configure;  ?>
<style>
.manage_profile_edit
{      
    bottom: 0;
    left: 127px;
    position: absolute;
    right: 0;
    top: 122px;
    width: 37px;
    z-index: 99;
}
.manage_profile_edit a{
    background: #3bbbeb none repeat scroll 0 0;
    border: 4px solid #c7c7c7;
    border-radius: 100%;
    display: block;
    height: 39px;
    width: 100%;
}
.partners_image_des_box{
    position: relative;
}
</style>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>Dashboard</h2>
            </section>
            <?php
         //   $user= $this->Global->getUser($user['id']);
		//	$membership     = $user['membership_level'];
			//$survey_type    = $user['survey_type'];
           // $memberships= $this->Global->getMembership($user['id']);
           // if($memberships['slug']!='visitor'){
            ?>
            <section class="dashboard_content">
                <div class="dashboard_left_box">
                    <?php echo $this->element('dashboard_menu'); ?>
                </div>
                <div class="dashboard_right_box">
                    <div class="dashboard_profile_box">
                        <div class="dashboard_user_pic">
                            <?php
                            if(!empty($post->profile_photo)){
                            ?>
                            <a href="javascript:;"><?php  echo $this->Html->image("user_images/".$post->profile_photo,['style'=>'width: 100%;height:100%;']); ?></a>
                            <?php }else{ ?>
                                <a href="javascript:;"><?php  echo $this->Html->image("user_default.jpeg",['style'=>'width: 100%;']); ?></a>
                            <?php } ?>
                        </div>  
                        <div class="dashboard_user_des">
                            <ul>
								<?php $city = $this->Global->getCityName($post->city); //echo $city['name'] ; ?>
								<?php $country = $this->Global->getCountryName($post->country); //echo $country['name'];?>
								<?php $state = $this->Global->getStateName($post->region); //echo $state['name']; ?>
                                <li><p>Name: <span><?php echo ucwords($post->first_name." ".$post->last_name); ?></span></p></li>
                                <li><p>Age: <span><?php echo $post->age; ?></span></p></li>
                                <li><p>Location: <span><?php   $city   =!empty($city['name'])?ucfirst($city['name']).",&nbsp":"";
                                                                    $state  =!empty($state['name'])?ucfirst($state['name']).",&nbsp":"";
                                                                    $country=!empty($country['name'])?ucfirst($country['name']):"";
                                                                    echo $city."".$state."".$country; ?>
                                                                 </span></p></li>
                                <li><p>Email Address: <span><?php echo $post->email; ?></span></p></li>
                                <!--<li><p>Occupation : <span></span></p></li>
                                <li><p>Username : <span><?php //echo $post->username; ?></span></p></li>
                                <li><p>Phone : <span><?php //if(!empty($post->phone)){ echo $post->phone; }else{ echo "N/A"; } ?></span></p></li>
                                <li><p>Gender : <span><?php //if($post->gender=='0'){ echo "Male"; }else{ echo "Female"; } ?></span></p></li>
                                <li><p>Relationship : <span><?php //if($post->relationship_status=='2'){ echo "I have a current/permanent partner"; }else{ echo "I have a prospective partner(s)"; } ?></span></p></li>
                                <li><p>Membership : <span><?php //echo ucfirst($post->membership_level); ?></span></p></li>-->
                                <li>
                                    <p>Dating Sites: <span>
                                        <?php
                                            if(!empty($post->datingsites)){
                                                $datingsitesArr = unserialize($post->datingsites);
                                                if($datingsitesArr){
                                                    foreach($datingsitesArr as $datingsitess){
                                                        if($datingsitess != 0){
                                                            echo $this->Global->getDetingSiteName($datingsitess);
                                                        }elseif ($datingsitess == 0){
                                                            echo $post['other'];
                                                        }
                                                    }
                                                }
                                            }
                                ?></span></p>
							</li>
                            </ul>
                        </div>
                    </div>
                    <div class="my_partners_box">
                        <div class="my_partners_head">
                         <?php echo $this->Flash->render(); ?>
                         <h2>MY PARTNERS</h2>
                        </div>
                        
						<ul>
                            <?php
                            $partners   =$this->Global->getPartners($post->id);
			  
                            $membership	=$this->Global->getMembership($post->membership_level);
                          
                            if($partners){
                                $i=1;
                            foreach($partners as $val){
                               
                            ?>
                            <li>
							  <div class="my_partners_left">
                                <div class="partners_image_des_box">
                                    <div class="partner_profile_edit manage_profile_edit">
                                        <a href='<?php echo SITE_URL."Pages/editreceiver/".base64_encode($post->id)."/".base64_encode($val->email)."/".base64_encode($val->survey_id) ?>'/><?php echo $this->html->image('edit_icon.png'); ?></a>
                                    </div>
                                
								<div class="partners_proscons_box">
                                    <div class="partners_user_image">
                                        <?php
                                        //$partner = $this->Global->getReceiverNew($val->receiver_email,$val->user_id,$val->survey_id);
                                        //$inUser = $this->Global->findReceiverInUser($val->receiver_email);
                                       //  pr($inUser);die;
                                         
                                        if(!empty($val->profile_photo)){ ?>
                                            <a href="javascript:;"><?php echo $this->Html->image("user_images/".$val->profile_photo,['style'=>'height: 100%;']); ?></a>
                                        <?php }else{
                                           
                                            ?>
                                            <a href="javascript:;"><?php echo $this->Html->image("user_default.jpeg"); ?></a>
                                        <?php }
                                        ?>
                                        </div>
                                 
								<?php if($membership['slug'] !='gold'){
                                    
                                    ?>
                                <div class="proscons_score">
                                    <?php
                                        
                                        //$survey_for=4;
                                        $proscons = $this->Global->getprosconsPlatinum($val->user_id,$val->email);
                                        //pr()
                                        $pros = !empty($proscons['total_positives'])?$proscons['total_positives']:"0";
										$cons = !empty($proscons['total_negative'])?$proscons['total_negative']:"0";
                                        if(!empty($proscons)){
                                                if($pros=='0'){ ?>
                                                    <span>TrueMatch score</span> 	   
                                                    <p><?php echo "0"; ?></p>
                                                <?php }else if($cons=='0'){ ?>
                                                    <span>TrueMatch score</span> 	   
                                                    <p><?php echo round($pros/1,'2'); ?></p>
                                                <?php }else{  ?>
                                                    <span>TrueMatch score</span> 	   
                                                    <p><?php echo round($pros/$cons,'2'); ?></p>
                                                <?php }
                                        }	
                                        else{ ?>
                                                <span>Not Updated</span> 	   
                                                <p>0</p>
                                           <?php } 
                                        ?>
                                    
                                </div>
                                <?php } ?>
								</div>   
								   
								   
								   
								<div class="partners_image_des">
                                        <h2><span><?php echo ucwords($val->name); ?></span></h2>
                                        <?php 
                                        //$inuser     =   $this->Global->findReceiverInUser($val->email);
                                        //if(empty($inuser)){
                                         //   $profile=$inUser['profile_photo'];
                                         //   $inuser =   $this->Global->getReceiverNew($partner['email'],$val->user_id,$val->survey_id);
                                              
                                        // }
                                        ?>
                                        <?php $city1 = $this->Global->getCityName($val->city); //echo $city['name'] ; ?>
                                        <?php $country1 = $this->Global->getCountryName($val->country); //echo $country['name'];?>
                                        <?php $state1 = $this->Global->getStateName($val->region); //echo $state['name']; ?>
                                        <p>Age: <span><?php echo !empty($val->age)?$val->age:""; ?></span></p>
                                        <p>Occupation: <span><?php echo ucfirst($val->occupation); ?></span></p>
                                        <p>Location: <span><?php   $city   =!empty($city1['name'])?ucfirst($city1['name']).",&nbsp":"";
                                                                    $state  =!empty($state1['name'])?ucfirst($state1['name']).",&nbsp":"";
                                                                    $country=!empty($country1['name'])?ucfirst($country1['name']):"";
                                                                    echo $city.$state.$country; ?></span></p>
                                        <p>Email: <span><?php echo $val->email; ?></span></p>
                                        <input type="hidden" value="<?php echo $val->email; ?>" class="remail<?php echo $i; ?>"/>
                                        <input type="hidden" value="<?php echo $val->user_id; ?>" class="user<?php echo $i; ?>"/>
                                        <input type="hidden" value="<?php echo $val->survey_id; ?>" class="survey<?php echo $i; ?>"/>
                                    </div>
                                </div>
								

                                </div>
								
								<div class="compatibility_con_button">
                                    <?php
                                
                                       /* if(strlen($val->name) > 5){
                                            $name = ucwords(substr($val->name,'0','5'))."...";
                                        }else{
                                            $name = ucwords($val->name);
                                        }*/
                                        echo $this->html->link("My Compatibility Reports with ".ucwords($val->name),['controller'=>'Pages','action'=>'mycompatibilityreports',base64_encode($post->id),base64_encode($val->email)]); 
                                        if($membership['slug']=='platinum'){ ?>
                                        
                                        <?php echo $this->html->link("TrueMatch scores for ".ucwords($val->name),['controller'=>'Pages','action'=>'feedback',base64_encode($val->email),base64_encode($post->id),base64_encode($val->survey_id)]); ?>
                                        <?php } 
                                            echo $this->Html->link("Delete",['controller'=>'Pages','action'=>'deletepartner',base64_encode($val->email),base64_encode($val->user_id)],['class'=>'deletebtnnew']);
                                        ?>
                                </div>
                            </li>  
                            <?php $i++; }
                            }else{
                                ?>
                                <li>
                                    <h3>You do not have any partners yet.</h3>
                                </li>
                                <?php    
                            }?>
                        </ul>
                    </div>
                </div>
            </section>
            <?php //}else{
                
            //}?>
        </div>
        
        <?php echo $this->element('home_footer'); ?>
    </div>
    <script>
        $(".addoccup").click(function(){
           var id = $(this).data('id');
            $(this).hide();
            $("#occupt"+id).show();
        });
        $(".occupt").click(function(){
            var id =$(this).data('id');
            var remail  =$('.remail'+id).val();
            var user    =$('.user'+id).val();
            var survey  =$('.survey'+id).val();
            var occupation = $(".occupation"+id).val();
           //alert(occupation);
           //alert(remail);
            $.ajax({
            type:'POST',
            data:{remail:remail,occupation:occupation,user:user,survey:survey},
            url:"<?php echo SITE_URL.'Pages/editoccupation';?>",
            success:function(data) {
                $("#occupt"+id).html('<span>'+occupation+'</span>');
            }
            });
        });  
        $('.deletebtnnew').click(function(){
           var con = confirm("Are you sure? Do you want to delete this partner.");
           if(con == true){
            return true;
           }else{
            return false;
           }
        });
    </script>
</body>
</html>