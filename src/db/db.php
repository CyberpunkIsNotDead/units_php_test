<?php

include_once(dirname(__DIR__) . "/../config/db_config.php");


class MariaDB {
  
  protected $host = null;
  protected $port = null;
  protected $user = null;
  protected $pass = null;
  protected $dbname = null;

  public $connection = null;
  public $error = null;

  
  function __construct() {
    $this->host = DB_HOST;
    $this->port = DB_PORT;
    $this->user = DB_USER;
    $this->pass = DB_PASS;
    $this->dbname = DB_NAME;
  }


  public function connect() {
    $connection = new mysqli(
      $this->host,
      $this->user,
      $this->pass,
      $this->dbname,
      $this->port
    );

    if ($connection->connect_errno) {
      $this->error = $connection->connect_error;
      throw new Exception("Connection failed: " . $connection->connect_error);
    } else {
      $this->connection = $connection;
    }
  }


  public function disconnect() {
    $this->connection->close();
    $this->connection = null;
  }


  protected function performQuery($query) {
    return $this->connection->query($query);
  }


  protected function execPrepStatement($statement, $types_str, $params) {
    if (!$prepared = $this->connection->prepare($statement)) {
      return false;
    }

    if (!$prepared->bind_param($types_str, ...$params)) {
      return false;
    }
    
    if (!$prepared->execute()) {
      return false;
    }

    $prepared->close();
    return true;
  }


  public function selectFrom($table, $columns = "*", $where = null,
  $order_by = null, $order_direction = null, $limit = null) {
      $query = "SELECT $columns FROM $table " .
        ($where ? "WHERE $where " : null) .
        ($order_by ? "ORDER BY $order_by " : null) .
        ($order_direction ? " $order_direction " : null );
        ($limit ? "LIMIT $limit" : null);

      $result = $this->performQuery($query);
      
      if ($result === false) {
        throw new Exception("Can't perform query");
      }
      return $result;
  }

  
  public function insertInto($table, $columns, $values) {
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
    
    $result = $this->performQuery($query);
    
    if ($result === false) {
      throw new Exception("Can't perform query");
    }
    return $result;
  }


  public function updateById($table, $columns, $values, $id) {
    $data_str_templates = array();
    $types_str = "";

    foreach($columns as $column => $type) {
      $entry_str = "$column = ?";
      array_push($data_str_templates, $entry_str);
      $types_str .= $type;
    }
    array_push($values, $id); // to unpack as params
    $types_str .= "i"; // for id

    $set_str_template = implode(", ", $data_str_templates);

    $statement = "UPDATE $table SET $set_str_template WHERE id = ?";

    if (!$this->execPrepStatement($statement, $types_str, $values)) {
      return false;
    }

    return true;
  }
}

?> 
