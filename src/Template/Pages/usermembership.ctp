<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<style>
.membership_list ul li{
    display: inline-block;
    float:none !important;
}
</style>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
		<div class="warper">
            <section class="inner_page_header">
                <h2>Membership information</h2>
            </section>
			<div class="best_plans_box">
				<div class="container">
					<div class="best_plans_head">
					<h2>Your Membership</h2>
					</div>
					<div class="membership_list">
                       <?php echo $this->Flash->render(); ?>
					    <ul>
                            <?php if(isset($membershipDetails) && $membershipDetails['slug']=='visitor'){ ?>
						<li class="active">
						    <div class="membership_head">
                                <h1>Visitor</h1>
                                <h2>Pay per Survey</h2>
						    </div>
						    <div class="membership_des">
                                    <p>Free Survey : 1</p>
                                    <p>Simple Survey : $.99<br />
                                    with Compatibility Report (up to 15 questions from limited categories)</p>
                                    <p>Advance Survey : $2.99<br />
                                    with Compatibility Report and TrueMatch Score Calculator (unlimited questions and categories)</p>
						    </div>
						    <div class="membership_button">
                                <p><br />
                                Advantages:<br />
                                No subscription <br />
                                No commitment</p>
						    </div>
						    <div class="membership_button"><?php //echo $this->html->link('Select',['controller'=>'Pages','action'=>'paymentpage',base64_encode("4.99"),]); ?></div>
						</li>
                        <?php } else if(isset($membershipDetails) && $membershipDetails['slug']=='lifetime'){ ?>
						<li>
						    <div class="membership_head">
                                <h1>Lifetime Member</h1>
                                <h2>$29.99</h2>
						    </div>
						    <div class="membership_des check">
                                <p>Unlimited questions and surveys</p>
								<p>Member Dashboard &amp; Partner Management</p>
								<p>Saved surveys and favorite questions</p>
								<p>Compatibility Reports with unlimited partners</p>
								<p>ALL question categories</p>
								<p>TrueMatch Score Calculator</p>
								<p>Priority customer support</p>
						    </div>
						    <div class="membership_button">
                                <p>Advantages:<br />
                                No subscription<br />
								All-inclusive<br />
								Best value</p>
						    </div>
						    <div class="membership_button"><?php //echo $this->html->link('Select',['controller'=>'Pages','action'=>'paymentpage',base64_encode("4.99"),]); ?></div>
						</li>
					    </ul>
                        <?php }else if(isset($membershipDetails) && $membershipDetails['slug']=='platinum'){ ?>
					    <ul>
                            <li>
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
                                <div class="membership_button"><?php //echo $this->html->link('Select',['controller'=>'Pages','action'=>'paymentpage',base64_encode("9.99"),]); ?></div>
                            </li>
					    </ul>
                        <?php } ?>
					</div>
				    </div>
				</div>
                <div class="add_survey_button">
                    <?php echo $this->Html->Link("Back",['controller'=>'Pages','action'=>'memberdashboard'],['style'=>'background-color:black;']); ?>
                </div>
			    </div>
 				<?php echo $this->element('home_footer'); ?>
    		</div>
		</body>
	</html>