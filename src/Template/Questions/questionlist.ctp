 
 <?php
 use Cake\ORM\TableRegistry;
 echo $this->element('admin_header'); ?>
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
                <li><a href="javascript:;" title="Sample page 1">Question Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Questions List</a>
                </li>
               <!--<li class="pull-right">
                    <div class="input-group input-widget">
                    </div>
                </li>-->
            </ul>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <div class="row">
                    <?php echo $this->Flash->render() ?>
					<div class="msg"></div>
                    <div class="col-sm-12">
                        <div class="nest" id="tableStaticClose">
                            <div class="title-alt">
                                <h6>
                                  <?php if(isset($id) && !empty($id)){ echo $this->Global->getCategoryName($id); }else{ 
                                    echo "Quetsions List"; } ?> </h6>
                                <?php echo  $this->Html->Link("Delete All",['controller'=>'Questions','action'=>'deleteall'],['class'=>'btn btn-primary back']); ?>

                            </div>
                            <div class="body-nest" id="tableStatic">
                                <section id="flip-scroll">
                                    <table class="table table-bordered table-striped cf">
                                        <thead class="cf">
                                            <tr>
                                                <th style="width: 5%">S.No.</th>
                                                <th style="width: 20%">Question</th>
                                                <th style="width: 10%">Category</th>
                                                <th style="width: 10%">User Email</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 40%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                if(isset($quest)){
                                                     $i=1;
                                                    foreach($quest as $val){
                                                        
                                                    ?>
                                                    <tr class="tr<?php echo $val->id; ?>">
                                                        <td style="width: 5%"><?php echo $i; ?></td>
                                                        <td style="width: 20%"><?php echo $val->question_text; ?></td>
                                                        <!--<td class="numeric"><?php //echo $val->option_1; ?></td>
                                                        <td class="numeric"><?php //echo $val->option_2; ?></td>
                                                        <td class="numeric"><?php //echo $val->option_3; ?></td>
                                                        <td class="numeric"><?php //echo $val->option_4; ?></td>-->
                                                        <td style="width: 10%">
                                                            <?php echo $this->Global->getCategoryName($val->category_id); ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                           <?php echo $val->email; ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <button type="button" class="btn btnStatus <?php echo $btnStatus[$val->status] ?>" data-id="<?php echo $val->id; ?>" data-status="<?php echo $val->status; ?>"><?php echo $status[$val->status]; ?></button>
                                                        </td>
                                                        <td style="width: 40%" class="showNewbtn">
                                                        <?php
                                                         echo   $this->Html->Link('<span class="entypo-pencil"></span>&nbsp;&nbsp;Edit',
                                                                              array('controller'=>'Questions','action'=>'editquestion',base64_encode($val->id)),
                                                                              array('escape' => false,'class'=>'btn btn-info')
                                                                              );
                                                        ?>
                                                          <!---<button type="button" class="btn btn-primary">
                                                              
                                                            </button>-->
                                                        <button type="button" class="btn btn-danger btnDelete" data-id="<?php echo $val->id; ?>">Delete</button>
                                                       
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
                                                         <!--<button type="button" class="btn btn-success btnApproved" style="display: none">Approved</button>-->
                                                         <!--<button type="button" class="btn btn-danger btnRejected" style="display: none">Not Approved</button>-->
                                                        </td>
                                                       
                                                    </tr>
                                                <?php $i++; }  
                                                }
                                                ?>
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
            url:"<?php echo SITE_URL.'Questions/editstatus';?>",
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
               url:"<?php echo SITE_URL.'Questions/delete';?>",
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
		confirm('Do you want to Approve this question?');
		var newBtn = '<button type="button" class="btn btn-success">Approved</button>';
	    }if(valnew=='5'){
		confirm('Do you want to Reject this question?');
		var newBtn = '<button type="button" class="btn btn-danger">Not Approved</button>'
	    }
           $.ajax({
               type:'POST',
               data:{id:id,value:valnew},
               url:"<?php echo SITE_URL.'Questions/approve';?>",
               success:function(data) {
				   
                $('.btnReview'+id).remove();
                // alert(valnew);
                if (valnew == '4') {
		    var msg ='<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">×</button><span class="entypo-thumbs-up"></span><strong></strong>&nbsp;&nbsp;This Question has been approved.</div>'
                    $(".msg").html(msg);
		}
		if(valnew == '5'){
		    var msg ='<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button><span class="entypo-thumbs-down"></span><strong></strong>&nbsp;&nbsp;This Question has been rejected.</div>'
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
