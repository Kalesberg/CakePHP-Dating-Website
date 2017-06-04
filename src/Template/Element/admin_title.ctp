 <?php
 $AdminData = $this->request->session()->read('Auth.User'); ?>
 <!-- CONTENT -->
                <!--TITLE -->
                <div class="row">
                    <div id="paper-top">
                        <div class="col-sm-3">
                            
                            <h2 class="tittle-content-header">
                                <!--<i class="icon-media-record"></i>--> 
                                <span>Dashboard
                                </span>
                            </h2>
    
                        </div>
                        <div class="col-sm-7">
                            <div class="devider-vertical visible-lg"></div>
                            <div class="tittle-middle-header">
    
                                <div class="alert">
                                   
                                    <span class="tittle-alert entypo-info-circled"></span>
                                    Welcome,&nbsp;
                                    <?php
  										$users=$this->Global->getUser($AdminData['id']);
                                    ?>
                                   <strong><?php echo $users['first_name']." ".$users['last_name']; ?></strong>
                                    &nbsp;&nbsp;Your last sign in at <?php echo $users['last_login']; ?>
                                </div>
							</div>
						</div>      
                </div>
                <!--/ TITLE -->