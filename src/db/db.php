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

  protected function checkIfConnected() {
    if ($this->connection) {
      return true;
    } else {
      return false;
    }
  }

  protected function performQuery($query) {
    $result = $this->connection->query($query);
    return $result;
  }

  protected function getQueryResult($query) {
    if ($this->checkIfConnected()) {
      return $this->performQuery($query);
    } else {
      throw new Exception("You are not connected to database!");
      return false;
    }
  }


  // SELECT isbn, title, 
  // CONCAT(name_first, ' ', name_last) AS author
  // FROM books
  // JOIN authors USING (author_id)
  // WHERE name_last = 'Dostoevsky'
  // ORDER BY title ASC
  // LIMIT 5;

  public function selectFrom(
    $table,
    $columns = "*",
    $where = null,
    $order_by = null,
    $order_direction = null,
    $limit = null
  ) {
      $query = "SELECT $columns FROM $table " .
        ($where ? "WHERE $where " : null) .
        ($order_by ? "ORDER BY $order_by " : null) .
        ($order_direction ? " $order_direction " : null );
        ($limit ? "LIMIT $limit" : null);

    $result;
    try {
      return $this->getQueryResult($query);
    } catch (Exception $e) {
      throw new Exception("An error occured: $e");
      return false;
    }
  }


  // INSERT INTO person (first_name, last_name) VALUES ('John', 'Doe');

  public function insertInto(
    $table,
    $columns,
    $values
  ) {
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
    try {
      return $this->getQueryResult($query);
    } catch (Exception $e) {
      throw new Exception("An error occured: $e");
      return false;
    }
  }

  // function __destruct() {}
}

?> 
