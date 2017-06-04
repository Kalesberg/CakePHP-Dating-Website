<?php use Cake\Auth\DefaultPasswordHasher;use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;
?>
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
                    <li><a href="javascript:;" title="Sample page 1">User Management</a>
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
                    foreach($post as $val){
                        $user_id    =$val->id; 
                        $first_name =$val->first_name;
                        $last_name  =$val->last_name;
                        $email      =$val->email;
                        $contact    =$val->phone;
                        $city       =$val->city;
                        $country    =$val->country;
                        $role       =$val->role;
                        $gender     =$val->gender;
                        $age        =$val->age;
                        $relationship_status=$val->relationship_status;
                        $membership=$val->membership_level;
                        $user_name=$val->username;
                        $profile_photo=$val->profile_photo;
                        $password=base64_decode($val->password);
                        $last_login=$val->last_login;
                        }
                        
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
                                             <!-- <li class="list-group-item text-center">
                                              <span class="pull-left">
                                                    <strong>Ratings</strong>
                                                </span>
    
    
                                                <div class="ratings">
    
                                                    <a href="#">
                                                        <span class="fa fa-star"></span>
                                                    </a>
                                                    <a href="#">
                                                        <span class="fa fa-star"></span>
                                                    </a>
                                                    <a href="#">
                                                        <span class="fa fa-star"></span>
                                                    </a>
                                                    <a href="#">
                                                        <span class="fa fa-star"></span>
                                                    </a>
                                                    <a href="#">
                                                        <span class="fa fa-star-o"></span>
                                                    </a>
    
                                                </div>
    
    
                                            </li>-->
    
                                            <!--<li class="list-group-item text-right">
                                                <span class="pull-left">
                                                    <strong>Joined</strong>
                                                </span>2.13.2014</li>-->
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
                                            <span class="entypo-folder">&nbsp;&nbsp;View Surveys',
                                             array('controller' => 'Surveys','action' => 'surveylist',base64_encode($user_id)), 
                                             array('escape' => false,'class'=>'btn btn-info',"title"=>"User Survey",'type'=>'button')
                                        );
                                        ?>
										<?php echo $this->Html->link('
                                            <span class="entypo-folder">&nbsp;&nbsp;Payment History',
                                             array('controller' => 'Users','action' => 'history',base64_encode($user_id)), 
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
                                            <dd><?php echo $first_name." ".$last_name; ?></dd>
    
                                            <dt>Email</dt>
                                            <dd><?php echo $email;?></dd>
                                            <dt>Username</dt>
                                            <dd><?php echo $user_name; ?></dd>
                                            
                                            <dt>Phone</dt>
                                            <dd><?php if(!empty($contact)){ echo $contact; }else{ echo "N/A"; } ?></dd>
    
                                            <dt>City</dt>
                                            <dd><?php $cities = $this->Global->getCityName($city); echo $cities['name'] ;?></dd>
    
                                            <dt>Country</dt>
                                            <dd><?php $country = $this->Global->getCountryName($country); echo $country['name'];?></dd>
    
                                            <dt>Region</dt>
                                            <dd><?php $state = $this->Global->getStateName($val->region); echo $state['name']; ?></dd>
                                            <dt>Age</dt>
                                            <dd><?php echo $age;?></dd>
    
                                            <dt>Gender</dt>
                                            <dd><?php if($gender=='1'){ echo 'Male'; }else{ echo 'Female'; } ?></dd>
                                            <dt>Relationship Status</dt>
                                            <dd><?php if($relationship_status == '2'){echo "I have a current/permanent partner";  }else{ echo "I have a prospective partner(s)"; };?></dd>
                                             <dt>Membership</dt>
                                            <dd><?php $memberships=$this->Global->getMembership($membership); echo $memberships['membership_name']; ?></dd>
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
                                            </dd>-->
    
                                        </dl>
                                       <!-- <hr>
                                        <h5>
                                        <span class="entypo-arrows-ccw"></span>&nbsp;&nbsp;Recent Activities</h5>

                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped table-condensed">
                                            <tbody>
                                                <tr>
                                                    <td><i class="pull-right fa fa-edit"></i> Today, 1:00 - Jeff Manzi liked your post.</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="pull-right fa fa-edit"></i> Today, 12:23 - Mark Friendo liked and shared your post.</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="pull-right fa fa-edit"></i> Today, 12:20 - You posted a new blog entry title "Why social media is".</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="pull-right fa fa-edit"></i> Yesterday - Karen P. liked your post.</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="pull-right fa fa-edit"></i> 2 Days Ago - Philip W. liked your post.</td>
                                                </tr>
                                                <tr>
                                                    <td><i class="pull-right fa fa-edit"></i> 2 Days Ago - Jeff Manzi liked your post.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>-->
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