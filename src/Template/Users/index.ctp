<body>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status">&nbsp;</div>
    </div>
    <?php echo $this->element('admin_header'); ?>
    <?php echo $this->element('admin_sidebar'); ?>
    <!--  PAPER WRAP -->
    <div class="wrap-fluid">
        <div class="container-fluid paper-wrap bevel tlbr">
            <?php echo $this->element('admin_title'); ?>
            <!-- BREADCRUMB -->
            <ul id="breadcrumb">
                <li>
                    <span class="entypo-home"></span>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Home</a>
                </li>
                <li><i class="fa fa-lg fa-angle-right"></i>
                </li>
                <li><a href="javascript:;" title="Sample page 1">Dashboard</a>
                </li>
                <!-- <li class="pull-right">
                    <div class="input-group input-widget">
                        <input style="border-radius:15px" type="text" placeholder="Search..." class="form-control">
                    </div>
                </li>-->
            </ul>
            <!-- END OF BREADCRUMB -->
            <!--<div id="paper-middle">
                <div id="mapContainer"></div>
            </div>-->
            <!--  DEVICE MANAGER -->
            <div class="content-wrap">
                 <?php echo $this->Flash->render() ?>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="profit" id="profitClose">
                            <div class="headline ">
                                <h3>
                                    <span>
                                    <i class="icon icon-user-group"></i> &#160;&#160;Total Users</span>
                                </h3>
                                <!--<div class="titleClose">
                                    <a href="#profitClose" class="gone">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>-->
                            </div>
                            <div class="value">
                              <!--  <span class="pull-left"><i class="entypo-clock clock-position"></i>
                                </span>-->
                                <div id="getting-started">
                                    <span><?php echo $total; ?></span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="progress-tinny">
                                <div style="width: 50%" class="bar"></div>
                            </div>
                           <!-- <div class="profit-line">
                                <i class="fa fa-caret-up fa-lg"></i>&#160;&#160; 50% &#160;From Last Hour</div>-->
                        </div>
                    </div>
                   <div class="col-sm-3">
                        <div class="revenue" id="revenueClose">
                            <div class="headline ">
                                <h3>
                                    <span>
                                    <i class="icon icon-user"></i>&#160;&#160;Total Visitors</span>
                                </h3>
                                <!--<div class="titleClose">
                                    <a href="#revenueClose" class="gone">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>-->
                            </div>
                            <div class="value">
                                  </sapn></sapn><?php echo $visitor; ?>
                            </div>
                            <div class="progress-tinny">
                                <div style="width: 25%" class="bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="order" id="orderClose">
                            <div class="headline ">
                                <h3>
                                    <span>
                                    <i class="icon icon-user"></i>&#160;&#160;Total Gold Member</span>
                                </h3>
                               <!-- <div class="titleClose">
                                    <a href="#orderClose" class="gone">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>-->
                            </div>
                            <div class="value">
                                </sapn></sapn><?php echo $gold; ?>
                            </div>
                            <div class="progress-tinny">
                                <div style="width: 10%" class="bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class=" member" id="memberClose">
                            <div class="headline ">
                                <h3>
                                    <span>
                                        <i class="icon icon-user"></i>
                                        &#160;&#160;Total Platinum Users
                                    </span>
                                </h3>
                                <!--<div class="titleClose">
                                    <a href="#memberClose" class="gone">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>-->
                            </div>
                            <div class="value">
                                <span>
                                </span><?php echo $platinum; ?>
                            </div>
                            <div class="progress-tinny">
                                <div style="width: 50%" class="bar"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="profit" id="profitClose">
                            <div class="headline ">
                                <h3>
                                    <span>
                                    <i class="icon icon-user-group"></i> &#160;&#160;Affiliates</span>
                                </h3>
                                <!--<div class="titleClose">
                                    <a href="#profitClose" class="gone">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>-->
                            </div>
                            <div class="value">
                              <!--  <span class="pull-left"><i class="entypo-clock clock-position"></i>
                                </span>-->
                                <div id="getting-started">
                                    <span><?php echo $totalAffiliat; ?></span>
                                    <span></span>
                                </div>
                            </div>
                            <div class="progress-tinny">
                                <div style="width: 50%" class="bar"></div>
                            </div>
                           <!-- <div class="profit-line">
                                <i class="fa fa-caret-up fa-lg"></i>&#160;&#160; 50% &#160;From Last Hour</div>-->
                        </div>
                    </div>
                </div>
            </div>
            <!--  / DEVICE MANAGER -->
            <!--<div class="content-wrap">
                <div class="row">
                    <div class="col-sm-8">
                        <div id="siteClose" class="nest">
                            <div class="title-alt">
                                <h6>
                                    <span class="fontawesome-truck"></span>&nbsp;Destination</h6>
                                <div class="titleClose">
                                    <a class="gone" href="#siteClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#site">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>
                            </div>
                            <div id="site" class="body-nest" style="min-height:296px;">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="armada-devider">
                                                    <div class="armada">
                                                        <span style="background:#65C3DF">
                                                            <span class="maki-bus"></span>&nbsp;&nbsp;Trans Berlin</span>
                                                        <p>
                                                            <span class="entypo-gauge"></span>&nbsp;12 Km/<i>Hours</i>
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="driver-devider">
                                                    <img class="armada-pic img-circle" alt="" src="http://api.randomuser.me/portraits/thumb/men/14.jpg">
                                                    <h3>Mark Zukenbeirg</h3>
                                                    <br>
                                                    <p>Driver</p>
                                                </td>
                                                <td class="progress-devider">
                                                    <section id="basic">
                                                        <article>

                                                            <div class="number-pb">
                                                                <div class="number-pb-shown"></div>
                                                                <div class="number-pb-num">0</div>
                                                            </div>
                                                        </article>
                                                    </section>
                                                    <span class="label pull-left">Berlin</span>
                                                    <span class="label pull-right">Muchen</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="armada-devider">
                                                    <div class="armada">
                                                        <span style="background:#45B6B0">
                                                            <span class="fontawesome-plane"></span>&nbsp;&nbsp;Fly Airlines</span>
                                                        <p>
                                                            <span class="entypo-gauge"></span>&nbsp;600 Km/<i>Hours</i>
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="driver-devider">
                                                    <img class="armada-pic img-circle" alt="" src="http://api.randomuser.me/portraits/thumb/men/38.jpg">
                                                    <h3>Marko Freytag</h3>
                                                    <br>
                                                    <p>Pilot</p>
                                                </td>
                                                <td class="progress-devider">
                                                    <section id="percentage">
                                                        <article>
                                                            <div class="number-pb">
                                                                <div class="number-pb-shown dream"></div>
                                                                <div class="number-pb-num">0%</div>
                                                            </div>
                                                        </article>
                                                    </section>
                                                    <span class="label pull-left">Berlin</span>
                                                    <span class="label pull-right">London</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="armada-devider">
                                                    <div class="armada">
                                                        <span style="background:#FF6B6B">
                                                            <span class="maki-aboveground-rail"></span>&nbsp;&nbsp;Fazttrain</span>
                                                        <p>
                                                            <span class="entypo-gauge"></span>&nbsp;40 Km/<i>Hours</i>
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="driver-devider">
                                                    <img class="armada-pic img-circle" alt="" src="http://api.randomuser.me/portraits/thumb/men/39.jpg">
                                                    <h3>Dieter Gruenewald</h3>
                                                    <br>
                                                    <p>machinist</p>
                                                </td>
                                                <td class="progress-devider">
                                                    <section id="step">
                                                        <article>
                                                            <div class="number-pb">
                                                                <div class="number-pb-shown sun"></div>
                                                                <div class="number-pb-num">0/0</div>
                                                            </div>
                                                        </article>
                                                    </section>
                                                    <span class="label pull-left">Berlin</span>
                                                    <span class="label pull-right">Dortmund</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div id="RealTimeClose" class="nest">
                            <div class="title-alt">
                                <h6>
                                    <span class="fontawesome-resize-horizontal"></span>&nbsp;Direction</h6>
                                <div class="titleClose">
                                    <a class="gone" href="#RealTimeClose">
                                        <span class="entypo-cancel"></span>
                                    </a>
                                </div>
                                <div class="titleToggle">
                                    <a class="nav-toggle-alt" href="#RealTime">
                                        <span class="entypo-up-open"></span>
                                    </a>
                                </div>
                            </div>
                            <div id="RealTime" style="min-height:296px;padding-top:20px;" class="body-nest">
                                <ul class="direction">
                                    <li>
                                        <span class="direction-icon maki-fuel" style="background:#FF6B6B"></span>
                                        <h3>
                                            <span>Gas Station</span>
                                        </h3>
                                        <p>5 Km Foward&nbsp;&nbsp;<i class="fa fa-arrow-circle-up"></i>
                                        </p>
                                        <p><i>Estimated time </i>:&nbsp;<i class="fa fa-clock-o"></i>&nbsp;&nbsp;20 Min</p>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <span class="direction-icon maki-fast-food" style="background:#65C3DF"></span>
                                        <h3>
                                            <span>Restourant</span>
                                        </h3>
                                        <p>1 Km Turn Left&nbsp;&nbsp;<i class="fa fa-reply"></i>
                                        </p>
                                        <p><i>Estimated time </i>:&nbsp;<i class="fa fa-clock-o"></i>&nbsp;&nbsp;20 Min</p>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <span class="direction-icon maki-giraffe" style="background:#45B6B0"></span>
                                        <h3>
                                            <span>Zoo</span>
                                        </h3>
                                        <p>3 Km Turn Right &nbsp;&nbsp;<i class="fa fa-share"></i>
                                        </p>
                                        <p><i>Estimated time </i>:&nbsp;<i class="fa fa-clock-o"></i>&nbsp;&nbsp;20 Min</p>
                                    </li>
                                    <li class="divider"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-wrap">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="chart-wrap">
                            <div class="chart-dash">
                                <!-- <div id="sparkline"></div> 
                                <div id="placeholder" style="width:100%;height:200px;"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="speed">
                                        <h2>Speed Avarage</h2>
                                        <h1>74
                                            <span>Km / hours</span>
                                        </h1>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="traffic">
                                        <h2>Traffic per day</h2>
                                        <h1>2.5874</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="traffic-rates">
                                        <h4>Traffic Rates</h4>
                                        <h1>30 %
                                            <span>-1,3 %</span>
                                        </h1>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="traffic-rates">
                                        <h4>Traffic Rates</h4>
                                        <h1>30 %
                                            <span>-1,3 %</span>
                                        </h1>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="traffic-rates">
                                        <h4>Traffic Rates</h4>
                                        <h1>30 %
                                            <span>-1,3 %</span>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="weather-wrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="temperature"><b>Monday</b>, 07:30 AM
                                        <span>F</span>
                                        <span><b>C</b>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="weather-icon">
                                        <i class="wi-day-lightning"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="weather-text">
                                        <h2>Berlin
                                            <br><i>Day Lightning</i>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="weather-text">
                                        <h3><i class="wi-thermometer"></i>18<i class="wi-celcius"></i>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="weather-dash">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="daily-weather">
                                            <h2>Mon</h2>
                                            <h3>85
                                                <span><i class="wi-fahrenheit"></i>
                                                </span>
                                            </h3>
                                            <h4>
                                                <span><i class="wi-day-lightning"></i>
                                                </span>
                                            </h4>
                                            <h5>15
                                                <i>km/h</i>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="daily-weather">
                                            <h2>Tue</h2>
                                            <h3>81
                                                <span><i class="wi-fahrenheit"></i>
                                                </span>
                                            </h3>
                                            <h4><i class="wi-day-cloudy"></i>
                                            </h4>
                                            <h5>12
                                                <i>km/h</i>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="daily-weather">
                                            <h2>Wed</h2>
                                            <h3>83
                                                <span><i class="wi-fahrenheit"></i>
                                                </span>
                                            </h3>
                                            <h4><i class="wi-rain-mix"></i>
                                            </h4>
                                            <h5>14
                                                <i>km/h</i>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="daily-weather">
                                            <h2>Thu</h2>
                                            <h3>80
                                                <span><i class="wi-fahrenheit"></i>
                                                </span>
                                            </h3>
                                            <h4><i class="wi-day-sunny"></i>
                                            </h4>
                                            <h5>15
                                                <i>km/h</i>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="daily-weather">
                                            <h2>Fri</h2>
                                            <h3>79
                                                <span><i class="wi-fahrenheit"></i>
                                                </span>
                                            </h3>
                                            <h4><i class="wi-day-storm-showers"></i>
                                            </h4>
                                            <h5>11
                                                <i>km/h</i>
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="daily-weather">
                                            <h2>Sat</h2>
                                            <h3>82
                                                <span><i class="wi-fahrenheit"></i>
                                                </span>
                                            </h3>
                                            <h4><i class="wi-cloudy"></i>
                                            </h4>
                                            <h5>10
                                                <i>km/h</i>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /END OF CONTENT -->

    <?php echo $this->element('admin_footer'); ?>
    <?php echo $this->element('admin_rightsidebar'); ?>
    <?php  echo $this->element('admin_footer_js');  ?>
    <script>
    var gauge4 = new Gauge("canvas4", {
        'mode': 'needle',
        'range': {
            'min': 0,
            'max': 90
        }
    });
    gauge4.draw(Math.floor(Math.random() * 90));
    var run = setInterval(function() {
        gauge4.draw(Math.floor(Math.random() * 90));
    }, 3500);
    </script>
    <script type="text/javascript">
    /* Javascript
     *
     * See http://jhere.net/docs.html for full documentation
     */
    $(window).on('load', function() {
        $('#mapContainer').jHERE({
            center: [52.500556, 13.398889],
            type: 'smart',
            zoom: 10
        }).jHERE('marker', [52.500556, 13.338889], {
            icon: 'assets/img/marker.png',
            anchor: {
                x: 12,
                y: 32
            },
            click: function() {
                alert('Hallo from Berlin!');
            }
        })
            .jHERE('route', [52.711, 13.011], [52.514, 13.453], {
                color: '#FFA200',
                marker: {
                    fill: '#86c440',
                    text: '#'
                }
            });
    });
    </script>
    <script type="text/javascript">
    var output, started, duration, desired;

    // Constants
    duration = 5000;
    desired = '50';

    // Initial setup
    output = $('#speed');
    started = new Date().getTime();

    // Animate!
    animationTimer = setInterval(function() {
        // If the value is what we want, stop animating
        // or if the duration has been exceeded, stop animating
        if (output.text().trim() === desired || new Date().getTime() - started > duration) {
            console.log('animating');
            // Generate a random string to use for the next animation step
            output.text('' + Math.floor(Math.random() * 60)

            );

        } else {
            console.log('animating');
            // Generate a random string to use for the next animation step
            output.text('' + Math.floor(Math.random() * 120)

            );
        }
    }, 5000);
    </script>
    <script type="text/javascript">
    $('#getting-started').countdown('2015/01/01', function(event) {
        $(this).html(event.strftime(

            '<span>%M</span>' + '<span class="start-min">:</span>' + '<span class="start-min">%S</span>'));
    });
    </script>
