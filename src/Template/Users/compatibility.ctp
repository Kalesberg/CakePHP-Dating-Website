
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
                <li><a href="javascript:;" title="Sample page 1">Compatibility List</a>
                </li>
               <li class="pull-right">
                    <div class="input-group input-widget">
                        <form action="userlist" method="post">
                            <input type="checkbox" name="active" value="<?php echo ACTIVE; ?>" <?php if($statusA == ACTIVE){ ?> checked <?php ; } ?>/><label>Active</label>
                            <input type="checkbox" name="inactive" value="<?php echo INACTIVE; ?>" <?php if($statusI== INACTIVE){ ?> checked <?php ; } ?>/><label>Inactive</label>
                            <input style="border-radius:15px;width: 75%;" placeholder="Search..." class="form-control" type="text" name="searchvalue" value="<?php if(isset($searchvalue)){ echo $searchvalue; } ?>">
                            <button class="btn btn btn-primary pull-right" style="border-radius:15px" type="submit">
                            Search
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
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
                                                <th style="width: 10%">Contact</th>
                                                <th style="width: 10%">Membership</th>
												<th style="width: 10%">Compatibility<br/>Report</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 33%">Action</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                            <?php $i=1;
                                            foreach($post as $val){ ?>
                                            <tr>
                                                <td style="width: 5%"><?php echo $i; ?></td>
                                                <td style="width: 10%"><?php echo ucfirst($val->first_name)." ".ucfirst( $val->last_name); ?></td>
                       							
                                                <td style="width: 10%"><?php echo $val->email; ?></td>
                                                <td style="width: 10%"><?php if(!empty($val->phone)){ echo $val->phone; }else{ echo "N/A"; } ?></td>
                                                <td style="width: 10%"><?php echo $val->membership_level; ?></td>
												<td style="width: 10%"> 
												<?php
                                                 echo $this->Html->Link('<span class="entypo-folder"></span>&nbsp;&nbsp;View Reports',
                                                      array('controller'=>'Users','action'=>'compatibility',base64_encode($val->id)),
                                                      array('escape' => false,'class'=>'btn btn-primary')
                                                      );
                                                ?></td>
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
   
</body>

</html>
