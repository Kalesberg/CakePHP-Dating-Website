 <?php
 use Cake\ORM\TableRegistry;
 echo $this->element('admin_header'); ?>
        <?php echo $this->element('admin_sidebar');
        $status = [ACTIVE=>'Active',INACTIVE=>'Inacttive'];
         $btnStatus = [ACTIVE=>'btn-success',INACTIVE=>'btn-danger'];?>
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
                <li><a href="javascript:;" title="Sample page 1">Promo Code Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Promo Code List</a>
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
                                    Promo Code List</h6>
                            <div class="body-nest" id="tableStatic">
                                <section id="flip-scroll">
                                    <table class="table table-bordered table-striped cf">
                                        <thead class="cf">
                                            <tr>
                                                <th style="width: 5%">S.No.</th>
                                                <th style="width: 10%">Title</th>
                                                <th style="width: 10%">Price</th>
                                                <th style="width: 10%">Type</th>
                                                <th style="width: 10%">Duration</th>
                                                <th style="width: 10%">Description</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 33%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;
                                           // pr($post);die;
                                            foreach($post as $val){ ?>
                                            <tr>
                                                <td style="width: 5%"><?php echo $i; ?></td>
                                                <td style="width: 10%"><?php $name =$val->promocode_title; $name= substr($name,'0','80'); echo $name; ?></td>
                                                <td style="width: 10%"><?php echo $val->price; ?></td>
                                                <td style="width: 10%"><?php
                                                $membership =$this->Global->getMembership($val->type);
                                                echo $membership['membership_name'];
                                                ?>
                                                </td>
                                                <td style="width: 10%"><?php echo $val->duration;
                                                ?></td>
                                                <td style="width: 10%"><?php echo $val->description; ?></td>
                                                <td style="width: 10%">
                                                     <button type="button" class="btn btnStatus <?php echo $btnStatus[$val->status] ?>" data-id="<?php echo $val->id; ?>" data-status="<?php echo $val->status; ?>"><?php echo $status[$val->status]; ?></button>
                                                </td>
                                                <td style="width: 33%">
                                                <?php
                                                // echo $this->Html->Link('<span class="entypo-pencil"></span>&nbsp;&nbsp;Edit',
                                                  //                    array('controller'=>'Promocodes','action'=>'editpromocode',base64_encode($val->id)),
                                                    //                  array('escape' => false,'class'=>'btn btn-info')
                                                      //                );
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
            url:"<?php echo SITE_URL.'Promocodes/editstatus';?>",
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
               url:"<?php echo SITE_URL.'Promocodes/delete';?>",
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
