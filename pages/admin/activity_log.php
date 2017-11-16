<?php
include("../superadmin/settings.php");
?>
<html>
<head>
<!-- JQUERY BABY -->
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

  <title>Site Administration</title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="../../assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="../../assets/css/paper-dashboard.css" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="../../assets/css/demo.css" rel="stylesheet" />

    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="../../assets/css/themify-icons.css" rel="stylesheet">
</head>
<body>
  <div class="wrapper">

    <div class="sidebar" data-background-color="white" data-active-color="danger">

      <!--
      Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
      Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
    -->

        <div class="sidebar-wrapper">
              <div class="logo">
                  <a href="/" class="simple-text">
                      Home
                  </a>
              </div>

              <ul class="nav">
                <li>
                    <a href="settings.php">
                        <i class="ti-settings"></i>
                        <p>Settings</p>
                    </a>
                </li>
                <li>
                    <a href="admin.php">
                        <i class="ti-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                  <li>
                      <a href="programs.php">
                          <i class="ti-view-list-alt"></i>
                          <p>Programs</p>
                      </a>
                  </li>
                  <li>
                      <a href="metrics.php">
                          <i class="ti-view-list-alt"></i>
                          <p>Metrics</p>
                      </a>
                  </li>
                  <li>
                      <a href="data.php">
                          <i class="ti-check-box"></i>
                          <p>Approve Data</p>
                      </a>
                  </li>
                  <li>
                      <a href="methods.php">
                          <i class="ti-view-list-alt"></i>
                          <p>Methodology</p>
                      </a>
                  </li>
                  <li class="active">
                      <a href="#">
                          <i class="ti-view-list-alt"></i>
                          <p>Activity Log</p>
                      </a>
                  </li>
                  <li>
                      <a href="notifications.php">
                          <i class="ti-email"></i>
                          <p>Notifications</p>
                      </a>
                  </li>
              </ul>
        </div>
      </div>

    <div class="main-panel">
      <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">Departments</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                    </ul>
                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                              <div class="content table-responsive table-full-width">
                                <table id="activities" class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th>Activity Type</th>
                                          <th>Timestamp</th>
                                          <th>User</th>
                                          <th>Message</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $ch = curl_init();
                                    $url = $activity_db.'?$order=date%20desc';
                                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                      curl_setopt($ch, CURLOPT_URL, $url);
                                      $r = curl_exec($ch);
                                      $data = json_decode($r, true);
                                      $tbody = "";
                                      $max = count($data);
                                      for ($i = 0; $i < $max; $i++) {
                                        $tbody.="<tr><td>".$data[$i]["activity_type"]."</td><td>".$data[$i]["date"]."</td><td>".$data[$i]["user"]."</td><td>".$data[$i]["message"]."</td></tr>";
                                      }
                                      echo $tbody;
                                     ?>
                                  </tbody>
                              </table>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
</body>
<!--   Core JS Files   -->
<script src="../../assets/js/bootstrap.min.js" type="text/javascript"></script>

<!-- Paper Dashboard Core javascript and methods for Demo purpose -->
<script src="../../assets/js/paper-dashboard.js"></script>

<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="../../assets/js/demo.js"></script>
</html>
