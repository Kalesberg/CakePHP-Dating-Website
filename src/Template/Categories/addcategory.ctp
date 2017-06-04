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
                <li><a href="javascript:;" title="Sample page 1">Category Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Add Category</a>
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
                                <h6>Category Details
                                    </h6>
                               
                            </div>
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                      <?php echo $this->Form->Create($post,array('class'=>'form-horizontal','enctype'=>'multipart/form-data')); ?>
                                  <!--<form action="addcategory" id="" class="form-horizontal" enctype="multipart/form-data" method="post">-->
                                        <fieldset>
                                            <div class="text-center">
                                                <img src="#" class="avatar img-circle" alt="avatar" id="photo">
                                                <h6>Upload a different photo...</h6>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <span class="btn btn-primary btn-file">
                                                            Browse
                                                            <?php // echo $this->Form->create('category_icon',['type'=>'file','onchange'=>'readURL(this)','id'=>'photo']) ?>
                                                            <input type="file" onchange="readURL(this);" multiple="" value="" name="category_icon" id="photo">
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Title*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('category_name',['class'=>'form-control','required'=>true,'label'=>false]); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Show in Member Dashboard*</label>
                                                <div class="controls">
                                                    <?php $flag = array('1'=>'Yes','2'=>'No');echo $this->Form->select('flag', $flag, ['class'=>'form-control','required'=>'required']); ?>
                                                    
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Description</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('description',['class'=>'form-control','type'=>'textarea','label'=>false]); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="email">Status*</label>
                                                <div class="controls">
                                              
                                                <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['class'=>'form-control','required'=>'required']); ?>
                                                </div>
                                                   
                                            </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <?php echo $this->Html->Link("Cancel",['controller'=>'Categories','action'=>'categorylist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                                
                                            </div>
                                        </fieldset>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php echo $this->element('admin_footer'); ?>
<?php  echo $this->element('admin_footer_js');  ?>
<!-- /MAIN EFFECT -->

    <script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#photo')
                    .attr('src', e.target.result)
                    .width(100)
                    .height(100);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
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
    </script>
   
    <script type="text/javascript">
    function onAddTag(tag) {
        alert("Added a tag: " + tag);
    }

    function onRemoveTag(tag) {
        alert("Removed a tag: " + tag);
    }

    function onChangeTag(input, tag) {
        alert("Changed a tag: " + tag);
    }

    $(function() {

        $('#tags_1').tagsInput({
            width: 'auto'
        });
        $('#tags_2').tagsInput({
            width: 'auto',
            onChange: function(elem, elem_tags) {
                var languages = ['php', 'ruby', 'javascript'];
                $('.tag', elem_tags).each(function() {
                    if ($(this).text().search(new RegExp('\\b(' + languages.join('|') + ')\\b')) >= 0)
                        $(this).css('background-color', '#FBB44C');
                });
            }
        });
        $('#tags_3').tagsInput({
            width: 'auto',

            //autocomplete_url:'test/fake_plaintext_endpoint.html' //jquery.autocomplete (not jquery ui)
            autocomplete_url: 'test/fake_json_endpoint.html' // jquery ui autocomplete requires a json endpoint
        });


        // Uncomment this line to see the callback functions in action
        //          $('input.tags').tagsInput({onAddTag:onAddTag,onRemoveTag:onRemoveTag,onChange: onChangeTag});       

        // Uncomment this line to see an input with no interface for adding new tags.
        //          $('input.tags').tagsInput({interactive:false});
    });
    </script>
