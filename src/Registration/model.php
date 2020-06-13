<?php

require_once "../db/db.php";

$db = new MariaDB;

$db->connect();

print_r($db->connection);
echo "<br>";
echo "<br>";
$db->insertInto(
  "users",
  "username, email",
  "'user', 'email@example.com'"
);

print_r($db->connection);

$db->disconnect();

?>
