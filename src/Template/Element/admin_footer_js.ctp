<script>
    var WEBROOT = '<?php echo WEBROOT; ?>';   
</script>
<?php
echo $this->Html->script(['progress-bar/src/jquery.velocity.min',
                          //'progress-bar/number-pb',
                          'progress-bar/progress-app',
                          'preloader',
                          'bootstrap',
                          'app',
                          'load',
                          'main',
                         // 'chart/jquery.flot',
                          //'chart/jquery.flot.resize',
                         // 'chart/realTime',
                          //'speed/canvasgauge-coustom',
                         // 'countdown/jquery.countdown',
                     //     'jhere-custom',
                         'jquery.min',
                      //    'map/gmap3',
                          'jquery.cookie',
                          //'colorPicker/bootstrap-colorpicker.min',
                         // 'inputMask/jquery.maskedinput',
                          'switch/bootstrap-switch',
                         // 'validate/jquery.validate.min',
                         // 'idealform/jquery.idealforms',
                          'timepicker/bootstrap-timepicker',
                          'datepicker/bootstrap-datepicker',
                         // 'datepicker/clockface',
                          'datepicker/bootstrap-datetimepicker',
                        //  'tag/jquery.tagsinput',
						  'jquery-1.12.4.min',
						  'html5',
                          'toggle_close',
                          'footable/js/footable.js?v=2-0-1',
                    //      'footable/js/footable.sort.js?v=2-0-1',
                      //    'footable/js/footable.filter.js?v=2-0-1',
                      //    'footable/js/footable.paginate.js?v=2-0-1',
                          ]);
?>
<?php echo $this->Html->script('ckeditor/ckeditor', array('inline' => false));?>
<script src="https://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
