<?php
$code=$_GET["code"];
$s=getenv("client_id");
$url="https://peter.demo.socrata.com/oauth/access_token?client_id=M45QwgboTFjF2aPVUUfYs5mtt&client_secret=".$s."&grant_type=authorization_code&redirect_uri=https://performance-ingress.herokuapp.com/success.php&code=".$code;


function getAccessCode($u) {
  $c = curl_init();
  echo "<script>console.log(`".$u."`)</script>";
  $fields=array("empty"=>"");
  //set the url, number of POST vars, POST data
  curl_setopt($c,CURLOPT_URL, $u);
  curl_setopt($c,CURLOPT_POST,1);
  curl_setopt($c,CURLOPT_POSTFIELDS,$fields);
  $result = curl_exec($c);
  curl_close($c);
  $d = json_decode($result, true);
  return $d;
}

$access_token = getAccessCode($url);
var_dump($access_token);

function getUserInfo($u, $a) {
  $c = curl_init();
  echo "<script>console.log(`".$u."`)</script>";
  //set the url, number of POST vars, POST data
  curl_setopt($c,CURLOPT_URL, $u);
  curl_setopt($c,CURLOPT_HTTPHEADER,array('Authorization: OAuth '.$a));
  $result = curl_exec($c);
  curl_close($c);
  $d = json_decode($result, true);
  return $d;
}
$user = "https://peter.demo.socrata.com/users/current.json";

$info = getUserInfo($user, $access_token);
var_dump($info);
?>
