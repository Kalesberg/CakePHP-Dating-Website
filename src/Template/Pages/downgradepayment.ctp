<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<body>
    <div class="site_content">
        <?php $user = $this->request->session()->read('Auth.User');
      //  $session = $this->request->session();
        echo $this->element('home_header'); ?>
		<div class="warper">
            <section class="inner_page_header">
                <h2>payment information</h2>
            </section>
			<div class="best_plans_box">
				<div class="container">
					<div class="best_plans_head">
					<h2>Self-Match Plans</h2>
                    <?php echo $this->Flash->render(); ?>
					</div>
                     <?php
                     $users=$this->Global->getUser($user['id']);
                     $membership=$this->Global->getMembership($users['membership_level']);
                     
                     ?>
					<div class="membership_list">
					    
                    <?php if($membership['slug']=='platinum'){ ?>
                        <ul>
                            <ul>
                            <li>
                                <div class="membership_head">
                                    <h1>Visitor</h1>
                                    <h2>Pay per Survey</h2>
                                </div>
                                <div class="membership_des">
                                    <p>Free Survey : 1</p>
                                    <p>Simple Survey : $.99<br />
                                    with Compatibility Report(up to 15 questions from limited categories)</p>
                                    <p>Advance Survey : $2.99<br />
                                    with Compatibility Report and TrueMatch Score Calculator(unlimited questions and categories)</p>
                                </div>
                                <div class="membership_button">
                                <p><br />
                                Advantages:<br />
                                No subscription <br />
                                No commitment</p>
                                </div>
                                <div class="membership_button"><?php echo $this->html->link('Select',['controller'=>'Pages','action'=>'deleteaccount']); ?></div>
                            </li>
                        </ul>
                            <li>
                                <div class="membership_head">
                                <h1>Gold Member</h1>
                                <h2>$4.99/month</h2>
                                </div>
                                <div class="membership_des check">
                                <p>Unlimited questions and surveys</p>
                                <p>Member Dashboard &amp; Partner Management</p>
                                <p>Saved surveys and favorite questions</p>
                                <p>Compatibility Reports with unlimited partners</p>
                                <p>Limited question categories</p>
                                </div>
                                <div class="membership_button">
                                <p>Advantages:<br />
                                Value</p>
                                </div>
                                <div class="membership_button"><?php echo $this->html->link('Select',['controller'=>'Pages','action'=>'choosepaymenttype',base64_encode("gold"),base64_encode("0"),base64_encode("downgrade")]); ?></div>
                            </li>
                        </ul>
                        
					    <ul>
                            <li class="active">
                                <div class="membership_head">
                                <h1>Platinum Member</h1>
                                <h2>$9.99/month</h2>
                                </div>
                                <div class="membership_des check">
                                <p>Unlimited questions and surveys</p>
                                <p>Member Dashboard &amp; Partner Management</p>
                                <p>Saved surveys and favorite questions</p>
                                <p>Compatibility Reports with unlimited partners</p>
                                <p>ALL question categories</p>
                                <p>TrueMatch Score Calculator</p>
                                </div>
                                <div class="membership_button">
                                <p>Advantages: <br />
                                All-inclusive <br />
                                Best value</p>
                                </div>
                              <!--  <div class="membership_button"><?php //echo $this->html->link('Select',['controller'=>'Pages','action'=>'choosepaymenttype',base64_encode("platinum"),base64_encode("0"),base64_encode("downgrade")]); ?></div>-->
                            </li>
                        </ul>
                    <?php } ?>
					</div>
				    </div>
				</div>
			    </div>
 				<?php echo $this->element('home_footer'); ?>
    		</div>
		</body>
	</html>