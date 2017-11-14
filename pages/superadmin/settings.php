<?php
class settings extends SQLite3
{
    function __construct()
    {
        $this->open('../../assets/db/settings.db');
    }
}

$db = new settings();

$result = $db->query('SELECT * FROM defaults');

while($res = $result->fetchArray(SQLITE3_ASSOC)){
  switch ($res['pagename']) {
    case "users_db":
      $users_db = $res["url"];
    case "departments_db":
      $departments_db = $res["url"];
    case "entry_db":
      $entry_db = $res["url"];
    case "activity_db":
      $activity_db = $res["url"];
  }
}
$base_url = "https://".$res["domain"];
$socrata_users_url = "https://api.us.socrata.com/api/catalog/v1/users?domain=".$res["domain"];
?>
