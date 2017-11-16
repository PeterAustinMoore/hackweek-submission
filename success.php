<?php
include("pages/superadmin/settings.php");
$code=$_GET["code"];
$s=getenv("client_id");
$url="https://peter.demo.socrata.com/oauth/access_token?client_id=M45QwgboTFjF2aPVUUfYs5mtt&client_secret=".$s."&grant_type=authorization_code&redirect_uri=https://performance-ingress.herokuapp.com/success.php&code=".$code;

# Get Users
$ch = curl_init();
$url = $users_db.'?$order=userid%20asc&$where=isdeleted='."'false'";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$r = curl_exec($ch);
$users = json_decode($r, true);
$users_mapping = array();
$total = count($users);
for($i = 0; $i < $total; $i ++) {
  $users_mapping[$users[$i]["email"]] = array("isDeptAdmin" => $users[$i]["isDeptAdmin"], "department" => $users[$i]["departmentid"]);
}
function getAccessCode($u) {
  $c = curl_init();
  echo "<script>console.log(`".$u."`)</script>";
  $fields=array("empty"=>"");
  //set the url, number of POST vars, POST data
  curl_setopt($c,CURLOPT_URL, $u);
  curl_setopt($c,CURLOPT_POST,1);
  curl_setopt($c,CURLOPT_POSTFIELDS,$fields);
  curl_setopt($c, CURLOPT_HEADER, true);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($c);
  $header_size = curl_getinfo($c,CURLINFO_HEADER_SIZE);
  $header = substr(substr($result, 0, $header_size),34,3);
  $body = substr($result, $header_size);
  if($header == 400) {
    header("Location: index.php");
  }
  curl_close($c);
  $d = json_decode($body, true);
  return $d["access_token"];
}
if(!isset($_COOKIE["access_token"])) {
  $access_token = getAccessCode($url);
  setcookie("access_token", $access_token, time()+3600);
} else {
  $access_token = $_COOKIE["access_token"];
}

function getUserInfo($u, $a) {
  $c = curl_init();
  echo "<script>console.log(`".$u."`)</script>";
  //set the url, number of POST vars, POST data
  curl_setopt($c,CURLOPT_URL, $u);
  curl_setopt($c,CURLOPT_HTTPHEADER,array('Authorization: OAuth '.$a));
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  $result = curl_exec($c);
  curl_close($c);
  $d = json_decode($result, true);
  return $d;
}
$user = "https://peter.demo.socrata.com/users/current.json";
$info = getUserInfo($user, $access_token);
$email = $info["email"];
setcookie("user",$email,time() + 3600);
if($info["roleName"] === "administrator") {
  header("Location: pages/admin/settings.php");
} else {
  if(in_array($email, $user_mapping)){
    setcookie("department", $user_mapping[$email]["department"], time() + 3600);
    if($user_mapping[$email]["isDeptAdmin"] === "true") {
      header("Location: pages/departments/users.php");
    } else {
      header("Location: pages/entry/dataentry.php");
    }
  } else {
    echo "<h1>Please request access from administrator</h1>";
  }
}
?>
