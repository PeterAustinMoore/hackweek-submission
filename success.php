<?php
$code=$_GET["code"];
$s=getenv("client_id");
$url="https://peter.demo.socrata.com/oauth/access_token?client_id=M45QwgboTFjF2aPVUUfYs5mtt&client_secret=".$s."&grant_type=authorization_code&redirect_uri=https://performance-ingress.herokuapp.com/success.php&code=".urlencode($code);


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
  $d = json_decode($result);
  $a = $d->access_token;
  return $a;
}

$access_token = getAccessCode($url);
echo $access_token;

function getUserInfo($c, $u, $a) {
  return $a;
}
?>
