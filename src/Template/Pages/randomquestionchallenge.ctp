<?php
$user = $this->request->session()->read('Auth.User'); ?>
<style>
    .row {
    margin-left: -10px;
    margin-right: -10px;
    }
    .business_type_left::before {
    background: rgba(0, 0, 0, 0) url("../img/switch.png") no-repeat scroll 0 0;
    content: "";
    height: 21px;
    position: absolute;
    right: 0;
    top: 75px;
    width: 21px;
    z-index: 99;
    }
    .business_type_bg .business_type_left {
    padding-right: 30px;
    }
    .business_type_left {
    position: relative;
    }
    .col-md-6 {
    width: 50%;
    }
    .col-lg-6 {
    float: left;
    min-height: 1px;
    padding-left: 10px;
    }
    .form_fild_box {
    display: block;
    margin-bottom: 15px;
    position: relative;
    width: 100%;
    }
    .collapse.in {
    visibility: visible;
    }
    .form_fild_box label {
    color: #666666;
    display: block;
    font-family: "OpenSans-Bold";
    font-size: 17px;
    text-transform: uppercase;
    width: 100%;
    }
    label {
    font-weight: bold;
    margin-bottom: 5px;
    max-width: 100%;
    }
    .collapse.in {
    visibility: visible;
    }
    .businessType {
    display: block;
    }
    .multiple_new_box {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #adadad;
    border-radius: 4px;
    height: 115px;
    list-style: outside none none;
    margin: 0;
    overflow: auto;
    padding: 0;
    }
    .multiple_new_box li {
    border-bottom: 1px solid #adadad;
    border-radius: 4px 4px 0 0;
    cursor: pointer;
    width: 100%;
    }
    .multiple_new_box {
    list-style: outside none none;
    }
    .multiple_new_box {
    background: #fff none repeat scroll 0 0;
    border: 1px solid #adadad;
    border-radius: 4px;
    height: 115px;
    list-style: outside none none;
    margin: 0;
    overflow: auto;
    padding: 0;
    }
    .multiple_new_box li {
    border-bottom: 1px solid #adadad;
    border-radius: 4px 4px 0 0;
    cursor: pointer;
    display: block;
    width: 100%;
    }
    .multiple_new_box {
    list-style: outside none none;
    }
    .col-md-6 {
    float: left;
    min-height: 1px;
    padding-left: 10px;
    padding-right: 10px;
    position: relative;
    }
    .col-md-6 {
    width: 50%;
    }
    .multiple_new_box li span {
    color: #919191;
    display: block;
    padding: 10px;
    text-transform: uppercase;
    width: 100%;
}
</style>
<body>
    <div class="site_content">
    <!--header-content-here-->
    <?php echo $this->element('home_header'); ?>
    <div class="warper">
        <section class="inner_page_header">
            <h2>Random Challenge Survey</h2>
        </section>
        <?php echo $this->Form->create($post,['controller'=>'Pages','action'=>'randomquestions']); ?>
        <section class="question_bank_content">
            <div class="container">
                <div class="question_bank_head">
                    <input type="hidden" value="" class="valBusinessType" name="category_type">
                    <div class="row business_type_bg">
                        <div class="col-md-6 business_type_left">
                            <div class="form_fild_box">
                                <input value="type1,type2,type3" class="valBusinessType" name="category_type" maxlength="50" type="hidden"> 
                                <label>Question Categories:</label>
                                <div class="businessType">
                                    <ul class="ms_list multiple_new_box">
                                        <?php
                                        $users      =$this->Global->getUser($user['id']);
                                        $membership =$this->Global->getMembership($users['membership_level']);
                                        foreach($post as $val){
                                            if($membership['slug'] == 'gold'){
                                                if($val->id == INTIMATE){ ?>
                                            <?php }else{ ?>
                                                <li class="list_selectable list<?php echo $val->id;; ?>" data-key="<?php echo $val->id; ?>" data-val="<?php echo $val->category_name; ?>"><span><?php echo $val->category_name; ?></span></li>
                                          <?php  }
                                        ?>              
                                       <?php }else{ ?>
                                           <li class="list_selectable list<?php echo $val->id;; ?>" data-key="<?php echo $val->id; ?>" data-val="<?php echo $val->category_name; ?>"><span><?php echo $val->category_name; ?></span></li>
                                       <?php  } } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form_fild_box">
                                <label>Question Categories Listing:</label>
                                <div class="businessType">
                                    <ul class="ms_listNew multiple_new_box">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form_fild_box" style="width: 97%; margin-top:10px; ">
                                <label>Total Questions</label>
                                <div class="businessType">
                                  <?php echo $this->Form->input('totalqus',['label'=>false,'required'=>'required']); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="random_question_button">
                        <button class="random_question_button">NEXT</button>
                    </div>
                </div>
        </section>
        <?php echo $this->Form->end(); ?>
        </div>
        <?php echo $this->element('home_footer'); ?>
    </div>
    <script>
    function getvalue(id){
        var cat_name = document.getElementById('catName'+id).value;
        $('#categories').val(cat_name);
    }
	$('.question_bank_head .ms_list .list_selectable').click(function() {
	    var datakey = $(this).attr('data-key');
	    var dataval = $(this).attr('data-val');
	    var newLi = '<li data-val="' + dataval + '" data-key="' + datakey + '"><span>' + dataval + '</span></li>';
	    $('.ms_listNew').append(newLi);
	    $(this).hide();
	    var ids = [];
	    $(".question_bank_head .ms_listNew li").each(function() {
		var val = $(this).attr('data-key');
		ids.push(val);
	    });
	    $('.question_bank_head .valBusinessType').val(ids.toString());
	});
    
	$('.question_bank_head').on('click', '.ms_listNew li', function() {
	    var datakey = $(this).attr('data-key');
	    $(this).remove();
	    $('.list_selectable.list' + datakey).show();
	    var ids = [];
	    $(".question_bank_head .ms_listNew li").each(function() {
		var val = $(this).attr('data-key');
		ids.push(val);
	    });
	    $('.question_bank_head .valBusinessType').val(ids.toString());
	});
         
    </script>
</body>
</html>

