<?php
$e = getenv('client_id');
echo $e;
$url = "https://peter.demo.socrata.com/oauth/authorize?client_id=".$e."&response_type=code&redirect_uri=https://performance-ingress.herokuapp.com/success.php";
header('Location: '.$url);
?>
