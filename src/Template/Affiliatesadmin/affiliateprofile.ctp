
<?php //  $$ = $this->request->session()->read('Auth.User'); ?>
        <?php echo $this->element('admin_header'); ?>
        <?php echo $this->element('admin_sidebar'); ?>
        <!--  PAPER WRAP -->
        <div class="wrap-fluid">
            <div class="container-fluid paper-wrap bevel tlbr">
    
               <?php echo $this->element('admin_title'); ?>
    
                <!-- BREADCRUMB -->
                <ul id="breadcrumb">
                    <li>
                        <span class="entypo-home"></span>
                    </li>
                    <li><i class="fa fa-lg fa-angle-right"></i>
                    </li>
                    <li><a href="javascript:;" title="Sample page 1">Affiliate Management</a>
                    </li>
                    <li><i class="fa fa-lg fa-angle-right"></i>
                    </li>
                    <li><a href="javascript:;" title="Sample page 1">Profile</a>
                    </li>
                   <!-- <li class="pull-right">
                        <div class="input-group input-widget">
    
                            <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">
                        </div>
                    </li>-->
                </ul>
    
                <!-- END OF BREADCRUMB -->
                <?php
                   // foreach($post as $val){
                        $user_id    =$post->id; 
                        $first_name =$post->first_name;
                        $last_name  =$post->last_name;
                        $email      =$post->email;
                        $contact    =$post->phone;
                        $city       =$post->city;
                        $country    =$post->country;
						$state	    =$post->region;
                        $role       =$post->role;
						$company    =$post->company;
						$website    =$post->website;
						$address1  	=$post->address1;
						$address2   =$post->address2;
						$zip_code  =$post->zip_code;
						$business_type  =$post->business_type;
                        $relationship_status=$post->relationship_status;
                        $membership=$post->membership_level;
                        $user_name=$post->username;
                        $profile_photo=$post->profile_photo;
                        $password=base64_decode($post->password);
                        $last_login=$post->last_login;
                      // }
                        
                ?>
                <div class="content-wrap">
                    <!-- PROFILE -->
                    <div class="row">
                        <?php echo $this->Flash->render() ?>
                        <div class="col-sm-12">
                            <div class="well profile" style='width:97%;' >
                                <div class="col-sm-12">
                                    <div class="col-xs-12 col-sm-4 text-center">
                                        <ul class="list-group">
                                            <li class="list-group-item text-left">
                                                <span class="entypo-user"></span>&nbsp;&nbsp;Profile</li>
                                            <li class="list-group-item text-center">
                                                <?php echo $this->Html->image(!empty($profile_photo)?"user_images/".$profile_photo:'user_default.jpeg',array('class'=>'img-circle img-responsive img-profile')); ?>
    
                                            </li>
                                            
                                            <li class="list-group-item text-right">
                                                <span class="pull-left">
                                                    <strong>Last Login</strong>
                                                </span><?php echo $last_login; ?></li>
                                            <li class="list-group-item text-right">
                                                <span class="pull-left">
                                                    <strong>First Name</strong>
                                                </span><?php echo $first_name; ?></li>
										</ul>
    									<?php echo $this->Html->link('
                                            <span class="entypo-folder">&nbsp;&nbsp;Payment',
                                             array('controller' => 'Affiliatesadmin','action' => 'paymenttoaffiliate',base64_encode($user_id)), 
                                             array('escape' => false,'class'=>'btn btn-info',"title"=>"Payment",'type'=>'button')
                                        );
                                        ?>
										<?php echo $this->Html->link('
                                            <span class="entypo-folder">&nbsp;&nbsp;Payment History',
                                             array('controller' => 'Affiliatesadmin','action' => 'history',base64_encode($user_id)), 
                                             array('escape' => false,'class'=>'btn btn-info',"title"=>"Payment History",'type'=>'button')
                                        );
                                        ?>
                                    </div>
                                    <div class="col-xs-12 col-sm-8 profile-name">
                                        
                                        <h2><?php echo $first_name." ".$last_name; ?>
                                      <span class="pull-right social-profile">
                                       <?php /*echo $this->Html->link('
                                            <span class="entypo-pencil">&nbsp;&nbsp;Edit',
                                             array('controller' => 'users','action' => 'editprofile',$user_id), 
                                             array('escape' => false,'class'=>'btn btn-info',"title"=>"New User",'type'=>'button')
                                        );*/
                                        ?></span>
                                   
                                        </h2>
                                        <hr>
                                        <dl class="dl-horizontal-profile">
                                            <dt>Name</dt>
                                            <dd><?php echo ucwords($first_name)." ".ucwords($last_name); ?></dd>
                                            <dt>Email</dt>
                                            <dd><?php echo $email;?></dd>
											 <dt>Company</dt>
                                            <dd><?php echo $company;?></dd>
											 <dt>Website</dt>
                                            <dd><?php echo $website;?></dd>
											<dt>Address 1</dt>
                                            <dd><?php echo $address1;?></dd>
											<dt>Address 2</dt>
                                            <dd><?php echo $address2;?></dd>
                                            <dt>Username</dt>
                                            <dd><?php echo $user_name; ?></dd>
                                            <dt>Phone</dt>
                                            <dd><?php if(!empty($contact)){ echo $contact; }else{ echo "N/A"; } ?></dd>
                                            <dt>Country</dt>
                                            <dd><?php  $country = $this->Global->getCountryName($country); echo $country['name'];?></dd>
    
                                            <dt>Region</dt>
                                            <dd><?php $state = $this->Global->getStateName($state); echo $state['name']; ?></dd>
											<dt>City</dt>
                                            <dd><?php $city = $this->Global->getCityName($city); echo $city['name'] ;?></dd>
                                           	<dt>Zip/Postal Code</dt>
                                            <dd><?php echo $zip_code;?></dd>
											<dt>Business Type</dt>
                                            <dd><?php
												if($business_type !=0 ){
												if($business_type==1){ echo "online dating site"; }
												else if($business_type==2){ echo "online dating agency/services"; }
												else if($business_type==3){ echo "blog"; }else{ echo "other"; } }?></dd>
											
                                            <dt>Referral Code</dt>
                                            <dd><?php echo $post->refferal_code;?></dd>
                                            <dt>Total Referrals</dt>
                                            <dd><?php $refferal = $this->Global->getRefferals($user_id);
                                           // pr($refferal);die;
                                                    echo count($refferal);
                                                 
                                                ?></dd>
                                            <dt>Total Visitor Referrals</dt>
                                            <dd><?php $visitor=$this->Global->getVisitors($user_id); echo count($visitor); ?></dd>
                                            <dt>Total Gold Referrals</dt>
                                            <dd><?php
                                            $gold=$this->Global->getGold($user_id); echo count($gold);
                                           ?></dd>
                                            <dt>Total Platinum Referrals</dt>
                                            <dd><?php
                                            $platinum=$this->Global->getGold($user_id); echo count($platinum);
                                           ?></dd>
                                            <dt>Successfull Referrals</dt>
                                            <dd><?php echo !empty($ActiveRefferalsCount)?$ActiveRefferalsCount:"0"; ?></dd>
											<dt>Total Commission</dt>
                                            <dd><?php echo !empty($totalcomm)?"$".$totalcomm:"0"; ?></dd>
                                            <dt>Received Commission</dt>
                                            <dd><?php echo !empty($totalpaidamount)?"$".$totalpaidamount:"0"; ?></dd>
                                            <dt>Due Commission</dt>
                                            <dd><?php echo !empty($duecommission)?"$".$duecommission:"0"; ?></dd>
                                            <?php /*<dt>Relationship Status</dt>
                                            <dd><?php if($relationship_status == '2'){echo "I have a current/permanent partner";  }else{ echo "I have a prospective partner(s)"; };?></dd>
                                             <dt>Membership</dt>
                                            <dd><?php echo $membership;?></dd>
                                            <dt>Dating Site</dt>
                                            <dd><?php
                                            //pr($val->datingsites);die;
                                            if(!empty($val->datingsites)){
                                                $datingsitesArr = unserialize($val->datingsites);
                                                if($datingsitesArr){
                                                    foreach($datingsitesArr as $datingsitess){
                                                        if($datingsitess != 0){
                                                            echo $this->Global->getDetingSiteName($datingsitess);
                                                        }elseif ($datingsitess == 0){
                                                            echo $val['other'];
                                                        }
                                                    }
                                                }
                                            } ?></dd>
                                        <!--<dt></dt>
                                            <dd>
                                                <span class="tags">html5</span>
                                                <span class="tags">css3</span>
                                                <span class="tags">jquery</span>
                                                <span class="tags">bootstrap3</span>
                                            </dd>-->*/ ?>
    
                                        </dl>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>              
            <!-- END OF PROFILE -->
    
    
        <!-- /END OF CONTENT -->
        <?php echo $this->element('admin_footer'); ?>
        <!-- RIGHT SLIDER CONTENT -->
        
        <?php  echo $this->element('admin_footer_js');  ?>
     
   </body>