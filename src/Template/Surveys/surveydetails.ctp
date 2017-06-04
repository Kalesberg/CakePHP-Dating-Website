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
                    <li><a href="javascript:;" title="Sample page 1">Survey Management</a>
                    </li>
                    <li><i class="fa fa-lg fa-angle-right"></i>
                    </li>
                    <li><a href="javascript:;" title="Sample page 1">Survey Details</a>
                    </li>
                   <!-- <li class="pull-right">
                        <div class="input-group input-widget">
    
                            <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">
                        </div>
                    </li>-->
                </ul>
    
                <!-- END OF BREADCRUMB -->
               
                <div class="content-wrap">
                    <!-- PROFILE -->
                    <div class="row">
                        <?php echo $this->Flash->render() ?>
                        <div class="col-sm-12">
                            <div class="well profile" style='width:97%;' >
                                <div class="col-sm-12">
                                    <?php $i=1;

                                    $questions=!empty($post['questions_id'])?$post['questions_id']:"";
                                	$sender_answer = [];
                                	$sender_answer  = !empty($post['answers'])?(unserialize($post['answers'])):[];
                              
									if($questions){
										$questions  = !empty($questions)?(unserialize($questions)):[];
										foreach($questions as $q){
										  $quest= $this->Global->getQuestionsNew($q);; ?>
										<div>
											 <p><?php echo $i.".".$quest['question_text']; ?></p>
										</div>
											<?php $answers	=	$this->Global->getAnswer($post['id'],$post['status']);
												?>
											<ul style="list-style: none;">
												<li><input type="radio" value="A"  name="answers<?php echo $q; ?>" <?php if(isset($answers[$q])&&($answers[$q]=='A')){ ?> checked <?php ; } ?>/>&nbsp;&nbsp;A.&nbsp;&nbsp;<?php echo $quest['option_1']; ?></li>
												<li><input type="radio" value="B"  name="answers<?php echo $q; ?>" <?php if(isset($answers[$q])&&($answers[$q]=='B')){ ?> checked <?php ; } ?>/>&nbsp;&nbsp;B.&nbsp;&nbsp;<?php echo $quest['option_2']; ?></li>
												<?php if(!empty($quest['option_3'])){ ?>
												<li><input type="radio" value="C"  name="answers<?php echo $q; ?>"<?php if(isset($answers[$q])&&($answers[$q]=='C')){ ?> checked <?php ; } ?> />&nbsp;&nbsp;C.&nbsp;&nbsp;<?php echo $quest['option_3']; ?></li>
												<?php }if(!empty($quest['option_4'])){ ?>
												<li><input type="radio" value="D"  name="answers<?php echo $q; ?>"<?php if(isset($answers[$q])&&($answers[$q]=='D')){ ?> checked <?php ; } ?>  />&nbsp;&nbsp;D.&nbsp;&nbsp;<?php echo $quest['option_4']; ?></li>
												<?php } ?>
											 </ul>
									
										<?php $i++;  } 
										}else{ ?>
										<div>
											 <p>There is no question in this survey.</p>
										</div>
									<?php }
									?>
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