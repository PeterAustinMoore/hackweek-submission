<?php
$e = getenv('client_id');
$url = "https://peter.demo.socrata.com/oauth/authorize?client_id=".$e."&response_type=code&redirect_uri=https://goo.gl/qztQcM";
header('Location: '.$url);
?>
