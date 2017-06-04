
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
                <li><a href="javascript:;" title="Sample page 1">Category Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Category List</a>
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
                    <div class="col-sm-12">
                        <div class="nest" id="tableStaticClose">
                            <div class="title-alt">
                                <h6>
                                    Category List</h6>
                             <?php echo  $this->Html->Link("Delete All",['controller'=>'Categories','action'=>'deleteall'],['class'=>'btn btn-primary back']); ?>
                            </div>

                            <div class="body-nest" id="tableStatic">
                                <section id="flip-scroll">
                                    <table class="table table-bordered table-striped cf">
                                        <thead class="cf">
                                            <tr>
                                                <th style="width: 5%">S.No.</th>
                                                <th style="width: 14%">Title</th>
                                                <th style="width: 25%">Icon</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 45%">Action</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $i=1;
                                            foreach($post as $val){
                                                $id    =$val->id;
                                                $category_name  =$val->category_name;
                                                $category_icon  =$val->category_icon;
                                                $DBstatus         =$val->status;
                                            ?>
                                            <tr>
                                                <td style="width: 5%"><?php echo $i; ?></td>
                                                <td style="width: 14%"><?php $category_name= substr($category_name,'0','80'); echo $category_name; ?></td>
                                                <td  style="width: 25%"><?php if(!empty($category_icon)){ echo $this->Html->image($category_icon,array('height'=>'100','width'=>'100'));}else{ echo $this->Html->image("small-bg10.jpg",array('height'=>'100','width'=>'100')); } ?></td>
                                                <td style="width: 10%">
                                                     <button type="button" class="btn btnStatus <?php echo $btnStatus[$DBstatus] ?>" data-id="<?php echo $id; ?>" data-status="<?php echo $DBstatus; ?>"><?php echo $status[$DBstatus]; ?></button>
                                                </td>
                                                <td style="width: 45%">
                                                <?php
                                                 echo $this->Html->Link('<span class="entypo-eye"></span>&nbsp;&nbsp;View Questions',
                                                                      array('controller'=>'Questions','action'=>'questionlist',base64_encode($id)),
                                                                      array('escape' => false,'class'=>'btn btn-info')
                                                                      );
                                                ?>
                                                <?php
                                                 echo   $this->Html->Link('<span class="entypo-pencil"></span>&nbsp;&nbsp;Edit',
                                                                      array('controller'=>'Categories','action'=>'editcategory',base64_encode($id)),
                                                                      array('escape' => false,'class'=>'btn btn-info')
                                                                      );
                                                ?>
                                                  <!---  <button type="button" class="btn btn-primary">
                                                      
                                                    </button>-->
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
            url:"<?php echo SITE_URL.'Categories/editstatus';?>",
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
               url:"<?php echo SITE_URL.'Categories/delete';?>",
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
