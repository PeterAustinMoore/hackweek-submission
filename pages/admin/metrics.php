<?php
    include("../superadmin/settings.php");
    $username = getenv("username");
    $password = getenv("password");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $programs_db);
    $result = curl_exec($ch);
    $programs_raw = json_decode($result, true);

    $programs = json_encode($programs_raw);

    if(isset($_POST["metric"])) {
      $data = array();
      $g = $_POST["metric"];
      $u = $_POST["unit"];
      $t = $_POST["timeframe"];
      $prog = $_POST["program"];
      $editable = $_POST["CanEdit"];
      $editable_ids = array();
      foreach($editable as $te => $prog) {
        array_push($editable_ids, $te);
      }
      foreach($g as $k => $v) {
        $update = date("c");
        if(in_array($k, $editable_ids)) {
          array_push($data, array("id"=>$k, "metric_title"=>$v, "timeframe" => $t[$k], "unit" => $u[$k], "program" => $prog[$k], "canedit"=>"true", "updated"=>$update));
        } else {
          array_push($data, array("id"=>$k, "metric_title"=>$v, "timeframe" => $t[$k], "unit" => $u[$k], "program" => $prog[$k], "canedit"=>"false", "updated"=>$update));
        }
      }
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $metric_db);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_exec($ch);


      $activity_data = array("activity_type"=>"Metric Update","date"=>$update,"user"=>$username,"message"=>json_encode($data));
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_URL, $activity_db);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($activity_data));
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_exec($ch);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_URL, $metric_db);
    $result = curl_exec($ch);
    $metrics=json_decode($result, true);
    $metric_count = count($metrics);
  ?>
  <html>
  <head>
  <!-- JQUERY BABY -->
  <script
    src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
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
      <link href="../../assets/css/themify-icons.css" rel="stylesheet">>
      <script type="text/javascript">
      $(document).ready(function(){
        var timeframe = ["quarterly", "annual", "monthly"];
        var data = <?php echo json_encode($metrics); ?>;
        var programs = <?php echo $programs ?>;
        var prog_mapping = {};
        for(d in programs) {
          prog_mapping[programs[d]["id"]] = programs[d]["program"];
        }
        var table = "";
        for(d in data) {
          table += "<tr>";
          table += "<td>" + data[d]["id"] + "</td>";
          table += "<td><input type='text' name='metric["+data[d]["id"]+"]' value='" + data[d]["metric_title"] + "' /></td>";
          table += "<td><select name='program["+data[d]["id"]+"]'>";
          for(dd in prog_mapping){
            if(dd == data[d]["program"]) {
              table += "<option selected value=" + data[d]["program"] + ">" + prog_mapping[data[d]["program"]] + "</option>";
            } else {
              table += "<option value=" + dd + ">" + prog_mapping[dd] + "</option>";
            }
          }
          table += "<td><select name='timeframe["+data[d]["id"]+"]'>"
          for(t in timeframe) {
            if(data[d]["timeframe"] === timeframe[t]) {
              table += "<option selected value='"+ timeframe[t] + "'>" + timeframe[t] + "</option>";
            } else {
              table += "<option value='"+ timeframe[t] + "'>" + timeframe[t] + "</option>";
            }
          }
          table += "</select></td>";
          table += "<td>" + data[d]["category"] + "</td>";
          table += "<td><input type='text' name='unit["+data[d]["id"]+"]' value='" + data[d]["unit"] + "' /></td>";
          if(data[d]["canedit"] == "true"){
            table += "<td><input type='checkbox' name='CanEdit["+data[d]["id"]+"]' checked /></td>";
          } else {
            table += "<td><input type='checkbox' name='CanEdit["+data[d]["id"]+"]' /></td>";
          }
          table += "<td><a href='data.php?metric="+data[d]["id"]+"'>Manage and Approve Data</td>";
          table += "</tr>";
        }
        document.getElementById("tb").innerHTML = table;


        var current_metric_count = <?php echo (int)$metric_count + 1 ?>;

        // New Metric Layout
        var newMetric = current_metric_count + "  ";
        newMetric += "<input type='text' id='newName' value='' placeholder='Metric Title' />"
        newMetric += "<select id='newMetric'>"
        for(dd in prog_mapping) {
          newMetric += "<option value=" + dd + ">"+ prog_mapping[dd]+"</option>";
        }
        newMetric += "</select><select id='newTimeframe'>";
        for(t in timeframe) {
          newMetric += "<option value='"+ timeframe[t]+"'>"+timeframe[t]+"</option>";
        }
        newMetric += "</select>";
        newMetric += "<input id ='newUnit' type='text' placeholder='Unit' value='' />"
        document.getElementById("newMetric").innerHTML = newMetric;

        // Create New Metric
        $("#newMetricButton").click(function(e) {
          e.preventDefault();
          var newName = $("#newName").val();
          var newMetric = $("#newMetric").val();
          var newTimeframe = $("#newTimeframe").val();
          var newUnit = $("#newUnit").val();
          //$("table tbody").append(markup);
        });
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
                    <li>
                        <a href="programs.php">
                            <i class="ti-view-list-alt"></i>
                            <p>Programs</p>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#">
                            <i class="ti-view-list-alt"></i>
                            <p>Metrics</p>
                        </a>
                    </li>
                    <li>
                        <a href="data.php">
                            <i class="ti-check-box"></i>
                            <p>Manage and Approve</p>
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
                      <a class="navbar-brand" href="#">Metrics</a>
                  </div>
                  <div class="collapse navbar-collapse">
                      <ul class="nav navbar-nav navbar-right">
                      </ul>
                  </div>
              </div>
          </nav>


          <div class="content">
            <form name="programs" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
              <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                          <div class="content">
                            <input name="allowChange" type="checkbox" /> Allow Programs to Alter All Metric Names and Targets
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                              <div class="content">
                                <div id="newMetric"></div>
                                <input id="newMetricButton" type="submit" value="Create Metric" />
                              </div>
                            </div>
                          </div>
                        </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                          <div class="content">
                            <input type="submit" value="Update Metrics" />
                            <a target="_blank" style="float:right" href="<?php echo str_replace(".json","",str_replace("resource","d",$metric_db)); ?>">View Dataset</a>
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
                                        <th>Metric ID</th>
                                        <th>Metric Name</th>
                                        <th>Program</th>
                                        <th>Timeframe</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Program Admin Can Edit</th>
                                        <th></th>
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
