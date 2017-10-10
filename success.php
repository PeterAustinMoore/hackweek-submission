<?php
echo "Hello";
$url = "https://www.google.com";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
$h = curl_exec($ch);
var_dump($h);
?>
