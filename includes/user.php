<?php
  //require_once('branch_db.php');  
  error_reporting(E_ALL);
  ini_set('display_errors','On');


  class Users {
    private static $config;
    private static $db;


    public static function init() {
      $configObj = new Config();
      self::$db = new BranchDB($configObj);
      self::$config = $configObj->getConfig();
    }

    /*
      Create a new user.
  
      User.new($username, $password, $email [, $name [, $biography]])
    */
    public static function create($username, $password, $email) {
      $num_args = func_get_args();

      // default these to null
      $name = NULL;
      $biography = NULL;

      // if they're passed in, and if they're not empty,
      // use them
      if ($num_args >= 4 && func_get_args(3) != '') {
        $name = func_get_arg(3);
      }
      if ($num_args == 5 && func_get_args(4) != '') {
        $biography = func_get_arg(4);
      }

      // generate the unique salt for this user
      // based on the current time and their username
      // fun stuff...
      $salt = hash('sha256', uniqid(mt_rand(), true) . self::$config['salt'] . strtolower($username));

      // create a hash based on the salt and password.
      // this is a method because eventually we'll grab the
      // user's salt when attempting to login (the salt is the
      // first 64 characters of their stored password).
      $hash = self::gimmieHash($salt, $password);

      // start the query. We know that these three are required
      $query = 'insert into users (username, password, email';

      // if we have the other two non required fields
      if ($name != NULL) {
        $query .= ', name';
      }
      if ($biography != NULL) {
        $query .= ', biography';
      }

      // again, these are the required fields
      $query .= ") values ('$username', '$hash', '$email'";

      if ($name != NULL) {
        $query .= ", '$name'";
      }
      if ($biography != NULL) {
        $query .= ", '$biography'";
      }

      // close up the query's string.
      $query .= ');';

      // the BranchDB class automatically prepares our query,
      // so we don't have to do anything to make it work.
      self::$db->query($query);

    }

    private static function gimmieHash($salt, $password) {
      $hash = $salt . $password;
      for ($i = 0; $i < 100000; $i++) {
        $hash = hash('sha256', $hash);
      }
      $hash = $salt . $hash;

      return $hash;
    }

    /*
      Find by functions
    */

    function find_by_id($id) {

    }
  }
  Users::init();


  class DBUser {

  }

?>