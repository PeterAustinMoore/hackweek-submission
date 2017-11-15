<?php
    include("../superadmin/settings.php");
    $username = getenv("username");
    $password = getenv("password");

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_URL, $goal_db);
    $result = curl_exec($ch);
    $goals=json_decode($result, true);
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
        var data = <?php echo json_encode($goals); ?>;

        var table = "";
        for(d in data) {
          table += "<tr>";
          table += "<td>" + data[d]["id"] + "</td>";
          table += "<td><input type='text' name='goal["+data[d]["id"]+"]' value='" + data[d]["goal_title"] + "' /></td>";
          table += "<td>"+ data[d]["target"]+"</td>";
          table += "<td><input type='text' /></td>";
          table += "<td><input type='text' /></td>";
          table += "<td><input type='text' /></td>";
          table += "<td><input type='text' /></td>";
          table += "</tr>";
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
                      <a class="navbar-brand" href="#">Data</a>
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
                                          <th colspan="4" style="text-align:center" id="year">2017</th>
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
