    <?php echo $this->element('admin_header'); ?>
    <?php echo $this->element('admin_sidebar'); ?>
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
                <li><a href="#" title="Sample page 1">Admin Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="#" title="Sample page 1">Global Settings</a>
                </li>
                <li class="pull-right">
                    <div class="input-group input-widget">
                     <!--   <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">-->
                    </div>
                </li>
            </ul>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <div class="row">
                    <?php //echo $this->Flash->render() ?>
                    <div class="col-sm-12">
                        <div class="nest" id="validationClose">
                            <div class="title-alt">
                                <h6>
                                    Global Settings</h6>
                                <!--<div class="titleClose">
                                    <a class="gone" href="#validationClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#validation">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>-->
                            </div>
                            <div class="body-nest" id="validation">
                                
                                <div class="form_center">
                                <?php
                                    foreach($post as $val){ 
                                   ?>
                                        <fieldset>
                                            <div class="control-group col-md-10">
                                                <label class="control-label" for="<?php echo $val->slug; ?>"><?php echo $val->title; ?>*</label>
                                                <div class="controls">
                                                    <input type="<?php echo $val->type; ?>" class="form-control setingId<?php echo $val['id']; ?>" maxlength="<?php echo $val->maxlength; ?>" name="value" id="data" value='<?php echo $val->value ; ?>'>
                                                    <input type="hidden" value="<?php echo $val->id ?>" name="id"/>
                                                </div>
                                            </div>
                                            <div class="form-actions col-md-2"  >
                                                <button type="button" data-id="<?php echo $val->id; ?>" class="btn btn-primary updateButton" data-class="setingId<?php echo $val['id']; ?>"  style="margin-top: 27px;">Update</button>
                                            </div>
                                        </fieldset>
                                   
                                <?php  } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
             <?php echo $this->element('admin_footer'); ?>
            <?php  echo $this->element('admin_footer_js'); ?>
<!-- /MAIN EFFECT -->
    <script>
   $(function(){
      $('.updateButton').click(function(){
        var id = $(this).data('id');
        var inputclass = $(this).data('class');
        var inputval = $('.'+inputclass).val();
        $.ajax({
            type:'POST',
            data:{id:id,value:inputval},
            url:"<?php echo SITE_URL.'Globalsettings/globalsettingupdate';?>",
            success:function(data) {
            }
         });
    });
   });  
</script>
