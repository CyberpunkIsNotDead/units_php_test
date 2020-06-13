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

      $result = $this->performQuery($query);
      
      if ($result === false) {
        throw new Exception("Can't perform query");
      }
      return $result;
  }


  // INSERT INTO person (first_name, last_name) VALUES ('John', 'Doe');

  public function insertInto(
    $table,
    $columns,
    $values
  ) {
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
    
    $result = $this->performQuery($query);
    
    if ($result === false) {
      throw new Exception("Can't perform query");
    }
    return $result;
  }


  // UPDATE table
  // SET column1 = expression1,
  //     column2 = expression2,
  //     ...
  // [WHERE conditions]
  // [ORDER BY expression [ ASC | DESC ]]
  // [LIMIT number_rows];

  public function updateById($table, $columns, $values, $id) {
    $data_str_templates = array();

    foreach($columns as $column) {
      $entry_str = "$column = ?";
      array_push($data_str_templates, $entry_str);
    }

    $set_str_template = implode(", ", $data_str_templates);

    $statement = "UPDATE $table SET $set_str_template WHERE id = ?";

    $prepared = $this->connection->prepare($statement);


    // $stmt = $mysqli->prepare("SELECT * FROM myTable WHERE name = ? AND age = ?");
    // $stmt->bind_param("si", $_POST['name'], $_POST['age']);
    // $stmt->execute();
    // //fetching result would go here, but will be covered later
    // $stmt->close();

    // $prepared = $this->connection->prepare($statement)

    return $statement;
    // $query = "UPDATE $table SET ";
  }

  // function __destruct() {}
}

?> 
