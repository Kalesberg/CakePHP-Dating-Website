<?php $AdminData = $this->request->session()->read('Auth.User');
    $cont = $this->request->params['controller'];
    $action = $this->request->params['action'];
    $pass = !empty($this->request->params['pass'][0])?$this->request->params['pass'][0]:"";
?>
<!--footer-content-here-->
<footer>
    <div class="top_footer">
        <div class="container" >
            <div class="foot_socail">
                <ul>
                    <li><a target="_blank" href="https://www.facebook.com/selfmatch.com1/?fref=ts"><?php echo $this->Html->image('s_icon02.png'); ?></a></li>
                    <li><a target="_blank" href="https://twitter.com/selfmatch"><?php echo $this->Html->image('s_icon01.png'); ?></a></li>
                    <li><a target="_blank" href="https://www.pinterest.com/selfmatch/"><?php echo $this->Html->image('s_icon06.png'); ?></a></li>
                    <li><a target="_blank" href="https://www.linkedin.com/company/self-match-llc"><?php echo $this->Html->image('s_icon03.png'); ?></a></li>
                    <li><a target="_blank" href="https://www.youtube.com/channel/UCwCb2Y7DGIaDI04-9aTapeA"><?php echo $this->Html->image('s_icon05.png'); ?></a></li>
                    <li><a target="_blank" href="https://www.instagram.com/selfmatch/"><?php echo $this->Html->image('s_icon07.png'); ?></a></li>
                    <!--<li><a href="javascript:;"><?php //echo $this->Html->image('s_icon03.png'); ?></a></li>
                    <li><a href="javascript:;"><?php //echo $this->Html->image('s_icon04.png'); ?></a></li>-->
                  
                </ul>
            </div>
            
            <div style="display: inline-block; margin-left:20px;">
                    
                
                  <!--- DO NOT EDIT - GlobalSign SSL Site Seal Code - DO NOT EDIT ---><table width=125 border=0 cellspacing=0 cellpadding=0 title="CLICK TO VERIFY: This site uses a GlobalSign SSL Certificate to secure your personal information." ><tr><td><span id="ss_img_wrapper_gmogs_image_100-40_en_blue"><a href="https://www.globalsign.com/" target=_blank title="GlobalSign Site Seal" rel="nofollow"><img alt="SSL" border=0 id="ss_img" src="//seal.globalsign.com/SiteSeal/images/gs_noscript_100-40_en.gif"></a></span><script type="text/javascript" src="//seal.globalsign.com/SiteSeal/gmogs_image_100-40_en_blue.js"></script></td></tr></table><!--- DO NOT EDIT - GlobalSign SSL Site Seal Code - DO NOT EDIT --->
                </div>
            
            
            
        </div>
    </div>
    <div class="bottom_footer">
        <div class="container">
            
            <div class="foot_menu">
                <ul>
                    <li <?php if($action != 'view' ) { if($action=="home"){ ?> class='active' <?php  } } ?>>
                        <?php echo $this->Html->link("Home",
                            array('controller'=>'Pages','action'=>'home')
                        ); ?>
                    </li>
                   <?php $menu = $this->Global->getMenu(FOOTER_MENU);
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
                         <li <?php if( $pass == $val->slug ) {   ?> class='active' <?php } ?>><a href="<?php echo SITE_URL.$val->slug.".html"; ?>"><?php echo ucwords(str_replace('?','',$val->title)); ?></a></li>
                    <?php   }
                    ?>
                   <li <?php if($action != 'view' ) { if($action=="contactus") { ?> class='active' <?php  } } ?>>
                        <?php echo $this->Html->link("Contact",
                      array('controller'=>'Pages','action'=>'contactus')
                    ); ?>
                    </li>
                    <?php
                        if($AdminData){
                            $users=$this->Global->getUser($AdminData['id']);
                            $memberhip=$this->Global->getMembership($users['membership_level']);
                            if($memberhip['slug']!='visitor'){?>
                        <li <?php if($action != 'view' ) { if($action=="memberdashboard") { ?> class='active' <?php  } } ?>>
                            <?php 
                            echo $this->Html->link("Dashboard",
                            array('controller'=>'Pages','action'=>'memberdashboard'),
                             array('escape' => false)); ?>
                        </li>
                        <?php 
                            } }else{ ?>
                        <li <?php if($action != 'view' ) { if($action=="freeregister") { ?> class='active' <?php  } } ?>>
                         <?php
                            echo $this->Html->link("Register",
                            array('controller'=>'freetrial','action'=>'freeregister'), array('escape' => false));
                        }
                        ?>
                    </li>
                    
                    <li>
                        <a href="<?php echo SITE_URL.'term_of_use.html'; ?>">Terms of Use</a>
                    </li>
                    <li>
                        <a href="<?php echo SITE_URL.'privacy_policy.html'; ?>">Privacy Policy</a>
                    </li>
                    <li>
                         <?php
                            echo $this->Html->link("Affiliates",
                            array('controller'=>'Affiliates','action'=>'login'), array('escape' => false));
                        
                        ?>
                    </li>
                    <!-- <li>
                         <?php
                            //echo $this->Html->link("Site Map",
                          //  array('controller'=>'pages','action'=>'siteMap'), array('escape' => false));
                        
                        ?>
                    </li>-->
                    
                </ul>
            </div>
        
		  <div class="copyright"><p>Copyright © Self-Match. All rights reserved</p></div> 
		
		</div>
    </div>
</footer>
<!--footer-content-end-->
<?php echo $this->Html->script(['jquery.flexslider-min', 'css3-animate-it']); ?>
 <script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    $(function(){
        $('.alert .close').click(function(){
            $('.alert').remove();
        })
    });
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-77647223-1', 'auto');
  ga('send', 'pageview');

</script>
<!-- Start Alexa Certify Javascript -->
<script type="text/javascript">
_atrk_opts = { atrk_acct:"u2aMo1IWhe10Io", domain:"self-match.com",dynamic: true};
(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
</script>
<noscript><img src="https://d5nxst8fruw4z.cloudfront.net/atrk.gif?account=u2aMo1IWhe10Io" style="display:none" height="1" width="1" alt="" /></noscript>
<!-- End Alexa Certify Javascript -->