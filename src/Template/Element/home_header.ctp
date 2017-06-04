<?php   $AdminData = $this->request->session()->read('Auth.User');
        $cont = $this->request->params['controller'];
        $action = $this->request->params['action'];
        $pass = !empty($this->request->params['pass'][0])?$this->request->params['pass'][0]:"";
?>
<header>
    <div class="container">
        <div class="logo"><a href="<?php echo SITE_URL.'Pages/home'; ?>"><?php echo $this->Html->image('logo.jpg'); ?></a></div>
        
		<a class="menu_toggel"><?php echo $this->Html->image('menu_toggel01.png'); ?></a>
		<div class="head_right">
            <nav>
                <ul class="menu">
                    <li
                       <?php if($action != 'view' ) { if($action=="home") { ?> class='active' <?php  } } ?>>
                        <?php echo $this->Html->link("Home",
                            array('controller'=>'Pages','action'=>'home')
                        ); ?>
                    </li>
                    <?php $menu = $this->Global->getMenu(HEADER_MENU);
                  //  pr($menu->toArray());
                    $i = 1;
                    
                    foreach($menu as $val){
                    
                      if($i == 3){
                        ?>
                        <li <?php if($action != 'view' ) { if($action=="questionbank") { ?> class='active' <?php  } } ?>>
                        <?php echo $this->Html->link("Question Bank",
                           array('controller'=>'Pages','action'=>'questionbank')
                        ); ?>
                    </li>
                        <?php
                        
                      }$i++;
                      ?>
                      
                         <li <?php if( $pass == $val->slug) {   ?> class='active' <?php } ?>><a href="<?php echo SITE_URL.$val->slug.".html"; ?>"><?php echo ucwords(str_replace('?','',$val->title)); ?></a></li>
                    <?php   }
                    ?>
                    
                    
                   <li <?php if($action != 'view' ) { if($action=="contactus") { ?> class='active' <?php  } } ?>>
                        <?php echo $this->Html->link("Contact",
                      array('controller'=>'Pages','action'=>'contactus')
                    ); ?>
                    </li>
                    <?php
                        if(isset($AdminData)){
                            if($AdminData['role']==AFFILIATE){ ?>
                                <li <?php if($cont=='Affiliates' && $action != 'view' ) { if($action=="affiliateDetail") { ?> class='active' <?php  } } ?>>
                                    <?php 
                                    echo $this->Html->link("Dashboard",
                                    array('controller'=>'Affiliates','action'=>'affiliateDetail'),
                                     array('escape' => false)); ?>
                                </li>
                        <?php }else if($AdminData['role']==ADMIN){ ?>
                                <li <?php if($cont=='Users' && $action != 'view' ) { if($action=="index") { ?> class='active' <?php  } } ?>>
                                    <?php 
                                    echo $this->Html->link("Dashboard",
                                    array('controller'=>'Users','action'=>'index'),
                                     array('escape' => false)); ?>
                                </li>
                        <?php }
                        else{
                            $users=$this->Global->getUser($AdminData['id']);
                            $memberhip=$this->Global->getMembership($users['membership_level']);
                            if($memberhip['slug']!='visitor'){
                            ?>
                            
                                <li <?php if($action != 'view' ) { if($action=="memberdashboard") { ?> class='active' <?php  } } ?>>
                                    <?php 
                                    echo $this->Html->link("Dashboard",
                                    array('controller'=>'Pages','action'=>'memberdashboard'),
                                     array('escape' => false)); ?>
                                </li>
                        <?php }  }
                        }else{ ?>
                        <li <?php if($action != 'view' ) { if($action=="register") { ?> class='active' <?php  } } ?>>
                        <?php
                            echo $this->Html->link("Register",
                            array('controller'=>'freetrial','action'=>'freeregister'), array('escape' => false));
                        }
                        ?>
                        </li>
                </ul>
            </nav>
            <div class="login_button">
             <?php
             if($AdminData){
                if($AdminData['role']==USER || $AdminData['role']==ADMIN){
                    echo $this->Html->link("Log out",
                    array('controller' => 'users','action' => 'logout',), 
                    array('escape' => false)
                );
                }if($AdminData['role']==AFFILIATE){
                    echo $this->Html->link("Log out",
                    array('controller' => 'Affiliates','action' => 'logout',), 
                    array('escape' => false)
                    );
                }
             }else{
                 echo $this->Html->link("Log in",
                     array('controller' => 'users','action' => 'login',), 
                     array('escape' => false)
                     );
             }
             ?>
            </div>
        <?php if($AdminData){
             $user=$this->Global->getUser($AdminData['id']);
             $profile_photo=$user['profile_photo'];
            ?>
		    <div class="userimage_title">
			 <div class="user_image">
			  <a style="cursor: auto;"><?php if(!empty($profile_photo)){ echo $this->Html->image("user_images/".$profile_photo); }else{
              echo $this->Html->image('user_default.jpeg'); } ?></a>
			 </div>
			 <span><a href="javascript:;" style="text-decoration: none;">Hello <?php echo $user['first_name']."!"; ?> </a></span>
			 
			</div>
		<?php } ?>
		</div>
    </div> 
</header>
<!--header-content-end-->
