<?php
include("../superadmin/settings.php");

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $departments_db.'?$where=departmentid='."'1'");
$result = curl_exec($ch);
$departments = json_decode($result, true);
$department = $departments[0]["department"];

$username = getenv("username");
$password = getenv("password");

curl_setopt($ch, CURLOPT_URL, $socrata_users_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Socrata-Host:opendata.socrata.com"));
$result = curl_exec($ch);
$users = json_decode($result, true);
$users_array = $users["results"];

if(isset($_POST["users"])) {
  $data = array();
  $u = $_POST["users"];
  $d = $_POST["departments"];
  $deleted = $_POST["delete"];
  $added = $_POST["add"];
  $del_ids = array();
  foreach($deleted as $td => $dept) {
    array_push($del_ids, $td);
  }
  $add_ids = array();
  foreach($added as $ta => $dept_a) {
    array_push($add_ids, $ta);
  }
  foreach($u as $k => $v) {
    $update = date("c");
    if(in_array($k, $del_ids) && !in_array($k, $add_ids)) {
      array_push($data, array("userid"=>$k, "email"=>$v, "departmentid"=>$d[$k], "updated"=>$update,"isdeleted"=>"true"));
    } else {
      array_push($data, array("userid"=>$k, "email"=>$v, "departmentid"=>$d[$k], "updated"=>$update,"isdeleted"=>"false"));
    }
  }
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $users_db);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_exec($ch);


  $activity_data = array("activity_type"=>"User Update","date"=>$update,"user"=>$username,"message"=>json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $activity_db);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($activity_data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_exec($ch);

  $DeptCanEdit = false;
}
  ?>


  <html>
  <head>
  <!-- JQUERY BABY -->
  <script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
    integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
    crossorigin="anonymous"></script>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title>Notifications</title>

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
                      <a href="users.php">
                          <i class="ti-user"></i>
                          <p>Users</p>
                      </a>
                  </li>
                    <li>
                        <a href="data.php">
                            <i class="ti-check-box"></i>
                            <p>Approve Data</p>
                        </a>
                    </li>
                    <?php if($DeptCanEdit){
                      echo "<li><a href='goals.php'><i class='ti-view-list-alt'></i><p>Approve Data</p></a></li>";
                      }
                    ?>
                    <li class="active">
                        <a href="#">
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
                        <a class="navbar-brand" href="#">Notifications</a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                        </ul>
                    </div>
                </div>
            </nav>


          <div class="content">
              <div class="container-fluid">
                <form name="departments" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">

                  <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <div class="content">
                          <input type="submit" />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="card">
                        <label><input type="checkbox" name="all" id="all"/>All Activities</label><br>
                        <label><input type="checkbox" name="goals" id="goals"ondragover=""/>On Goal Name Change</label><br>
                        <label><input type="checkbox"name="data" id="data" />On Goal Data Change</label><br>
                      </div>
                    </div>
                  </div>
                </form>
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
