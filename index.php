<html>
</head>
<style>
html, body {
  background-image: url('assets/img/background/background.jpg');
  background-size: 100% 100%;
  height: 100%;
  width: 100%;
  margin:0;
  text-align: center;
}
#wrapper {
  display: block;
}
.b {
  display: inline-block;
  padding-left: 50px;
  padding-right: 50px;
  height: 150px;
  color:white;
  text-align: center;
  line-height: 150px;

  -moz-border-radius: 200px / 70px;
  -webkit-border-radius: 200px / 70px;
  border-radius: 200px / 70px;
}
.b:hover{
  font-size: 20px;
}
h1 {
  padding-top: 10%;
  color:white;
}
</style>
</head>
<body>
<h1>VIEW SITE AS A...</h1>
<div id="wrapper">
  <a href="pages/admin/settings.php"><div class="b">PERFORMANCE GURU</div></a>
  <a href="pages/departments/data.php"><div class="b">PROGRAM LEADER</div></a>
  <a href="pages/entry/data.php"><div class="b">DATA OWNER/WIZARD</div></a>
</div>
</body>
</html>

<?php
//$url = "Location: https://peter.demo.socrata.com/oauth/authorize?client_id=M45QwgboTFjF2aPVUUfYs5mtt&response_type=code&redirect_uri=https://performance-ingress.herokuapp.com/success.php";
//header($url);
//include("grid.php");
?>
