<?php

require_once "../db/db.php";

$db = new MariaDB;

$db->connect();
echo "<br>";
echo "<br>";

$columns = array(
  "userinfo" => "s",
);

$values = array(
  "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ac urna non est scelerisque pharetra. Vivamus ullamcorper rhoncus tincidunt. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Pellentesque quis ante et ex mattis vestibulum eget sit amet nisi. Orci varius natoque penatibus et magnis dis. ",
);

if ($db->updateById("users", $columns, $values, 2)) {
  echo "updated successfully";
} else {
  echo "an error occured";
}

$db->disconnect();

?>
