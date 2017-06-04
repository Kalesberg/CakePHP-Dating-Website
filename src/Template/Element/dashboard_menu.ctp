<div class="dashboard_menu">
<ul>
    <?php $user=$this->request->session()->read('Auth.User');
      $cont = $this->request->params['controller'];
      $action = $this->request->params['action'];
     
       // $pass = !empty($this->request->params['pass'][0])?$this->request->params['pass'][0]:"";
    ?>
    <li <?php if($action=='memberdashboard'){ ?> class="active" <?php } ?>>
        <?php echo $this->Html->link("Dashboard",
            array('controller'=>'Pages','action'=>'memberdashboard')
        ); ?>
    </li>
    <li <?php if($action=='manageprofile'){ ?> class="active" <?php } ?>>
        <?php  //$id=$this->Auth->user('id');
            echo $this->Html->link("Manage Profile",
            array('controller'=>'Pages','action'=>'manageprofile')
        ); ?>
    </li>
    <?php
        $users=$this->Global->getUser($user['id']);
        $memberhip=$this->Global->getMembership($users['membership_level']);

    if($memberhip['slug']!='visitor') { ?>
    <li class="submenu" data-id="1"><a href="javascript:;" >Things To Do</a>
        <ul class="dropdown1">
           
            
        <?php /*
                    $membership=$user['membership_level'];
                    if(!empty($membership) && ($membership=="visitor")){ ?>
            <li>
                <?php  
                    echo $this->Html->link("Simple Survey",
                    array('controller'=>'Pages','action'=>'questionbank')
                    );
                ?>
            </li>
            <li><?php  
                        echo $this->Html->link("Advanced Survey",
                        array('controller'=>'Pages','action'=>'Upgrade Membership')
                        );
                    }   ?>
            </li>*/ ?>
            <li>
            <?php  
                echo $this->Html->link("New Custom Survey",
                array('controller'=>'Pages','action'=>'questionbank')
                );
             ?>
            </li>
                 <li>
                    <?php
                         echo $this->Html->link("Ice-Breaker Survey",
                         array('controller'=>'Pages','action'=>'questionsdetails',base64_encode(ICE_BREACKER))
                         );
                     
                     ?>
                </li>
                <li>
                    <?php
                         echo $this->Html->link("Easy-Match Survey",
                         array('controller'=>'Pages','action'=>'questionsdetails',base64_encode(EASY_MATCH))
                         );
                     
                     ?>
                </li>
                 <li>
                    <?php
                         echo $this->Html->link("Deal-Breaker Survey",
                         array('controller'=>'Pages','action'=>'questionsdetails',base64_encode(DEAL_BREACKER))
                         );
                     
                     ?>
                </li>
            <li>
                <?php  //$id=$this->Auth->user('id');
                echo $this->Html->link("Random Challenge Survey",
                array('controller'=>'Pages','action'=>'randomquestionchallenge')
                ); ?>
            </li>
            <li>
                <?php echo $this->Html->link("Contribute a Question",['controller'=>'Pages','action'=>'addquestion']); ?>   
            </li>
        </ul>
    </li>
    <li class="submenu" data-id="2">
        <a href="javascript:;">MY STORAGE</a>
            <ul class="dropdown2">
                <li>
                    <?php echo $this->Html->link("My Saved Surveys",['controller'=>'Pages','action'=>'mysurvey']); ?>
                </li>
                <li>
                <?php echo $this->Html->link("My Favorite Questions",['controller'=>'Pages','action'=>'favouritelist']); ?>
                </li>
            </ul>
        
    </li>
    <?php } ?>
    <li class="submenu" data-id="3"><a href="javascript:;">My Account</a>
        <ul class="dropdown3">
            <li>
                <?php echo $this->Html->link("Payment history",['controller'=>'Pages','action'=>'paymenthistory']); ?>
            </li>
            <li>
                <?php echo $this->Html->link("Membership Level",['controller'=>'Pages','action'=>'usermembership']); ?>
            </li>
            
            <li>
            <?php if($memberhip['slug']!='lifetime'){
               echo $this->Html->link("Upgrade Membership",['controller'=>'Pages','action'=>'upgradepayment']); } ?>
            </li>
            <?php  if($memberhip['slug']!='lifetime' && $memberhip['slug']!='platinum') { ?>
            <li>
                <?php echo $this->Html->link("Downgrade Membership",['controller'=>'Pages','action'=>'downgradepayment']); ?>
            </li>
            
            <?php } ?>
            <li>
                <?php  echo $this->Html->link("Update Payment Method",['controller'=>'Pages','action'=>'cardupdate']); ?>
            </li>
            <li>
                <?php  echo $this->Html->link("Cancel Membership",['controller'=>'Pages','action'=>'deleteaccount'],['class'=>'deletebtn']); ?>
            </li>
            
        </ul>
        
    </li>
    <li <?php if($action=='changepassword'){ ?> class="active" <?php } ?>><?php  //$id=$this->Auth->user('id');
            echo $this->Html->link("Change password",
            array('controller'=>'Pages','action'=>'changepassword')
        ); ?>
    </li>
    <li><?php  //$id=$this->Auth->user('id');
            echo $this->Html->link("Log Out",
            array('controller'=>'Users','action'=>'logout')
        ); ?></li>
</ul>
</div>

<script>
$(document).ready(function(){
    $(".submenu").click(function(){
        var id = $(this).data('id');
        $(".dropdown"+id).slideToggle();
    });
});
$('.deletebtn').click(function(e){
   var com = confirm("You have requested to cancel your membership. A confirmation with additional information was sent to your email address.");
    if (com == true){
        return true;
    }else{
        return false;
    }
});
</script>