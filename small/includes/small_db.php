<?php
  /* Small's Database Abstraction
      A simple interface for playing with the database, because
      isn't that what it's all about?
  */

  class SmallDB { 
    private static $db;

    // see config.php for more information on why this is here.
    public static function init() {
      $nArgs = func_get_args();

      self::$db = new mysqli(
        Config::get('db.host'),
        Config::get('db.user'),
        Config::get('db.password'),
        Config::get('db.database')
      );
    }

    // make a query on the database. Can't be manipulated
    public static function query($queryString) {
      echo $queryString; // debug

      // try preparing the statement
      if ($stmt = self::$db->prepare($queryString)) {
        $stmt->execute();
        $out = $stmt->result_metadata();
        $stmt->close();

        // query got through, return metadata.
        return $out;
      }

      // query couldn't be prepared. Print the error.
      // This might need to be handled better in the future.
      echo "SmallDB::query\n";
      echo self::$db->error;
      return false;
    }

    // make a query on the database, return a mysqli_stmt object
    // that can be manipulated by the user
    public static function queryStmt($queryString) {
      //echo $queryString; // debug

      // try to run the query
      if ($stmt = self::$db->prepare($queryString)) {
        // if it'll work, pass the executed statement out.
        $stmt->execute();
        return $stmt;
      }

      // if it didn't work, print the error.
      echo "SmallDB::queryStmt\n";
      echo self::$db->error;
      return false;
    }

  }

  // see config.php for more information on why this is here
  SmallDB::init();

?>