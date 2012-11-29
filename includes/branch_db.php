<?php

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  class BranchDB { 
    private static $db;

    public static function init() {
      $nArgs = func_get_args();

      self::$db = new mysqli(
        Config::get('db_host'),
        Config::get('db_user'),
        Config::get('db_password'),
        Config::get('db_database')
      );
    }

    public static function query($queryString) {
      // debugging purposes
      echo $queryString;

      if ($stmt = self::$db->prepare($queryString)) {
        $stmt->execute();
        $out = $stmt->result_metadata();
        $stmt->close();

        return $out;
      }

      echo "DEATH TO ALL";
      return false;
    }

    public static function queryStmt($queryString) {
      echo $queryString;

      if ($stmt = self::$db->prepare($queryString)) {
        $stmt->execute();
        return $stmt;
      }

      echo "DEATH TO ALL";
      return false;
    }

  }
  BranchDB::init();

?>