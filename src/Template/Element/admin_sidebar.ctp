<!-- SIDE MENU -->
    <div id="skin-select">
        <div id="logo">
            <a href="<?php echo SITE_URL.'Pages/home'; ?>" target="_blank"><h1><?php echo $this->Html->image('logo.jpg'); ?>
                <!--<span>Admin</span>-->
            </h1></a>
        </div>

        <a id="toggle">
            <span class="entypo-menu"></span>
        </a>
       <div class="skin-part">
            <div id="tree-wrap">
                <div class="side-bar">
                    <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">
                                <span class="">Admin Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="icon-window"></i><span>Dashboard</span>',
                                             array('controller' => 'users','action' => 'index'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Dashboard")
                                     );
                             ?>
                        </li>
                        <li>
                           <?php echo $this->Html->link('<i class="icon-document-edit"></i><span>Profile</span>',
                                             array('controller' => 'users','action' => 'profile'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Profile")
                                     );
                             ?>
                        </li>
                        <li>
                           <?php echo $this->Html->link('<i class="fontawesome-key"></i>
                                <span>Change Password</span>',
                                             array('controller' => 'users','action' => 'changepassword'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Change Password")
                                     );
                             ?>       
                        </li>
                        <li>
                            <?php echo $this->Html->Link(' <i class="icon icon-gear"></i>
                                <span>Global Settings</span>',array('controller'=>'Globalsettings','action'=>'globalsetting'),
                            array('escape'=>false,'class'=>'tooltip-tip ajax-load','title'=>'Global Setting')
                  			 );?>   
                        </li>
                    </ul>
					<ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">                <span class="">CMS Page Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
							</a>
                        </li>
                        <li>
                            <?php echo $this->Html->Link('<i class="entypo-leaf"></i>
                                <span>Page List</span>',array('controller'=>'Cmspages','action'=>'cmspagelist'),
                            array('escape'=>false,'class'=>'tooltip-tip ajax-load','title'=>'Cms Page List')
                            );?>  
                        </li>
                        <li>
                            <?php echo $this->Html->Link('<i class="icon icon-document-new"></i>
                                <span>Add Page</span>',array('controller'=>'Cmspages','action'=>'addcmspage'),
                            array('escape'=>false,'class'=>'tooltip-tip ajax-load','title'=>'Add Cms Page')
                            );?>  
                        </li>
                    </ul>
                    <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">

                                <span class="">User Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="icon icon-user"></i>
                                <span>User List
                                </span>',
                                             array('controller' => 'users','action' => 'userlist'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Users")
                                     );
                             ?>
						</li>
                        <li>
                            <?php echo $this->Html->link('<i class="entypo-list-add"></i>
                                <span>Add User</span>',
                                             array('controller' => 'users','action' => 'adduser'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"New User")
                                     );
                             ?>
						</li>
                        <li>
                            <?php echo $this->Html->link('<i class="icon icon-user"></i>
                                <span>Deleted User List
                                </span>',
                                             array('controller' => 'users','action' => 'deleteduser'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Deleted Users")
                                     );
                             ?>
						</li>
                    </ul>
                    <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">

                                <span class="">Affiliate Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                             <?php echo $this->Html->link('<i class="icon icon-view-list"></i>
                            <span>Affiliate List
                                </span>',
                                           array('controller' => 'Affiliatesadmin','action' => 'affiliatelist'), 
                                            array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All  Affiliates")
                                 );
                             ?>
                        </li>
                        <li>
                             <?php echo $this->Html->link('<i class="icon icon-view-list"></i>
                            <span>Referral List
                                </span>',
                                           array('controller' => 'Affiliatesadmin','action' => 'refferallist'), 
                                            array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All  Refferals")
                                 );
                             ?>
                        </li>
                        <li>
                             <?php echo $this->Html->link('<i class="icon icon-view-list"></i>
                                <span>Add Affiliate
                                </span>',
                                array('controller' => 'Affiliatesadmin','action' => 'addaffiliate'), 
                                array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Add Affiliate")
                                );
                             ?>
                        </li>
                    </ul>
                    <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">

                                <span class="">Membership Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="entypo-users"></i>
                                <span>Membership List
                                </span>',
                                             array('controller' => 'Memberships','action' => 'membershiplist'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All Memberships")
                                     );
                             ?>
                        </li>
                        <li>
                             <?php echo $this->Html->link('<i class="entypo-user-add"></i>
                                <span>Add Membership</span>',
                                             array('controller' => 'Memberships','action' => 'addmembership'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Add Membership")
                                     );
                             ?>
                            
                        </li>
                    </ul>
                     <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">

                                <span class="">Email Template Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                             <?php echo $this->Html->link('<i class="entypo-mail"></i>
                                <span>Template List</span>',
                                             array('controller' => 'Emailtemplates','action' => 'emailtemplatelist'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All Email Templates")
                                     );
                             ?>
                            
                        </li>
                        <li>
                           <?php echo $this->Html->link('<i class="entypo-newspaper"></i>
                                <span>Add Template</span>',
                                             array('controller' => 'Emailtemplates','action' => 'addemailtemplate'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Add Emial Template")
                                     );
                             ?>
                        </li>
                    </ul>
                    <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">

                                <span class="">Category Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="entypo-sweden"></i>
                                <span>Category List
                                </span>',
                                             array('controller' => 'Categories','action' => 'categorylist'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All Categories")
                                     );
                             ?>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="entypo-list-add"></i>
                                <span>Add Category
                                </span>',
                                             array('controller' => 'Categories','action' => 'addcategory'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Add Category")
                                     );
                             ?>
                        </li>
                    </ul>
                     <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">

                                <span class="">Question Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="fontawesome-list-ol"></i>
                                <span>Question List
                                </span>',
                                             array('controller' => 'Questions','action' => 'questionlist'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All Questions")
                                     );
                             ?>
                        </li>
                        <li>
                           <?php echo $this->Html->link('<i class="fontawesome-question-sign"></i>
                                <span>Add Question
                                </span>',
                                             array('controller' => 'Questions','action' => 'addquestion'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Add New Question")
                                     );
                             ?>
                        </li>
                    </ul>
                    <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">

                                <span class="">Promo Code Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                           
                            <?php echo $this->Html->link('<i class="fontawesome-tags"></i>
                                <span>Promo Code List
                                </span>',
                                             array('controller' => 'Promocodes','action' => 'promocodelist'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All Promo Codes")
                                     );
                             ?>
                        </li>
                        <li>
                             <?php echo $this->Html->link('<i class="fontawesome-tag"></i>
                                <span>Add Promo Code 
                                </span>',
                                             array('controller' => 'Promocodes','action' => 'addpromocode'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"Add New Code")
                                     );
                             ?>
                        </li>
                    </ul>
                    <ul class="topnav menu-left-nest">
                        <li>
                            <a href="javascript:;" style="border-left:0px solid!important;" class="title-menu-left">         <span class="">Survey Management</span>
                                <!--<i data-toggle="tooltip" class="entypo-cog pull-right config-wrap"></i>-->
                            </a>
                        </li>
                        <li>
                            <?php echo $this->Html->link('<i class="icon icon-document-edit"></i>
                                <span>Survey List
                                </span>',
                                             array('controller' => 'Surveys','action' => 'surveylist'), 
                                             array('escape' => false,'class'=>'tooltip-tip ajax-load',"title"=>"All Surveys")
                                     );
                             ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF SIDE MENU -->
