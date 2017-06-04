
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
                <li><a href="javascript:;" title="Sample page 1">Membership Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Add Membership</a>
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
                                <h6>Membership Details
                                    </h6>
                               
                            </div>
                            <div class="body-nest" id="validation">
                                <div class="form_center">
                                    <?php echo $this->Form->create($post, array('url'=>array("action"=>'addmembership'),
                                        'class' =>'form-horizontal'
                                        //'id'=>'demo-form2',
                                       // 'inputDefaults' => array(
                                       // 'label' => false,
                                        //'div' => false
                                        )); ?>
                                    <!--<form action="addmembership" id="contact-form" method="post" context="validator" class="form-horizontal">-->
                                        <fieldset>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Membership Name*</label>
                                                <div class="controls">
                                                     <?php echo $this->Form->input('membership_name',array('class'=>'form-control','label'=>false)); ?>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="first_name">Price*</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('price',array('class'=>'form-control','label'=>false)); ?>
                                                
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="last_name">Description</label>
                                                <div class="controls">
                                                    <?php echo $this->Form->input('description',array('class'=>'form-control','type'=>'textarea','label'=>false)); ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="control-group"> 
                                                <label class="control-label" for="email">Status*</label>
                                                <div class="controls">
                                                      <?php $status = array(INACTIVE=>'Inactive',ACTIVE=>'Active');echo $this->Form->select('status', $status, ['class'=>'form-control']); ?>
                                                </div>
                                            </div>
                                            <div class="form-actions" style="margin:20px 0 0 0;">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <?php echo $this->Html->Link("Cancel",['controller'=>'Memberships','action'=>'membershiplist'],['class'=>'btn btn-default','escape'=>false]); ?>
                                            </div>
                                        </fieldset>
                                    <?php echo $this->Form->end()?>
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
    <script>
    $(document).ready(function() {
        //Validation
        $('#contact-form').validate({
            rules: {
                membership:{
                     minlength: 2,
                    required: true
                },
                
            highlight: function(element) {
                $(element).closest('.control-group').removeClass('success').addClass('error');
            },
            success: function(element) {
                element
                    .text('OK!').addClass('valid')
                    .closest('.control-group').removeClass('error').addClass('success');
            }
            }
        });
        });
        // MASKED INPUT

        $("#date").mask("99/99/9999", {
            completed: function() {
                alert("Your birthday was: " + this.val());
            }
        });
        $("#phone").mask("(999) 999-9999");

        $("#money").mask("99.999.9999", {
            placeholder: "*"
        });
        $("#ssn").mask("99--AAA--9999", {
            placeholder: "*"
        });


        //COLOR PICKER
        window.prettyPrint && prettyPrint();

        // Code for those demos
        var _createColorpickers = function() {
            $('#cp1').colorpicker({
                format: 'hex'
            });
            $('#cp2').colorpicker();
            $('#cp3').colorpicker();
            var bodyStyle = $('body')[0].style;
            $('#cp4').colorpicker().on('changeColor', function(ev) {
                bodyStyle.backgroundColor = ev.color.toHex();
            });
        }

        _createColorpickers();

        $('.bscp-destroy').click(function(e) {
            e.preventDefault();
            $('.bscp').colorpicker('destroy');
        });

        $('.bscp-create').click(function(e) {
            e.preventDefault();
            _createColorpickers();
        });


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
