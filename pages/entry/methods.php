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
    curl_setopt($ch, CURLOPT_URL, $metric_db);
    $result = curl_exec($ch);
    $metrics=json_decode($result, true);

    if(isset($_POST["metric"])) { echo "";
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_URL, $staging_data_db);
    $result = curl_exec($ch);
    $staging_data=json_decode($result, true);
  ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Data Entry</title>

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

    <!-- JQUERY BABY -->
    <script
      src="https://code.jquery.com/jquery-3.2.1.min.js"
      integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
      crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
      integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
      crossorigin="anonymous"></script>

    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="../../assets/css/themify-icons.css" rel="stylesheet">
    <script>
    $(document).ready(function(){
      var metrics = <?php echo json_encode($metrics); ?>;
      var prod_data = <?php echo json_encode($staging_data); ?>;
      var data_for_table = {};

      var metric_lookup = {};
      for(g in metrics) {
        metric_lookup[metrics[g]['id']] = {"title":metrics[g]["metric_title"], "target":metrics[g]["target"], "department":metrics[g]["department"]};
        data_for_table[metrics[g]["id"]] = {"title":metrics[g]["metric_title"], "target":metrics[g]["target"], "department":metrics[g]["department"]};
        for(p in prod_data) {
          if(prod_data[p]["metric_id"] == metrics[g]["id"]) {
            var quarter = "quarter"+prod_data[p]["period"];
            var fy = prod_data[p]["fiscal_year"];
            var id = prod_data[p]["id"]
            data_for_table[prod_data[p]["metric_id"]] = Object.assign({"data":{[fy]:{}}, "title":metric_lookup[prod_data[p]["metric_id"]]["title"], "department":metric_lookup[prod_data[p]["metric_id"]]["department"], "target":metric_lookup[prod_data[p]["metric_id"]]["target"]}, data_for_table[prod_data[p]["metric_id"]]);
            data_for_table[prod_data[p]["metric_id"]]["data"][fy] = Object.assign({"id":id, [quarter]: prod_data[p]["value"]},data_for_table[prod_data[p]["metric_id"]]["data"][fy]);
          }
        }
      }

      var table = "";
      for(key in data_for_table) {
        table += "<tr>";
        table += "<td>" + key + "</td>";
        table += "<td><input type='text' name='metric["+key+"]' autocomplete='off' value='" + data_for_table[key]["title"] + "' /></td>";
        table += "<td><textarea name='methods["+key+"]' rows='7' cols='50'></textarea></td>";
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
                    <a href="data.php">
                        <i class="ti-view-list-alt"></i>
                        <p>Data</p>
                    </a>
                </li>
                <li class="active">
                    <a href="#">
                        <i class="ti-view-list-alt"></i>
                        <p>Methodology</p>
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
                    <a class="navbar-brand" href="#">Metric Data</a>
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
                          <input type="submit" value="Save" />
													<input type="submit" value="Submit for Approval" />
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
																			<th>Metric ID</th>
																			<th>Metric Name</th>
																			<th style="text-align:center">Methodology</th>
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
