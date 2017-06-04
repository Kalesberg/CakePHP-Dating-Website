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
                <li><a href="javascript:;" title="Sample page 1">Affiliate Management</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Referral List</a>
                </li>
               <!--<li class="pull-right">
                    <div class="input-group input-widget">
                       
                        
                    </div>
                </li>-->
            </ul>
            <!-- END OF BREADCRUMB -->
            <div class="content-wrap">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="nest" id="tableStaticClose">
                            <div class="title-alt">
                                <h6>
                                    Referral List</h6>
                                
                                <div class="titleClose">
                                    <a class="gone" href="#tableStaticClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#tableStatic">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>
                            </div>

                            <div class="body-nest" id="tableStatic">

                                <section id="flip-scroll">

                                    <table class="table table-bordered table-striped cf">
                                        <thead class="cf">
                                            <tr>
                                                <th style="width: 5%">S.No.</th>
                                                <th style="width: 10%">First Name</th>
                                                <th style="width: 10%">Last Name</th>
                                                <th style="width: 10%">Email</th>
                                                <th style="width: 10%">Contact</th>
                                                <th style="width: 10%">Refference</br></th>
                                                <th style="width: 10%">Status</th>
                                                <th style="width: 34%">Action</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($post as $val){ ?>
                                            <tr>
                                                <td style="width: 5%"><?php echo $val->id; ?></td>
                                                <td style="width: 10%"><?php echo $val->first_name; ?></td>
                                                <td style="width: 10%"><?php echo $val->last_name; ?></td>
                                                <td style="width: 10%"><?php echo $val->email; ?></td>
                                                <td style="width: 10%"><?php echo $val->contact; ?></td>
                                                <td style="width: 10%"><?php  $user = $this->Global->getUser($val->reference_id); echo $user['first_name']." ".$user['last_name']; ?></td>
                                                <td style="width: 10%">
                                                    <button type="button" class="btn btn-success">
                                                        Active
                                                    </button>
                                                </td>
                                                <td style="width: 34%">
                                                    <button type="button" class="btn btn-info">
                                                        <span class="entypo-pencil"></span>&nbsp;&nbsp;Edit
                                                    </button>
                                                    <button type="button" class="btn btn-danger">
                                                        <span class="icon icon-trash"></span>&nbsp;&nbsp;Delete
                                                    </button>
                                                </td>
                                               
                                            </tr>
                                            <?php } ?>
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
    <script type="text/javascript">
    $(function() {
        $('#footable-res2').footable().bind('footable_filtering', function(e) {
            var selected = $('.filter-status').find(':selected').text();
            if (selected && selected.length > 0) {
                e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                e.clear = !e.filter;
            }
        });

        $('.clear-filter').click(function(e) {
            e.preventDefault();
            $('.filter-status').val('');
            $('table.demo').trigger('footable_clear_filter');
        });

        $('.filter-status').change(function(e) {
            e.preventDefault();
            $('table.demo').trigger('footable_filter', {
                filter: $('#filter').val()
            });
        });

        $('.filter-api').click(function(e) {
            e.preventDefault();

            //get the footable filter object
            var footableFilter = $('table').data('footable-filter');

            alert('about to filter table by "tech"');
            //filter by 'tech'
            footableFilter.filter('tech');

            //clear the filter
            if (confirm('clear filter now?')) {
                footableFilter.clearFilter();
            }
        });
    });
    </script>

</body>

</html>
