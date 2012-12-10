<?php
  //include("includes/session.php");

  class User {

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
      if ($num_args >= 4 && func_get_arg(3) != '') {
        $name = func_get_arg(3);
      }
      if ($num_args == 5 && func_get_arg(4) != '') {
        $biography = func_get_arg(4);
      }

      // generate the unique salt for this user
      // based on the current time and their username
      // fun stuff...
      $salt = hash('sha256', uniqid(mt_rand(), true) . Config::get('site.salt') . strtolower($username));

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
      $out = BranchDB::query($query);

      if ($out != false) {
        return true;
      }
      else {
        return false;
      }
    }

    public static function validatePassword($username, $password) {
      $query = "select password from users where username = \"$username\";";

      $stmt = BranchDB::queryStmt($query);
      $stmt->bind_result($hashword);
      $stmt->fetch();
      $stmt->close();

      // debugging
      //echo '<br >' . $hashword . "<br >";

      // salt is first 64 chars of stored password
      $salt = substr($hashword, 0, 64);

      $hash = self::gimmieHash($salt, $password);

      if ($hash == $hashword) {
        return true;
      }

      return false;
    }

    public static function login($username, $password) {
      if (!self::validatePassword($username, $password)) {
        return false;
      } 
      
      $user = self::find_by_username($username);


      if ($user == false) {
        return false;
      }

      Session::start();
      Session::set('id', $user->id);
      Session::set('username', $user->username);

      if (isset($user->name)) {
        Session::set('name', $user->name);
      }
      
      return true;
    }

    public static function logout() {
      Session::end();
    }

    public static function isLoggedIn() {
      return Session::hasSession();
    }

    public static function changePassword($username, $password) {
      $user = self::find_by_id($username);

      $salt = hash('sha256', uniqid(mt_rand(), true) . Config::get('site.salt') . strtolower($username));
      $hash = self::gimmieHash($salt, $password);

      $query = "update users set password = \"$hash\" where id = {$user->id};";
      BranchDB::query($query);
    }

    public static function current() {
      if (!self::isLoggedIn()) {
        return false;
      }

      return self::find_by_id(Session::get('id'));
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

    public static function all() {
      return self::find();
    }

    public static function find_by_id($id) {
      return self::find("where id=$id")[0];
    }

    public static function find_by_username($inUser) {
      return self::find("where username=\"$inUser\"")[0];
    }

    private static function find() {
      $num_args = func_get_args();
      $query = 'select id,username,email,name,biography from users';
      $query .= $num_args == 1 ? ' ' . func_get_arg(0) : '';
      $query .= ';';

      $out = array();
      $stmt = BranchDB::queryStmt($query);

      $stmt->bind_result($id, $username, $email, $name, $biography);
      while ($stmt->fetch()) {
        array_push($out, new DBUser($id, $username, $email, $name, $biography));
      }

      return $out;

    }
  }


  class DBUser {
    public $id;
    public $username;
    public $email;
    public $name;
    public $biography;

    /*
      Create a new DBUser, which is a row on the database expressed as
      an object.

      Should only be called using the Users class find_by functions

      new DBUser([id [, username [, email [, full name [, biography]]]]])
    */
    public function DBUser() {
      $numArgs = func_get_args();

      if ($numArgs >= 1) {
        $this->id = func_get_arg(0);
      }
      if ($numArgs >= 2) {
        $this->username = func_get_arg(1);
      }
      if ($numArgs >= 3) {
        $this->email = func_get_arg(2);
      }
      if ($numArgs >= 4) {
        $this->name = func_get_arg(3);
      }
      if ($numArgs == 5) {
        $this->biography = func_get_arg(4);
      }
    }

    public function getName() {
      return isset($this->name) && $this->name != "" ? $this->name : $this->username;
    }

    public function printName() {
      echo $this->getName();
    }

    public function gravatarURL() {
      $url = 'http://gravatar.com/avatar/';
      $url .= md5($this->email);
      $url .= '?s=50&d=identicon';

      echo $url;
    }
  }

?>