<?php
include("../superadmin/settings.php");

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $programs_db);
$result = curl_exec($ch);
$programs = json_decode($result, true);

$program_selection_basic = "";
foreach($programs as $program) {
  $program_selection_basic .= "<option value='".$program["id"]."'>".$program["program"]."</option>";
}

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
  $d = $_POST["programs"];
  $admin = $_POST["isAdmin"];
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
      array_push($data, array("id"=>$k, "email"=>$v, "programid"=>$d[$k], "updated"=>$update,"isdeleted"=>"true"));
    } else {
      if($admin[$k]) {
        array_push($data, array("id"=>$k, "email"=>$v, "programid"=>$d[$k], "updated"=>$update,"isdeleted"=>"false","isadmin"=>"true"));
      } else {
        array_push($data, array("id"=>$k, "email"=>$v, "programid"=>$d[$k], "updated"=>$update,"isdeleted"=>"false", "isadmin" => "false"));
      }
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
      <script src="../../assets/js/getgoals.js"></script>
      <script type="text/javascript">
      $(document).ready(function(){
        var data = goalGetter.get();
        var count = 0;
        $.ajax({
              url: "<?php echo $users_db; ?>?$select=max(id)",
              dataType: 'json',
              async: false,
              success: function(data) {
                count = parseInt(data[0]["max_id"]);
              }
            });
            var emails = <?php echo json_encode($users_array) ?>;
            email_list = [];
            for(i in emails) {
              email_list.push(emails[i]["email"]);
            }
            $( "#email" ).autocomplete({
                source: email_list
              });
          $(".add-row").click(function(){
            var email = $("#email").val();
            if($.inArray(email, email_list) === -1) {
              document.getElementById("errors").innerHTML = "Email address not list of acceptable users please see <a target='_blank' href='<?php echo $base_url ?>/admin/users'>Users Administration</a>";
            } else {
                count = count + 1;
                var markup = "<tr><td><input type='checkbox' name='deleted["+count.toString()+"]' /><td>"+count.toString()+"</td><td><input type='text' name='users["+count.toString()+"]' value='"+email+"' /></td><td><select id='program' name='programs["+count.toString()+"]'><?php echo $program_selection_basic ?></select></td></tr>";
                $("table tbody").append(markup);
            }
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
        .ui-menu {
          background: white;
          border: 1px solid black;
        }
        .ui-menu-item {
          list-style-type: none;
          margin:5px;
          width:100%;
        }
        .ui-menu-item:hover {
          cursor: pointer;
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
                  <li class="active">
                      <a href="#">
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
                      <a class="navbar-brand" href="#">Users</a>
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
                            <form autocomplete="off">
                              <div class="ui-widget">
                                <h4>Users must be added to Socrata and have Socrata accounts prior to being added here</h4>
                                <input id="email">
                                <input type="button" class="add-row" value="Add Users">
                                <h3 id="errors"><h3>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                    <form name="users" method="POST"  autocomplete="off" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                              <div class="content">
                                <input type="submit" />
                                <button id="see_removed">View Removed</button>
                                <button id="see_current">Return</button>
                                <a target="_blank" style="float:right" href="https://peter.demo.socrata.com/dataset/Admin-Emails/mnj2-zafk">View Dataset</a>
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
                                            <th>User ID</th>
                                            <th>User</th>
                                            <th>Program</th>
                                            <th>Is Program Admin?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $ch = curl_init();
                                      $url = $users_db.'?$order=id%20asc&$where=isdeleted='."'false'";
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        $r = curl_exec($ch);
                                        $data = json_decode($r, true);
                                        $tbody = "";
                                        $max = count($data);
                                        $program_lookup = array();
                                        for ($i = 0; $i < $max; $i++) {
                                          $program_selection = "";
                                          foreach($programs as $program) {
                                            array_push($program_lookup, array($program["id"]=>$program["program"]));
                                            if($program["id"] == $data[$i]["programid"]) {
                                              $program_selection.="<option selected value='".$program["id"]."'>".$program["program"]."</option>";
                                            } else {
                                              $program_selection.="<option value='".$program["id"]."'>".$program["program"]."</option>";
                                            }
                                          }
                                          $dept = "<select id='program' name='programs[".$data[$i]["id"]."]'>";
                                          if($data[$i]["isadmin"] == "true") {
                                            $tbody.="<tr><td><input name='delete[".$data[$i]["id"]."]' type='checkbox' /></td><td>".$data[$i]["id"]."</td><td><input name='users[".$data[$i]["id"]."]' readonly type='text' value='".$data[$i]["email"]."' /></td><td>".$dept.$program_selection."</select></td><td><input type='checkbox' name='isAdmin[".$data[$i]["id"]."]' checked /></td></tr>";
                                          } else {
                                            $tbody.="<tr><td><input name='delete[".$data[$i]["id"]."]' type='checkbox' /></td><td>".$data[$i]["id"]."</td><td><input name='users[".$data[$i]["id"]."]' readonly type='text' value='".$data[$i]["email"]."' /></td><td>".$dept.$program_selection."</select></td><td><input type='checkbox' name='isAdmin[".$data[$i]["id"]."]' /></td></tr>";
                                          }
                                        }
                                        echo $tbody;
                                       ?>
                                    </tbody>
                                </table>
                                <table id="users_removed" class="table table-striped">
                                  <thead>
                                    <tr>
                                        <th>Add Back</th>
                                        <th>User ID</th>
                                        <th>User</th>
                                        <th>Program</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php
                                    $ch = curl_init();
                                    $url = $users_db.'?$order=id%20asc&$where=isdeleted='."'true'";

                                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                      curl_setopt($ch, CURLOPT_URL, $url);
                                      $r = curl_exec($ch);
                                      $data = json_decode($r, true);
                                      $tbody = "";
                                      $program_lookup = array();
                                      $max = count($data);
                                      for ($i = 0; $i < $max; $i++) {
                                        $program_selection = "";
                                        foreach($programs as $program) {
                                          array_push($program_lookup, array($program["id"]=>$program["program"]));
                                          if($program["id"] == $data[$i]["programid"]) {
                                            $program_selection.="<option selected value='".$program["id"]."'>".$program["program"]."</option>";
                                          } else {
                                            $program_selection.="<option value='".$program["id"]."'>".$program["program"]."</option>";
                                          }
                                        }
                                        $dept = "<select id='program' name='programs[".$data[$i]["id"]."]'>";
                                        $tbody.="<tr><td><input name='delete[".$data[$i]["id"]."]' type='hidden' /><input name='add[".$data[$i]["id"]."]' type='checkbox' /></td><td>".$data[$i]["id"]."</td><td><input name='users[".$data[$i]["id"]."]' disabled type='text' value='".$data[$i]["email"]."' /></td><td>".$dept.$program_selection."</select></td></tr>";
                                      }
                                      echo $tbody;

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
