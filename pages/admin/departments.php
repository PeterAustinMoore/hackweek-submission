<?php
include("../superadmin/settings.php");

$ch = curl_init();

$username = getenv("username");
$password = getenv("password");

if(isset($_POST["department"])) {
  $data = array();
  $d = $_POST["department"];
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
  foreach($d as $k => $v) {
    $update = date("c");
    if(in_array($k, $del_ids) && !in_array($k, $add_ids)) {
      array_push($data, array("departmentid"=>$k, "department"=>$v, "updated"=>$update,"isdeleted"=>"true"));
    } else {
      array_push($data, array("departmentid"=>$k, "department"=>$v, "updated"=>$update,"isdeleted"=>"false"));
    }
  }
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $departments_db);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
  curl_exec($ch);
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
    <script type="text/javascript">
    $(document).ready(function(){
        $(".add-row").click(function(){
          $.getJSON('<?php echo $departments_db ?>?$select=max(departmentid)', function(data){
            var name = $("#department").val();
            var current_id = parseInt(data[0]["max_departmentid"]) + 1;
            var markup = "<tr><td><input name='delete["+current_id.toString()+"]' type='checkbox' /><td>"+current_id.toString()+"</td><td><input name=department["+current_id.toString()+"] type='text' value='"+name+"' /></td></tr>";
            $("table tbody").append(markup);
          });
        });
        $("#see_removed").click(function(e){
          e.preventDefault();
          $("#users").toggle();
          $("#users_removed").toggle();
          $("#see_removed").toggle();
          $("#see_current").toggle();
        });
        $("#see_current").click(function(e){
          e.preventDefault();
          $("#users").toggle();
          $("#users_removed").toggle();
          $("#see_current").toggle();
          $("#see_removed").toggle();
        });
      });
      </script>
      <style>
      #users_removed {
        display:none;
      }
      #see_current {
        display:none;
      }
      </style>
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
                    <a href="users.php">
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
                      <a href="goals.php">
                          <i class="ti-view-list-alt"></i>
                          <p>Goals</p>
                      </a>
                  </li>
                  <li>
                      <a href="data.php">
                          <i class="ti-check-box"></i>
                          <p>Manage and Approve</p>
                      </a>
                  </li>
                  <li>
                      <a href="narratives.php">
                          <i class="ti-view-list-alt"></i>
                          <p>Narratives</p>
                      </a>
                  </li>
                  <li>
                      <a href="activity_log.php">
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
                          <div class="content">
                            <form>
                                <input type="text" id="department" placeholder="Department">
                                <input type="button" class="add-row" value="Add Department">
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <form autocomplete="off" name="departments" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                      <div class="row">
                          <div class="col-md-12">
                              <div class="card">
                                <div class="content">
                                  <input type="submit" value="Update" />
                                  <button id="see_removed">View Removed</button>
                                  <button id="see_current">Return</button>
                                  <a style="float:right" href="https://peter.demo.socrata.com/dataset/Admin-Emails/6z67-xud9">View Dataset</a>
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
                                    $url = $departments_db.'?$order=departmentid%20asc&$where=isdeleted='."'false'";
                                    if(!isset($_POST["departments"])) {
                                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                      curl_setopt($ch, CURLOPT_URL, $url);
                                      $r = curl_exec($ch);
                                      $data = json_decode($r, true);
                                      $tbody = "";
                                      for ($i = 0; $i < count($data); $i++) {
                                        $tbody.="<tr><td><input name='delete[".$data[$i]["departmentid"]."]' type='checkbox' /></td><td id='deptId'>".$data[$i]["departmentid"]."</td><td><input name='department[".$data[$i]["departmentid"]."]' type='text' value='".$data[$i]["department"]."' /></td></tr>";
                                      }
                                      echo $tbody;
                                      }
                                     ?>
                                  </tbody>
                              </table>
                              <table id="users_removed" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Add Back</th>
                                        <th>Department ID</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $ch = curl_init();
                                  $url = $departments_db.'?$order=departmentid%20asc&$where=isdeleted='."'true'";
                                  if(!isset($_POST["departments"])) {
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    curl_setopt($ch, CURLOPT_URL, $url);
                                    $r = curl_exec($ch);
                                    $data = json_decode($r, true);
                                    $tbody = "";
                                    for ($i = 0; $i < count($data); $i++) {
                                      $tbody.="<tr><td><input name=delete[".$data[$i]["departmentid"]."] type='hidden' value='deleted' /> <input name='add[".$data[$i]["departmentid"]."]' type='checkbox' /></td><td>".$data[$i]["departmentid"]."</td><td><input name='department[".$data[$i]["departmentid"]."]' type='text' value='".$data[$i]["department"]."' /></td></tr>";
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
<script src="../../assets/js/bootstrap.min.js" type="text/javascript"></script>

<!-- Paper Dashboard Core javascript and methods for Demo purpose -->
<script src="../../assets/js/paper-dashboard.js"></script>

<!-- Paper Dashboard DEMO methods, don't include it in your project! -->
<script src="../../assets/js/demo.js"></script>
</html>
