 <?php echo $this->element('admin_header'); ?>
        <?php echo $this->element('admin_sidebar');
            $status = [ACTIVE=>'Active',INACTIVE=>'Inactive'];
            $btnStatus = [ACTIVE=>'btn-success',INACTIVE=>'btn-danger'];
           
        ?>
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
                <li><a href="javascript:;" title="Sample page 1">Affiliate Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Affiliate List</a>
                </li>
               <!--<li class="pull-right">
                    <div class="input-group input-widget">
                       
                        
                    </div>
                </li>-->
            </ul>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="nest" id="tableStaticClose">
                            <div class="title-alt">
                                <h6>
                                    Affiliate List</h6>
                                
                                <div class="titleClose">
                                    <a class="gone" href="#tableStaticClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#tableStatic">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>
                            </div>

                            <div class="body-nest" id="tableStatic">

                                <section id="flip-scroll">

                                    <table class="table table-bordered table-striped cf">
                                        <thead class="cf">
                                            <tr>
                                                <th >S.No.</th>
                                                <th >Name</th>
                                                <th>Email</th>
                                                <th >Total Referrals</th>
												<th >Total Commission</th>
                                                <th >Commission<br/>Received</th>
                                                <th >Commission<br/>Pending</th>
                                               <!--<th	>Total<br/>Successfull Referrals</th>-->
                                                <th >Status</th>
                                                <th >Action</th>
                                                 <th >Request</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;
                                            foreach($post as $val){ ?>
                                            <tr class="tr<?php echo $val->id; ?>">
                                                <td ><?php echo $i ?></td>
                                                <td><?php echo ucwords($val->first_name)." ".ucwords($val->last_name); ?></td>
                                                <td ><?php echo $val->email; ?></td>
                                                <td><?php $refferal = $this->Global->getRefferals($val->id);
                                                    echo count($refferal);
                                                ?> </td>
												<td><?php
                                              
                                                $data = $this->Global->getBenifitDetails($val->id);
																
                                                echo  "$".!empty($data['comm'])?$data['comm']:"0"; ?></td>
                                                <td><?php echo "$".!empty($data['received'])?$data['received']:"0"; ?></td>
                                                <td><?php echo "$".!empty($data['pending'])?$data['pending']:"0"; ?></td>
                                              <!--  <td><?php //echo !empty($data['ActiveRefferals'])?$data['ActiveRefferals']:"0"; ?></td>-->
                                                <td style="width: 10%">
                                                     <button type="button" class="btn btnStatus <?php echo $btnStatus[$val->status] ?>" data-id="<?php echo $val->id; ?>" data-status="<?php echo $val->status; ?>"><?php echo $status[$val->status]; ?></button>
                                                </td>
                                              
                                                <td style="width: 33%">
                                                <?php
                                                 echo   $this->Html->Link('<span class="entypo-folder"></span>&nbsp;&nbsp;View Profile',
                                                                      array('controller'=>'Affiliatesadmin','action'=>'affiliateprofile',base64_encode($val->id)),
                                                                      array('escape' => false,'class'=>'btn btn-primary')
                                                                      );
                                                ?>
                                                <?php
                                                 echo   $this->Html->Link('<span class="entypo-pencil"></span>&nbsp;&nbsp;Edit',
                                                                      array('controller'=>'Affiliatesadmin','action'=>'editaffiliate',base64_encode($val->id)),
                                                                      array('escape' => false,'class'=>'btn btn-info')
                                                                      );
                                                ?>
                                                <button type="button" class="btn btn-danger btnDelete" data-id="<?php echo $val->id; ?>">Delete</button>
                                                </td>
                                                <td>
                                                     <?php
                                                        if($val->review==UNDER_REVIEW){ ?>
                                                                                        <button type="button" class="btn btn-success btnReview btnReview<?php echo $val->id; ?>" data-id="<?php echo $val->id; ?>" data-value="<?php echo APPROVED; ?>">Approve</button>
                                                                                        <button type="button" class="btn btn-danger btnReview btnReview<?php echo $val->id; ?>" data-id="<?php echo $val->id; ?>" data-value="<?php echo REJECTED; ?>">Reject</button>
                                                                    <?php
                                                        }if($val->review==APPROVED && !empty($val->email)){ ?>
                                                                                        <button type="button" class="btn btn-success btnApproved">Approved</button>
                                                                    <?php 		}if($val->review==REJECTED && !empty($val->email)){ ?>
                                                            <button type="button" class="btn btn-danger btnRejected">Not Approved</button>
                                                                    <?php 		} ?>
                                                </td>
                                            </tr>
                                        <?php $i++; } ?>
                                        </tbody>
                                    </table>
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
            url:"<?php echo SITE_URL.'Affiliatesadmin/editstatus';?>",
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
               url:"<?php echo SITE_URL.'Affiliatesadmin/delete';?>",
               success:function(data) {
               }
            });
            $(this).closest('tr').remove();
         }
         
      });
      $('.btnReview').click(function(){
	    var id = $(this).data('id');
	    var valnew = $(this).data('value');
	    var thisChange = $(this).parent();
	    if (valnew=='4') {
		var com =confirm('Do you want to Approve this Affiliate ?');
            if (com==true) {
               var newBtn = '<button type="button" class="btn btn-success">Approved</button>';
            }else{
                return false;
            }
	    }if(valnew=='5'){
        var con =confirm('Do you want to Reject this Affiliate?');
        if (con==true) {
           var newBtn = '<button type="button" class="btn btn-danger">Not Approved</button>'
        }else{
            return false;
        }
	    }
           $.ajax({
               type:'POST',
               data:{id:id,value:valnew},
               url:"<?php echo SITE_URL.'Affiliatesadmin/approve';?>",
               success:function(data) {
                    location.reload();
                $('.btnReview'+id).remove();
                // alert(valnew);
                if (valnew == '4') {
                    var msg ='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><span class="entypo-thumbs-up"></span><strong></strong>&nbsp;&nbsp;This Affiliate has been approved.</div>'
                            $(".msg").html(msg);
                }
                if(valnew == '5'){
                    var msg ='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><span class="entypo-thumbs-down"></span><strong></strong>&nbsp;&nbsp;This Affiliate has been rejected.</div>'
                    $(".msg").html(msg);
                }
                $('.tr'+id+' .showNewbtn').append(newBtn);
                }
            });
            //$(this).closest('tr').remove();
        return false;
      });
   });
    
 
</script>
</body>

</html>
