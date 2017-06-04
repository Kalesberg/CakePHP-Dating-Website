<?php ?>
<!doctype html>
<html>
<head>
     <?= $this->Html->charset() ?>
<meta charset="utf-8">

<title>Self-Match</title>


<?php 
   // pr($this->request->params);
   // pr($meta_title_description);
?>

<?php
//$link = $meta_title_description;
$titleDescription =[];
foreach($meta_title_description as $key => $value){
    $link[$key] = $value['link'];
    $titleDescription[str_replace('/','_',$value['link'])]  = $value;
}
       // pr($titleDescription);
        if($this->request->params['action'] == 'questionsdetails'){
            $current_link = $this->request->params['controller']."/".$this->request->params['action']."/".$this->request->params['pass'][0];
            
        }else if(!empty($this->request->params['pass'][0])){
            $current_link = $this->request->params['controller']."/".$this->request->params['action']."/".$this->request->params['pass'][0];
            $current_link = strtolower($current_link);      
        }else{
            $current_link = $this->request->params['controller']."/".$this->request->params['action'];
            $current_link = strtolower($current_link);      
        }
        
        if (in_array($current_link, $link))
            {
            //echo "Match found";
                $detail = $titleDescription[str_replace('/','_',$current_link)];
                ?>
                <meta name="title" content="<?php echo $detail['meta_title']; ?>">
                <meta name="description" content="<?php echo $detail['meta_description']; ?>">
               <?php
            }
          else
            {
                ?>
                <meta name="title" content="Self-Match.com |Explore Compatibility with Your Partner">
                <meta name="description" content="Meet Self-Match, a unique tool that compares your and your partner’s responses to the survey that you've created and puts YOU in control of the matching process. ">
<?php
            }
?>

<!--responsive-meta-here-->
<meta name="viewport" content="minimum-scale=1.0, maximum-scale=1.0,width=device-width, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="google-site-verification" content="iTIz5EqZErKeef116KsHCfCphckZ2V_f_82Z8bnevYw" />
<!--responsive-meta-end-->
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
   <?php 
   
    echo $this->Html->css(['home_style'],['media' => 'screen']); 
    echo $this->Html->css(['print'],['media' => 'print']);
    echo $this->Html->script(['1.7.2.jquery.min',
                              'html5',
                             'jquery.flexslider-min',
                             'css3-animate-it',
                             'jquery-ui-1.10.4.custom',
                             'print'
                              ]);
							  
	echo $this->Html->css(['animations','flexslider','responsive']);						  
    ?>
    <script type="text/javascript">
        var SITE_URL = '<?php echo SITE_URL; ?>';
    $(window).load(function() {
        $('.flexslider').flexslider();
    });
    </script>
<!--slider-js-end-->

<!--ie-support-html5-tag-js-here-->

<!--ie-support-html5-tag-js-end-->

<!--menu-toggel-script-here-->
<script type="text/javascript">
  $(document).ready(function() {
  $('.menu_toggel').click(function() {
  $('.head_right').slideToggle('fast');
  });
  });
</script> 
<!--menu-toggel-script-end-->
</head>
    <?php echo $this->fetch('content'); ?>
</html>

