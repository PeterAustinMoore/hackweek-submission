<?php
include("../superadmin/settings.php");

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $programs_db.'?$where=programid='."'1'");
$result = curl_exec($ch);
$programs = json_decode($result, true);
$program = $programs[0]["program"];

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
  $deleted = $_POST["delete"];
  $added = $_POST["add"];
  $del_ids = array();
  foreach($deleted as $td => $prog) {
    array_push($del_ids, $td);
  }
  $add_ids = array();
  foreach($added as $ta => $prog_a) {
    array_push($add_ids, $ta);
  }
  foreach($u as $k => $v) {
    $update = date("c");
    if(in_array($k, $del_ids) && !in_array($k, $add_ids)) {
      array_push($data, array("id"=>$k, "email"=>$v, "programid"=>$d[$k], "updated"=>$update,"isdeleted"=>"true"));
    } else {
      array_push($data, array("id"=>$k, "email"=>$v, "programid"=>$d[$k], "updated"=>$update,"isdeleted"=>"false"));
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
$ch = curl_init();
$url = $metric_db.'?$where=canedit='."'true'";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
$result = curl_exec($ch);
$data = json_decode($result, true);
if(count($data) > 0) {
  $CanEdit = true;
} else {
  $CanEdit = false;
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
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="../../assets/img/favicon.png">
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
        var count = 0;
        $.ajax({
              url: "https://peter.demo.socrata.com/resource/mnj2-zafk.json?$select=max(id)",
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
                var markup = "<tr><td><input type='checkbox' name='deleted["+count.toString()+"]' /><td>"+count.toString()+"</td><td><input type='text' name='users["+count.toString()+"]' value='"+email+"' /></td><td><select id='' name='programs["+count.toString()+"]'><?php echo $program_selection_basic ?></select></td></tr>";
                $("table tbody").append(markup);
            }
          });

          window.addEventListener('input', function (e) {
            if(e.target.id === "program") {
              var i = e.target.name;
              var s = i.split("[")[1];
              s = s.replace("]","");
              var id = "changed["+s+"]";
              document.getElementById(id).checked = true;
            }
          }, false);
        });
        </script>
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
                  <li class="active">
                      <a href="#">
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
                    <?php if($CanEdit){
                      echo "<li><a href='metrics.php'><i class='ti-view-list-alt'></i><p>Metrics</p></a></li>";
                      }
                    ?>
                    <li>
                        <a href="methods.php">
                            <i class="ti-email"></i>
                            <p>Methodology</p>
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
                              <input id="email" placeholder="User Email" />
                              <input type="button" class="add-row" value="Add User">
                              <h3 id="errors"></h3>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                        <form name="s" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
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
                              <div class="content table-responsive table-full-width">
                                  <table id="users" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Remove</th>
                                            <th>User ID</th>
                                            <th>User</th>
                                            <th>Program</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      $ch = curl_init();
                                      $url = 'https://peter.demo.socrata.com/resource/mnj2-zafk.json?$order=id%20asc&$where=id='."'1'"."%20and%20isdeleted="."'false'";
                                      if(!isset($_POST["programs"])) {
                                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($ch, CURLOPT_URL, $url);
                                        $r = curl_exec($ch);
                                        $data = json_decode($r, true);
                                        $tbody = "";
                                        for ($i = 0; $i < count($data); $i++) {
                                          $tbody.="<tr><td><input type='checkbox' /></td><td>".$data[$i]["id"]."</td><td><input input data-lpignore='true' style='width:250px' name='[".$data[$i]["id"]."]' type='text' value='".$data[$i]["email"]."' /></td><td>".$program."</td></tr>";
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
