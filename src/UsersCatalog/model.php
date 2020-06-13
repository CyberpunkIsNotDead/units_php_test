<?php

require "../db/db.php";

$db = new MariaDB;

$db->connect();

print_r($db->connection);
echo "<br>";
echo "<br>";
print_r($db->selectFrom("users"));

$db->disconnect();

?>
