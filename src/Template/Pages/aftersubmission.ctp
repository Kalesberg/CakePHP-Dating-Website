<?php
$user = $this->request->session()->read('Auth.User'); ?>
<body>
    <div class="site_content">
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
                <h2>Survey</h2>
            </section>
            <section class="question_detail_content">
                <div class="container">
                    <div class="question_detail_head">
                    <h2>
                       
                        <?php //if(!empty($category)){ echo $category->category_name; } ?>
                    </h2>
                     <?php echo $this->Flash->render(); ?>
                    <div class="favorite_list_button">
                        <?php //echo $this->Html->Link("Favorite list",['controller'=>'Pages','action'=>'favouritelist']); ?>
                    </div>
                </div>
                <div class="add_survey_button">
                <?php echo $this->Html->Link("Send Another Survey",['controller'=>'Pages','action'=>'questionbank']); ?>
                   <?php echo $this->Html->Link("My Dashboard",['controller'=>'Pages','action'=>'memberdashboard',base64_encode($user['id'])]); ?>
                 
                </div>
                
             
            </div>
        </section>
    </div>
    <?php echo $this->element('home_footer'); ?>
    </div>
<?php //echo $site_url=SITE_URL; ?>

</body>

</html>