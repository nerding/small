<?php

  class BranchDB { 
    private static $db;

    public static function init() {
      $nArgs = func_get_args();

      self::$db = new mysqli(
        Config::get('db.host'),
        Config::get('db.user'),
        Config::get('db.password'),
        Config::get('db.database')
      );
    }

    public static function query($queryString) {
      // debugging purposes
      //echo $queryString;

      if ($stmt = self::$db->prepare($queryString)) {
        $stmt->execute();
        $out = $stmt->result_metadata();
        $stmt->close();

        return $out;
      }

      echo "BranchDB::query\n";
      echo self::$db->error;
      return false;
    }

    public static function queryStmt($queryString) {
      //echo $queryString;

      if ($stmt = self::$db->prepare($queryString)) {
        $stmt->execute();
        return $stmt;
      }

      echo "BranchDB::queryStmt\n";
      echo self::$db->error;
      return false;
    }

  }
  BranchDB::init();

?>