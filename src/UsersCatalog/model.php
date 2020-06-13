<?php

require_once "../db/db.php";

$db = new MariaDB;

$db->connect();

print_r($db->connection);
echo "<br>";
echo "<br>";

$result = $db->selectFrom("users");

$rows = $result->fetch_all(MYSQLI_ASSOC);
print_r($rows);

echo "<br>";
echo "<br>";

foreach($rows as $row) {
  print_r($row);
  echo "<br>";
}

$db->disconnect();

?>
