<?php  include("../../settings.php"); ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Paper Dashboard by Creative Tim</title>

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
      function fastpivot(a){"use strict";var t={};if("string"!=typeof a&&a.length>0){var l=Object.keys(a[0]),n={};l.forEach(function(a){n[a]={},n[a]._labels=[],n[a]._labelsdata=[],n[a]._data={}}),a.forEach(function(a,t){l.forEach(function(t){var l=a[t];n[t]._data[l]=(n[t]._data[l]||0)+1,n[t]._labels[l]=null})}),l.forEach(function(a){for(var t in n[a]._data)n[a]._labelsdata.push(n[a]._data[t]);n[a]._labels=Object.keys(n[a]._labels)}),t=n}return t}

      var url = "https://peter.demo.socrata.com/resource/rwxv-uhfm.json?$where=department=%27Community%20Development%27&$order=measure";
      var data = {};
      $.ajax({
            url: url,
            dataType: 'json',
            async: false,
            success: function(output) {
              data = output;
            }
          });
          console.log(data);
          var same_set = [];
          var pivoted = []
        for(var i = 0; i < data.length - 1; i++) {
          var next = i + 1;
          if(data[i]["measure"] === data[next]["measure"]) {
            same_set.push(data[i]);
          } else {
            same_set.push(data[i]);
            var pivoteddata = fastpivot(same_set);
            var d = pivoteddata["year_quarter"]._labels.map(function(e, i){
                return [e, pivoteddata["value"]._labels[i]];
            });
            pivoted.push({"id":pivoteddata["measureid"]._labels[0],"measure":pivoteddata["measure"]._labels[0],"data":d});
            same_set = [];
          }
        }
        var table = "";
        for(i in pivoted) {
          table += "<tr>";
          table += "<td>" + pivoted[i]["id"] + "</td>";
          table += "<td>" + pivoted[i]["measure"] + "</td>";
          for(j in pivoted[i]["data"]) {
            if(j < 5) {
              table += "<td>" + pivoted[i]["data"][j][1] + "</td>"
            }
          }
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
                <li class="active">
                    <a href="#">
                        <i class="ti-view-list-alt"></i>
                        <p>Goals</p>
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
                    <a class="navbar-brand" href="#">Table List</a>
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
                          <input type="submit" value="Submit" />
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="header">
                                <h4 class="title">Striped Table</h4>
                                <p class="category">Here is a subtitle for this table</p>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table class="table table-striped">
                                  <thead>
                                      <th>Goal ID</th>
                                    <th>Goal Name</th>
                                    <th>Target</th>
                                    <th>Q1</th>
                                    <th>Q2</th>
                                    <th>Q3</th>
                                    <th>Q4</th>
                                  </thead>
                                  <tbody id="tb">

                                  </tbody>
                                </table>

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
