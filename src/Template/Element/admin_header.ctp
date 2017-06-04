<?php
$AdminData = $this->request->session()->read('Auth.User'); ?>
<nav role="navigation" class="navbar navbar-static-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button data-target="#bs-example-navbar-collapse-1" data-toggle="collapse" class="navbar-toggle" type="button">
                    <span class="entypo-menu"></span>
                </button>
                <button class="navbar-toggle toggle-menu-mobile toggle-left" type="button">
                    <span class="entypo-list-add"></span>
                </button>
                <div id="logo-mobile" class="visible-xs">
                   <a href="<?php echo SITE_URL.'Pages/home'; ?>" target="_blank"> <h1><?php echo $this->Html->image('logo.png',['width'=>'48%']); ?>
		          </h1></a>
                </div></div>  <!-- Collect the nav links, forms, and other content for toggling -->
            	<div id="bs-example-navbar-collapse-1" class="collapse navbar-collapse">
                <ul class="nav navbar-nav"></ul>
                <div id="nt-title-container" class="navbar-left running-text visible-lg">
                    <ul class="date-top">
                        <li class="entypo-calendar" style="margin-right:5px"></li>
                        <li id="Date"></li>
                    </ul>
                    <ul id="digital-clock" class="digital">
                        <li class="entypo-clock" style="margin-right:5px"></li>
                        <li class="hour"></li>
                        <li>:</li>
                        <li class="min"></li>
                        <li>:</li>
                        <li class="sec"></li>
                        <li class="meridiem"></li>
                    </ul>
                </div>
				<ul style="margin-right:0;" class="nav navbar-nav navbar-right">
                    <li>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <?php
                           $users=$this->Global->getUser($AdminData['id']);
                            echo $this->Html->image("user_images/".$users->profile_photo,array('class'=>'admin-pic img-circle')) ?>Hi, <?php echo $users->first_name.' '.$users->last_name; ?><b class="caret"></b>
                        </a>
                        <ul style="margin-top:14px;" role="menu" class="dropdown-setting dropdown-menu">
                             <li>
								<?php echo $this->Html->link('<span class="entypo-user"></span>&#160;&#160;My Profile',
											  array('controller' => 'users','action' => 'profile'), 
											  array('escape' => false)
										  );
									  ?>
							</li>
							<li class="divider"></li>
							<li>
								<?php echo $this->Html->link('<span class="entypo-logout"></span>&#160;&#160;Logout',
											  array('controller' => 'users','action' => 'logout'), 
											  array('escape' => false)
										  );
									  ?>
							</li>
                        </ul>
                    </li>      
                </ul>
			</div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- /END OF TOP NAVBAR -->