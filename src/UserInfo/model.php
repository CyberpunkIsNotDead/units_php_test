<?php

require_once "../db/db.php";

$db = new MariaDB;

$db->connect();
echo "<br>";
echo "<br>";

// trim and validate user input
$columns = array(
  "userinfo"
);

$values = array(
  "Lorem ipsum dolor sit amet' select * from users"
);

$data_arr = array(
  "userinfo" => array(
    "s" => "Lorem ipsum dolor sit amet' select * from users"
  ),
  "age" => array(
    "i" => 24
  ) 
);

echo $db->updateById(
  "users",
  $columns,
  $values,
  1
);

$db->disconnect();

?>
