<?php
$url = 'https://peter.demo.socrata.com/resource/6z67-xud9.json';
$ch = curl_init();

$username = getenv("username");
$password = getenv("password");
if(isset($_POST["department"])) {
  $data = array();
  $d = $_POST["department"];
  foreach($d as $k => $v) {
    array_push($data, array("departmentid"=>$k, "department"=>$v));
  }
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  $result = curl_exec($ch);
  $rd = json_decode($result, true);
  echo "<script>alert('Rows updated: ".$rd["Rows Updated"]."');</script>";
 } ?>
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
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="assets/css/paper-dashboard.css" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="assets/css/demo.css" rel="stylesheet" />

    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
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
                    <a href="admin.php">
                        <i class="ti-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                  <li class="active">
                      <a href="#">
                          <i class="ti-view-list-alt"></i>
                          <p>Departments</p>
                      </a>
                  </li>
                  <li>
                      <a href="admin_grid.php">
                          <i class="ti-view-list-alt"></i>
                          <p>Approve Data</p>
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
                          <div class="content">
                            <form>
                                <input type="text" id="department" placeholder="Department">
                              <input type="button" class="add-row" value="Add Department">
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>

                      <form name="departments" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                      <div class="row">
                          <div class="col-md-12">
                              <div class="card">
                                <div = "content">
                                  <input type="submit" value="Save" />
                                </div>
                              </div>
                            </div>
                          </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                              <div class="content table-responsive table-full-width">
                                <table id="users" class="table table-striped">
                                  <thead>
                                      <tr>
                                          <th>Remove</th>
                                          <th>Department ID</th>
                                          <th>Department</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $ch = curl_init();
                                    $url = 'https://peter.demo.socrata.com/resource/6z67-xud9.json?$order=departmentid%20asc';
                                    if(!isset($_POST["departments"])) {
                                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                      curl_setopt($ch, CURLOPT_URL, $url);
                                      $r = curl_exec($ch);
                                      $data = json_decode($r, true);
                                      $tbody = "";
                                      for ($i = 0; $i < count($data); $i++) {
                                        $tbody.="<tr><td><button></button></td><td>".$data[$i]["departmentid"]."</td><td><input name='department[".$data[$i]["departmentid"]."]' type='text' value='".$data[$i]["department"]."' /></td></tr>";
                                      }
                                      echo $tbody;
                                      }
                                     ?>
                                  </tbody>
                              </table>
                            </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
</body>
<!--   Core JS Files   -->
<script src="assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>

<!--  Checkbox, Radio & Switch Plugins -->
<script src="assets/js/bootstrap-checkbox-radio.js"></script>

<!--  Charts Plugin -->
<script src="assets/js/chartist.min.js"></script>

<!--  Notifications Plugin    -->
<script src="assets/js/bootstrap-notify.js"></script>

<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

<!-- Paper Dashboard Core javascript and methods for Demo purpose -->
<script src="assets/js/paper-dashboard.js"></script>

<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="assets/js/demo.js"></script>
</html>
