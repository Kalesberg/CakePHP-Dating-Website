
 <?php echo $this->element('admin_header'); ?>
        <?php echo $this->element('admin_sidebar');
        $status = [ACTIVE=>'Active',INACTIVE=>'Inactive'];
         $btnStatus = [ACTIVE=>'btn-success',INACTIVE=>'btn-danger']; ?>
    <!--  PAPER WRAP -->
    <div class="wrap-fluid">
        <div class="container-fluid paper-wrap bevel tlbr">
            <!-- CONTENT -->
        
            <!-- BREADCRUMB -->
            <ul id="breadcrumb">
                <li>
                    <span class="entypo-home"></span>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">User Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Deleted User List</a>
                </li>
				
               <li class="pull-right">
                    <div class="input-group input-widget">
                        
                    </div>
                </li>
            </ul>
			<div class="content-wrap">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="nest" id="labelClose">
                            <div class="title-alt">
                                <h6>
                                    Date Picker</h6>
                                <div class="titleClose">
                                    <a class="gone" href="#dateClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#date_1">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>
							</div>
							<form action="userlist" method="post">
								<div class="body-nest" id="date_1" style="height:166px;">
									<div class="form_center"> 

										<div class="col-md-6">
											<span>Date From</span>
											<div id="datetimepicker1" class="input-group date">

												<input class="form-control" data-format="yyyy-MM-dd" name="datefrom" type="text" placeholder="yyyy/MM/dd">

												<span class="input-group-addon add-on">
													<i style="font-style:normal;" data-time-icon="entypo-clock"   data-date-icon="entypo-calendar">
													</i>
												</span>
											</div>
										</div>

										<div class="col-md-6">
											<span>Date To</span>
											<div id="datetimepicker2" class="input-group date">
												<input class="form-control" data-format="yyyy-MM-dd" name="dateto" type="text" placeholder="yyyy/MM/dd">

												<span class="input-group-addon add-on">
													<i style="font-style:normal;" data-time-icon="entypo-clock" data-date-icon="entypo-calendar">
													</i>
												</span>
											</div>
										</div>

									</div>
									<!--<div class="col-md-6">
										<div class="col-md-3">
											<div class="input-group date">
												<input type="checkbox"  class="form-control" name="active" value="<?php //echo ACTIVE; ?>" <?php //if($statusA == ACTIVE){ ?> checked <?php //; } ?>/><label>Active</label>    
											</div>
										</div>		
										<div class="col-md-3">
											<div class="input-group date">
											   <input type="checkbox"  class="form-control"name="inactive" value="<?php //echo INACTIVE; ?>" <?php //if($statusI== INACTIVE){ ?> checked <?php //; } ?>/><label>Inactive</label>
											</div>
										</div>
                                    </div>-->
									<div class="col-md-6">
										<div class="input-group date">
                                        	<input placeholder="Search..." style="border-radius:15px; margin:10px 0px;width:100%;" class="form-control" type="text" name="searchvalue" value="<?php if(isset($searchvalue)){ echo $searchvalue; } ?>">
										</div>
										<button class="btn btn btn-primary pull-right" style="border-radius:15px" type="submit">
										Search
										</button>
                                    </div>
									
								</div>
							</form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <div class="row">
					
                     <?php echo $this->Flash->render() ?>
					
                    <div class="col-sm-12">
                        <div class="nest" id="tableStaticClose">
                            <div class="title-alt">
                                <h6>
                                    Users List</h6>
                            </div>

                            <div class="body-nest" id="tableStatic">

                                <section id="flip-scroll">

                                    <table class="table table-bordered table-striped cf" id="footable-res2" data-filter="#filter" data-filter-text-only="true">
                                        <thead class="cf">
                                            
                                            <tr>
                                                <th style="width: 5%">S.No.</th>
                                                <th style="width: 10%">Name</th>
                                                <th style="width: 10%">Email</th>
                                                <!--<th style="width: 10%">Contact</th>
                                                <th style="width: 10%">Membership</th>
												
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 33%">Action</th>-->
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php $i=1;

                                            foreach($post as $val){ ?>
                                            <tr>
                                                <td style="width: 5%"><?php echo $i; ?></td>
                                                <td style="width: 10%"><?php echo ucfirst($val->name); ?></td>
                       							
                                                <td style="width: 10%"><?php echo $val->email; ?></td>
                                                <?php /*<td style="width: 10%"><?php if(!empty($val->phone)){ echo $val->phone; }else{ echo "N/A"; } ?></td>
                                                <td style="width: 10%"><?php $memberships=$this->Global->getMembership($val->membership_level); echo $memberships['membership_name'];  ?></td>
                                                <td style="width: 10%">
                                                     <button type="button" class="btn btnStatus <?php echo $btnStatus[$val->status] ?>" data-id="<?php echo $val->id; ?>" data-status="<?php echo $val->status; ?>"><?php echo $status[$val->status]; ?></button>
                                                </td>
                                              
                                                <td style="width: 33%">
                                                <?php
                                                 echo   $this->Html->Link('<span class="entypo-folder"></span>&nbsp;&nbsp;View Profile',
                                                                      array('controller'=>'Users','action'=>'userprofile',base64_encode($val->id)),
                                                                      array('escape' => false,'class'=>'btn btn-primary')
                                                                      );
                                                ?>
                                                <?php
                                                 echo   $this->Html->Link('<span class="entypo-pencil"></span>&nbsp;&nbsp;Edit',
                                                                      array('controller'=>'Users','action'=>'edituser',base64_encode($val->id)),
                                                                      array('escape' => false,'class'=>'btn btn-info')
                                                                      );
                                                ?>
                                                <button type="button" class="btn btn-danger btnDelete" data-id="<?php echo $val->id; ?>">Delete</button>
                                            
                                                </td> */ ?>
                                            </tr>
                                        <?php $i++; } ?>
                                           
                                        </tbody>
                                    </table>
									<?php  if(!empty($post)){?>
                     <?php echo $this->element('admin_paginator');?>
                     <?php }?>
                                </section>

                            </div>

                        </div>


                    </div>

                </div>
            </div>

            <!-- /END OF CONTENT -->


    <?php echo $this->element('admin_footer'); ?>
    <!--  END OF PAPER WRAP -->

    <?php  echo $this->element('admin_footer_js');  ?>
    <!-- /MAIN EFFECT -->
    <!-- GAGE -->
    <script type="text/javascript">
    $(function() {
        $('.footable-res').footable();
    });
    </script>
   <script>
   $(function(){
      $('.btnStatus').click(function(){
         var id = $(this).data('id');
         var status = $(this).data('status');
        /// alert(status);
         $.ajax({
            type:'POST',
            data:{id:id,status:status},
            url:"<?php echo SITE_URL.'Users/editstatus';?>",
            success:function(data) {
            }
         });
            if(status == '<?php echo ACTIVE; ?>'){
               $(this).removeClass('btn-success');
               $(this).addClass('btn-danger');
               $(this).text('Inactive');
               $(this).data('status','<?php echo INACTIVE; ?>');
            }else{
               $(this).removeClass('btn-danger');
               $(this).addClass('btn-success');
               $(this).text('Active');
               $(this).data('status','<?php echo ACTIVE; ?>');
            }
      });
      $('.btnDelete').click(function(){
         var id = $(this).data('id');
         if(confirm('Do you want to delete?')){
            $.ajax({
               type:'POST',
               data:{id:id},
               url:"<?php echo SITE_URL.'Users/delete';?>",
               success:function(data) {
               }
            });
            $(this).closest('tr').remove();
         }
         
      });
   });
</script>
    <script type="text/javascript">
    $('#datetimepicker1').datetimepicker({
        language: 'pt-BR'
    });
    $('#dp1').datepicker()
    $('#dpYears').datepicker();
    $('#timepicker1').timepicker();
    $('#t1').clockface();
    $('#t2').clockface({
        format: 'HH:mm',
        trigger: 'manual'
    });

    $('#toggle-btn').click(function(e) {
        e.stopPropagation();
        $('#t2').clockface('toggle');
    });
		$('#datetimepicker2').datetimepicker({
        language: 'pt-BR'
    });
    $('#dp1').datepicker()
    $('#dpYears').datepicker();
    $('#timepicker1').timepicker();
    $('#t1').clockface();
    $('#t2').clockface({
        format: 'HH:mm',
        trigger: 'manual'
    });

    $('#toggle-btn').click(function(e) {
        e.stopPropagation();
        $('#t2').clockface('toggle');
    });
    </script>
			 
</body>

</html>
