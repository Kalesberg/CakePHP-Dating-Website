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
                <li><a href="javascript:;" title="Sample page 1">Cms Page Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Page List</a>
                </li>
               <!--<li class="pull-right">
                      <div class="input-group input-widget">
                        <form action="cmspagelist" method="post">
                            <input style="border-radius:15px;width: 75%;" placeholder="Search..." class="form-control" type="text" name="searchvalue" value="">
                            <button class="btn btn btn-primary pull-right" style="border-radius:15px" type="submit">
                            Search
                            </button>
                        </form>
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
                                    Cms Pages List</h6>
                                
                                <!--<div class="titleClose">
                                    <a class="gone" href="#tableStaticClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#tableStatic">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>-->
                            </div>

                            <div class="body-nest" id="tableStatic">

                                <section id="flip-scroll">

                                    <table class="table table-bordered table-striped cf">
                                        <thead class="cf">
                                            <tr>
                                                <th style="width: 5%">S.No.</th>
                                                <th style="width: 10%">Title</th>
                                                <th style="width: 10%">Slug</th>
                                                <th style="width: 10%">Meta Title</th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 46%">Action</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i=1;
                                            foreach($post as $val){ ?>
                                            <tr>
                                                <td style="width: 5%"><?php echo $i; ?></td>
                                                <td style="width: 10%"><?php $name =$val->title; $name= substr($name,'0','80'); echo $name; ?></td>
                                                <td style="width: 10%"><?php echo $val->slug; ?></td>
                                                <td class="num"style="width: 10%"><?php echo $val->meta_title; ?></td>
                                                <td style="width: 10%">
                                                     <button type="button" class="btn btnStatus <?php echo $btnStatus[$val->status] ?>" data-id="<?php echo $val->id; ?>" data-status="<?php echo $val->status; ?>"><?php echo $status[$val->status]; ?></button>
                                                </td>
                                                <td style="width: 46%">
                                                    <!--<a href="<?php //echo SITE_URL.$val->slug.'.html' ?>" class='btn btn-info' target='_blank'><span class="icon icon-preview"></span>&nbsp;&nbsp;Preview</a>-->
                                                    
                                                <a href="<?php echo SITE_URL.$val->slug.'.html' ?>" class='btn btn-info' target='_blank'><span class="entypo-folder"></span>&nbsp;&nbsp;View</a>
                                                
                                                    
                                                    <?php
                                                 echo   $this->Html->Link('<span class="entypo-pencil"></span>&nbsp;&nbsp;Edit',
                                                                      array('controller'=>'Cmspages','action'=>'editcmspage',base64_encode($val->id)),
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
            url:"<?php echo SITE_URL.'Cmspages/editstatus';?>",
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
               url:"<?php echo SITE_URL.'Cmspages/delete';?>",
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
