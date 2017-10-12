<?php
$code=$_GET["code"];
$s=getenv("client_id");
$url="https://peter.demo.socrata.com/oauth/access_token?client_id=M45QwgboTFjF2aPVUUfYs5mtt&client_secret=".$s."&grant_type=authorization_code&redirect_uri=https://performance-ingress.herokuapp.com/success.php&code=".$code;

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_POST,1);
curl_setopt($ch,CURLOPT_POSTFIELDS,"");
$result = curl_exec($ch);
$access_token = json_decode($result);
echo $access_token["access_token"];

?>
