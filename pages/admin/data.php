<?php
    include("../superadmin/settings.php");
    $username = getenv("username");
    $password = getenv("password");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_URL, $settings_db);
    $result = curl_exec($ch);
    $settings=json_decode($result, true);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_URL, $goal_db);
    $result = curl_exec($ch);
    $goals=json_decode($result, true);

    $c = count($goals);
    $dept_mapping = array();
    for($i = 0; $i < $c; $i ++) {
      $dept_mapping[$goals[$i]["id"]] = $goals[$i]["department"];
    }

    if(isset($_POST["goal"])) {
      $g = $_POST["goal"];
      $fy = $_POST["fiscal_year"];
      foreach($g as $kk => $vv) {
        $d = $_POST[$kk];
        $data = array();
        foreach($d as $k => $v) {
          $update = date("c");
          $id = $kk."-".$k."-".$fy;
          $goal_id = $kk;
          $goal_title = $vv;
          $department = $dept_mapping[$kk];
          $period = $k;
          $fiscal_year = $fy;
          $date_key = 'quarter'.$k;
          $date = $settings[0][$date_key]."/".$fy;
          $value = $v;
          array_push($data, array("id"=>$id, "goal_id"=>$goal_id, "goal_title"=>$goal_title, "department"=> $department, "period" => $period, "fiscal_year" => $fiscal_year, "date" => $date, "value" => $value, "updated"=>$update));
          }
      }

      # Production Table Update
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $production_data_db);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      $r = curl_exec($ch);
      $res = json_decode($r, true);

      if($res["Errors"] == 0) {
        echo "<script>alert('Data updated successfully!')</script>";
      }


      # Department Staging Table Update
#      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
#      curl_setopt($ch, CURLOPT_URL, $production_data_db);
#      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
#      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
#      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
#      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
#      curl_exec($ch);


#      $activity_data = array("activity_type"=>"Data Update","date"=>$update,"user"=>$username,"message"=>json_encode($data));
#      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
#      curl_setopt($ch, CURLOPT_URL, $activity_db);
#      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
#      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
#      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($activity_data));
#      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
#      curl_exec($ch);
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_URL, $production_data_db);
    $result = curl_exec($ch);
    $prod_data=json_decode($result, true);
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
      <link href="../../assets/css/themify-icons.css" rel="stylesheet">
      <script>
      $(document).ready(function(){
        var goals = <?php echo json_encode($goals); ?>;
        var goal_lookup = {};
        for(g in goals) {
          goal_lookup[goals[g]['id']] = {"title":goals[g]["goal_title"], "target":goals[g]["target"], "department":goals[g]["department"]};
        }

        var prod_data = <?php echo json_encode($prod_data); ?>;
        var data_for_table = {};
        for(p in prod_data) {
          var quarter = "quarter"+prod_data[p]["period"];
          var fy = prod_data[p]["fiscal_year"];
          var id = prod_data[p]["id"]
          data_for_table[prod_data[p]["goal_id"]] = Object.assign({[fy]:{}}, data_for_table[prod_data[p]["goal_id"]]);
          data_for_table[prod_data[p]["goal_id"]][fy] = Object.assign({"title":goal_lookup[prod_data[p]["goal_id"]]["title"], "department":goal_lookup[prod_data[p]["goal_id"]]["department"], "target":goal_lookup[prod_data[p]["goal_id"]]["target"], "id":id,  [quarter]: prod_data[p]["value"]},data_for_table[prod_data[p]["goal_id"]][fy]);
        }
        var table = "";
        for(key in data_for_table) {
          for(year in data_for_table[key]) {
            if(year == 2017) {
              table += "<tr>";
              table += "<td>" + key + "</td>";
              table += "<td><input type='text' name='goal["+key+"]' autocomplete='off' value='" + data_for_table[key][year]["title"] + "' /></td>";
              table += "<td>"+ data_for_table[key][year]["target"]+"</td>";
              table += "<td><input name='"+key+"[1]' type='text' value="+ data_for_table[key][year]["quarter1"]+" /></td>";
              table += "<td><input name='"+key+"[2]' type='text' value="+ data_for_table[key][year]["quarter2"]+" /></td>";
              table += "<td><input name='"+key+"[3]' type='text' value="+ data_for_table[key][year]["quarter3"]+" /></td>";
              table += "<td><input name='"+key+"[4]' type='text' value="+ data_for_table[key][year]["quarter4"]+" /></td>";
              table += "</tr>";
            }
          }
        }
        document.getElementById("tb").innerHTML = table;
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
                  <li>
                      <a href="users.php">
                          <i class="ti-user"></i>
                          <p>Users</p>
                      </a>
                  </li>
                    <li>
                        <a href="departments.php">
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
                    <li class="active">
                        <a href="#">
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
                      <a class="navbar-brand" href="#">Manage and Approve</a>
                  </div>
                  <div class="collapse navbar-collapse">
                      <ul class="nav navbar-nav navbar-right">
                      </ul>
                  </div>
              </div>
          </nav>


          <div class="content">
            <form name="departments" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                          <div class="content">
                            <input type="submit" name="reject" value="Reject with note" />
                            <input type="submit" name="approve" value="Approve" />
                            <a target="_blank" style="float:right" href="<?php echo str_replace(".json","",str_replace("resource","d",$production_data_db)); ?>">View Dataset</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="card">
                              <div class="content table-responsive table-full-width">
                                  <table class="table table-striped">
                                      <thead>
                                        <tr>
                                          <th colspan="3"></th>
                                          <th colspan="4" style="text-align:center" id="year"><input type="text" name="fiscal_year" readonly value="2017" /></th>
                                        </tr>
                                        <tr>
                                          <th>Goal ID</th>
                                          <th>Goal Name</th>
                                          <th>Target</th>
                                          <th style="text-align:center">Q1</th>
                                          <th style="text-align:center">Q2</th>
                                          <th style="text-align:center">Q3</th>
                                          <th style="text-align:center">Q4</th>
                                        </tr>
                                      </thead>
                                      <tbody id="tb">
                                      </tbody>
                                  </table>

                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </form>
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
