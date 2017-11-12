<?php
$base = "https://peter.demo.socrata.com";
$dashboards_url = 'https://peter.demo.socrata.com/api/stat/v1/dashboards.json';
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($ch, CURLOPT_URL, $dashboards_url);
$result = curl_exec($ch);
$dashboards=json_decode($result, true);
$data=array();
foreach($dashboards as $dashboard) {
  $dash_name = $dashboard["name"];
  $dash_id = $dashboard["id"];
  $dash_url_api = $base."/api/stat/v1/dashboards/".$dash_id.".json";
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL, $dash_url_api);
  curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
  $result=curl_exec($ch);
  $dashboard_data=json_decode($result, true);
  $categories=$dashboard_data["categories"];
  foreach($categories as $category){
    $cat_id=$category["id"];
    $goals=$category["goals"];
    foreach($goals as $goal){
      array_push($data, array("id"=>$goal["id"],
        "name"=>$goal["name"],
        "dashboard"=>$dash_name,
        "dashboard_url"=>$base."/stat/goals/".$dash_id,
        "url"=>$base."/stat/goals/".$dash_id."/".$cat_id."/".$goal["id"]
      ));
    }
  }
}
echo json_encode($data);
?>
