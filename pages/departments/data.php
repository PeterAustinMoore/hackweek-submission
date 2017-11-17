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
    <link href="../../assets/css/themify-icons.css" rel="stylesheet">

    <?php include("../superadmin/settings.php");
        $username = getenv("username");
        $password = getenv("password");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_URL, $metric_db);
        $result = curl_exec($ch);
        $metrics=json_decode($result, true);

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

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_URL, $settings_db);
        $result = curl_exec($ch);
        $settings=json_decode($result, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_URL, $metric_db);
        $result = curl_exec($ch);
        $metrics=json_decode($result, true);

        $c = count($metrics);
        $prog_mapping = array();
        for($i = 0; $i < $c; $i ++) {
          $prog_mapping[$metrics[$i]["id"]] = $metrics[$i]["program"];
        }

        if(isset($_POST["metric"])) {
          $g = $_POST["metric"];
          $fy = $_POST["fiscal_year"];
          $data = array();
          foreach($g as $kk => $vv) {
            $d = $_POST[$kk];
            foreach($d as $k => $v) {
              if($v) {
                $update = date("c");
                $id = $kk."-".$k."-".$fy;
                $metric_id = $kk;
                $metric_title = $vv;
                $program = $prog_mapping[$kk];
                $period = $k;
                $fiscal_year = $fy;
                $date_key = 'quarter'.$k;
                $date = $settings[0][$date_key]."/".$fy;
                $value = str_replace("undefined","",$v);
                array_push($data, array("id"=>$id, "metric_id"=>$metric_id, "metric_title"=>$metric_title, "program"=> $program, "period" => $period, "fiscal_year" => $fiscal_year, "date" => $date, "value" => $value, "updated"=>$update));
                }
              }
          }
          # Program Staging Table Update
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_URL, $program_data_db);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
          curl_exec($ch);

          # Staging Table Update
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_URL, $staging_data_db);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

          $r = curl_exec($ch);
          $res = json_decode($r, true);
          if($res["Errors"] == 0) {
            echo '<script>$(document).ready(function() {var div = document.getElementById("alerter");
                  div.style.opacity = "1";
                  setTimeout(function(){ div.style.opacity = "0"; }, 3000); });</script>';
          }
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_URL, $staging_data_db);
        $result = curl_exec($ch);
        $prog_data=json_decode($result, true);
    ?>
    <script>
    $(document).ready(function(){
      var metrics = <?php echo json_encode($metrics); ?>;
      var prod_data = <?php echo json_encode($prog_data); ?>;
      var data_for_table = {};

      var metric_lookup = {};
      for(g in metrics) {
        metric_lookup[metrics[g]['id']] = {"title":metrics[g]["metric_title"], "target":metrics[g]["target"], "program":metrics[g]["program"]};
        data_for_table[metrics[g]["id"]] = {"title":metrics[g]["metric_title"], "target":metrics[g]["target"], "program":metrics[g]["program"]};
        for(p in prod_data) {
          if(prod_data[p]["metric_id"] == metrics[g]["id"]) {
            var quarter = "quarter"+prod_data[p]["period"];
            var fy = prod_data[p]["fiscal_year"];
            var id = prod_data[p]["id"]
            data_for_table[prod_data[p]["metric_id"]] = Object.assign({"data":{[fy]:{}}, "title":metric_lookup[prod_data[p]["metric_id"]]["title"], "program":metric_lookup[prod_data[p]["metric_id"]]["program"], "target":metric_lookup[prod_data[p]["metric_id"]]["target"]}, data_for_table[prod_data[p]["metric_id"]]);
            data_for_table[prod_data[p]["metric_id"]]["data"][fy] = Object.assign({"id":id, [quarter]: prod_data[p]["value"]},data_for_table[prod_data[p]["metric_id"]]["data"][fy]);
          }
        }
      }

      var table = "";
      for(key in data_for_table) {
        table += "<tr>";
        table += "<td>" + key + "</td>";
        table += "<td><input type='text' name='metric["+key+"]' autocomplete='off' value='" + data_for_table[key]["title"] + "' /></td>";
        table += "<td>"+ data_for_table[key]["target"]+"</td>";
        if("data" in data_for_table[key]) {
          for(year in data_for_table[key]["data"]) {
            if(year == 2017) {
              table += "<td><input name='"+key+"[1]' type='text' value="+ ((data_for_table[key]["data"][year]["quarter1"] !== undefined) ? data_for_table[key]["data"][year]["quarter1"] : "''") +" /></td>";
              table += "<td><input name='"+key+"[2]' type='text' value="+ ((data_for_table[key]["data"][year]["quarter2"] !== undefined) ? data_for_table[key]["data"][year]["quarter2"] : "''")+" /></td>";
              table += "<td><input name='"+key+"[3]' type='text' value="+ ((data_for_table[key]["data"][year]["quarter3"] !== undefined) ? data_for_table[key]["data"][year]["quarter3"] : "''")+" /></td>";
              table += "<td><input name='"+key+"[4]' type='text' value="+ ((data_for_table[key]["data"][year]["quarter4"] !== undefined) ? data_for_table[key]["data"][year]["quarter4"] : "''")+" /></td>";
            }
          }
        } else {
          table += "<td><input name='"+key+"[1]' type='text' value='' /></td>";
          table += "<td><input name='"+key+"[2]' type='text' value='' /></td>";
          table += "<td><input name='"+key+"[3]' type='text' value='' /></td>";
          table += "<td><input name='"+key+"[4]' type='text' value='' /></td>";
        }
        table += "</tr>";
      }
      document.getElementById("tb").innerHTML = table;

      $("#reject").click(function(e){
        e.preventDefault();
        document.getElementById("rejectionNote").style.display = "block";
      });
      $("#rejection").submit(function(e) {
        e.preventDefault();
        document.getElementById("rejectionNote").style.display = "none";
        var div = document.getElementById("rejected");
              div.style.opacity = "1";
              setTimeout(function(){ div.style.opacity = "0"; }, 3000);
      });
    });
    </script>
    <style>
    #timeframe {
      height:45px;
    }
    .tf {
      width:33%;
      height:100%;
      display: inline-block;
      text-align: center;
    }
    input[type="text"] {
      width:100%;
    }
    .alert {
      color: white;
      opacity: 0;
      transition: opacity 0.6s;
      width:100%;
      z-index: 100;
      right:0;
      width:150px;
      position:absolute;
    }

    .alert.success {background-color: #4CAF50;}
    .alert.reject {background-color: #f44336;}

    #rejectionNote {
      display:none;
      background: white;
      opacity: 1;
      transition: opacity 0.6s;
      width:50%;
      z-index: 100;
      left:10%;
      top:15%;
      padding:80px;
      position:absolute;
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
                    <a href="users.php">
                        <i class="ti-user"></i>
                        <p>Users</p>
                    </a>
                </li>
                  <li class="active">
                      <a href="#">
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
      <div class="alert success" id="alerter">
          Approved
      </div>
      <div class="alert reject" id="rejected">
          Rejected
      </div>
      <div id="rejectionNote">
        <form id="rejection">
          <h4>Reason for rejection</h4>
          <textarea name='rejected' rows='7' cols='50'></textarea>
          <input type="submit" />
        </form>
      </div>
    <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">Data Approval</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                    </ul>
                </div>
            </div>
        </nav>



            <div class="content">
                <div class="container-fluid">
                  <form name="programs" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                              <div class="content">
                                <input type="submit" id="reject" name="reject" value="Reject with note" />
                                <input type="submit" name="approve" value="Approve" />
                              </div>
                            </div>
                          </div>
                        </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="header">
                                    <h4 class="title">Submitted Data</h4>
                                </div>
                                <div class="content table-responsive table-full-width">
                                    <table class="table table-striped">
                                      <thead>
                                        <tr>
                                          <th colspan="3"></th>
                                          <th colspan="4" style="text-align:center" id="year"><input type="text" name="fiscal_year" readonly value="2017" /></th>
                                        </tr>
                                        <tr>
                                          <th>Metric ID</th>
                                          <th>Metric Name</th>
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
                                  </form>
                                </div>
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
