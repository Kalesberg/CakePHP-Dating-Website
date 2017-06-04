<body>
    <div class="site_content">
    <!--header-content-here-->
        <?php echo $this->element('home_header'); ?>
        <div class="warper">
            <section class="inner_page_header">
             <h2>About Us</h2>
            </section>
            <section class="about_content">
                <div class="container">
                    <div class="about_list">
                        <ul>
                            <li>
                                <div class="about_image_box">
                                    <a href="javascript:;">
                                    <?php echo $this->Html->image('about_img01.png'); ?></a>
                                </div>
                                <div class="about_image_des">
                                    <h2>Lorem ipsum dolor sit amet</h2>
                                    <p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. </p>
                                    <p>Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus. Quisque lorem tortor fringilla sed, </p>
                                </div>
                            </li>
                            <li>
                                <div class="about_image_box">
                                    <a href="javascript:;"><?php echo $this->Html->image('about_img02.png'); ?></a>
                                </div>
                                <div class="about_image_des">
                                    <h2>Lorem ipsum dolor sit amet</h2>
                                    <p>Lorem ipsum dolor sit amet enim. Etiam ullamcorper. Suspendisse a pellentesque dui, non felis. Maecenas malesuada elit lectus felis, malesuada ultricies. Curabitur et ligula. Ut molestie a, ultricies porta urna. Vestibulum commodo volutpat a, convallis ac, laoreet enim. </p>
                                    <p>Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. Vestibulum dapibus, mauris nec malesuada fames ac turpis velit, rhoncus eu, luctus et interdum adipiscing wisi. Aliquam erat ac ipsum. Integer aliquam purus. Quisque lorem tortor fringilla sed, </p>
                                </div>
                            </li>
                        </ul>    
                    </div>
                </div>
            </section>
        </div>
        <?php echo $this->element('home_footer'); ?>
    </div>
</body>
</html>