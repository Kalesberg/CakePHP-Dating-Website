<body>
 <style>
    .vertical{
        height:470px;
    }
    .vertical a img{
        height:100%;
    }
     
    .affilite_box_right
    {
        float: left;
        margin-left: 85px;
        width: 28%;
    }
     .affilite_box{
        width: 58%;
     }
     .copy_button {
         padding-right: 304px;
     }
     .new{
        padding-right: 111px;
     }
     
 </style>
    <div class="site_content">
        <!--header-content-here-->
        <?php echo $this->element('home_header'); ?>
        <!--header-content-end-->
    <?php $user = $this->request->session()->read('Auth.User');
    ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>Affiliate detail</h2>
            </section>
            <section class="affiliate_detail_content">
                <div class="referrals_commission_bg">
                    <div class="container">
                        <div class="affiliate_box">
                           
                            <div class="affiliate_profile_edit manage_profile_edit">
                                <a href='<?php echo SITE_URL."Affiliates/manageprofile"?>'/><?php echo $this->html->image('edit_icon.png'); ?></a>
                            </div>
                            <div class="affiliate_user_image">
                                <?php if(!empty($post[0]['profile_photo'])){ ?>
                                <a href="javascript:;"><?php echo $this->Html->image("user_images/".$post[0]['profile_photo'],array('id'=>'photo'));
                                ?></a>
                                <?php echo $this->Form->input('photo',['type'=>'hidden','value'=>$post[0]['profile_photo']]); ?></a>
                                <?php  }else{ ?>
                                        <a href="javascript:;"><?php echo $this->Html->image('user_default.jpeg',array('id'=>'photo'));
                                    ?></a>
                                <?php  } ?>
                            </div>
                            <div class="affiliate_user_des">
                                <h2><?php echo $post[0]['first_name']." ".$post[0]['last_name']; ?></h2>
                                <p><strong>Email   :</strong>  <?php echo $user['email']; ?></p>
                                <!--<p><strong>Email   :</strong> <a href="javascript:;"></a></p>-->
                            </div>
                        </div> 
                        <div class="referrals_commission_box">
                            <div class="ref_com_box_inner float_left">
                                <h2>REFERRALS</h2>
                                <ul>
                                    <li>
                                        <?php $refferal = $this->Global->getRefferals($user['id']);
                                            //pr($refferal);die;
                                        ?>
                                        <p>This Month</p>
                                        <span><?php if(!empty($thisMonthUser)){ echo $thisMonthUser;  }else{ echo "0"; } ?></span>
                                    </li>
                                    <li>
                                        <p>Total Referrals  </p>
                                        <span><?php echo count($refferal); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="ref_com_box_inner float_right">
                                <h2>Commission</h2>
                                <ul>
                                    <li>
                                        <p>Pending</p>
                                        <span><?php if(!empty($duecommission)){ echo "$".$duecommission;  }else{ echo "$0"; } ?></span>
                                    </li>
                                    <li>
                                        <p>Received</p>
                                        <span><?php if(!empty($totalpaidamount)){ echo "$".$totalpaidamount;  }else{ echo "$0"; } ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="referred_members_section">
                <div class="container">
                    <?php echo $this->Flash->render(); ?>
                    <div class="referred_members">
                        <div class="referred_members_head">
                            <h2>Referred Members </h2>
                            <?php $refferal = $this->Global->getRefferals($user['id']);
                        //pr($refferal);die;
                            ?>
                        </div>
                        <div class="referred_members_table_box">
                            <table>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Membership Type</th>
                                    <th>Date</th>
                                </tr>
                                <?php
                               if($refferal){
                                foreach($refferal as $val){
                                    $date =substr($val->created,'0','8');
                                    ?>
                                <tr>
                                    <td>
                                        <div class="referred_image">
                                            <?php if(!empty($val->profile_photo)){ ?>
                                            <a href="javascript:;"><?php echo $this->Html->image("user_images/".$val->profile_photo,['height'=>'70px','width'=>'70px']); ?></a>
                                            <?php }else{ ?>
                                            <a href="javascript:;"><?php echo $this->Html->image("user_default.jpeg",['height'=>'70px','width'=>'70px']); ?></a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                    <td><?php echo ucfirst($val->first_name)." ".ucfirst($val->last_name); ?></td>
                                    <?php $mem=$this->Global->getMembership($val->membership_level);  ?>
                                    <td><?php echo ucfirst($mem['slug']); ?></td>
                                    <td><?php echo $date; ?></td>
                                </tr>
                                <?php  }
                                }else{
                                ?>
                                <tr>
                                    <td><strong align="center">You have no referrals.</strong></td>
                                </tr>
                                <?php    
                            }?>
                            </table>
                        </div>
                       <!-- <div class="see_all_button">
                            <a href="javascript:;">See All</a>
                        </div> -->	
                    </div>
                    <div class="affilite_invite_box">
                        <div class="affilite_box">
                            <div class="affilite_head">
                                <h2>Affiliate Banner</h2>
                            </div>
                            <div class="affilite_content_box">
                                <div class="affilite_box_inner">
                                    <div class="affilite_program">
                                        <a href="javascript:;"><?php echo $this->Html->image("affiliate_img01.jpeg"); ?></a>
                                    </div>
                                    
                                    <div class="copy_button">
                                        <a href="javascript:;">Copy</a>
                                    </div>
                                </div>
                            </div>
                
                            <div class="email_send_box">
                                <div class="email_input" style=" width:100%;">
                                    <input type="text" id="add" style="display:none;" value='<iframe width="365" height="80" src="<?php echo SITE_URL.'pages/advertisement/'.base64_encode($user['id']); ?>" frameborder="0" allowfullscreen></iframe>'/>
                                </div>
                            </div>
                            <div class="invite_friend_box">
                                <div class="invite_friend_head">
                                    <h2>Invite Your Friend</h2>
                                </div> 
                                <div class="invite_friend_content">
                                    <?php echo $this->Form->create('Send',['controller'=>'Affiliates','action'=>'invite']); ?>
                                    <div class="invite_friend_inner">
                                        <div class="email_send_box">
                                            <div class="email_input">
                                                <input type="email" placeholder="Email Address" name="email" required/>
                                            </div>
                                            
                                            <div class="send_button">
                                                <input type="submit" value="Send"/>
                                            </div>
                                        </div> 
                                    </div>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                            
                        </div>
                        <div class="affilite_box_right">
                           <div class="affilite_head">
                                <h2>Affiliate Banner</h2>
                            </div>
                            <div class="affilite_content_box">
                                <div class="affilite_box_inner">
                                    <div class="affilite_program vertical">
                                        <a href="javascript:;"><?php echo $this->Html->image("affiliate_img02.jpeg"); ?></a>
                                    </div>
                                    
                                    <div class="copy_button new">
                                        <a href="javascript:;">Copy</a>
                                    </div>
                                </div>
                            </div>
                            <div class="email_send_box">
                                <div class="email_input" style=" width:100%;">
                                    <input type="text" id="addnew" style="display:none;" value='<iframe width="365" height="80" src="<?php echo SITE_URL.'pages/advertisementver/'.base64_encode($user['id']); ?>" frameborder="0" allowfullscreen></iframe>'/>
                                </div>
                            </div>
                        </div>
                         
                    </div>
                    
                </div> 
            </section> 
        </div>
        <?php echo $this->element('home_footer'); ?>
    </div>
<script>
    $(".copy_button").click(function(){
        $("#add").css('display','block');
    });
     $(".new").click(function(){
        $("#addnew").css('display','block');
    });
</script>   
</body>
</html>