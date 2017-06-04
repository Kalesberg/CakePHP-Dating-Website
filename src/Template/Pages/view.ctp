<body>
    <div class="site_content">
    <!--header-content-here-->
        <?php echo $this->element('home_header'); ?>
         <div class="warper">
            <section class="inner_page_header">
                <h2><?php echo $title; ?></h2>
            </section>
            <div class="container">
                  <?php echo $data->content;?>
            </div>
          
         </div>
        <?php echo $this->element('home_footer'); ?>
   </div>
   </div>
</body>
</html> 















   
   
  
  