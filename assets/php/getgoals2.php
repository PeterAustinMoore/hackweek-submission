<?php
include "../../pages/superadmin/settings.php";
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($ch, CURLOPT_URL, $goal_db);
$result = curl_exec($ch);
$data=json_decode($result, true);
echo json_encode($data);
?>
